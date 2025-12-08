<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicacionVela extends Model
{
    protected $table = 'publicacion_velas';

    protected $fillable = [
        'foto',
        'texto',
        'hashtags',
        'fecha_publicacion',
    ];

    protected $casts = [
        'fecha_publicacion' => 'datetime',
    ];
}
