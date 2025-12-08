<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientesGoogleEnviadasResource\Pages;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class ClientesGoogleEnviadasResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationLabel = 'Propuestas Enviadas';
    protected static ?string $modelLabel = 'Propuesta Enviada';
    protected static ?string $pluralModelLabel = 'Propuestas Enviadas';
    protected static ?string $navigationGroup = 'Administración';
    protected static ?int $navigationSort = 3;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

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
                                if (empty($state)) {
                                    return null;
                                }
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
                        Forms\Components\Toggle::make('propuesta_enviada')
                            ->label('Propuesta Enviada')
                            ->default(true)
                            ->disabled()
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
                        $clean = preg_replace('/^https?:\\/\\//i', '', $state);
                        $clean = preg_replace('/^www\\./i', '', $clean);
                        return rtrim($clean, '/');
                    })
                    ->limit(40)
                    ->icon('heroicon-o-globe-alt'),
                Tables\Columns\IconColumn::make('propuesta_enviada')
                    ->label('Propuesta Enviada')
                    ->boolean()
                    ->visible(fn () => Schema::hasColumn('clientes_en_proceso', 'propuesta_enviada')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Registro')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-o-eye'),
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil-square'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
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

            if (Schema::hasColumn('clientes_en_proceso', 'propuesta_enviada')) {
                $query->where('propuesta_enviada', true);
            }

            return $query;
        } catch (\Exception $e) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClientesGoogleEnviadas::route('/'),
            'create' => Pages\CreateClientesGoogleEnviada::route('/create'),
            'view' => Pages\ViewClientesGoogleEnviada::route('/{record}'),
            'edit' => Pages\EditClientesGoogleEnviada::route('/{record}/edit'),
        ];
    }
}

