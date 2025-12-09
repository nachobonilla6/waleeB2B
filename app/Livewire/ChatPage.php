<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
            ];
        })->toArray();
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
            $response = Http::timeout(30)->post('https://n8n.srv1137974.hstgr.cloud/webhook/444688a4-305e-4d97-b667-5f52c2c3bda9', [
                'message' => $userMessage,
                'user' => auth()->user()->name ?? 'Usuario',
                'email' => auth()->user()->email ?? '',
            ]);

            if ($response->successful()) {
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
                
                // Guardar respuesta del asistente en la base de datos
                $assistantChatMessage = ChatMessage::create([
                    'user_id' => auth()->id(),
                    'message' => $assistantMessage,
                    'type' => 'assistant',
                ]);
                
                $this->messages[] = [
                    'type' => 'assistant',
                    'content' => $assistantMessage,
                    'timestamp' => $assistantChatMessage->created_at,
                ];
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

