<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Factura extends Model
{
    protected $table = 'facturas';

    protected $fillable = [
        'cliente_id',
        'correo',
        'numero_factura',
        'serie',
        'fecha_emision',
        'concepto',
        'concepto_pago',
        'subtotal',
        'total',
        'monto_pagado',
        'metodo_pago',
        'estado',
        'fecha_vencimiento',
        'notas',
        'enviada_at',
        'enlace',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'fecha_vencimiento' => 'date',
        'enviada_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'monto_pagado' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($factura) {
            // Crear cita en el calendario cuando se crea una factura
            try {
                $cliente = $factura->cliente;
                $clienteNombre = $cliente ? $cliente->nombre_empresa : 'Cliente';
                
                $titulo = "Factura: {$factura->numero_factura} - {$clienteNombre}";
                $descripcion = "Factura creada\n";
                $descripcion .= "Número: {$factura->numero_factura}\n";
                $descripcion .= "Cliente: {$clienteNombre}\n";
                $descripcion .= "Total: $" . number_format($factura->total, 2) . "\n";
                $descripcion .= "Estado: " . ucfirst($factura->estado) . "\n";
                if ($factura->fecha_vencimiento) {
                    $descripcion .= "Vencimiento: " . $factura->fecha_vencimiento->format('d/m/Y') . "\n";
                }
                if ($factura->notas) {
                    $descripcion .= "Notas: {$factura->notas}";
                }
                
                // Usar fecha de emisión o fecha actual
                $fechaInicio = $factura->fecha_emision 
                    ? \Carbon\Carbon::parse($factura->fecha_emision)->setTime(9, 0)
                    : now()->setTime(9, 0);
                
                \App\Models\Cita::create([
                    'titulo' => $titulo,
                    'descripcion' => $descripcion,
                    'fecha_inicio' => $fechaInicio,
                    'fecha_fin' => $fechaInicio->copy()->addHour(),
                    'cliente_id' => $factura->cliente_id,
                    'estado' => 'programada',
                    'color' => '#ef4444', // Color rojo para facturas
                ]);
            } catch (\Exception $e) {
                \Log::error('Error al crear cita desde factura: ' . $e->getMessage());
            }
        });
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
}
