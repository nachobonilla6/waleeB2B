<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table = 'servicios';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'tipo',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope para servicios activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para servicios predefinidos
     */
    public function scopePredefinidos($query)
    {
        return $query->where('tipo', 'predefinido');
    }

    /**
     * Scope para servicios personalizados
     */
    public function scopePersonalizados($query)
    {
        return $query->where('tipo', 'personalizado');
    }
}
