<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FacturaResource\Pages;
use App\Filament\Resources\FacturaResource\RelationManagers;
use App\Models\Factura;
use App\Models\Cliente;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FacturaResource extends Resource
{
    protected static ?string $model = Factura::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Facturas';
    protected static ?string $navigationGroup = 'Administración';
    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'Factura';
    protected static ?string $pluralModelLabel = 'Facturas';
    protected static bool $shouldRegisterNavigation = false;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        $pendientes = static::getModel()::where('estado', 'pendiente')->count();
        return $pendientes > 0 ? 'success' : 'success';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Información Básica')
                ->icon('heroicon-o-information-circle')
                ->collapsible()
                ->collapsed(false)
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
                    Forms\Components\TextInput::make('correo')
                        ->label('Correo Electrónico')
                        ->email()
                        ->maxLength(255)
                        ->helperText('Correo donde se enviará la factura'),
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('numero_factura')
                            ->label('Nº Factura')
                            ->default(fn ($get) => $get('numero_factura') ?: 'FAC-' . date('Ymd') . '-' . rand(100, 999))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('fecha_emision')
                            ->label('Fecha Emisión')
                            ->default(fn ($get) => $get('fecha_emision') ?: now())
                            ->required()
                            ->displayFormat('d/m/Y'),
                    ]),
                ])
                ->columns(2),
            Forms\Components\Section::make('Detalles y Montos')
                ->icon('heroicon-o-currency-dollar')
                ->collapsible()
                ->collapsed(false)
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
                ])
                ->columns(2),
            Forms\Components\Section::make('Pago y Estado')
                ->icon('heroicon-o-banknotes')
                ->collapsible()
                ->collapsed(false)
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
                            ->default(fn ($get) => $get('estado') ?: 'pendiente')
                            ->required(),
                        Forms\Components\DatePicker::make('fecha_vencimiento')
                            ->label('Fecha Vencimiento')
                            ->displayFormat('d/m/Y'),
                    ]),
                    Forms\Components\Textarea::make('notas')
                        ->label('Notas')
                        ->rows(3)
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('numero_factura')
                    ->label('Nº Factura')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cliente.nombre_empresa')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_emision')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('concepto')
                    ->label('Concepto')
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'diseno_web' => '🌐 Diseño Web',
                        'redes_sociales' => '📱 Redes Sociales',
                        'seo' => '🔍 SEO',
                        'publicidad' => '📢 Publicidad',
                        'mantenimiento' => '🔧 Mantenimiento',
                        'hosting' => '☁️ Hosting',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'pendiente',
                        'success' => 'pagada',
                        'danger' => 'vencida',
                        'gray' => 'cancelada',
                    ]),
                Tables\Columns\TextColumn::make('metodo_pago')
                    ->label('Pago')
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'transferencia' => '🏦 Transferencia',
                        'sinpe' => '📲 SINPE',
                        'tarjeta' => '💳 Tarjeta',
                        'efectivo' => '💵 Efectivo',
                        'paypal' => '🅿️ PayPal',
                        default => $state,
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('enlace')
                    ->label('Ver Factura')
                    ->icon('heroicon-o-document')
                    ->url(fn ($record) => $record->enlace ?: null)
                    ->openUrlInNewTab()
                    ->color('primary')
                    ->formatStateUsing(fn ($state) => $state ? 'Ver Factura' : null),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'pagada' => 'Pagada',
                        'vencida' => 'Vencida',
                        'cancelada' => 'Cancelada',
                    ]),
                Tables\Filters\SelectFilter::make('cliente_id')
                    ->label('Cliente')
                    ->options(Cliente::pluck('nombre_empresa', 'id')),
            ])
            ->actions([
                Tables\Actions\Action::make('enviar_email')
                    ->label('Enviar Email')
                    ->icon('heroicon-o-envelope')
                    ->color('success')
                    ->action(function (Factura $record) {
                        // Obtener correo del cliente
                        $correoDestino = $record->correo ?? $record->cliente?->correo ?? null;
                        
                        if (empty($correoDestino)) {
                            \Filament\Notifications\Notification::make()
                                ->title('⚠️ No se puede enviar')
                                ->body('La factura no tiene un correo electrónico asociado.')
                                ->warning()
                                ->send();
                            return;
                        }
                        
                        // Preparar datos para el webhook
                        $webhookData = [
                            'numero_factura' => (string) ($record->numero_factura ?? ''),
                            'fecha_emision' => $record->fecha_emision ? $record->fecha_emision->format('Y-m-d') : '',
                            'concepto' => (string) ($record->concepto ?? ''),
                            'subtotal' => (string) ($record->subtotal ?? '0'),
                            'total' => (string) ($record->total ?? '0'),
                            'metodo_pago' => (string) ($record->metodo_pago ?? ''),
                            'estado' => (string) ($record->estado ?? ''),
                            'fecha_vencimiento' => $record->fecha_vencimiento ? $record->fecha_vencimiento->format('Y-m-d') : '',
                            'notas' => (string) ($record->notas ?? ''),
                            'cliente_id' => $record->cliente_id ?? null,
                            'cliente_nombre' => (string) ($record->cliente?->nombre_empresa ?? ''),
                            'cliente_correo' => (string) $correoDestino,
                            'factura_id' => $record->id ?? null,
                        ];
                        
                        try {
                            $response = \Illuminate\Support\Facades\Http::post(
                                'https://n8n.srv1137974.hstgr.cloud/webhook-test/62cb26b6-1b4a-492b-8780-709ff47c81bf',
                                $webhookData
                            );
                            
                            if ($response->successful()) {
                                \Filament\Notifications\Notification::make()
                                    ->title('✅ Factura enviada')
                                    ->body('La factura ha sido enviada al webhook correctamente')
                                    ->success()
                                    ->send();
                            } else {
                                throw new \Exception('Error en la respuesta del webhook: ' . $response->status());
                            }
                        } catch (\Exception $webhookException) {
                            \Log::error('Error enviando factura al webhook desde tabla', [
                                'error' => $webhookException->getMessage(),
                                'trace' => $webhookException->getTraceAsString(),
                                'correo' => $correoDestino,
                                'factura' => $record->numero_factura ?? 'N/A',
                            ]);
                            
                            \Filament\Notifications\Notification::make()
                                ->title('❌ Error al enviar factura')
                                ->body('No se pudo enviar la factura al webhook: ' . $webhookException->getMessage())
                                ->danger()
                                ->persistent()
                                ->send();
                        }
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Enviar factura por email')
                    ->modalDescription('¿Estás seguro de que deseas enviar esta factura por correo electrónico?')
                    ->modalSubmitActionLabel('Sí, enviar'),
                Tables\Actions\ViewAction::make()
                    ->modalHeading(fn (Factura $record) => 'Ver Factura: ' . $record->numero_factura)
                    ->modalWidth('4xl')
                    ->beforeFormFilled(function (Factura $record) {
                        // Cargar relaciones antes de mostrar el infolist
                        $record->loadMissing('cliente');
                    }),
                Tables\Actions\EditAction::make()
                    ->modalHeading(fn (Factura $record) => 'Editar Factura: ' . $record->numero_factura)
                    ->modalWidth('4xl')
                    ->mutateFormDataUsing(function (array $data, Factura $record): array {
                        // Asegurar que todos los datos se carguen correctamente
                        $record->loadMissing('cliente');
                        return array_merge($data, [
                            'cliente_id' => $record->cliente_id,
                            'correo' => $record->correo,
                            'numero_factura' => $record->numero_factura,
                            'fecha_emision' => $record->fecha_emision,
                            'concepto' => $record->concepto,
                            'subtotal' => $record->subtotal,
                            'total' => $record->total,
                            'metodo_pago' => $record->metodo_pago,
                            'estado' => $record->estado,
                            'fecha_vencimiento' => $record->fecha_vencimiento,
                            'notas' => $record->notas,
                        ]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Información Básica')
                    ->icon('heroicon-o-information-circle')
                    ->collapsible()
                    ->collapsed(false)
                    ->schema([
                        Infolists\Components\TextEntry::make('numero_factura')
                            ->label('Nº Factura')
                            ->weight('bold')
                            ->size('lg')
                            ->default('-'),
                        Infolists\Components\TextEntry::make('cliente.nombre_empresa')
                            ->label('Cliente')
                            ->default('-'),
                        Infolists\Components\TextEntry::make('correo')
                            ->label('Correo Electrónico')
                            ->url(fn ($record) => $record->correo ? 'mailto:' . $record->correo : null)
                            ->openUrlInNewTab()
                            ->default('-')
                            ->visible(fn ($record) => !empty($record->correo)),
                        Infolists\Components\TextEntry::make('fecha_emision')
                            ->label('Fecha de Emisión')
                            ->date('d/m/Y')
                            ->default('-'),
                        Infolists\Components\TextEntry::make('fecha_vencimiento')
                            ->label('Fecha de Vencimiento')
                            ->date('d/m/Y')
                            ->default('-')
                            ->visible(fn ($record) => !empty($record->fecha_vencimiento)),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make('Detalles y Montos')
                    ->icon('heroicon-o-currency-dollar')
                    ->collapsible()
                    ->collapsed(false)
                    ->schema([
                        Infolists\Components\TextEntry::make('concepto')
                            ->label('Concepto')
                            ->formatStateUsing(fn (?string $state): string => match($state) {
                                'diseno_web' => '🌐 Diseño Web',
                                'redes_sociales' => '📱 Gestión Redes Sociales',
                                'seo' => '🔍 SEO / Posicionamiento',
                                'publicidad' => '📢 Publicidad Digital',
                                'mantenimiento' => '🔧 Mantenimiento Mensual',
                                'hosting' => '☁️ Hosting & Dominio',
                                default => $state ?? '-',
                            }),
                        Infolists\Components\TextEntry::make('subtotal')
                            ->label('Subtotal (USD)')
                            ->money('USD')
                            ->size('lg')
                            ->default('0.00'),
                        Infolists\Components\TextEntry::make('total')
                            ->label('Total con IVA (13%)')
                            ->money('USD')
                            ->weight('bold')
                            ->size('lg')
                            ->color('success')
                            ->default('0.00'),
                    ])
                    ->columns(3),
                Infolists\Components\Section::make('Pago y Estado')
                    ->icon('heroicon-o-banknotes')
                    ->collapsible()
                    ->collapsed(false)
                    ->schema([
                        Infolists\Components\TextEntry::make('metodo_pago')
                            ->label('Método de Pago')
                            ->formatStateUsing(fn (?string $state): string => match($state) {
                                'transferencia' => '🏦 Transferencia Bancaria',
                                'sinpe' => '📲 SINPE Móvil',
                                'tarjeta' => '💳 Tarjeta de Crédito',
                                'efectivo' => '💵 Efectivo',
                                'paypal' => '🅿️ PayPal',
                                default => $state ?? '-',
                            }),
                        Infolists\Components\TextEntry::make('estado')
                            ->label('Estado')
                            ->badge()
                            ->formatStateUsing(fn (?string $state): string => match($state) {
                                'pendiente' => '🟡 Pendiente',
                                'pagada' => '🟢 Pagada',
                                'vencida' => '🔴 Vencida',
                                'cancelada' => '⚫ Cancelada',
                                default => $state ?? '-',
                            })
                            ->color(fn (?string $state): string => match($state) {
                                'pendiente' => 'warning',
                                'pagada' => 'success',
                                'vencida' => 'danger',
                                'cancelada' => 'gray',
                                default => 'gray',
                            }),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make('Notas Adicionales')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Infolists\Components\TextEntry::make('notas')
                            ->label('Notas')
                            ->columnSpanFull()
                            ->default('-')
                            ->visible(fn ($record) => !empty($record->notas)),
                    ])
                    ->visible(fn ($record) => !empty($record->notas)),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFacturas::route('/'),
            'create' => Pages\CreateFactura::route('/create'),
            'view' => Pages\ViewFactura::route('/{record}'),
            'edit' => Pages\EditFactura::route('/{record}/edit'),
        ];
    }
}
