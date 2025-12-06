<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Filament\Notifications\Notification;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $modelLabel = 'Cliente Google';
    protected static ?string $navigationLabel = 'Clientes Google';
    protected static ?string $navigationGroup = 'Herramientas';
    protected static ?int $navigationSort = 1;

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
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->label('Dirección')
                    ->columnSpanFull()
                    ->maxLength(500),
                Forms\Components\TextInput::make('telefono_1')
                    ->label('Teléfono 1')
                    ->tel()
                    ->maxLength(20),
                Forms\Components\TextInput::make('telefono_2')
                    ->label('Teléfono 2')
                    ->tel()
                    ->maxLength(20),
                Forms\Components\TextInput::make('website')
                    ->label('Sitio web')
                    ->formatStateUsing(fn ($state) => str_replace(['http://', 'https://'], '', $state)) // elimina el prefijo al mostrar
                    ->prefix('https://')
                    ->maxLength(255)
                    ->readOnly()
                    ->disabled()
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
                    ->label('Sitio propuesto')
                    ->options(\App\Models\Sitio::all()->pluck('nombre', 'enlace'))
                    ->searchable()
                    ->required(false),
                Forms\Components\Toggle::make('propuesta_enviada')
                    ->label('Enviar propuesta')
                    ->default(false)
                    ->visible(fn () => Schema::hasColumn('clients', 'propuesta_enviada')),
                Forms\Components\Textarea::make('feedback')
                    ->label('Feedback')
                    ->columnSpanFull()
                    ->readOnly()
                    ->dehydrated(fn ($state) => $state),
                Forms\Components\Textarea::make('propuesta')
                    ->label('Propuesta')
                    ->columnSpanFull()
                    ->rows(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->paginationPageOptions([5, 10, 25, 50])
            ->defaultPaginationPageOption(5)
            ->heading('Clientes Google')
            ->description(function () {
                try {
                    return 'Total: ' . \App\Models\Client::count();
                } catch (\Exception $e) {
                    return 'Total: 0';
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        return mb_strlen($state) > 20 ? mb_substr($state, 0, 20) . '…' : $state;
                    }),
                Tables\Columns\TextColumn::make('contacto')
                    ->label('Contacto')
                    ->html()
                    ->state(function ($record) {
                        $telefono1 = e($record->telefono_1 ?? '');
                        $telefono2 = e($record->telefono_2 ?? '');
                        $email = e($record->email ?? '');
                        $address = e($record->address ?? '');
                        
                        $result = '';
                        if ($telefono1) $result .= $telefono1;
                        if ($telefono2) $result .= ($telefono1 ? ' / ' : '') . $telefono2;
                        if ($email) $result .= '<br><span class="text-xs text-gray-500">' . $email . '</span>';
                        if ($address) $result .= '<br><span class="text-xs text-gray-400">' . (mb_strlen($address) > 30 ? mb_substr($address, 0, 30) . '…' : $address) . '</span>';
                        return $result ?: '-';
                    }),
                Tables\Columns\TextColumn::make('website')
                    ->label('Sitio Web / Propuesto')
                    ->html()
                    ->state(function($record) {
                        $webRaw = $record->website ?? '';
                        $propRaw = $record->proposed_site ?? '';
                        
                        $result = '';
                        
                        // Sitio web original
                        if ($webRaw) {
                            $webClean = str_replace(['http://', 'https://'], '', $webRaw);
                            $webLim = mb_strlen($webClean) > 20 ? mb_substr($webClean, 0, 20) . '…' : $webClean;
                            $webFull = str_starts_with($webRaw, 'http') ? $webRaw : 'https://' . $webRaw;
                            $result .= '<a href="' . e($webFull) . '" target="_blank">' . e($webLim) . '</a>';
                        }
                        
                        // Sitio propuesto (mismo estilo que email pero clickeable)
                        if ($propRaw) {
                            $propClean = str_replace(['http://', 'https://'], '', $propRaw);
                            $propLim = mb_strlen($propClean) > 20 ? mb_substr($propClean, 0, 20) . '…' : $propClean;
                            $propFull = str_starts_with($propRaw, 'http') ? $propRaw : 'https://' . $propRaw;
                            $result .= '<br><span class="text-xs text-gray-500"><a href="' . e($propFull) . '" target="_blank" class="hover:underline">' . e($propLim) . '</a></span>';
                        }
                        
                        return $result ?: '';
                    }),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('propuesta_enviada')
                    ->label('Propuesta Enviada')
                    ->placeholder('Todos los clientes')
                    ->trueLabel('Con propuesta enviada')
                    ->falseLabel('Sin propuesta enviada')
                    ->queries(
                        true: function (Builder $query) {
                            if (Schema::hasColumn('clients', 'propuesta_enviada')) {
                                return $query->where('propuesta_enviada', true);
                            }
                            return $query;
                        },
                        false: function (Builder $query) {
                            if (Schema::hasColumn('clients', 'propuesta_enviada')) {
                                return $query->where('propuesta_enviada', false);
                            }
                            return $query;
                        },
                        blank: fn (Builder $query) => $query,
                    )
                    ->visible(fn () => Schema::hasColumn('clients', 'propuesta_enviada')),
            ])
            ->actions([
                \Filament\Tables\Actions\EditAction::make()
                    ->label('Crear propuesta')
                    ->icon('heroicon-m-pencil')
                    ->tooltip('Crear propuesta')
                    ->iconButton(),
                Tables\Actions\Action::make('enviar_gmail')
                    ->label('Enviar a Gmail')
                    ->icon('heroicon-o-envelope')
                    ->tooltip(fn (Client $record) => empty($record->email) ? 'No se puede enviar: el cliente no tiene email' : 'Enviar datos a Gmail vía n8n')
                    ->iconButton()
                    ->requiresConfirmation()
                    ->disabled(fn (Client $record) => empty($record->email))
                    ->action(function (Client $record) {
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
                                // Marcar como propuesta enviada en la base de datos
                                if (Schema::hasColumn('clients', 'propuesta_enviada')) {
                                    $record->update(['propuesta_enviada' => true]);
                                }
                                
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
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('enviar_propuestas')
                        ->label('Enviar propuestas seleccionadas')
                        ->icon('heroicon-o-envelope')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $enviados = 0;
                            $errores = 0;
                            $sinEmail = 0;
                            
                            foreach ($records as $record) {
                                // Solo enviar si tiene email
                                if (empty($record->email)) {
                                    $sinEmail++;
                                    continue;
                                }
                                
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
                                        if (Schema::hasColumn('clients', 'propuesta_enviada')) {
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
                            
                            // Mostrar notificación con el resumen
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
            // Verificar si la tabla existe antes de hacer el query
            if (!Schema::hasTable('clients')) {
                return parent::getEloquentQuery()->whereRaw('1 = 0');
            }
            
            $query = parent::getEloquentQuery();
            
            // Verificar si la columna propuesta_enviada existe antes de usarla
            if (Schema::hasColumn('clients', 'propuesta_enviada')) {
                $query->where(function ($q) {
                    $q->whereNull('propuesta_enviada')
                      ->orWhere('propuesta_enviada', false);
                });
            }
            
            return $query;
        } catch (\Exception $e) {
            // Si hay algún error, retornar un query vacío
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }
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
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'view' => Pages\ViewClient::route('/{record}'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
