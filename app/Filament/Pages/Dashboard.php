<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\IngresosDashboardWidget;
use App\Filament\Widgets\ClientesStatsWidget;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Actions\Action;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $title = 'WALEÉ Dashboard';
    protected static string $view = 'filament.pages.dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            ClientesStatsWidget::class,
            IngresosDashboardWidget::class,
        ];
    }

    /**
     * Sobrescribir para evitar que se muestren widgets descubiertos automáticamente
     * Devolver array vacío para que no se muestren widgets automáticos
     */
    public function getWidgets(): array
    {
        return [];
    }

    /**
     * Sobrescribir para evitar que se muestren widgets descubiertos automáticamente
     * Devolver array vacío para que no se muestren widgets automáticos
     */
    public function getVisibleWidgets(): array
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
