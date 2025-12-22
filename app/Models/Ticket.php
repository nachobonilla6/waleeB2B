<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    protected $table = 'tickets';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'telefono',
        'website',
        'asunto',
        'mensaje',
        'imagen',
        'estado',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Estados disponibles
     */
    const ESTADO_ENVIADO = 'enviado';
    const ESTADO_RECIBIDO = 'recibido';
    const ESTADO_RESUELTO = 'resuelto';

    public static function estados(): array
    {
        return [
            self::ESTADO_ENVIADO => 'Enviado',
            self::ESTADO_RECIBIDO => 'Recibido',
            self::ESTADO_RESUELTO => 'Resuelto',
        ];
    }

    /**
     * Get the user that owns the ticket
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get estado badge color
     */
    public function getEstadoColorAttribute(): string
    {
        return match($this->estado) {
            self::ESTADO_ENVIADO => 'amber',
            self::ESTADO_RECIBIDO => 'blue',
            self::ESTADO_RESUELTO => 'emerald',
            default => 'slate',
        };
    }

    /**
     * Get archivos as array (handles both single file string and JSON array)
     */
    public function getArchivosAttribute(): array
    {
        if (empty($this->imagen)) {
            return [];
        }
        
        // Try to decode as JSON (multiple files)
        $decoded = json_decode($this->imagen, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }
        
        // Single file (string)
        return [$this->imagen];
    }
}

