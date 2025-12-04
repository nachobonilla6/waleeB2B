<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class DeployButton extends Component
{
    public bool $showConfirm = false;
    public bool $isDeploying = false;

    public function confirmDeploy()
    {
        $this->showConfirm = true;
    }

    public function cancelDeploy()
    {
        $this->showConfirm = false;
    }

    public function deploy()
    {
        $this->isDeploying = true;
        $this->showConfirm = false;

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

        $this->isDeploying = false;
    }

    public function render()
    {
        return view('livewire.deploy-button');
    }
}

