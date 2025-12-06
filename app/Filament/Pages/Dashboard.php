<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\SiteStatsWidget;
use App\Filament\Widgets\IngresosStatsWidget;
use App\Filament\Widgets\IngresosDiariosChart;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $title = 'Dashboard';
    protected static string $view = 'filament.pages.dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            IngresosStatsWidget::class,
            SiteStatsWidget::class,
            IngresosDiariosChart::class,
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
}
