<?php

namespace App\Filament\Resources\ClienteResource\Pages;

use App\Filament\Resources\ClienteResource;
use App\Models\Cliente;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListClientes extends ListRecords
{
    protected static string $resource = ClienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nuevo Cliente')
                ->icon('heroicon-o-plus'),
            Actions\Action::make('cotizacion')
                ->label('Crear Cotización')
                ->icon('heroicon-o-document-text')
                ->color('success')
                ->modalHeading('📝 Nueva Cotización')
                ->modalWidth('lg')
                ->form([
                    Forms\Components\Select::make('cliente_id')
                        ->label('Cliente')
                        ->options(Cliente::pluck('nombre_empresa', 'id'))
                        ->searchable()
                        ->required(),
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('numero_cotizacion')
                            ->label('Nº Cotización')
                            ->default('COT-' . date('Ymd') . '-' . rand(100, 999))
                            ->disabled(),
                        Forms\Components\DatePicker::make('fecha')
                            ->label('Fecha')
                            ->default(now())
                            ->required(),
                    ]),
                    Forms\Components\Select::make('tipo_servicio')
                        ->label('Tipo de Servicio')
                        ->options([
                            'diseno_web' => '🌐 Diseño Web',
                            'redes_sociales' => '📱 Gestión Redes Sociales',
                            'seo' => '🔍 SEO / Posicionamiento',
                            'publicidad' => '📢 Publicidad Digital',
                            'mantenimiento' => '🔧 Mantenimiento Web',
                            'hosting' => '☁️ Hosting & Dominio',
                            'combo' => '📦 Paquete Completo',
                        ])
                        ->required(),
                    Forms\Components\Select::make('plan')
                        ->label('Plan')
                        ->options([
                            'basico' => 'Básico - $99/mes',
                            'profesional' => 'Profesional - $199/mes',
                            'premium' => 'Premium - $349/mes',
                            'empresarial' => 'Empresarial - $499/mes',
                            'personalizado' => 'Personalizado',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('monto')
                        ->label('Monto (USD)')
                        ->numeric()
                        ->prefix('$')
                        ->required(),
                    Forms\Components\Select::make('vigencia')
                        ->label('Vigencia')
                        ->options([
                            '7' => '7 días',
                            '15' => '15 días',
                            '30' => '30 días',
                            '60' => '60 días',
                        ])
                        ->default('15'),
                    Forms\Components\Textarea::make('descripcion')
                        ->label('Descripción / Servicios incluidos')
                        ->rows(3),
                ])
                ->action(function (array $data) {
                    $cliente = Cliente::find($data['cliente_id']);
                    Notification::make()
                        ->title('✅ Cotización creada')
                        ->body('Cotización ' . $data['numero_cotizacion'] . ' para ' . $cliente->nombre_empresa . ' generada correctamente.')
                        ->success()
                        ->send();
                }),
            Actions\Action::make('factura')
                ->label('Crear Factura')
                ->icon('heroicon-o-banknotes')
                ->color('primary')
                ->modalHeading('💰 Nueva Factura')
                ->modalWidth('lg')
                ->form([
                    Forms\Components\Select::make('cliente_id')
                        ->label('Cliente')
                        ->options(Cliente::pluck('nombre_empresa', 'id'))
                        ->searchable()
                        ->required(),
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('numero_factura')
                            ->label('Nº Factura')
                            ->default('FAC-' . date('Ymd') . '-' . rand(100, 999))
                            ->disabled(),
                        Forms\Components\DatePicker::make('fecha_emision')
                            ->label('Fecha Emisión')
                            ->default(now())
                            ->required(),
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
                            ->disabled(),
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
                ])
                ->action(function (array $data) {
                    $cliente = Cliente::find($data['cliente_id']);
                    Notification::make()
                        ->title('✅ Factura creada')
                        ->body('Factura ' . $data['numero_factura'] . ' para ' . $cliente->nombre_empresa . ' generada correctamente.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
