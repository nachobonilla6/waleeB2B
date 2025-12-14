<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Filament\Support\Facades\Filament;
use Filament\Support\Facades\Route;
use App\Filament\Resources\ClienteEnProcesoResource;

class ToolsWidget extends Widget
{
    protected static string $view = 'filament.widgets.tools-widget';
    protected static ?int $sort = -999; // Very low value to ensure it appears at the very top

    public static function canView(): bool
    {
        return true; // Or add permission check if needed
    }

    public function getViewData(): array
    {
        return [
            'tools' => [
                [
                    'label' => 'Web Solutions',
                    'icon' => 'heroicon-m-home',
                    'url' => 'https://websolutions.work/',
                    'color' => 'indigo',
                    'description' => 'Sitio Web Principal',
                    'external' => true,
                ],
                [
                    'label' => 'Chat de Soporte',
                    'icon' => 'heroicon-m-chat-bubble-left-right',
                    'url' => route('chat'),
                    'color' => 'green',
                    'description' => 'Soporte en tiempo real',
                    'external' => true,
                ],
                [
                    'label' => 'Hostinger',
                    'icon' => 'heroicon-m-server',
                    'url' => 'https://hpanel.hostinger.com/',
                    'color' => 'purple',
                    'description' => 'Panel de Hosting',
                    'external' => true,
                ],
                [
                    'label' => 'GoDaddy',
                    'icon' => 'heroicon-m-globe-alt',
                    'url' => 'https://account.godaddy.com/products',
                    'color' => 'green',
                    'description' => 'Administrar Dominios',
                    'external' => true,
                ],
                [
                    'label' => 'Site Scraper',
                    'icon' => 'heroicon-m-globe-alt',
                    'url' => '/admin/site-scraper',
                    'color' => 'green',
                    'description' => 'Extraer datos de sitios web',
                    'external' => false,
                ],
                // Web Solutions moved to the top
            ],
        ];
    }
}
