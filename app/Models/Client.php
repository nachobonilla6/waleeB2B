<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clientes_en_proceso';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'website',
        'address',
        'telefono_1',
        'telefono_2',
        'feedback',
        'propuesta',
        'proposed_site',
        'propuesta_enviada',
        'estado',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
