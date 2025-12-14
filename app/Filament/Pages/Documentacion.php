<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Documentacion extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Documentación';
    protected static ?string $title = 'Documentación del Sistema';
    protected static ?string $navigationGroup = 'Herramientas';
    protected static ?int $navigationSort = 100;
    protected static ?string $slug = 'documentacion';

    protected static string $view = 'filament.pages.documentacion';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
