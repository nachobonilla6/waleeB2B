<?php

namespace App\Filament\Resources\SitioResource\Pages;

use App\Filament\Resources\SitioResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;

class EditSitio extends EditRecord
{
    protected static string $resource = SitioResource::class;

    protected string $webhookUrl = 'https://n8n.srv1137974.hstgr.cloud/webhook-test/92c5f4ef-f206-4e3d-a613-5874c7dbc8bd';

    protected function afterSave(): void
    {
        $record = $this->record;
        
        try {
            $response = Http::timeout(30)->post($this->webhookUrl, [
                'id' => $record->id,
                'nombre' => $record->nombre ?? '',
                'enlace' => $record->enlace ?? '',
                'descripcion' => $record->descripcion ?? '',
                'imagen' => $record->imagen ? asset('storage/' . $record->imagen) : '',
                'video_url' => $record->video_url ?? '',
                'en_linea' => $record->en_linea ?? false,
                'tags' => $record->tags->pluck('nombre')->toArray(),
                'updated_at' => $record->updated_at->toIso8601String(),
                'action' => 'updated',
            ]);

            if ($response->successful()) {
                Notification::make()
                    ->title('Datos enviados al webhook')
                    ->success()
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error al enviar al webhook')
                ->body($e->getMessage())
                ->warning()
                ->send();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('deleteImage')
                ->label('Eliminar imagen')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->action(function () {
                    $record = $this->record;
                    
                    // Delete the file from storage
                    if ($record->imagen) {
                        Storage::disk('public')->delete($record->imagen);
                    }
                    
                    // Clear the field
                    $record->imagen = null;
                    $record->save();
                    
                    // Show success notification
                    Notification::make()
                        ->title('Imagen eliminada')
                        ->success()
                        ->send();
                        
                    // Refresh the form
                    $this->fillForm();
                })
                ->visible(fn () => !empty($this->record?->imagen)),
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
