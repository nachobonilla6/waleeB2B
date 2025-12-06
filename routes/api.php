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

// Webhook para errores de n8n (Error Trigger) - DESHABILITADO: Modelo N8nError eliminado
// Route::post('/n8n-error', ...) - Comentado porque N8nError ya no existe

// Webhook para contenido de marketing desde n8n - DESHABILITADO: Modelo N8nPost eliminado
// Route::post('/n8n-content', ...) - Comentado porque N8nPost ya no existe

