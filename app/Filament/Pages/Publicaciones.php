<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Post;

class Publicaciones extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Publicaciones (Legacy)';
    protected static ?string $title = 'Publicaciones';
    protected static ?string $navigationGroup = 'Contenido';
    protected static ?int $navigationSort = 20;

    protected static string $view = 'filament.pages.publicaciones';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function getPosts()
    {
        return Post::latest()->get();
    }
}
