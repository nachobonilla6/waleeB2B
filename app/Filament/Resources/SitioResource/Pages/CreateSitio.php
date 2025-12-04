<?php

namespace App\Filament\Resources\SitioResource\Pages;

use App\Filament\Resources\SitioResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class CreateSitio extends CreateRecord
{
    protected static string $resource = SitioResource::class;

    protected string $webhookUrl = 'https://n8n.srv1137974.hstgr.cloud/webhook-test/92c5f4ef-f206-4e3d-a613-5874c7dbc8bd';

    protected function afterCreate(): void
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
                'created_at' => $record->created_at->toIso8601String(),
                'action' => 'created',
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
}
