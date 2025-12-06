<?php

namespace App\Filament\Pages\Contabilidad;

use App\Filament\Pages\Entradas;
use App\Filament\Pages\Salidas;
use Filament\Pages\Page;
use Filament\Actions;

class ReportesPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Reportes';
    protected static ?string $title = 'Reportes';
    protected static ?string $navigationGroup = 'Contabilidad';
    protected static ?int $navigationSort = 3;
    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.pages.contabilidad.reportes-page';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('facturas')
                ->label('Facturas')
                ->icon('heroicon-o-banknotes')
                ->color('gray')
                ->url(FacturasPage::getUrl()),
            Actions\Action::make('cotizaciones')
                ->label('Cotizaciones')
                ->icon('heroicon-o-document-text')
                ->color('gray')
                ->url(CotizacionesPage::getUrl()),
            Actions\Action::make('reportes')
                ->label('Reportes')
                ->icon('heroicon-o-chart-bar')
                ->color('success')
                ->url(static::getUrl()),
        ];
    }

    public static function getRouteName(): string
    {
        return 'contabilidad.reportes';
    }

    public static function getRoutes(): \Closure
    {
        return function () {
            \Illuminate\Support\Facades\Route::get('/contabilidad/reportes', static::class)
                ->name(static::getRouteName());
        };
    }
}

