<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Chat extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Chat';
    protected static ?string $title = 'Chat';
    protected static ?string $navigationGroup = 'Herramientas';
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.chat';
    
    public function getHeading(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return '';
    }
}

