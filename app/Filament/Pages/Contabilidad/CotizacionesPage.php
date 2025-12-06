<?php

namespace App\Filament\Pages\Contabilidad;

use App\Filament\Resources\CotizacionResource;
use App\Models\Cotizacion;
use Filament\Pages\Page;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;

class CotizacionesPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Cotizaciones';
    protected static ?string $title = 'Cotizaciones';
    protected static ?string $navigationGroup = 'Contabilidad';
    protected static ?int $navigationSort = 2;
    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.pages.contabilidad.cotizaciones-page';
    
    protected static ?string $slug = 'contabilidad/cotizaciones';

    public function table(Table $table): Table
    {
        return CotizacionResource::table($table);
    }

    protected function getTableQuery(): Builder
    {
        return Cotizacion::query();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nueva Cotización')
                ->icon('heroicon-o-plus')
                ->url(CotizacionResource::getUrl('create')),
            Actions\Action::make('facturas')
                ->label('Facturas')
                ->icon('heroicon-o-banknotes')
                ->color('gray')
                ->url(FacturasPage::getUrl()),
            Actions\Action::make('cotizaciones')
                ->label('Cotizaciones')
                ->icon('heroicon-o-document-text')
                ->color('success')
                ->url(static::getUrl()),
            Actions\Action::make('reportes')
                ->label('Reportes')
                ->icon('heroicon-o-chart-bar')
                ->color('gray')
                ->url(ReportesPage::getUrl()),
        ];
    }
}

