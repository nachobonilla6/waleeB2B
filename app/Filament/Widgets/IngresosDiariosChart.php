<?php

namespace App\Filament\Widgets;

use App\Models\Factura;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class IngresosDiariosChart extends ChartWidget
{
    protected static ?string $heading = 'Ingresos Diarios (Últimos 30 días)';
    
    protected static ?int $sort = 10;
    
    protected static ?string $pollingInterval = '30s';
    
    protected static bool $isLazy = true;
    
    protected int | string | array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $today = Carbon::today();
        $startDate = $today->copy()->subDays(29); // Últimos 30 días incluyendo hoy
        
        // Obtener ingresos diarios de los últimos 30 días
        $ingresosDiarios = [];
        $labels = [];
        
        for ($i = 0; $i < 30; $i++) {
            $date = $startDate->copy()->addDays($i);
            $labels[] = $date->format('d/m');
            
            $ingresos = (float) Factura::where('estado', 'pagada')
                ->whereDate('fecha_emision', $date)
                ->sum('total');
            
            $ingresosDiarios[] = $ingresos;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Ingresos (USD)',
                    'data' => $ingresosDiarios,
                    'borderColor' => 'rgb(34, 197, 94)', // Verde
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) { return "$" + value.toLocaleString(); }',
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) { return "$" + context.parsed.y.toLocaleString("en-US", {minimumFractionDigits: 2, maximumFractionDigits: 2}); }',
                    ],
                ],
            ],
        ];
    }
}

