<?php

namespace App\Filament\Widgets;

use App\Models\Cliente;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class ClienteStatsWidget extends BaseWidget
{
    protected static ?int $sort = 0;
    protected static ?string $pollingInterval = '30s';
    protected static bool $isLazy = true;

    protected function getStats(): array
    {
        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();
        $startOfLastMonth = $today->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $today->copy()->subMonth()->endOfMonth();
        
        // Total de clientes
        $totalClientes = Cliente::count();
        
        // Clientes nuevos este mes
        $clientesEsteMes = Cliente::where('created_at', '>=', $startOfMonth)->count();
        
        // Clientes del mes pasado
        $clientesMesPasado = Cliente::whereBetween('created_at', [
            $startOfLastMonth,
            $endOfLastMonth
        ])->count();
        
        // Calcular cambio porcentual
        $cambio = $clientesMesPasado > 0 
            ? round((($clientesEsteMes - $clientesMesPasado) / $clientesMesPasado) * 100, 1)
            : ($clientesEsteMes > 0 ? 100 : 0);

        // Clientes nuevos hoy
        $clientesHoy = Cliente::whereDate('created_at', $today)->count();

        return [
            Stat::make('Total Clientes', Number::format($totalClientes))
                ->description('Clientes registrados')
                ->descriptionIcon('heroicon-m-users')
                ->color('success')
                ->url(route('filament.admin.resources.clientes.index')),
                
            Stat::make('Nuevos este mes', $clientesEsteMes)
                ->description($cambio >= 0 ? "+{$cambio}% vs mes pasado" : "{$cambio}% vs mes pasado")
                ->descriptionIcon($cambio >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($cambio >= 0 ? 'success' : 'danger')
                ->chart([
                    $clientesMesPasado > 2 ? $clientesMesPasado - 2 : 0,
                    $clientesMesPasado > 1 ? $clientesMesPasado - 1 : 0,
                    $clientesMesPasado,
                    $clientesEsteMes > 1 ? $clientesEsteMes - 1 : 0,
                    $clientesEsteMes,
                ]),
                
            Stat::make('Nuevos hoy', $clientesHoy)
                ->description('Clientes registrados hoy')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('info'),
        ];
    }
}

