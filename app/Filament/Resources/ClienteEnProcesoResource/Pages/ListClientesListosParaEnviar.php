<?php

namespace App\Filament\Resources\ClienteEnProcesoResource\Pages;

use App\Filament\Resources\ClienteEnProcesoResource;
use App\Filament\Resources\ClientesGoogleEnviadasResource;
use App\Filament\Resources\ClientesGoogleCopiaResource;
use App\Models\Client;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;

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
        $extraerUrl = ClientesGoogleCopiaResource::getUrl('index');
        $currentUrl = url()->current();

        // Contar clientes pendientes
        $pendingCount = Client::where('estado', 'pending')->count();

        return [
            Actions\Action::make('extraer_nuevos_clientes')
                ->label('Extraer Nuevos Clientes')
                ->url($extraerUrl)
                ->color($currentUrl === $extraerUrl ? 'primary' : 'gray'),
            Actions\Action::make('clientes_google')
                ->label('Clientes Google')
                ->url($clientesGoogleUrl)
                ->color($currentUrl === $clientesGoogleUrl ? 'primary' : 'gray')
                ->badge($pendingCount > 0 ? (string) $pendingCount : null)
                ->badgeColor('warning'),
            Actions\Action::make('listos_para_enviar')
                ->label('Listos para Enviar')
                ->url($listosUrl)
                ->color($currentUrl === $listosUrl ? 'primary' : 'gray'),
            Actions\Action::make('propuestas_enviadas')
                ->label('Propuestas Enviadas')
                ->url($propuestasUrl)
                ->color($currentUrl === $propuestasUrl ? 'primary' : 'gray'),
        ];
    }

    protected function getTableQuery(): Builder
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
            ]);
    }
}

