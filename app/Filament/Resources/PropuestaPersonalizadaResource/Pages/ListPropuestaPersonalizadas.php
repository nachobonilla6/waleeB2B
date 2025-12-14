<?php

namespace App\Filament\Resources\PropuestaPersonalizadaResource\Pages;

use App\Filament\Resources\PropuestaPersonalizadaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPropuestaPersonalizadas extends ListRecords
{
    protected static string $resource = PropuestaPersonalizadaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No permitir crear desde aquí, solo ver las enviadas
        ];
    }
}
