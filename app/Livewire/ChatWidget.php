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

        $userMessage = strtolower(trim($this->newMessage));
        $this->newMessage = '';

        // Generar respuesta inteligente basada en el mensaje del usuario
        $response = $this->generateResponse($userMessage);

        // Simular respuesta del asistente después de 1 segundo
        $this->js("
            setTimeout(() => {
                \$wire.addAssistantMessage(" . json_encode($response) . ");
            }, 1000);
        ");
    }

    protected function generateResponse($message)
    {
        // Respuestas inteligentes basadas en palabras clave
        if (str_contains($message, 'hola') || str_contains($message, 'hi') || str_contains($message, 'buenos días') || str_contains($message, 'buenas tardes')) {
            return '¡Hola! 👋 Me alegra saludarte. ¿En qué puedo ayudarte hoy?';
        }

        if (str_contains($message, 'calendario') || str_contains($message, 'cita') || str_contains($message, 'agendar')) {
            return 'Para gestionar citas y calendarios, puedes usar la página de Google Calendar en el menú. Allí podrás ver, crear y editar citas, y sincronizarlas con tu calendario de Google. 📅';
        }

        if (str_contains($message, 'publicación') || str_contains($message, 'publicar') || str_contains($message, 'vela')) {
            return 'Puedes crear y gestionar publicaciones de velas desde el recurso "Publicación Vela" en el menú. Usa el botón "Nueva Publicación" para crear una nueva entrada. 🕯️';
        }

        if (str_contains($message, 'cliente') || str_contains($message, 'clientes')) {
            return 'Para gestionar clientes, ve a la sección de Clientes en el menú. Allí podrás ver, crear y editar información de tus clientes. 👥';
        }

        if (str_contains($message, 'ayuda') || str_contains($message, 'help') || str_contains($message, 'soporte')) {
            return 'Estoy aquí para ayudarte. Puedo ayudarte con: gestión de calendarios y citas, publicaciones, clientes, y más. ¿Sobre qué necesitas ayuda específicamente? 💬';
        }

        if (str_contains($message, 'gracias') || str_contains($message, 'thank')) {
            return '¡De nada! 😊 Si necesitas algo más, no dudes en preguntarme.';
        }

        if (str_contains($message, 'adios') || str_contains($message, 'bye') || str_contains($message, 'hasta luego')) {
            return '¡Hasta luego! Que tengas un excelente día. 👋';
        }

        if (str_contains($message, 'google') || str_contains($message, 'oauth')) {
            return 'Para autorizar Google Calendar, ve a la página "Autorizar Google Calendar" en el menú. Allí podrás conectar tu cuenta de Google para sincronizar eventos. 🔐';
        }

        // Respuesta por defecto más útil
        return 'Entiendo tu consulta. Puedo ayudarte con: gestión de calendarios, publicaciones, clientes, y configuración del sistema. ¿Podrías ser más específico sobre lo que necesitas? 🤔';
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
