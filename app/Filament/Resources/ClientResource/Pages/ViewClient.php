<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use App\Filament\Pages\EmailComposer;
use App\Filament\Pages\HistorialPage;
use App\Filament\Resources\FacturaResource;
use App\Filament\Resources\CotizacionResource;
use App\Mail\CotizacionMail;
use App\Models\Note;
use App\Models\Client;
use App\Models\Factura;
use App\Models\Cotizacion;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ViewClient extends ViewRecord implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = ClientResource::class;
    
    protected static string $view = 'filament.resources.client-resource.pages.view-client';

    public function getMaxContentWidth(): MaxWidth | string | null
    {
        return MaxWidth::Full;
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);
        $this->record->load('notes.user');
    }
    
    public function table(Table $table): Table
    {
        $clientId = $this->record->id;
        
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
        
        // Envolver en una subquery para poder ordenar
        $unifiedQuery = Note::query()
            ->fromSub($notesQuery, 'unified')
            ->orderBy('created_at', 'desc')
            ->select('unified.*');

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
                    ->sortable()
                    ->formatStateUsing(function ($state, $record) {
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
                        'note' => 'gray',
                        'call' => 'primary',
                        'meeting' => 'info',
                        'email' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'note' => 'Nota',
                        'call' => 'Llamada',
                        'meeting' => 'Reunión',
                        'email' => 'Email',
                        default => $state,
                    }),
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
                        
                        // Nota - enlace al view de la nota
                        if ($recordType === 'note' && $recordId) {
                            $url = \App\Filament\Resources\NoteResource::getUrl('view', ['record' => $recordId]);
                            $contentHtml = '<div class="whitespace-pre-wrap">' . nl2br(e($state)) . '</div>';
                            return '<a href="' . $url . '" class="text-primary-600 dark:text-primary-400 hover:underline">' . $contentHtml . '</a>';
                        }
                        
                        return '<div class="whitespace-pre-wrap">' . nl2br(e($state)) . '</div>';
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('No hay actividades registradas')
            ->emptyStateDescription('Las actividades de este cliente aparecerán aquí.')
            ->emptyStateIcon('heroicon-o-document-text');
    }

    /**
     * Botones de acción.
     */
    protected array $cotizacionData = [];

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('crear_cotizacion')
                ->label('Crear Cotización')
                ->icon('heroicon-o-document-text')
                ->color('gray')
                ->modalHeading('📝 Nueva Cotización')
                ->modalWidth('4xl')
                ->afterFormValidated(function (array $data, $action) {
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
                            ->default(fn () => $this->record->email)
                            ->required(),
                    ]),
                    Forms\Components\Textarea::make('descripcion')
                        ->label('Descripción / Servicios incluidos')
                        ->rows(2)
                        ->columnSpanFull(),
                ])
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
                            Notification::make()
                                ->title('📝 Borrador guardado')
                                ->body('Cotización ' . ($data['numero_cotizacion'] ?? 'N/A') . ' guardada como borrador.')
                                ->warning()
                                ->send();
                        }),
                    Actions\Action::make('enviar')
                        ->label('📧 Enviar por Correo')
                        ->color('success')
                        ->icon('heroicon-o-envelope')
                        ->action(function () {
                            $data = $this->cotizacionData;
                            
                            if (empty($data)) {
                                Notification::make()
                                    ->title('❌ Error')
                                    ->body('No se pudieron obtener los datos del formulario.')
                                    ->danger()
                                    ->send();
                                return;
                            }
                            
                            try {
                                $fecha = $data['fecha'] ?? '';
                                if ($fecha instanceof \Carbon\Carbon) {
                                    $fecha = $fecha->format('Y-m-d');
                                }
                                
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
                                    'cliente_id' => null,
                                    'cliente_nombre' => (string) ($this->record->name ?? ''),
                                    'cliente_correo' => (string) ($this->record->email ?? ''),
                                    'timestamp' => now()->toIso8601String(),
                                ];

                                $correoDestino = $data['correo'] ?? '';
                                
                                if (empty($correoDestino)) {
                                    Notification::make()
                                        ->title('❌ Error')
                                        ->body('No se especificó un correo electrónico.')
                                        ->danger()
                                        ->send();
                                    return;
                                }
                                
                                $cotizacion = Cotizacion::create([
                                    'numero_cotizacion' => $data['numero_cotizacion'] ?? 'COT-' . date('Ymd') . '-' . rand(100, 999),
                                    'fecha' => $fecha,
                                    'idioma' => $data['idioma'] ?? 'es',
                                    'cliente_id' => null,
                                    'tipo_servicio' => $data['tipo_servicio'] ?? '',
                                    'plan' => $data['plan'] ?? '',
                                    'monto' => $data['monto'] ?? 0,
                                    'vigencia' => $data['vigencia'] ?? '15',
                                    'correo' => $correoDestino,
                                    'descripcion' => $data['descripcion'] ?? '',
                                    'estado' => 'enviada',
                                ]);
                                
                                Mail::to($correoDestino)->send(new CotizacionMail($emailData));
                                
                                $cotizacion->enviada_at = now();
                                $cotizacion->save();
                                
                                Notification::make()
                                    ->title('✅ Cotización enviada')
                                    ->body('Cotización ' . ($data['numero_cotizacion'] ?? 'N/A') . ' enviada por email a ' . $correoDestino)
                                    ->success()
                                    ->send();
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('❌ Error')
                                    ->body('Error al procesar la cotización: ' . $e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),
                ]),
            Actions\Action::make('crear_factura')
                ->label('Crear Factura')
                ->icon('heroicon-o-banknotes')
                ->color('gray')
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
                            ->default(fn () => $this->record->email)
                            ->required(),
                    ]),
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
                            Notification::make()
                                ->title('✅ Factura enviada')
                                ->body('Factura ' . $data['numero_factura'] . ' enviada a ' . $data['correo'])
                                ->success()
                                ->send();
                        }),
                ]),
            Actions\Action::make('redactar_email')
                ->label('Redactar Email')
                ->icon('heroicon-o-envelope')
                ->color('gray')
                ->url(fn () => EmailComposer::getUrl())
                ->openUrlInNewTab(),
            Actions\Action::make('agregar_nota')
                ->label('Agregar Nota')
                ->icon('heroicon-o-pencil-square')
                ->color('gray')
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
                    $client = $this->record;
                    
                    Note::create([
                        'client_id' => $client->id,
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
