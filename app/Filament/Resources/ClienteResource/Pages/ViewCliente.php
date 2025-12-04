<?php

namespace App\Filament\Resources\ClienteResource\Pages;

use App\Filament\Resources\ClienteResource;
use App\Filament\Resources\VelaSportPostResource;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;

class ViewCliente extends Page
{
    use InteractsWithRecord;

    protected static string $resource = ClienteResource::class;

    protected static string $view = 'filament.resources.cliente-resource.pages.view-cliente';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function getTitle(): string
    {
        return $this->record->nombre_empresa;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cotizacion')
                ->label('Crear Cotización')
                ->icon('heroicon-o-document-text')
                ->color('success')
                ->modalHeading('📝 Nueva Cotización')
                ->modalWidth('lg')
                ->form([
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
                    Notification::make()
                        ->title('✅ Cotización creada')
                        ->body('Cotización ' . $data['numero_cotizacion'] . ' generada correctamente.')
                        ->success()
                        ->send();
                }),
                
            Action::make('factura')
                ->label('Crear Factura')
                ->icon('heroicon-o-banknotes')
                ->color('primary')
                ->modalHeading('💰 Nueva Factura')
                ->modalWidth('lg')
                ->form([
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
                    Notification::make()
                        ->title('✅ Factura creada')
                        ->body('Factura ' . $data['numero_factura'] . ' generada correctamente.')
                        ->success()
                        ->send();
                }),
                
            Action::make('posts')
                ->label('Ver Posts')
                ->icon('heroicon-o-newspaper')
                ->color('info')
                ->url(VelaSportPostResource::getUrl('index')),

            Action::make('edit')
                ->label('Editar')
                ->icon('heroicon-o-pencil')
                ->color('warning')
                ->url(fn () => ClienteResource::getUrl('edit', ['record' => $this->record])),

            Action::make('create')
                ->label('Nuevo Cliente')
                ->icon('heroicon-o-plus')
                ->url(ClienteResource::getUrl('create')),

            Action::make('back')
                ->label('Volver')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(ClienteResource::getUrl('index')),
        ];
    }
}

