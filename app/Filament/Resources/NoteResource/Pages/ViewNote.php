<?php

namespace App\Filament\Resources\NoteResource\Pages;

use App\Filament\Resources\NoteResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewNote extends ViewRecord
{
    protected static string $resource = NoteResource::class;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
        
        $this->authorizeAccess();
        
        // Cargar relaciones antes de mostrar el infolist
        $this->record->loadMissing(['user', 'cliente', 'client']);
        
        if (! $this->hasInfolist()) {
            $this->fillForm();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
