<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Schema;

class ClientesStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        try {
            // Clientes extraídos (total de clientes)
            $clientesExtraidos = 0;
            if (Schema::hasTable('clientes_en_proceso')) {
                $clientesExtraidos = Client::count();
            }

            // Propuestas enviadas
            $propuestasEnviadas = 0;
            if (Schema::hasTable('clientes_en_proceso') && Schema::hasColumn('clientes_en_proceso', 'estado')) {
                $propuestasEnviadas = Client::where('estado', 'propuesta_enviada')->count();
            }

            // Propuestas aceptadas (valor simbólico de 3 por ahora)
            $propuestasAceptadas = 3;
        } catch (\Exception $e) {
            $clientesExtraidos = 0;
            $propuestasEnviadas = 0;
            $propuestasAceptadas = 3;
        }

        return [
            Stat::make('Clientes Extraídos', number_format($clientesExtraidos))
                ->description('Total de clientes extraídos')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->icon('heroicon-o-user-group'),
                
            Stat::make('Propuestas Enviadas', number_format($propuestasEnviadas))
                ->description('Propuestas enviadas a clientes')
                ->descriptionIcon('heroicon-m-paper-airplane')
                ->color('info')
                ->icon('heroicon-o-paper-airplane'),
                
            Stat::make('Propuestas Aceptadas', number_format($propuestasAceptadas))
                ->description('Propuestas aceptadas por clientes')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->icon('heroicon-o-check-circle'),
        ];
    }
}
