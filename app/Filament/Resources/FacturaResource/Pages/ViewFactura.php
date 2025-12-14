<?php

namespace App\Filament\Resources\FacturaResource\Pages;

use App\Filament\Resources\FacturaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFactura extends ViewRecord
{
    protected static string $resource = FacturaResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);
        
        // Cargar la relación del cliente antes de mostrar el infolist
        $this->record->loadMissing('cliente');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}

