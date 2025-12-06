<?php

namespace App\Filament\Pages\Contabilidad;

use App\Filament\Pages\Entradas;
use App\Filament\Pages\Salidas;
use App\Filament\Widgets\ReportesStatsWidget;
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
    
    protected static ?string $slug = 'contabilidad/reportes';

    protected function getHeaderWidgets(): array
    {
        return [
            ReportesStatsWidget::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return [
            'sm' => 1,
            'md' => 2,
            'lg' => 3,
            'xl' => 3,
            '2xl' => 3,
        ];
    }

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
}

