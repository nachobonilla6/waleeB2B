<?php

namespace App\Filament\Resources\FacturaResource\Pages;

use App\Filament\Resources\FacturaResource;
use App\Filament\Resources\CotizacionResource;
use App\Filament\Pages\Contabilidad;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;
use Filament\View\PanelsRenderHook;
use Filament\Support\Facades\FilamentView;

class ListFacturas extends ListRecords
{
    protected static string $resource = FacturaResource::class;

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

    public function getMaxContentWidth(): MaxWidth | string | null
    {
        return MaxWidth::Full;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('facturas')
                ->label('Facturas')
                ->icon('heroicon-o-banknotes')
                ->color('success')
                ->url(static::getResource()::getUrl('index')),
            Actions\Action::make('cotizaciones')
                ->label('Cotizaciones')
                ->icon('heroicon-o-document-text')
                ->color('gray')
                ->url(CotizacionResource::getUrl('index')),
            Actions\Action::make('reportes')
                ->label('Reportes')
                ->icon('heroicon-o-chart-bar')
                ->color('gray')
                ->url(Contabilidad::getUrl() . '?tab=reportes'),
        ];
    }
}
