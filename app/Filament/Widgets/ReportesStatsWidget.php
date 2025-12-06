<?php

namespace App\Filament\Widgets;

use App\Models\Factura;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class ReportesStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();
        
        // Entradas (facturas pagadas)
        $entradasTotales = (float) Factura::where('estado', 'pagada')->sum('total');
        $entradasMensual = (float) Factura::where('estado', 'pagada')
            ->where('fecha_emision', '>=', $startOfMonth)
            ->sum('total');
        $entradasDia = (float) Factura::where('estado', 'pagada')
            ->whereDate('fecha_emision', $today)
            ->sum('total');
        
        // Salidas (facturas pendientes y vencidas)
        $salidasTotales = (float) Factura::whereIn('estado', ['pendiente', 'vencida'])->sum('total');
        $salidasMensual = (float) Factura::whereIn('estado', ['pendiente', 'vencida'])
            ->where('fecha_emision', '>=', $startOfMonth)
            ->sum('total');
        $salidasDia = (float) Factura::whereIn('estado', ['pendiente', 'vencida'])
            ->whereDate('fecha_emision', $today)
            ->sum('total');
        
        return [
            Stat::make('Entradas Totales', Number::currency($entradasTotales, 'CRC'))
                ->description('Total de facturas pagadas')
                ->descriptionIcon('heroicon-m-arrow-down-circle')
                ->color('success'),
                
            Stat::make('Entradas Mensual', Number::currency($entradasMensual, 'CRC'))
                ->description('Entradas de este mes')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
                
            Stat::make('Entradas del Día', Number::currency($entradasDia, 'CRC'))
                ->description('Entradas de hoy')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
                
            Stat::make('Salidas Totales', Number::currency($salidasTotales, 'CRC'))
                ->description('Total de facturas pendientes/vencidas')
                ->descriptionIcon('heroicon-m-arrow-up-circle')
                ->color('warning'),
                
            Stat::make('Salidas Mensual', Number::currency($salidasMensual, 'CRC'))
                ->description('Salidas de este mes')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('warning'),
                
            Stat::make('Salidas del Día', Number::currency($salidasDia, 'CRC'))
                ->description('Salidas de hoy')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),
        ];
    }
}

