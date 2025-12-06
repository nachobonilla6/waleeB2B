<?php

namespace App\Filament\Widgets;

use App\Models\Factura;
use App\Filament\Resources\FacturaResource;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class IngresosStatsWidget extends BaseWidget
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
        $startOfLastYear = $today->copy()->subYear()->startOfYear();
        $endOfLastYear = $today->copy()->subYear()->endOfYear();
        
        // Facturas pagadas (estado = 'pagada')
        $facturasPagadasQuery = Factura::where('estado', 'pagada');
        
        // Total de ingresos (todas las facturas pagadas)
        $totalIngresos = (float) $facturasPagadasQuery->sum('total');
        
        // Ingresos de este mes
        $ingresosEsteMes = (float) Factura::where('estado', 'pagada')
            ->where('fecha_emision', '>=', $startOfMonth)
            ->sum('total');
        
        // Ingresos del mes pasado
        $ingresosMesPasado = (float) Factura::where('estado', 'pagada')
            ->whereBetween('fecha_emision', [$startOfLastMonth, $endOfLastMonth])
            ->sum('total');
        
        // Calcular cambio porcentual
        $cambioMensual = $ingresosMesPasado > 0 
            ? round((($ingresosEsteMes - $ingresosMesPasado) / $ingresosMesPasado) * 100, 1)
            : ($ingresosEsteMes > 0 ? 100 : 0);
        
        // Ingresos de este año
        $ingresosEsteAno = (float) Factura::where('estado', 'pagada')
            ->where('fecha_emision', '>=', $startOfYear)
            ->sum('total');
        
        // Ingresos del año pasado
        $ingresosAnoPasado = (float) Factura::where('estado', 'pagada')
            ->whereBetween('fecha_emision', [$startOfLastYear, $endOfLastYear])
            ->sum('total');
        
        // Calcular cambio porcentual anual
        $cambioAnual = $ingresosAnoPasado > 0 
            ? round((($ingresosEsteAno - $ingresosAnoPasado) / $ingresosAnoPasado) * 100, 1)
            : ($ingresosEsteAno > 0 ? 100 : 0);
        
        // Ingresos de hoy
        $ingresosHoy = (float) Factura::where('estado', 'pagada')
            ->whereDate('fecha_emision', $today)
            ->sum('total');
        
        // Número de facturas pagadas
        $totalFacturasPagadas = Factura::where('estado', 'pagada')->count();
        
        // Facturas pagadas este mes
        $facturasEsteMes = Factura::where('estado', 'pagada')
            ->where('fecha_emision', '>=', $startOfMonth)
            ->count();
        
        // Facturas pagadas hoy
        $facturasHoy = Factura::where('estado', 'pagada')
            ->whereDate('fecha_emision', $today)
            ->count();

        return [
            Stat::make('Total Ingresos', Number::currency($totalIngresos, 'CRC'))
                ->description('Facturas pagadas: ' . Number::format($totalFacturasPagadas))
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success')
                ->url(FacturaResource::getUrl('index')),
                
            Stat::make('Ingresos este mes', Number::currency($ingresosEsteMes, 'CRC'))
                ->description($cambioMensual >= 0 ? "+{$cambioMensual}% vs mes pasado" : "{$cambioMensual}% vs mes pasado")
                ->descriptionIcon($cambioMensual >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($cambioMensual >= 0 ? 'success' : 'danger')
                ->chart([
                    $ingresosMesPasado > 0 ? $ingresosMesPasado * 0.8 : 0,
                    $ingresosMesPasado > 0 ? $ingresosMesPasado * 0.9 : 0,
                    $ingresosMesPasado,
                    $ingresosEsteMes > 0 ? $ingresosEsteMes * 0.9 : 0,
                    $ingresosEsteMes,
                ]),
                
            Stat::make('Ingresos este año', Number::currency($ingresosEsteAno, 'CRC'))
                ->description($cambioAnual >= 0 ? "+{$cambioAnual}% vs año pasado" : "{$cambioAnual}% vs año pasado")
                ->descriptionIcon($cambioAnual >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($cambioAnual >= 0 ? 'success' : 'warning'),
                
            Stat::make('Ingresos hoy', Number::currency($ingresosHoy, 'CRC'))
                ->description('Facturas pagadas hoy: ' . $facturasHoy)
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('info'),
        ];
    }
}

