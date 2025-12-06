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
    ];

    protected $casts = [
        'fecha' => 'date',
        'monto' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
}
