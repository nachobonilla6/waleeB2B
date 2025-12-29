<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsappMessage extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_messages';

    protected $fillable = [
        'conversation_id',
        'direction',
        'content',
        'message_id',
        'message_type',
        'media_url',
        'media_type',
        'media_name',
        'status',
        'whatsapp_timestamp',
        'metadata',
    ];

    protected $casts = [
        'whatsapp_timestamp' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Relación con la conversación
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(WhatsappConversation::class, 'conversation_id');
    }

    /**
     * Verificar si el mensaje es entrante
     */
    public function isIncoming(): bool
    {
        return $this->direction === 'incoming';
    }

    /**
     * Verificar si el mensaje es saliente
     */
    public function isOutgoing(): bool
    {
        return $this->direction === 'outgoing';
    }
}

