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
        'fecha_expiracion',
        'codigo_barras',
        'imagen',
        'activo',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'stock' => 'integer',
        'activo' => 'boolean',
        'fecha_expiracion' => 'date',
    ];

    /**
     * Obtener la URL completa de la imagen
     */
    public function getImagenUrlAttribute()
    {
        if (!$this->imagen) {
            return null;
        }

        // Si ya es una URL completa, retornarla
        if (str_starts_with($this->imagen, 'http://') || str_starts_with($this->imagen, 'https://')) {
            return $this->imagen;
        }

        // Si empieza con storage/, usar asset directamente
        if (str_starts_with($this->imagen, 'storage/')) {
            return asset($this->imagen);
        }

        // Si es una ruta relativa, agregar storage/
        return asset('storage/' . $this->imagen);
    }
}
