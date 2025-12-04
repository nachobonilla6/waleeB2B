<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $fillable = [
        'nombre',
        'slug'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            $tag->slug = Str::slug($tag->nombre);
        });

        static::updating(function ($tag) {
            $tag->slug = Str::slug($tag->nombre);
        });
    }

    public function sitios()
    {
        return $this->belongsToMany(Sitio::class);
    }
}
