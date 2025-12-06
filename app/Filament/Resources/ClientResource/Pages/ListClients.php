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
            Actions\Action::make('site_scraper')
                ->label('Site Scraper')
                ->icon('heroicon-o-globe-alt')
                ->url('/admin/site-scraper')
                ->color('success'),
            Actions\CreateAction::make(),
        ];
    }
}
