<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clientes_en_proceso';

    protected $fillable = [
        'name',
        'foto',
        'email',
        'website',
        'address',
        'telefono_1',
        'horario',
        'telefono_2',
        'feedback',
        'propuesta',
        'proposed_site',
        'propuesta_enviada',
        'estado',
        'webhook_url',
        'page_id',
        'token',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'cliente_id');
    }
}
