<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewClient extends ViewRecord
{
    protected static string $resource = ClientResource::class;

    /**
     * Botones de acción (solo diseño, sin funcionalidad por ahora).
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('enviar_factura')
                ->label('Enviar Factura')
                ->icon('heroicon-o-document-text')
                ->color('gray')
                ->disabled()
                ->tooltip('Próximamente'),
            Actions\Action::make('enviar_cotizacion')
                ->label('Enviar Cotización')
                ->icon('heroicon-o-currency-dollar')
                ->color('gray')
                ->disabled()
                ->tooltip('Próximamente'),
            Actions\Action::make('redactar_email')
                ->label('Redactar Email')
                ->icon('heroicon-o-envelope')
                ->color('gray')
                ->disabled()
                ->tooltip('Próximamente'),
            Actions\Action::make('agregar_nota')
                ->label('Agregar Nota')
                ->icon('heroicon-o-pencil-square')
                ->color('gray')
                ->disabled()
                ->tooltip('Próximamente'),
        ];
    }
}
