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
            ->orderBy('created_at', 'asc')
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
        if (empty(trim($this->newMessage))) {
            return;
        }

        // Guardar mensaje del usuario en la base de datos
        $userMessage = trim($this->newMessage);
        $userChatMessage = ChatMessage::create([
            'user_id' => auth()->id(),
            'message' => $userMessage,
            'type' => 'user',
        ]);

        // Agregar mensaje del usuario a la vista
        $this->messages[] = [
            'type' => 'user',
            'content' => $userMessage,
            'timestamp' => $userChatMessage->created_at,
        ];

        $this->newMessage = '';
        $this->isLoading = true;

        // Enviar mensaje al webhook de n8n
        try {
            $response = Http::timeout(60)->post('https://n8n.srv1137974.hstgr.cloud/webhook/444688a4-305e-4d97-b667-5f52c2c3bda9', [
                'message' => $userMessage,
                'user' => auth()->user()->name ?? 'Usuario',
                'email' => auth()->user()->email ?? '',
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
                
                $this->messages[] = [
                    'type' => 'assistant',
                    'content' => $assistantMessage ?? 'Mensaje de audio',
                    'timestamp' => $assistantChatMessage->created_at,
                    'audio_url' => $audioUrl,
                    'id' => $assistantChatMessage->id,
                ];
                
                // Disparar evento para reproducir audio automáticamente
                $this->dispatch('new-audio-message', audioUrl: $audioUrl);
            } else {
                $errorMessage = 'Lo siento, hubo un error al procesar tu mensaje. Por favor, intenta de nuevo.';
                
                // Guardar mensaje de error en la base de datos
                $errorChatMessage = ChatMessage::create([
                    'user_id' => auth()->id(),
                    'message' => $errorMessage,
                    'type' => 'assistant',
                ]);
                
                $this->messages[] = [
                    'type' => 'assistant',
                    'content' => $errorMessage,
                    'timestamp' => $errorChatMessage->created_at,
                ];
            }
        } catch (\Exception $e) {
            Log::error('Error al enviar mensaje al webhook de n8n: ' . $e->getMessage());
            
            $errorMessage = 'Lo siento, hubo un error al conectarse con el servidor. Por favor, intenta de nuevo más tarde.';
            
            // Guardar mensaje de error en la base de datos
            $errorChatMessage = ChatMessage::create([
                'user_id' => auth()->id(),
                'message' => $errorMessage,
                'type' => 'assistant',
            ]);
            
            $this->messages[] = [
                'type' => 'assistant',
                'content' => $errorMessage,
                'timestamp' => $errorChatMessage->created_at,
            ];
        } finally {
            $this->isLoading = false;
        }
    }

    public function render()
    {
        return view('livewire.chat-page');
    }
}

