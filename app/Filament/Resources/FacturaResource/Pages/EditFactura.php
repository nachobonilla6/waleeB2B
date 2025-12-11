<?php

namespace App\Filament\Resources\FacturaResource\Pages;

use App\Filament\Resources\FacturaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFactura extends EditRecord
{
    protected static string $resource = FacturaResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Asegurar que todos los datos se carguen correctamente, incluyendo relaciones
        if ($this->record) {
            $this->record->loadMissing('cliente');
            
            // Asegurar que todos los campos estén presentes con los valores del registro
            $data = array_merge($data, [
                'cliente_id' => $this->record->cliente_id,
                'correo' => $this->record->correo,
                'numero_factura' => $this->record->numero_factura,
                'fecha_emision' => $this->record->fecha_emision,
                'concepto' => $this->record->concepto,
                'subtotal' => $this->record->subtotal,
                'total' => $this->record->total,
                'metodo_pago' => $this->record->metodo_pago,
                'estado' => $this->record->estado,
                'fecha_vencimiento' => $this->record->fecha_vencimiento,
                'notas' => $this->record->notas,
            ]);
        }
        
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
