<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    protected $table = 'gastos';

    protected $fillable = [
        'nombre',
        'fecha',
        'pagado',
        'total',
        'link',
    ];

    protected $casts = [
        'fecha' => 'date',
        'pagado' => 'boolean',
        'total' => 'decimal:2',
    ];
}

