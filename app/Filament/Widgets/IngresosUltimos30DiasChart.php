<?php

namespace App\Filament\Widgets;

use App\Models\Factura;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Number;

class IngresosUltimos30DiasChart extends ChartWidget
{
    protected static ?string $heading = 'Ingresos de los Últimos 30 Días';
    
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?string $maxHeight = '300px';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $today = Carbon::today();
        $startDate = $today->copy()->subDays(29); // Últimos 30 días incluyendo hoy
        
        $labels = [];
        $ingresos = [];
        
        // Generar datos para cada día de los últimos 30 días
        for ($i = 0; $i < 30; $i++) {
            $date = $startDate->copy()->addDays($i);
            $labels[] = $date->format('d/m');
            
            // Obtener ingresos del día (facturas pagadas)
            $ingresoDia = (float) Factura::where('estado', 'pagada')
                ->whereDate('fecha_emision', $date)
                ->sum('total');
            
            $ingresos[] = $ingresoDia;
        }
        
        return [
            'datasets' => [
                [
                    'label' => 'Ingresos (CRC)',
                    'data' => $ingresos,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgb(59, 130, 246)',
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
                        'callback' => 'function(value) { return "₡" + value.toLocaleString("es-CR"); }',
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) { return "Ingresos: ₡" + context.parsed.y.toLocaleString("es-CR"); }',
                    ],
                ],
            ],
        ];
    }
}
