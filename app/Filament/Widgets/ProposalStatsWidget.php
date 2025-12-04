<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class ProposalStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static ?string $pollingInterval = '30s';
    protected static bool $isLazy = true;

    protected function getStats(): array
    {
        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();
        
        // Total clients (as total proposals)
        $totalClients = Client::count();
        
        // Clients created today (as proposals in review)
        $clientsToday = Client::whereDate('created_at', $today)->count();
        
        // Dummy data for approved this month (random between 30-50% of clients this month)
        $clientsThisMonth = Client::where('created_at', '>=', $startOfMonth)->count();
        $approvedThisMonth = (int) round($clientsThisMonth * (rand(30, 50) / 100));
        
        // Calculate percentage change (dummy data - using last month's data)
        $lastMonthClients = Client::whereBetween('created_at', [
            $today->copy()->subMonth()->startOfMonth(),
            $today->copy()->subMonth()->endOfMonth()
        ])->count();
        
        $lastMonthApproved = (int) round($lastMonthClients * (rand(30, 50) / 100));
        $approvalChange = $lastMonthApproved > 0 
            ? round((($approvedThisMonth - $lastMonthApproved) / $lastMonthApproved) * 100, 1)
            : ($approvedThisMonth > 0 ? 100 : 0);

        return [
            Stat::make('Total Propuestas', Number::format($totalClients))
                ->description('Propuestas creadas')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary')
                ->url(route('filament.admin.resources.clients.index')),
                
            Stat::make('Propuestas de hoy', $clientsToday)
                ->description('Nuevas propuestas hoy')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('warning')
                ->url(route('filament.admin.resources.clients.index', ['tableFilters[created_at][isActive]' => 'true', 'tableFilters[created_at][dates][0]' => $today->format('Y-m-d')])),
                
            Stat::make('Aprobadas este mes', $approvedThisMonth)
                ->description($approvalChange >= 0 ? "+{$approvalChange}% vs mes pasado" : "{$approvalChange}% vs mes pasado")
                ->descriptionIcon($approvalChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($approvalChange >= 0 ? 'success' : 'danger')
                ->chart([
                    $lastMonthApproved - 2,
                    $lastMonthApproved - 1,
                    $lastMonthApproved,
                    $approvedThisMonth - 1,
                    $approvedThisMonth,
                    $approvedThisMonth + 1,
                    $approvedThisMonth
                ]),
        ];
    }
}
