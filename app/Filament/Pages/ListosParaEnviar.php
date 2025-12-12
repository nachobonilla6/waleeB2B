<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Resources\ClienteEnProcesoResource;

class ListosParaEnviar extends Page
{
    protected static ?string $navigationLabel = 'Listos para Enviar';
    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';
    protected static ?string $navigationGroup = 'Extraer Clientes';
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.listos-para-enviar';

    public function mount(): void
    {
        $this->redirect(ClienteEnProcesoResource::getUrl('listos'));
    }
}
