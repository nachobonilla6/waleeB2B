<?php

namespace App\Filament\Resources\FacturaResource\Pages;

use App\Filament\Resources\FacturaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFactura extends EditRecord
{
    protected static string $resource = FacturaResource::class;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
        
        $this->authorizeAccess();
        
        // Cargar la relación del cliente antes de llenar el formulario
        $this->record->loadMissing('cliente');
        
        $this->fillForm();
        
        $this->previousUrl = url()->previous();
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Asegurar que todos los datos se carguen correctamente
        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
        ];
    }
}
