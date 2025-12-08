<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PublicacionVelaResource\Pages;
use App\Filament\Resources\PublicacionVelaResource\RelationManagers;
use App\Models\PublicacionVela;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PublicacionVelaResource extends Resource
{
    protected static ?string $model = PublicacionVela::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Publicaciones Vela';
    protected static ?string $modelLabel = 'Publicación Vela';
    protected static ?string $pluralModelLabel = 'Publicaciones Vela';
    protected static ?string $navigationGroup = 'Administración';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Publicación')
                    ->schema([
                        Forms\Components\FileUpload::make('foto')
                            ->label('Foto')
                            ->image()
                            ->directory('publicaciones-vela')
                            ->imageEditor()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('texto')
                            ->label('Texto')
                            ->required()
                            ->rows(3)
                            ->maxLength(500)
                            ->helperText('Texto de la publicación (aproximadamente 25 palabras)')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('hashtags')
                            ->label('Hashtags')
                            ->placeholder('#hashtag1 #hashtag2 #hashtag3')
                            ->helperText('Separar hashtags con espacios')
                            ->columnSpanFull(),
                        Forms\Components\DateTimePicker::make('fecha_publicacion')
                            ->label('Fecha de Publicación')
                            ->default(now())
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto')
                    ->circular()
                    ->size(60),
                Tables\Columns\TextColumn::make('texto')
                    ->label('Texto')
                    ->limit(50)
                    ->wrap(),
                Tables\Columns\TextColumn::make('hashtags')
                    ->label('Hashtags')
                    ->limit(30),
                Tables\Columns\TextColumn::make('fecha_publicacion')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('fecha_publicacion', 'desc');
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
            'index' => Pages\ListPublicacionVelas::route('/'),
            'create' => Pages\CreatePublicacionVela::route('/create'),
            'edit' => Pages\EditPublicacionVela::route('/{record}/edit'),
        ];
    }
}
