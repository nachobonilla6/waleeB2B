<?php

namespace App\Filament\Pages\Contabilidad;

use App\Filament\Resources\CotizacionResource;
use App\Filament\Resources\FacturaResource;
use App\Models\Cotizacion;
use App\Models\Factura;
use App\Models\Cliente;
use App\Mail\CotizacionMail;
use App\Mail\FacturaMail;
use Filament\Pages\Page;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\Wizard\Step;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;

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
        return CotizacionResource::table($table)
            ->query(Cotizacion::query());
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nueva Cotización')
                ->icon('heroicon-o-plus')
                ->color('success')
                ->model(Cotizacion::class)
                ->steps([
                    Step::make('Información Básica')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\TextInput::make('numero_cotizacion')
                                    ->label('Nº Cotización')
                                    ->default('COT-' . date('Ymd') . '-' . rand(100, 999))
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                Forms\Components\DatePicker::make('fecha')
                                    ->label('Fecha')
                                    ->default(now())
                                    ->required(),
                            ]),
                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\Select::make('cliente_id')
                                    ->label('Cliente')
                                    ->options(Cliente::pluck('nombre_empresa', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (Forms\Set $set, $state) {
                                        if ($state) {
                                            $cliente = Cliente::find($state);
                                            if ($cliente?->correo) {
                                                $set('correo', $cliente->correo);
                                            }
                                        }
                                    }),
                                Forms\Components\Select::make('idioma')
                                    ->label('Idioma')
                                    ->options([
                                        'es' => 'Español',
                                        'en' => 'English',
                                        'fr' => 'Français',
                                    ])
                                    ->default('es')
                                    ->required(),
                            ]),
                        ]),
                    Step::make('Detalles del Servicio')
                        ->icon('heroicon-o-briefcase')
                        ->schema([
                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\Select::make('tipo_servicio')
                                    ->label('Tipo de Servicio')
                                    ->options([
                                        'diseno_web' => 'Diseño Web',
                                        'redes_sociales' => 'Gestión Redes Sociales',
                                        'seo' => 'SEO / Posicionamiento',
                                        'publicidad' => 'Publicidad Digital',
                                        'mantenimiento' => 'Mantenimiento Web',
                                        'hosting' => 'Hosting & Dominio',
                                        'combo' => 'Paquete Completo',
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
                                    ->label('Monto (₡)')
                                    ->numeric()
                                    ->prefix('₡')
                                    ->required(),
                                Forms\Components\Select::make('vigencia')
                                    ->label('Vigencia')
                                    ->options([
                                        '7' => '7 días',
                                        '15' => '15 días',
                                        '30' => '30 días',
                                        '60' => '60 días',
                                    ])
                                    ->default('15')
                                    ->required(),
                            ]),
                            Forms\Components\Textarea::make('descripcion')
                                ->label('Descripción / Servicios incluidos')
                                ->rows(3)
                                ->columnSpanFull(),
                        ]),
                    Step::make('Contacto y Estado')
                        ->icon('heroicon-o-envelope')
                        ->schema([
                            Forms\Components\TextInput::make('correo')
                                ->label('Correo electrónico')
                                ->email()
                                ->required(),
                            Forms\Components\Select::make('estado')
                                ->label('Estado')
                                ->options([
                                    'pendiente' => 'Pendiente',
                                    'enviada' => 'Enviada',
                                    'aceptada' => 'Aceptada',
                                    'rechazada' => 'Rechazada',
                                ])
                                ->default('pendiente')
                                ->required(),
                        ]),
                ])
                ->modalWidth('4xl')
                ->extraModalFooterActions(function ($action) {
                    return [
                        Actions\Action::make('guardar_y_enviar')
                            ->label('Guardar y Enviar por Email')
                            ->icon('heroicon-o-envelope')
                            ->color('success')
                            ->requiresConfirmation()
                            ->action(function (array $data) use ($action) {
                                // Crear la cotización
                                $cotizacion = Cotizacion::create($data);
                                
                                // Obtener datos del cliente
                                $cliente = Cliente::find($data['cliente_id'] ?? null);
                                
                                // Preparar datos para el email
                                $fecha = isset($data['fecha']) ? (is_string($data['fecha']) ? $data['fecha'] : $data['fecha']->format('Y-m-d')) : '';
                                
                                $emailData = [
                                    'numero_cotizacion' => (string) ($data['numero_cotizacion'] ?? ''),
                                    'fecha' => $fecha,
                                    'idioma' => (string) ($data['idioma'] ?? ''),
                                    'tipo_servicio' => (string) ($data['tipo_servicio'] ?? ''),
                                    'plan' => (string) ($data['plan'] ?? ''),
                                    'monto' => (string) ($data['monto'] ?? ''),
                                    'vigencia' => (string) ($data['vigencia'] ?? ''),
                                    'correo' => (string) ($data['correo'] ?? ''),
                                    'descripcion' => (string) ($data['descripcion'] ?? ''),
                                    'cliente_id' => $data['cliente_id'] ?? null,
                                    'cliente_nombre' => (string) ($cliente?->nombre_empresa ?? ''),
                                    'cliente_correo' => (string) ($data['correo'] ?? $cliente?->correo ?? ''),
                                    'timestamp' => now()->toIso8601String(),
                                ];
                                
                                // Enviar email
                                $correoDestino = $data['correo'] ?? $cliente?->correo ?? '';
                                
                                if (empty($correoDestino)) {
                                    \Filament\Notifications\Notification::make()
                                        ->title('⚠️ Cotización guardada')
                                        ->body('La cotización se guardó pero no se especificó un correo electrónico para enviar.')
                                        ->warning()
                                        ->send();
                                    return;
                                }
                                
                                try {
                                    \Illuminate\Support\Facades\Mail::to($correoDestino)->send(new \App\Mail\CotizacionMail($emailData));
                                    
                                    \Filament\Notifications\Notification::make()
                                        ->title('✅ Cotización creada y enviada')
                                        ->body('Cotización ' . ($data['numero_cotizacion'] ?? 'N/A') . ' guardada y enviada por email a ' . $correoDestino)
                                        ->success()
                                        ->send();
                                } catch (\Exception $mailException) {
                                    \Filament\Notifications\Notification::make()
                                        ->title('⚠️ Cotización guardada')
                                        ->body('La cotización se guardó pero no se pudo enviar el email: ' . $mailException->getMessage())
                                        ->warning()
                                        ->send();
                                }
                                
                                $action->success();
                            }),
                    ];
                })
                ->successNotificationTitle('Cotización creada exitosamente'),
            Actions\CreateAction::make('nueva_factura')
                ->label('Nueva Factura')
                ->icon('heroicon-o-document-plus')
                ->color('primary')
                ->model(Factura::class)
                ->steps([
                    Step::make('Información Básica')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Forms\Components\Select::make('cliente_id')
                                ->label('Cliente')
                                ->options(Cliente::pluck('nombre_empresa', 'id'))
                                ->searchable()
                                ->required()
                                ->live()
                                ->afterStateUpdated(function (Forms\Set $set, $state) {
                                    if ($state) {
                                        $cliente = Cliente::find($state);
                                        if ($cliente?->correo) {
                                            $set('correo', $cliente->correo);
                                        }
                                    }
                                }),
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
                        ]),
                    Step::make('Detalles y Montos')
                        ->icon('heroicon-o-currency-dollar')
                        ->schema([
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
                        ]),
                    Step::make('Pago y Estado')
                        ->icon('heroicon-o-banknotes')
                        ->schema([
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
                            Forms\Components\TextInput::make('correo')
                                ->label('Correo electrónico para envío')
                                ->email()
                                ->placeholder('correo@ejemplo.com')
                                ->helperText('Correo donde se enviará la factura. Se auto-completa con el correo del cliente seleccionado.')
                                ->required(),
                            Forms\Components\Textarea::make('notas')
                                ->label('Notas')
                                ->rows(3)
                                ->columnSpanFull(),
                        ]),
                ])
                ->modalWidth('4xl')
                ->extraModalFooterActions(function ($action) {
                    return [
                        Actions\Action::make('guardar_y_enviar_factura')
                            ->label('Guardar y Enviar por Email')
                            ->icon('heroicon-o-envelope')
                            ->color('success')
                            ->requiresConfirmation()
                            ->action(function (array $data) use ($action) {
                                // Crear la factura
                                $factura = Factura::create($data);
                                
                                // Obtener datos del cliente
                                $cliente = Cliente::find($data['cliente_id'] ?? null);
                                
                                // Preparar datos para el email
                                $emailData = [
                                    'numero_factura' => (string) ($data['numero_factura'] ?? ''),
                                    'fecha_emision' => isset($data['fecha_emision']) ? (is_string($data['fecha_emision']) ? $data['fecha_emision'] : $data['fecha_emision']->format('Y-m-d')) : '',
                                    'concepto' => (string) ($data['concepto'] ?? ''),
                                    'subtotal' => (string) ($data['subtotal'] ?? '0'),
                                    'total' => (string) ($data['total'] ?? '0'),
                                    'metodo_pago' => (string) ($data['metodo_pago'] ?? ''),
                                    'estado' => (string) ($data['estado'] ?? ''),
                                    'fecha_vencimiento' => isset($data['fecha_vencimiento']) ? (is_string($data['fecha_vencimiento']) ? $data['fecha_vencimiento'] : $data['fecha_vencimiento']->format('Y-m-d')) : '',
                                    'notas' => (string) ($data['notas'] ?? ''),
                                    'cliente_nombre' => (string) ($cliente?->nombre_empresa ?? ''),
                                    'cliente_correo' => (string) ($data['correo'] ?? $cliente?->correo ?? ''),
                                ];
                                
                                // Enviar email
                                $correoDestino = $data['correo'] ?? $cliente?->correo ?? '';
                                
                                if (empty($correoDestino)) {
                                    \Filament\Notifications\Notification::make()
                                        ->title('⚠️ Factura guardada')
                                        ->body('La factura se guardó pero no se especificó un correo electrónico para enviar.')
                                        ->warning()
                                        ->send();
                                    return;
                                }
                                
                                try {
                                    \Illuminate\Support\Facades\Mail::to($correoDestino)->send(new \App\Mail\FacturaMail($emailData));
                                    
                                    \Filament\Notifications\Notification::make()
                                        ->title('✅ Factura creada y enviada')
                                        ->body('Factura ' . ($data['numero_factura'] ?? 'N/A') . ' guardada y enviada por email a ' . $correoDestino)
                                        ->success()
                                        ->send();
                                } catch (\Exception $mailException) {
                                    \Filament\Notifications\Notification::make()
                                        ->title('⚠️ Factura guardada')
                                        ->body('La factura se guardó pero no se pudo enviar el email: ' . $mailException->getMessage())
                                        ->warning()
                                        ->send();
                                }
                                
                                $action->success();
                            }),
                    ];
                })
                ->successNotificationTitle('Factura creada exitosamente'),
            Actions\Action::make('facturas')
                ->label('Facturas')
                ->icon('heroicon-o-banknotes')
                ->color('success')
                ->url(FacturasPage::getUrl()),
            Actions\Action::make('cotizaciones')
                ->label('Cotizaciones')
                ->icon('heroicon-o-document-text')
                ->color('gray')
                ->url(static::getUrl()),
            Actions\Action::make('reportes')
                ->label('Reportes')
                ->icon('heroicon-o-chart-bar')
                ->color('gray')
                ->url(ReportesPage::getUrl()),
        ];
    }
}

