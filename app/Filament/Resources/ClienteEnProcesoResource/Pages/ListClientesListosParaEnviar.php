<?php

namespace App\Filament\Resources\ClienteEnProcesoResource\Pages;

use App\Filament\Resources\ClienteEnProcesoResource;
use App\Filament\Resources\ClienteEnProcesoResource\Widgets\ClientesEnProcesoCards;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Builder;

class ListClientesListosParaEnviar extends ListRecords
{
    protected static string $resource = ClienteEnProcesoResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            ClientesEnProcesoCards::class,
        ];
    }

    public function getTitle(): string
    {
        return 'Clientes Listos para Enviar';
    }

    public function getHeading(): string
    {
        return 'Clientes Listos para Enviar';
    }

    public function getMaxContentWidth(): MaxWidth | string | null
    {
        return MaxWidth::Full;
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->where('estado', 'listo_para_enviar');
    }
}

