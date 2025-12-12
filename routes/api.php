<?php

use Illuminate\Support\Facades\Route;
use Filament\Notifications\Notification;
use App\Models\User;

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

// Webhook para errores de n8n (Error Trigger) - DESHABILITADO: Modelo N8nError eliminado
// Route::post('/n8n-error', ...) - Comentado porque N8nError ya no existe

// Webhook para contenido de marketing desde n8n - DESHABILITADO: Modelo N8nPost eliminado
// Route::post('/n8n-content', ...) - Comentado porque N8nPost ya no existe

