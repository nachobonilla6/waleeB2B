<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Factura;

class Reportes extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Reportes';
    protected static ?string $title = 'Reportes';
    protected static ?string $navigationGroup = 'Contabilidad';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.reportes';

    public static function getNavigationBadge(): ?string
    {
        $totalFacturas = Factura::count();
        return (string) $totalFacturas;
    }

    protected function getHeaderWidgets(): array
    {
        return [];
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

