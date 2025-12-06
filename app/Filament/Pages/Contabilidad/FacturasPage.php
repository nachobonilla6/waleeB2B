<?php

namespace App\Filament\Pages\Contabilidad;

use App\Filament\Resources\FacturaResource;
use App\Models\Factura;
use Filament\Pages\Page;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;

class FacturasPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Facturas';
    protected static ?string $title = 'Facturas';
    protected static ?string $navigationGroup = 'Contabilidad';
    protected static ?int $navigationSort = 1;
    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.pages.contabilidad.facturas-page';
    
    protected static ?string $slug = 'contabilidad/facturas';

    public function table(Table $table): Table
    {
        return FacturaResource::table($table)
            ->query(Factura::query());
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('facturas')
                ->label('Facturas')
                ->icon('heroicon-o-banknotes')
                ->color('success')
                ->url(static::getUrl()),
            Actions\Action::make('cotizaciones')
                ->label('Cotizaciones')
                ->icon('heroicon-o-document-text')
                ->color('gray')
                ->url(CotizacionesPage::getUrl()),
            Actions\Action::make('reportes')
                ->label('Reportes')
                ->icon('heroicon-o-chart-bar')
                ->color('gray')
                ->url(ReportesPage::getUrl()),
        ];
    }
}

