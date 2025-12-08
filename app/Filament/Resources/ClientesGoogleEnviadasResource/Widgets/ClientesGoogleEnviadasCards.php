<?php

namespace App\Filament\Resources\ClientesGoogleEnviadasResource\Widgets;

use Filament\Widgets\Widget;

class ClientesGoogleEnviadasCards extends Widget
{
    protected static string $view = 'filament.resources.clientes-google-enviadas-resource.widgets.clientes-google-enviadas-cards';

    protected int|string|array $columnSpan = 'full';
}

