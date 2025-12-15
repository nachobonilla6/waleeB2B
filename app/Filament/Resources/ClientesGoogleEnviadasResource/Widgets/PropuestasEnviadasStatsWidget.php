<?php

namespace App\Filament\Resources\ClientesGoogleEnviadasResource\Widgets;

use App\Models\Client;
use App\Models\PropuestaPersonalizada;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class PropuestasEnviadasStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $hoy = Carbon::today();
        $inicioSemana = Carbon::now()->startOfWeek();
        $inicioMes = Carbon::now()->startOfMonth();

        // Propuestas enviadas (Client con estado propuesta_enviada)
        $propuestasHoy = Client::where('estado', 'propuesta_enviada')
            ->whereDate('updated_at', $hoy)
            ->count();

        $propuestasSemana = Client::where('estado', 'propuesta_enviada')
            ->where('updated_at', '>=', $inicioSemana)
            ->count();

        $propuestasMes = Client::where('estado', 'propuesta_enviada')
            ->where('updated_at', '>=', $inicioMes)
            ->count();

        // Propuestas personalizadas enviadas
        $personalizadasHoy = PropuestaPersonalizada::whereDate('created_at', $hoy)->count();
        $personalizadasSemana = PropuestaPersonalizada::where('created_at', '>=', $inicioSemana)->count();
        $personalizadasMes = PropuestaPersonalizada::where('created_at', '>=', $inicioMes)->count();

        // Totales (propuestas + personalizadas)
        $totalHoy = $propuestasHoy + $personalizadasHoy;
        $totalSemana = $propuestasSemana + $personalizadasSemana;
        $totalMes = $propuestasMes + $personalizadasMes;

        return [
            Stat::make('Propuestas Enviadas Hoy', $totalHoy)
                ->description('Propuestas enviadas hoy')
                ->descriptionIcon('heroicon-m-envelope')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5]),
            Stat::make('Propuestas Enviadas Esta Semana', $totalSemana)
                ->description('Desde ' . $inicioSemana->format('d/m/Y'))
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info')
                ->chart([7, 3, 4, 5, 6, 3, 5]),
            Stat::make('Propuestas Enviadas Este Mes', $totalMes)
                ->description('Desde ' . $inicioMes->format('d/m/Y'))
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('primary')
                ->chart([7, 3, 4, 5, 6, 3, 5]),
        ];
    }
}
