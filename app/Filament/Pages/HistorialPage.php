<?php

namespace App\Filament\Pages;

use App\Models\Note;
use App\Models\Client;
use App\Models\Factura;
use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\PropuestaPersonalizada;
use Illuminate\Support\Facades\DB;
use App\Filament\Resources\FacturaResource;
use App\Filament\Resources\CotizacionResource;
use App\Filament\Resources\NoteResource;
use Filament\Pages\Page;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\Wizard\Step;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;

class HistorialPage extends Page implements HasTable
{
    use InteractsWithTable;

    public $mountedTableActionRecord = null;
    public $mountedTableActionRecordType = null;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Registro de Actividades';
    protected static ?string $title = 'Registro de Actividades';
    protected static ?string $navigationGroup = 'Herramientas';
    protected static ?int $navigationSort = 121;

    protected static string $view = 'filament.pages.historial-page';

    public static function getNavigationBadge(): ?string
    {
        try {
            $notesCount = Note::count();
            $facturasCount = Factura::count();
            $cotizacionesCount = Cotizacion::count();
            $propuestasPersonalizadasCount = PropuestaPersonalizada::count();
            $total = $notesCount + $facturasCount + $cotizacionesCount + $propuestasPersonalizadasCount;
            return $total > 0 ? (string) $total : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getMaxContentWidth(): MaxWidth | string | null
    {
        return MaxWidth::Full;
    }

    public function table(Table $table): Table
    {
        // Usar un query builder de Eloquent con union
        $notesQuery = Note::query()
            ->select([
                'notes.id',
                'notes.client_id',
                'notes.cliente_id',
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

        $propuestasQuery = Client::query()
            ->where(function ($q) {
                $q->where('estado', 'propuesta_enviada')
                  ->orWhere('propuesta_enviada', true);
            })
            ->whereNotNull('propuesta')
            ->select([
                'clientes_en_proceso.id',
                'clientes_en_proceso.id as client_id',
                DB::raw("NULL as cliente_id"),
                'clientes_en_proceso.propuesta as content',
                DB::raw("'propuesta_enviada' as type"),
                DB::raw("NULL as user_id"),
                'clientes_en_proceso.updated_at as created_at',
                'clientes_en_proceso.updated_at',
                DB::raw("'propuesta' as record_type"),
                'clientes_en_proceso.propuesta',
                'clientes_en_proceso.name',
                DB::raw("NULL as enlace"),
            ]);

        // Query para facturas creadas
        $facturasCreadasQuery = Factura::query()
            ->select([
                'facturas.id',
                DB::raw("NULL as client_id"),
                'facturas.cliente_id',
                DB::raw("CONCAT('Factura ', facturas.numero_factura, ' - ', facturas.concepto, ' - Total: $', facturas.total) as content"),
                DB::raw("'factura_creada' as type"),
                DB::raw("NULL as user_id"),
                'facturas.created_at',
                'facturas.updated_at',
                DB::raw("'factura' as record_type"),
                DB::raw("NULL as propuesta"),
                DB::raw("NULL as name"),
                'facturas.enlace',
            ]);

        // Query para facturas editadas (updated_at != created_at y no es por enviada_at)
        $facturasEditadasQuery = Factura::query()
            ->whereRaw('facturas.updated_at != facturas.created_at')
            ->where(function($q) {
                $q->whereNull('facturas.enviada_at')
                  ->orWhereRaw('facturas.updated_at != facturas.enviada_at');
            })
            ->select([
                'facturas.id',
                DB::raw("NULL as client_id"),
                'facturas.cliente_id',
                DB::raw("CONCAT('Factura ', facturas.numero_factura, ' - ', facturas.concepto, ' - Total: $', facturas.total) as content"),
                DB::raw("'factura_editada' as type"),
                DB::raw("NULL as user_id"),
                'facturas.updated_at as created_at',
                'facturas.updated_at',
                DB::raw("'factura' as record_type"),
                DB::raw("NULL as propuesta"),
                DB::raw("NULL as name"),
                'facturas.enlace',
            ]);

        // Query para facturas enviadas (las que tienen enviada_at)
        $facturasEnviadasQuery = Factura::query()
            ->whereNotNull('enviada_at')
            ->select([
                'facturas.id',
                DB::raw("NULL as client_id"),
                'facturas.cliente_id',
                DB::raw("CONCAT('Factura ', facturas.numero_factura, ' - ', facturas.concepto, ' - Total: $', facturas.total) as content"),
                DB::raw("'factura_enviada' as type"),
                DB::raw("NULL as user_id"),
                'facturas.enviada_at as created_at',
                'facturas.updated_at',
                DB::raw("'factura' as record_type"),
                DB::raw("NULL as propuesta"),
                DB::raw("NULL as name"),
                'facturas.enlace',
            ]);

        // Query para cotizaciones creadas
        $cotizacionesCreadasQuery = Cotizacion::query()
            ->select([
                'cotizacions.id',
                DB::raw("NULL as client_id"),
                'cotizacions.cliente_id',
                DB::raw("CONCAT('Cotización ', cotizacions.numero_cotizacion, ' - ', cotizacions.tipo_servicio, ' - ', cotizacions.plan, ' - Monto: $', cotizacions.monto) as content"),
                DB::raw("'cotizacion_creada' as type"),
                DB::raw("NULL as user_id"),
                'cotizacions.created_at',
                'cotizacions.updated_at',
                DB::raw("'cotizacion' as record_type"),
                DB::raw("NULL as propuesta"),
                DB::raw("NULL as name"),
                DB::raw("NULL as enlace"),
            ]);

        // Query para cotizaciones editadas (updated_at != created_at)
        $cotizacionesEditadasQuery = Cotizacion::query()
            ->whereRaw('cotizacions.updated_at != cotizacions.created_at')
            ->where(function($q) {
                $q->whereNull('cotizacions.enviada_at')
                  ->orWhereRaw('cotizacions.updated_at != cotizacions.enviada_at');
            })
            ->select([
                'cotizacions.id',
                DB::raw("NULL as client_id"),
                'cotizacions.cliente_id',
                DB::raw("CONCAT('Cotización ', cotizacions.numero_cotizacion, ' - ', cotizacions.tipo_servicio, ' - ', cotizacions.plan, ' - Monto: $', cotizacions.monto) as content"),
                DB::raw("'cotizacion_editada' as type"),
                DB::raw("NULL as user_id"),
                'cotizacions.updated_at as created_at',
                'cotizacions.updated_at',
                DB::raw("'cotizacion' as record_type"),
                DB::raw("NULL as propuesta"),
                DB::raw("NULL as name"),
                DB::raw("NULL as enlace"),
            ]);

        // Query para cotizaciones enviadas (las que tienen enviada_at o estado = 'enviada')
        $cotizacionesEnviadasQuery = Cotizacion::query()
            ->where(function($q) {
                $q->whereNotNull('enviada_at')
                  ->orWhere('estado', 'enviada');
            })
            ->select([
                'cotizacions.id',
                DB::raw("NULL as client_id"),
                'cotizacions.cliente_id',
                DB::raw("CONCAT('Cotización ', cotizacions.numero_cotizacion, ' - ', cotizacions.tipo_servicio, ' - ', cotizacions.plan, ' - Monto: $', cotizacions.monto) as content"),
                DB::raw("'cotizacion_enviada' as type"),
                DB::raw("NULL as user_id"),
                DB::raw("COALESCE(cotizacions.enviada_at, cotizacions.updated_at) as created_at"),
                'cotizacions.updated_at',
                DB::raw("'cotizacion' as record_type"),
                DB::raw("NULL as propuesta"),
                DB::raw("NULL as name"),
                DB::raw("NULL as enlace"),
            ]);

        // Crear la UNION y envolverla en una subquery para poder ordenar
        $unionQuery = $notesQuery
            ->unionAll($propuestasQuery)
            ->unionAll($facturasCreadasQuery)
            ->unionAll($facturasEditadasQuery)
            ->unionAll($facturasEnviadasQuery)
            ->unionAll($cotizacionesCreadasQuery)
            ->unionAll($cotizacionesEditadasQuery)
            ->unionAll($cotizacionesEnviadasQuery);
        
        // Query para propuestas personalizadas
        $propuestasPersonalizadasQuery = PropuestaPersonalizada::query()
            ->select([
                'propuestas_personalizadas.id',
                'propuestas_personalizadas.cliente_id as client_id',
                DB::raw("NULL as cliente_id"),
                DB::raw("CONCAT('Propuesta Personalizada enviada a ', propuestas_personalizadas.email, ' - Asunto: ', propuestas_personalizadas.subject) as content"),
                DB::raw("'propuesta_personalizada' as type"),
                'propuestas_personalizadas.user_id',
                'propuestas_personalizadas.created_at',
                'propuestas_personalizadas.updated_at',
                DB::raw("'propuesta_personalizada' as record_type"),
                DB::raw("NULL as propuesta"),
                'propuestas_personalizadas.cliente_nombre as name',
                DB::raw("NULL as enlace"),
            ]);
        
        // Agregar propuestas personalizadas al union
        $unionQuery = $unionQuery->unionAll($propuestasPersonalizadasQuery);
        
        // Envolver en una subquery usando fromSub para poder ordenar
        // Ordenar por created_at (desc)
        $unifiedQuery = Note::query()
            ->fromSub($unionQuery, 'unified')
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
                        if (isset($record->record_type) && ($record->record_type === 'propuesta' || $record->record_type === 'factura' || $record->record_type === 'cotizacion' || $record->record_type === 'propuesta_personalizada')) {
                            // Para propuestas personalizadas, mostrar el usuario si existe
                            if ($record->record_type === 'propuesta_personalizada' && $state) {
                                $user = \App\Models\User::find($state);
                                return $user?->name ?? 'Sistema';
                            }
                            return 'Sistema';
                        }
                        if ($state) {
                            $user = \App\Models\User::find($state);
                            return $user?->name ?? 'Sistema';
                        }
                        return 'Sistema';
                    }),
                Tables\Columns\TextColumn::make('cliente_nombre')
                    ->label('Cliente')
                    ->searchable(false)
                    ->sortable(false)
                    ->weight('bold')
                    ->getStateUsing(function ($record) {
                        // Acceso seguro a propiedades (funciona con objetos y arrays)
                        $getValue = function($key) use ($record) {
                            if (is_array($record)) {
                                return $record[$key] ?? null;
                            }
                            return $record->$key ?? null;
                        };
                        
                        $recordType = $getValue('record_type');
                        
                        // Propuestas
                        if ($recordType === 'propuesta') {
                            return $getValue('name') ?? 'N/A';
                        }
                        
                        // Propuestas Personalizadas
                        if ($recordType === 'propuesta_personalizada') {
                            return $getValue('name') ?? 'N/A';
                        }
                        
                        // Facturas y Cotizaciones
                        if ($recordType === 'factura' || $recordType === 'cotizacion') {
                            $clienteId = $getValue('cliente_id');
                            if ($clienteId) {
                                try {
                                    $cliente = Cliente::find($clienteId);
                                    return $cliente?->nombre_empresa ?? 'N/A';
                                } catch (\Exception $e) {
                                    \Log::error('Error getting cliente for factura/cotizacion', [
                                        'cliente_id' => $clienteId,
                                        'error' => $e->getMessage()
                                    ]);
                                    return 'N/A';
                                }
                            }
                            return 'N/A';
                        }
                        
                        // Notas - pueden tener client_id (Client) o cliente_id (Cliente)
                        $clientId = $getValue('client_id');
                        if ($clientId) {
                            try {
                                $client = Client::find($clientId);
                                if ($client) {
                                    return $client->name ?? 'N/A';
                                }
                            } catch (\Exception $e) {
                                // Continuar con cliente_id
                            }
                        }
                        
                        $clienteId = $getValue('cliente_id');
                        if ($clienteId) {
                            try {
                                $cliente = Cliente::find($clienteId);
                                return $cliente?->nombre_empresa ?? 'N/A';
                            } catch (\Exception $e) {
                                return 'N/A';
                            }
                        }
                        
                        return 'N/A';
                    }),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'note' => 'warning',
                        'call' => 'primary',
                        'meeting' => 'info',
                        'email' => 'success',
                        'propuesta_enviada' => 'success',
                        'propuesta_personalizada' => 'success',
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
                        'propuesta_personalizada' => '📧 Propuesta Personalizada',
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
                    ->extraAttributes(fn ($record) => [
                        'class' => 'min-h-[4.5rem] py-3',
                        'style' => 'min-height: 4.5rem; padding-top: 0.75rem; padding-bottom: 0.75rem;',
                    ])
                    ->html()
                    ->formatStateUsing(function ($state, $record) {
                        $content = $state ?? '';
                        $getValue = function($key) use ($record) {
                            if (is_array($record)) {
                                return $record[$key] ?? null;
                            }
                            return $record->$key ?? null;
                        };
                        
                        $recordType = $getValue('record_type');
                        $recordId = $getValue('id');
                        $type = $getValue('type');
                        
                        // Factura creada - enlace al view
                        if ($recordType === 'factura' && $type === 'factura_creada' && $recordId) {
                            $url = FacturaResource::getUrl('view', ['record' => $recordId]);
                            $contentHtml = '<div class="whitespace-pre-wrap font-semibold text-success-600 dark:text-success-400">' . e($content) . '</div>';
                            return '<a href="' . $url . '" class="text-success-600 dark:text-success-400 hover:underline">' . $contentHtml . '</a>';
                        }
                        
                        // Factura enviada - enlace al view
                        if ($recordType === 'factura' && $type === 'factura_enviada' && $recordId) {
                            $url = FacturaResource::getUrl('view', ['record' => $recordId]);
                            $contentHtml = '<div class="whitespace-pre-wrap font-semibold text-success-600 dark:text-success-400">' . e($content) . '</div>';
                            return '<a href="' . $url . '" class="text-success-600 dark:text-success-400 hover:underline">' . $contentHtml . '</a>';
                        }
                        
                        // Factura editada - enlace al view
                        if ($recordType === 'factura' && $type === 'factura_editada' && $recordId) {
                            $url = FacturaResource::getUrl('view', ['record' => $recordId]);
                            $contentHtml = '<div class="whitespace-pre-wrap font-semibold text-success-600 dark:text-success-400">' . e($content) . '</div>';
                            return '<a href="' . $url . '" class="text-success-600 dark:text-success-400 hover:underline">' . $contentHtml . '</a>';
                        }
                        
                        // Cotización creada - enlace al view
                        if ($recordType === 'cotizacion' && $type === 'cotizacion_creada' && $recordId) {
                            $url = CotizacionResource::getUrl('view', ['record' => $recordId]);
                            $contentHtml = '<div class="whitespace-pre-wrap font-semibold text-success-600 dark:text-success-400">' . e($content) . '</div>';
                            return '<a href="' . $url . '" class="text-success-600 dark:text-success-400 hover:underline">' . $contentHtml . '</a>';
                        }
                        
                        // Cotización editada - enlace al edit
                        if ($recordType === 'cotizacion' && $type === 'cotizacion_editada' && $recordId) {
                            $url = CotizacionResource::getUrl('edit', ['record' => $recordId]);
                            $contentHtml = '<div class="whitespace-pre-wrap font-semibold text-success-600 dark:text-success-400">' . e($content) . '</div>';
                            return '<a href="' . $url . '" class="text-success-600 dark:text-success-400 hover:underline">' . $contentHtml . '</a>';
                        }
                        
                        // Cotización enviada - enlace al view
                        if ($recordType === 'cotizacion' && $type === 'cotizacion_enviada' && $recordId) {
                            $url = CotizacionResource::getUrl('view', ['record' => $recordId]);
                            $contentHtml = '<div class="whitespace-pre-wrap font-semibold text-success-600 dark:text-success-400">' . e($content) . '</div>';
                            return '<a href="' . $url . '" class="text-success-600 dark:text-success-400 hover:underline">' . $contentHtml . '</a>';
                        }
                        
                        // Nota - enlace al view de la nota
                        if ($recordType === 'note' && $recordId) {
                            $url = NoteResource::getUrl('view', ['record' => $recordId]);
                            $contentHtml = '<div class="whitespace-pre-wrap text-success-600 dark:text-success-400">' . nl2br(e($content)) . '</div>';
                            return '<a href="' . $url . '" class="text-success-600 dark:text-success-400 hover:underline">' . $contentHtml . '</a>';
                        }
                        
                        // Propuesta Personalizada - mostrar contenido
                        if ($recordType === 'propuesta_personalizada') {
                            return '<div class="whitespace-pre-wrap font-semibold text-success-600 dark:text-success-400">' . e($content) . '</div>';
                        }
                        
                        // Por defecto, sin enlace
                        if (isset($record->record_type) && ($record->record_type === 'factura' || $record->record_type === 'cotizacion')) {
                            return '<div class="whitespace-pre-wrap font-semibold text-success-600 dark:text-success-400">' . e($content) . '</div>';
                        }
                        return '<div class="whitespace-pre-wrap text-success-600 dark:text-success-400">' . nl2br(e($content)) . '</div>';
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipo')
                    ->options([
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
                        'propuesta_personalizada' => '📧 Propuesta Personalizada',
                    ]),
                Tables\Filters\SelectFilter::make('client_id')
                    ->label('Cliente')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->label('Borrar')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('¿Borrar registro?')
                    ->modalDescription('¿Estás seguro de que deseas borrar este registro? Esta acción no se puede deshacer.')
                    ->action(function ($record) {
                        try {
                            $recordType = is_array($record) ? ($record['record_type'] ?? null) : ($record->record_type ?? null);
                            $recordId = is_array($record) ? ($record['id'] ?? null) : ($record->id ?? null);
                            
                            if (!$recordType || !$recordId) {
                                throw new \Exception('No se pudo identificar el tipo de registro');
                            }
                            
                            switch ($recordType) {
                                case 'note':
                                    Note::find($recordId)?->delete();
                                    break;
                                case 'factura':
                                    Factura::find($recordId)?->delete();
                                    break;
                                case 'cotizacion':
                                    Cotizacion::find($recordId)?->delete();
                                    break;
                                case 'propuesta':
                                    Client::find($recordId)?->update(['propuesta' => null, 'propuesta_enviada' => false]);
                                    break;
                                default:
                                    throw new \Exception('Tipo de registro no soportado para borrar');
                            }
                            
                            Notification::make()
                                ->title('Registro borrado')
                                ->body('El registro ha sido borrado exitosamente.')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Error al borrar')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('crear_nota')
                ->label('Crear Nota')
                ->icon('heroicon-o-pencil-square')
                ->color('primary')
                ->form([
                    Forms\Components\Select::make('tipo_cliente')
                        ->label('Tipo de Cliente')
                        ->options([
                            'client' => 'Cliente Google (clientes_en_proceso)',
                            'cliente' => 'Cliente Activo (clientes)',
                        ])
                        ->required()
                        ->live()
                        ->default('client'),
                    Forms\Components\Select::make('client_id')
                        ->label('Cliente Google')
                        ->options(Client::pluck('name', 'id'))
                        ->searchable()
                        ->required()
                        ->visible(fn (Forms\Get $get) => $get('tipo_cliente') === 'client'),
                    Forms\Components\Select::make('cliente_id')
                        ->label('Cliente Activo')
                        ->options(Cliente::pluck('nombre_empresa', 'id'))
                        ->searchable()
                        ->required()
                        ->visible(fn (Forms\Get $get) => $get('tipo_cliente') === 'cliente'),
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
                    $noteData = [
                        'content' => $data['content'],
                        'type' => 'note',
                        'user_id' => auth()->id(),
                    ];

                    if ($data['tipo_cliente'] === 'client' && isset($data['client_id'])) {
                        $noteData['client_id'] = $data['client_id'];
                    } elseif ($data['tipo_cliente'] === 'cliente' && isset($data['cliente_id'])) {
                        $noteData['cliente_id'] = $data['cliente_id'];
                    }

                    Note::create($noteData);

                    Notification::make()
                        ->title('Nota creada')
                        ->body('La nota se ha guardado correctamente.')
                        ->success()
                        ->send();
                }),
            Actions\CreateAction::make()
                ->label('Crear Factura')
                ->icon('heroicon-o-banknotes')
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
                                    ->label('Subtotal (₡)')
                                    ->numeric()
                                    ->prefix('₡')
                                    ->step(0.01)
                                    ->helperText('Opcional: Subtotal sin impuestos'),
                                Forms\Components\TextInput::make('total')
                                    ->label('Total (₡) - Incluye impuestos')
                                    ->numeric()
                                    ->prefix('₡')
                                    ->required()
                                    ->step(0.01)
                                    ->helperText('Total final que incluye todos los impuestos')
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
                            'https://n8n.srv1137974.hstgr.cloud/webhook-test/62cb26b6-1b4a-492b-8780-709ff47c81bf',
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

                    Notification::make()
                        ->title('Factura creada')
                        ->body('La factura se ha creado correctamente.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
