<?php

namespace App\Filament\Resources\SupportCaseResource\Widgets;

use App\Models\SupportCase;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SupportCaseStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Tickets Abiertos', SupportCase::where('status', 'open')->count())
                ->description('Tickets pendientes de atención')
                ->descriptionIcon('heroicon-o-inbox')
                ->color('success'),
                
            Stat::make('En Progreso', SupportCase::where('status', 'in_progress')->count())
                ->description('Tickets siendo atendidos')
                ->descriptionIcon('heroicon-o-cog')
                ->color('info'),
                
            Stat::make('Tickets Resueltos', SupportCase::where('status', 'resolved')->count())
                ->description('Tickets resueltos')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
                
            Stat::make('Tickets Cerrados', SupportCase::where('status', 'closed')->count())
                ->description('Tickets cerrados')
                ->descriptionIcon('heroicon-o-lock-closed')
                ->color('danger'),
                
            Stat::make('Total de Tickets', SupportCase::count())
                ->description('Tickets en el sistema')
                ->descriptionIcon('heroicon-o-ticket')
                ->color('primary'),
        ];
    }
}