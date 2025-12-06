<?php

namespace App\Filament\Pages;

use App\Filament\Resources\ClientResource;
use App\Filament\Resources\ClientPropuestaEnviadaResource;
use Filament\Pages\Page;

class SiteScraper extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationLabel = 'Site Scraper';
    protected static ?string $title = 'Site Scraper';
    protected static ?string $navigationGroup = 'Herramientas';
    protected static ?int $navigationSort = 0;

    protected static string $view = 'filament.pages.site-scraper';
}
