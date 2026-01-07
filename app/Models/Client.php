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
        'facebook',
        'address',
        'telefono_1',
        'horario',
        'ciudad',
        'direccion',
        'idioma',
        'industria',
        'telefono_2',
        'feedback',
        'propuesta',
        'proposed_site',
        'propuesta_enviada',
        'estado',
        'is_active',
        'webhook_url',
        'webhook_productos',
        'page_id',
        'token',
        'nota',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Accessor para direccion - asegura que sea accesible
     */
    public function getDireccionAttribute($value)
    {
        // Si direccion está vacío pero address tiene valor, usar address
        if (empty($value) && !empty($this->attributes['address'] ?? null)) {
            return $this->attributes['address'];
        }
        return $value;
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'cliente_id');
    }

    public function emails()
    {
        return $this->hasMany(\App\Models\PropuestaPersonalizada::class, 'cliente_id');
    }
}
