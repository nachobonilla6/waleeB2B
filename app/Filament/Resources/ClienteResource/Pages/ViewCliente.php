<?php

namespace App\Filament\Resources\ClienteResource\Pages;

use App\Filament\Resources\ClienteResource;
use App\Mail\CotizacionMail;
use App\Models\Note;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\Mail;

class ViewCliente extends Page
{
    use InteractsWithRecord;

    protected static string $resource = ClienteResource::class;

    protected static string $view = 'filament.resources.cliente-resource.pages.view-cliente';
    
    protected array $cotizacionData = [];

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $this->record->loadMissing(['facturas', 'notes.user']);
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
                                    Mail::to($correoDestino)->send(new CotizacionMail($emailData));
                                    
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

