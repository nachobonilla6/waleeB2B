<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tarea extends Model
{
    protected $fillable = [
        'lista_id',
        'texto',
        'fecha_hora',
        'tipo',
        'favorito',
        'estado',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'favorito' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Estados disponibles
     */
    const ESTADO_PENDING = 'pending';
    const ESTADO_COMPLETADO = 'completado';

    public static function estados(): array
    {
        return [
            self::ESTADO_PENDING => 'Pendiente',
            self::ESTADO_COMPLETADO => 'Completado',
        ];
    }

    /**
     * Get the lista that owns the tarea.
     */
    public function lista(): BelongsTo
    {
        return $this->belongsTo(Lista::class);
    }

    /**
     * Scope para filtrar tareas pendientes
     */
    public function scopePending($query)
    {
        return $query->where('estado', self::ESTADO_PENDING);
    }

    /**
     * Scope para filtrar tareas completadas
     */
    public function scopeCompletadas($query)
    {
        return $query->where('estado', self::ESTADO_COMPLETADO);
    }

    /**
     * Scope para filtrar tareas favoritas
     */
    public function scopeFavoritas($query)
    {
        return $query->where('favorito', true);
    }
}

