<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class DeployButton extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public function deployAction(): Action
    {
        return Action::make('deploy')
            ->label('Deploy')
            ->icon('heroicon-o-arrow-up-tray')
            ->color('success')
            ->size('sm')
            ->requiresConfirmation()
            ->modalIcon('heroicon-o-arrow-up-tray')
            ->modalHeading('Deploy a Producción')
            ->modalDescription('¿Estás seguro de que quieres hacer deploy? Esto ejecutará git pull en el servidor de producción.')
            ->modalSubmitActionLabel('Sí, hacer deploy')
            ->action(function () {
                try {
                    $response = Http::timeout(30)->post('https://n8n.srv1137974.hstgr.cloud/webhook/1ec6c667-1b0d-46c9-ad95-8140cc041bba', [
                        'command' => 'cd /home/u655097049/domains/websolutions.work && git pull origin main',
                        'timestamp' => now()->toIso8601String(),
                        'triggered_by' => auth()->user()->name ?? 'Admin',
                    ]);

                    if ($response->successful()) {
                        Notification::make()
                            ->title('Deploy iniciado')
                            ->body('El comando git pull se ha enviado al servidor.')
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Error en deploy')
                            ->body('El servidor respondió con error: ' . $response->status())
                            ->warning()
                            ->send();
                    }
                } catch (\Exception $e) {
                    Notification::make()
                        ->title('Error de conexión')
                        ->body($e->getMessage())
                        ->danger()
                        ->send();
                }
            });
    }

    public function render()
    {
        return view('livewire.deploy-button');
    }
}
