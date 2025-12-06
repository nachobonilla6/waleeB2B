<?php

namespace App\Filament\Resources\BookmarkResource\Pages;

use App\Filament\Resources\BookmarkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookmarks extends ListRecords
{
    protected static string $resource = BookmarkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth('md')
                ->form([
                    \Filament\Forms\Components\TextInput::make('categoria')
                        ->label('Categoría')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Ej: Desarrollo, Diseño, Herramientas...'),
                    \Filament\Forms\Components\TextInput::make('nombre')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Nombre del bookmark'),
                    \Filament\Forms\Components\TextInput::make('enlace')
                        ->label('Enlace')
                        ->required()
                        ->url()
                        ->maxLength(255)
                        ->placeholder('https://ejemplo.com'),
                ]),
        ];
    }
}
