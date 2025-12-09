<?php

namespace App\Livewire;

use Livewire\Component;

class ChatWidget extends Component
{
    public $isOpen = false;
    public $messages = [];
    public $newMessage = '';

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

    public function toggleChat()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function sendMessage()
    {
        if (empty(trim($this->newMessage))) {
            return;
        }

        // Agregar mensaje del usuario
        $this->messages[] = [
            'type' => 'user',
            'content' => trim($this->newMessage),
            'timestamp' => now(),
        ];

        $userMessage = trim($this->newMessage);
        $this->newMessage = '';

        // Simular respuesta del asistente después de 1 segundo
        // En una implementación real, aquí harías una llamada a tu API de chat
        $this->js("
            setTimeout(() => {
                \$wire.addAssistantMessage('Gracias por tu mensaje. Estoy aquí para ayudarte con cualquier consulta sobre websolutions.work.');
            }, 1000);
        ");
    }

    public function addAssistantMessage($message)
    {
        $this->messages[] = [
            'type' => 'assistant',
            'content' => $message,
            'timestamp' => now(),
        ];
    }

    public function render()
    {
        return view('livewire.chat-widget');
    }
}
