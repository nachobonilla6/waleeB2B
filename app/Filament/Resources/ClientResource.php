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
use Illuminate\Support\Facades\Schema;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Clientes Activos';
    protected static ?string $modelLabel = 'Cliente Activo';
    protected static ?string $pluralModelLabel = 'Clientes Activos';
    protected static ?string $navigationGroup = 'Administración';
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        try {
            if (Schema::hasColumn('clientes_en_proceso', 'estado')) {
                return (string) static::getModel()::where('estado', 'accepted')->count();
            }
            return (string) static::getModel()::count();
        } catch (\Exception $e) {
            return '0';
        }
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Solo mostrar clientes con estado 'accepted'
        if (Schema::hasColumn('clientes_en_proceso', 'estado')) {
            $query->where('estado', 'accepted');
        }

        return $query;
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
                            ->label('Sitio Web')
                            ->url()
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Información Adicional')
                    ->schema([
                        Forms\Components\Select::make('estado')
                            ->label('Estado')
                            ->options([
                                'pending' => 'Pending',
                                'accepted' => 'Activo',
                                'rejected' => 'Rejected',
                            ])
                            ->default('accepted')
                            ->required()
                            ->native(false)
                            ->visible(fn () => Schema::hasColumn('clientes_en_proceso', 'estado')),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('telefono_1')
                    ->label('Teléfono')
                    ->searchable(),
            ])
            ->filters([
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
                    ])
                    ->columns(2),
                Infolists\Components\Section::make('Información Adicional')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Infolists\Components\TextEntry::make('estado')
                            ->label('Estado')
                            ->badge()
                            ->color('success')
                            ->formatStateUsing(fn (string $state): string => 'Activo')
                            ->visible(fn () => Schema::hasColumn('clientes_en_proceso', 'estado')),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Fecha de Registro')
                            ->dateTime('d/m/Y H:i'),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Última Actualización')
                            ->dateTime('d/m/Y H:i'),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make('Notas')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('notes')
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('content')
                                    ->label('')
                                    ->columnSpanFull(),
                                Infolists\Components\TextEntry::make('type')
                                    ->label('Tipo')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'note' => 'gray',
                                        'call' => 'primary',
                                        'meeting' => 'info',
                                        'email' => 'success',
                                        default => 'gray',
                                    })
                                    ->formatStateUsing(fn (string $state): string => match ($state) {
                                        'note' => 'Nota',
                                        'call' => 'Llamada',
                                        'meeting' => 'Reunión',
                                        'email' => 'Email',
                                        default => $state,
                                    }),
                                Infolists\Components\TextEntry::make('user.name')
                                    ->label('Creado por')
                                    ->placeholder('Sistema'),
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Fecha')
                                    ->dateTime('d/m/Y H:i'),
                            ])
                            ->columns(3)
                            ->emptyLabel('No hay notas registradas')
                            ->orderBy('created_at', 'desc'),
                    ])
                    ->collapsible()
                    ->collapsed(false),
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
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'view' => Pages\ViewClient::route('/{record}'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
