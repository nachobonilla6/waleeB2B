<?php

use Illuminate\Support\Facades\Route;
use Filament\Notifications\Notification;
use App\Models\User;
use App\Models\N8nError;
use App\Models\N8nPost;

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

// Webhook para errores de n8n (Error Trigger)
Route::post('/n8n-error', function (\Illuminate\Http\Request $request) {
    \Log::info('Error de n8n recibido', [
        'body' => $request->all(),
        'ip' => $request->ip(),
    ]);

    try {
        $data = $request->all();
        
        // Si es array, tomar el primer elemento
        if (isset($data[0])) {
            $data = $data[0];
        }
        
        // Guardar el error en la base de datos
        $n8nError = N8nError::createFromN8n($data);
        
        // Extraer datos para la notificación
        $errorMessage = $n8nError->error_message ?: 'Error sin mensaje';
        $workflowName = $n8nError->workflow_name ?: '';
        $nodeName = $n8nError->last_node_executed ?: '';
        $executionUrl = $n8nError->execution_url ?: '';
        $mode = $n8nError->mode ?: '';

        // Construir el mensaje de forma limpia
        $bodyParts = [];
        
        if ($errorMessage) {
            $bodyParts[] = "🔴 {$errorMessage}";
        }
        if ($nodeName) {
            $bodyParts[] = "📍 Nodo: {$nodeName}";
        }
        if ($workflowName) {
            $bodyParts[] = "⚙️ Workflow: {$workflowName}";
        }

        $body = implode("\n", $bodyParts);
        
        if (empty($body)) {
            $body = "Se produjo un error en n8n.";
        }

        $users = User::all();
        
        if ($users->isEmpty()) {
            \Log::warning('No hay usuarios para enviar notificaciones de error');
            return response()->json(['success' => false, 'message' => 'No hay usuarios', 'error_id' => $n8nError->id], 200);
        }

        $notificationsSent = 0;
        foreach ($users as $user) {
            try {
                $notification = Notification::make()
                    ->title('⚠️ Error en n8n')
                    ->body($body)
                    ->danger()
                    ->icon('heroicon-o-exclamation-triangle')
                    ->duration(10000);

                $notificationData = $notification->getDatabaseMessage();

                $user->notifications()->create([
                    'id' => \Illuminate\Support\Str::uuid()->toString(),
                    'type' => \Filament\Notifications\DatabaseNotification::class,
                    'data' => $notificationData,
                    'read_at' => now(),
                ]);

                $notificationsSent++;
            } catch (\Exception $e) {
                \Log::error('Error enviando notificación de error a usuario ' . $user->id . ': ' . $e->getMessage());
            }
        }

        \Log::info("Notificaciones de error enviadas: {$notificationsSent}");

        return response()->json([
            'success' => true,
            'message' => 'Error guardado y notificación enviada',
            'error_id' => $n8nError->id,
            'notifications_sent' => $notificationsSent,
        ], 200);

    } catch (\Exception $e) {
        \Log::error('Error procesando error de n8n: ' . $e->getMessage());
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
})->name('api.n8n-error');

// Webhook para contenido de marketing desde n8n
Route::post('/n8n-content', function (\Illuminate\Http\Request $request) {
    \Log::info('Contenido de n8n recibido', [
        'body' => $request->all(),
        'ip' => $request->ip(),
    ]);

    try {
        $data = $request->all();
        
        // Si es array, tomar el primer elemento
        if (isset($data[0])) {
            $data = $data[0];
        }
        
        // Guardar en la base de datos
        $post = N8nPost::createFromN8n($data);
        
        $titulo = $post->titulo;
        $texto = $post->texto ?? '';
        $hashtagsString = $post->hashtags_string;

        // Limitar texto a 30 palabras para notificación
        $palabras = explode(' ', $texto);
        $textoLimitado = implode(' ', array_slice($palabras, 0, 30));
        if (count($palabras) > 30) {
            $textoLimitado .= '...';
        }

        $body = $textoLimitado;
        if ($hashtagsString) {
            $body .= "\n\n" . $hashtagsString;
        }

        // Enviar notificación a todos los usuarios de Filament
        $users = User::all();
        $notificationsSent = 0;

        foreach ($users as $user) {
            try {
                $notification = Notification::make()
                    ->title("📢 {$titulo}")
                    ->body($body)
                    ->success()
                    ->icon('heroicon-o-megaphone')
                    ->duration(10000);

                $notificationData = $notification->getDatabaseMessage();

                $user->notifications()->create([
                    'id' => \Illuminate\Support\Str::uuid()->toString(),
                    'type' => \Filament\Notifications\DatabaseNotification::class,
                    'data' => $notificationData,
                    'read_at' => null, // No marcar como leída para que aparezca en la campana
                ]);

                $notificationsSent++;
            } catch (\Exception $e) {
                \Log::error('Error enviando notificación de contenido: ' . $e->getMessage());
            }
        }

        \Log::info("Post #{$post->id} guardado. Notificaciones enviadas: {$notificationsSent}");

        return response()->json([
            'success' => true,
            'message' => 'Contenido guardado y notificación enviada',
            'post_id' => $post->id,
            'titulo' => $titulo,
            'notifications_sent' => $notificationsSent,
            'timestamp' => now()->toDateTimeString(),
        ], 200);

    } catch (\Exception $e) {
        \Log::error('Error procesando contenido de n8n: ' . $e->getMessage());
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
})->name('api.n8n-content');

