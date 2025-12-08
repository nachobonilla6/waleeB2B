<?php

namespace App\Filament\Resources\PublicacionVelaResource\Pages;

use App\Filament\Resources\PublicacionVelaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPublicacionVelas extends ListRecords
{
    protected static string $resource = PublicacionVelaResource::class;

    protected static string $view = 'filament.resources.publicacion-vela-resource.pages.list-publicacion-velas';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth('3xl')
                ->slideOver()
                ->modalHeading('Crear Publicación Vela'),
        ];
    }

    protected function hasTable(): bool
    {
        return false;
    }

    public function getRecords()
    {
        return $this->getTableQuery()->get();
    }
}
