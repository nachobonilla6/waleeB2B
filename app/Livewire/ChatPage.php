<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\ChatMessage;

class ChatPage extends Component
{
    public $messages = [];
    public $newMessage = '';
    public $isLoading = false;

    public function mount()
    {
        // Cargar mensajes históricos del usuario
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $chatMessages = ChatMessage::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc') // Ordenar por más recientes primero
            ->get();

        $this->messages = $chatMessages->map(function ($message) {
            return [
                'type' => $message->type,
                'content' => $message->message,
                'timestamp' => $message->created_at,
                'audio_url' => $message->audio_url,
            ];
        })->toArray();
    }

    /**
     * Generate audio from text using OpenAI TTS
     */
    private function generateAudio($text, $messageId)
    {
        try {
            $apiKey = config('services.openai.api_key');
            
            if (empty($apiKey)) {
                Log::warning('OpenAI API key not configured');
                return null;
            }

            // Call OpenAI TTS API
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.openai.com/v1/audio/speech', [
                    'model' => 'tts-1',
                    'input' => $text,
                    'voice' => 'alloy', // Options: alloy, echo, fable, onyx, nova, shimmer
                    'response_format' => 'mp3',
                ]);

            if ($response->successful()) {
                // Save audio file to storage
                $audioContent = $response->body();
                
                // Crear directorio si no existe
                $directory = 'chat-audio';
                if (!Storage::disk('public')->exists($directory)) {
                    Storage::disk('public')->makeDirectory($directory);
                }
                
                $filename = $directory . '/' . $messageId . '_' . time() . '.mp3';
                $saved = Storage::disk('public')->put($filename, $audioContent);
                
                if ($saved) {
                    // Return the public URL usando la ruta personalizada (sin symlink)
                    $url = route('chat.audio', ['filename' => basename($filename)]);
                    Log::info('Audio generado con OpenAI TTS', ['filename' => $filename, 'url' => $url, 'size' => strlen($audioContent)]);
                    return $url;
                } else {
                    Log::error('Error al guardar audio generado con OpenAI TTS');
                    return null;
                }
            } else {
                Log::error('OpenAI TTS API error: ' . $response->body());
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Error generating audio with OpenAI TTS: ' . $e->getMessage());
            return null;
        }
    }

    public function sendMessage()
    {
        Log::info('sendMessage() llamado', [
            'newMessage' => $this->newMessage,
            'isLoading' => $this->isLoading,
            'user_id' => auth()->id(),
        ]);
        
        if (empty(trim($this->newMessage))) {
            Log::warning('Intento de enviar mensaje vacío');
            return;
        }

        // Guardar mensaje del usuario en la base de datos
        $userMessage = trim($this->newMessage);
        
        Log::info('Enviando mensaje al webhook', [
            'user_id' => auth()->id(),
            'message' => $userMessage,
            'user_name' => auth()->user()->name ?? 'Usuario',
            'user_email' => auth()->user()->email ?? '',
        ]);
        
        $userChatMessage = ChatMessage::create([
            'user_id' => auth()->id(),
            'message' => $userMessage,
            'type' => 'user',
        ]);
        
        Log::info('Mensaje del usuario guardado en BD', [
            'chat_message_id' => $userChatMessage->id,
        ]);

        // Agregar mensaje del usuario al inicio (orden descendente - más recientes primero)
        array_unshift($this->messages, [
            'type' => 'user',
            'content' => $userMessage,
            'timestamp' => $userChatMessage->created_at,
        ]);

        $this->newMessage = '';
        $this->isLoading = true;

        // Obtener historial de conversación (últimos 100 mensajes) como texto
        try {
            $conversationHistory = ChatMessage::where('user_id', auth()->id())
                ->orderBy('created_at', 'asc')
                ->limit(100)
                ->get()
                ->map(function ($message) {
                    $sender = $message->type === 'user' ? (auth()->user()->name ?? 'Usuario') : 'WALEE';
                    $timestamp = $message->created_at->format('Y-m-d H:i:s');
                    return "[{$timestamp}] {$sender}: {$message->message}";
                })
                ->implode("\n");
            
            // Limitar el tamaño del historial a 50KB para evitar problemas
            if (strlen($conversationHistory) > 50000) {
                $conversationHistory = substr($conversationHistory, -50000);
                Log::warning('Historial de conversación truncado a 50KB');
            }
        } catch (\Exception $e) {
            Log::error('Error al obtener historial de conversación', [
                'error' => $e->getMessage(),
            ]);
            $conversationHistory = '';
        }

        // Preparar datos para el webhook
        $webhookData = [
            'message' => $userMessage,
            'user' => auth()->user()->name ?? 'Usuario',
            'email' => auth()->user()->email ?? '',
            'conversation_history' => $conversationHistory,
        ];
        
        Log::info('Datos a enviar al webhook', [
            'url' => 'https://n8n.srv1137974.hstgr.cloud/webhook-test/444688a4-305e-4d97-b667-5f52c2c3bda9',
            'data' => [
                'message' => $userMessage,
                'user' => auth()->user()->name ?? 'Usuario',
                'email' => auth()->user()->email ?? '',
                'conversation_history_length' => strlen($conversationHistory),
                'conversation_history_preview' => substr($conversationHistory, 0, 200),
            ],
        ]);

        // Enviar mensaje al webhook de n8n con historial
        try {
            $response = Http::timeout(60)
                ->retry(2, 1000) // Reintentar 2 veces con 1 segundo de espera
                ->post('https://n8n.srv1137974.hstgr.cloud/webhook-test/444688a4-305e-4d97-b667-5f52c2c3bda9', $webhookData);
            
            Log::info('Respuesta del webhook recibida', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'headers' => $response->headers(),
                'body_preview' => substr($response->body(), 0, 500),
            ]);

            if ($response->successful()) {
                $contentType = $response->header('Content-Type') ?? '';
                $audioUrl = null;
                $assistantMessage = null;
                
                // Verificar si la respuesta es un archivo de audio MP3 (binario directo)
                if (str_contains($contentType, 'audio/mpeg') || str_contains($contentType, 'audio/mp3') || str_contains($contentType, 'application/octet-stream')) {
                    // La respuesta es directamente un archivo de audio
                    $audioContent = $response->body();
                    
                    if (!empty($audioContent) && strlen($audioContent) > 100) { // Verificar que sea un archivo válido
                        // Crear directorio si no existe
                        $directory = 'chat-audio';
                        if (!Storage::disk('public')->exists($directory)) {
                            Storage::disk('public')->makeDirectory($directory);
                        }
                        
                        $filename = $directory . '/webhook_' . auth()->id() . '_' . time() . '.mp3';
                        $saved = Storage::disk('public')->put($filename, $audioContent);
                        
                        if ($saved) {
                            // Generar URL usando la ruta personalizada (sin symlink)
                            $audioUrl = route('chat.audio', ['filename' => basename($filename)]);
                            Log::info('Audio guardado exitosamente', ['filename' => $filename, 'url' => $audioUrl, 'size' => strlen($audioContent)]);
                        } else {
                            Log::error('Error al guardar audio del webhook');
                        }
                        
                        // Intentar obtener el texto del mensaje si viene en headers
                        $assistantMessage = $response->header('X-Message-Text') ?? $response->header('X-Output') ?? 'Mensaje de audio';
                    } else {
                        Log::warning('Contenido de audio vacío o muy pequeño', ['size' => strlen($audioContent ?? '')]);
                    }
                } else {
                    // La respuesta es JSON
                    try {
                        $responseData = $response->json();
                        
                        // Obtener la respuesta del webhook
                        // El formato de n8n puede ser un array con objetos que tienen "output"
                        if (is_array($responseData) && isset($responseData[0]['output'])) {
                            $assistantMessage = $responseData[0]['output'];
                        } elseif (is_array($responseData) && isset($responseData['output'])) {
                            $assistantMessage = $responseData['output'];
                        } else {
                            $assistantMessage = $responseData['response'] ?? $responseData['message'] ?? $responseData['text'] ?? $responseData['output'] ?? 'Gracias por tu mensaje.';
                        }
                        
                        // Verificar si viene un archivo de audio en la respuesta JSON
                        if (isset($responseData['audio_url']) || isset($responseData[0]['audio_url'])) {
                            $audioUrl = $responseData['audio_url'] ?? $responseData[0]['audio_url'] ?? null;
                        } elseif (isset($responseData['audio']) || isset($responseData[0]['audio'])) {
                            // Si viene el audio como base64 o datos binarios
                            $audioData = $responseData['audio'] ?? $responseData[0]['audio'] ?? null;
                            if ($audioData) {
                                // Si es base64, decodificarlo
                                if (is_string($audioData) && str_starts_with($audioData, 'data:audio')) {
                                    $audioContent = base64_decode(explode(',', $audioData)[1] ?? '');
                                } else {
                                    $audioContent = is_string($audioData) ? base64_decode($audioData) : $audioData;
                                }
                                
                                if ($audioContent && strlen($audioContent) > 100) {
                                    // Crear directorio si no existe
                                    $directory = 'chat-audio';
                                    if (!Storage::disk('public')->exists($directory)) {
                                        Storage::disk('public')->makeDirectory($directory);
                                    }
                                    
                                    $filename = $directory . '/webhook_' . auth()->id() . '_' . time() . '.mp3';
                                    $saved = Storage::disk('public')->put($filename, $audioContent);
                                    
                                    if ($saved) {
                                        // Generar URL usando la ruta personalizada (sin symlink)
                                        $audioUrl = route('chat.audio', ['filename' => basename($filename)]);
                                        Log::info('Audio guardado desde JSON', ['filename' => $filename, 'url' => $audioUrl]);
                                    }
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        // Si no es JSON válido, intentar como texto
                        $assistantMessage = $response->body();
                        Log::warning('Webhook response is not valid JSON: ' . $e->getMessage());
                    }
                }
                
                // Guardar respuesta del asistente en la base de datos
                $assistantChatMessage = ChatMessage::create([
                    'user_id' => auth()->id(),
                    'message' => $assistantMessage ?? 'Mensaje de audio',
                    'type' => 'assistant',
                ]);
                
                // Si no hay audio del webhook, generar con OpenAI TTS
                if (!$audioUrl) {
                    $audioUrl = $this->generateAudio($assistantMessage ?? 'Mensaje de audio', $assistantChatMessage->id);
                }
                
                if ($audioUrl) {
                    $assistantChatMessage->update(['audio_url' => $audioUrl]);
                }
                
                // Agregar mensaje del asistente al inicio (orden descendente)
                array_unshift($this->messages, [
                    'type' => 'assistant',
                    'content' => $assistantMessage ?? 'Mensaje de audio',
                    'timestamp' => $assistantChatMessage->created_at,
                    'audio_url' => $audioUrl,
                    'id' => $assistantChatMessage->id,
                ]);
                
                // Disparar evento para reproducir audio automáticamente
                $this->dispatch('new-audio-message', audioUrl: $audioUrl);
            } else {
                Log::error('Webhook respondió con error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'headers' => $response->headers(),
                ]);
                
                $errorMessage = 'Lo siento, hubo un error al procesar tu mensaje. Por favor, intenta de nuevo.';
                
                // Guardar mensaje de error en la base de datos
                $errorChatMessage = ChatMessage::create([
                    'user_id' => auth()->id(),
                    'message' => $errorMessage,
                    'type' => 'assistant',
                ]);
                
                // Agregar mensaje de error al inicio (orden descendente)
                array_unshift($this->messages, [
                    'type' => 'assistant',
                    'content' => $errorMessage . ' (Status: ' . $response->status() . ')',
                    'timestamp' => $errorChatMessage->created_at,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error al enviar mensaje al webhook de n8n', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => auth()->id(),
                'webhook_url' => 'https://n8n.srv1137974.hstgr.cloud/webhook-test/444688a4-305e-4d97-b667-5f52c2c3bda9',
            ]);
            
            $errorMessage = 'Lo siento, hubo un error al conectarse con el servidor. Por favor, intenta de nuevo más tarde.';
            
            // Guardar mensaje de error en la base de datos
            $errorChatMessage = ChatMessage::create([
                'user_id' => auth()->id(),
                'message' => $errorMessage,
                'type' => 'assistant',
            ]);
            
            // Agregar mensaje de error al inicio (orden descendente)
            array_unshift($this->messages, [
                'type' => 'assistant',
                'content' => $errorMessage . ' (Error: ' . substr($e->getMessage(), 0, 100) . ')',
                'timestamp' => $errorChatMessage->created_at,
            ]);
        } finally {
            $this->isLoading = false;
        }
    }

    public function render()
    {
        return view('livewire.chat-page');
    }
}

