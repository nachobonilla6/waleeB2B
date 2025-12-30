<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublicidadEvento extends Model
{
    protected $table = 'publicidad_eventos';

    protected $fillable = [
        'titulo',
        'descripcion',
        'texto', // Texto de la publicación (contenido principal)
        'notas',
        'fecha_inicio',
        'fecha_fin',
        'cliente_id',
        'tipo_publicidad', // post, historia, reel, anuncio, etc.
        'plataforma', // facebook, instagram, tiktok, etc.
        'estado', // programado, publicado, cancelado
        'color',
        'recurrencia',
        'recurrencia_fin',
        'recurrencia_dias',
        'url_post',
        'imagen_url',
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'recurrencia_dias' => 'array',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
}
