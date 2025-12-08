<?php

namespace App\Filament\Resources\ClientesGoogleCopiaResource\Pages;

use App\Filament\Resources\ClientesGoogleCopiaResource;
use App\Filament\Resources\ClienteEnProcesoResource\Widgets\ClientesEnProcesoCards;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;

class ListClientesGoogleCopias extends ListRecords
{
    protected static string $resource = ClientesGoogleCopiaResource::class;

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

    protected function hasTable(): bool
    {
        return false;
    }

    public function getTitle(): string
    {
        return 'Clientes Google (Copia)';
    }

    public function getHeading(): string
    {
        return 'Clientes Google (Copia)';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

