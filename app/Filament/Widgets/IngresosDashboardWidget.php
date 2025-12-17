<?php

namespace App\Filament\Widgets;

use App\Models\Factura;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class IngresosDashboardWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();
        $startOfWeek = $today->copy()->startOfWeek(Carbon::MONDAY);
        
        // Ingresos totales (todas las facturas pagadas)
        $ingresosTotales = (float) Factura::where('estado', 'pagada')->sum('total');
        
        // Ingresos del mes actual
        $ingresosMensual = (float) Factura::where('estado', 'pagada')
            ->where('fecha_emision', '>=', $startOfMonth)
            ->sum('total');
        
        // Ingresos de la semana actual (desde lunes)
        $ingresosSemana = (float) Factura::where('estado', 'pagada')
            ->where('fecha_emision', '>=', $startOfWeek)
            ->sum('total');
        
        return [
            Stat::make('Ingresos Totales', Number::currency($ingresosTotales, 'CRC'))
                ->description('Total de facturas pagadas')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
                
            Stat::make('Ingresos Mensuales', Number::currency($ingresosMensual, 'CRC'))
                ->description('Ingresos de este mes')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
                
            Stat::make('Ingresos de la Semana', Number::currency($ingresosSemana, 'CRC'))
                ->description('Ingresos de esta semana')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}

