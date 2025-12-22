<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Controllers\SupportCaseController;
use App\Http\Controllers\Auth\GoogleLoginController;
use App\Http\Controllers\ChatStreamController;
use App\Models\Sitio;

// Ruta para servir archivos de audio del chat (sin necesidad de symlink)
Route::get('/storage/chat-audio/{filename}', function ($filename) {
    $path = storage_path('app/public/chat-audio/' . $filename);
    
    if (!file_exists($path)) {
        abort(404);
    }
    
    $file = file_get_contents($path);
    $type = mime_content_type($path);
    
    return response($file, 200)
        ->header('Content-Type', $type)
        ->header('Content-Length', filesize($path))
        ->header('Cache-Control', 'public, max-age=3600');
})->where('filename', '.*')->name('chat.audio');

// Rutas de autenticación con Google
Route::get('/auth/google', [GoogleLoginController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleLoginController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Página de inicio
Route::get('/', function () {
    $sitios = Sitio::with('tags')
        ->where('en_linea', true)
        ->orderBy('created_at', 'desc')
        ->take(6) // Show only 6 most recent active sites
        ->get();
    
    return view('welcome', compact('sitios'));
})->name('home');

// Dashboard
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Sistema para tu negocio - Página pública
Route::get('/sistema-para-tu-negocio', function () {
    return view('sistema-para-tu-negocio');
})->name('sistema.negocio');

// Formulario para crear un nuevo ticket
Route::get('/tickets/crear', function () {
    return view('test-form');
})->name('tickets.create');

// Ruta para la plataforma educativa
Route::get('/educacion', [App\Http\Controllers\EducationController::class, 'index'])->name('education.index');

// Ruta para el salón de belleza
Route::get('/salon', [App\Http\Controllers\SalonController::class, 'index'])->name('salon.index');

// Rutas para eliminación de datos
Route::get('/data-deletion', [App\Http\Controllers\DataDeletionController::class, 'show'])
    ->name('data-deletion.show');

Route::delete('/data-deletion', [App\Http\Controllers\DataDeletionController::class, 'destroy'])
    ->middleware('auth')
    ->name('user.data.delete');

// Rutas para páginas legales
Route::get('/privacidad', function () {
    return view('privacy');
})->name('privacy');

Route::get('/terminos', function () {
    return view('terms');
})->name('terms');

// Webhook para recibir datos del formulario
Route::post('/webhook/tickets', function (\Illuminate\Http\Request $request) {
    // Validar los datos recibidos
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'image' => 'nullable|image|max:2048', // 2MB Max
    ]);

    // Procesar la imagen si se subió
    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('case-images', 'public');
    }

    // Datos para el webhook
    $webhookData = [
        'name' => $validated['name'],
        'email' => $validated['email'],
        'title' => $validated['title'],
        'description' => $validated['description'],
        'status' => 'open',
        'image' => $imagePath,
        'created_at' => now()->toDateTimeString(),
    ];

    // Aquí iría el código para enviar los datos a tu webhook externo
    // Por ejemplo, usando Guzzle o similar
    // $response = Http::post('URL_DEL_WEBHOOK', $webhookData);

    // Por ahora, solo retornamos los datos como JSON
    return response()->json([
        'success' => true,
        'message' => 'Ticket recibido correctamente',
        'data' => $webhookData
    ]);
})->name('webhook.tickets');

// Ruta para WALEE Chat (fuera de Filament)
Route::get('/walee', function () {
    return view('walee-chat');
})->middleware(['auth'])->name('walee');

// Limpiar chat
Route::post('/walee-chat/clear', function () {
    \App\Models\ChatMessage::where('user_id', auth()->id())->delete();
    return response()->json(['success' => true]);
})->middleware(['auth'])->name('walee.chat.clear');

// Ruta para WALEE Dashboard - Solo admin autenticado
Route::get('/walee-dashboard', function () {
    return view('walee-dashboard');
})->middleware(['auth'])->name('walee.dashboard');

// Tickets de soporte
Route::get('/walee-tickets', function () {
    return view('walee-tickets');
})->middleware(['auth'])->name('walee.tickets');

// Tareas
Route::get('/tareas', function () {
    return view('walee-tareas');
})->middleware(['auth'])->name('walee.tareas');

// Calendario
Route::get('/walee-calendario', function () {
    return view('walee-calendario');
})->middleware(['auth'])->name('walee.calendario');

// Rutas para Citas
Route::post('/citas', function (\Illuminate\Http\Request $request) {
    try {
        $cita = new \App\Models\Cita();
        $cita->titulo = $request->input('titulo');
        $cita->cliente_id = $request->input('cliente_id');
        $cita->fecha_inicio = \Carbon\Carbon::parse($request->input('fecha_inicio'));
        $cita->fecha_fin = $request->input('fecha_fin') ? \Carbon\Carbon::parse($request->input('fecha_fin')) : null;
        $cita->ubicacion = $request->input('ubicacion');
        $cita->descripcion = $request->input('descripcion');
        $cita->estado = $request->input('estado', 'programada');
        $cita->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Cita creada correctamente',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('citas.store');

Route::put('/citas/{id}', function (\Illuminate\Http\Request $request, $id) {
    try {
        $cita = \App\Models\Cita::findOrFail($id);
        $cita->titulo = $request->input('titulo');
        $cita->cliente_id = $request->input('cliente_id');
        $cita->fecha_inicio = \Carbon\Carbon::parse($request->input('fecha_inicio'));
        $cita->fecha_fin = $request->input('fecha_fin') ? \Carbon\Carbon::parse($request->input('fecha_fin')) : null;
        $cita->ubicacion = $request->input('ubicacion');
        $cita->descripcion = $request->input('descripcion');
        $cita->estado = $request->input('estado', 'programada');
        $cita->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Cita actualizada correctamente',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('citas.update');

Route::delete('/citas/{id}', function ($id) {
    try {
        $cita = \App\Models\Cita::findOrFail($id);
        $cita->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Cita eliminada correctamente',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('citas.delete');

Route::post('/walee-tickets', function (\Illuminate\Http\Request $request) {
    try {
        $imagePath = null;
        
        if ($request->hasFile('imagen')) {
            $imagePath = $request->file('imagen')->store('tickets', 'public');
        }
        
        $ticket = \App\Models\Ticket::create([
            'user_id' => auth()->id(),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'telefono' => $request->input('telefono'),
            'website' => $request->input('website'),
            'asunto' => $request->input('asunto'),
            'mensaje' => $request->input('mensaje'),
            'imagen' => $imagePath,
            'estado' => 'enviado',
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Ticket enviado correctamente',
            'ticket_id' => $ticket->id,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('walee.tickets.store');

Route::post('/walee-tickets/{id}/estado', function (\Illuminate\Http\Request $request, $id) {
    try {
        $ticket = \App\Models\Ticket::findOrFail($id);
        $nuevoEstado = $request->input('estado');
        
        $ticket->update([
            'estado' => $nuevoEstado,
        ]);
        
        // Si se marca como recibido, enviar webhook
        if ($nuevoEstado === 'recibido') {
            $nombre = $ticket->name ?? $ticket->user?->name ?? 'Cliente';
            $imagenUrl = $ticket->imagen ? asset('storage/' . $ticket->imagen) : null;
            
            \Illuminate\Support\Facades\Http::timeout(10)->post(
                'https://n8n.srv1137974.hstgr.cloud/webhook/8b796dcc-396f-492c-b81e-30c7ed35f006',
                [
                    // Datos del ticket
                    'ticket_id' => $ticket->id,
                    'estado' => 'recibido',
                    'fecha' => $ticket->created_at->format('d/m/Y H:i'),
                    
                    // Datos del cliente
                    'nombre' => $nombre,
                    'email' => $ticket->email,
                    'telefono' => $ticket->telefono,
                    'website' => $ticket->website,
                    
                    // Contenido del ticket
                    'asunto' => $ticket->asunto,
                    'mensaje' => $ticket->mensaje,
                    'imagen' => $imagenUrl,
                    
                    // Datos para el email
                    'email_to' => $ticket->email,
                    'email_subject' => "Ticket Recibido: {$ticket->asunto}",
                    'email_body' => "Hola {$nombre},\n\nRecibimos su ticket con el asunto: \"{$ticket->asunto}\"\n\nLe avisaremos cuando esté resuelto.\n\nSaludos,\nWeb Solutions",
                    'email_from' => 'websolutionscrnow@gmail.com',
                    'email_from_name' => 'Web Solutions',
                ]
            );
        }
        
        // Si se marca como resuelto, enviar webhook
        if ($nuevoEstado === 'resuelto') {
            $nombre = $ticket->name ?? $ticket->user?->name ?? 'Cliente';
            $imagenUrl = $ticket->imagen ? asset('storage/' . $ticket->imagen) : null;
            
            \Illuminate\Support\Facades\Http::timeout(10)->post(
                'https://n8n.srv1137974.hstgr.cloud/webhook/2109bf94-761d-4e3c-8417-11bcf36b5b1e',
                [
                    // Datos del ticket
                    'ticket_id' => $ticket->id,
                    'estado' => 'resuelto',
                    'fecha' => $ticket->created_at->format('d/m/Y H:i'),
                    
                    // Datos del cliente
                    'nombre' => $nombre,
                    'email' => $ticket->email,
                    'telefono' => $ticket->telefono,
                    'website' => $ticket->website,
                    
                    // Contenido del ticket
                    'asunto' => $ticket->asunto,
                    'mensaje' => $ticket->mensaje,
                    'imagen' => $imagenUrl,
                    
                    // Datos para el email
                    'email_to' => $ticket->email,
                    'email_subject' => "Ticket Resuelto: {$ticket->asunto}",
                    'email_body' => "Hola {$nombre},\n\nSu ticket con el asunto: \"{$ticket->asunto}\" ha sido resuelto.\n\nGracias por contactarnos.\n\nSaludos,\nWeb Solutions",
                    'email_from' => 'websolutionscrnow@gmail.com',
                    'email_from_name' => 'Web Solutions',
                ]
            );
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('walee.tickets.estado');

// Ruta para WALEE Clientes - Selector de opciones
Route::get('/walee-clientes', function () {
    return view('walee-clientes');
})->middleware(['auth'])->name('walee.clientes');

// Ruta para WALEE Clientes Activos - Lista de clientes aceptados
Route::get('/walee-clientes-activos', function () {
    return view('walee-clientes-activos');
})->middleware(['auth'])->name('walee.clientes.activos');

// Ruta para WALEE Clientes en Proceso - Lista de clientes en seguimiento
Route::get('/walee-clientes-en-proceso', function () {
    return view('walee-clientes-en-proceso');
})->middleware(['auth'])->name('walee.clientes.proceso');

// Rutas para WALEE Extraer Clientes
Route::get('/walee-extraer-clientes', function () {
    return view('walee-extraer-clientes');
})->middleware(['auth'])->name('walee.extraer.clientes');

// API para extraer clientes
Route::post('/walee-extraer/iniciar', function (\Illuminate\Http\Request $request) {
    try {
        $jobId = \Illuminate\Support\Str::uuid()->toString();
        $webhookUrl = 'https://n8n.srv1137974.hstgr.cloud/webhook/0c01d9a1-788c-44d2-9c1b-9457901d0a3c';
        
        $nombreLugar = $request->input('nombre_lugar');
        $industria = $request->input('industria');
        
        // Crear el registro del workflow
        $workflowRun = \App\Models\WorkflowRun::create([
            'job_id' => $jobId,
            'status' => 'pending',
            'progress' => 0,
            'step' => 'En cola',
            'workflow_name' => 'Búsqueda: ' . ($nombreLugar ?? 'Sin nombre'),
            'data' => [
                'nombre_lugar' => $nombreLugar,
                'industria' => $industria,
            ],
        ]);
        
        // Preparar payload para n8n
        $payload = [
            'job_id' => $jobId,
            'progress_url' => url('/api/n8n/progress'),
            'nombre_lugar' => $nombreLugar,
            'industria' => $industria,
        ];
        
        // Llamar al webhook de n8n
        $response = \Illuminate\Support\Facades\Http::timeout(120)->post($webhookUrl, $payload);
        
        if ($response->successful()) {
            $workflowRun->update([
                'status' => 'running',
                'step' => 'Iniciado - Buscando lugares',
                'started_at' => now(),
            ]);
            
            return response()->json([
                'success' => true,
                'job_id' => $jobId,
                'message' => 'Búsqueda iniciada correctamente',
            ]);
        } else {
            $workflowRun->update([
                'status' => 'failed',
                'step' => 'Error al iniciar búsqueda',
                'error_message' => 'Error al iniciar workflow: ' . $response->status(),
                'completed_at' => null,
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al iniciar búsqueda: ' . $response->status(),
            ], 500);
        }
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('walee.extraer.iniciar');

Route::get('/walee-extraer/workflows', function () {
    $workflows = \App\Models\WorkflowRun::orderBy('created_at', 'desc')
        ->limit(20)
        ->get();
    return response()->json($workflows);
})->middleware(['auth'])->name('walee.extraer.workflows');

Route::get('/walee-extraer/workflow/{id}', function ($id) {
    $workflow = \App\Models\WorkflowRun::findOrFail($id);
    return response()->json($workflow);
})->middleware(['auth'])->name('walee.extraer.workflow');

Route::post('/walee-extraer/workflow/{id}/stop', function ($id) {
    try {
        $workflow = \App\Models\WorkflowRun::findOrFail($id);
        $workflow->update([
            'status' => 'failed',
            'step' => 'Cancelado manualmente',
            'error_message' => 'Workflow detenido manualmente por el usuario',
            'completed_at' => now(),
        ]);
        
        // Procesar el siguiente workflow en cola
        \App\Models\WorkflowRun::processNextPendingWorkflow();
        
        return response()->json([
            'success' => true,
            'message' => 'Workflow detenido correctamente',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('walee.extraer.workflow.stop');

Route::post('/walee-extraer/workflow/{id}/retry', function ($id) {
    try {
        $record = \App\Models\WorkflowRun::findOrFail($id);
        $jobId = \Illuminate\Support\Str::uuid()->toString();
        $webhookUrl = 'https://n8n.srv1137974.hstgr.cloud/webhook/0c01d9a1-788c-44d2-9c1b-9457901d0a3c';
        
        // Obtener datos originales
        $originalData = $record->data ?? [];
        
        // Crear nuevo registro
        $newWorkflowRun = \App\Models\WorkflowRun::create([
            'job_id' => $jobId,
            'status' => 'pending',
            'progress' => 0,
            'step' => 'En cola',
            'workflow_name' => $record->workflow_name,
            'data' => $originalData,
        ]);
        
        // Preparar payload para n8n
        $payload = [
            'job_id' => $jobId,
            'progress_url' => url('/api/n8n/progress'),
            'nombre_lugar' => $originalData['nombre_lugar'] ?? '',
            'industria' => $originalData['industria'] ?? '',
        ];
        
        // Llamar al webhook de n8n
        $response = \Illuminate\Support\Facades\Http::timeout(120)->post($webhookUrl, $payload);
        
        if ($response->successful()) {
            $newWorkflowRun->update([
                'status' => 'running',
                'step' => 'Iniciado - Buscando lugares',
                'started_at' => now(),
            ]);
            
            return response()->json([
                'success' => true,
                'job_id' => $jobId,
                'message' => 'Workflow reintentado correctamente',
            ]);
        } else {
            $newWorkflowRun->update([
                'status' => 'failed',
                'step' => 'Error al iniciar búsqueda',
                'error_message' => 'Error al iniciar workflow: ' . $response->status(),
                'completed_at' => null,
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al reintentar: ' . $response->status(),
            ], 500);
        }
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('walee.extraer.workflow.retry');

// Rutas para WALEE Emails
Route::get('/walee-emails', function () {
    return view('walee-emails');
})->middleware(['auth'])->name('walee.emails');

Route::get('/walee-emails/crear', function () {
    return view('walee-emails-crear');
})->middleware(['auth'])->name('walee.emails.crear');

Route::get('/walee-emails/enviados', function () {
    return view('walee-emails-enviados');
})->middleware(['auth'])->name('walee.emails.enviados');

// API para generar email con AI
Route::post('/walee-emails/generar', function (\Illuminate\Http\Request $request) {
    try {
        $apiKey = config('services.openai.api_key');
        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Falta OPENAI_API_KEY. Configura la API key en el servidor.',
            ], 500);
        }
        
        $clientName = $request->input('client_name', 'el cliente');
        $clientWebsite = $request->input('client_website', '');
        $aiPrompt = $request->input('ai_prompt', '');
        
        // Construir el prompt
        if (empty($aiPrompt)) {
            $prompt = "Genera un email profesional de propuesta personalizada para {$clientName}";
            if ($clientWebsite) {
                $prompt .= " cuyo sitio web es {$clientWebsite}";
            }
            $prompt .= ". El email debe ser persuasivo, profesional y enfocado en ofrecer servicios de diseño web, marketing digital y desarrollo de software.";
        } else {
            $prompt = "Genera un email profesional. {$aiPrompt}";
            if ($clientName !== 'el cliente') {
                $prompt .= " El cliente se llama {$clientName}.";
            }
            if ($clientWebsite) {
                $prompt .= " Su sitio web es {$clientWebsite}.";
            }
        }
        
        $response = \Illuminate\Support\Facades\Http::withToken($apiKey)
            ->acceptJson()
            ->timeout(120)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Eres un experto en marketing digital y redacción de emails comerciales. Genera emails profesionales, persuasivos y directos. Responde SOLO con JSON que contenga "subject" (asunto del email, máximo 10 palabras) y "body" (cuerpo del email completo). NO incluyas mensajes de cierre como "Si necesitas alguna modificación", "No dudes en contactarme", etc. Al final del body, SIEMPRE incluye esta firma: "\n\nWeb Solutions\nwebsolutionscrnow@gmail.com\n+506 8806 1829 (WhatsApp)\nwebsolutions.work"',
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt . ' Responde en JSON con "subject" y "body".',
                    ],
                ],
            ]);
        
        if ($response->successful()) {
            $responseData = $response->json();
            $content = $responseData['choices'][0]['message']['content'] ?? '';
            
            if (empty($content)) {
                throw new \RuntimeException('La respuesta de AI está vacía.');
            }
            
            $data = is_string($content) ? json_decode($content, true) : $content;
            
            if (!is_array($data)) {
                throw new \RuntimeException('La respuesta de AI no es JSON válido.');
            }
            
            $emailSubject = trim($data['subject'] ?? 'Propuesta Personalizada');
            $emailBody = trim($data['body'] ?? '');
            
            if (empty($emailBody)) {
                throw new \RuntimeException('El cuerpo del email está vacío.');
            }
            
            // Limpiar mensajes de cierre comunes
            $emailBody = preg_replace('/\s*(Si necesitas alguna modificación.*?\.|No dudes en.*?\.|Estoy a tu disposición.*?\.|Quedo a la espera.*?\.).*/is', '', $emailBody);
            $emailBody = trim($emailBody);
            
            return response()->json([
                'success' => true,
                'subject' => $emailSubject,
                'body' => $emailBody,
            ]);
        } else {
            throw new \Exception('Error en la respuesta de OpenAI: ' . $response->status());
        }
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('walee.emails.generar');

// API para enviar email
Route::post('/walee-emails/enviar', function (\Illuminate\Http\Request $request) {
    try {
        $clienteId = $request->input('cliente_id');
        $email = $request->input('email');
        $subject = $request->input('subject');
        $body = $request->input('body');
        $aiPrompt = $request->input('ai_prompt');
        
        $client = $clienteId ? \App\Models\Client::find($clienteId) : null;
        
        // Enviar email
        \Illuminate\Support\Facades\Mail::raw($body, function ($message) use ($email, $subject) {
            $message->from('websolutionscrnow@gmail.com', 'Web Solutions')
                    ->to($email)
                    ->subject($subject);
        });
        
        // Guardar en la base de datos
        \App\Models\PropuestaPersonalizada::create([
            'cliente_id' => $clienteId ?: null,
            'cliente_nombre' => $client?->name ?? 'N/A',
            'email' => $email,
            'subject' => $subject,
            'body' => $body,
            'ai_prompt' => $aiPrompt ?: null,
            'user_id' => auth()->id(),
        ]);
        
        // Marcar el contacto como propuesta personalizada enviada
        if ($client) {
            $client->update([
                'propuesta_enviada' => true,
                'estado' => 'propuesta_personalizada_enviada'
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Email enviado correctamente',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('walee.emails.enviar');

// Rutas para emails recibidos
Route::get('/walee-emails/recibidos', function () {
    return view('walee-emails-recibidos');
})->middleware(['auth'])->name('walee.emails.recibidos');

Route::post('/walee-emails/recibidos/{id}/read', function ($id) {
    $email = \App\Models\EmailRecibido::findOrFail($id);
    $email->update(['is_read' => true]);
    return response()->json(['success' => true]);
})->middleware(['auth'])->name('walee.emails.recibidos.read');

Route::post('/walee-emails/recibidos/{id}/star', function ($id) {
    $email = \App\Models\EmailRecibido::findOrFail($id);
    $email->update(['is_starred' => !$email->is_starred]);
    return response()->json(['success' => true, 'is_starred' => $email->is_starred]);
})->middleware(['auth'])->name('walee.emails.recibidos.star');

Route::post('/walee-emails/recibidos/sync', function () {
    // Esta ruta puede conectarse a Gmail API o recibir emails desde un webhook de n8n
    // Por ahora retorna un mensaje informativo
    return response()->json([
        'success' => true,
        'message' => 'Configurar webhook en n8n para recibir emails automáticamente',
    ]);
})->middleware(['auth'])->name('walee.emails.recibidos.sync');

// Ruta para Facturas & Cotizaciones
Route::get('/walee-facturas', function () {
    return view('walee-facturas');
})->middleware(['auth'])->name('walee.facturas');

Route::get('/walee-facturas/crear', function () {
    return view('walee-facturas-crear');
})->middleware(['auth'])->name('walee.facturas.crear');

Route::post('/walee-facturas/guardar', function (\Illuminate\Http\Request $request) {
    try {
        $factura = \App\Models\Factura::create([
            'cliente_id' => $request->input('cliente_id') ?: null,
            'correo' => $request->input('correo'),
            'numero_factura' => $request->input('numero_factura'),
            'serie' => $request->input('serie'),
            'fecha_emision' => $request->input('fecha_emision'),
            'concepto' => $request->input('concepto'),
            'concepto_pago' => $request->input('concepto_pago'),
            'subtotal' => $request->input('subtotal') ?: 0,
            'total' => $request->input('total') ?: 0,
            'monto_pagado' => $request->input('monto_pagado') ?: 0,
            'metodo_pago' => $request->input('metodo_pago'),
            'estado' => $request->input('estado', 'pendiente'),
            'fecha_vencimiento' => $request->input('fecha_vencimiento'),
            'notas' => $request->input('notas'),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Factura creada correctamente',
            'factura_id' => $factura->id,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('walee.facturas.guardar');

Route::get('/walee-facturas/crear-ai', function () {
    return view('walee-facturas-crear-ai');
})->middleware(['auth'])->name('walee.facturas.crear-ai');

// API para generar factura con AI
Route::post('/walee-facturas/generar-ai', function (\Illuminate\Http\Request $request) {
    try {
        $apiKey = config('services.openai.api_key');
        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Falta OPENAI_API_KEY. Configura la API key en el servidor.',
            ], 500);
        }
        
        $clienteNombre = $request->input('cliente_nombre', 'Cliente');
        $clienteIndustria = $request->input('cliente_industria', '');
        $clienteDescripcion = $request->input('cliente_descripcion', '');
        $instrucciones = $request->input('instrucciones', '');
        
        // Construir el prompt
        $prompt = "Genera los datos para una factura profesional para el cliente '{$clienteNombre}'";
        
        if ($clienteIndustria) {
            $prompt .= " que pertenece al sector '{$clienteIndustria}'";
        }
        
        if ($clienteDescripcion) {
            $prompt .= ". Descripción del negocio: {$clienteDescripcion}";
        }
        
        if ($instrucciones) {
            $prompt .= ". Instrucciones específicas: {$instrucciones}";
        }
        
        $prompt .= ". La empresa que emite la factura es Web Solutions, especializada en desarrollo web, marketing digital, sistemas personalizados y diseño gráfico.";
        
        $response = \Illuminate\Support\Facades\Http::withToken($apiKey)
            ->acceptJson()
            ->timeout(120)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Eres un experto en facturación y servicios digitales. Genera datos realistas para facturas basándote en el tipo de cliente. Responde SOLO con JSON que contenga: "concepto" (descripción detallada del servicio, máximo 300 caracteres, lista de items con viñetas), "total" (número entero en colones costarricenses, valores realistas entre 50000 y 500000), "notas" (notas adicionales breves para la factura, máximo 100 caracteres). Los servicios típicos incluyen: Diseño web (150000-300000), Mantenimiento mensual (35000-75000), Logo (80000-150000), Marketing digital (100000-200000), Sistema personalizado (250000-500000).',
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt . ' Responde en JSON con "concepto", "total" y "notas".',
                    ],
                ],
            ]);
        
        if ($response->successful()) {
            $responseData = $response->json();
            $content = $responseData['choices'][0]['message']['content'] ?? '';
            
            if (empty($content)) {
                throw new \RuntimeException('La respuesta de AI está vacía.');
            }
            
            $data = is_string($content) ? json_decode($content, true) : $content;
            
            if (!is_array($data)) {
                throw new \RuntimeException('La respuesta de AI no es JSON válido.');
            }
            
            return response()->json([
                'success' => true,
                'concepto' => trim($data['concepto'] ?? 'Servicios de desarrollo web'),
                'total' => intval($data['total'] ?? 150000),
                'notas' => trim($data['notas'] ?? ''),
            ]);
        } else {
            throw new \Exception('Error en la respuesta de OpenAI: ' . $response->status());
        }
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('walee.facturas.generar-ai');

// Lista de facturas
Route::get('/walee-facturas/lista', function () {
    return view('walee-facturas-lista');
})->middleware(['auth'])->name('walee.facturas.lista');

// Ver factura individual
Route::get('/walee-facturas/{id}', function ($id) {
    $factura = \App\Models\Factura::with('cliente')->findOrFail($id);
    return view('walee-factura-ver', compact('factura'));
})->middleware(['auth'])->name('walee.factura.ver');

// Enviar factura por email
Route::post('/walee-facturas/{id}/enviar', function ($id) {
    try {
        $factura = \App\Models\Factura::with('cliente')->findOrFail($id);
        
        if (!$factura->correo) {
            return response()->json([
                'success' => false,
                'message' => 'La factura no tiene correo asociado',
            ], 400);
        }
        
        // Construir el cuerpo del email
        $emailBody = "Estimado cliente,\n\n";
        $emailBody .= "Adjunto encontrará los detalles de su factura:\n\n";
        $emailBody .= "Factura #" . $factura->numero_factura . "\n";
        $emailBody .= "Fecha: " . ($factura->fecha_emision?->format('d/m/Y') ?? 'N/A') . "\n";
        $emailBody .= "Concepto: " . $factura->concepto . "\n";
        $emailBody .= "Total: ₡" . number_format($factura->total, 0, ',', '.') . "\n\n";
        
        if ($factura->notas) {
            $emailBody .= "Notas: " . $factura->notas . "\n\n";
        }
        
        $emailBody .= "Gracias por su preferencia.\n\n";
        $emailBody .= "Web Solutions\n";
        $emailBody .= "websolutionscrnow@gmail.com\n";
        $emailBody .= "+506 8806 1829 (WhatsApp)\n";
        $emailBody .= "websolutions.work";
        
        // Enviar email
        \Illuminate\Support\Facades\Mail::raw($emailBody, function ($message) use ($factura) {
            $message->from('websolutionscrnow@gmail.com', 'Web Solutions')
                    ->to($factura->correo)
                    ->subject('Factura #' . $factura->numero_factura . ' - Web Solutions');
        });
        
        // Marcar como enviada
        $factura->update([
            'enviada_at' => now(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Factura enviada correctamente',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('walee.factura.enviar');

Route::get('/walee-cotizaciones', function () {
    return view('walee-cotizaciones');
})->middleware(['auth'])->name('walee.cotizaciones');

// Ruta para ver detalle de un cliente
Route::get('/walee-cliente/{id}', function ($id) {
    $cliente = \App\Models\Client::findOrFail($id);
    return view('walee-cliente-detalle', compact('cliente'));
})->middleware(['auth'])->name('walee.cliente.detalle');

// Ruta para editar un cliente
Route::get('/walee-cliente/{id}/editar', function ($id) {
    $cliente = \App\Models\Client::findOrFail($id);
    return view('walee-cliente-editar', compact('cliente'));
})->middleware(['auth'])->name('walee.cliente.editar');

// Ruta para actualizar un cliente
Route::put('/walee-cliente/{id}', function (\Illuminate\Http\Request $request, $id) {
    $cliente = \App\Models\Client::findOrFail($id);
    
    $data = [
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'telefono_1' => $request->input('telefono_1'),
        'telefono_2' => $request->input('telefono_2'),
        'website' => $request->input('website'),
        'address' => $request->input('address'),
        'estado' => $request->input('estado'),
        'feedback' => $request->input('feedback'),
    ];
    
    // Procesar foto si se subió una nueva
    if ($request->hasFile('foto_file')) {
        $file = $request->file('foto_file');
        $path = $file->store('clientes_en_proceso_fotos', 'public');
        $data['foto'] = $path;
    }
    
    $cliente->update($data);
    
    return redirect()->route('walee.cliente.detalle', $id)->with('success', 'Cliente actualizado correctamente');
})->middleware(['auth'])->name('walee.cliente.actualizar');

// Ruta para settings del cliente
Route::get('/walee-cliente/{id}/settings', function ($id) {
    $cliente = \App\Models\Client::findOrFail($id);
    return view('walee-cliente-settings', compact('cliente'));
})->middleware(['auth'])->name('walee.cliente.settings');

// Ruta para guardar webhook del cliente
Route::post('/walee-cliente/{id}/webhook', function (\Illuminate\Http\Request $request, $id) {
    try {
        $cliente = \App\Models\Client::findOrFail($id);
        $cliente->webhook_url = $request->input('webhook_url');
        $cliente->page_id = $request->input('page_id');
        $cliente->token = $request->input('token');
        $cliente->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Configuración guardada correctamente',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('walee.cliente.webhook');

// Rutas para Tareas (POST, PUT, DELETE)
Route::post('/tareas', function (\Illuminate\Http\Request $request) {
    try {
        $tarea = new \App\Models\Tarea();
        $tarea->lista_id = $request->input('lista_id') ?: null;
        $tarea->texto = $request->input('texto');
        // Si viene fecha_hora del request, usarla; si no, usar now()
        $tarea->fecha_hora = $request->input('fecha_hora') ? \Carbon\Carbon::parse($request->input('fecha_hora')) : now();
        $tarea->tipo = $request->input('tipo') ?: null;
        $tarea->favorito = false; // Por defecto no es favorito
        $tarea->estado = 'pending';
        $tarea->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Tarea creada correctamente',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('tareas.store');

Route::put('/tareas/{id}', function (\Illuminate\Http\Request $request, $id) {
    try {
        $tarea = \App\Models\Tarea::findOrFail($id);
        $tarea->texto = $request->input('texto');
        $tarea->lista_id = $request->input('lista_id') ?: null;
        if ($request->input('fecha_hora')) {
            $tarea->fecha_hora = \Carbon\Carbon::parse($request->input('fecha_hora'));
        }
        $tarea->tipo = $request->input('tipo') ?: null;
        $tarea->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Tarea actualizada correctamente',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('tareas.update');

Route::delete('/tareas/{id}', function ($id) {
    try {
        $tarea = \App\Models\Tarea::findOrFail($id);
        $tarea->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Tarea eliminada correctamente',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('tareas.delete');

Route::post('/tareas/{id}/toggle', function ($id) {
    try {
        $tarea = \App\Models\Tarea::findOrFail($id);
        $tarea->estado = $tarea->estado === 'pending' ? 'completado' : 'pending';
        $tarea->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('tareas.toggle');

Route::post('/tareas/{id}/favorito', function ($id) {
    try {
        $tarea = \App\Models\Tarea::findOrFail($id);
        $tarea->favorito = !$tarea->favorito;
        $tarea->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Favorito actualizado',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('tareas.favorito');

// Rutas para Listas
Route::post('/listas', function (\Illuminate\Http\Request $request) {
    try {
        $lista = new \App\Models\Lista();
        $lista->nombre = $request->input('nombre');
        $lista->descripcion = $request->input('descripcion');
        $lista->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Lista creada correctamente',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('listas.store');

// Ruta para crear publicación del cliente
Route::post('/walee-cliente/{id}/publicaciones', function (\Illuminate\Http\Request $request, $id) {
    try {
        $cliente = \App\Models\Client::findOrFail($id);
        
        // Guardar fotos si se subieron
        $fotosPaths = [];
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $path = $foto->store('publicaciones', 'public');
                $fotosPaths[] = $path;
            }
        }
        
        // Guardar primera foto como image_url para compatibilidad
        $imageUrl = !empty($fotosPaths) ? asset('storage/' . $fotosPaths[0]) : null;
        
        // Usar el contenido como título si no hay título específico
        $title = $request->input('title') ?: substr($request->input('content'), 0, 100);
        
        $publicacion = \App\Models\Post::create([
            'cliente_id' => $cliente->id,
            'title' => $title,
            'content' => $request->input('content'),
            'image_url' => $imageUrl,
        ]);
        
        // Enviar webhook con los datos y fotos
        try {
            $client = new \GuzzleHttp\Client();
            
            $multipartData = [
                [
                    'name' => 'contenido',
                    'contents' => $request->input('content'),
                ],
                [
                    'name' => 'cliente_id',
                    'contents' => (string) $cliente->id,
                ],
                [
                    'name' => 'cliente_nombre',
                    'contents' => $cliente->name,
                ],
                [
                    'name' => 'publicacion_id',
                    'contents' => (string) $publicacion->id,
                ],
            ];
            
            // Agregar fotos al webhook
            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $index => $foto) {
                    $multipartData[] = [
                        'name' => 'fotos[]',
                        'contents' => fopen($foto->getRealPath(), 'r'),
                        'filename' => $foto->getClientOriginalName(),
                    ];
                }
            }
            
            $client->post('https://n8n.srv1137974.hstgr.cloud/webhook-test/6368cb37-0292-4232-beab-69e98e910df6', [
                'multipart' => $multipartData,
                'timeout' => 30,
            ]);
        } catch (\Exception $webhookError) {
            \Log::warning('Error al enviar webhook de publicación: ' . $webhookError->getMessage());
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Publicación creada correctamente',
            'publicacion' => $publicacion,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('walee.cliente.publicaciones.store');

// Ruta para eliminar publicación del cliente
Route::delete('/walee-cliente/{id}/publicaciones/{publicacion_id}', function ($id, $publicacion_id) {
    try {
        $cliente = \App\Models\Client::findOrFail($id);
        $publicacion = \App\Models\Post::where('id', $publicacion_id)
            ->where('cliente_id', $cliente->id)
            ->firstOrFail();
        
        $publicacion->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Publicación eliminada correctamente',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('walee.cliente.publicaciones.delete');

// Streaming de chat con OpenAI
Route::post('/chat/stream', [ChatStreamController::class, 'stream'])
    ->middleware(['auth'])
    ->name('chat.stream');

// Finalizar y persistir conversación después del streaming
Route::post('/chat/finalize', [\App\Http\Controllers\ChatActionsController::class, 'finalize'])
    ->middleware(['auth'])
    ->name('chat.finalize');

// Rutas para los casos de soporte
Route::get('/cases', [SupportCaseController::class, 'index'])->name('cases.index');
Route::post('/support-cases/{case}/close', [SupportCaseController::class, 'close'])
    ->name('support-cases.close');

// Site Scraper Routes
// Rutas protegidas para el scraper de sitios web
Route::middleware(['auth'])->group(function () {
    Route::get('/site-scraper', [\App\Http\Controllers\SiteScraperController::class, 'index'])->name('site-scraper');
    Route::post('/site-scraper/search', [\App\Http\Controllers\SiteScraperController::class, 'search'])->name('site-scraper.search');
    
    // Ruta para el chat
    Route::get('/chat', function () {
        return view('chat.index');
    })->name('chat');
    
    // Callback de Google Calendar OAuth2
    Route::get('/google-calendar/callback', function (\Illuminate\Http\Request $request) {
        $code = $request->get('code');
        
        if (!$code) {
            \Filament\Notifications\Notification::make()
                ->title('Error de autorización')
                ->body('No se recibió el código de autorización de Google.')
                ->danger()
                ->send();
            
            return redirect()->route('filament.admin.pages.google-calendar-auth');
        }
        
        $service = new \App\Services\GoogleCalendarService();
        $success = $service->handleCallback($code);
        
        if ($success) {
            \Filament\Notifications\Notification::make()
                ->title('Autorización exitosa')
                ->body('Google Calendar ha sido autorizado correctamente.')
                ->success()
                ->send();
        } else {
            \Filament\Notifications\Notification::make()
                ->title('Error de autorización')
                ->body('No se pudo completar la autorización. Intenta nuevamente.')
                ->danger()
                ->send();
        }
        
        return redirect()->route('filament.admin.pages.google-calendar-auth');
    })->name('google-calendar.callback');
});

// Rutas de autenticación y configuración
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    if (Features::canManageTwoFactorAuthentication()) {
        Volt::route('settings/two-factor', 'settings.two-factor')
            ->middleware(Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword') 
                ? ['auth', 'password.confirm'] 
                : ['auth']
            )
            ->name('two-factor.show');
    }
});



Route::get('/test-form', function () {
    return view('test-form');
});

// Ruta para venta de camas/colchones
Route::get('/camas', function () {
    return view('camas');
})->name('camas');

// Ruta para sistema para tu negocio
Route::get('/sistema-para-tu-negocio', function () {
    return view('sistema-para-tu-negocio');
})->name('sistema-para-tu-negocio');

// Ruta para sistema de pizzeria
Route::get('/pizzeria', function () {
    return view('pizzeria');
})->name('pizzeria');

// Ruta para clientes con propuesta enviada
Route::get('/clientes/propuesta-enviada', [App\Http\Controllers\ClientPropuestaEnviadaController::class, 'index'])
    ->middleware('auth')
    ->name('clients.propuesta-enviada');

// Rutas para listas personalizadas móviles
Route::prefix('ejemplo1/lista')->group(function () {
    Route::get('/clientes', function () {
        $clientes = \App\Models\Cliente::orderBy('created_at', 'desc')->paginate(20);
        return view('ejemplo1-listas.clientes', compact('clientes'));
    })->name('ejemplo1.lista.clientes');
    
    Route::get('/clientes/{id}', function ($id) {
        $cliente = \App\Models\Cliente::findOrFail($id);
        return view('ejemplo1-listas.show.cliente', compact('cliente'));
    })->name('ejemplo1.show.cliente');
    
    Route::get('/citas', function () {
        $citas = \App\Models\Cita::with('cliente')->orderBy('fecha_inicio', 'desc')->paginate(20);
        return view('ejemplo1-listas.citas', compact('citas'));
    })->name('ejemplo1.lista.citas');
    
    Route::get('/citas/{id}', function ($id) {
        $cita = \App\Models\Cita::with('cliente')->findOrFail($id);
        return view('ejemplo1-listas.show.cita', compact('cita'));
    })->name('ejemplo1.show.cita');
    
    Route::get('/usuarios', function () {
        $usuarios = \App\Models\User::orderBy('created_at', 'desc')->paginate(20);
        return view('ejemplo1-listas.usuarios', compact('usuarios'));
    })->name('ejemplo1.lista.usuarios');
    
    Route::get('/usuarios/{id}', function ($id) {
        $usuario = \App\Models\User::findOrFail($id);
        return view('ejemplo1-listas.show.usuario', compact('usuario'));
    })->name('ejemplo1.show.usuario');
    
    Route::get('/propuestas-enviadas', function () {
        $propuestas = \App\Models\Client::where('propuesta_enviada', true)->orderBy('created_at', 'desc')->paginate(20);
        return view('ejemplo1-listas.propuestas-enviadas', compact('propuestas'));
    })->name('ejemplo1.lista.propuestas-enviadas');
    
    Route::get('/propuestas-enviadas/{id}', function ($id) {
        $propuesta = \App\Models\Client::findOrFail($id);
        return view('ejemplo1-listas.show.propuesta-enviada', compact('propuesta'));
    })->name('ejemplo1.show.propuesta-enviada');
    
    Route::get('/facturas', function () {
        $facturas = \App\Models\Factura::with('cliente')->orderBy('created_at', 'desc')->paginate(20);
        return view('ejemplo1-listas.facturas', compact('facturas'));
    })->name('ejemplo1.lista.facturas');
    
    Route::get('/facturas/{id}', function ($id) {
        $factura = \App\Models\Factura::with('cliente')->findOrFail($id);
        return view('ejemplo1-listas.show.factura', compact('factura'));
    })->name('ejemplo1.show.factura');
    
    Route::get('/sitios', function () {
        $sitios = \App\Models\Sitio::with('tags')->orderBy('created_at', 'desc')->paginate(20);
        return view('ejemplo1-listas.sitios', compact('sitios'));
    })->name('ejemplo1.lista.sitios');
    
    Route::get('/sitios/{id}', function ($id) {
        $sitio = \App\Models\Sitio::with('tags')->findOrFail($id);
        return view('ejemplo1-listas.show.sitio', compact('sitio'));
    })->name('ejemplo1.show.sitio');
    
    Route::get('/support-cases', function () {
        $cases = \App\Models\SupportCase::orderBy('created_at', 'desc')->paginate(20);
        return view('ejemplo1-listas.support-cases', compact('cases'));
    })->name('ejemplo1.lista.support-cases');
    
    Route::get('/support-cases/{id}', function ($id) {
        $case = \App\Models\SupportCase::findOrFail($id);
        return view('ejemplo1-listas.show.support-case', compact('case'));
    })->name('ejemplo1.show.support-case');
});

// Ruta ejemplo1 - Información de Filament optimizada para móviles
Route::get('/ejemplo1', function () {
    try {
        $panel = \Filament\Facades\Filament::getPanel('admin');
    } catch (\Exception $e) {
        $panel = null;
    }
    
    // Estadísticas
    $clientes = \App\Models\Cliente::count();
    $clientesActivos = \App\Models\Cliente::where('estado_cuenta', 'activo')->count();
    $citasHoy = \App\Models\Cita::whereDate('fecha_inicio', today())->where('estado', '!=', 'cancelada')->count();
    $citasProximas = \App\Models\Cita::where('fecha_inicio', '>=', now())
        ->where('estado', '!=', 'cancelada')
        ->orderBy('fecha_inicio', 'asc')
        ->limit(5)
        ->with('cliente')
        ->get();
    $citasTotal = \App\Models\Cita::where('estado', '!=', 'cancelada')->count();
    $usuarios = \App\Models\User::count();
    
    // Mapeo de recursos de Filament a rutas personalizadas
    $resourceRouteMap = [
        'App\\Filament\\Resources\\ClienteResource' => 'ejemplo1.lista.clientes',
        'App\\Filament\\Resources\\ClientPropuestaEnviadaResource' => 'ejemplo1.lista.propuestas-enviadas',
        'App\\Filament\\Resources\\FacturaResource' => 'ejemplo1.lista.facturas',
        'App\\Filament\\Resources\\SitioResource' => 'ejemplo1.lista.sitios',
        'App\\Filament\\Resources\\SupportCaseResource' => 'ejemplo1.lista.support-cases',
        'App\\Filament\\Resources\\UserResource' => 'ejemplo1.lista.usuarios',
    ];
    
    // Mapeo de páginas de Filament a rutas personalizadas
    $pageRouteMap = [
        'App\\Filament\\Pages\\GoogleCalendar' => 'ejemplo1.lista.citas',
    ];
    
    // Obtener todos los recursos y páginas
    $groupedItems = [];
    
    if ($panel) {
        // Obtener todos los recursos
        $resources = [];
        foreach ($panel->getResources() as $resourceClass) {
            try {
                if (method_exists($resourceClass, 'shouldRegisterNavigation') && !$resourceClass::shouldRegisterNavigation()) {
                    continue;
                }
                
                $resourceClassFull = is_string($resourceClass) ? $resourceClass : get_class($resourceClass);
                $customRoute = $resourceRouteMap[$resourceClassFull] ?? null;
                
                $resources[] = [
                    'name' => $resourceClass::getNavigationLabel() ?? class_basename($resourceClass),
                    'url' => $customRoute ? route($customRoute) : ($resourceClass::getUrl('index') ?? '#'),
                    'icon' => $resourceClass::getNavigationIcon(),
                    'group' => $resourceClass::getNavigationGroup() ?? 'Otros',
                    'badge' => method_exists($resourceClass, 'getNavigationBadge') ? $resourceClass::getNavigationBadge() : null,
                ];
            } catch (\Exception $e) {
                // Ignorar recursos que no se pueden cargar
                continue;
            }
        }
        
        // Obtener todas las páginas personalizadas
        $pages = [];
        foreach ($panel->getPages() as $pageClass) {
            try {
                if (method_exists($pageClass, 'shouldRegisterNavigation') && !$pageClass::shouldRegisterNavigation()) {
                    continue;
                }
                
                $pageClassFull = is_string($pageClass) ? $pageClass : get_class($pageClass);
                $customRoute = $pageRouteMap[$pageClassFull] ?? null;
                
                $pages[] = [
                    'name' => $pageClass::getNavigationLabel() ?? class_basename($pageClass),
                    'url' => $customRoute ? route($customRoute) : ($pageClass::getUrl() ?? '#'),
                    'icon' => $pageClass::getNavigationIcon(),
                    'group' => $pageClass::getNavigationGroup() ?? 'Otros',
                ];
            } catch (\Exception $e) {
                // Ignorar páginas que no se pueden cargar
                continue;
            }
        }
        
        // Agrupar recursos y páginas por grupo
        foreach ($resources as $item) {
            $group = $item['group'] ?? 'Otros';
            if (!isset($groupedItems[$group])) {
                $groupedItems[$group] = [];
            }
            $groupedItems[$group][] = $item;
        }
        foreach ($pages as $item) {
            $group = $item['group'] ?? 'Otros';
            if (!isset($groupedItems[$group])) {
                $groupedItems[$group] = [];
            }
            $groupedItems[$group][] = $item;
        }
    }
    
    return view('ejemplo1-mobile', compact(
        'clientes',
        'clientesActivos',
        'citasHoy',
        'citasProximas',
        'citasTotal',
        'usuarios',
        'groupedItems'
    ));
})->name('ejemplo1');
