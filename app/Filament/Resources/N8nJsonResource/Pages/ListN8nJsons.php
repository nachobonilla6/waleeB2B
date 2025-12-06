<?php

namespace App\Filament\Resources\N8nJsonResource\Pages;

use App\Filament\Resources\N8nJsonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListN8nJsons extends ListRecords
{
    protected static string $resource = N8nJsonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nuevo JSON'),
        ];
    }
}


