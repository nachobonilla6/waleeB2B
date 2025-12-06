<?php

namespace App\Filament\Resources\N8nJsonResource\Pages;

use App\Filament\Resources\N8nJsonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditN8nJson extends EditRecord
{
    protected static string $resource = N8nJsonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}


