<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailRecibido extends Model
{
    protected $table = 'emails_recibidos';

    protected $fillable = [
        'message_id',
        'uid',
        'folder',
        'from_email',
        'from_name',
        'reply_to',
        'to_email',
        'to_name',
        'cc',
        'bcc',
        'subject',
        'body',
        'body_html',
        'attachments',
        'headers',
        'in_reply_to',
        'references',
        'priority',
        'is_read',
        'is_starred',
        'is_important',
        'has_attachments',
        'flags',
        'received_at',
        'sent_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'headers' => 'array',
        'flags' => 'array',
        'is_read' => 'boolean',
        'is_starred' => 'boolean',
        'is_important' => 'boolean',
        'has_attachments' => 'boolean',
        'received_at' => 'datetime',
        'sent_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

