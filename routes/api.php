<?php

use Illuminate\Support\Facades\Route;
use Filament\Notifications\Notification;
use App\Models\User;
use App\Http\Controllers\WhatsappWebhookController;
use App\Http\Controllers\WhatsappController;

// Webhook para notificaciones de Facebook
Route::post('/notificacion-facebook', function (\Illuminate\Http\Request $request) {
    // Log de la petición para debugging
    \Log::info('Notificación de Facebook recibida', [
        'headers' => $request->headers->all(),
        'body' => $request->all(),
        'ip' => $request->ip(),
    ]);

    try {
        // Obtener los datos
        $data = $request->all();
        $dataBody = $data['data'] ?? $data;

        // Extraer información (priorizar "Nombre" con mayúscula, que es el título correcto)
        // El campo "nombre" (minúscula) contiene el mensaje completo, no el título
        $nombre = $dataBody['Nombre'] ?? 'Notificación de n8n';
        $texto = $dataBody['Texto'] ?? $dataBody['texto'] ?? '';
        $imagen = $dataBody['Imagen'] ?? $dataBody['imagen'] ?? '';
        $hashtags = $dataBody['Hashtags'] ?? $dataBody['hashtags'] ?? [];

        // Limitar el texto a 25 palabras
        $palabras = explode(' ', $texto);
        $textoLimitado = implode(' ', array_slice($palabras, 0, 25));
        if (count($palabras) > 25) {
            $textoLimitado .= '...';
        }

        // Construir el body con hashtags (sin incluir la imagen en el texto)
        $hashtagsString = '';
        if (is_array($hashtags) && !empty($hashtags)) {
            $hashtagsString = implode(' ', $hashtags);
        } elseif (!empty($hashtags)) {
            $hashtagsString = $hashtags;
        }

        $body = $textoLimitado;
        if ($hashtagsString) {
            $body .= "\n\n" . $hashtagsString;
        }
        
        // Limpiar la URL de la imagen (remover markdown si existe)
        $imagenUrl = $imagen;
        if (preg_match('/\[([^\]]+)\]\(([^\)]+)\)/', $imagen, $matches)) {
            $imagenUrl = $matches[2]; // Extraer la URL del markdown
        } elseif (preg_match('/https?:\/\/[^\s\)]+/', $imagen, $matches)) {
            $imagenUrl = $matches[0]; // Extraer la URL directa
        }

        // Enviar notificación a todos los usuarios de Filament
        $users = User::all();
        
        if ($users->isEmpty()) {
            \Log::warning('No hay usuarios para enviar notificaciones');
            return response()->json([
                'success' => false,
                'message' => 'No hay usuarios en el sistema',
            ], 200);
        }

        $notificationsSent = 0;
        foreach ($users as $user) {
            try {
                // Crear notificación estilo Facebook (simple, como toast)
                $notification = Notification::make()
                    ->title('📘 Nueva publicación en Facebook')
                    ->body($body)
                    ->info()
                    ->icon('heroicon-o-face-smile')
                    ->duration(10000); // 10 segundos
                
                $notificationData = $notification->getDatabaseMessage();
                
                $user->notifications()->create([
                    'id' => \Illuminate\Support\Str::uuid()->toString(),
                    'type' => \Filament\Notifications\DatabaseNotification::class,
                    'data' => $notificationData,
                    'read_at' => now(), // Marcar como leída para que no quede en la campana
                ]);
                
                $notificationsSent++;
            } catch (\Exception $e) {
                \Log::error('Error enviando notificación a usuario ' . $user->id . ': ' . $e->getMessage());
                \Log::error($e->getTraceAsString());
            }
        }

        \Log::info("Notificaciones enviadas: {$notificationsSent} de {$users->count()} usuarios");

    } catch (\Exception $e) {
        \Log::error('Error procesando notificación de Facebook: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
    }

    return response()->json([
        'success' => true,
        'message' => 'Notificación recibida y enviada a Filament',
        'data' => $data,
        'timestamp' => now()->toDateTimeString(),
    ], 200);
})->name('api.notificacion-facebook');

// Webhook genérico para recibir elementos de n8n y convertirlos en notificaciones de Filament
Route::post('/n8n-webhook', function (\Illuminate\Http\Request $request) {
    // Log de la petición para debugging
    \Log::info('Webhook de n8n recibido', [
        'headers' => $request->headers->all(),
        'body' => $request->all(),
        'ip' => $request->ip(),
    ]);

    try {
        $rawData = $request->all();
        
        // n8n puede enviar los datos de diferentes formas:
        // 1. Array de elementos directamente: [{"campo1": "valor1"}, {"campo2": "valor2"}]
        // 2. Objeto con campo 'data': {"data": [{"campo1": "valor1"}]}
        // 3. Un solo elemento: {"campo1": "valor1"}
        // 4. Dentro de un array con estructura n8n: [{"json": {"campo1": "valor1"}}]
        
        $elements = [];
        
        // Si viene como array directo
        if (isset($rawData[0]) && is_array($rawData[0])) {
            $elements = $rawData;
        }
        // Si viene dentro de un campo 'data'
        elseif (isset($rawData['data']) && is_array($rawData['data'])) {
            $elements = $rawData['data'];
        }
        // Si viene como un solo objeto, lo convertimos en array
        elseif (is_array($rawData) && !empty($rawData)) {
            // Verificar si tiene estructura n8n con 'json'
            if (isset($rawData[0]['json'])) {
                $elements = array_map(function($item) {
                    return $item['json'] ?? $item;
                }, $rawData);
            } else {
                $elements = [$rawData];
            }
        }
        
        if (empty($elements)) {
            \Log::warning('No se encontraron elementos en el webhook de n8n');
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron elementos para procesar',
                'received_data' => $rawData,
            ], 200);
        }

        // Obtener todos los usuarios
        $users = User::all();
        
        if ($users->isEmpty()) {
            \Log::warning('No hay usuarios para enviar notificaciones');
            return response()->json([
                'success' => false,
                'message' => 'No hay usuarios en el sistema',
            ], 200);
        }

        $notificationsSent = 0;
        $totalElements = count($elements);

        // Procesar cada elemento y crear una notificación
        foreach ($elements as $index => $element) {
            // Buscar el nombre del negocio en diferentes campos posibles
            $nombreNegocio = $element['nombre_lugar']
                ?? $element['nombreLugar']
                ?? $element['nombre_negocio']
                ?? $element['nombreNegocio']
                ?? $element['nombre']
                ?? $element['Nombre']
                ?? $element['name']
                ?? $element['Name']
                ?? $element['negocio']
                ?? $element['Negocio']
                ?? $element['business']
                ?? $element['Business']
                ?? null;
            
            // Si encontramos un nombre de negocio, formatear como notificación de cliente extraído
            if ($nombreNegocio) {
                $title = 'Nuevo Cliente';
                $body = 'Nuevo cliente extraído: ' . $nombreNegocio;
            } else {
                // Si no hay nombre de negocio, usar la lógica original
                $title = $element['titulo'] 
                    ?? $element['Titulo'] 
                    ?? $element['title'] 
                    ?? $element['Title']
                    ?? ($totalElements > 1 ? "Notificación " . ($index + 1) : 'Notificación de n8n');
                
                $body = $element['mensaje']
                    ?? $element['Mensaje']
                    ?? $element['message']
                    ?? $element['Message']
                    ?? $element['texto']
                    ?? $element['Texto']
                    ?? $element['text']
                    ?? $element['descripcion']
                    ?? $element['Descripcion']
                    ?? $element['description']
                    ?? '';
                
                // Si el body está vacío, intentar construir uno con los datos disponibles
                if (empty($body)) {
                    $bodyParts = [];
                    foreach ($element as $key => $value) {
                        // Ignorar campos comunes que no queremos mostrar
                        if (!in_array(strtolower($key), ['titulo', 'title', 'nombre', 'name', 'id', 'timestamp', 'fecha', 'date', 'nombre_lugar', 'nombrenegocio'])) {
                            if (is_string($value) || is_numeric($value)) {
                                $bodyParts[] = ucfirst($key) . ': ' . $value;
                            }
                        }
                    }
                    $body = !empty($bodyParts) ? implode("\n", array_slice($bodyParts, 0, 5)) : 'Nuevo elemento recibido de n8n';
                }
            }
            
            // Limitar el body a 200 caracteres para que no sea muy largo
            if (strlen($body) > 200) {
                $body = substr($body, 0, 197) . '...';
            }
            
            // Determinar el tipo de notificación (success, info, warning, danger)
            // Si es un nuevo cliente extraído, usar 'success' por defecto
            $defaultStatus = $nombreNegocio ? 'success' : 'info';
            
            $status = $element['tipo']
                ?? $element['Tipo']
                ?? $element['type']
                ?? $element['status']
                ?? $element['estado']
                ?? $defaultStatus;
            
            // Normalizar el status
            $status = match(strtolower($status)) {
                'success', 'exito', 'éxito', 'ok' => 'success',
                'warning', 'advertencia', 'alerta' => 'warning',
                'danger', 'error', 'error', 'fallo' => 'danger',
                default => 'info',
            };
            
            // Icono opcional
            // Si es un nuevo cliente, usar icono de usuario por defecto
            $defaultIcon = $nombreNegocio ? 'heroicon-o-user-plus' : match($status) {
                'success' => 'heroicon-o-check-circle',
                'warning' => 'heroicon-o-exclamation-triangle',
                'danger' => 'heroicon-o-x-circle',
                default => 'heroicon-o-bell',
            };
            
            $icon = $element['icono'] 
                ?? $element['Icono']
                ?? $element['icon']
                ?? $defaultIcon;
            
            // Enviar notificación a todos los usuarios
            foreach ($users as $user) {
                try {
                    $notification = Notification::make()
                        ->title($title)
                        ->body($body)
                        ->{$status}() // success(), info(), warning(), danger()
                        ->icon($icon)
                        ->duration(10000); // 10 segundos
                    
                    $notificationData = $notification->getDatabaseMessage();
                    
                    $user->notifications()->create([
                        'id' => \Illuminate\Support\Str::uuid()->toString(),
                        'type' => \Filament\Notifications\DatabaseNotification::class,
                        'data' => $notificationData,
                        'read_at' => null, // Dejar sin leer para que aparezca en la campana
                    ]);
                    
                    $notificationsSent++;
                } catch (\Exception $e) {
                    \Log::error('Error enviando notificación a usuario ' . $user->id . ': ' . $e->getMessage());
                }
            }
        }

        \Log::info("Notificaciones enviadas: {$notificationsSent} de " . ($users->count() * $totalElements) . " posibles");

    } catch (\Exception $e) {
        \Log::error('Error procesando webhook de n8n: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Error procesando webhook: ' . $e->getMessage(),
            'timestamp' => now()->toDateTimeString(),
        ], 500);
    }

    return response()->json([
        'success' => true,
        'message' => 'Notificaciones recibidas y enviadas a Filament',
        'elements_processed' => count($elements ?? []),
        'notifications_sent' => $notificationsSent ?? 0,
        'timestamp' => now()->toDateTimeString(),
    ], 200);
})->name('api.n8n-webhook');

// Webhook para que n8n reporte el progreso de workflows
Route::post('/n8n/progress', [\App\Http\Controllers\N8nProgressController::class, 'update'])
    ->name('api.n8n-progress');

// Rutas para cotización de sitios web y automatizaciones
Route::post('/cotizacion/iniciar', [\App\Http\Controllers\CotizacionWorkflowController::class, 'iniciar'])
    ->name('api.cotizacion.iniciar');

Route::get('/cotizacion/estado/{jobId}', [\App\Http\Controllers\CotizacionWorkflowController::class, 'estado'])
    ->name('api.cotizacion.estado');

// Webhook para errores de n8n (Error Trigger) - DESHABILITADO: Modelo N8nError eliminado
// Route::post('/n8n-error', ...) - Comentado porque N8nError ya no existe

// Webhook para contenido de marketing desde n8n - DESHABILITADO: Modelo N8nPost eliminado
// Route::post('/n8n-content', ...) - Comentado porque N8nPost ya no existe

// Webhook para recibir emails desde n8n (conectado a Gmail)
Route::post('/emails/recibidos', function (\Illuminate\Http\Request $request) {
    \Log::info('Email recibido via webhook', [
        'body' => $request->all(),
    ]);
    
    try {
        $data = $request->all();
        
        // Soportar múltiples formatos de entrada
        $emails = [];
        
        if (isset($data[0]) && is_array($data[0])) {
            $emails = $data;
        } elseif (isset($data['emails']) && is_array($data['emails'])) {
            $emails = $data['emails'];
        } elseif (isset($data['from_email']) || isset($data['from']) || isset($data['sender'])) {
            $emails = [$data];
        }
        
        $count = 0;
        foreach ($emails as $emailData) {
            // Extraer campos con múltiples posibles nombres
            $messageId = $emailData['message_id'] ?? $emailData['id'] ?? $emailData['messageId'] ?? null;
            $uid = $emailData['uid'] ?? $emailData['imap_uid'] ?? null;
            $folder = $emailData['folder'] ?? $emailData['mailbox'] ?? $emailData['box'] ?? 'INBOX';
            
            $fromEmail = $emailData['from_email'] ?? $emailData['from'] ?? $emailData['sender'] ?? $emailData['remitente'] ?? '';
            $fromName = $emailData['from_name'] ?? $emailData['sender_name'] ?? $emailData['nombre'] ?? null;
            $replyTo = $emailData['reply_to'] ?? $emailData['replyTo'] ?? null;
            
            $toEmail = $emailData['to_email'] ?? $emailData['to'] ?? $emailData['recipient'] ?? null;
            $toName = $emailData['to_name'] ?? $emailData['recipient_name'] ?? null;
            $cc = $emailData['cc'] ?? $emailData['CC'] ?? null;
            $bcc = $emailData['bcc'] ?? $emailData['BCC'] ?? null;
            
            $subject = $emailData['subject'] ?? $emailData['asunto'] ?? $emailData['titulo'] ?? 'Sin asunto';
            $body = $emailData['body'] ?? $emailData['text'] ?? $emailData['texto'] ?? $emailData['content'] ?? '';
            $bodyHtml = $emailData['body_html'] ?? $emailData['html'] ?? $emailData['contenido_html'] ?? null;
            $attachments = $emailData['attachments'] ?? $emailData['adjuntos'] ?? [];
            $headers = $emailData['headers'] ?? null;
            
            $inReplyTo = $emailData['in_reply_to'] ?? $emailData['inReplyTo'] ?? null;
            $references = $emailData['references'] ?? null;
            $priority = $emailData['priority'] ?? null;
            
            $isRead = $emailData['is_read'] ?? $emailData['isRead'] ?? $emailData['read'] ?? false;
            $isStarred = $emailData['is_starred'] ?? $emailData['isStarred'] ?? $emailData['starred'] ?? false;
            $isImportant = $emailData['is_important'] ?? $emailData['isImportant'] ?? false;
            $hasAttachments = !empty($attachments) || ($emailData['has_attachments'] ?? false);
            $flags = $emailData['flags'] ?? null;
            
            $receivedAt = $emailData['received_at'] ?? $emailData['date'] ?? $emailData['fecha'] ?? now();
            $sentAt = $emailData['sent_at'] ?? $emailData['sentAt'] ?? null;
            
            // Verificar si el email ya existe (por message_id o uid)
            $exists = false;
            if ($messageId) {
                $exists = \App\Models\EmailRecibido::where('message_id', $messageId)->exists();
            } elseif ($uid && $folder) {
                $exists = \App\Models\EmailRecibido::where('uid', $uid)->where('folder', $folder)->exists();
            }
            
            if ($exists) {
                continue;
            }
            
            \App\Models\EmailRecibido::create([
                'message_id' => $messageId,
                'uid' => $uid,
                'folder' => $folder,
                'from_email' => $fromEmail,
                'from_name' => $fromName,
                'reply_to' => $replyTo,
                'to_email' => $toEmail,
                'to_name' => $toName,
                'cc' => $cc,
                'bcc' => $bcc,
                'subject' => $subject,
                'body' => $body,
                'body_html' => $bodyHtml,
                'attachments' => is_array($attachments) ? $attachments : [],
                'headers' => is_array($headers) ? $headers : null,
                'in_reply_to' => $inReplyTo,
                'references' => $references,
                'priority' => $priority,
                'is_read' => $isRead,
                'is_starred' => $isStarred,
                'is_important' => $isImportant,
                'has_attachments' => $hasAttachments,
                'flags' => is_array($flags) ? $flags : null,
                'received_at' => $receivedAt,
                'sent_at' => $sentAt,
            ]);
            
            $count++;
        }
        
        return response()->json([
            'success' => true,
            'message' => "Se guardaron {$count} emails",
            'count' => $count,
        ]);
    } catch (\Exception $e) {
        \Log::error('Error guardando email: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->name('api.emails.recibidos');

// Webhook para recibir mensajes de WhatsApp desde n8n
// URL: https://websolutions.work/api/whatsapp/webhook/1c5f2da5-0d1a-4d87-a9da-bb1544748868
Route::post('/whatsapp/webhook/{id}', [WhatsappWebhookController::class, 'handleWebhook'])
    ->where('id', '[a-f0-9\-]+')
    ->name('api.whatsapp.webhook');

// Endpoint de prueba para verificar que el webhook funciona
Route::post('/whatsapp/webhook-test', [WhatsappWebhookController::class, 'handleWebhook'])
    ->name('api.whatsapp.webhook.test');

// Endpoint simple de prueba (sin procesar, solo log)
Route::post('/whatsapp/test', function (\Illuminate\Http\Request $request) {
    \Log::info('TEST WhatsApp webhook recibido', [
        'method' => $request->method(),
        'url' => $request->fullUrl(),
        'headers' => $request->headers->all(),
        'raw_body' => $request->getContent(),
        'parsed_body' => $request->all(),
        'ip' => $request->ip(),
    ]);
    
    return response()->json([
        'success' => true,
        'message' => 'Webhook de prueba recibido correctamente',
        'received_data' => $request->all(),
        'timestamp' => now()->toDateTimeString(),
    ]);
})->name('api.whatsapp.test');

// Rutas API para WhatsApp
Route::middleware(['auth'])->group(function () {
    Route::get('/whatsapp/conversations', [WhatsappController::class, 'getConversations'])->name('api.whatsapp.conversations');
    Route::get('/whatsapp/conversations/search', [WhatsappController::class, 'searchConversations'])->name('api.whatsapp.conversations.search');
    Route::get('/whatsapp/conversations/{id}/messages', [WhatsappController::class, 'getMessages'])->name('api.whatsapp.messages');
    Route::post('/whatsapp/conversations/{id}/send', [WhatsappController::class, 'sendMessage'])->name('api.whatsapp.send');
});

// Webhooks para órdenes programadas (Bot Alpha)
// Crear o actualizar orden programada
Route::post('/ordenes-programadas', function (\Illuminate\Http\Request $request) {
    try {
        $validated = $request->validate([
            'tipo' => 'required|in:extraccion_clientes,emails_automaticos',
            'activo' => 'boolean',
            'recurrencia_horas' => 'nullable|numeric|min:0.5',
            'configuracion' => 'nullable|array',
            'user_id' => 'nullable|exists:users,id',
        ]);

        // Buscar si ya existe una orden del mismo tipo para el usuario
        $orden = \App\Models\OrdenProgramada::where('tipo', $validated['tipo'])
            ->when(isset($validated['user_id']), function ($query) use ($validated) {
                return $query->where('user_id', $validated['user_id']);
            })
            ->first();

        if ($orden) {
            // Actualizar orden existente
            $orden->update([
                'activo' => $validated['activo'] ?? $orden->activo,
                'recurrencia_horas' => $validated['recurrencia_horas'] ?? $orden->recurrencia_horas,
                'configuracion' => $validated['configuracion'] ?? $orden->configuracion,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Orden actualizada correctamente',
                'data' => $orden->fresh(),
            ]);
        } else {
            // Crear nueva orden
            $orden = \App\Models\OrdenProgramada::create([
                'tipo' => $validated['tipo'],
                'activo' => $validated['activo'] ?? true,
                'recurrencia_horas' => $validated['recurrencia_horas'] ?? null,
                'configuracion' => $validated['configuracion'] ?? null,
                'user_id' => $validated['user_id'] ?? null,
                'last_run' => null,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Orden creada correctamente',
                'data' => $orden,
            ], 201);
        }
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error de validación',
            'errors' => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
        \Log::error('Error en webhook de órdenes programadas: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->name('api.ordenes-programadas.create');

// Obtener órdenes programadas pendientes (para n8n Schedule Trigger)
Route::get('/ordenes-programadas/pendientes', function (\Illuminate\Http\Request $request) {
    try {
        $minutos = $request->input('minutos', 30);
        $tipo = $request->input('tipo'); // opcional: filtrar por tipo
        
        $query = \App\Models\OrdenProgramada::pendientes($minutos);
        
        if ($tipo) {
            $query->porTipo($tipo);
        }
        
        $ordenes = $query->get();
        
        return response()->json([
            'success' => true,
            'count' => $ordenes->count(),
            'data' => $ordenes,
        ]);
    } catch (\Exception $e) {
        \Log::error('Error al obtener órdenes pendientes: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->name('api.ordenes-programadas.pendientes');

// Marcar orden como ejecutada (actualizar last_run)
Route::post('/ordenes-programadas/{id}/ejecutar', function ($id) {
    try {
        $orden = \App\Models\OrdenProgramada::findOrFail($id);
        $orden->update(['last_run' => now()]);
        
        return response()->json([
            'success' => true,
            'message' => 'Orden marcada como ejecutada',
            'data' => $orden->fresh(),
        ]);
    } catch (\Exception $e) {
        \Log::error('Error al marcar orden como ejecutada: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
})->name('api.ordenes-programadas.ejecutar');

