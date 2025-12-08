<?php

namespace App\Filament\Resources\ClienteEnProcesoResource\Pages;

use App\Filament\Resources\ClienteEnProcesoResource;
use App\Filament\Resources\ClienteEnProcesoResource\Widgets\ClientesEnProcesoCards;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClienteEnProcesos extends ListRecords
{
    protected static string $resource = ClienteEnProcesoResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            ClientesEnProcesoCards::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
