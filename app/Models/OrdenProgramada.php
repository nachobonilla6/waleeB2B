<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenProgramada extends Model
{
    protected $table = 'ordenes_programadas';

    protected $fillable = [
        'tipo',
        'activo',
        'recurrencia_horas',
        'last_run',
        'configuracion',
        'user_id',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'recurrencia_horas' => 'decimal:2',
        'last_run' => 'datetime',
        'configuracion' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con el usuario
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Scope para obtener órdenes activas que necesitan ejecutarse
     */
    public function scopePendientes($query, $minutos = 30)
    {
        return $query->where('activo', true)
            ->where(function ($q) use ($minutos) {
                $q->whereNull('last_run')
                  ->orWhere('last_run', '<=', now()->subMinutes($minutos));
            });
    }

    /**
     * Scope para filtrar por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }
}
