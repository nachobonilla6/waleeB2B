<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class N8nJson extends Model
{
    protected $table = 'n8n_jsons';

    protected $fillable = [
        'name',
        'description',
        'json',
    ];
}


