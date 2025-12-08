<?php

namespace App\Filament\Resources\ClientesGoogleEnviadasResource\Pages;

use App\Filament\Resources\ClientesGoogleEnviadasResource;
use Filament\Resources\Pages\CreateRecord;

class CreateClientesGoogleEnviada extends CreateRecord
{
    protected static string $resource = ClientesGoogleEnviadasResource::class;

    public function getTitle(): string
    {
        return 'Crear Cliente Google (Enviada)';
    }
}

