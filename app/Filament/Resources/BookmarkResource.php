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

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

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
                Tables\Columns\ImageColumn::make('favicon')
                    ->label('')
                    ->getStateUsing(function ($record) {
                        if (empty($record->enlace)) {
                            return null;
                        }
                        try {
                            $url = parse_url($record->enlace);
                            $domain = $url['host'] ?? null;
                            if ($domain) {
                                // Remover www. si existe
                                $domain = preg_replace('/^www\./', '', $domain);
                                // Usar el servicio de Google para obtener el favicon
                                return "https://www.google.com/s2/favicons?domain={$domain}&sz=32";
                            }
                        } catch (\Exception $e) {
                            return null;
                        }
                        return null;
                    })
                    ->defaultImageUrl('data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><rect width="32" height="32" fill="#ccc"/><text x="50%" y="50%" text-anchor="middle" dy=".3em" font-size="20">🔗</text></svg>'))
                    ->circular(false)
                    ->width(32)
                    ->height(32)
                    ->grow(false),
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
