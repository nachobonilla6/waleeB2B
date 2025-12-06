<?php

namespace App\Filament\Pages;

use App\Models\Factura;
use Filament\Pages\Page;
use Filament\Actions;
use App\Filament\Resources\FacturaResource;
use App\Filament\Resources\CotizacionResource;

class Contabilidad extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calculator';
    protected static ?string $navigationLabel = 'Contabilidad';
    protected static ?string $title = 'Contabilidad';
    protected static ?string $navigationGroup = 'Contabilidad';
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.contabilidad';

    public ?string $activeTab = 'facturas';

    public function mount(): void
    {
        $this->activeTab = request()->get('tab', 'facturas');
    }

    public function setActiveTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('facturas')
                ->label('Facturas')
                ->icon('heroicon-o-banknotes')
                ->color($this->activeTab === 'facturas' ? 'success' : 'gray')
                ->action(fn () => $this->setActiveTab('facturas')),
            Actions\Action::make('cotizaciones')
                ->label('Cotizaciones')
                ->icon('heroicon-o-document-text')
                ->color($this->activeTab === 'cotizaciones' ? 'success' : 'gray')
                ->action(fn () => $this->setActiveTab('cotizaciones')),
            Actions\Action::make('reportes')
                ->label('Reportes')
                ->icon('heroicon-o-chart-bar')
                ->color($this->activeTab === 'reportes' ? 'success' : 'gray')
                ->action(fn () => $this->setActiveTab('reportes')),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $pendientes = Factura::where('estado', 'pendiente')->count();
        return $pendientes > 0 ? (string) $pendientes : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }
}

