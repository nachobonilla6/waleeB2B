<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookmarkResource\Pages;
use App\Filament\Resources\BookmarkResource\RelationManagers;
use App\Models\Bookmark;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookmarkResource extends Resource
{
    protected static ?string $model = Bookmark::class;

    protected static ?string $navigationIcon = 'heroicon-o-bookmark';
    protected static ?string $navigationLabel = 'Bookmarks';
    protected static ?string $modelLabel = 'Bookmark';
    protected static ?string $pluralModelLabel = 'Bookmarks';
    protected static ?string $navigationGroup = 'Configuración';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('categoria')
                    ->label('Categoría')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Ej: Desarrollo, Diseño, Herramientas...'),
                Forms\Components\TextInput::make('nombre')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Nombre del bookmark'),
                Forms\Components\TextInput::make('enlace')
                    ->label('Enlace')
                    ->required()
                    ->url()
                    ->maxLength(255)
                    ->placeholder('https://ejemplo.com'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('categoria')
                    ->label('Categoría')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('enlace')
                    ->label('Enlace')
                    ->url(fn ($record) => $record->enlace)
                    ->openUrlInNewTab()
                    ->searchable()
                    ->limit(50)
                    ->copyable()
                    ->default('—'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('categoria')
                    ->label('Categoría')
                    ->options(function () {
                        return \App\Models\Bookmark::query()
                            ->distinct()
                            ->pluck('categoria', 'categoria')
                            ->toArray();
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalWidth('md')
                    ->form([
                        Forms\Components\TextInput::make('categoria')
                            ->label('Categoría')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ej: Desarrollo, Diseño, Herramientas...'),
                        Forms\Components\TextInput::make('nombre')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Nombre del bookmark'),
                        Forms\Components\TextInput::make('enlace')
                            ->label('Enlace')
                            ->required()
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://ejemplo.com'),
                    ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListBookmarks::route('/'),
        ];
    }
}
