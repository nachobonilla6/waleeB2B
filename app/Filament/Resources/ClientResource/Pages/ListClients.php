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
        return [];
    }
    
    public function getTitle(): string
    {
        return '';
    }
    
    protected static string $view = 'filament.resources.client-resource.pages.list-clients';
}
