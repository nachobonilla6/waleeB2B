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

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Clients';
    protected static ?string $modelLabel = 'Client';
    protected static ?string $pluralModelLabel = 'Clients';
    protected static ?string $navigationGroup = 'Administración';
    protected static ?int $navigationSort = 15;

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
                        Forms\Components\TextInput::make('proposed_site')
                            ->label('Sitio Propuesto')
                            ->maxLength(255),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Información Adicional')
                    ->schema([
                        Forms\Components\Textarea::make('message')
                            ->label('Mensaje')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('propuesta_enviada')
                            ->label('Propuesta Enviada')
                            ->default(false),
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
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('telefono_1')
                    ->label('Teléfono')
                    ->searchable(),
                Tables\Columns\TextColumn::make('website')
                    ->label('Sitio Web')
                    ->url(fn ($record) => $record->website ? (str_starts_with($record->website, 'http') ? $record->website : 'https://' . $record->website) : null)
                    ->openUrlInNewTab()
                    ->formatStateUsing(function ($state) {
                        if (! $state) {
                            return null;
                        }
                        $clean = preg_replace('/^https?:\/\//i', '', $state);
                        $clean = preg_replace('/^www\./i', '', $clean);
                        return rtrim($clean, '/');
                    })
                    ->limit(40),
                Tables\Columns\IconColumn::make('propuesta_enviada')
                    ->label('Propuesta')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('propuesta_enviada')
                    ->label('Propuesta Enviada')
                    ->placeholder('Todos')
                    ->trueLabel('Con propuesta')
                    ->falseLabel('Sin propuesta'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
