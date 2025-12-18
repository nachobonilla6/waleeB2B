<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailRecibido extends Model
{
    protected $table = 'emails_recibidos';

    protected $fillable = [
        'message_id',
        'from_email',
        'from_name',
        'subject',
        'body',
        'body_html',
        'attachments',
        'is_read',
        'is_starred',
        'received_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_read' => 'boolean',
        'is_starred' => 'boolean',
        'received_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

