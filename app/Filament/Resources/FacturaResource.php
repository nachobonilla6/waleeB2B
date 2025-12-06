<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FacturaResource\Pages;
use App\Filament\Resources\FacturaResource\RelationManagers;
use App\Models\Factura;
use App\Models\Cliente;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FacturaResource extends Resource
{
    protected static ?string $model = Factura::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Facturas';
    protected static ?string $navigationGroup = 'Contabilidad';
    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'Factura';
    protected static ?string $pluralModelLabel = 'Facturas';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        $pendientes = static::getModel()::where('estado', 'pendiente')->count();
        return $pendientes > 0 ? 'success' : 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información de Factura')
                    ->icon('heroicon-o-banknotes')
                    ->schema([
                        Forms\Components\Select::make('cliente_id')
                            ->label('Cliente')
                            ->options(Cliente::pluck('nombre_empresa', 'id'))
                            ->searchable()
                            ->required(),
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('numero_factura')
                                ->label('Nº Factura')
                                ->default(fn () => 'FAC-' . date('Ymd') . '-' . rand(100, 999))
                                ->required()
                                ->maxLength(255),
                            Forms\Components\DatePicker::make('fecha_emision')
                                ->label('Fecha Emisión')
                                ->default(now())
                                ->required()
                                ->displayFormat('d/m/Y'),
                        ]),
                        Forms\Components\Select::make('concepto')
                            ->label('Concepto')
                            ->options([
                                'diseno_web' => '🌐 Diseño Web',
                                'redes_sociales' => '📱 Gestión Redes Sociales',
                                'seo' => '🔍 SEO / Posicionamiento',
                                'publicidad' => '📢 Publicidad Digital',
                                'mantenimiento' => '🔧 Mantenimiento Mensual',
                                'hosting' => '☁️ Hosting & Dominio',
                            ])
                            ->required(),
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('subtotal')
                                ->label('Subtotal (USD)')
                                ->numeric()
                                ->prefix('$')
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('total', round($state * 1.13, 2))),
                            Forms\Components\TextInput::make('total')
                                ->label('Total con IVA (13%)')
                                ->numeric()
                                ->prefix('$')
                                ->required(),
                        ]),
                        Forms\Components\Select::make('metodo_pago')
                            ->label('Método de Pago')
                            ->options([
                                'transferencia' => '🏦 Transferencia Bancaria',
                                'sinpe' => '📲 SINPE Móvil',
                                'tarjeta' => '💳 Tarjeta de Crédito',
                                'efectivo' => '💵 Efectivo',
                                'paypal' => '🅿️ PayPal',
                            ])
                            ->required(),
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Select::make('estado')
                                ->label('Estado')
                                ->options([
                                    'pendiente' => '🟡 Pendiente',
                                    'pagada' => '🟢 Pagada',
                                    'vencida' => '🔴 Vencida',
                                    'cancelada' => '⚫ Cancelada',
                                ])
                                ->default('pendiente')
                                ->required(),
                            Forms\Components\DatePicker::make('fecha_vencimiento')
                                ->label('Fecha Vencimiento')
                                ->displayFormat('d/m/Y'),
                        ]),
                        Forms\Components\Textarea::make('notas')
                            ->label('Notas')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),
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
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'pendiente',
                        'success' => 'pagada',
                        'danger' => 'vencida',
                        'gray' => 'cancelada',
                    ]),
                Tables\Columns\TextColumn::make('metodo_pago')
                    ->label('Pago')
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
            ->defaultSort('fecha_emision', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'pagada' => 'Pagada',
                        'vencida' => 'Vencida',
                        'cancelada' => 'Cancelada',
                    ]),
                Tables\Filters\SelectFilter::make('cliente_id')
                    ->label('Cliente')
                    ->options(Cliente::pluck('nombre_empresa', 'id')),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFacturas::route('/'),
            'create' => Pages\CreateFactura::route('/create'),
            'edit' => Pages\EditFactura::route('/{record}/edit'),
        ];
    }
}
