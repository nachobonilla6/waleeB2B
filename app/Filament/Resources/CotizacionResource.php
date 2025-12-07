<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CotizacionResource\Pages;
use App\Filament\Resources\CotizacionResource\RelationManagers;
use App\Models\Cotizacion;
use App\Models\Cliente;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Number;

class CotizacionResource extends Resource
{
    protected static ?string $model = Cotizacion::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Cotizaciones';
    protected static ?string $navigationGroup = 'Administración';
    protected static ?int $navigationSort = 2;
    protected static ?string $modelLabel = 'Cotización';
    protected static ?string $pluralModelLabel = 'Cotizaciones';
    protected static bool $shouldRegisterNavigation = false;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Información Básica')
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
                    Forms\Components\Wizard\Step::make('Detalles del Servicio')
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
                    Forms\Components\Wizard\Step::make('Contacto y Estado')
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
                ->columnSpanFull()
                ->submitAction(Forms\Components\Actions\Action::make('submit')
                    ->label('Crear Cotización')
                    ->icon('heroicon-o-check')
                ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('numero_cotizacion')
                    ->label('Nº Cotización')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('fecha')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cliente.nombre_empresa')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipo_servicio')
                    ->label('Tipo de Servicio')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'diseno_web' => 'Diseño Web',
                        'redes_sociales' => 'Gestión Redes Sociales',
                        'seo' => 'SEO / Posicionamiento',
                        'publicidad' => 'Publicidad Digital',
                        'mantenimiento' => 'Mantenimiento Web',
                        'hosting' => 'Hosting & Dominio',
                        'combo' => 'Paquete Completo',
                        default => $state,
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('monto')
                    ->label('Monto')
                    ->money('CRC', divideBy: 1)
                    ->sortable(),
                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pendiente' => 'warning',
                        'enviada' => 'info',
                        'aceptada' => 'success',
                        'rechazada' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pendiente' => 'Pendiente',
                        'enviada' => 'Enviada',
                        'aceptada' => 'Aceptada',
                        'rechazada' => 'Rechazada',
                        default => $state,
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'enviada' => 'Enviada',
                        'aceptada' => 'Aceptada',
                        'rechazada' => 'Rechazada',
                    ]),
                Tables\Filters\SelectFilter::make('tipo_servicio')
                    ->label('Tipo de Servicio')
                    ->options([
                        'diseno_web' => 'Diseño Web',
                        'redes_sociales' => 'Gestión Redes Sociales',
                        'seo' => 'SEO / Posicionamiento',
                        'publicidad' => 'Publicidad Digital',
                        'mantenimiento' => 'Mantenimiento Web',
                        'hosting' => 'Hosting & Dominio',
                        'combo' => 'Paquete Completo',
                    ]),
                Tables\Filters\Filter::make('fecha')
                    ->form([
                        Forms\Components\DatePicker::make('fecha_desde')
                            ->label('Fecha desde'),
                        Forms\Components\DatePicker::make('fecha_hasta')
                            ->label('Fecha hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['fecha_desde'],
                                fn (Builder $query, $date): Builder => $query->whereDate('fecha', '>=', $date),
                            )
                            ->when(
                                $data['fecha_hasta'],
                                fn (Builder $query, $date): Builder => $query->whereDate('fecha', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('enviar_email')
                    ->label('Enviar Email')
                    ->icon('heroicon-o-envelope')
                    ->color('success')
                    ->action(function (Cotizacion $record) {
                        // Aquí puedes agregar la lógica para enviar el email
                        \Filament\Notifications\Notification::make()
                            ->title('Email enviado')
                            ->body('La cotización ha sido enviada por correo electrónico.')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('fecha', 'desc');
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
            'index' => Pages\ListCotizacions::route('/'),
            'create' => Pages\CreateCotizacion::route('/create'),
            'edit' => Pages\EditCotizacion::route('/{record}/edit'),
        ];
    }
}
