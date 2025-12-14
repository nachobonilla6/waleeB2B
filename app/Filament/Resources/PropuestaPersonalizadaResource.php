<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropuestaPersonalizadaResource\Pages;
use App\Filament\Resources\PropuestaPersonalizadaResource\RelationManagers;
use App\Models\PropuestaPersonalizada;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PropuestaPersonalizadaResource extends Resource
{
    protected static ?string $model = PropuestaPersonalizada::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationLabel = 'Propuestas Personalizadas';
    protected static ?string $modelLabel = 'Propuesta Personalizada';
    protected static ?string $pluralModelLabel = 'Propuestas Personalizadas';
    protected static ?string $navigationGroup = 'Herramientas';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('cliente_nombre')
                    ->label('Cliente')
                    ->disabled(),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->disabled(),
                Forms\Components\TextInput::make('subject')
                    ->label('Asunto')
                    ->disabled(),
                Forms\Components\Textarea::make('body')
                    ->label('Mensaje')
                    ->disabled()
                    ->rows(10),
                Forms\Components\Textarea::make('ai_prompt')
                    ->label('Prompt usado')
                    ->disabled()
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cliente_nombre')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->icon('heroicon-o-envelope'),
                Tables\Columns\TextColumn::make('subject')
                    ->label('Asunto')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Enviado por')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('📧 Propuesta Personalizada')
                    ->modalWidth('4xl')
                    ->modalContent(fn (PropuestaPersonalizada $record) => view('filament.resources.propuesta-personalizada-resource.view-modal', ['record' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar'),
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
            'index' => Pages\ListPropuestaPersonalizadas::route('/'),
        ];
    }
}
