<?php

namespace App\Filament\Widgets;

use App\Models\Factura;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ClientFinanzasStatsWidget extends BaseWidget
{
    /**
     * Correo del cliente (clientes_en_proceso) para filtrar facturas.
     */
    public ?string $email = null;

    protected function getStats(): array
    {
        if (! $this->email) {
            return [
                Stat::make('Total', '₡0,00')->color('primary'),
                Stat::make('Pagado', '₡0,00')->color('success'),
                Stat::make('Pendiente', '₡0,00')->color('danger'),
            ];
        }

        $baseQuery = Factura::query()->where('correo', $this->email);

        $total = (clone $baseQuery)->sum('total');
        $pagado = (clone $baseQuery)->where('estado', 'pagada')->sum('total');
        $pendiente = $total - $pagado;

        $format = fn (float $value) => '₡' . number_format($value, 2, ',', ' ');

        return [
            Stat::make('Total', $format($total))
                ->description('Facturado al cliente')
                ->color('primary'),

            Stat::make('Pagado', $format($pagado))
                ->description('Facturas pagadas')
                ->color('success'),

            Stat::make('Pendiente', $format(max($pendiente, 0)))
                ->description('Saldo pendiente')
                ->color('danger'),
        ];
    }
}


