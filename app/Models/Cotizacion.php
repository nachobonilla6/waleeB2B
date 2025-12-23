<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cotizacion extends Model
{
    protected $fillable = [
        'numero_cotizacion',
        'fecha',
        'idioma',
        'cliente_id',
        'tipo_servicio',
        'plan',
        'monto',
        'vigencia',
        'correo',
        'descripcion',
        'estado',
        'enviada_at',
    ];

    protected $casts = [
        'fecha' => 'date',
        'monto' => 'decimal:2',
        'enviada_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($cotizacion) {
            // Crear cita en el calendario cuando se crea una cotización
            try {
                $cliente = $cotizacion->cliente;
                $clienteNombre = $cliente ? $cliente->nombre_empresa : 'Cliente';
                
                $titulo = "Cotización: {$cotizacion->numero_cotizacion} - {$clienteNombre}";
                $descripcion = "Cotización creada\n";
                $descripcion .= "Número: {$cotizacion->numero_cotizacion}\n";
                $descripcion .= "Cliente: {$clienteNombre}\n";
                $descripcion .= "Monto: $" . number_format($cotizacion->monto, 2) . "\n";
                if ($cotizacion->tipo_servicio) {
                    $descripcion .= "Servicio: {$cotizacion->tipo_servicio}\n";
                }
                if ($cotizacion->plan) {
                    $descripcion .= "Plan: {$cotizacion->plan}\n";
                }
                if ($cotizacion->vigencia) {
                    $descripcion .= "Vigencia: {$cotizacion->vigencia}\n";
                }
                if ($cotizacion->descripcion) {
                    $descripcion .= "Descripción: {$cotizacion->descripcion}";
                }
                
                // Usar fecha de cotización o fecha actual
                $fechaInicio = $cotizacion->fecha 
                    ? \Carbon\Carbon::parse($cotizacion->fecha)->setTime(10, 0)
                    : now()->setTime(10, 0);
                
                \App\Models\Cita::create([
                    'titulo' => $titulo,
                    'descripcion' => $descripcion,
                    'fecha_inicio' => $fechaInicio,
                    'fecha_fin' => $fechaInicio->copy()->addHour(),
                    'cliente_id' => $cotizacion->cliente_id,
                    'estado' => 'programada',
                    'color' => '#3b82f6', // Color azul para cotizaciones
                ]);
            } catch (\Exception $e) {
                \Log::error('Error al crear cita desde cotización: ' . $e->getMessage());
            }
        });
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
}
