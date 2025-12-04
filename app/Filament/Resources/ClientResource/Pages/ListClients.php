<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClients extends ListRecords
{
    protected static string $resource = ClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('config_bot')
                ->label('Configuración del Bot')
                ->icon('heroicon-o-cog-6-tooth')
                ->url('https://n8n.srv1137974.hstgr.cloud/workflow/3OwxkPVt7soP2dzJ')
                ->openUrlInNewTab()
                ->color('gray'),
            Actions\Action::make('site_scraper')
                ->label('Site Scraper')
                ->icon('heroicon-o-globe-alt')
                ->url('/admin/site-scraper')
                ->color('success'),
            Actions\CreateAction::make(),
        ];
    }
}
