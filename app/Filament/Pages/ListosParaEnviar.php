<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Resources\ClienteEnProcesoResource;
use App\Models\Client;
use Illuminate\Support\Facades\Schema;

class ListosParaEnviar extends Page
{
    protected static ?string $navigationLabel = 'Listos para Enviar';
    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';
    protected static ?string $navigationGroup = 'Extraer Clientes';
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.listos-para-enviar';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        try {
            if (!Schema::hasTable('clientes_en_proceso')) {
                return '0';
            }
            $count = Client::where('estado', 'listo_para_enviar')->count();
            return $count > 0 ? (string) $count : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }

    public function mount(): void
    {
        $this->redirect(ClienteEnProcesoResource::getUrl('listos'));
    }
}
