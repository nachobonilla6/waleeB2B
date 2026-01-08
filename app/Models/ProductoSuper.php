<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoSuper extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'categoria',
        'stock',
        'cantidad',
        'fecha_expiracion',
        'fecha_entrada',
        'fecha_limite_venta',
        'fecha_salida',
        'codigo_barras',
        'imagen',
        'foto_qr',
        'activo',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'stock' => 'integer',
        'cantidad' => 'integer',
        'activo' => 'boolean',
        'fecha_expiracion' => 'date',
        'fecha_entrada' => 'date',
        'fecha_limite_venta' => 'date',
        'fecha_salida' => 'date',
    ];

    /**
     * Obtener la URL completa de la imagen
     */
    public function getImagenUrlAttribute()
    {
        if (!$this->imagen || empty(trim($this->imagen))) {
            return null;
        }

        $imagen = trim($this->imagen);

        // Si ya es una URL completa, retornarla
        if (str_starts_with($imagen, 'http://') || str_starts_with($imagen, 'https://')) {
            return $imagen;
        }

        // Si empieza con storage/, removerlo porque asset() lo agregará
        if (str_starts_with($imagen, 'storage/')) {
            $imagen = substr($imagen, 8); // Remover "storage/"
        }

        // Construir URL usando asset con storage/
        return asset('storage/' . $imagen);
    }
}
