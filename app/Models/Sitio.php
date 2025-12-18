<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Sitio extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'nombre',
        'descripcion',
        'en_linea',
        'enlace',
        'imagen',
        'video_url'
    ];

    protected $casts = [
        'en_linea' => 'boolean',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Get the cliente that owns the sitio
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
}
