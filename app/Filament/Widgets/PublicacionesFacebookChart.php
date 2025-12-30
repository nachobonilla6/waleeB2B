<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Number;

class PublicacionesFacebookChart extends ChartWidget
{
    protected static ?string $heading = 'Publicaciones subidas a FB';
    
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
        $data = [];
        
        // Generar datos para cada día de los últimos 30 días
        for ($i = 0; $i < 30; $i++) {
            $date = $startDate->copy()->addDays($i);
            $labels[] = $date->format('d/m');
            
            // Obtener posts creados ese día
            $postCount = \App\Models\Post::whereDate('created_at', $date)->count();
            
            $data[] = $postCount;
        }
        
        return [
            'datasets' => [
                [
                    'label' => 'Publicaciones',
                    'data' => $data,
                    'backgroundColor' => 'rgba(24, 119, 242, 0.1)', // FB Blue
                    'borderColor' => 'rgb(24, 119, 242)', // FB Blue
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
                        'stepSize' => 1,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
        ];
    }
}
