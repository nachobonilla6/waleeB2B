<?php

namespace App\Filament\Resources\ClienteResource\Pages;

use App\Filament\Resources\ClienteResource;
use App\Mail\CotizacionMail;
use App\Models\Note;
use App\Models\Factura;
use App\Models\Cotizacion;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class ViewCliente extends Page implements HasTable
{
    use InteractsWithRecord;
    use InteractsWithTable;

    protected static string $resource = ClienteResource::class;

    protected static string $view = 'filament.resources.cliente-resource.pages.view-cliente';
    
    protected array $cotizacionData = [];
    
    public ?string $activeTab = 'facturas';
    
    public ?string $filtroAno = null;
    public ?string $filtroTrimestre = null;
    public ?string $filtroMes = null;
    public ?string $filtroSerie = null;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $this->record->loadMissing(['notes.user']);
    }
    
    public function setActiveTab(string $tab): void
    {
        $this->activeTab = $tab;
    }
    
    public function getFacturasFiltradasProperty()
    {
        $clientEmail = $this->record->email ?? null;
        $clientName = $this->record->name ?? null;
        
        // Buscar facturas por correo o nombre del cliente
        $query = Factura::query()->where(function($q) use ($clientEmail, $clientName) {
            if ($clientEmail) {
                $q->where('correo', $clientEmail)
                  ->orWhereHas('cliente', function($clienteQuery) use ($clientEmail, $clientName) {
                      if ($clientEmail) {
                          $clienteQuery->where('correo', $clientEmail);
                      }
                      if ($clientName) {
                          $clienteQuery->orWhere('nombre_empresa', 'like', '%' . $clientName . '%');
                      }
                  });
            }
        });
        
        if ($this->filtroAno && $this->filtroAno !== 'TODOS') {
            $query->whereYear('fecha_emision', $this->filtroAno);
        }
        
        if ($this->filtroTrimestre && $this->filtroTrimestre !== 'TODOS') {
            $mesInicio = (($this->filtroTrimestre - 1) * 3) + 1;
            $mesFin = $mesInicio + 2;
            $query->whereMonth('fecha_emision', '>=', $mesInicio)
                  ->whereMonth('fecha_emision', '<=', $mesFin);
        }
        
        if ($this->filtroMes && $this->filtroMes !== 'TODOS') {
            $query->whereMonth('fecha_emision', $this->filtroMes);
        }
        
        if ($this->filtroSerie && $this->filtroSerie !== 'TODOS') {
            $query->where('serie', $this->filtroSerie);
        }
        
        return $query->orderBy('fecha_emision', 'desc')->get();
    }
    
    public function getResumenFacturasProperty()
    {
        $facturas = $this->facturasFiltradas;
        
        $total = $facturas->sum('total');
        $pagado = $facturas->sum('monto_pagado');
        $pendiente = $total - $pagado;
        
        return [
            'total' => $total,
            'pagado' => $pagado,
            'pendiente' => $pendiente,
        ];
    }

    public function getTitle(): string
    {
        return '';
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    public function table(Table $table): Table
    {
        $clientId = $this->record->id;
        $clientEmail = $this->record->email ?? null;
        
        // Query para notas del cliente
        $notesQuery = Note::query()
            ->where('client_id', $clientId)
            ->select([
                'notes.id',
                'notes.client_id',
                DB::raw("NULL as cliente_id"),
                'notes.content',
                'notes.type',
                'notes.user_id',
                'notes.created_at',
                'notes.updated_at',
                DB::raw("'note' as record_type"),
                DB::raw("NULL as propuesta"),
                DB::raw("NULL as name"),
                DB::raw("NULL as enlace"),
            ]);
        
        // Query para facturas creadas (buscar por correo o nombre)
        $facturasCreadasQuery = Factura::query()
            ->where(function($q) use ($clientEmail) {
                if ($clientEmail) {
                    $q->where('correo', $clientEmail)
                      ->orWhereHas('cliente', function($clienteQuery) use ($clientEmail) {
                          $clienteQuery->where('correo', $clientEmail);
                      });
                }
            })
            ->select([
                'facturas.id',
                DB::raw("NULL as client_id"),
                'facturas.cliente_id',
                DB::raw("CONCAT('Factura ', facturas.numero_factura, ' - ', facturas.concepto, ' - Total: ₡', ROUND(facturas.total, 2)) as content"),
                DB::raw("'factura_creada' as type"),
                DB::raw("NULL as user_id"),
                'facturas.created_at',
                'facturas.updated_at',
                DB::raw("'factura' as record_type"),
                DB::raw("NULL as propuesta"),
                DB::raw("NULL as name"),
                'facturas.enlace',
            ]);

        // Query para facturas editadas
        $facturasEditadasQuery = Factura::query()
            ->where(function($q) use ($clientEmail) {
                if ($clientEmail) {
                    $q->where('correo', $clientEmail)
                      ->orWhereHas('cliente', function($clienteQuery) use ($clientEmail) {
                          $clienteQuery->where('correo', $clientEmail);
                      });
                }
            })
            ->whereRaw('facturas.updated_at != facturas.created_at')
            ->where(function($q) {
                $q->whereNull('facturas.enviada_at')
                  ->orWhereRaw('facturas.updated_at != facturas.enviada_at');
            })
            ->select([
                'facturas.id',
                DB::raw("NULL as client_id"),
                'facturas.cliente_id',
                DB::raw("CONCAT('Factura ', facturas.numero_factura, ' - ', facturas.concepto, ' - Total: ₡', ROUND(facturas.total, 2)) as content"),
                DB::raw("'factura_editada' as type"),
                DB::raw("NULL as user_id"),
                'facturas.updated_at as created_at',
                'facturas.updated_at',
                DB::raw("'factura' as record_type"),
                DB::raw("NULL as propuesta"),
                DB::raw("NULL as name"),
                'facturas.enlace',
            ]);

        // Query para facturas enviadas
        $facturasEnviadasQuery = Factura::query()
            ->where(function($q) use ($clientEmail) {
                if ($clientEmail) {
                    $q->where('correo', $clientEmail)
                      ->orWhereHas('cliente', function($clienteQuery) use ($clientEmail) {
                          $clienteQuery->where('correo', $clientEmail);
                      });
                }
            })
            ->whereNotNull('enviada_at')
            ->select([
                'facturas.id',
                DB::raw("NULL as client_id"),
                'facturas.cliente_id',
                DB::raw("CONCAT('Factura ', facturas.numero_factura, ' - ', facturas.concepto, ' - Total: ₡', ROUND(facturas.total, 2)) as content"),
                DB::raw("'factura_enviada' as type"),
                DB::raw("NULL as user_id"),
                'facturas.enviada_at as created_at',
                'facturas.updated_at',
                DB::raw("'factura' as record_type"),
                DB::raw("NULL as propuesta"),
                DB::raw("NULL as name"),
                'facturas.enlace',
            ]);

        // Query para cotizaciones creadas (buscar por correo)
        $cotizacionesCreadasQuery = Cotizacion::query()
            ->where(function($q) use ($clientEmail) {
                if ($clientEmail) {
                    $q->where('correo', $clientEmail)
                      ->orWhereHas('cliente', function($clienteQuery) use ($clientEmail) {
                          $clienteQuery->where('correo', $clientEmail);
                      });
                }
            })
            ->select([
                'cotizacions.id',
                DB::raw("NULL as client_id"),
                'cotizacions.cliente_id',
                DB::raw("CONCAT('Cotización ', cotizacions.numero_cotizacion, ' - ', cotizacions.tipo_servicio, ' - ', cotizacions.plan, ' - Monto: ₡', ROUND(cotizacions.monto, 2)) as content"),
                DB::raw("'cotizacion_creada' as type"),
                DB::raw("NULL as user_id"),
                'cotizacions.created_at',
                'cotizacions.updated_at',
                DB::raw("'cotizacion' as record_type"),
                DB::raw("NULL as propuesta"),
                DB::raw("NULL as name"),
                DB::raw("NULL as enlace"),
            ]);

        // Query para cotizaciones editadas
        $cotizacionesEditadasQuery = Cotizacion::query()
            ->where(function($q) use ($clientEmail) {
                if ($clientEmail) {
                    $q->where('correo', $clientEmail)
                      ->orWhereHas('cliente', function($clienteQuery) use ($clientEmail) {
                          $clienteQuery->where('correo', $clientEmail);
                      });
                }
            })
            ->whereRaw('cotizacions.updated_at != cotizacions.created_at')
            ->where(function($q) {
                $q->whereNull('cotizacions.enviada_at')
                  ->orWhereRaw('cotizacions.updated_at != cotizacions.enviada_at');
            })
            ->select([
                'cotizacions.id',
                DB::raw("NULL as client_id"),
                'cotizacions.cliente_id',
                DB::raw("CONCAT('Cotización ', cotizacions.numero_cotizacion, ' - ', cotizacions.tipo_servicio, ' - ', cotizacions.plan, ' - Monto: ₡', ROUND(cotizacions.monto, 2)) as content"),
                DB::raw("'cotizacion_editada' as type"),
                DB::raw("NULL as user_id"),
                'cotizacions.updated_at as created_at',
                'cotizacions.updated_at',
                DB::raw("'cotizacion' as record_type"),
                DB::raw("NULL as propuesta"),
                DB::raw("NULL as name"),
                DB::raw("NULL as enlace"),
            ]);

        // Query para cotizaciones enviadas
        $cotizacionesEnviadasQuery = Cotizacion::query()
            ->where(function($q) use ($clientEmail) {
                if ($clientEmail) {
                    $q->where('correo', $clientEmail)
                      ->orWhereHas('cliente', function($clienteQuery) use ($clientEmail) {
                          $clienteQuery->where('correo', $clientEmail);
                      });
                }
            })
            ->where(function($q) {
                $q->whereNotNull('enviada_at')
                  ->orWhere('estado', 'enviada');
            })
            ->select([
                'cotizacions.id',
                DB::raw("NULL as client_id"),
                'cotizacions.cliente_id',
                DB::raw("CONCAT('Cotización ', cotizacions.numero_cotizacion, ' - ', cotizacions.tipo_servicio, ' - ', cotizacions.plan, ' - Monto: ₡', ROUND(cotizacions.monto, 2)) as content"),
                DB::raw("'cotizacion_enviada' as type"),
                DB::raw("NULL as user_id"),
                DB::raw("COALESCE(cotizacions.enviada_at, cotizacions.updated_at) as created_at"),
                'cotizacions.updated_at',
                DB::raw("'cotizacion' as record_type"),
                DB::raw("NULL as propuesta"),
                DB::raw("NULL as name"),
                DB::raw("NULL as enlace"),
            ]);

        // Unir todas las queries
        $unifiedQuery = $notesQuery
            ->union($facturasCreadasQuery)
            ->union($facturasEditadasQuery)
            ->union($facturasEnviadasQuery)
            ->union($cotizacionesCreadasQuery)
            ->union($cotizacionesEditadasQuery)
            ->union($cotizacionesEnviadasQuery)
            ->orderBy('created_at', 'desc');

        return $table
            ->query($unifiedQuery)
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->label('Creado por')
                    ->placeholder('Sistema')
                    ->formatStateUsing(function ($state) {
                        if ($state) {
                            $user = \App\Models\User::find($state);
                            return $user?->name ?? 'Sistema';
                        }
                        return 'Sistema';
                    }),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'note' => 'warning',
                        'call' => 'primary',
                        'meeting' => 'info',
                        'email' => 'success',
                        'propuesta_enviada' => 'warning',
                        'factura_creada' => 'warning',
                        'factura_editada' => 'warning',
                        'factura_enviada' => 'success',
                        'cotizacion_creada' => 'warning',
                        'cotizacion_editada' => 'warning',
                        'cotizacion_enviada' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'note' => 'Nota',
                        'call' => 'Llamada',
                        'meeting' => 'Reunión',
                        'email' => 'Email',
                        'propuesta_enviada' => '📄 Propuesta Enviada',
                        'factura_creada' => '💰 Factura Creada',
                        'factura_editada' => '✏️ Factura Editada',
                        'factura_enviada' => '📧 Factura Enviada',
                        'cotizacion_creada' => '📋 Cotización Creada',
                        'cotizacion_editada' => '✏️ Cotización Editada',
                        'cotizacion_enviada' => '📧 Cotización Enviada',
                        default => $state,
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('content')
                    ->label('Detalles')
                    ->wrap()
                    ->searchable()
                    ->html()
                    ->formatStateUsing(function ($state, $record) {
                        $getValue = function($key) use ($record) {
                            if (is_array($record)) {
                                return $record[$key] ?? null;
                            }
                            return $record->$key ?? null;
                        };
                        
                        $recordId = $getValue('id');
                        $recordType = $getValue('record_type');
                        $enlace = $getValue('enlace');
                        
                        // Nota - enlace al view de la nota
                        if ($recordType === 'note' && $recordId) {
                            $url = \App\Filament\Resources\NoteResource::getUrl('view', ['record' => $recordId]);
                            $contentHtml = '<div class="whitespace-pre-wrap">' . nl2br(e($state)) . '</div>';
                            return '<a href="' . $url . '" class="text-primary-600 dark:text-primary-400 hover:underline">' . $contentHtml . '</a>';
                        }
                        
                        // Factura - enlace si existe
                        if ($recordType === 'factura' && $enlace) {
                            $contentHtml = '<div class="whitespace-pre-wrap">' . nl2br(e($state)) . '</div>';
                            return '<a href="' . $enlace . '" target="_blank" class="text-primary-600 dark:text-primary-400 hover:underline">' . $contentHtml . '</a>';
                        }
                        
                        return '<div class="whitespace-pre-wrap">' . nl2br(e($state)) . '</div>';
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('No hay actividades registradas')
            ->emptyStateDescription('Las actividades de este cliente aparecerán aquí.')
            ->emptyStateIcon('heroicon-o-document-text');
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
                        Forms\Components\TextInput::make('numero_cotizacion')
                            ->label('Nº Cotización')
                            ->default('COT-' . date('Ymd') . '-' . rand(100, 999))
                            ->dehydrated()
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
                ->modalFooterActionsAlignment(Alignment::End)
                ->modalFooterActions(fn ($action) => [
                    Action::make('cancelar')
                        ->label('Cancelar')
                        ->color('gray')
                        ->close(),
                    Action::make('borrador')
                        ->label('💾 Guardar Borrador')
                        ->color('warning')
                        ->action(function () use ($action) {
                            $data = $action->getFormData();
                            Notification::make()
                                ->title('📝 Borrador guardado')
                                ->body('Cotización ' . ($data['numero_cotizacion'] ?? 'N/A') . ' guardada como borrador.')
                                ->warning()
                                ->send();
                        }),
                    Action::make('enviar')
                        ->label('📧 Enviar por Correo')
                        ->color('success')
                        ->icon('heroicon-o-envelope')
                        ->action(function () {
                            // Usar los datos guardados en afterFormValidated
                            $data = $this->cotizacionData;
                            
                            // Si está vacío, mostrar error
                            if (empty($data)) {
                                Notification::make()
                                    ->title('❌ Error')
                                    ->body('No se pudieron obtener los datos del formulario. Por favor, completa el formulario y vuelve a intentar.')
                                    ->danger()
                                    ->send();
                                return;
                            }
                            
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
                                    'cliente_id' => $this->record->id ?? null,
                                    'cliente_nombre' => (string) ($this->record->nombre_empresa ?? ''),
                                    'cliente_correo' => (string) ($this->record->correo ?? ''),
                                    'timestamp' => now()->toIso8601String(),
                                ];

                                // Enviar email con la cotización
                                $correoDestino = $data['correo'] ?? '';
                                
                                if (empty($correoDestino)) {
                                    Notification::make()
                                        ->title('❌ Error')
                                        ->body('No se especificó un correo electrónico para enviar la cotización. Datos recibidos: ' . json_encode($data))
                                        ->danger()
                                        ->send();
                                    return;
                                }
                                
                                try {
                                    // Crear la cotización si no existe
                                    $cotizacion = \App\Models\Cotizacion::create([
                                        'numero_cotizacion' => $data['numero_cotizacion'] ?? 'COT-' . date('Ymd') . '-' . rand(100, 999),
                                        'fecha' => $fecha,
                                        'idioma' => $data['idioma'] ?? 'es',
                                        'cliente_id' => $this->record->id ?? null,
                                        'tipo_servicio' => $data['tipo_servicio'] ?? '',
                                        'plan' => $data['plan'] ?? '',
                                        'monto' => $data['monto'] ?? 0,
                                        'vigencia' => $data['vigencia'] ?? '15',
                                        'correo' => $correoDestino,
                                        'descripcion' => $data['descripcion'] ?? '',
                                        'estado' => 'enviada',
                                    ]);
                                    
                                    Mail::to($correoDestino)->send(new CotizacionMail($emailData));
                                    
                                    // Marcar como enviada
                                    $cotizacion->enviada_at = now();
                                    $cotizacion->save();
                                    
                                    Notification::make()
                                        ->title('✅ Cotización enviada')
                                        ->body('Cotización ' . ($data['numero_cotizacion'] ?? 'N/A') . ' enviada por email a ' . $correoDestino)
                                        ->success()
                                        ->send();
                                } catch (\Exception $mailException) {
                                    Notification::make()
                                        ->title('❌ Error al enviar email')
                                        ->body('No se pudo enviar el email: ' . $mailException->getMessage())
                                        ->danger()
                                        ->send();
                                }
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('❌ Error inesperado')
                                    ->body('Error al procesar la cotización: ' . $e->getMessage())
                                    ->danger()
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
                            ->label('Subtotal (₡)')
                            ->numeric()
                            ->prefix('₡')
                            ->step(0.01)
                            ->helperText('Se calcula automáticamente si introduces el total'),
                        Forms\Components\TextInput::make('total')
                            ->label('Total (₡) - Incluye impuestos')
                            ->numeric()
                            ->prefix('₡')
                            ->required()
                            ->step(0.01)
                            ->live()
                            ->helperText('Total final que incluye todos los impuestos')
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                // Si se introduce el total y no hay subtotal, calcular el subtotal
                                if ($state && empty($get('subtotal'))) {
                                    $subtotal = round($state / 1.13, 2);
                                    $set('subtotal', $subtotal);
                                }
                            })
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

            Action::make('agregar_nota')
                ->label('Agregar Nota')
                ->icon('heroicon-o-pencil-square')
                ->color('primary')
                ->form([
                    Forms\Components\Textarea::make('content')
                        ->label('Contenido de la nota')
                        ->required()
                        ->rows(5)
                        ->placeholder('Escribe tu nota aquí...')
                        ->maxLength(5000),
                    Forms\Components\Select::make('type')
                        ->label('Tipo')
                        ->options([
                            'note' => 'Nota',
                            'call' => 'Llamada',
                            'meeting' => 'Reunión',
                            'email' => 'Email',
                        ])
                        ->default('note')
                        ->required(),
                ])
                ->action(function (array $data) {
                    $cliente = $this->record;
                    
                    Note::create([
                        'cliente_id' => $cliente->id,
                        'content' => $data['content'],
                        'type' => $data['type'],
                        'user_id' => auth()->id(),
                    ]);

                    // Recargar la relación de notas
                    $this->record->refresh();
                    $this->record->load('notes.user');

                    Notification::make()
                        ->title('Nota agregada')
                        ->body('La nota se ha guardado correctamente.')
                        ->success()
                        ->send();
                }),
        ];
    }
}

