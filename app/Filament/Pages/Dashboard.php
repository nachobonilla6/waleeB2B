<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\EntradasStatsWidget;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $title = 'Dashboard';
    protected static string $view = 'filament.pages.dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            EntradasStatsWidget::class,
        ];
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
