<?php

namespace App\Filament\Widgets;

use App\Models\Factura;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class SalidasStatsWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected static ?string $pollingInterval = '30s';
    protected static bool $isLazy = true;

    protected function getStats(): array
    {
        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();
        $startOfLastMonth = $today->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $today->copy()->subMonth()->endOfMonth();
        $startOfYear = $today->copy()->startOfYear();
        
        // Salidas = Facturas pendientes y vencidas (dinero que se debe recibir pero aún no se ha recibido)
        $facturasPendientesQuery = Factura::whereIn('estado', ['pendiente', 'vencida']);
        
        // Total de salidas pendientes
        $totalSalidas = (float) $facturasPendientesQuery->sum('total');
        
        // Salidas de este mes
        $salidasEsteMes = (float) Factura::whereIn('estado', ['pendiente', 'vencida'])
            ->where('fecha_emision', '>=', $startOfMonth)
            ->sum('total');
        
        // Salidas del mes pasado
        $salidasMesPasado = (float) Factura::whereIn('estado', ['pendiente', 'vencida'])
            ->whereBetween('fecha_emision', [$startOfLastMonth, $endOfLastMonth])
            ->sum('total');
        
        // Calcular cambio porcentual
        $cambioMensual = $salidasMesPasado > 0 
            ? round((($salidasEsteMes - $salidasMesPasado) / $salidasMesPasado) * 100, 1)
            : ($salidasEsteMes > 0 ? 100 : 0);
        
        // Salidas de este año
        $salidasEsteAno = (float) Factura::whereIn('estado', ['pendiente', 'vencida'])
            ->where('fecha_emision', '>=', $startOfYear)
            ->sum('total');
        
        // Salidas vencidas
        $salidasVencidas = (float) Factura::where('estado', 'vencida')
            ->sum('total');
        
        // Número de facturas pendientes/vencidas
        $totalFacturasPendientes = Factura::whereIn('estado', ['pendiente', 'vencida'])->count();

        return [
            Stat::make('Total Salidas Pendientes', Number::currency($totalSalidas, 'CRC'))
                ->description('Facturas pendientes/vencidas: ' . Number::format($totalFacturasPendientes))
                ->descriptionIcon('heroicon-m-arrow-up-circle')
                ->color('warning')
                ->chart([
                    $salidasMesPasado > 0 ? $salidasMesPasado * 0.8 : 0,
                    $salidasMesPasado > 0 ? $salidasMesPasado * 0.9 : 0,
                    $salidasMesPasado,
                    $salidasEsteMes > 0 ? $salidasEsteMes * 0.9 : 0,
                    $salidasEsteMes,
                ]),
                
            Stat::make('Salidas este mes', Number::currency($salidasEsteMes, 'CRC'))
                ->description($cambioMensual >= 0 ? "+{$cambioMensual}% vs mes pasado" : "{$cambioMensual}% vs mes pasado")
                ->descriptionIcon($cambioMensual >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($cambioMensual >= 0 ? 'warning' : 'danger'),
                
            Stat::make('Salidas este año', Number::currency($salidasEsteAno, 'CRC'))
                ->description('Acumulado del año')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('warning'),
                
            Stat::make('Salidas Vencidas', Number::currency($salidasVencidas, 'CRC'))
                ->description('Facturas vencidas')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}

