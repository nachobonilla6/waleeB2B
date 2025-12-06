<?php

namespace App\Filament\Resources\CotizacionResource\Pages;

use App\Filament\Resources\CotizacionResource;
use App\Filament\Resources\ClienteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCotizacions extends ListRecords
{
    protected static string $resource = CotizacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nueva Cotización')
                ->icon('heroicon-o-plus'),
            Actions\Action::make('clientes')
                ->label('Clientes')
                ->icon('heroicon-o-users')
                ->color('primary')
                ->url(ClienteResource::getUrl('index')),
        ];
    }
}
