<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacturaPaquete extends Model
{
    protected $table = 'factura_paquetes';

    protected $fillable = [
        'nombre',
        'descripcion',
        'categoria',
        'precio',
        'activo',
        'items_incluidos',
        'orden',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'activo' => 'boolean',
        'items_incluidos' => 'array',
        'orden' => 'integer',
    ];
}
