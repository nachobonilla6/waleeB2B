<?php

namespace App\Filament\Resources\ClienteEnProcesoResource\Pages;

use App\Filament\Resources\ClienteEnProcesoResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Builder;

class ListClientesListosParaEnviar extends ListRecords
{
    protected static string $resource = ClienteEnProcesoResource::class;

    public function getMaxContentWidth(): MaxWidth | string | null
    {
        return MaxWidth::Full;
    }

    public function getTitle(): string
    {
        return 'Clientes Listos para Enviar';
    }

    public function getHeading(): string
    {
        return 'Clientes Listos para Enviar';
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getTableView(): string
    {
        return <<<'blade'
@include('components.google-clients-nav')
@include('filament::components.table')
blade;
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->where('estado', 'listo_para_enviar');
    }
}

