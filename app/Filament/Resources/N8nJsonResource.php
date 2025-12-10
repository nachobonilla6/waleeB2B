<?php

namespace App\Filament\Resources;

use App\Filament\Resources\N8nJsonResource\Pages;
use App\Models\N8nJson;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class N8nJsonResource extends Resource
{
    protected static ?string $model = N8nJson::class;

    protected static ?string $navigationIcon = 'heroicon-o-code-bracket';

    protected static ?string $navigationLabel = 'Workflows JSON';

    protected static ?string $navigationGroup = 'Soporte';

    protected static ?int $navigationSort = 5;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    protected static ?string $modelLabel = 'Workflow JSON';

    protected static ?string $pluralModelLabel = 'Workflows JSON';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Descripción')
                    ->rows(2)
                    ->maxLength(1000),
                Forms\Components\Textarea::make('json')
                    ->label('JSON del workflow')
                    ->rows(20)
                    ->required()
                    ->helperText('Pega aquí el JSON exportado del workflow de n8n.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(50),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListN8nJsons::route('/'),
            'create' => Pages\CreateN8nJson::route('/create'),
            'edit' => Pages\EditN8nJson::route('/{record}/edit'),
        ];
    }
}


