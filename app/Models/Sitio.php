<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Sitio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'en_linea',
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
}
