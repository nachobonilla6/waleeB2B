<?php

namespace App\Filament\Resources\ClienteEnProcesoResource\Pages;

use App\Filament\Resources\ClienteEnProcesoResource;
use App\Filament\Resources\ClienteEnProcesoResource\Widgets\ClientesEnProcesoCards;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;

class ListClienteEnProcesos extends ListRecords
{
    protected static string $resource = ClienteEnProcesoResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            ClientesEnProcesoCards::class,
        ];
    }

    public function getMaxContentWidth(): MaxWidth | string | null
    {
        return MaxWidth::Full;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
