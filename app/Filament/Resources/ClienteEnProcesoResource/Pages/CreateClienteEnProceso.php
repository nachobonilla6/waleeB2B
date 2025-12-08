<?php

namespace App\Filament\Resources\ClienteEnProcesoResource\Pages;

use App\Filament\Resources\ClienteEnProcesoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateClienteEnProceso extends CreateRecord
{
    protected static string $resource = ClienteEnProcesoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
