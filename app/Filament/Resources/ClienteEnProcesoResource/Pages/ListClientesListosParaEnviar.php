<?php

namespace App\Filament\Resources\ClienteEnProcesoResource\Pages;

use App\Filament\Resources\ClienteEnProcesoResource;
use App\Filament\Resources\ClientesGoogleEnviadasResource;
use App\Models\Client;
use App\Models\WorkflowRun;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ListClientesListosParaEnviar extends ListRecords
{
    protected static string $resource = ClienteEnProcesoResource::class;

    public function getMaxContentWidth(): MaxWidth | string | null
    {
        return MaxWidth::Full;
    }

    public function getTitle(): string
    {
        return 'Clientes Listos para Enviar';
    }

    public function getHeading(): string
    {
        return 'Clientes Listos para Enviar';
    }

    protected function getHeaderActions(): array
    {
        $clientesGoogleUrl = ClienteEnProcesoResource::getUrl('index');
        $listosUrl = ClienteEnProcesoResource::getUrl('listos');
        $propuestasUrl = ClientesGoogleEnviadasResource::getUrl('index');
        $siteScraperUrl = url('/admin/list-clientes-google-copias');
        $currentUrl = url()->current();

        // Contar clientes pendientes
        $pendingCount = Client::where('estado', 'pending')->count();
        $listosCount = Client::where('estado', 'listo_para_enviar')->count();
        $propuestasCount = Client::where('estado', 'propuesta_enviada')->count();

        return [
            Actions\Action::make('start_search')
                ->label('Iniciar Búsqueda')
                ->icon('heroicon-o-magnifying-glass')
                ->color('gray')
                ->form([
                    Forms\Components\TextInput::make('nombre_lugar')
                        ->label('Lugar')
                        ->placeholder('Ej: Heredia, San José, etc.')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('industria')
                        ->label('Tipo de Negocio')
                        ->options([
                            'tienda_ropa' => '👕 Tienda de Ropa',
                            'pizzeria' => '🍕 Pizzería',
                            'restaurante' => '🍽️ Restaurante',
                            'cafeteria' => '☕ Cafetería',
                            'farmacia' => '💊 Farmacia',
                            'supermercado' => '🛒 Supermercado',
                            'peluqueria' => '✂️ Peluquería / Salón de Belleza',
                            'gimnasio' => '💪 Gimnasio',
                            'veterinaria' => '🐾 Veterinaria',
                            'taller_mecanico' => '🔧 Taller Mecánico',
                            'otro' => '📝 Otro',
                        ])
                        ->required()
                        ->native(false),
                ])
                ->action(function (array $data) {
                    try {
                        $jobId = Str::uuid();
                        $webhookUrl = 'https://n8n.srv1137974.hstgr.cloud/webhook/0c01d9a1-788c-44d2-9c1b-9457901d0a3c';

                        // Crear el registro del workflow
                        $workflowRun = WorkflowRun::create([
                            'job_id' => $jobId,
                            'status' => 'pending',
                            'progress' => 0,
                            'step' => 'En cola',
                            'workflow_name' => 'Búsqueda: ' . ($data['nombre_lugar'] ?? 'Sin nombre'),
                            'data' => [
                                'nombre_lugar' => $data['nombre_lugar'],
                                'industria' => $data['industria'],
                            ],
                        ]);

                        // Preparar payload para n8n
                        $payload = [
                            'job_id' => $jobId,
                            'progress_url' => url('/api/n8n/progress'),
                            'nombre_lugar' => $data['nombre_lugar'],
                            'industria' => $data['industria'],
                        ];

                        // Llamar al webhook de n8n
                        $response = Http::timeout(120)->post($webhookUrl, $payload);

                        if ($response->successful()) {
                            $workflowRun->update([
                                'status' => 'running',
                                'step' => 'Iniciado - Buscando lugares',
                                'started_at' => now(),
                            ]);

                            Notification::make()
                                ->title('Búsqueda iniciada')
                                ->body('La búsqueda se ha iniciado correctamente. ID: ' . substr($jobId, 0, 8))
                                ->success()
                                ->send();
                        } else {
                            $workflowRun->update([
                                'status' => 'failed',
                                'step' => 'Error al iniciar búsqueda',
                                'error_message' => 'Error al iniciar workflow: ' . $response->status(),
                                'completed_at' => null,
                            ]);

                            Notification::make()
                                ->title('Error al iniciar búsqueda')
                                ->body('El webhook respondió con error: ' . $response->status())
                                ->danger()
                                ->send();
                        }
                    } catch (\Exception $e) {
                        if (isset($workflowRun)) {
                            $workflowRun->update([
                                'status' => 'failed',
                                'step' => 'Error al iniciar',
                                'error_message' => $e->getMessage(),
                                'completed_at' => null,
                            ]);
                        }

                        Notification::make()
                            ->title('Error')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            Actions\Action::make('site_scraper')
                ->label('Site Scraper')
                ->icon('heroicon-o-magnifying-glass-plus')
                ->url($siteScraperUrl)
                ->color($currentUrl === $siteScraperUrl ? 'primary' : 'gray'),
            Actions\Action::make('clientes_google')
                ->label('Clientes Google')
                ->url($clientesGoogleUrl)
                ->color($currentUrl === $clientesGoogleUrl ? 'primary' : 'gray')
                ->badge($pendingCount > 0 ? (string) $pendingCount : null)
                ->badgeColor('warning'),
            Actions\Action::make('listos_para_enviar')
                ->label('Listos para Enviar')
                ->url($listosUrl)
                ->color($currentUrl === $listosUrl ? 'primary' : 'gray')
                ->badge($listosCount > 0 ? (string) $listosCount : null)
                ->badgeColor('info'),
            Actions\Action::make('propuestas_enviadas')
                ->label('Propuestas Enviadas')
                ->url($propuestasUrl)
                ->color($currentUrl === $propuestasUrl ? 'primary' : 'gray')
                ->badge($propuestasCount > 0 ? (string) $propuestasCount : null)
                ->badgeColor('success'),
        ];
    }

    protected function getTableQuery(): ?Builder
    {
        // Usar el modelo directamente para no heredar el filtro de "pending" del recurso principal
        return Client::query()
            ->where('estado', 'listo_para_enviar');
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('website')
                    ->label('Sitio Web')
                    ->url(fn ($record) => $record->website ? (str_starts_with($record->website, 'http') ? $record->website : 'https://' . $record->website) : null)
                    ->openUrlInNewTab()
                    ->limit(40),
                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'accepted' => 'success',
                        'rejected' => 'danger',
                        'listo_para_enviar' => 'info',
                        'propuesta_enviada' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                        'listo_para_enviar' => 'Listo para enviar',
                        'propuesta_enviada' => 'Propuesta enviada',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->icon('heroicon-o-envelope')
                    ->color('success')
                    ->url(fn($record) => $record->email ? 'mailto:' . $record->email : null)
                    ->openUrlInNewTab(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-o-eye'),
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil-square'),
                Tables\Actions\Action::make('enviar_propuesta')
                    ->label('Enviar Propuesta')
                    ->icon('heroicon-o-envelope')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Enviar propuesta por email')
                    ->modalDescription('¿Estás seguro de que deseas enviar la propuesta por email a este cliente?')
                    ->action(function ($record) {
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
                            $response = \Http::timeout(30)->post('https://n8n.srv1137974.hstgr.cloud/webhook/f1d17b9f-5def-4ee1-b539-d0cd5ec6be6a', [
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
                                $record->update(['propuesta_enviada' => true, 'estado' => 'propuesta_enviada']);
                                Notification::make()
                                    ->title('Propuesta enviada')
                                    ->body('La propuesta se ha enviado correctamente a ' . $record->email)
                                    ->success()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('Error al enviar propuesta')
                                    ->body($response->body())
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
                    ->visible(fn ($record) => ($record->estado ?? null) === 'listo_para_enviar'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('enviar_masivo')
                        ->label('Enviar Propuestas Seleccionadas')
                        ->icon('heroicon-o-envelope')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Enviar propuestas masivamente')
                        ->modalDescription(fn ($records) => '¿Estás seguro de que deseas enviar ' . $records->count() . ' propuesta(s) por email?')
                        ->action(function ($records) {
                            $enviados = 0;
                            $errores = 0;
                            $sinEmail = 0;

                            foreach ($records as $record) {
                                if (empty($record->email)) {
                                    $sinEmail++;
                                    continue;
                                }

                                try {
                                    $videoUrl = '';
                                    if ($record->proposed_site) {
                                        $sitio = \App\Models\Sitio::where('enlace', $record->proposed_site)->first();
                                        $videoUrl = $sitio?->video_url ?? '';
                                    }
                                    
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
                                        $record->update(['propuesta_enviada' => true, 'estado' => 'propuesta_enviada']);
                                        $enviados++;
                                    } else {
                                        $errores++;
                                    }
                                } catch (\Exception $e) {
                                    $errores++;
                                }
                            }

                            $mensaje = "Enviados: {$enviados}";
                            if ($errores > 0) {
                                $mensaje .= " | Errores: {$errores}";
                            }
                            if ($sinEmail > 0) {
                                $mensaje .= " | Sin email: {$sinEmail}";
                            }

                            Notification::make()
                                ->title($enviados > 0 ? 'Propuestas enviadas' : 'Error al enviar')
                                ->body($mensaje)
                                ->success($enviados > 0 && $errores === 0)
                                ->warning($enviados > 0 && $errores > 0)
                                ->danger($enviados === 0)
                                ->send();
                        }),
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Eliminar Seleccionados')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Eliminar clientes seleccionados')
                        ->modalDescription(fn ($records) => '¿Estás seguro de que deseas eliminar ' . $records->count() . ' cliente(s)? Esta acción no se puede deshacer.')
                        ->action(function ($records) {
                            $count = $records->count();
                            $records->each->delete();
                            
                            Notification::make()
                                ->title('Clientes eliminados')
                                ->body("Se eliminaron {$count} cliente(s) correctamente.")
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }
}

