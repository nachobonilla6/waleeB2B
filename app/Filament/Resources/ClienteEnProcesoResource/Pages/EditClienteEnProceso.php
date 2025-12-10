<?php

namespace App\Filament\Resources\ClienteEnProcesoResource\Pages;

use App\Filament\Resources\ClienteEnProcesoResource;
use App\Models\Client;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class EditClienteEnProceso extends EditRecord
{
    protected static string $resource = ClienteEnProcesoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            Actions\Action::make('enviar_propuesta')
                ->label('Enviar Propuesta por Email')
                ->icon('heroicon-o-envelope')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Enviar propuesta por email')
                ->modalDescription('¿Estás seguro de que deseas enviar la propuesta por email a este cliente?')
                ->action(function () {
                    // Primero guardar los cambios
                    $this->save();
                    
                    $record = $this->record->fresh();
                    
                    if (empty($record->email)) {
                        Notification::make()
                            ->title('Error')
                            ->body('El cliente no tiene un correo electrónico.')
                            ->danger()
                            ->send();
                        return;
                    }

                    try {
                        $videoUrl = '';
                        if ($record->proposed_site) {
                            $sitio = \App\Models\Sitio::where('enlace', $record->proposed_site)->first();
                            $videoUrl = $sitio?->video_url ?? '';
                        }

                        // Usar webhook de producción
                        $response = Http::timeout(30)->post('https://n8n.srv1137974.hstgr.cloud/webhook/f1d17b9f-5def-4ee1-b539-d0cd5ec6be6a', [
                            'name' => $record->name ?? '',
                            'email' => $record->email ?? '',
                            'website' => $record->website ?? '',
                            'proposed_site' => $record->proposed_site ?? '',
                            'video_url' => $videoUrl,
                            'feedback' => $record->feedback ?? '',
                            'propuesta' => $record->propuesta ?? '',
                            'cliente_id' => $record->id ?? null,
                            'cliente_nombre' => $record->name ?? '',
                            'cliente_correo' => $record->email ?? '',
                        ]);

                        if ($response->successful()) {
                            if (Schema::hasColumn('clientes_en_proceso', 'propuesta_enviada')) {
                                $update = ['propuesta_enviada' => true];
                                if (Schema::hasColumn('clientes_en_proceso', 'estado')) {
                                    $update['estado'] = 'propuesta_enviada';
                                }
                                $record->update($update);
                            }
                            Notification::make()
                                ->title('Propuesta enviada')
                                ->body('La propuesta se ha enviado correctamente a ' . $record->email)
                                ->success()
                                ->send();
                            
                            // Redirigir a la lista después de enviar
                            return redirect($this->getResource()::getUrl('index'));
                        } else {
                            $errorBody = $response->body();
                            $errorData = json_decode($errorBody, true);
                            
                            \Log::error('Error al enviar propuesta desde edit al webhook', [
                                'status' => $response->status(),
                                'body' => $errorBody,
                                'cliente_id' => $record->id,
                                'email' => $record->email,
                            ]);
                            
                            $errorMessage = 'Error ' . $response->status();
                            if (isset($errorData['message'])) {
                                $errorMessage .= ': ' . $errorData['message'];
                                if (str_contains($errorData['message'], 'Respond to Webhook')) {
                                    $errorMessage .= ' - El workflow de n8n necesita tener un nodo "Respond to Webhook" configurado.';
                                }
                            } else {
                                $errorMessage .= ': ' . substr($errorBody, 0, 150);
                            }
                            
                            Notification::make()
                                ->title('Error al enviar propuesta')
                                ->body($errorMessage)
                                ->danger()
                                ->persistent()
                                ->send();
                        }
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Error al enviar')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
                ->visible(fn () => !($this->record->propuesta_enviada ?? false)),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
