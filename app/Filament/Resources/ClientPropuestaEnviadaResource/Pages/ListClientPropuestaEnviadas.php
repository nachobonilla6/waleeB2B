<?php

namespace App\Filament\Resources\ClientPropuestaEnviadaResource\Pages;

use App\Filament\Resources\ClientPropuestaEnviadaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClientPropuestaEnviadas extends ListRecords
{
    protected static string $resource = ClientPropuestaEnviadaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No permitimos crear nuevos desde aquí, solo ver los que ya tienen propuesta enviada
        ];
    }
}







