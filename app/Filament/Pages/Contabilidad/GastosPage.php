<?php

namespace App\Filament\Pages\Contabilidad;

use App\Filament\Resources\GastoResource;
use App\Models\Gasto;
use Filament\Pages\Page;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Support\Enums\MaxWidth;

class GastosPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Gastos';
    protected static ?string $title = 'Gastos';
    protected static ?string $navigationGroup = 'Contabilidad';
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.contabilidad.gastos-page';
    
    protected static ?string $slug = 'contabilidad/gastos';

    public function getMaxContentWidth(): MaxWidth | string | null
    {
        return MaxWidth::Full;
    }

    public function table(Table $table): Table
    {
        return GastoResource::table($table)
            ->query(Gasto::query()->orderBy('fecha', 'desc'));
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nuevo Gasto')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->model(Gasto::class),
        ];
    }
}

