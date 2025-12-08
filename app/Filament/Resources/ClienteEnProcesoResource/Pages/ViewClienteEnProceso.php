<?php

namespace App\Filament\Resources\ClienteEnProcesoResource\Pages;

use App\Filament\Resources\ClienteEnProcesoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewClienteEnProceso extends ViewRecord
{
    protected static string $resource = ClienteEnProcesoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
