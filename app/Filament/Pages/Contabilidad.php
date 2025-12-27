<?php

namespace App\Filament\Pages;

use App\Models\Factura;
use Filament\Pages\Page;
use Filament\Actions;
use App\Filament\Pages\Contabilidad\FacturasPage;
use App\Filament\Pages\Contabilidad\GastosPage;
use App\Filament\Pages\Contabilidad\CotizacionesPage;
use App\Filament\Pages\Contabilidad\ReportesPage;

class Contabilidad extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calculator';
    protected static ?string $navigationLabel = 'Contabilidad';
    protected static ?string $title = 'Contabilidad';
    protected static ?string $navigationGroup = 'Contabilidad';
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.contabilidad';

    public function mount(): void
    {
        // Redirigir a la página de Facturas por defecto
        $tab = request()->get('tab', 'facturas');
        
        match($tab) {
            'facturas' => $this->redirect(FacturasPage::getUrl()),
            'gastos' => $this->redirect(GastosPage::getUrl()),
            'cotizaciones' => $this->redirect(CotizacionesPage::getUrl()),
            'reportes' => $this->redirect(ReportesPage::getUrl()),
            default => $this->redirect(FacturasPage::getUrl()),
        };
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('facturas')
                ->label('Facturas')
                ->icon('heroicon-o-banknotes')
                ->color('gray')
                ->url(FacturasPage::getUrl()),
            Actions\Action::make('gastos')
                ->label('Gastos')
                ->icon('heroicon-o-currency-dollar')
                ->color('gray')
                ->url(GastosPage::getUrl()),
            Actions\Action::make('cotizaciones')
                ->label('Cotizaciones')
                ->icon('heroicon-o-document-text')
                ->color('gray')
                ->url(CotizacionesPage::getUrl()),
            Actions\Action::make('reportes')
                ->label('Reportes')
                ->icon('heroicon-o-chart-bar')
                ->color('gray')
                ->url(ReportesPage::getUrl()),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $total = Factura::count();
        return $total > 0 ? (string) $total : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }
}

