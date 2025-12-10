<?php

namespace App\Filament\Resources\ClienteEnProcesoResource\Pages;

use App\Filament\Resources\ClienteEnProcesoResource;
use App\Filament\Resources\ClientesGoogleEnviadasResource;
use App\Filament\Resources\ClientesGoogleCopiaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;

class ListClienteEnProcesos extends ListRecords
{
    protected static string $resource = ClienteEnProcesoResource::class;

    public function getMaxContentWidth(): MaxWidth | string | null
    {
        return MaxWidth::Full;
    }

    public function getTitle(): string
    {
        return 'Clientes Google';
    }

    public function getHeading(): string
    {
        return 'Clientes Google';
    }

    protected function getHeaderActions(): array
    {
        $clientesGoogleUrl = ClienteEnProcesoResource::getUrl('index');
        $listosUrl = ClienteEnProcesoResource::getUrl('listos');
        $propuestasUrl = ClientesGoogleEnviadasResource::getUrl('index');
        $extraerUrl = ClientesGoogleCopiaResource::getUrl('index');
        $currentUrl = url()->current();

        return [
            Actions\Action::make('clientes_google')
                ->label('Clientes Google')
                ->url($clientesGoogleUrl)
                ->color($currentUrl === $clientesGoogleUrl ? 'primary' : 'gray'),
            Actions\Action::make('listos_para_enviar')
                ->label('Listos para Enviar')
                ->url($listosUrl)
                ->color($currentUrl === $listosUrl ? 'primary' : 'gray'),
            Actions\Action::make('propuestas_enviadas')
                ->label('Propuestas Enviadas')
                ->url($propuestasUrl)
                ->color($currentUrl === $propuestasUrl ? 'primary' : 'gray'),
            Actions\Action::make('extraer_nuevos_clientes')
                ->label('Extraer Nuevos Clientes')
                ->url($extraerUrl)
                ->color($currentUrl === $extraerUrl ? 'primary' : 'gray'),
        ];
    }
}
