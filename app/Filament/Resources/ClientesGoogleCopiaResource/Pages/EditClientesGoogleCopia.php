<?php

namespace App\Filament\Resources\ClientesGoogleCopiaResource\Pages;

use App\Filament\Resources\ClientesGoogleCopiaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClientesGoogleCopia extends EditRecord
{
    protected static string $resource = ClientesGoogleCopiaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Editar Cliente Google (Copia)';
    }
}

