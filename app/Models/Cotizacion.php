<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cotizacion extends Model
{
    protected $fillable = [
        'numero_cotizacion',
        'fecha',
        'idioma',
        'cliente_id',
        'tipo_servicio',
        'plan',
        'monto',
        'vigencia',
        'correo',
        'descripcion',
        'estado',
        'enviada_at',
    ];

    protected $casts = [
        'fecha' => 'date',
        'monto' => 'decimal:2',
        'enviada_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
}
