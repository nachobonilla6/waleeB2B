<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NoteResource\Pages;
use App\Models\Note;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class NoteResource extends Resource
{
    protected static ?string $model = Note::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Notas';
    protected static ?string $navigationGroup = 'Administración';
    protected static ?int $navigationSort = 3;
    protected static ?string $modelLabel = 'Nota';
    protected static ?string $pluralModelLabel = 'Notas';
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('cliente_id')
                    ->label('Cliente')
                    ->relationship('cliente', 'nombre_empresa')
                    ->searchable()
                    ->visible(fn ($record) => !$record || !$record->client_id),
                Forms\Components\Select::make('client_id')
                    ->label('Cliente')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->visible(fn ($record) => !$record || !$record->cliente_id),
                Forms\Components\Textarea::make('content')
                    ->label('Contenido')
                    ->required()
                    ->rows(5),
                Forms\Components\Select::make('type')
                    ->label('Tipo')
                    ->options([
                        'note' => 'Nota',
                        'call' => 'Llamada',
                        'meeting' => 'Reunión',
                        'email' => 'Email',
                    ])
                    ->required(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Información de la Nota')
                    ->icon('heroicon-o-document-text')
                    ->collapsible()
                    ->collapsed(false)
                    ->schema([
                        Infolists\Components\TextEntry::make('content')
                            ->label('Contenido')
                            ->columnSpanFull()
                            ->formatStateUsing(fn (?string $state): string => nl2br(e($state ?? ''))),
                        Infolists\Components\TextEntry::make('type')
                            ->label('Tipo')
                            ->badge()
                            ->color(fn (?string $state): string => match($state) {
                                'note' => 'gray',
                                'call' => 'primary',
                                'meeting' => 'info',
                                'email' => 'success',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (?string $state): string => match($state) {
                                'note' => 'Nota',
                                'call' => 'Llamada',
                                'meeting' => 'Reunión',
                                'email' => 'Email',
                                default => $state ?? '-',
                            }),
                        Infolists\Components\TextEntry::make('user.name')
                            ->label('Creado por')
                            ->default('-'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Fecha de Creación')
                            ->dateTime('d/m/Y H:i')
                            ->default('-'),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make('Cliente Asociado')
                    ->icon('heroicon-o-user')
                    ->collapsible()
                    ->collapsed(false)
                    ->schema([
                        Infolists\Components\TextEntry::make('cliente.nombre_empresa')
                            ->label('Cliente')
                            ->url(fn ($record) => $record->cliente_id ? \App\Filament\Resources\ClienteResource::getUrl('view', ['record' => $record->cliente_id]) : null)
                            ->default('-')
                            ->visible(fn ($record) => !empty($record->cliente_id)),
                        Infolists\Components\TextEntry::make('client.name')
                            ->label('Cliente')
                            ->url(fn ($record) => $record->client_id ? \App\Filament\Resources\ClientResource::getUrl('view', ['record' => $record->client_id]) : null)
                            ->default('-')
                            ->visible(fn ($record) => !empty($record->client_id)),
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
            'index' => Pages\ListNotes::route('/'),
            'create' => Pages\CreateNote::route('/create'),
            'view' => Pages\ViewNote::route('/{record}'),
            'edit' => Pages\EditNote::route('/{record}/edit'),
        ];
    }
}
