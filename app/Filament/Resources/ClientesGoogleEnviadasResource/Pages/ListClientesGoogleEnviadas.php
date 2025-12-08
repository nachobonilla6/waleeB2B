<?php

namespace App\Filament\Resources\ClientesGoogleEnviadasResource\Pages;

use App\Filament\Resources\ClientesGoogleEnviadasResource;
use App\Filament\Resources\ClientesGoogleEnviadasResource\Widgets\ClientesGoogleEnviadasCards;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;

class ListClientesGoogleEnviadas extends ListRecords
{
    protected static string $resource = ClientesGoogleEnviadasResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            ClientesGoogleEnviadasCards::class,
        ];
    }

    public function getMaxContentWidth(): MaxWidth | string | null
    {
        return MaxWidth::Full;
    }

    public function getTitle(): string
    {
        return 'Propuestas Enviadas';
    }

    public function getHeading(): string
    {
        return 'Propuestas Enviadas';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

