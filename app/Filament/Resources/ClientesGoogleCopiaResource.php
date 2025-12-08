<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClienteEnProcesoResource;
use App\Filament\Resources\ClientesGoogleCopiaResource\Pages;
use App\Models\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class ClientesGoogleCopiaResource extends ClienteEnProcesoResource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Site Scraper';
    protected static ?string $modelLabel = 'Site Scraper';
    protected static ?string $pluralModelLabel = 'Site Scraper';
    protected static ?string $navigationGroup = 'Herramientas';
    protected static ?int $navigationSort = 5;

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function getEloquentQuery(): Builder
    {
        // Reutiliza el filtro base: solo pendientes (propuesta_enviada null/false)
        try {
            if (!Schema::hasTable('clientes_en_proceso')) {
                return parent::getEloquentQuery()->whereRaw('1 = 0');
            }

            $query = parent::getEloquentQuery()
                ->orderByDesc('created_at')
                ->orderByDesc('id');

            if (Schema::hasColumn('clientes_en_proceso', 'propuesta_enviada')) {
                $query->where(function ($q) {
                    $q->whereNull('propuesta_enviada')
                      ->orWhere('propuesta_enviada', false);
                });
            }

            // Mostrar solo registros creados en 2023
            $query->whereYear('created_at', 2023);

            return $query;
        } catch (\Exception $e) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClientesGoogleCopias::route('/'),
            'create' => Pages\CreateClientesGoogleCopia::route('/create'),
            'view' => Pages\ViewClientesGoogleCopia::route('/{record}'),
            'edit' => Pages\EditClientesGoogleCopia::route('/{record}/edit'),
        ];
    }
}

