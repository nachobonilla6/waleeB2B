<?php

namespace App\Filament\Resources\ClienteResource\Pages;

use App\Filament\Resources\ClienteResource;
use App\Filament\Resources\VelaSportPostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCliente extends EditRecord
{
    protected static string $resource = ClienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('view')
                ->label('Ver')
                ->icon('heroicon-o-eye')
                ->color('success')
                ->url(fn () => ClienteResource::getUrl('view', ['record' => $this->record])),
            Actions\Action::make('posts')
                ->label('Ver Posts')
                ->icon('heroicon-o-newspaper')
                ->color('info')
                ->url(VelaSportPostResource::getUrl('index')),
            Actions\CreateAction::make()
                ->label('Nuevo Cliente')
                ->icon('heroicon-o-plus')
                ->url(ClienteResource::getUrl('create')),
            Actions\DeleteAction::make()
                ->label('Eliminar'),
            Actions\Action::make('back')
                ->label('Volver')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(ClienteResource::getUrl('index')),
        ];
    }
}
