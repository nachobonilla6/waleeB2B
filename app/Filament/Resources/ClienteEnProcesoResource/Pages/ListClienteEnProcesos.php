<?php

namespace App\Filament\Resources\ClienteEnProcesoResource\Pages;

use App\Filament\Resources\ClienteEnProcesoResource;
use App\Filament\Resources\ClientesGoogleEnviadasResource;
use App\Filament\Resources\ClientesGoogleCopiaResource\Pages\ListClientesGoogleCopias;
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
        $extraerUrl = ListClientesGoogleCopias::getUrl();
        $currentUrl = url()->current();

        // Contar clientes pendientes
        $pendingCount = \App\Models\Client::where('estado', 'pending')->count();
        $listosCount = \App\Models\Client::where('estado', 'listo_para_enviar')->count();
        $propuestasCount = \App\Models\Client::where('estado', 'propuesta_enviada')->count();

        return [
            Actions\Action::make('extraer_nuevos_clientes')
                ->label('Extraer Nuevos Clientes')
                ->url($extraerUrl)
                ->color($currentUrl === $extraerUrl ? 'primary' : 'gray'),
            Actions\Action::make('clientes_google')
                ->label('Clientes Google')
                ->url($clientesGoogleUrl)
                ->color($currentUrl === $clientesGoogleUrl ? 'primary' : 'gray')
                ->badge($pendingCount > 0 ? (string) $pendingCount : null)
                ->badgeColor('warning'),
            Actions\Action::make('listos_para_enviar')
                ->label('Listos para Enviar')
                ->url($listosUrl)
                ->color($currentUrl === $listosUrl ? 'primary' : 'gray')
                ->badge($listosCount > 0 ? (string) $listosCount : null)
                ->badgeColor('info'),
            Actions\Action::make('propuestas_enviadas')
                ->label('Propuestas Enviadas')
                ->url($propuestasUrl)
                ->color($currentUrl === $propuestasUrl ? 'primary' : 'gray')
                ->badge($propuestasCount > 0 ? (string) $propuestasCount : null)
                ->badgeColor('success'),
        ];
    }
}
