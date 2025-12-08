<?php

namespace App\Filament\Resources\PublicacionVelaResource\Pages;

use App\Filament\Resources\PublicacionVelaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPublicacionVela extends EditRecord
{
    protected static string $resource = PublicacionVelaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
