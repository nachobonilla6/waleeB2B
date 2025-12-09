<?php

namespace App\Filament\Resources\PublicacionVelaResource\Pages;

use App\Filament\Resources\PublicacionVelaResource;
use Filament\Actions;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPublicacionVelas extends ListRecords
{
    protected static string $resource = PublicacionVelaResource::class;

    protected static string $view = 'filament.resources.publicacion-vela-resource.pages.list-publicacion-velas';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth('4xl')
                ->slideOver()
                ->modalHeading('Crear Publicación Vela')
                ->icon('heroicon-o-plus'),
        ];
    }

    protected function configureCreateAction(CreateAction | \Filament\Tables\Actions\CreateAction $action): void
    {
        $resource = static::getResource();
        
        $action
            ->authorize($resource::canCreate())
            ->model($this->getModel())
            ->modelLabel($this->getModelLabel() ?? static::getResource()::getModelLabel())
            ->form(fn (\Filament\Forms\Form $form): \Filament\Forms\Form => $this->form($form->columns(2)));
        
        // No establecer URL para forzar que use modal
        // if ($resource::hasPage('create')) {
        //     $action->url(fn (): string => $resource::getUrl('create'));
        // }
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
