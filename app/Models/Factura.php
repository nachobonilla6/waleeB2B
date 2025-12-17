<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Factura extends Model
{
    protected $table = 'facturas';

    protected $fillable = [
        'cliente_id',
        'correo',
        'numero_factura',
        'serie',
        'fecha_emision',
        'concepto',
        'concepto_pago',
        'subtotal',
        'total',
        'monto_pagado',
        'metodo_pago',
        'estado',
        'fecha_vencimiento',
        'notas',
        'enviada_at',
        'enlace',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'fecha_vencimiento' => 'date',
        'enviada_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'monto_pagado' => 'decimal:2',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
}
