<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Chat extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Walee Chat';
    protected static ?string $title = 'Chat';
    protected static ?string $navigationGroup = 'Herramientas';
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.chat';
    
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
    
    // Personalizar la URL de navegación para que apunte a /walee
    public static function getNavigationUrl(): string
    {
        return '/walee';
    }
    
    public function getHeading(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return '';
    }

    public static function getNavigationBadge(): ?string
    {
        return '1';
    }
}

