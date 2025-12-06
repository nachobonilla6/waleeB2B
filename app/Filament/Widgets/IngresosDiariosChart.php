<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class IngresosDiariosChart extends ChartWidget
{
    protected static ?string $heading = 'Ingresos Diarios (Últimos 30 días)';
    
    protected static ?int $sort = 10;
    
    protected static ?string $pollingInterval = '30s';
    
    protected static bool $isLazy = true;
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?string $maxHeight = '200px';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $today = Carbon::today();
        $startDate = $today->copy()->subDays(29); // Últimos 30 días incluyendo hoy
        
        // Generar ingresos ficticios para los últimos 30 días
        $ingresosDiarios = [];
        $labels = [];
        
        // Base de ingresos diarios con variación realista
        $baseIngreso = 500; // Base mínima
        $variacionMaxima = 2000; // Variación máxima
        
        for ($i = 0; $i < 30; $i++) {
            $date = $startDate->copy()->addDays($i);
            $labels[] = $date->format('d/m');
            
            // Generar ingresos ficticios con variación
            // Más ingresos en días laborables, menos en fines de semana
            $dayOfWeek = $date->dayOfWeek; // 0 = domingo, 6 = sábado
            $isWeekend = $dayOfWeek == 0 || $dayOfWeek == 6;
            
            // Base ajustada según día de la semana
            $adjustedBase = $isWeekend ? $baseIngreso * 0.3 : $baseIngreso;
            
            // Variación aleatoria pero con tendencia
            $variacion = rand(0, $variacionMaxima);
            
            // Añadir algunos picos aleatorios (días con ingresos altos)
            $pico = rand(1, 10) === 1 ? rand(3000, 5000) : 0;
            
            $ingresos = round($adjustedBase + $variacion + $pico, 2);
            
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

