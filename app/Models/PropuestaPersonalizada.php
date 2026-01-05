<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropuestaPersonalizada extends Model
{
    protected $table = 'emails';

    protected $fillable = [
        'cliente_id',
        'cliente_nombre',
        'email',
        'subject',
        'body',
        'ai_prompt',
        'sitio_id',
        'enlace',
        'attachment',
        'user_id',
        'tipo',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Client::class, 'cliente_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function sitio(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sitio::class);
    }

    /**
     * Get archivos as array (handles both single file string and JSON array)
     */
    public function getArchivosAttribute(): array
    {
        if (empty($this->attachment)) {
            return [];
        }
        
        // Try to decode as JSON (multiple files)
        $decoded = json_decode($this->attachment, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }
        
        // Single file (string)
        return [$this->attachment];
    }
}
