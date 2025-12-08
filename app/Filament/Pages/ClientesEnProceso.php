<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ClientesEnProceso extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Clientes en Proceso';
    protected static ?string $title = 'Clientes en Proceso';
    protected static ?string $navigationGroup = 'Herramientas';
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.clientes-en-proceso';
}
