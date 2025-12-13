<?php

namespace App\Filament\Pages\Contabilidad;

use App\Filament\Resources\FacturaResource;
use App\Filament\Resources\CotizacionResource;
use App\Models\Factura;
use App\Models\Cotizacion;
use App\Models\Cliente;
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
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;

class FacturasPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Facturas';
    protected static ?string $title = 'Facturas';
    protected static ?string $navigationGroup = 'Administración';
    protected static ?int $navigationSort = 1;
    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.pages.contabilidad.facturas-page';
    
    protected static ?string $slug = 'contabilidad/facturas';

    public function table(Table $table): Table
    {
        return FacturaResource::table($table)
            ->query(Factura::query()->orderBy('id', 'desc'));
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nueva Factura')
                ->icon('heroicon-o-plus')
                ->color('gray')
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
                ->action(function (array $data) {
                    // Crear la factura
                    $factura = Factura::create($data);
                    
                    // Obtener datos del cliente
                    $cliente = Cliente::find($data['cliente_id'] ?? null);
                    
                    // Preparar datos para el webhook
                    $webhookData = [
                        'numero_factura' => (string) ($data['numero_factura'] ?? $factura->numero_factura ?? ''),
                        'fecha_emision' => isset($data['fecha_emision']) ? (is_string($data['fecha_emision']) ? $data['fecha_emision'] : $data['fecha_emision']->format('Y-m-d')) : ($factura->fecha_emision ? $factura->fecha_emision->format('Y-m-d') : ''),
                        'concepto' => (string) ($data['concepto'] ?? $factura->concepto ?? ''),
                        'subtotal' => (string) ($data['subtotal'] ?? $factura->subtotal ?? '0'),
                        'total' => (string) ($data['total'] ?? $factura->total ?? '0'),
                        'metodo_pago' => (string) ($data['metodo_pago'] ?? $factura->metodo_pago ?? ''),
                        'estado' => (string) ($data['estado'] ?? $factura->estado ?? ''),
                        'fecha_vencimiento' => isset($data['fecha_vencimiento']) ? (is_string($data['fecha_vencimiento']) ? $data['fecha_vencimiento'] : $data['fecha_vencimiento']->format('Y-m-d')) : ($factura->fecha_vencimiento ? $factura->fecha_vencimiento->format('Y-m-d') : ''),
                        'notas' => (string) ($data['notas'] ?? $factura->notas ?? ''),
                        'cliente_id' => $data['cliente_id'] ?? $factura->cliente_id ?? null,
                        'cliente_nombre' => (string) ($cliente?->nombre_empresa ?? ''),
                        'cliente_correo' => (string) ($data['correo'] ?? $factura->correo ?? $cliente?->correo ?? ''),
                        'factura_id' => $factura->id ?? null,
                    ];
                    
                    try {
                        $response = Http::timeout(30)->post(
                            'https://n8n.srv1137974.hstgr.cloud/webhook/62cb26b6-1b4a-492b-8780-709ff47c81bf',
                            $webhookData
                        );
                        
                        if (!$response->successful()) {
                            \Log::warning('Error en respuesta del webhook al crear factura', [
                                'status' => $response->status(),
                                'response' => $response->body(),
                                'factura_id' => $factura->id ?? null,
                            ]);
                        }
                    } catch (\Exception $webhookException) {
                        \Log::error('Error enviando factura al webhook', [
                            'error' => $webhookException->getMessage(),
                            'factura_id' => $factura->id ?? null,
                        ]);
                    }
                })
                ->extraModalFooterActions(function ($action) {
                    return [
                        Actions\Action::make('guardar_y_enviar')
                            ->label('Guardar y Enviar por Email')
                            ->icon('heroicon-o-envelope')
                            ->color('success')
                            ->requiresConfirmation()
                            ->action(function (array $data) use ($action) {
                                // Crear la factura
                                $factura = Factura::create($data);
                                
                                // Obtener datos del cliente
                                $cliente = Cliente::find($data['cliente_id'] ?? null);
                                
                                // Preparar datos para el webhook
                                $webhookData = [
                                    'numero_factura' => (string) ($data['numero_factura'] ?? ''),
                                    'fecha_emision' => isset($data['fecha_emision']) ? (is_string($data['fecha_emision']) ? $data['fecha_emision'] : $data['fecha_emision']->format('Y-m-d')) : '',
                                    'concepto' => (string) ($data['concepto'] ?? ''),
                                    'subtotal' => (string) ($data['subtotal'] ?? '0'),
                                    'total' => (string) ($data['total'] ?? '0'),
                                    'metodo_pago' => (string) ($data['metodo_pago'] ?? ''),
                                    'estado' => (string) ($data['estado'] ?? ''),
                                    'fecha_vencimiento' => isset($data['fecha_vencimiento']) ? (is_string($data['fecha_vencimiento']) ? $data['fecha_vencimiento'] : $data['fecha_vencimiento']->format('Y-m-d')) : '',
                                    'notas' => (string) ($data['notas'] ?? ''),
                                    'cliente_id' => $data['cliente_id'] ?? $factura->cliente_id ?? null,
                                    'cliente_nombre' => (string) ($cliente?->nombre_empresa ?? ''),
                                    'cliente_correo' => (string) ($data['correo'] ?? $cliente?->correo ?? ''),
                                    'factura_id' => $factura->id ?? null,
                                ];
                                
                                try {
                                    $response = Http::timeout(30)->post(
                                        'https://n8n.srv1137974.hstgr.cloud/webhook/62cb26b6-1b4a-492b-8780-709ff47c81bf',
                                        $webhookData
                                    );
                                    
                                    if ($response->successful()) {
                                        \Filament\Notifications\Notification::make()
                                            ->title('✅ Factura creada y enviada')
                                            ->body('Factura ' . ($data['numero_factura'] ?? 'N/A') . ' guardada y enviada al webhook correctamente')
                                            ->success()
                                            ->send();
                                    } else {
                                        throw new \Exception('Error en la respuesta del webhook: ' . $response->status());
                                    }
                                } catch (\Exception $webhookException) {
                                    \Log::error('Error enviando factura al webhook', [
                                        'error' => $webhookException->getMessage(),
                                        'trace' => $webhookException->getTraceAsString(),
                                        'factura' => $data['numero_factura'] ?? 'N/A',
                                    ]);
                                    
                                    \Filament\Notifications\Notification::make()
                                        ->title('⚠️ Factura guardada')
                                        ->body('La factura se guardó pero no se pudo enviar al webhook. Error: ' . $webhookException->getMessage())
                                        ->warning()
                                        ->persistent()
                                        ->send();
                                }
                                
                                $action->success();
                            }),
                    ];
                })
                ->successNotificationTitle('Factura creada exitosamente'),
            Actions\CreateAction::make('nueva_cotizacion')
                ->label('Nueva Cotización')
                ->icon('heroicon-o-document-plus')
                ->color('gray')
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
                        Actions\Action::make('guardar_y_enviar_cotizacion')
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
                                    Mail::to($correoDestino)->send(new \App\Mail\CotizacionMail($emailData));
                                    
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

