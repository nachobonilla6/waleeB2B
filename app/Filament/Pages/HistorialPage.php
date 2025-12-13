<?php

namespace App\Filament\Pages;

use App\Models\Note;
use App\Models\Client;
use App\Models\Factura;
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
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;

class HistorialPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Historial';
    protected static ?string $title = 'Historial de Notas';
    protected static ?string $navigationGroup = 'Clientes';
    protected static ?int $navigationSort = 121;

    protected static string $view = 'filament.pages.historial-page';

    public function table(Table $table): Table
    {
        return $table
            ->query(Note::query()->with(['client', 'cliente', 'user'])->orderBy('created_at', 'desc'))
            ->columns([
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
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Creado por')
                    ->placeholder('Sistema')
                    ->sortable(),
                Tables\Columns\TextColumn::make('content')
                    ->label('Nota')
                    ->wrap()
                    ->searchable()
                    ->extraAttributes(fn ($record) => [
                        'class' => 'min-h-[4.5rem] py-3',
                        'style' => 'min-height: 4.5rem; padding-top: 0.75rem; padding-bottom: 0.75rem;',
                    ])
                    ->html()
                    ->formatStateUsing(fn ($state) => '<div class="whitespace-pre-wrap">' . nl2br(e($state)) . '</div>'),
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->placeholder(fn ($record) => $record->cliente?->nombre_empresa ?? 'N/A')
                    ->getStateUsing(fn ($record) => $record->client?->name ?? $record->cliente?->nombre_empresa ?? 'N/A')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipo')
                    ->options([
                        'note' => 'Nota',
                        'call' => 'Llamada',
                        'meeting' => 'Reunión',
                        'email' => 'Email',
                    ]),
                Tables\Filters\SelectFilter::make('client_id')
                    ->label('Cliente')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->preload(),
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
                        'type' => $data['type'],
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

                    Notification::make()
                        ->title('Factura creada')
                        ->body('La factura se ha creado correctamente.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
