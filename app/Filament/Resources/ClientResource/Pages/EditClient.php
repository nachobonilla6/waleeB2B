<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class EditClient extends EditRecord
{
    protected static string $resource = ClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSaveButtonLabel(): string
    {
        return 'Enviar propuesta';
    }
    protected function getSaveChangesButtonLabel(): string
    {
        return 'Enviar propuesta';
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $wasEnviado = $record->propuesta_enviada;
        $updated = parent::handleRecordUpdate($record, $data);
        $nowEnviado = $updated->refresh()->propuesta_enviada;
        
        // Si se marcó como enviada, enviar al webhook
        if (($wasEnviado === false || $wasEnviado === null) && $nowEnviado) {
            try {
                $response = Http::post('https://n8n.srv1137974.hstgr.cloud/webhook/92c5f4ef-f206-4e3d-a613-5874c7dbc8bd', [
                    'name' => $updated->name ?? '',
                    'email' => $updated->email ?? '',
                    'website' => $updated->website ?? '',
                    'proposed_site' => $updated->proposed_site ?? '',
                    'feedback' => $updated->feedback ?? '',
                    'propuesta' => $updated->propuesta ?? '',
                ]);

                if ($response->successful()) {
                    Notification::make()
                        ->title('La propuesta se ha enviado a ' . ($updated->email ?? 'el cliente'))
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
        
        return $updated;
    }
}
