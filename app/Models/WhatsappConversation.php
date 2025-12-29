<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WhatsappConversation extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_conversations';

    protected $fillable = [
        'phone_number',
        'contact_name',
        'contact_image',
        'last_message',
        'last_message_at',
        'is_archived',
        'is_pinned',
        'unread_count',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'is_archived' => 'boolean',
        'is_pinned' => 'boolean',
        'unread_count' => 'integer',
    ];

    /**
     * Relación con los mensajes de la conversación
     */
    public function messages(): HasMany
    {
        return $this->hasMany(WhatsappMessage::class, 'conversation_id')->orderBy('created_at', 'asc');
    }

    /**
     * Obtener el último mensaje
     */
    public function lastMessage()
    {
        return $this->messages()->latest()->first();
    }

    /**
     * Actualizar el último mensaje y timestamp
     */
    public function updateLastMessage(string $content, $timestamp = null)
    {
        $this->update([
            'last_message' => $content,
            'last_message_at' => $timestamp ?? now(),
        ]);
    }

    /**
     * Incrementar contador de no leídos
     */
    public function incrementUnread()
    {
        $this->increment('unread_count');
    }

    /**
     * Marcar como leído
     */
    public function markAsRead()
    {
        $this->update(['unread_count' => 0]);
    }
}

