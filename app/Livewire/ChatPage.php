<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatPage extends Component
{
    public $messages = [];
    public $newMessage = '';
    public $isLoading = false;

    public function mount()
    {
        // Mensaje inicial del asistente
        $this->messages = [
            [
                'type' => 'assistant',
                'content' => '¡Hola! Soy WALEE, tu asistente de websolutions.work. ¿En qué puedo ayudarte hoy?',
                'timestamp' => now(),
            ],
        ];
    }

    public function sendMessage()
    {
        if (empty(trim($this->newMessage))) {
            return;
        }

        // Agregar mensaje del usuario
        $userMessage = trim($this->newMessage);
        $this->messages[] = [
            'type' => 'user',
            'content' => $userMessage,
            'timestamp' => now(),
        ];

        $this->newMessage = '';
        $this->isLoading = true;

        // Enviar mensaje al webhook de n8n
        try {
            $response = Http::timeout(30)->post('https://n8n.srv1137974.hstgr.cloud/webhook-test/444688a4-305e-4d97-b667-5f52c2c3bda9', [
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
                
                $this->messages[] = [
                    'type' => 'assistant',
                    'content' => $assistantMessage,
                    'timestamp' => now(),
                ];
            } else {
                $this->messages[] = [
                    'type' => 'assistant',
                    'content' => 'Lo siento, hubo un error al procesar tu mensaje. Por favor, intenta de nuevo.',
                    'timestamp' => now(),
                ];
            }
        } catch (\Exception $e) {
            Log::error('Error al enviar mensaje al webhook de n8n: ' . $e->getMessage());
            
            $this->messages[] = [
                'type' => 'assistant',
                'content' => 'Lo siento, hubo un error al conectarse con el servidor. Por favor, intenta de nuevo más tarde.',
                'timestamp' => now(),
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

