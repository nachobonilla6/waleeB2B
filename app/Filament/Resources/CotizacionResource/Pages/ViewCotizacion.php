<?php

namespace App\Filament\Resources\CotizacionResource\Pages;

use App\Filament\Resources\CotizacionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCotizacion extends ViewRecord
{
    protected static string $resource = CotizacionResource::class;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
        
        $this->authorizeAccess();
        
        // Cargar la relación del cliente antes de mostrar el infolist
        $this->record->loadMissing('cliente');
        
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
