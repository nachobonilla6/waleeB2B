<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contrato extends Model
{
    protected $table = 'contratos';

    protected $fillable = [
        'cliente_id',
        'correo',
        'servicios',
        'precio',
        'idioma',
        'pdf_path',
        'enviada_at',
        'estado',
    ];

    protected $casts = [
        'servicios' => 'array',
        'precio' => 'decimal:2',
        'enviada_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
}

