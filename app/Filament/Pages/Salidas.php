<?php

namespace App\Filament\Pages;

use App\Models\Factura;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;

class Salidas extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-circle';
    protected static ?string $navigationLabel = 'Salidas';
    protected static ?string $title = 'Reporte de Salidas';
    protected static ?string $navigationGroup = 'Administración';
    protected static ?int $navigationSort = 7;

    protected static string $view = 'filament.pages.salidas';

    public function table(Table $table): Table
    {
        return $table
            ->query(Factura::query()->whereIn('estado', ['pendiente', 'vencida']))
            ->defaultSort('fecha_emision', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('numero_factura')
                    ->label('Nº Factura')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cliente.nombre_empresa')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_emision')
                    ->label('Fecha Emisión')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_vencimiento')
                    ->label('Fecha Vencimiento')
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(fn ($record) => $record->fecha_vencimiento && $record->fecha_vencimiento < now() ? 'danger' : null),
                Tables\Columns\TextColumn::make('concepto')
                    ->label('Concepto')
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'diseno_web' => '🌐 Diseño Web',
                        'redes_sociales' => '📱 Redes Sociales',
                        'seo' => '🔍 SEO',
                        'publicidad' => '📢 Publicidad',
                        'mantenimiento' => '🔧 Mantenimiento',
                        'hosting' => '☁️ Hosting',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('CRC')
                    ->sortable(),
                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pendiente' => 'warning',
                        'vencida' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'pendiente' => '🟡 Pendiente',
                        'vencida' => '🔴 Vencida',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('metodo_pago')
                    ->label('Método de Pago')
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'transferencia' => '🏦 Transferencia',
                        'sinpe' => '📲 SINPE',
                        'tarjeta' => '💳 Tarjeta',
                        'efectivo' => '💵 Efectivo',
                        'paypal' => '🅿️ PayPal',
                        default => $state,
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'vencida' => 'Vencida',
                    ]),
                Tables\Filters\SelectFilter::make('cliente_id')
                    ->label('Cliente')
                    ->relationship('cliente', 'nombre_empresa'),
                Tables\Filters\Filter::make('fecha_emision')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('desde')
                            ->label('Desde'),
                        \Filament\Forms\Components\DatePicker::make('hasta')
                            ->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['desde'],
                                fn (Builder $query, $date): Builder => $query->whereDate('fecha_emision', '>=', $date),
                            )
                            ->when(
                                $data['hasta'],
                                fn (Builder $query, $date): Builder => $query->whereDate('fecha_emision', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

