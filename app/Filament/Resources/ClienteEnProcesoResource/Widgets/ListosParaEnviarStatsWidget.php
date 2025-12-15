<?php

namespace App\Filament\Resources\ClienteEnProcesoResource\Widgets;

use App\Models\Client;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ListosParaEnviarStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Esperando - Clientes Google (estado pending)
        $esperando = Client::where('estado', 'pending')->count();

        // En Cola - Listos para Enviar (estado listo_para_enviar)
        $enCola = Client::where('estado', 'listo_para_enviar')->count();

        // Enviados - Propuestas Enviadas (estado propuesta_enviada)
        $enviados = Client::where('estado', 'propuesta_enviada')->count();

        return [
            Stat::make('Esperando', $esperando)
                ->description('Clientes Google')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->chart([7, 3, 4, 5, 6, 3, 5]),
            Stat::make('En Cola', $enCola)
                ->description('Listos para Enviar')
                ->descriptionIcon('heroicon-m-queue-list')
                ->color('info')
                ->chart([7, 3, 4, 5, 6, 3, 5]),
            Stat::make('Enviados', $enviados)
                ->description('Propuestas Enviadas')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5]),
        ];
    }
}
