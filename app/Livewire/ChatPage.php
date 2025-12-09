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
    public $voiceEnabled = true; // Toggle de voz activado por defecto

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
        if (empty(trim($this->newMessage ?? ''))) {
            return;
        }

        $userMessage = trim($this->newMessage);

        // Guardar mensaje del usuario
        $userChatMessage = ChatMessage::create([
            'user_id' => auth()->id(),
            'message' => $userMessage,
            'type' => 'user',
        ]);

        // Agregar mensaje del usuario al inicio (más reciente primero)
        array_unshift($this->messages, [
            'type' => 'user',
            'content' => $userMessage,
            'timestamp' => $userChatMessage->created_at,
        ]);

        $this->newMessage = '';
        $this->isLoading = true;

        try {
            // Historial breve (últimos 20 en orden cronológico)
            $history = ChatMessage::where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get()
                ->reverse()
                ->map(function ($message) {
                    return [
                        'role' => $message->type === 'user' ? 'user' : 'assistant',
                        'content' => $message->message,
                    ];
                })
                ->values()
                ->toArray();

            $assistantMessage = $this->generateAiResponse($history, $userMessage);

            $assistantChatMessage = ChatMessage::create([
                'user_id' => auth()->id(),
                'message' => $assistantMessage,
                'type' => 'assistant',
            ]);

            $audioUrl = null;
            if ($this->voiceEnabled) {
                $audioUrl = $this->generateAudio($assistantMessage, $assistantChatMessage->id);
                if ($audioUrl) {
                    $assistantChatMessage->update(['audio_url' => $audioUrl]);
                }
            }

            array_unshift($this->messages, [
                'type' => 'assistant',
                'content' => $assistantMessage,
                'timestamp' => $assistantChatMessage->created_at,
                'audio_url' => $audioUrl,
                'id' => $assistantChatMessage->id,
            ]);

            if ($audioUrl) {
                $this->dispatch('new-audio-message', audioUrl: $audioUrl);
            }
        } catch (\Exception $e) {
            Log::error('Error al generar respuesta con OpenAI', [
                'message' => $e->getMessage(),
            ]);

            $errorMessage = 'Lo siento, hubo un problema al generar la respuesta. Intenta de nuevo.';

            $errorChatMessage = ChatMessage::create([
                'user_id' => auth()->id(),
                'message' => $errorMessage,
                'type' => 'assistant',
            ]);

            array_unshift($this->messages, [
                'type' => 'assistant',
                'content' => $errorMessage,
                'timestamp' => $errorChatMessage->created_at,
            ]);
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Genera respuesta con OpenAI Chat
     */
    private function generateAiResponse(array $history, string $userMessage): string
    {
        $apiKey = config('services.openai.api_key');
        if (empty($apiKey)) {
            throw new \RuntimeException('OpenAI API key no configurada');
        }

        $messages = array_merge(
            [
                [
                    'role' => 'system',
                    'content' => 'Eres WALEE, asistente de websolutions.work. Responde de forma breve, clara y en español. Cuando haya enlaces, preséntalos con texto descriptivo. Puedes ayudar a revisar disponibilidad en calendario y redactar correos si el usuario lo solicita.',
                ],
            ],
            $history,
            [
                [
                    'role' => 'user',
                    'content' => $userMessage,
                ],
            ]
        );

        $response = Http::timeout(60)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => $messages,
                'temperature' => 0.6,
                'max_tokens' => 500,
            ]);

        if (!$response->successful()) {
            Log::error('OpenAI Chat error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \RuntimeException('Error al generar respuesta con OpenAI');
        }

        $json = $response->json();
        return $json['choices'][0]['message']['content'] ?? 'Lo siento, no pude generar una respuesta en este momento.';
    }

    public function toggleVoice()
    {
        $this->voiceEnabled = !$this->voiceEnabled;
    }

    public function render()
    {
        return view('livewire.chat-page');
    }
}

