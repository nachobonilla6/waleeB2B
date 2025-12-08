<?php

namespace App\Filament\Resources\ClientesGoogleEnviadasResource\Pages;

use App\Filament\Resources\ClientesGoogleEnviadasResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewClientesGoogleEnviada extends ViewRecord
{
    protected static string $resource = ClientesGoogleEnviadasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Detalle Propuesta Enviada';
    }
}

