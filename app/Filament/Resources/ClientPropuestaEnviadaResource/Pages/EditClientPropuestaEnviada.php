<?php

namespace App\Filament\Resources\ClientPropuestaEnviadaResource\Pages;

use App\Filament\Resources\ClientPropuestaEnviadaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClientPropuestaEnviada extends EditRecord
{
    protected static string $resource = ClientPropuestaEnviadaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSaveButtonLabel(): string
    {
        return 'Guardar cambios';
    }
    
    protected function getSaveChangesButtonLabel(): string
    {
        return 'Guardar cambios';
    }
}







