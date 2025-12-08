<?php

namespace App\Filament\Resources\ClientesGoogleCopiaResource\Pages;

use App\Filament\Resources\ClientesGoogleCopiaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateClientesGoogleCopia extends CreateRecord
{
    protected static string $resource = ClientesGoogleCopiaResource::class;

    public function getTitle(): string
    {
        return 'Crear Cliente Google (Copia)';
    }
}

