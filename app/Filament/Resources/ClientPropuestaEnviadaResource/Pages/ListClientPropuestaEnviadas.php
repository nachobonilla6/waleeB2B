<?php

namespace App\Filament\Resources\ClientPropuestaEnviadaResource\Pages;

use App\Filament\Resources\ClientPropuestaEnviadaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClientPropuestaEnviadas extends ListRecords
{
    protected static string $resource = ClientPropuestaEnviadaResource::class;
    
    protected static string $view = 'filament.resources.client-propuesta-enviada-resource.pages.list-client-propuesta-enviadas';

    protected function getHeaderActions(): array
    {
        return [
            // No permitimos crear nuevos desde aquí, solo ver los que ya tienen propuesta enviada
        ];
    }
}







