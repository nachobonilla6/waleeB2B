<?php

namespace App\Livewire;

use Livewire\Component;

class FloatingChat extends Component
{
    public $isOpen = false;
    public $messages = [];
    public $newMessage = '';

    public function toggleChat()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function sendMessage()
    {
        if (trim($this->newMessage) === '') {
            return;
        }

        $this->messages[] = [
            'id' => uniqid(),
            'text' => $this->newMessage,
            'sender' => 'user',
            'timestamp' => now()->format('H:i'),
        ];

        $this->newMessage = '';

        // Aquí puedes agregar lógica para enviar el mensaje a un backend/API
        // Por ahora solo lo agregamos a la lista de mensajes
    }

    public function render()
    {
        return view('livewire.floating-chat');
    }
}
