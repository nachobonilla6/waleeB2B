<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'content',
        'image_url',
        'cliente_id',
    ];

    public function cliente()
    {
        return $this->belongsTo(Client::class, 'cliente_id');
    }
}
