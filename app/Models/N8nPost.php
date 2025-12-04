<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class N8nPost extends Model
{
    protected $fillable = [
        'cliente',
        'titulo',
        'texto',
        'imagen',
        'hashtags',
        'footer',
        'status',
        'published_at',
    ];

    protected $casts = [
        'hashtags' => 'array',
        'published_at' => 'datetime',
    ];

    /**
     * Create a post from n8n webhook data
     */
    public static function createFromN8n(array $data): self
    {
        return self::create([
            'titulo' => $data['titulo'] ?? $data['title'] ?? 'Sin título',
            'texto' => $data['texto'] ?? $data['text'] ?? null,
            'imagen' => $data['imagen'] ?? $data['image'] ?? null,
            'hashtags' => $data['hashtags'] ?? $data['hasgtags'] ?? [], // typo fix
            'footer' => $data['footer'] ?? null,
            'status' => 'pending',
        ]);
    }

    /**
     * Get hashtags as string
     */
    public function getHashtagsStringAttribute(): string
    {
        if (empty($this->hashtags)) {
            return '';
        }
        
        return implode(' ', $this->hashtags);
    }
}

