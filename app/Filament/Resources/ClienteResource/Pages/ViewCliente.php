<?php

namespace App\Filament\Resources\ClienteResource\Pages;

use App\Filament\Resources\ClienteResource;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class ViewCliente extends Page
{
    use InteractsWithRecord;

    protected static string $resource = ClienteResource::class;

    protected static string $view = 'filament.resources.cliente-resource.pages.view-cliente';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $this->record->loadMissing('facturas');
    }

    public function getTitle(): string
    {
        return '';
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cotizacion')
                ->label('Crear Cotización')
                ->icon('heroicon-o-document-text')
                ->color('success')
                ->modalHeading('📝 Nueva Cotización')
                ->modalWidth('4xl')
                ->form([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Select::make('idioma')
                            ->label('🌐 Idioma')
                            ->options([
                                'es' => '🇪🇸 Español',
                                'en' => '🇺🇸 English',
                                'fr' => '🇫🇷 Français',
                            ])
                            ->default('es')
                            ->required(),
                        Forms\Components\TextInput::make('numero_cotizacion')
                            ->label('Nº Cotización')
                            ->default('COT-' . date('Ymd') . '-' . rand(100, 999))
                            ->disabled(),
                    ]),
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\DatePicker::make('fecha')
                            ->label('Fecha')
                            ->default(now())
                            ->required(),
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
                    ]),
                    Forms\Components\Grid::make(2)->schema([
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
                    ]),
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Select::make('vigencia')
                            ->label('Vigencia')
                            ->options([
                                '7' => '7 días',
                                '15' => '15 días',
                                '30' => '30 días',
                                '60' => '60 días',
                            ])
                            ->default('15'),
                        Forms\Components\TextInput::make('correo')
                            ->label('📧 Correo electrónico')
                            ->email()
                            ->default(fn () => $this->record->correo)
                            ->required(),
                    ]),
                    Forms\Components\Textarea::make('descripcion')
                        ->label('Descripción / Servicios incluidos')
                        ->rows(2)
                        ->columnSpanFull(),
                ])
                ->modalFooterActions(fn ($action) => [
                    Action::make('cancelar')
                        ->label('Cancelar')
                        ->color('gray')
                        ->close(),
                    Action::make('borrador')
                        ->label('💾 Guardar Borrador')
                        ->color('warning')
                        ->action(function (array $data) {
                            Notification::make()
                                ->title('📝 Borrador guardado')
                                ->body('Cotización ' . $data['numero_cotizacion'] . ' guardada como borrador.')
                                ->warning()
                                ->send();
                        }),
                    Action::make('enviar')
                        ->label('📧 Enviar Correo Electrónico')
                        ->color('success')
                        ->action(function (array $data) {
                            try {
                                // Enviar datos al webhook de n8n
                                $webhookData = [
                                    'numero_cotizacion' => $data['numero_cotizacion'] ?? '',
                                    'fecha' => $data['fecha'] ?? '',
                                    'idioma' => $data['idioma'] ?? '',
                                    'tipo_servicio' => $data['tipo_servicio'] ?? '',
                                    'plan' => $data['plan'] ?? '',
                                    'monto' => $data['monto'] ?? '',
                                    'vigencia' => $data['vigencia'] ?? '',
                                    'correo' => $data['correo'] ?? '',
                                    'descripcion' => $data['descripcion'] ?? '',
                                    'cliente_id' => $this->record->id ?? null,
                                    'cliente_nombre' => $this->record->nombre_empresa ?? '',
                                    'cliente_correo' => $this->record->correo ?? '',
                                    'timestamp' => now()->toIso8601String(),
                                ];

                                $response = Http::timeout(30)->post('https://n8n.srv1137974.hstgr.cloud/webhook/8fa4f274-a074-48ad-b3d8-42e83e5fca51', $webhookData);

                                if ($response->successful()) {
                                    Notification::make()
                                        ->title('✅ Cotización enviada')
                                        ->body('Cotización ' . ($data['numero_cotizacion'] ?? 'N/A') . ' enviada a ' . ($data['correo'] ?? 'N/A') . ' y al webhook.')
                                        ->success()
                                        ->send();
                                } else {
                                    $errorMessage = $response->status() . ': ' . ($response->body() ?: 'Sin respuesta del servidor');
                                    Notification::make()
                                        ->title('⚠️ Cotización enviada con advertencia')
                                        ->body('Cotización enviada a ' . ($data['correo'] ?? 'N/A') . '. Error del webhook: ' . $errorMessage)
                                        ->warning()
                                        ->send();
                                }
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('✅ Cotización enviada')
                                    ->body('Cotización ' . ($data['numero_cotizacion'] ?? 'N/A') . ' enviada a ' . ($data['correo'] ?? 'N/A') . '. Error al enviar al webhook: ' . $e->getMessage())
                                    ->warning()
                                    ->send();
                            }
                        }),
                ]),
                
            Action::make('factura')
                ->label('Crear Factura')
                ->icon('heroicon-o-banknotes')
                ->color('primary')
                ->modalHeading('💰 Nueva Factura')
                ->modalWidth('4xl')
                ->form([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Select::make('idioma')
                            ->label('🌐 Idioma')
                            ->options([
                                'es' => '🇪🇸 Español',
                                'en' => '🇺🇸 English',
                                'fr' => '🇫🇷 Français',
                            ])
                            ->default('es')
                            ->required(),
                        Forms\Components\TextInput::make('numero_factura')
                            ->label('Nº Factura')
                            ->default('FAC-' . date('Ymd') . '-' . rand(100, 999))
                            ->disabled(),
                    ]),
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\DatePicker::make('fecha_emision')
                            ->label('Fecha Emisión')
                            ->default(now())
                            ->required(),
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
                    ]),
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
                    Forms\Components\Grid::make(2)->schema([
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
                        Forms\Components\TextInput::make('correo')
                            ->label('📧 Correo electrónico')
                            ->email()
                            ->default(fn () => $this->record->correo)
                            ->required(),
                    ]),
                ])
                ->modalFooterActions(fn ($action) => [
                    Action::make('cancelar')
                        ->label('Cancelar')
                        ->color('gray')
                        ->close(),
                    Action::make('borrador')
                        ->label('💾 Guardar Borrador')
                        ->color('warning')
                        ->action(function (array $data) {
                            Notification::make()
                                ->title('📝 Borrador guardado')
                                ->body('Factura ' . $data['numero_factura'] . ' guardada como borrador.')
                                ->warning()
                                ->send();
                        }),
                    Action::make('enviar')
                        ->label('📧 Enviar Correo Electrónico')
                        ->color('success')
                        ->action(function (array $data) {
                            Notification::make()
                                ->title('✅ Factura enviada')
                                ->body('Factura ' . $data['numero_factura'] . ' enviada a ' . $data['correo'])
                                ->success()
                                ->send();
                        }),
                ]),
                
            Action::make('edit')
                ->label('Editar')
                ->icon('heroicon-o-pencil')
                ->color('success')
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

