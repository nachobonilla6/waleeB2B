<?php

namespace App\Filament\Resources\ClienteEnProcesoResource\Widgets;

use Filament\Widgets\Widget;

class ClientesEnProcesoCards extends Widget
{
    protected static string $view = 'filament.resources.cliente-en-proceso-resource.widgets.clientes-en-proceso-cards';

    protected int|string|array $columnSpan = 'full';
}
