<?php

namespace App\Filament\Widgets;

use App\Models\Factura;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class EntradasStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static ?string $pollingInterval = '30s';
    protected static bool $isLazy = true;

    protected function getStats(): array
    {
        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();
        $startOfLastMonth = $today->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $today->copy()->subMonth()->endOfMonth();
        $startOfYear = $today->copy()->startOfYear();
        
        // Entradas = Facturas pagadas
        $facturasPagadasQuery = Factura::where('estado', 'pagada');
        
        // Total de entradas
        $totalEntradas = (float) $facturasPagadasQuery->sum('total');
        
        // Entradas de este mes
        $entradasEsteMes = (float) Factura::where('estado', 'pagada')
            ->where('fecha_emision', '>=', $startOfMonth)
            ->sum('total');
        
        // Entradas del mes pasado
        $entradasMesPasado = (float) Factura::where('estado', 'pagada')
            ->whereBetween('fecha_emision', [$startOfLastMonth, $endOfLastMonth])
            ->sum('total');
        
        // Calcular cambio porcentual
        $cambioMensual = $entradasMesPasado > 0 
            ? round((($entradasEsteMes - $entradasMesPasado) / $entradasMesPasado) * 100, 1)
            : ($entradasEsteMes > 0 ? 100 : 0);
        
        // Entradas de este año
        $entradasEsteAno = (float) Factura::where('estado', 'pagada')
            ->where('fecha_emision', '>=', $startOfYear)
            ->sum('total');
        
        // Entradas de hoy
        $entradasHoy = (float) Factura::where('estado', 'pagada')
            ->whereDate('fecha_emision', $today)
            ->sum('total');
        
        // Número de facturas pagadas
        $totalFacturasPagadas = Factura::where('estado', 'pagada')->count();

        return [
            Stat::make('Total Entradas', Number::currency($totalEntradas, 'CRC'))
                ->description('Facturas pagadas: ' . Number::format($totalFacturasPagadas))
                ->descriptionIcon('heroicon-m-arrow-down-circle')
                ->color('success')
                ->url(\App\Filament\Pages\Entradas::getUrl())
                ->chart([
                    $entradasMesPasado > 0 ? $entradasMesPasado * 0.8 : 0,
                    $entradasMesPasado > 0 ? $entradasMesPasado * 0.9 : 0,
                    $entradasMesPasado,
                    $entradasEsteMes > 0 ? $entradasEsteMes * 0.9 : 0,
                    $entradasEsteMes,
                ]),
                
            Stat::make('Entradas este mes', Number::currency($entradasEsteMes, 'CRC'))
                ->description($cambioMensual >= 0 ? "+{$cambioMensual}% vs mes pasado" : "{$cambioMensual}% vs mes pasado")
                ->descriptionIcon($cambioMensual >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($cambioMensual >= 0 ? 'success' : 'danger'),
                
            Stat::make('Entradas este año', Number::currency($entradasEsteAno, 'CRC'))
                ->description('Acumulado del año')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
                
            Stat::make('Entradas hoy', Number::currency($entradasHoy, 'CRC'))
                ->description('Ingresos del día')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}

