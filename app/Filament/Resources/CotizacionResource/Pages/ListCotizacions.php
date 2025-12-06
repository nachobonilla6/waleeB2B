<?php

namespace App\Filament\Resources\CotizacionResource\Pages;

use App\Filament\Resources\CotizacionResource;
use App\Filament\Resources\ClienteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\View\PanelsRenderHook;
use Filament\Support\Facades\FilamentView;

class ListCotizacions extends ListRecords
{
    protected static string $resource = CotizacionResource::class;

    public function mount(): void
    {
        parent::mount();
        
        // Si se accede con parámetro embed, ocultar sidebar con CSS
        if (request()->has('embed')) {
            FilamentView::registerRenderHook(
                PanelsRenderHook::HEAD_END,
                fn () => view('filament.hooks.hide-sidebar-css')
            );
        }
    }

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
