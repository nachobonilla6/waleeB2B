<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\IngresosDashboardWidget;
use App\Filament\Widgets\IngresosUltimos30DiasChart;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Actions\Action;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $title = 'WALEÉ Dashboard';

    /**
     * Widgets visibles en el dashboard - solo estos se mostrarán
     * Esto sobrescribe el descubrimiento automático de widgets
     */
    public function getWidgets(): array
    {
        return [
            IngresosDashboardWidget::class,
            IngresosUltimos30DiasChart::class,
        ];
    }

    /**
     * Sobrescribir para evitar que se muestren widgets descubiertos automáticamente
     * Solo mostrar los widgets definidos explícitamente en getWidgets()
     */
    public function getVisibleWidgets(): array
    {
        return $this->getWidgets();
    }

    /**
     * No mostrar widgets en el header
     */
    protected function getHeaderWidgets(): array
    {
        return [];
    }

    /**
     * Asegurar que no haya widgets en el footer
     */
    public function getFooterWidgets(): array
    {
        return [];
    }

    public function getColumns(): int | string | array
    {
        return [
            'sm' => 1,
            'md' => 1,
            'lg' => 1,
            'xl' => 1,
            '2xl' => 1,
        ];
    }
}
