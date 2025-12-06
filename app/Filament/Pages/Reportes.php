<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\EntradasStatsWidget;
use App\Filament\Widgets\SalidasStatsWidget;

class Reportes extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Reportes';
    protected static ?string $title = 'Reportes';
    protected static ?string $navigationGroup = 'Administración';
    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.pages.reportes';

    protected function getHeaderWidgets(): array
    {
        return [
            EntradasStatsWidget::class,
            SalidasStatsWidget::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return [
            'sm' => 1,
            'md' => 2,
            'lg' => 2,
            'xl' => 2,
            '2xl' => 2,
        ];
    }
}

