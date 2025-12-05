<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class FootwearStorePage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    
    protected static ?string $navigationLabel = 'Tienda Footwear';
    
    protected static ?string $title = 'Footwear etc. San Jose';
    
    protected static ?string $navigationGroup = null;
    
    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.pages.footwear-store-page';
    
    protected static bool $shouldRegisterNavigation = false; // No mostrar en navegación, solo accesible por URL
}
