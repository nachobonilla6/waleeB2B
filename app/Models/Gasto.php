<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    protected $table = 'gastos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo',
        'fecha',
        'proxima_fecha_pago',
        'pagado',
        'total',
        'link',
    ];

    protected $casts = [
        'fecha' => 'date',
        'proxima_fecha_pago' => 'date',
        'pagado' => 'boolean',
        'total' => 'decimal:2',
    ];
}

