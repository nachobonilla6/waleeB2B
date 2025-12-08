<?php

namespace App\Filament\Resources\ClientesGoogleCopiaResource\Pages;

use App\Filament\Resources\ClientesGoogleCopiaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewClientesGoogleCopia extends ViewRecord
{
    protected static string $resource = ClientesGoogleCopiaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Detalle Cliente Google (Copia)';
    }
}

