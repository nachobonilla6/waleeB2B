<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Controllers\SupportCaseController;
use App\Http\Controllers\Auth\GoogleLoginController;
use App\Http\Controllers\Auth\LoginController;
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

// Ruta para servir archivos de publicaciones (pública, sin autenticación)
// Esta ruta debe estar ANTES de cualquier middleware que pueda bloquearla
Route::get('/storage/publicaciones/{filename}', function ($filename) {
    try {
        // Limpiar el nombre del archivo para seguridad (prevenir directory traversal)
        $filename = basename($filename);
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename); // Solo permitir caracteres seguros
        
        $path = storage_path('app/public/publicaciones/' . $filename);
        
        \Log::info('Intentando servir archivo de publicación', [
            'filename' => $filename,
            'path' => $path,
            'exists' => file_exists($path)
        ]);
        
        if (!file_exists($path) || !is_file($path)) {
            \Log::warning('Archivo de publicación no encontrado', [
                'path' => $path,
                'filename' => $filename,
                'directory_exists' => is_dir(dirname($path)),
                'directory_contents' => is_dir(dirname($path)) ? implode(', ', array_slice(scandir(dirname($path)), 0, 10)) : 'N/A'
            ]);
            abort(404, 'Archivo no encontrado: ' . $filename);
        }
        
        $file = file_get_contents($path);
        $type = mime_content_type($path) ?: 'image/jpeg';
        
        \Log::info('Archivo servido correctamente', [
            'filename' => $filename,
            'size' => filesize($path),
            'type' => $type
        ]);
        
        return response($file, 200)
            ->header('Content-Type', $type)
            ->header('Content-Length', filesize($path))
            ->header('Cache-Control', 'public, max-age=31536000')
            ->header('Access-Control-Allow-Origin', '*'); // Permitir acceso desde cualquier origen
    } catch (\Exception $e) {
        \Log::error('Error al servir archivo de publicación', [
            'filename' => $filename ?? 'unknown',
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        abort(500, 'Error al servir archivo');
    }
})->where('filename', '.*')->name('storage.publicaciones');

// Ruta de prueba para verificar que los archivos existen
Route::get('/test-storage', function () {
    $publicacionesPath = storage_path('app/public/publicaciones');
    $files = [];
    
    if (is_dir($publicacionesPath)) {
        $files = array_slice(scandir($publicacionesPath), 2); // Remove . and ..
        $files = array_filter($files, function($file) use ($publicacionesPath) {
            return is_file($publicacionesPath . '/' . $file);
        });
    }
    
    return response()->json([
        'storage_path' => $publicacionesPath,
        'directory_exists' => is_dir($publicacionesPath),
        'files_count' => count($files),
        'files' => array_values($files),
        'symlink_exists' => is_link(public_path('storage')),
        'symlink_target' => is_link(public_path('storage')) ? readlink(public_path('storage')) : null,
    ]);
})->name('test.storage');

// Rutas de autenticación con Google
Route::get('/auth/google', [GoogleLoginController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleLoginController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Ruta de login con estilo Walee
Route::get('/login', function () {
    if (auth()->check()) {
        return redirect()->route('walee.dashboard');
    }
    return view('walee-login');
})->middleware('guest')->name('login');

// Ruta POST para procesar login
Route::post('/login', [LoginController::class, 'store'])->middleware('guest')->name('login.store');

// Página de inicio
Route::get('/', function () {
    $sitios = Sitio::with('tags')
        ->where('en_linea', true)
        ->orderBy('created_at', 'desc')
        ->take(6) // Show only 6 most recent active sites
        ->get();
    
    return view('welcome', compact('sitios'));
})->name('home');


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

// Obtener historial del chat (últimos 20 mensajes)
Route::get('/walee-chat/history', function () {
    try {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['messages' => []], 401);
        }

        // Obtener los últimos 20 mensajes ordenados por fecha de creación (más antiguos primero)
        $messages = \App\Models\ChatMessage::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->reverse() // Invertir para mostrar del más antiguo al más reciente
            ->map(function ($message) {
                return [
                    'message' => $message->message,
                    'type' => $message->type,
                    'created_at' => $message->created_at->toISOString(),
                ];
            })
            ->values();

        return response()->json(['messages' => $messages]);
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Error obteniendo historial del chat', ['error' => $e->getMessage()]);
        return response()->json(['messages' => []], 500);
    }
})->middleware(['auth'])->name('walee.chat.history');

// Limpiar chat
Route::post('/walee-chat/clear', function () {
    \App\Models\ChatMessage::where('user_id', auth()->id())->delete();
    return response()->json(['success' => true]);
})->middleware(['auth'])->name('walee.chat.clear');

// Ruta para WALEE Dashboard - Solo admin autenticado
Route::get('/walee-dashboard', function () {
    return view('walee-dashboard');
})->middleware(['auth'])->name('walee.dashboard');

// Dashboard de Tickets - Estadísticas
Route::get('/walee-tickets-dashboard', function () {
    // Estadísticas de tickets
    $totalTickets = \App\Models\Ticket::count();
    $ticketsEnviados = \App\Models\Ticket::where('estado', 'enviado')->count();
    $ticketsRecibidos = \App\Models\Ticket::where('estado', 'recibido')->count();
    $ticketsResueltos = \App\Models\Ticket::where('estado', 'resuelto')->count();
    
    // Tickets este mes
    $ticketsEsteMes = \App\Models\Ticket::whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->count();
    
    // Tickets esta semana
    $ticketsEstaSemana = \App\Models\Ticket::whereBetween('created_at', [
        now()->startOfWeek(),
        now()->endOfWeek()
    ])->count();
    
    // Tickets hoy
    $ticketsHoy = \App\Models\Ticket::whereDate('created_at', today())->count();
    
    // Tickets urgentes (solo los que no están resueltos)
    $ticketsUrgentes = \App\Models\Ticket::where('urgente', true)
        ->where('estado', '!=', 'resuelto')
        ->count();
    
    // Tickets prioritarios
    $ticketsPrioritarios = \App\Models\Ticket::where('prioritario', true)->count();
    
    // Tickets a discutir
    $ticketsADiscutir = \App\Models\Ticket::where('a_discutir', true)->count();
    
    // Distribución de tickets por día (últimos 15 días)
    $ticketsPorDiaRaw = \App\Models\Ticket::selectRaw('DATE(created_at) as dia, COUNT(*) as total')
        ->where('created_at', '>=', now()->subDays(15))
        ->groupBy('dia')
        ->orderBy('dia', 'asc')
        ->get()
        ->keyBy('dia');
    
    // Rellenar todos los días de los últimos 15 días
    $ticketsPorDia = [];
    for ($i = 14; $i >= 0; $i--) {
        $fecha = now()->subDays($i)->format('Y-m-d');
        $ticket = $ticketsPorDiaRaw->get($fecha);
        $ticketsPorDia[] = [
            'dia' => $fecha,
            'total' => $ticket ? (int)$ticket->total : 0
        ];
    }
    
    // Tickets recientes (últimos 10)
    $ticketsRecientes = \App\Models\Ticket::orderBy('created_at', 'desc')
        ->limit(10)
        ->get();
    
    return view('walee-tickets-dashboard', compact(
        'totalTickets',
        'ticketsEnviados',
        'ticketsRecibidos',
        'ticketsResueltos',
        'ticketsEsteMes',
        'ticketsEstaSemana',
        'ticketsHoy',
        'ticketsUrgentes',
        'ticketsPrioritarios',
        'ticketsADiscutir',
        'ticketsPorDia',
        'ticketsRecientes'
    ));
})->middleware(['auth'])->name('walee.tickets.dashboard');

// Tickets de soporte - Rutas separadas por pestaña
Route::get('/walee-tickets', function () {
    return redirect()->route('walee.tickets.tab', ['tab' => 'todos']);
})->middleware(['auth'])->name('walee.tickets');

Route::get('/walee-tickets/{tab}', function ($tab) {
    // Validar que la pestaña sea válida
    $validTabs = ['todos', 'enviados', 'recibidos', 'resueltos'];
    if (!in_array($tab, $validTabs)) {
        return redirect()->route('walee.tickets.tab', ['tab' => 'todos']);
    }
    return view('walee-tickets', ['activeTab' => $tab]);
})->middleware(['auth'])->where('tab', 'todos|enviados|recibidos|resueltos')->name('walee.tickets.tab');

// Tareas
Route::get('/walee-tareas', function () {
    return view('walee-tareas');
})->middleware(['auth'])->name('walee.tareas');

// Calendario
Route::get('/walee-calendario', function () {
    return view('walee-calendario');
})->middleware(['auth'])->name('walee.calendario');

// Planeador de Publicidad - Requiere cliente_id
Route::get('/walee-planeador-publicidad/{cliente_id}', function ($cliente_id) {
    $cliente = \App\Models\Cliente::findOrFail($cliente_id);
    
    // Si no viene vista, redirigir a vista semanal
    if (!request()->has('vista')) {
        $semanaActual = now()->format('Y-W');
        return redirect()->route('walee.planeador.publicidad', [
            'cliente_id' => $cliente_id,
            'vista' => 'semanal',
            'semana' => $semanaActual
        ]);
    }
    
    return view('walee-planeador-publicidad', compact('cliente'));
})->middleware(['auth'])->name('walee.planeador.publicidad');

// Rutas para eventos de publicidad
Route::post('/publicidad-eventos', function (\Illuminate\Http\Request $request) {
    try {
        $evento = new \App\Models\PublicidadEvento();
        $evento->titulo = $request->input('titulo');
        $evento->descripcion = $request->input('descripcion');
        $evento->cliente_id = $request->input('cliente_id');
        $evento->tipo_publicidad = $request->input('tipo_publicidad');
        $evento->plataforma = $request->input('plataforma');
        $evento->estado = $request->input('estado', 'programado');
        $evento->fecha_inicio = \Carbon\Carbon::parse($request->input('fecha_inicio'));
        $evento->fecha_fin = $request->input('fecha_fin') ? \Carbon\Carbon::parse($request->input('fecha_fin')) : null;
        $evento->color = $request->input('color', '#8b5cf6');
        $evento->recurrencia = $request->input('recurrencia', 'none');
        $evento->recurrencia_fin = $request->input('recurrencia_fin') ? \Carbon\Carbon::parse($request->input('recurrencia_fin')) : null;
        $evento->save();
        
        return response()->json(['success' => true, 'message' => 'Evento de publicidad creado exitosamente']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
})->middleware(['auth']);

Route::put('/publicidad-eventos/{id}', function (\Illuminate\Http\Request $request, $id) {
    try {
        $evento = \App\Models\PublicidadEvento::findOrFail($id);
        $evento->titulo = $request->input('titulo');
        $evento->descripcion = $request->input('descripcion');
        $evento->tipo_publicidad = $request->input('tipo_publicidad');
        $evento->plataforma = $request->input('plataforma');
        $evento->estado = $request->input('estado', 'programado');
        $evento->fecha_inicio = \Carbon\Carbon::parse($request->input('fecha_inicio'));
        $evento->fecha_fin = $request->input('fecha_fin') ? \Carbon\Carbon::parse($request->input('fecha_fin')) : null;
        $evento->color = $request->input('color', '#8b5cf6');
        $evento->recurrencia = $request->input('recurrencia', 'none');
        $evento->recurrencia_fin = $request->input('recurrencia_fin') ? \Carbon\Carbon::parse($request->input('recurrencia_fin')) : null;
        $evento->save();
        
        return response()->json(['success' => true, 'message' => 'Evento de publicidad actualizado exitosamente']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
})->middleware(['auth']);

Route::delete('/publicidad-eventos/{id}', function ($id) {
    try {
        $evento = \App\Models\PublicidadEvento::findOrFail($id);
        $evento->delete();
        
        return response()->json(['success' => true, 'message' => 'Evento de publicidad eliminado exitosamente']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
})->middleware(['auth']);

// Generar texto con AI para publicaciones
Route::post('/publicidad-eventos/generar-texto-ai', function (\Illuminate\Http\Request $request) {
    try {
        $apiKey = config('services.openai.api_key');
        
        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'Falta OPENAI_API_KEY. Configura la API key en el servidor.',
            ], 500);
        }
        
        $prompt = $request->input('prompt');
        $clienteId = $request->input('cliente_id');
        $clienteNombre = $request->input('cliente_nombre', 'el cliente');
        
        $cliente = \App\Models\Cliente::find($clienteId);
        if ($cliente) {
            $clienteNombre = $cliente->nombre_empresa;
        }
        
        $systemPrompt = 'You are an expert in digital marketing and social media content creation. Generate creative, attractive, and professional texts for social media posts. The text must be engaging, use emojis strategically, and have a clear call to action. Respond ONLY with the post text, no additional explanations. The text must be exactly 50 words in English.';
        
        $userPrompt = $prompt . ($clienteNombre !== 'el cliente' ? " The client is {$clienteNombre}." : '') . " Generate exactly 50 words in English.";
        
        $response = \Illuminate\Support\Facades\Http::withToken($apiKey)
            ->timeout(60)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $systemPrompt,
                    ],
                    [
                        'role' => 'user',
                        'content' => $userPrompt,
                    ],
                ],
            ]);
        
        if ($response->successful()) {
            $responseData = $response->json();
            $texto = $responseData['choices'][0]['message']['content'] ?? '';
            
            if (empty($texto)) {
                throw new \RuntimeException('La respuesta de AI está vacía.');
            }
            
            return response()->json([
                'success' => true,
                'texto' => trim($texto),
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
})->middleware(['auth']);

// Programar publicación con imagen
Route::post('/publicidad-eventos/programar', function (\Illuminate\Http\Request $request) {
    try {
        $evento = new \App\Models\PublicidadEvento();
        $evento->titulo = 'Publicación programada';
        $evento->texto = $request->input('texto');
        $evento->cliente_id = $request->input('cliente_id');
        $evento->tipo_publicidad = null; // Ya no se usa
        $evento->plataforma = $request->input('plataforma_publicacion');
        $evento->estado = 'programado';
        $evento->fecha_inicio = \Carbon\Carbon::parse($request->input('fecha_publicacion'));
        $evento->color = '#8b5cf6';
        
        // Subir imagen si existe
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreArchivo = 'publicidad_' . $evento->cliente_id . '_' . time() . '.' . $imagen->getClientOriginalExtension();
            $ruta = $imagen->storeAs('publicidad', $nombreArchivo, 'public');
            $evento->imagen_url = str_replace('public/', '', $ruta);
        }
        
        $evento->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Publicación programada exitosamente',
            'evento_id' => $evento->id
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
})->middleware(['auth']);

Route::get('/citas/{id}/detalle', function ($id) {
    $cita = \App\Models\Cita::with('cliente')->findOrFail($id);
    $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
    ];
    $clientes = \App\Models\Cliente::orderBy('nombre_empresa')->get();
    return view('walee-cita-detalle', compact('cita', 'meses', 'clientes'));
})->middleware(['auth'])->name('walee.cita.detalle');

Route::get('/walee-tareas/{id}/detalle', function ($id) {
    $tarea = \App\Models\Tarea::with('lista')->findOrFail($id);
    $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
    ];
    $listas = \App\Models\Lista::orderBy('nombre')->get();
    $tiposExistentes = \App\Models\Tarea::select('tipo')->distinct()->whereNotNull('tipo')->pluck('tipo');
    return view('walee-tarea-detalle', compact('tarea', 'meses', 'listas', 'tiposExistentes'));
})->middleware(['auth'])->name('walee.tarea.detalle');

Route::get('/walee-calendario/dia/{ano}/{mes}/{dia}', function ($ano, $mes, $dia) {
    $fecha = \Carbon\Carbon::create($ano, $mes, $dia);
    
    // Obtener citas del día
    $citas = \App\Models\Cita::with('cliente')
        ->whereDate('fecha_inicio', $fecha->format('Y-m-d'))
        ->orderBy('fecha_inicio', 'asc')
        ->get();
    
    // Obtener tareas del día
    $tareas = \App\Models\Tarea::with('lista')
        ->whereNotNull('fecha_hora')
        ->whereDate('fecha_hora', $fecha->format('Y-m-d'))
        ->orderBy('fecha_hora', 'asc')
        ->get();
    
    // Obtener notas del día
    $notas = \App\Models\Note::with(['cliente', 'user'])
        ->whereDate('fecha', $fecha->format('Y-m-d'))
        ->orderBy('pinned', 'desc')
        ->orderBy('created_at', 'asc')
        ->get();
    
    // Combinar y ordenar por hora
    $items = collect();
    
    foreach ($citas as $cita) {
        $clienteNombre = null;
        if ($cita->cliente_id && $cita->cliente) {
            $clienteNombre = $cita->cliente->nombre_empresa;
        }
        
        $items->push([
            'tipo' => 'cita',
            'id' => $cita->id,
            'titulo' => $cita->titulo,
            'hora' => $cita->fecha_inicio,
            'hora_fin' => $cita->fecha_fin,
            'color' => $cita->color ?? '#10b981',
            'estado' => $cita->estado,
            'cliente_id' => $cita->cliente_id,
            'cliente' => $clienteNombre,
            'ubicacion' => $cita->ubicacion,
            'descripcion' => $cita->descripcion,
            'data' => $cita
        ]);
    }
    
    foreach ($tareas as $tarea) {
        $items->push([
            'tipo' => 'tarea',
            'id' => $tarea->id,
            'titulo' => $tarea->texto,
            'hora' => $tarea->fecha_hora,
            'hora_fin' => null,
            'color' => '#8b5cf6', // Violeta para tareas
            'estado' => $tarea->estado,
            'tipo_tarea' => $tarea->tipo,
            'lista' => $tarea->lista->nombre ?? null,
            'descripcion' => null, // Las tareas no tienen descripción
            'data' => $tarea
        ]);
    }
    
    foreach ($notas as $nota) {
        $items->push([
            'tipo' => 'nota',
            'id' => $nota->id,
            'titulo' => Str::limit($nota->content, 50),
            'hora' => $nota->fecha ? \Carbon\Carbon::parse($nota->fecha)->setTime(12, 0) : $fecha->setTime(12, 0),
            'hora_fin' => null,
            'color' => '#3b82f6', // Azul para notas
            'estado' => null,
            'tipo_nota' => $nota->type,
            'cliente' => $nota->cliente->nombre_empresa ?? null,
            'pinned' => $nota->pinned,
            'descripcion' => $nota->content,
            'data' => $nota
        ]);
    }
    
    $items = $items->sortBy('hora');
    
    $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
    ];
    
    $clientes = \App\Models\Cliente::orderBy('nombre_empresa')->get();
    $listas = \App\Models\Lista::orderBy('nombre')->get();
    $tiposExistentes = \App\Models\Tarea::select('tipo')->distinct()->whereNotNull('tipo')->pluck('tipo');
    
    return view('walee-calendario-dia', compact('fecha', 'items', 'meses', 'citas', 'tareas', 'notas', 'clientes', 'listas', 'tiposExistentes'));
})->middleware(['auth'])->name('walee.calendario.dia');

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
        $cita->notas = $request->input('notas');
        $cita->estado = $request->input('estado', 'programada');
        $cita->recurrencia = $request->input('recurrencia', 'none');
        $cita->recurrencia_fin = $request->input('recurrencia_fin') ? \Carbon\Carbon::parse($request->input('recurrencia_fin')) : null;
        $cita->recurrencia_dias = $request->input('recurrencia_dias');
        $cita->color = $request->input('color', '#10b981');
        $cita->invitados_emails = $request->input('invitados_emails');
        $cita->save();
        
        // Sincronizar con Google Calendar
        $googleEventUrl = null;
        try {
            $googleService = new \App\Services\GoogleCalendarService();
            if ($googleService->isAuthorized()) {
                $eventId = $googleService->createEvent($cita);
                if ($eventId) {
                    $cita->google_event_id = $eventId;
                    $cita->save();
                    // Obtener URL del evento para enviar por email
                    $googleEventUrl = $googleService->getEventUrl($eventId);
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Error sincronizando con Google Calendar: ' . $e->getMessage());
            // No fallar la creación de la cita si falla la sincronización
        }
        
        // Enviar email al cliente si tiene correo y se creó el evento en Google Calendar
        if ($cita->cliente_id && $cita->cliente && $cita->cliente->correo && $googleEventUrl) {
            try {
                $cita->load('cliente');
                \Illuminate\Support\Facades\Mail::to($cita->cliente->correo)
                    ->send(new \App\Mail\CitaCreadaMail($cita, $googleEventUrl));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Error enviando email de cita al cliente: ' . $e->getMessage());
                // No fallar la creación de la cita si falla el envío del email
            }
        }
        
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
        $cita->notas = $request->input('notas');
        $cita->estado = $request->input('estado', 'programada');
        $cita->recurrencia = $request->input('recurrencia', 'none');
        $cita->recurrencia_fin = $request->input('recurrencia_fin') ? \Carbon\Carbon::parse($request->input('recurrencia_fin')) : null;
        $cita->recurrencia_dias = $request->input('recurrencia_dias');
        $cita->color = $request->input('color', '#10b981');
        $cita->save();
        
        // Sincronizar con Google Calendar
        try {
            $googleService = new \App\Services\GoogleCalendarService();
            if ($googleService->isAuthorized()) {
                if ($cita->google_event_id) {
                    // Si ya tiene ID, actualizar
                    $googleService->updateEvent($cita);
                } else {
                    // Si no tiene ID, crear uno nuevo
                    $eventId = $googleService->createEvent($cita);
                    if ($eventId) {
                        $cita->google_event_id = $eventId;
                        $cita->save();
                    }
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Error sincronizando con Google Calendar: ' . $e->getMessage());
            // No fallar la actualización de la cita si falla la sincronización
        }
        
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
        
        // Eliminar de Google Calendar si existe
        try {
            $googleService = new \App\Services\GoogleCalendarService();
            if ($googleService->isAuthorized() && $cita->google_event_id) {
                $googleService->deleteEvent($cita);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Error eliminando de Google Calendar: ' . $e->getMessage());
            // Continuar con la eliminación local aunque falle la sincronización
        }
        
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
        $imagePaths = [];
        
        // Manejar múltiples archivos enviados como archivos[0], archivos[1], etc.
        if ($request->has('archivos')) {
            $archivos = $request->file('archivos');
            
            // Si es un array de archivos
            if (is_array($archivos)) {
                foreach ($archivos as $archivo) {
                    if ($archivo && $archivo->isValid()) {
                        // Validar tipo de archivo
                        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf'];
                        if (in_array($archivo->getMimeType(), $allowedMimes)) {
                            $path = $archivo->store('tickets', 'public');
                            $imagePaths[] = $path;
                        }
                    }
                }
            } 
            // Si es un solo archivo (compatibilidad)
            elseif ($archivos && $archivos->isValid()) {
                $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf'];
                if (in_array($archivos->getMimeType(), $allowedMimes)) {
                    $path = $archivos->store('tickets', 'public');
                    $imagePaths[] = $path;
                }
            }
        }
        
        // Mantener compatibilidad con el campo 'imagen' antiguo (un solo archivo)
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            if ($imagen->isValid()) {
                $imagePaths[] = $imagen->store('tickets', 'public');
            }
        }
        
        // Guardar como JSON si hay múltiples archivos, o como string si hay uno solo, o null si no hay
        $imagenValue = null;
        if (count($imagePaths) === 1) {
            $imagenValue = $imagePaths[0];
        } elseif (count($imagePaths) > 1) {
            $imagenValue = json_encode($imagePaths);
        }
        
        $ticket = \App\Models\Ticket::create([
            'user_id' => auth()->id(),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'telefono' => $request->input('telefono'),
            'website' => $request->input('website'),
            'asunto' => $request->input('asunto'),
            'mensaje' => $request->input('mensaje'),
            'imagen' => $imagenValue,
            'estado' => 'enviado',
            'urgente' => $request->has('urgente') && $request->input('urgente') == '1',
            'prioritario' => $request->has('prioritario') && $request->input('prioritario') == '1',
            'a_discutir' => $request->has('a_discutir') && $request->input('a_discutir') == '1',
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Ticket enviado correctamente',
            'ticket_id' => $ticket->id,
            'archivos_guardados' => count($imagePaths),
        ]);
    } catch (\Exception $e) {
        \Log::error('Error al crear ticket: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('walee.tickets.store');

Route::post('/walee-tickets/{id}/urgente', function ($id) {
    try {
        $ticket = \App\Models\Ticket::findOrFail($id);
        $nuevoEstado = !$ticket->urgente;
        
        // Si se activa urgente, desactivar los otros
        if ($nuevoEstado) {
            $ticket->urgente = true;
            $ticket->prioritario = false;
            $ticket->a_discutir = false;
        } else {
            $ticket->urgente = false;
        }
        $ticket->save();
        
        return response()->json([
            'success' => true,
            'urgente' => $ticket->urgente,
            'prioritario' => $ticket->prioritario,
            'a_discutir' => $ticket->a_discutir,
            'message' => $ticket->urgente ? 'Ticket marcado como urgente' : 'Urgente removido',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('walee.tickets.urgente');

Route::post('/walee-tickets/{id}/prioritario', function ($id) {
    try {
        $ticket = \App\Models\Ticket::findOrFail($id);
        $nuevoEstado = !$ticket->prioritario;
        
        // Si se activa prioritario, desactivar los otros
        if ($nuevoEstado) {
            $ticket->urgente = false;
            $ticket->prioritario = true;
            $ticket->a_discutir = false;
        } else {
            $ticket->prioritario = false;
        }
        $ticket->save();
        
        return response()->json([
            'success' => true,
            'urgente' => $ticket->urgente,
            'prioritario' => $ticket->prioritario,
            'a_discutir' => $ticket->a_discutir,
            'message' => $ticket->prioritario ? 'Ticket marcado como prioritario' : 'Prioritario removido',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('walee.tickets.prioritario');

Route::post('/walee-tickets/{id}/a-discutir', function ($id) {
    try {
        $ticket = \App\Models\Ticket::findOrFail($id);
        $nuevoEstado = !$ticket->a_discutir;
        
        // Si se activa a_discutir, desactivar los otros
        if ($nuevoEstado) {
            $ticket->urgente = false;
            $ticket->prioritario = false;
            $ticket->a_discutir = true;
        } else {
            $ticket->a_discutir = false;
        }
        $ticket->save();
        
        return response()->json([
            'success' => true,
            'urgente' => $ticket->urgente,
            'prioritario' => $ticket->prioritario,
            'a_discutir' => $ticket->a_discutir,
            'message' => $ticket->a_discutir ? 'Ticket marcado como a discutir' : 'A discutir removido',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('walee.tickets.a-discutir');

Route::put('/walee-tickets/{id}', function (\Illuminate\Http\Request $request, $id) {
    try {
        $ticket = \App\Models\Ticket::findOrFail($id);
        
        $ticket->update([
            'asunto' => $request->input('asunto'),
            'mensaje' => $request->input('mensaje'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'telefono' => $request->input('telefono'),
            'website' => $request->input('website'),
            'estado' => $request->input('estado'),
            'urgente' => $request->has('urgente') && $request->input('urgente'),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Ticket actualizado correctamente',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->where('id', '[0-9]+')->middleware(['auth'])->name('walee.tickets.update');

Route::delete('/walee-tickets/{id}', function ($id) {
    try {
        $ticket = \App\Models\Ticket::findOrFail($id);
        $ticket->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Ticket eliminado correctamente',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->where('id', '[0-9]+')->middleware(['auth'])->name('walee.tickets.delete');

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
                    'email_to' => 'whensolresolve@gmail.com',
                    'email_subject' => "[SUPPORT] Ticket Recibido: {$ticket->asunto}",
                    'email_body' => "Hola {$nombre},\n\nRecibimos su ticket con el asunto: \"{$ticket->asunto}\"\n\nLe avisaremos cuando esté resuelto.\n\nSaludos,\nWeb Solutions",
                    'email_from' => 'websolutionscrnow@gmail.com',
                    'email_from_name' => 'Web Solutions',
                    // Identificador para filtro de Gmail
                    'email_filter_keyword' => '[SUPPORT]',
                    // Etiquetas para Gmail
                    'email_label' => 'SUPPORT',
                    'gmail_label' => 'SUPPORT',
                    'label' => 'SUPPORT',
                    'labels' => ['SUPPORT'],
                    'labelIds' => ['SUPPORT'],
                    'gmail_labels' => ['SUPPORT'],
                    'addLabel' => 'SUPPORT',
                    // Configuración para que no caiga en Primary
                    'skip_inbox' => true,
                    'archive' => true,
                    'gmail_category' => 'SUPPORT',
                    'category' => 'SUPPORT',
                    // Headers personalizados para Gmail
                    'headers' => [
                        'X-Gmail-Label' => 'SUPPORT',
                        'X-Auto-Response-Suppress' => 'All',
                        'List-Unsubscribe' => '<mailto:websolutionscrnow@gmail.com>',
                    ],
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
                    'email_to' => 'whensolresolve@gmail.com',
                    'email_subject' => "[SUPPORT] Ticket Resuelto: {$ticket->asunto}",
                    'email_body' => "Hola {$nombre},\n\nSu ticket con el asunto: \"{$ticket->asunto}\" ha sido resuelto.\n\nGracias por contactarnos.\n\nSaludos,\nWeb Solutions",
                    'email_from' => 'websolutionscrnow@gmail.com',
                    'email_from_name' => 'Web Solutions',
                    // Identificador para filtro de Gmail
                    'email_filter_keyword' => '[SUPPORT]',
                    // Etiquetas para Gmail
                    'email_label' => 'SUPPORT',
                    'gmail_label' => 'SUPPORT',
                    'label' => 'SUPPORT',
                    'labels' => ['SUPPORT'],
                    'labelIds' => ['SUPPORT'],
                    'gmail_labels' => ['SUPPORT'],
                    'addLabel' => 'SUPPORT',
                    // Configuración para que no caiga en Primary
                    'skip_inbox' => true,
                    'archive' => true,
                    'gmail_category' => 'SUPPORT',
                    'category' => 'SUPPORT',
                    // Headers personalizados para Gmail
                    'headers' => [
                        'X-Gmail-Label' => 'SUPPORT',
                        'X-Auto-Response-Suppress' => 'All',
                        'List-Unsubscribe' => '<mailto:websolutionscrnow@gmail.com>',
                    ],
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

Route::post('/walee-clientes-en-proceso/delete', function (\Illuminate\Http\Request $request) {
    $clientIds = $request->input('client_ids', []);
    
    if (empty($clientIds) || !is_array($clientIds)) {
        return response()->json([
            'success' => false,
            'message' => 'No se proporcionaron IDs de clientes'
        ], 400);
    }
    
    try {
        $deleted = \App\Models\Client::whereIn('id', $clientIds)->delete();
        
        return response()->json([
            'success' => true,
            'message' => "Se borraron {$deleted} cliente(s) exitosamente",
            'deleted_count' => $deleted
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al borrar clientes: ' . $e->getMessage()
        ], 500);
    }
})->middleware(['auth'])->name('walee.clientes.en-proceso.delete');

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

Route::get('/walee-emails/sitios', function (\Illuminate\Http\Request $request) {
    $clienteId = $request->get('cliente_id');
    
    if (!$clienteId) {
        return response()->json([
            'success' => false,
            'message' => 'Cliente ID requerido',
            'sitios' => []
        ]);
    }
    
    // Buscar sitios por cliente_id directamente
    // También buscar por nombre del cliente en caso de que el cliente_id no coincida
    $client = \App\Models\Client::find($clienteId);
    
    $sitios = collect();
    
    if ($client) {
        // Buscar sitios que coincidan con el nombre del cliente o el ID
        $sitios = \App\Models\Sitio::where(function($query) use ($client, $clienteId) {
            $query->where('cliente_id', $clienteId)
                  ->orWhereHas('cliente', function($q) use ($client) {
                      $q->where('nombre_empresa', 'like', '%' . $client->name . '%')
                        ->orWhere('correo', $client->email);
                  });
        })
        ->where('en_linea', true)
        ->orderBy('nombre')
        ->get(['id', 'nombre', 'enlace', 'descripcion']);
    }
    
    // Si no hay resultados, buscar todos los sitios en línea
    if ($sitios->isEmpty()) {
        $sitios = \App\Models\Sitio::where('en_linea', true)
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'enlace', 'descripcion']);
    }
    
    return response()->json([
        'success' => true,
        'sitios' => $sitios
    ]);
})->middleware(['auth'])->name('walee.emails.sitios');

Route::get('/walee-emails/enviados', function () {
    return view('walee-emails-enviados');
})->middleware(['auth'])->name('walee.emails.enviados');

Route::get('/walee-emails/templates', function () {
    $templates = \App\Models\EmailTemplate::where('user_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->get();
    return view('walee-emails-templates', compact('templates'));
})->middleware(['auth'])->name('walee.emails.templates');

// Rutas para Email Templates
Route::post('/email-templates', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'nombre' => 'required|string|max:255',
        'asunto' => 'required|string|max:255',
        'contenido' => 'required|string',
        'ai_prompt' => 'nullable|string',
    ]);
    
    $template = \App\Models\EmailTemplate::create([
        'nombre' => $validated['nombre'],
        'asunto' => $validated['asunto'],
        'contenido' => $validated['contenido'],
        'ai_prompt' => $validated['ai_prompt'] ?? null,
        'user_id' => auth()->id(),
    ]);
    
    return response()->json([
        'success' => true,
        'message' => 'Template guardado correctamente',
        'template' => $template,
    ]);
})->middleware(['auth'])->name('email-templates.store');

Route::put('/email-templates/{id}', function (\Illuminate\Http\Request $request, $id) {
    $template = \App\Models\EmailTemplate::where('id', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail();
    
    $validated = $request->validate([
        'nombre' => 'required|string|max:255',
        'asunto' => 'required|string|max:255',
        'contenido' => 'required|string',
        'ai_prompt' => 'nullable|string',
    ]);
    
    $template->update([
        'nombre' => $validated['nombre'],
        'asunto' => $validated['asunto'],
        'contenido' => $validated['contenido'],
        'ai_prompt' => $validated['ai_prompt'] ?? null,
    ]);
    
    return response()->json([
        'success' => true,
        'message' => 'Template actualizado correctamente',
        'template' => $template,
    ]);
})->middleware(['auth'])->name('email-templates.update');

Route::delete('/email-templates/{id}', function ($id) {
    $template = \App\Models\EmailTemplate::where('id', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail();
    
    $template->delete();
    
    return response()->json([
        'success' => true,
        'message' => 'Template eliminado correctamente',
    ]);
})->middleware(['auth'])->name('email-templates.destroy');

// Ruta para enviar email desde template (usa la misma ruta existente /walee-emails/enviar)

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
        $sitioId = $request->input('sitio_id');
        $sitioNombre = $request->input('sitio_nombre', '');
        $sitioEnlace = $request->input('sitio_enlace', '');
        
        // Construir el prompt
        if (empty($aiPrompt)) {
            $prompt = "Genera un email profesional de propuesta personalizada para {$clientName}";
            if ($clientWebsite) {
                $prompt .= " cuyo sitio web es {$clientWebsite}";
            }
            
            // Agregar información del sitio si está seleccionado
            if ($sitioId && $sitioNombre && $sitioEnlace) {
                $prompt .= ". IMPORTANTE: Incluye en el email que {$sitioNombre} ({$sitioEnlace}) es un ejemplo de los sitios/proyectos que hemos creado. Menciona esto de manera natural en el contenido del email, destacando que es uno de nuestros proyectos exitosos y que pueden visitarlo en: {$sitioEnlace}";
            }
            $prompt .= ". El email debe ser persuasivo, profesional y enfocado en ofrecer servicios de diseño web, marketing digital y desarrollo de software. IMPORTANTE: Menciona que quien envía el email se llama Memphis.";
        } else {
            $prompt = "Genera un email profesional. {$aiPrompt}";
            if ($clientName !== 'el cliente') {
                $prompt .= " El cliente se llama {$clientName}.";
            }
            if ($clientWebsite) {
                $prompt .= " Su sitio web es {$clientWebsite}.";
            }
            
            // Agregar información del sitio si está seleccionado
            if ($sitioId && $sitioNombre && $sitioEnlace) {
                $prompt .= " IMPORTANTE: Incluye en el email que {$sitioNombre} ({$sitioEnlace}) es un ejemplo de los sitios/proyectos que hemos creado. Menciona esto de manera natural en el contenido del email, destacando que es uno de nuestros proyectos exitosos y que pueden visitarlo en: {$sitioEnlace}";
            }
            $prompt .= " IMPORTANTE: Menciona que quien envía el email se llama Memphis.";
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
                        'content' => 'Eres un experto en marketing digital y redacción de emails comerciales. Genera emails profesionales, persuasivos y directos. Responde SOLO con JSON que contenga "subject" (asunto del email, máximo 10 palabras) y "body" (cuerpo del email completo). NO incluyas mensajes de cierre como "Si necesitas alguna modificación", "No dudes en contactarme", etc. IMPORTANTE: En el cuerpo del email, SIEMPRE menciona que quien envía el email se llama Memphis. Al final del body, SIEMPRE incluye esta firma: "\n\nMemphis\nWeb Solutions\nwebsolutionscrnow@gmail.com\n+506 8806 1829 (WhatsApp)\nwebsolutions.work"',
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
        $sitioId = $request->input('sitio_id');
        $enlace = $request->input('enlace');
        
        $client = $clienteId ? \App\Models\Client::find($clienteId) : null;
        
        // Manejar múltiples archivos
        $attachmentPaths = [];
        
        // Manejar archivos enviados como archivos[0], archivos[1], etc.
        if ($request->has('archivos')) {
            $archivos = $request->file('archivos');
            
            if (is_array($archivos)) {
                foreach ($archivos as $archivo) {
                    if ($archivo && $archivo->isValid()) {
                        $attachmentName = time() . '_' . $archivo->getClientOriginalName();
                        $path = $archivo->storeAs('email-attachments', $attachmentName, 'public');
                        $attachmentPaths[] = $path;
                    }
                }
            } elseif ($archivos && $archivos->isValid()) {
                $attachmentName = time() . '_' . $archivos->getClientOriginalName();
                $path = $archivos->storeAs('email-attachments', $attachmentName, 'public');
                $attachmentPaths[] = $path;
            }
        }
        
        // Mantener compatibilidad con el campo 'attachment' antiguo (un solo archivo)
        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            if ($attachment->isValid()) {
                $attachmentName = time() . '_' . $attachment->getClientOriginalName();
                $path = $attachment->storeAs('email-attachments', $attachmentName, 'public');
                $attachmentPaths[] = $path;
            }
        }
        
        // Enviar email con adjuntos si existen
        \Illuminate\Support\Facades\Mail::raw($body, function ($message) use ($email, $subject, $attachmentPaths) {
            $message->from('websolutionscrnow@gmail.com', 'Memphis - Web Solutions')
                    ->to($email)
                    ->subject($subject);
            
            // Attach all files
            foreach ($attachmentPaths as $attachmentPath) {
                $fullPath = storage_path('app/public/' . $attachmentPath);
                if (file_exists($fullPath)) {
                    $message->attach($fullPath, [
                        'as' => basename($attachmentPath),
                        'mime' => mime_content_type($fullPath),
                    ]);
                }
            }
        });
        
        // Guardar como JSON si hay múltiples archivos, o como string si hay uno solo, o null si no hay
        $attachmentValue = null;
        if (count($attachmentPaths) === 1) {
            $attachmentValue = $attachmentPaths[0];
        } elseif (count($attachmentPaths) > 1) {
            $attachmentValue = json_encode($attachmentPaths);
        }
        
        // Guardar en la base de datos
        \App\Models\PropuestaPersonalizada::create([
            'cliente_id' => $clienteId ?: null,
            'cliente_nombre' => $client?->name ?? 'N/A',
            'email' => $email,
            'subject' => $subject,
            'body' => $body,
            'ai_prompt' => $aiPrompt ?: null,
            'sitio_id' => $sitioId ?: null,
            'enlace' => $enlace ?: null,
            'attachment' => $attachmentValue,
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
    try {
        $gmailService = app(\App\Services\GmailService::class);
        
        // Verificar si está autorizado
        if (!$gmailService->isAuthorized()) {
            return response()->json([
                'success' => false,
                'message' => 'Gmail no está autorizado. Por favor, autoriza el acceso primero.',
                'auth_url' => $gmailService->getAuthUrl(),
            ], 401);
        }
        
        // Sincronizar emails (solo los de clientes_en_proceso)
        $result = $gmailService->syncEmails(50);
        
        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => "Sincronización completada. {$result['synced']} emails guardados, {$result['skipped']} omitidos.",
                'synced' => $result['synced'],
                'skipped' => $result['skipped'],
                'errors' => $result['errors'] ?? 0,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Error al sincronizar: ' . ($result['error'] ?? 'Error desconocido'),
            ], 500);
        }
    } catch (\Exception $e) {
        \Log::error('Excepción al sincronizar emails: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Error al sincronizar: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('walee.emails.recibidos.sync');

// Ruta para Configuraciones
Route::get('/walee-configuraciones', function () {
    return view('walee-configuraciones');
})->middleware(['auth'])->name('walee.configuraciones');

// Ruta para ejecutar Git Pull via webhook
Route::post('/walee-configuraciones/git-pull', function (\Illuminate\Http\Request $request, \App\Services\N8nService $n8nService) {
    $webhookUrl = 'https://n8n.srv1137974.hstgr.cloud/webhook/753549e6-c8ff-4fcd-9b08-bcfe8b82cdc1';
    
    // Crear log
    $log = \App\Models\CommandLog::create([
        'action' => 'git_pull',
        'command' => 'git pull origin main',
        'user_id' => auth()->id(),
        'user_name' => auth()->user()->name ?? 'Usuario',
        'status' => 'pending',
        'executed_at' => now(),
    ]);
    
    try {
        $payload = [
            'action' => 'git_pull',
            'command' => 'git pull origin main',
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name ?? 'Usuario',
            'timestamp' => now()->toIso8601String(),
        ];
        
        $success = $n8nService->sendWebhook($webhookUrl, $payload);
        
        if ($success) {
            $log->update([
                'status' => 'success',
                'response' => 'Comando enviado exitosamente a n8n',
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Comando git pull origin main enviado exitosamente a n8n.',
                'log_id' => $log->id,
            ]);
        } else {
            $log->update([
                'status' => 'error',
                'error_message' => 'Error al enviar el comando a n8n',
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar el comando a n8n.',
            ], 500);
        }
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Error en git pull webhook: ' . $e->getMessage());
        $log->update([
            'status' => 'error',
            'error_message' => $e->getMessage(),
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Error interno del servidor: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('walee.configuraciones.git-pull');

// Ruta para ejecutar Migrate via webhook
Route::post('/walee-configuraciones/migrate', function (\Illuminate\Http\Request $request, \App\Services\N8nService $n8nService) {
    $webhookUrl = 'https://n8n.srv1137974.hstgr.cloud/webhook/753549e6-c8ff-4fcd-9b08-bcfe8b82cdc1';
    
    // Crear log
    $log = \App\Models\CommandLog::create([
        'action' => 'migrate',
        'command' => 'php artisan migrate',
        'user_id' => auth()->id(),
        'user_name' => auth()->user()->name ?? 'Usuario',
        'status' => 'pending',
        'executed_at' => now(),
    ]);
    
    try {
        $payload = [
            'action' => 'migrate',
            'command' => 'php artisan migrate',
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name ?? 'Usuario',
            'timestamp' => now()->toIso8601String(),
        ];
        
        $success = $n8nService->sendWebhook($webhookUrl, $payload);
        
        if ($success) {
            $log->update([
                'status' => 'success',
                'response' => 'Comando enviado exitosamente a n8n',
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Comando php artisan migrate enviado exitosamente a n8n.',
                'log_id' => $log->id,
            ]);
        } else {
            $log->update([
                'status' => 'error',
                'error_message' => 'Error al enviar el comando a n8n',
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar el comando a n8n.',
            ], 500);
        }
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Error en migrate webhook: ' . $e->getMessage());
        $log->update([
            'status' => 'error',
            'error_message' => $e->getMessage(),
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Error interno del servidor: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('walee.configuraciones.migrate');

// Ruta para ejecutar comando personalizado via webhook
Route::post('/walee-configuraciones/custom-command', function (\Illuminate\Http\Request $request, \App\Services\N8nService $n8nService) {
    $webhookUrl = 'https://n8n.srv1137974.hstgr.cloud/webhook/753549e6-c8ff-4fcd-9b08-bcfe8b82cdc1';
    
    $request->validate([
        'command' => 'required|string|max:500',
    ]);
    
    // Crear log
    $log = \App\Models\CommandLog::create([
        'action' => 'custom_command',
        'command' => $request->input('command'),
        'user_id' => auth()->id(),
        'user_name' => auth()->user()->name ?? 'Usuario',
        'status' => 'pending',
        'executed_at' => now(),
    ]);
    
    try {
        $payload = [
            'action' => 'custom_command',
            'command' => $request->input('command'),
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name ?? 'Usuario',
            'timestamp' => now()->toIso8601String(),
        ];
        
        $success = $n8nService->sendWebhook($webhookUrl, $payload);
        
        if ($success) {
            $log->update([
                'status' => 'success',
                'response' => 'Comando enviado exitosamente a n8n',
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Comando personalizado enviado exitosamente a n8n.',
                'log_id' => $log->id,
            ]);
        } else {
            $log->update([
                'status' => 'error',
                'error_message' => 'Error al enviar el comando a n8n',
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar el comando a n8n.',
            ], 500);
        }
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Error en custom command webhook: ' . $e->getMessage());
        $log->update([
            'status' => 'error',
            'error_message' => $e->getMessage(),
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Error interno del servidor: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('walee.configuraciones.custom-command');

// Ruta para obtener logs de comandos
Route::post('/walee-configuraciones/clear-emails', function () {
    try {
        \App\Models\EmailRecibido::truncate();
        return response()->json([
            'success' => true,
            'message' => 'Todos los emails recibidos han sido borrados correctamente.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al borrar los emails: ' . $e->getMessage()
        ], 500);
    }
})->middleware(['auth', 'verified'])->name('walee.configuraciones.clear-emails');

Route::get('/walee-configuraciones/logs', function () {
    try {
        // Verificar si la tabla existe
        if (!\Illuminate\Support\Facades\Schema::hasTable('command_logs')) {
            return response()->json([]);
        }
        
        $logs = \App\Models\CommandLog::orderBy('created_at', 'desc')
            ->limit(50)
            ->get();
        
        return response()->json($logs);
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Error al obtener logs: ' . $e->getMessage());
        return response()->json([], 200);
    }
})->middleware(['auth'])->name('walee.configuraciones.logs');

// Ruta para Facturas & Cotizaciones
Route::get('/walee-facturas', function () {
    return view('walee-facturas');
})->middleware(['auth'])->name('walee.facturas');

Route::get('/walee-facturas/crear', function () {
    return view('walee-facturas-crear');
})->middleware(['auth'])->name('walee.facturas.crear');

Route::post('/walee-facturas/guardar', function (\Illuminate\Http\Request $request) {
    try {
        \DB::beginTransaction();
        
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
        
        // Guardar items de la factura
        $items = $request->input('items', []);
        foreach ($items as $index => $item) {
            if (!empty($item['descripcion']) && !empty($item['precio_unitario'])) {
                \App\Models\FacturaItem::create([
                    'factura_id' => $factura->id,
                    'descripcion' => $item['descripcion'],
                    'cantidad' => $item['cantidad'] ?? 1,
                    'precio_unitario' => $item['precio_unitario'],
                    'subtotal' => ($item['cantidad'] ?? 1) * $item['precio_unitario'],
                    'orden' => $index,
                ]);
            }
        }
        
        // Guardar pagos recibidos
        $pagos = $request->input('pagos', []);
        foreach ($pagos as $pago) {
            if (!empty($pago['descripcion']) && !empty($pago['importe'])) {
                \App\Models\FacturaPago::create([
                    'factura_id' => $factura->id,
                    'descripcion' => $pago['descripcion'],
                    'fecha' => $pago['fecha'] ?? now(),
                    'importe' => $pago['importe'],
                    'metodo_pago' => $pago['metodo_pago'] ?? null,
                    'notas' => $pago['notas'] ?? null,
                ]);
            }
        }
        
        // Actualizar campos de descuentos
        $factura->update([
            'descuento_antes_impuestos' => $request->input('descuento_antes_impuestos', 0),
            'descuento_despues_impuestos' => $request->input('descuento_despues_impuestos', 0),
            'numero_orden' => $request->input('numero_orden'),
        ]);
        
        \DB::commit();
        
        return response()->json([
            'success' => true,
            'message' => 'Factura creada correctamente',
            'factura_id' => $factura->id,
        ]);
    } catch (\Exception $e) {
        \DB::rollBack();
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

// Obtener información del cliente para cálculo automático
Route::get('/walee-facturas/cliente/{id}/info', function ($id) {
    $cliente = \App\Models\Cliente::findOrFail($id);
    $facturas = \App\Models\Factura::where('cliente_id', $id)->get();
    
    $totalFacturado = $facturas->sum('total');
    $totalPagado = $facturas->sum('monto_pagado');
    $saldoPendiente = $totalFacturado - $totalPagado;
    
    return response()->json([
        'success' => true,
        'cliente' => [
            'id' => $cliente->id,
            'nombre' => $cliente->nombre_empresa,
            'correo' => $cliente->correo,
        ],
        'resumen' => [
            'total_facturado' => $totalFacturado,
            'total_pagado' => $totalPagado,
            'saldo_pendiente' => $saldoPendiente,
            'facturas_count' => $facturas->count(),
        ],
    ]);
})->middleware(['auth'])->name('walee.facturas.cliente.info');

// Obtener paquetes disponibles
Route::get('/walee-facturas/paquetes', function () {
    $paquetes = \App\Models\FacturaPaquete::where('activo', true)
        ->orderBy('categoria')
        ->orderBy('orden')
        ->get();
    
    return response()->json([
        'success' => true,
        'paquetes' => $paquetes,
    ]);
})->middleware(['auth'])->name('walee.facturas.paquetes');

// Previsualizar factura
Route::post('/walee-facturas/previsualizar', function (\Illuminate\Http\Request $request) {
    $data = $request->all();
    if (isset($data['items_json'])) {
        $data['items'] = json_decode($data['items_json'], true);
    }
    if (isset($data['pagos'])) {
        $data['pagos'] = json_decode($data['pagos'], true);
    }
    return view('walee-factura-preview', compact('data'));
})->middleware(['auth'])->name('walee.facturas.preview');

// Generar PDF de factura
Route::post('/walee-facturas/generar-pdf', function (\Illuminate\Http\Request $request) {
    $data = $request->all();
    if (isset($data['items_json'])) {
        $data['items'] = json_decode($data['items_json'], true);
    }
    if (isset($data['pagos'])) {
        $data['pagos'] = json_decode($data['pagos'], true);
    }
    
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('walee-factura-preview', compact('data'));
    $pdf->setPaper('A4', 'portrait');
    return $pdf->download('factura-' . ($data['numero_factura'] ?? 'temp') . '.pdf');
})->middleware(['auth'])->name('walee.facturas.generar-pdf');

// Enviar factura por email
Route::post('/walee-facturas/{id}/enviar-email', function ($id, \Illuminate\Http\Request $request) {
    $factura = \App\Models\Factura::with(['cliente', 'items', 'pagos'])->findOrFail($id);
    
    if (!$factura->correo) {
        return response()->json([
            'success' => false,
            'message' => 'La factura no tiene correo electrónico asociado',
        ], 400);
    }
    
    try {
        $data = [
            'numero_factura' => $factura->numero_factura,
            'fecha_emision' => $factura->fecha_emision->format('Y-m-d'),
            'fecha_vencimiento' => $factura->fecha_vencimiento ? $factura->fecha_vencimiento->format('Y-m-d') : null,
            'cliente_id' => $factura->cliente_id,
            'correo' => $factura->correo,
            'subtotal' => $factura->subtotal,
            'descuento_antes_impuestos' => $factura->descuento_antes_impuestos ?? 0,
            'descuento_despues_impuestos' => $factura->descuento_despues_impuestos ?? 0,
            'total' => $factura->total,
            'monto_pagado' => $factura->monto_pagado,
            'estado' => $factura->estado,
            'numero_orden' => $factura->numero_orden,
            'notas' => $factura->notas,
            'items_json' => json_encode($factura->items->map(function($item) {
                return [
                    'descripcion' => $item->descripcion,
                    'cantidad' => $item->cantidad,
                    'precio_unitario' => $item->precio_unitario,
                    'subtotal' => $item->subtotal,
                    'notas' => $item->notas ?? null,
                ];
            })->toArray()),
            'pagos' => json_encode($factura->pagos->map(function($pago) {
                return [
                    'descripcion' => $pago->descripcion,
                    'fecha' => $pago->fecha->format('Y-m-d'),
                    'importe' => $pago->importe,
                ];
            })->toArray()),
        ];
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('walee-factura-preview', ['data' => $data]);
        $pdf->setPaper('A4', 'portrait');
        
        \Mail::send('emails.factura-envio', [
            'factura' => $factura,
            'cliente' => $factura->cliente,
        ], function ($message) use ($factura, $pdf) {
            $message->to($factura->correo)
                    ->subject('Factura ' . $factura->numero_factura . ' - Web Solutions')
                    ->attachData($pdf->output(), 'factura-' . $factura->numero_factura . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
        });
        
        $factura->update(['enviada_at' => now()]);
        
        return response()->json([
            'success' => true,
            'message' => 'Factura enviada por email correctamente con PDF adjunto',
        ]);
    } catch (\Exception $e) {
        \Log::error('Error enviando factura por email: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al enviar email: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('walee.facturas.enviar-email');

// Herramientas - Enviar Contrato
Route::get('/walee-herramientas/enviar-contrato', function () {
    $clientes = \App\Models\Client::orderBy('name', 'asc')->get();
    $clientesData = $clientes->map(function($cliente) {
        return [
            'id' => $cliente->id,
            'name' => $cliente->name,
            'email' => $cliente->email,
        ];
    });
    return view('walee-herramientas-enviar-contrato', compact('clientes', 'clientesData'));
})->middleware(['auth'])->name('walee.herramientas.enviar-contrato');

Route::post('/walee-herramientas/enviar-contrato', function (\Illuminate\Http\Request $request) {
    try {
                $validated = $request->validate([
                    'cliente_id' => 'required|exists:clientes_en_proceso,id',
                    'servicios' => 'required|array|min:1',
                    'servicios.*' => 'required|string|in:diseno_web,redes_sociales,seo,publicidad,mantenimiento,hosting,combo',
                    'precio' => 'required|numeric|min:0',
                    'idioma' => 'required|in:es,en,fr,zh',
                ]);

        $cliente = \App\Models\Client::findOrFail($validated['cliente_id']);
        
        if (!$cliente->email) {
            return redirect()->back()
                ->with('error', 'El cliente no tiene un correo electrónico registrado')
                ->withInput();
        }

        // Mapeo de servicios a nombres y descripciones
        $servicios = [
            'diseno_web' => [
                'nombre' => 'Diseño Web',
                'descripcion' => 'Diseño y desarrollo de sitio web profesional, incluyendo diseño responsivo, optimización SEO básica y entrega de código fuente.'
            ],
            'redes_sociales' => [
                'nombre' => 'Gestión de Redes Sociales',
                'descripcion' => 'Gestión completa de redes sociales, incluyendo creación de contenido, programación de publicaciones, interacción con seguidores y análisis de métricas.'
            ],
            'seo' => [
                'nombre' => 'SEO / Posicionamiento',
                'descripcion' => 'Servicios de optimización para motores de búsqueda, incluyendo investigación de palabras clave, optimización on-page, link building y análisis de resultados.'
            ],
            'publicidad' => [
                'nombre' => 'Publicidad Digital',
                'descripcion' => 'Campañas publicitarias en plataformas digitales, incluyendo Google Ads, Facebook Ads, Instagram Ads y gestión de presupuesto publicitario.'
            ],
            'mantenimiento' => [
                'nombre' => 'Mantenimiento Web',
                'descripcion' => 'Servicio de mantenimiento continuo del sitio web, incluyendo actualizaciones de seguridad, respaldos, monitoreo y soporte técnico.'
            ],
            'hosting' => [
                'nombre' => 'Hosting & Dominio',
                'descripcion' => 'Servicios de hosting web y registro de dominio, incluyendo alojamiento, certificados SSL, correo electrónico y soporte técnico.'
            ],
            'combo' => [
                'nombre' => 'Paquete Completo',
                'descripcion' => 'Paquete integral que incluye diseño web, hosting, dominio, gestión de redes sociales, SEO básico y mantenimiento mensual.'
            ],
        ];

        // Obtener información de todos los servicios seleccionados
        $serviciosSeleccionados = [];
        foreach ($validated['servicios'] as $servicioKey) {
            $serviciosSeleccionados[] = $servicios[$servicioKey] ?? [
                'nombre' => ucfirst(str_replace('_', ' ', $servicioKey)),
                'descripcion' => 'Servicio personalizado según acuerdo entre las partes.'
            ];
        }
        
        // Crear lista de nombres de servicios para el email
        $nombresServicios = array_map(function($s) { return $s['nombre']; }, $serviciosSeleccionados);
        $listaServicios = implode(', ', $nombresServicios);

        // Generar PDF del contrato usando mPDF
        $pdfFileName = 'Contrato_' . str_replace(' ', '_', $cliente->name) . '_' . now()->format('Ymd') . '.pdf';
        $pdfPath = storage_path('app/temp/' . $pdfFileName);
        
        // Asegurar que el directorio existe
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }
        
        // Obtener traducciones según el idioma seleccionado
        $idioma = $validated['idioma'];
        $translations = \App\Helpers\ContractTranslations::getTranslations($idioma);
        
        // Generar HTML del contrato
        $htmlContent = view('contratos.contrato-pdf', [
            'cliente' => $cliente,
            'servicios' => $serviciosSeleccionados,
            'precio' => $validated['precio'],
            'idioma' => $idioma,
            't' => $translations,
        ])->render();
        
        // Generar PDF usando mPDF - cargar clase explícitamente si es necesario
        if (!class_exists('Mpdf\Mpdf')) {
            require_once base_path('vendor/autoload.php');
        }
        
        // Intentar diferentes formas de instanciar mPDF
        try {
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'orientation' => 'P',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 16,
                'margin_bottom' => 16,
                'margin_header' => 9,
                'margin_footer' => 9,
            ]);
        } catch (\Exception $e) {
            // Si falla, intentar con el namespace completo como string
            $mpdfClass = '\\Mpdf\\Mpdf';
            $mpdf = new $mpdfClass([
                'mode' => 'utf-8',
                'format' => 'A4',
                'orientation' => 'P',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 16,
                'margin_bottom' => 16,
                'margin_header' => 9,
                'margin_footer' => 9,
            ]);
        }
        
        $mpdf->WriteHTML($htmlContent);
        $mpdf->Output($pdfPath, 'F');

        // Preparar cuerpo del email según el idioma
        $clienteNombre = $cliente->name ?? $cliente->nombre_empresa ?? 'Cliente';
        $clienteEmail = $cliente->email ?? $cliente->correo;
        
        $emailBody = $translations['email_greeting'] . " " . $clienteNombre . ",\n\n";
        $emailBody .= $translations['email_body_1'] . " " . $listaServicios . ".\n\n";
        $emailBody .= $translations['email_body_2'] . "\n";
        $emailBody .= "- " . $translations['email_service'] . " " . $listaServicios . "\n";
        $emailBody .= "- " . $translations['email_price'] . " " . number_format($validated['precio'], 2, ',', '.') . " CRC (" . number_format($validated['precio'] / 520, 2, '.', ',') . " USD)\n";
        $emailBody .= "- " . $translations['email_date'] . " " . now()->format('d/m/Y') . "\n\n";
        $emailBody .= $translations['email_body_3'] . "\n\n";
        $emailBody .= $translations['email_closing'] . "\n";
        $emailBody .= $translations['email_company'] . "\n";
        $emailBody .= "websolutionscrnow@gmail.com";

        // Enviar email con PDF adjunto
        if (!file_exists($pdfPath)) {
            throw new \Exception('No se pudo generar el archivo PDF');
        }

        // Procesar archivos adjuntos adicionales
        $archivosAdjuntos = [];
        if ($request->hasFile('archivos')) {
            foreach ($request->file('archivos') as $archivo) {
                if ($archivo->isValid()) {
                    $archivoPath = $archivo->store('contratos/adjuntos', 'public');
                    $archivosAdjuntos[] = [
                        'path' => storage_path('app/public/' . $archivoPath),
                        'name' => $archivo->getClientOriginalName(),
                        'mime' => $archivo->getMimeType(),
                    ];
                }
            }
        }

        \Illuminate\Support\Facades\Mail::raw($emailBody, function ($message) use ($clienteEmail, $pdfPath, $pdfFileName, $listaServicios, $translations, $archivosAdjuntos) {
            $message->from('websolutionscrnow@gmail.com', 'Web Solutions - WALEÉ')
                    ->to($clienteEmail)
                    ->subject($translations['email_subject'] . ' ' . $listaServicios)
                    ->attach($pdfPath, [
                        'as' => $pdfFileName,
                        'mime' => 'application/pdf',
                    ]);
            
            // Adjuntar archivos adicionales
            foreach ($archivosAdjuntos as $archivo) {
                if (file_exists($archivo['path'])) {
                    $message->attach($archivo['path'], [
                        'as' => $archivo['name'],
                        'mime' => $archivo['mime'],
                    ]);
                }
            }
        });

        // Guardar PDF en storage permanente y guardar contrato en BD
        $permanentPath = 'contratos/' . $pdfFileName;
        $permanentFullPath = storage_path('app/public/' . $permanentPath);
        if (!file_exists(storage_path('app/public/contratos'))) {
            mkdir(storage_path('app/public/contratos'), 0755, true);
        }
        copy($pdfPath, $permanentFullPath);

        // Guardar contrato en la base de datos
        \App\Models\Contrato::create([
            'cliente_id' => null, // No hay relación directa con clientes_en_proceso
            'correo' => $clienteEmail,
            'servicios' => $validated['servicios'],
            'precio' => $validated['precio'],
            'idioma' => $validated['idioma'],
            'pdf_path' => $permanentPath,
            'enviada_at' => now(),
            'estado' => 'enviado',
        ]);

        // Eliminar archivo temporal después de enviar
        if (file_exists($pdfPath)) {
            unlink($pdfPath);
        }
        
        return redirect()->route('walee.herramientas.enviar-contrato')
            ->with('success', 'Contrato enviado correctamente por email a ' . $clienteEmail);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput();
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Error al enviar el contrato: ' . $e->getMessage())
            ->withInput();
    }
})->middleware(['auth'])->name('walee.herramientas.enviar-contrato.post');

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
    
    // Buscar contratos por email del cliente en proceso
    $contratos = \App\Models\Contrato::where('correo', $cliente->email)
        ->orderBy('created_at', 'desc')
        ->get();
    
    // Buscar cliente en tabla clientes por email o nombre para obtener cotizaciones y facturas
    $clientePrincipal = \App\Models\Cliente::where('correo', $cliente->email)
        ->orWhere('nombre_empresa', 'like', '%' . $cliente->name . '%')
        ->first();
    
    $cotizaciones = $clientePrincipal ? \App\Models\Cotizacion::where('cliente_id', $clientePrincipal->id)
        ->orderBy('created_at', 'desc')
        ->get() : collect();
    
    $facturas = $clientePrincipal ? \App\Models\Factura::where('cliente_id', $clientePrincipal->id)
        ->orderBy('created_at', 'desc')
        ->get() : collect();
    
    return view('walee-cliente-detalle', compact('cliente', 'contratos', 'cotizaciones', 'facturas', 'clientePrincipal'));
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

// Ruta para Walee WhatsApp
Route::get('/walee-whatsapp', function () {
    return view('walee-whatsapp');
})->middleware(['auth'])->name('walee.whatsapp');

Route::get('/walee-facebook/clientes', function (\Illuminate\Http\Request $request) {
        // Estadísticas de publicaciones
        $totalPublicaciones = \App\Models\Post::count();
        $publicacionesEsteMes = \App\Models\Post::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $publicacionesEstaSemana = \App\Models\Post::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();
        $publicacionesHoy = \App\Models\Post::whereDate('created_at', today())->count();
        
        // Clientes activos (con publicaciones en los últimos 30 días)
        $clientesActivos = \App\Models\Client::whereHas('posts', function($query) {
            $query->where('created_at', '>=', now()->subDays(30));
        })->count();
        
        $totalClientes = \App\Models\Client::count();
        
        // Publicaciones recientes con paginación (5 por página)
        $publicacionesRecientes = \App\Models\Post::with('cliente')
            ->orderBy('created_at', 'desc')
            ->paginate(5);
        
        // Clientes con más publicaciones
        $clientesTop = \App\Models\Client::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(5)
            ->get();
        
        // Distribución de publicaciones por día (últimos 15 días) - Rellenar días sin publicaciones con 0
        $publicacionesPorDiaRaw = \App\Models\Post::selectRaw('DATE(created_at) as dia, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(15))
            ->groupBy('dia')
            ->orderBy('dia', 'asc')
            ->get()
            ->keyBy('dia');
        
        // Rellenar todos los días de los últimos 15 días, incluyendo los que no tienen publicaciones (0)
        $publicacionesPorDia = [];
        for ($i = 14; $i >= 0; $i--) {
            $fecha = now()->subDays($i)->format('Y-m-d');
            $publicacion = $publicacionesPorDiaRaw->get($fecha);
            $publicacionesPorDia[] = [
                'dia' => $fecha,
                'total' => $publicacion ? (int)$publicacion->total : 0
            ];
        }
        
        return view('walee-facebook-clientes', compact(
            'totalPublicaciones',
            'publicacionesEsteMes',
            'publicacionesEstaSemana',
            'publicacionesHoy',
            'clientesActivos',
            'totalClientes',
            'publicacionesRecientes',
            'clientesTop',
            'publicacionesPorDia'
        ));
    })->middleware(['auth'])->name('walee.facebook.clientes');

// Ruta para ver todas las publicaciones
Route::get('/walee-facebook/publicaciones', function (\Illuminate\Http\Request $request) {
        $publicaciones = \App\Models\Post::with('cliente')
            ->orderBy('created_at', 'desc')
            ->paginate(5);
        
        return view('walee-facebook-publicaciones', compact('publicaciones'));
    })->middleware(['auth'])->name('walee.facebook.publicaciones');

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
Route::post('/walee-tareas', function (\Illuminate\Http\Request $request) {
    try {
        $tarea = new \App\Models\Tarea();
        $tarea->lista_id = $request->input('lista_id') ?: null;
        $tarea->texto = $request->input('texto');
        // Si viene fecha_hora del request, usarla; si no, usar now()
        $tarea->fecha_hora = $request->input('fecha_hora') ? \Carbon\Carbon::parse($request->input('fecha_hora')) : now();
        $tarea->tipo = $request->input('tipo') ?: null;
        $tarea->favorito = false; // Por defecto no es favorito
        $tarea->estado = 'pending';
        $tarea->recurrencia = $request->input('recurrencia', 'none');
        $tarea->recurrencia_fin = $request->input('recurrencia_fin') ? \Carbon\Carbon::parse($request->input('recurrencia_fin')) : null;
        $tarea->recurrencia_dias = $request->input('recurrencia_dias');
        $tarea->color = $request->input('color', '#8b5cf6');
        
        // Campos de notificación
        $tarea->notificacion_habilitada = $request->input('notificacion_habilitada', false);
        $tarea->notificacion_tipo = $request->input('notificacion_tipo') ?: null;
        $tarea->notificacion_minutos_antes = $request->input('notificacion_minutos_antes') ?: null;
        $tarea->notificacion_fecha_hora = $request->input('notificacion_fecha_hora') ? \Carbon\Carbon::parse($request->input('notificacion_fecha_hora')) : null;
        
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

Route::put('/walee-tareas/{id}', function (\Illuminate\Http\Request $request, $id) {
    try {
        $tarea = \App\Models\Tarea::findOrFail($id);
        $tarea->texto = $request->input('texto');
        $tarea->lista_id = $request->input('lista_id') ?: null;
        if ($request->input('fecha_hora')) {
            $tarea->fecha_hora = \Carbon\Carbon::parse($request->input('fecha_hora'));
        }
        $tarea->tipo = $request->input('tipo') ?: null;
        $tarea->recurrencia = $request->input('recurrencia', 'none');
        $tarea->recurrencia_fin = $request->input('recurrencia_fin') ? \Carbon\Carbon::parse($request->input('recurrencia_fin')) : null;
        $tarea->recurrencia_dias = $request->input('recurrencia_dias');
        $tarea->color = $request->input('color', '#8b5cf6');
        
        // Campos de notificación
        $tarea->notificacion_habilitada = $request->input('notificacion_habilitada', false);
        $tarea->notificacion_tipo = $request->input('notificacion_tipo') ?: null;
        $tarea->notificacion_minutos_antes = $request->input('notificacion_minutos_antes') ?: null;
        $tarea->notificacion_fecha_hora = $request->input('notificacion_fecha_hora') ? \Carbon\Carbon::parse($request->input('notificacion_fecha_hora')) : null;
        
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

Route::delete('/walee-tareas/{id}', function ($id) {
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

Route::post('/walee-tareas/{id}/toggle', function ($id) {
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

Route::post('/walee-tareas/{id}/favorito', function ($id) {
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

// Rutas para Notas (POST, PUT, DELETE)
Route::post('/notas', function (\Illuminate\Http\Request $request) {
    try {
        $nota = new \App\Models\Note();
        $nota->content = $request->input('content');
        $nota->type = $request->input('type', 'note');
        $nota->cliente_id = $request->input('cliente_id') ?: null;
        $nota->pinned = $request->input('pinned', false);
        $nota->fecha = $request->input('fecha') ?: now()->format('Y-m-d');
        $nota->user_id = auth()->id();
        $nota->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Nota creada correctamente',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('notas.store');

Route::get('/notas/{id}', function ($id) {
    try {
        $nota = \App\Models\Note::with(['cliente', 'user'])->findOrFail($id);
        
        // Formatear la fecha para que se muestre correctamente en el input date
        $notaData = [
            'id' => $nota->id,
            'content' => $nota->content,
            'type' => $nota->type,
            'cliente_id' => $nota->cliente_id,
            'pinned' => $nota->pinned,
            'fecha' => $nota->fecha ? $nota->fecha->format('Y-m-d') : null,
            'created_at' => $nota->created_at,
            'updated_at' => $nota->updated_at,
        ];
        
        if ($nota->cliente) {
            $notaData['cliente'] = [
                'id' => $nota->cliente->id,
                'nombre_empresa' => $nota->cliente->nombre_empresa,
            ];
        }
        
        if ($nota->user) {
            $notaData['user'] = [
                'id' => $nota->user->id,
                'name' => $nota->user->name,
            ];
        }
        
        return response()->json([
            'success' => true,
            'nota' => $notaData,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('notas.show');

Route::put('/notas/{id}', function (\Illuminate\Http\Request $request, $id) {
    try {
        $nota = \App\Models\Note::findOrFail($id);
        $nota->content = $request->input('content');
        $nota->type = $request->input('type', 'note');
        $nota->cliente_id = $request->input('cliente_id') ?: null;
        $nota->pinned = $request->input('pinned', false);
        $nota->fecha = $request->input('fecha') ?: now()->format('Y-m-d');
        $nota->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Nota actualizada correctamente',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('notas.update');

Route::delete('/notas/{id}', function ($id) {
    try {
        $nota = \App\Models\Note::findOrFail($id);
        $nota->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Nota eliminada correctamente',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('notas.delete');

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

Route::put('/listas/{id}', function (\Illuminate\Http\Request $request, $id) {
    try {
        $lista = \App\Models\Lista::findOrFail($id);
        
        // Obtener datos del request (puede venir como JSON o FormData)
        $nombre = $request->input('nombre');
        $descripcion = $request->input('descripcion');
        
        // Si viene como JSON, leer del body
        if (empty($nombre) && $request->isJson()) {
            $jsonData = $request->json()->all();
            $nombre = $jsonData['nombre'] ?? null;
            $descripcion = $jsonData['descripcion'] ?? null;
        }
        
        if (empty($nombre)) {
            return response()->json([
                'success' => false,
                'message' => 'El nombre de la lista es requerido',
            ], 400);
        }
        
        $lista->nombre = $nombre;
        $lista->descripcion = $descripcion;
        $lista->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Lista actualizada correctamente',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('listas.update');

Route::delete('/listas/{id}', function ($id) {
    try {
        $lista = \App\Models\Lista::findOrFail($id);
        // Eliminar todas las tareas asociadas
        $lista->tareas()->delete();
        // Eliminar la lista
        $lista->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Lista eliminada correctamente',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('listas.destroy');

// Ruta para crear publicación del cliente
// API para generar publicación de Facebook con AI
Route::post('/walee-cliente/{id}/publicaciones/generar', function (\Illuminate\Http\Request $request, $id) {
    try {
        $apiKey = config('services.openai.api_key');
        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Falta OPENAI_API_KEY. Configura la API key en el servidor.',
            ], 500);
        }
        
        $cliente = \App\Models\Client::findOrFail($id);
        $aiPrompt = $request->input('ai_prompt', '');
        
        // Construir el prompt (en inglés por defecto)
        if (empty($aiPrompt)) {
            $prompt = "Generate a professional Facebook post for {$cliente->name}";
            if ($cliente->website) {
                $prompt .= " whose website is {$cliente->website}";
            }
            $prompt .= ". The post must be attractive, professional, and optimized for social media. It must have a maximum of 500 characters, be persuasive, include appropriate emojis, and have a clear call to action. Write in English.";
        } else {
            $prompt = "Generate a professional Facebook post. {$aiPrompt}";
            if ($cliente->name) {
                $prompt .= " The client is called {$cliente->name}.";
            }
            if ($cliente->website) {
                $prompt .= " Their website is {$cliente->website}.";
            }
            $prompt .= " The post must have a maximum of 500 characters, be attractive, include appropriate emojis, and have a clear call to action. Write in English.";
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
                        'content' => 'You are an expert in digital marketing and social media, especially Facebook. Generate professional, attractive, and optimized Facebook posts. Posts must be concise (maximum 500 characters), persuasive, include appropriate emojis, and have a clear call to action. Always respond in English. Respond ONLY with JSON containing "content" (complete post text).',
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt . ' Respond in English and JSON format with "content".',
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
            
            $publicacionContent = trim($data['content'] ?? '');
            
            if (empty($publicacionContent)) {
                throw new \RuntimeException('El contenido de la publicación está vacío.');
            }
            
            // Agregar siempre el contacto de Melissa al final
            $contactoMelissa = 'contactar a Melissa at 506) 8321 4037';
            if (stripos($publicacionContent, $contactoMelissa) === false) {
                $publicacionContent .= ' ' . $contactoMelissa;
            }
            
            return response()->json([
                'success' => true,
                'content' => $publicacionContent,
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
})->middleware(['auth'])->name('walee.cliente.publicaciones.generar');

// Ruta para generar mensaje de WhatsApp con AI
Route::post('/walee/whatsapp/generar-mensaje', function (\Illuminate\Http\Request $request) {
    try {
        $apiKey = config('services.openai.api_key');
        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Falta OPENAI_API_KEY. Configura la API key en el servidor.',
            ], 500);
        }
        
        $prompt = $request->input('prompt', '');
        
        if (empty($prompt)) {
            return response()->json([
                'success' => false,
                'message' => 'El prompt no puede estar vacío.',
            ], 400);
        }
        
        // Construir el prompt para WhatsApp
        $systemPrompt = 'Eres un asistente experto en comunicación profesional por WhatsApp. Genera mensajes concisos, profesionales y amigables. Los mensajes deben ser apropiados para WhatsApp, usar un tono profesional pero cercano, y ser directos. Responde en español.';
        
        $userPrompt = "Genera un mensaje de WhatsApp profesional basado en el siguiente prompt: {$prompt}. El mensaje debe ser conciso, profesional y apropiado para WhatsApp.";
        
        $response = \Illuminate\Support\Facades\Http::withToken($apiKey)
            ->acceptJson()
            ->timeout(60)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $systemPrompt,
                    ],
                    [
                        'role' => 'user',
                        'content' => $userPrompt,
                    ],
                ],
                'temperature' => 0.7,
                'max_tokens' => 300,
            ]);
        
        if ($response->successful()) {
            $responseData = $response->json();
            $message = trim($responseData['choices'][0]['message']['content'] ?? '');
            
            if (empty($message)) {
                throw new \RuntimeException('La respuesta de AI está vacía.');
            }
            
            return response()->json([
                'success' => true,
                'message' => $message,
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
})->middleware(['auth'])->name('walee.whatsapp.generar-mensaje');

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
        
        // Guardar primera foto como image_url para compatibilidad - Usar route() para URL correcta
        $imageUrl = null;
        if (!empty($fotosPaths)) {
            // Extraer solo el nombre del archivo
            $filename = basename($fotosPaths[0]);
            // Generar URL absoluta usando route() para asegurar que funcione
            $imageUrl = route('storage.publicaciones', ['filename' => $filename]);
            // Asegurar HTTPS
            $imageUrl = str_replace('http://', 'https://', $imageUrl);
        }
        
        // Usar el contenido como título si no hay título específico
        $title = $request->input('title') ?: substr($request->input('content'), 0, 100);
        
        // Contenido original (sin WhatsApp)
        $contentOriginal = $request->input('content');
        
        // Obtener número de WhatsApp del cliente (telefono_1 o telefono_2)
        $whatsappNumber = $cliente->telefono_1 ?? $cliente->telefono_2 ?? null;
        
        // Agregar link a velasportfishingandtours.com siempre
        $websiteLink = "\n\nBook now: https://www.velasportfishingandtours.com/";
        
        // Preparar contenido con botón de WhatsApp para guardar en BD
        $contentWithWhatsApp = $contentOriginal . $websiteLink;
        $whatsappUrl = null;
        
        if ($whatsappNumber) {
            // Limpiar número (eliminar espacios, guiones, paréntesis, etc.)
            $cleanNumber = preg_replace('/[^0-9]/', '', $whatsappNumber);
            
            // Si no empieza con código de país, asumir Costa Rica (506)
            if (strlen($cleanNumber) <= 8) {
                $cleanNumber = '506' . $cleanNumber;
            }
            
            $whatsappUrl = "https://wa.me/{$cleanNumber}";
            
            // Agregar botón de WhatsApp al final del contenido para guardar
            $whatsappButton = "\n\n📱 Contáctanos por WhatsApp: {$whatsappUrl}";
            $contentWithWhatsApp = $contentOriginal . $websiteLink . $whatsappButton;
        } else {
            // Si no hay WhatsApp, solo agregar el link del sitio web
            $contentWithWhatsApp = $contentOriginal . $websiteLink;
        }
        
        $publicacion = \App\Models\Post::create([
            'cliente_id' => $cliente->id,
            'title' => $title,
            'content' => $contentWithWhatsApp, // Guardar con WhatsApp en BD
            'image_url' => $imageUrl,
        ]);
        
        // Enviar webhook a n8n con texto e imagen URL pública
        try {
            $webhookUrl = 'https://n8n.srv1137974.hstgr.cloud/webhook/f11f10f6-025d-4ecf-83af-a6dfa5acc147';
            
            // Obtener URL pública de la primera imagen (si existe) - Generar URL absoluta correcta
            $imageUrlPublic = null;
            if (!empty($fotosPaths) && !empty($fotosPaths[0])) {
                // Extraer solo el nombre del archivo
                $filename = basename($fotosPaths[0]);
                // Generar URL absoluta usando route() para asegurar que funcione
                $imageUrlPublic = route('storage.publicaciones', ['filename' => $filename]);
                // Asegurar HTTPS
                $imageUrlPublic = str_replace('http://', 'https://', $imageUrlPublic);
                
                \Log::info('URL de imagen generada para webhook', [
                    'filename' => $filename,
                    'url' => $imageUrlPublic,
                    'path' => $fotosPaths[0]
                ]);
            }
            
            // Agregar link del sitio web al contenido para el webhook
            $contentWithLink = $contentOriginal . "\n\nBook now: https://www.velasportfishingandtours.com/";
            
            // Preparar datos para el webhook
            $webhookData = [
                'texto' => $contentWithLink, // Texto de la publicación con link
                'image_url' => $imageUrlPublic, // URL pública de la imagen
            ];
            
            // Enviar al webhook de n8n
            $client = new \GuzzleHttp\Client();
            $client->post($webhookUrl, [
                'json' => $webhookData,
                'timeout' => 30,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]);
            
            \Log::info('Webhook enviado a n8n', [
                'webhook_url' => $webhookUrl,
                'data' => $webhookData,
            ]);
        } catch (\Exception $webhookError) {
            \Log::warning('Error al enviar webhook de publicación a n8n: ' . $webhookError->getMessage());
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

// Ruta para ver publicación (vista previa para compartir) - Sin autenticación para que Facebook pueda leer los Open Graph tags
Route::get('/walee-cliente/{id}/publicaciones/{publicacion_id}/share', function ($id, $publicacion_id) {
    try {
        $cliente = \App\Models\Client::findOrFail($id);
        $publicacion = \App\Models\Post::where('id', $publicacion_id)
            ->where('cliente_id', $cliente->id)
            ->firstOrFail();
        
        return view('walee-publicacion-share', compact('publicacion', 'cliente'));
    } catch (\Exception $e) {
        abort(404);
    }
})->name('walee.cliente.publicaciones.share');

// Ruta para republicar publicación en Facebook
Route::post('/walee-cliente/{id}/publicaciones/{publicacion_id}/republicar', function ($id, $publicacion_id) {
    try {
        $cliente = \App\Models\Client::findOrFail($id);
        $publicacion = \App\Models\Post::where('id', $publicacion_id)
            ->where('cliente_id', $cliente->id)
            ->firstOrFail();
        
        // Extraer texto sin el botón de WhatsApp (si existe)
        $content = $publicacion->content;
        // Remover líneas que contengan WhatsApp
        $content = preg_replace('/\n.*[Ww]hats[Aa]pp.*\n?/', '', $content);
        $content = trim($content);
        
        // Asegurar que el contenido tenga el link del sitio web
        if (strpos($content, 'velasportfishingandtours.com') === false) {
            $content .= "\n\nBook now: https://www.velasportfishingandtours.com/";
        }
        
        // Obtener URL de la imagen - Generar URL absoluta usando route()
        $imageUrl = $publicacion->image_url;
        if ($imageUrl) {
            // Si es una URL completa, solo asegurar HTTPS
            if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                $imageUrl = str_replace('http://', 'https://', $imageUrl);
            } else {
                // Extraer el nombre del archivo de la URL
                $filename = basename($imageUrl);
                // Si no hay nombre de archivo, intentar extraer del path
                if (empty($filename) || $filename === $imageUrl) {
                    // Intentar extraer de diferentes formatos
                    if (preg_match('/publicaciones\/([^\/]+)$/', $imageUrl, $matches)) {
                        $filename = $matches[1];
                    } else {
                        $filename = basename(str_replace(['/storage/', 'storage/'], '', $imageUrl));
                    }
                }
                // Generar URL usando route() para asegurar que funcione
                $imageUrl = route('storage.publicaciones', ['filename' => $filename]);
                // Asegurar HTTPS
                $imageUrl = str_replace('http://', 'https://', $imageUrl);
            }
        }
        
        // Enviar al webhook de n8n
        $webhookUrl = 'https://n8n.srv1137974.hstgr.cloud/webhook/692835c7-0e6a-4535-8d20-7b385a9a66ca';
        
        $webhookData = [
            'texto' => $content,
            'image_url' => $imageUrl,
        ];
        
        $client = new \GuzzleHttp\Client();
        $client->post($webhookUrl, [
            'json' => $webhookData,
            'timeout' => 30,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);
        
        \Log::info('Publicación republicada en Facebook', [
            'publicacion_id' => $publicacion_id,
            'cliente_id' => $id,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Publicación republicada en Facebook correctamente',
        ]);
    } catch (\Exception $e) {
        \Log::error('Error al republicar publicación: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->middleware(['auth'])->name('walee.cliente.publicaciones.republicar');

// Ruta para compartir publicación por WhatsApp (con imagen visible) - Sin autenticación para que WhatsApp pueda leer los Open Graph tags
Route::get('/walee-cliente/{id}/publicaciones/{publicacion_id}/whatsapp', function ($id, $publicacion_id) {
    try {
        $cliente = \App\Models\Client::findOrFail($id);
        $publicacion = \App\Models\Post::where('id', $publicacion_id)
            ->where('cliente_id', $cliente->id)
            ->firstOrFail();
        
        return view('walee-publicacion-whatsapp', compact('publicacion', 'cliente'));
    } catch (\Exception $e) {
        abort(404);
    }
})->name('walee.cliente.publicaciones.whatsapp');

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

// Ruta para chat flotante (usando webhook de n8n)
Route::post('/walee-chat', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'message' => 'required|string',
    ]);

    try {
        $user = $request->user();
        $userMessage = $request->string('message');

        // Construir historial breve para enviar al webhook (últimos 10 mensajes)
        $history = \App\Models\ChatMessage::where('user_id', $user?->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->reverse() // Del más antiguo al más reciente
            ->map(function ($message) {
                return [
                    'role' => $message->type === 'user' ? 'user' : 'assistant',
                    'content' => $message->message,
                ];
            })
            ->values()
            ->toArray();

        // Llamar al webhook de n8n
        $response = \Illuminate\Support\Facades\Http::timeout(60)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post('https://n8n.srv1137974.hstgr.cloud/webhook/4d4138cb-cfbc-4226-b4fa-83f068eb5db2', [
                'message' => $userMessage,
                'user_id' => $user?->id,
                'history' => $history,
            ]);

        if (!$response->successful()) {
            \Illuminate\Support\Facades\Log::error('N8N Webhook error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return response()->json(['error' => 'Error al generar respuesta'], 500);
        }

        // Manejar diferentes formatos de respuesta de n8n
        $responseBody = $response->body();
        $assistantMessage = null;
        
        // Intentar parsear como JSON
        $responseData = null;
        try {
            $responseData = json_decode($responseBody, true);
        } catch (\Exception $e) {
            // Si no es JSON, usar el texto directamente
            $assistantMessage = trim($responseBody);
        }
        
        // Si es JSON, buscar la respuesta en diferentes campos posibles
        if ($responseData !== null) {
            // n8n puede devolver la respuesta en diferentes formatos:
            // 1. Array con estructura n8n: [{"json": {"output": "respuesta"}}]
            // 2. Objeto directo: {"output": "respuesta"}
            // 3. Array simple: [{"output": "respuesta"}]
            
            if (is_array($responseData) && isset($responseData[0])) {
                // Si es array, tomar el primer elemento
                $firstItem = $responseData[0];
                
                // Si tiene estructura n8n con 'json'
                if (isset($firstItem['json'])) {
                    $firstItem = $firstItem['json'];
                }
                
                $assistantMessage = $firstItem['output'] 
                    ?? $firstItem['response'] 
                    ?? $firstItem['message'] 
                    ?? $firstItem['text'] 
                    ?? $firstItem['content']
                    ?? (is_string($firstItem) ? $firstItem : null);
            } else {
                // Si es objeto directo
                $assistantMessage = $responseData['output'] 
                    ?? $responseData['response'] 
                    ?? $responseData['message'] 
                    ?? $responseData['text'] 
                    ?? $responseData['content']
                    ?? null;
            }
        }
        
        // Si no se encontró respuesta, usar mensaje por defecto
        if (empty($assistantMessage) || $assistantMessage === 'undefined') {
            $assistantMessage = 'Lo siento, no pude generar una respuesta en este momento.';
        }
        
        $assistantMessage = trim($assistantMessage);

        // Guardar mensaje del usuario en la base de datos
        try {
            \App\Models\ChatMessage::create([
                'user_id' => $user?->id,
                'message' => $userMessage,
                'type' => 'user',
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error guardando mensaje del usuario', [
                'error' => $e->getMessage(),
                'user_id' => $user?->id,
            ]);
        }

        // Guardar respuesta del asistente en la base de datos
        try {
            \App\Models\ChatMessage::create([
                'user_id' => $user?->id,
                'message' => $assistantMessage,
                'type' => 'assistant',
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error guardando mensaje del asistente', [
                'error' => $e->getMessage(),
                'user_id' => $user?->id,
            ]);
        }

        return response()->json(['response' => $assistantMessage]);
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Chat error', ['error' => $e->getMessage()]);
        return response()->json(['error' => 'Error al procesar el mensaje'], 500);
    }
})->middleware(['auth'])->name('walee.chat');

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
    // Callback para autorización de Gmail
    Route::get('/gmail/callback', function (\Illuminate\Http\Request $request) {
        $code = $request->get('code');
        
        if (!$code) {
            return redirect('/walee-emails/recibidos')->with('error', 'No se recibió el código de autorización');
        }
        
        $gmailService = app(\App\Services\GmailService::class);
        
        if ($gmailService->handleCallback($code)) {
            return redirect('/walee-emails/recibidos')->with('success', 'Gmail autorizado correctamente');
        } else {
            return redirect('/walee-emails/recibidos')->with('error', 'Error al autorizar Gmail');
        }
    })->name('gmail.callback');
    
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
