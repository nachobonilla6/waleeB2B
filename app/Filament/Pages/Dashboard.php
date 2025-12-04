<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use App\Filament\Widgets\ClienteStatsWidget;
use App\Filament\Widgets\SiteStatsWidget;
use App\Filament\Widgets\ProposalStatsWidget;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $title = 'Dashboard';
    protected static string $view = 'filament.pages.dashboard';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('deploy')
                ->label('🚀 Deploy')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('🚀 Deploy a Producción')
                ->modalDescription('¿Estás seguro de que quieres hacer deploy? Esto ejecutará git pull en el servidor.')
                ->modalSubmitActionLabel('Sí, hacer deploy')
                ->action(function () {
                    try {
                        $response = Http::timeout(30)->post('https://n8n.srv1137974.hstgr.cloud/webhook-test/1ec6c667-1b0d-46c9-ad95-8140cc041bba', [
                            'command' => 'cd /home/u655097049/domains/websolutions.work && git pull origin main',
                            'timestamp' => now()->toIso8601String(),
                            'triggered_by' => auth()->user()->name ?? 'Admin',
                        ]);

                        if ($response->successful()) {
                            Notification::make()
                                ->title('✅ Deploy iniciado')
                                ->body('El comando git pull se ha enviado al servidor.')
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('⚠️ Error en deploy')
                                ->body('El servidor respondió con error: ' . $response->status())
                                ->warning()
                                ->send();
                        }
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('❌ Error de conexión')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ClienteStatsWidget::class,
            ProposalStatsWidget::class,
            SiteStatsWidget::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return [
            'sm' => 1,
            'md' => 2,
            'lg' => 3,
            'xl' => 3,
            '2xl' => 3,
        ];
    }
}
