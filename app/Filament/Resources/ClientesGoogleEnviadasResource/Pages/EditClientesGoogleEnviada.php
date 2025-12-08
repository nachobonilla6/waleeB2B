<?php

namespace App\Filament\Resources\ClientesGoogleEnviadasResource\Pages;

use App\Filament\Resources\ClientesGoogleEnviadasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClientesGoogleEnviada extends EditRecord
{
    protected static string $resource = ClientesGoogleEnviadasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Editar Propuesta Enviada';
    }
}

