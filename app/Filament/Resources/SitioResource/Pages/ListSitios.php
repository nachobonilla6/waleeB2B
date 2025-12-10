<?php

namespace App\Filament\Resources\SitioResource\Pages;

use App\Filament\Resources\SitioResource;
use App\Models\Sitio;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSitios extends ListRecords
{
    protected static string $resource = SitioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Subir Nuevo Sitio')
                ->icon('heroicon-o-plus-circle')
                ->modalWidth('4xl')
                ->modalHeading('Subir Nuevo Sitio')
                ->form(SitioResource::form(\Filament\Forms\Form::make()))
                ->mutateFormDataUsing(function (array $data): array {
                    return $data;
                })
                ->using(function (array $data) {
                    // Extract tags before creating
                    $tags = $data['tags'] ?? [];
                    unset($data['tags']);
                    
                    $record = Sitio::create($data);
                    
                    // Handle tags relationship
                    if (!empty($tags)) {
                        $record->tags()->sync($tags);
                    }
                    
                    // Reload to get tags
                    $record->load('tags');
                    
                    // Send to webhook
                    try {
                        $webhookUrl = 'https://n8n.srv1137974.hstgr.cloud/webhook-test/92c5f4ef-f206-4e3d-a613-5874c7dbc8bd';
                        \Illuminate\Support\Facades\Http::timeout(30)->post($webhookUrl, [
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
                    } catch (\Exception $e) {
                        \Filament\Notifications\Notification::make()
                            ->title('Sitio creado')
                            ->body('El sitio se creó pero hubo un error al enviar al webhook')
                            ->warning()
                            ->send();
                    }
                    
                    return $record;
                })
                ->successNotification(
                    \Filament\Notifications\Notification::make()
                        ->success()
                        ->title('Sitio creado')
                        ->body('El sitio se ha creado exitosamente.')
                ),
        ];
    }
    
    protected function getTableQuery(): ?\Illuminate\Database\Eloquent\Builder
    {
        return parent::getTableQuery()?->latest();
    }
    
    protected function getTableRecordsPerPageSelectOptions(): array 
    {
        return [5 => 5, 10, 25, 50];
    }
    
    public function getDefaultTableRecordsPerPageSelectOption(): int 
    {
        return 5;
    }
}
