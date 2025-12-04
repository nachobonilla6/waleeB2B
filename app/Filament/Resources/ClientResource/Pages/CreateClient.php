<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class CreateClient extends CreateRecord
{
    protected static string $resource = ClientResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->record;
        
        // Si se marcó como enviada al crear, enviar al webhook
        if ($record->propuesta_enviada) {
            try {
                // Buscar el video_url del sitio propuesto
                $videoUrl = '';
                if ($record->proposed_site) {
                    $sitio = \App\Models\Sitio::where('enlace', $record->proposed_site)->first();
                    $videoUrl = $sitio?->video_url ?? '';
                }
                
                $response = Http::post('https://n8n.srv1137974.hstgr.cloud/webhook-test/92c5f4ef-f206-4e3d-a613-5874c7dbc8bd', [
                    'name' => $record->name ?? '',
                    'email' => $record->email ?? '',
                    'website' => $record->website ?? '',
                    'proposed_site' => $record->proposed_site ?? '',
                    'video_url' => $videoUrl,
                    'feedback' => $record->feedback ?? '',
                    'propuesta' => $record->propuesta ?? '',
                ]);

                if ($response->successful()) {
                    Notification::make()
                        ->title('La propuesta se ha enviado a ' . ($record->email ?? 'el cliente'))
                        ->success()
                        ->send();
                } else {
                    Notification::make()
                        ->title('Error al enviar datos')
                        ->body('El webhook respondió con error: ' . $response->status())
                        ->danger()
                        ->send();
                }
            } catch (\Exception $e) {
                Notification::make()
                    ->title('Error al enviar datos')
                    ->body($e->getMessage())
                    ->danger()
                    ->send();
            }
        }
    }
}
