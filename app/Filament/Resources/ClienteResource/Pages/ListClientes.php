<?php

namespace App\Filament\Resources\ClienteResource\Pages;

use App\Filament\Resources\ClienteResource;
use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Mail\CotizacionMail;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\Mail;

class ListClientes extends ListRecords
{
    protected static string $resource = ClienteResource::class;
    
    protected array $cotizacionData = [];

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
                ->modalWidth('4xl')
                ->afterFormValidated(function (array $data, $action) {
                    // Guardar los datos en la propiedad de la clase
                    $this->cotizacionData = $data;
                })
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
                        Forms\Components\Select::make('cliente_id')
                            ->label('Cliente')
                            ->options(Cliente::pluck('nombre_empresa', 'id'))
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Forms\Set $set, $state) {
                                // Cuando se selecciona un cliente, actualizar el correo automáticamente
                                if ($state) {
                                    $cliente = Cliente::find($state);
                                    if ($cliente?->correo) {
                                        $set('correo', $cliente->correo);
                                    }
                                }
                            }),
                    ]),
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('numero_cotizacion')
                            ->label('Nº Cotización')
                            ->default('COT-' . date('Ymd') . '-' . rand(100, 999))
                            ->dehydrated()
                            ->disabled(),
                        Forms\Components\DatePicker::make('fecha')
                            ->label('Fecha')
                            ->default(now())
                            ->required(),
                    ]),
                    Forms\Components\Grid::make(2)->schema([
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
                    ]),
                    Forms\Components\Grid::make(2)->schema([
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
                    ]),
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('correo')
                            ->label('📧 Correo electrónico')
                            ->email()
                            ->required()
                            ->default(function (Forms\Get $get) {
                                $clienteId = $get('cliente_id');
                                if ($clienteId) {
                                    $cliente = Cliente::find($clienteId);
                                    return $cliente?->correo ?? '';
                                }
                                return '';
                            })
                            ->live()
                            ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                                // Si el correo está vacío y hay un cliente seleccionado, usar el correo del cliente
                                $correo = $get('correo');
                                if (empty($correo)) {
                                    $clienteId = $get('cliente_id');
                                    if ($clienteId) {
                                        $cliente = Cliente::find($clienteId);
                                        if ($cliente?->correo) {
                                            $set('correo', $cliente->correo);
                                        }
                                    }
                                }
                            }),
                        Forms\Components\Textarea::make('descripcion')
                            ->label('Descripción / Servicios incluidos')
                            ->rows(2),
                    ]),
                ])
                ->modalSubmitAction(
                    Actions\Action::make('enviar')
                        ->label('📧 Enviar por Correo')
                        ->color('success')
                        ->icon('heroicon-o-envelope')
                        ->action(function (array $data) {
                            // Los datos vienen directamente del formulario validado
                            $clienteId = $data['cliente_id'] ?? null;
                            $cliente = $clienteId ? Cliente::find($clienteId) : null;
                            
                            try {
                                // Convertir fecha a string si es un objeto Carbon
                                $fecha = $data['fecha'] ?? '';
                                if ($fecha instanceof \Carbon\Carbon) {
                                    $fecha = $fecha->format('Y-m-d');
                                } elseif (is_string($fecha) && !empty($fecha)) {
                                    $fecha = $fecha;
                                } else {
                                    $fecha = '';
                                }
                                
                                // Guardar la cotización en la base de datos
                                $cotizacion = Cotizacion::create([
                                    'numero_cotizacion' => $data['numero_cotizacion'] ?? 'COT-' . date('Ymd') . '-' . rand(100, 999),
                                    'fecha' => $fecha,
                                    'idioma' => $data['idioma'] ?? 'es',
                                    'cliente_id' => $clienteId,
                                    'tipo_servicio' => $data['tipo_servicio'] ?? '',
                                    'plan' => $data['plan'] ?? '',
                                    'monto' => $data['monto'] ?? 0,
                                    'vigencia' => $data['vigencia'] ?? '15',
                                    'correo' => $data['correo'] ?? '',
                                    'descripcion' => $data['descripcion'] ?? '',
                                    'estado' => 'enviada',
                                ]);
                                
                                // Preparar datos para el email
                                $emailData = [
                                    'numero_cotizacion' => (string) ($data['numero_cotizacion'] ?? ''),
                                    'fecha' => (string) $fecha,
                                    'idioma' => (string) ($data['idioma'] ?? ''),
                                    'tipo_servicio' => (string) ($data['tipo_servicio'] ?? ''),
                                    'plan' => (string) ($data['plan'] ?? ''),
                                    'monto' => (string) ($data['monto'] ?? ''),
                                    'vigencia' => (string) ($data['vigencia'] ?? ''),
                                    'correo' => (string) ($data['correo'] ?? ''),
                                    'descripcion' => (string) ($data['descripcion'] ?? ''),
                                    'cliente_id' => $clienteId,
                                    'cliente_nombre' => (string) ($cliente?->nombre_empresa ?? ''),
                                    'cliente_correo' => (string) ($cliente?->correo ?? ''),
                                    'timestamp' => now()->toIso8601String(),
                                ];

                                // Enviar email con la cotización
                                $correoDestino = $data['correo'] ?? '';
                                
                                if (empty($correoDestino)) {
                                    Notification::make()
                                        ->title('❌ Error')
                                        ->body('No se especificó un correo electrónico para enviar la cotización.')
                                        ->danger()
                                        ->send();
                                    return;
                                }
                                
                                try {
                                    Mail::to($correoDestino)->send(new CotizacionMail($emailData));
                                    
                                    Notification::make()
                                        ->title('✅ Cotización enviada')
                                        ->body('Cotización ' . ($data['numero_cotizacion'] ?? 'N/A') . ' guardada y enviada por email a ' . $correoDestino)
                                        ->success()
                                        ->send();
                                } catch (\Exception $mailException) {
                                    Notification::make()
                                        ->title('❌ Error al enviar email')
                                        ->body('La cotización se guardó pero no se pudo enviar el email: ' . $mailException->getMessage())
                                        ->warning()
                                        ->send();
                                }
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('❌ Error inesperado')
                                    ->body('Error al procesar la cotización: ' . $e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        })
                )
                ->modalFooterActionsAlignment(Alignment::End)
                ->modalFooterActions(fn ($action) => [
                    Actions\Action::make('cancelar')
                        ->label('Cancelar')
                        ->color('gray')
                        ->close(),
                    Actions\Action::make('borrador')
                        ->label('💾 Guardar Borrador')
                        ->color('warning')
                        ->action(function () use ($action) {
                            $data = $action->getFormData();
                            $cliente = Cliente::find($data['cliente_id'] ?? null);
                            Notification::make()
                                ->title('📝 Borrador guardado')
                                ->body('Cotización ' . ($data['numero_cotizacion'] ?? 'N/A') . ' guardada como borrador.')
                                ->warning()
                                ->send();
                        }),
                ]),
            Actions\Action::make('factura')
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
                        Forms\Components\Select::make('cliente_id')
                            ->label('Cliente')
                            ->options(Cliente::pluck('nombre_empresa', 'id'))
                            ->searchable()
                            ->required(),
                    ]),
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
                    Forms\Components\Grid::make(2)->schema([
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
                    Forms\Components\TextInput::make('correo')
                        ->label('📧 Correo electrónico')
                        ->email()
                        ->required()
                        ->columnSpanFull(),
                ])
                ->modalFooterActions(fn ($action) => [
                    Actions\Action::make('cancelar')
                        ->label('Cancelar')
                        ->color('gray')
                        ->close(),
                    Actions\Action::make('borrador')
                        ->label('💾 Guardar Borrador')
                        ->color('warning')
                        ->action(function (array $data) {
                            $cliente = Cliente::find($data['cliente_id']);
                            Notification::make()
                                ->title('📝 Borrador guardado')
                                ->body('Factura ' . $data['numero_factura'] . ' guardada como borrador.')
                                ->warning()
                                ->send();
                        }),
                    Actions\Action::make('enviar')
                        ->label('📧 Enviar Correo Electrónico')
                        ->color('success')
                        ->action(function (array $data) {
                            $cliente = Cliente::find($data['cliente_id']);
                            Notification::make()
                                ->title('✅ Factura enviada')
                                ->body('Factura ' . $data['numero_factura'] . ' enviada a ' . $data['correo'])
                                ->success()
                                ->send();
                        }),
                ]),
        ];
    }
}
