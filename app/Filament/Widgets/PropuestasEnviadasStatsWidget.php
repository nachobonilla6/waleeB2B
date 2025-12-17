<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\PropuestaPersonalizada;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PropuestasEnviadasStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();
        
        // Propuestas estándar enviadas este mes (estado propuesta_enviada)
        $propuestasEnviadasMes = Client::where('estado', 'propuesta_enviada')
            ->where('updated_at', '>=', $startOfMonth)
            ->count();
        
        // Propuestas personalizadas enviadas este mes (estado propuesta_personalizada_enviada)
        $propuestasPersonalizadasMes = Client::where('estado', 'propuesta_personalizada_enviada')
            ->where('updated_at', '>=', $startOfMonth)
            ->count();
        
        // Total de propuestas enviadas este mes (estándar + personalizadas)
        $totalPropuestasMes = $propuestasEnviadasMes + $propuestasPersonalizadasMes;
        
        // Propuestas enviadas hoy (estado propuesta_enviada)
        $propuestasEnviadasHoy = Client::where('estado', 'propuesta_enviada')
            ->whereDate('updated_at', $today)
            ->count();
        
        // Propuestas personalizadas enviadas hoy
        $propuestasPersonalizadasHoy = PropuestaPersonalizada::whereDate('created_at', $today)
            ->count();
        
        return [
            Stat::make('Total Propuestas Este Mes', $totalPropuestasMes)
                ->description('Incluye propuestas estándar y personalizadas')
                ->descriptionIcon('heroicon-m-envelope')
                ->color('success'),
                
            Stat::make('Propuestas Enviadas Hoy', $propuestasEnviadasHoy)
                ->description('Propuestas estándar enviadas hoy')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
                
            Stat::make('Propuestas Personalizadas Hoy', $propuestasPersonalizadasHoy)
                ->description('Propuestas personalizadas enviadas hoy')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('warning'),
        ];
    }
}

