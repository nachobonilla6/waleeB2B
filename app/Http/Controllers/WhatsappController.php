<?php

namespace App\Http\Controllers;

use App\Models\WhatsappConversation;
use App\Models\WhatsappMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WhatsappController extends Controller
{
    /**
     * Obtener todas las conversaciones
     */
    public function getConversations()
    {
        $conversations = WhatsappConversation::with(['lastMessage'])
            ->orderBy('last_message_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($conversation) {
                return [
                    'id' => $conversation->id,
                    'phone_number' => $conversation->phone_number,
                    'contact_name' => $conversation->contact_name ?? $conversation->phone_number,
                    'contact_image' => $conversation->contact_image,
                    'last_message' => $conversation->last_message,
                    'last_message_at' => $conversation->last_message_at?->format('Y-m-d H:i:s'),
                    'unread_count' => $conversation->unread_count,
                    'is_pinned' => $conversation->is_pinned,
                ];
            });

        return response()->json([
            'success' => true,
            'conversations' => $conversations,
        ]);
    }

    /**
     * Obtener mensajes de una conversación
     */
    public function getMessages($conversationId)
    {
        $conversation = WhatsappConversation::findOrFail($conversationId);
        
        // Marcar como leído
        $conversation->markAsRead();

        $messages = $conversation->messages()
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'direction' => $message->direction,
                    'content' => $message->content,
                    'message_type' => $message->message_type,
                    'media_url' => $message->media_url,
                    'media_type' => $message->media_type,
                    'media_name' => $message->media_name,
                    'status' => $message->status,
                    'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                    'time' => $message->created_at->format('H:i'),
                    'date' => $message->created_at->format('Y-m-d'),
                ];
            });

        return response()->json([
            'success' => true,
            'conversation' => [
                'id' => $conversation->id,
                'phone_number' => $conversation->phone_number,
                'contact_name' => $conversation->contact_name ?? $conversation->phone_number,
                'contact_image' => $conversation->contact_image,
            ],
            'messages' => $messages,
        ]);
    }

    /**
     * Enviar un mensaje
     */
    public function sendMessage(Request $request, $conversationId)
    {
        $request->validate([
            'content' => 'required|string|max:4096',
        ]);

        $conversation = WhatsappConversation::findOrFail($conversationId);
        $content = $request->input('content');

        // Crear mensaje en la base de datos
        $message = WhatsappMessage::create([
            'conversation_id' => $conversation->id,
            'direction' => 'outgoing',
            'content' => $content,
            'message_type' => 'text',
            'status' => 'pending', // Cambiar a 'sent' cuando se confirme desde n8n
        ]);

        // Actualizar última conversación
        $conversation->updateLastMessage($content);

        // Enviar mensaje a través de n8n (si tienes un webhook para enviar)
        // Por ahora solo lo guardamos, n8n debería enviar el mensaje y luego confirmar con otro webhook

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'direction' => $message->direction,
                'content' => $message->content,
                'status' => $message->status,
                'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                'time' => $message->created_at->format('H:i'),
            ],
        ]);
    }

    /**
     * Buscar conversaciones
     */
    public function searchConversations(Request $request)
    {
        $query = $request->input('q', '');

        if (empty($query)) {
            return $this->getConversations();
        }

        $conversations = WhatsappConversation::where('phone_number', 'like', "%{$query}%")
            ->orWhere('contact_name', 'like', "%{$query}%")
            ->orderBy('last_message_at', 'desc')
            ->get()
            ->map(function ($conversation) {
                return [
                    'id' => $conversation->id,
                    'phone_number' => $conversation->phone_number,
                    'contact_name' => $conversation->contact_name ?? $conversation->phone_number,
                    'contact_image' => $conversation->contact_image,
                    'last_message' => $conversation->last_message,
                    'last_message_at' => $conversation->last_message_at?->format('Y-m-d H:i:s'),
                    'unread_count' => $conversation->unread_count,
                    'is_pinned' => $conversation->is_pinned,
                ];
            });

        return response()->json([
            'success' => true,
            'conversations' => $conversations,
        ]);
    }
}

