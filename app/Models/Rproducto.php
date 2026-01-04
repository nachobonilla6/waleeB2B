<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rproducto extends Model
{
    protected $table = 'rproductos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
        'tipo',
        'fotos',
        'cliente_id',
    ];
    
    public function cliente()
    {
        return $this->belongsTo(Client::class);
    }

    protected $casts = [
        'fotos' => 'array',
    ];

    /**
     * Obtener las URLs completas de las fotos
     */
    public function getFotosUrlsAttribute()
    {
        if (!$this->fotos) {
            return [];
        }

        return array_map(function($foto) {
            return asset('storage/' . $foto);
        }, $this->fotos);
    }
}
