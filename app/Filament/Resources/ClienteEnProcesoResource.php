<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClienteEnProcesoResource\Pages;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class ClienteEnProcesoResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Clientes Google';
    protected static ?string $modelLabel = 'Cliente Google';
    protected static ?string $pluralModelLabel = 'Clientes Google';
    protected static ?string $navigationGroup = 'Administración';
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        try {
            return static::getEloquentQuery()->count();
        } catch (\Exception $e) {
            return '0';
        }
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Cliente')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Correo Electrónico')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('telefono_1')
                            ->label('Teléfono 1')
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\TextInput::make('telefono_2')
                            ->label('Teléfono 2')
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\Textarea::make('address')
                            ->label('Dirección')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Sitio Web')
                    ->schema([
                        Forms\Components\TextInput::make('website')
                            ->label('Sitio Web Actual')
                            ->formatStateUsing(fn ($state) => str_replace(['http://', 'https://'], '', $state))
                            ->prefix('https://')
                            ->maxLength(255)
                            ->suffixAction(
                                fn ($state) => $state
                                    ? Forms\Components\Actions\Action::make('open')
                                        ->icon('heroicon-o-eye')
                                        ->url(fn () => (str_starts_with($state, 'http') ? $state : 'https://' . $state))
                                        ->openUrlInNewTab()
                                    : null
                            )
                            ->dehydrateStateUsing(function ($state) {
                                if (empty($state)) return null;
                                $state = trim($state);
                                if (str_starts_with($state, 'http://') || str_starts_with($state, 'https://')) {
                                    return $state;
                                }
                                return 'https://' . $state;
                            }),
                        Forms\Components\Select::make('proposed_site')
                            ->label('Sitio Propuesto')
                            ->options(\App\Models\Sitio::all()->pluck('nombre', 'enlace'))
                            ->searchable()
                            ->helperText('Selecciona un sitio propuesto para este cliente'),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Propuesta y Feedback')
                    ->schema([
                        Forms\Components\Textarea::make('propuesta')
                            ->label('Propuesta')
                            ->rows(4)
                            ->columnSpanFull()
                            ->helperText('Propuesta personalizada para este cliente'),
                        Forms\Components\Textarea::make('feedback')
                            ->label('Feedback')
                            ->rows(3)
                            ->columnSpanFull()
                            ->readOnly()
                            ->helperText('Feedback del cliente (solo lectura)'),
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('llenar_ai')
                                ->label('Llenar con AI')
                                ->icon('heroicon-o-sparkles')
                                ->color('primary')
                                ->action(function (Set $set, Get $get, ?Client $record) {
                                    $website = $get('website') ?: $record?->website;

                                    if (! $website) {
                                        Notification::make()
                                            ->title('Falta sitio web')
                                            ->body('Agrega el sitio web antes de usar AI.')
                                            ->warning()
                                            ->send();
                                        return;
                                    }

                                    try {
                                        $response = Http::post(
                                            'https://n8n.srv1137974.hstgr.cloud/webhook-test/f1d17b9f-5def-4ee1-b539-d0cd5ec6be6a',
                                            [
                                                'website' => $website,
                                            ]
                                        );

                                        if ($response->successful()) {
                                            $data = $response->json();
                                            $propuesta = $data['propuesta'] ?? null;
                                            $feedback = $data['feedback'] ?? null;

                                            if ($record) {
                                                $record->update([
                                                    'propuesta' => $propuesta ?? $record->propuesta,
                                                    'feedback' => $feedback ?? $record->feedback,
                                                ]);
                                            }

                                            if ($propuesta !== null) {
                                                $set('propuesta', $propuesta);
                                            }

                                            if ($feedback !== null) {
                                                $set('feedback', $feedback);
                                            }

                                            Notification::make()
                                                ->title('Enviado a AI')
                                                ->body('Propuesta y feedback actualizados desde AI.')
                                                ->success()
                                                ->send();
                                        } else {
                                            Notification::make()
                                                ->title('Error al enviar')
                                                ->body('El webhook respondió con estado ' . $response->status())
                                                ->danger()
                                                ->send();
                                        }
                                    } catch (\Exception $e) {
                                        Notification::make()
                                            ->title('Error al enviar')
                                            ->body($e->getMessage())
                                            ->danger()
                                            ->send();
                                    }
                                }),
                        ])->columnSpanFull()
                          ->visibleOn('edit'),
                        Forms\Components\Toggle::make('propuesta_enviada')
                            ->label('Propuesta Enviada')
                            ->default(false)
                            ->helperText('Marcar cuando se haya enviado la propuesta')
                            ->visible(fn () => Schema::hasColumn('clientes_en_proceso', 'propuesta_enviada')),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->paginationPageOptions([5, 10, 25, 50])
            ->defaultPaginationPageOption(10)
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
                    ->formatStateUsing(function ($state) {
                        if (! $state) {
                            return null;
                        }
                        // Quitar protocolo y www.
                        $clean = preg_replace('/^https?:\\/\\//i', '', $state);
                        $clean = preg_replace('/^www\\./i', '', $clean);
                        return rtrim($clean, '/');
                    })
                    ->limit(40)
                    ->icon('heroicon-o-globe-alt'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('propuesta_enviada')
                    ->label('Propuesta Enviada')
                    ->placeholder('Todos')
                    ->trueLabel('Con propuesta enviada')
                    ->falseLabel('Sin propuesta enviada')
                    ->queries(
                        true: function (Builder $query) {
                            if (Schema::hasColumn('clientes_en_proceso', 'propuesta_enviada')) {
                                return $query->where('propuesta_enviada', true);
                            }
                            return $query;
                        },
                        false: function (Builder $query) {
                            if (Schema::hasColumn('clientes_en_proceso', 'propuesta_enviada')) {
                                return $query->where('propuesta_enviada', false);
                            }
                            return $query;
                        },
                        blank: fn (Builder $query) => $query,
                    )
                    ->visible(fn () => Schema::hasColumn('clientes_en_proceso', 'propuesta_enviada')),
            ])
            ->actions([
                Tables\Actions\Action::make('enviar_propuesta')
                    ->label('Enviar Propuesta')
                    ->icon('heroicon-o-envelope')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Enviar propuesta')
                    ->modalDescription('¿Estás seguro de que deseas enviar la propuesta a este cliente?')
                    ->action(function (Client $record) {
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
                                if (Schema::hasColumn('clientes_en_proceso', 'propuesta_enviada')) {
                                    $record->update(['propuesta_enviada' => true]);
                                }
                                Notification::make()
                                    ->title('Propuesta enviada')
                                    ->body('La propuesta se ha enviado correctamente a ' . $record->email)
                                    ->success()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('Error al enviar')
                                    ->body('El webhook respondió con error: ' . $response->status())
                                    ->danger()
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
                    ->visible(fn (Client $record) => !($record->propuesta_enviada ?? false)),
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-o-eye'),
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil-square'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('enviar_propuestas')
                        ->label('Enviar propuestas seleccionadas')
                        ->icon('heroicon-o-envelope')
                        ->color('success')
                        ->requiresConfirmation()
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
                                        if (Schema::hasColumn('clientes_en_proceso', 'propuesta_enviada')) {
                                            $record->update(['propuesta_enviada' => true]);
                                        }
                                        $enviados++;
                                    } else {
                                        $errores++;
                                    }
                                } catch (\Exception $e) {
                                    $errores++;
                                }
                            }

                            $mensaje = [];
                            if ($enviados > 0) {
                                $mensaje[] = "{$enviados} propuesta(s) enviada(s) exitosamente";
                            }
                            if ($errores > 0) {
                                $mensaje[] = "{$errores} error(es) al enviar";
                            }
                            if ($sinEmail > 0) {
                                $mensaje[] = "{$sinEmail} cliente(s) sin email omitido(s)";
                            }

                            Notification::make()
                                ->title('Proceso completado')
                                ->body(implode('. ', $mensaje))
                                ->success($errores === 0 && $sinEmail === 0)
                                ->warning($errores > 0 || $sinEmail > 0)
                                ->send();
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        try {
            if (!Schema::hasTable('clientes_en_proceso')) {
                return parent::getEloquentQuery()->whereRaw('1 = 0');
            }

            $query = parent::getEloquentQuery()
                ->orderByDesc('created_at')
                ->orderByDesc('id');

            // Solo mostrar clientes que NO tienen propuesta enviada (en proceso)
            if (Schema::hasColumn('clientes_en_proceso', 'propuesta_enviada')) {
                $query->where(function ($q) {
                    $q->whereNull('propuesta_enviada')
                      ->orWhere('propuesta_enviada', false);
                });
            }

            return $query;
        } catch (\Exception $e) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Información del Cliente')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Nombre')
                            ->weight('bold')
                            ->size('lg'),
                        Infolists\Components\TextEntry::make('email')
                            ->label('Correo Electrónico')
                            ->url(fn ($record) => $record->email ? 'mailto:' . $record->email : null)
                            ->openUrlInNewTab()
                            ->icon('heroicon-o-envelope'),
                        Infolists\Components\TextEntry::make('telefono_1')
                            ->label('Teléfono 1')
                            ->url(fn ($record) => $record->telefono_1 ? 'tel:' . $record->telefono_1 : null)
                            ->icon('heroicon-o-phone'),
                        Infolists\Components\TextEntry::make('telefono_2')
                            ->label('Teléfono 2')
                            ->url(fn ($record) => $record->telefono_2 ? 'tel:' . $record->telefono_2 : null)
                            ->icon('heroicon-o-phone'),
                        Infolists\Components\TextEntry::make('address')
                            ->label('Dirección')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make('Sitio Web')
                    ->icon('heroicon-o-globe-alt')
                    ->schema([
                        Infolists\Components\TextEntry::make('website')
                            ->label('Sitio Web Actual')
                            ->url(fn ($record) => $record->website ? (str_starts_with($record->website, 'http') ? $record->website : 'https://' . $record->website) : null)
                            ->openUrlInNewTab()
                            ->icon('heroicon-o-link'),
                        Infolists\Components\TextEntry::make('proposed_site')
                            ->label('Sitio Propuesto')
                            ->url(fn ($record) => $record->proposed_site ? (str_starts_with($record->proposed_site, 'http') ? $record->proposed_site : 'https://' . $record->proposed_site) : null)
                            ->openUrlInNewTab()
                            ->color('success')
                            ->icon('heroicon-o-sparkles'),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make('Propuesta y Feedback')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Infolists\Components\TextEntry::make('propuesta')
                            ->label('Propuesta')
                            ->columnSpanFull()
                            ->visible(fn ($record) => !empty($record->propuesta)),
                        Infolists\Components\TextEntry::make('feedback')
                            ->label('Feedback')
                            ->columnSpanFull()
                            ->visible(fn ($record) => !empty($record->feedback)),
                        Infolists\Components\IconEntry::make('propuesta_enviada')
                            ->label('Propuesta Enviada')
                            ->boolean()
                            ->visible(fn () => Schema::hasColumn('clientes_en_proceso', 'propuesta_enviada')),
                    ]),
                Infolists\Components\Section::make('Información Adicional')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Fecha de Registro')
                            ->dateTime('d/m/Y H:i'),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Última Actualización')
                            ->dateTime('d/m/Y H:i'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClienteEnProcesos::route('/'),
            'create' => Pages\CreateClienteEnProceso::route('/create'),
            'view' => Pages\ViewClienteEnProceso::route('/{record}'),
            'edit' => Pages\EditClienteEnProceso::route('/{record}/edit'),
        ];
    }
}
