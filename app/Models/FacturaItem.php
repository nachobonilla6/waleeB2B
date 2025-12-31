<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FacturaItem extends Model
{
    protected $table = 'factura_items';

    protected $fillable = [
        'factura_id',
        'descripcion',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'orden',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'orden' => 'integer',
    ];

    public function factura(): BelongsTo
    {
        return $this->belongsTo(Factura::class);
    }
}
