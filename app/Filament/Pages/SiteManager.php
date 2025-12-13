<?php

namespace App\Filament\Pages;

use App\Models\Sitio;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;

class SiteManager extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationLabel = 'Site Manager';
    protected static ?string $title = 'Site Manager';
    protected static ?string $navigationGroup = 'Herramientas';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.site-manager';

    public function getMaxContentWidth(): MaxWidth | string | null
    {
        return MaxWidth::Full;
    }
    
    public function getSitios()
    {
        return Sitio::with('tags')->get();
    }

    public static function getNavigationBadge(): ?string
    {
        try {
            return (string) Sitio::count();
        } catch (\Exception $e) {
            return '0';
        }
    }
}
