<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cita extends Model
{
    protected $fillable = [
        'titulo',
        'descripcion',
        'notas',
        'fecha_inicio',
        'fecha_fin',
        'cliente_id',
        'client_id', // ID de clientes_en_proceso
        'cliente', // Mantener por compatibilidad
        'ubicacion',
        'estado',
        'google_event_id',
        'google_calendar_id',
        'recurrencia',
        'recurrencia_fin',
        'color',
        'recurrencia_dias',
        'invitados_emails',
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'recurrencia_dias' => 'array',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
