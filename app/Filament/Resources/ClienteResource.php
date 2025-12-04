<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClienteResource\Pages;
use App\Filament\Resources\ClienteResource\RelationManagers;
use App\Models\Cliente;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Lista de Clientes';
    protected static ?string $navigationGroup = 'Clientes';
    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'Cliente';
    protected static ?string $pluralModelLabel = 'Clientes';
    
    // Búsqueda global
    protected static ?string $recordTitleAttribute = 'nombre_empresa';
    
    public static function getGloballySearchableAttributes(): array
    {
        return ['nombre_empresa', 'correo', 'telefono', 'ciudad', 'nombre_sitio'];
    }
    
    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'Email' => $record->correo,
            'Teléfono' => $record->telefono,
            'Ciudad' => $record->ciudad,
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    // Paso 1: Información de la Empresa
                    Forms\Components\Wizard\Step::make('Empresa')
                        ->icon('heroicon-o-building-office')
                        ->description('Datos básicos de la empresa')
                        ->schema([
                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\TextInput::make('nombre_empresa')
                                    ->label('Nombre de Empresa')
                                    ->placeholder('Ej: Web Solutions CR')
                                    ->required()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-building-office'),
                                Forms\Components\Select::make('estado_cuenta')
                                    ->label('Estado del Cliente')
                                    ->options([
                                        'activo' => '🟢 Activo',
                                        'pendiente' => '🟡 Pendiente',
                                        'suspendido' => '🔴 Suspendido',
                                        'cancelado' => '⚫ Cancelado',
                                    ])
                                    ->default('pendiente')
                                    ->native(false),
                            ]),
                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\Select::make('tipo_empresa')
                                    ->label('Tipo de Empresa')
                                    ->options([
                                        'servicios' => '🛎️ Servicios',
                                        'comercio' => '🛒 Comercio',
                                        'manufactura' => '🏭 Manufactura',
                                        'tecnologia' => '💻 Tecnología',
                                        'otro' => '📦 Otro',
                                    ])
                                    ->native(false),
                                Forms\Components\Select::make('industria')
                                    ->label('Industria')
                                    ->options([
                                        'turismo' => '✈️ Turismo',
                                        'gastronomia' => '🍽️ Gastronomía',
                                        'retail' => '🏪 Retail',
                                        'salud' => '🏥 Salud',
                                        'educacion' => '🎓 Educación',
                                        'tecnologia' => '💻 Tecnología',
                                        'otro' => '📦 Otro',
                                    ])
                                    ->native(false),
                            ]),
                            Forms\Components\Textarea::make('descripcion')
                                ->label('Descripción del Negocio')
                                ->placeholder('Breve descripción de la empresa y sus servicios...')
                                ->rows(3)
                                ->columnSpanFull(),
                        ]),

                    // Paso 2: Contacto
                    Forms\Components\Wizard\Step::make('Contacto')
                        ->icon('heroicon-o-phone')
                        ->description('Información de contacto')
                        ->schema([
                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\TextInput::make('correo')
                                    ->label('Correo Electrónico')
                                    ->email()
                                    ->required()
                                    ->placeholder('correo@empresa.com')
                                    ->prefixIcon('heroicon-o-envelope'),
                                Forms\Components\TextInput::make('telefono')
                                    ->label('Teléfono Principal')
                                    ->tel()
                                    ->placeholder('+506 8888-8888')
                                    ->prefixIcon('heroicon-o-phone'),
                            ]),
                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\TextInput::make('telefono_alternativo')
                                    ->label('Teléfono Alternativo')
                                    ->tel()
                                    ->placeholder('+506 2222-2222')
                                    ->prefixIcon('heroicon-o-phone'),
                                Forms\Components\TextInput::make('whatsapp')
                                    ->label('WhatsApp')
                                    ->tel()
                                    ->placeholder('+506 8888-8888')
                                    ->prefixIcon('heroicon-o-chat-bubble-left'),
                            ]),
                        ]),

                    // Paso 3: Ubicación
                    Forms\Components\Wizard\Step::make('Ubicación')
                        ->icon('heroicon-o-map-pin')
                        ->description('Dirección física')
                        ->schema([
                            Forms\Components\Grid::make(3)->schema([
                                Forms\Components\TextInput::make('pais')
                                    ->label('País')
                                    ->placeholder('Costa Rica')
                                    ->prefixIcon('heroicon-o-flag'),
                                Forms\Components\TextInput::make('estado')
                                    ->label('Provincia/Estado')
                                    ->placeholder('San José'),
                                Forms\Components\TextInput::make('ciudad')
                                    ->label('Ciudad')
                                    ->placeholder('San José'),
                            ]),
                            Forms\Components\TextInput::make('direccion')
                                ->label('Dirección Completa')
                                ->placeholder('Calle, Avenida, Número...')
                                ->prefixIcon('heroicon-o-home')
                                ->columnSpanFull(),
                            Forms\Components\TextInput::make('codigo_postal')
                                ->label('Código Postal')
                                ->placeholder('10101')
                                ->maxLength(10),
                        ]),

                    // Paso 4: Sitio Web
                    Forms\Components\Wizard\Step::make('Sitio Web')
                        ->icon('heroicon-o-globe-alt')
                        ->description('Información del sitio web')
                        ->schema([
                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\TextInput::make('nombre_sitio')
                                    ->label('Nombre del Dominio')
                                    ->placeholder('miempresa.com')
                                    ->prefixIcon('heroicon-o-globe-alt'),
                                Forms\Components\TextInput::make('url_sitio')
                                    ->label('URL del Sitio')
                                    ->url()
                                    ->placeholder('https://www.miempresa.com')
                                    ->prefixIcon('heroicon-o-link'),
                            ]),
                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\TextInput::make('hosting')
                                    ->label('Proveedor de Hosting')
                                    ->placeholder('Hostinger, GoDaddy, etc.')
                                    ->prefixIcon('heroicon-o-server'),
                                Forms\Components\DatePicker::make('dominio_expira')
                                    ->label('Fecha de Expiración')
                                    ->displayFormat('d/m/Y')
                                    ->prefixIcon('heroicon-o-calendar'),
                            ]),
                        ]),

                    // Paso 5: Fechas y Redes
                    Forms\Components\Wizard\Step::make('Fechas y Redes')
                        ->icon('heroicon-o-calendar')
                        ->description('Fechas importantes y redes sociales')
                        ->schema([
                            Forms\Components\Section::make('Fechas Importantes')
                                ->icon('heroicon-o-calendar-days')
                                ->collapsible()
                                ->schema([
                                    Forms\Components\Grid::make(4)->schema([
                                        Forms\Components\DatePicker::make('fecha_registro')
                                            ->label('Registro')
                                            ->displayFormat('d/m/Y'),
                                        Forms\Components\DatePicker::make('fecha_creacion')
                                            ->label('Creación')
                                            ->displayFormat('d/m/Y'),
                                        Forms\Components\DatePicker::make('fecha_cotizacion')
                                            ->label('Cotización')
                                            ->displayFormat('d/m/Y'),
                                        Forms\Components\DatePicker::make('fecha_factura')
                                            ->label('Factura')
                                            ->displayFormat('d/m/Y'),
                                    ]),
                                ]),
                            Forms\Components\Section::make('Redes Sociales')
                                ->icon('heroicon-o-share')
                                ->collapsible()
                                ->schema([
                                    Forms\Components\Repeater::make('redes_sociales')
                                        ->label('')
                                        ->schema([
                                            Forms\Components\Grid::make(3)->schema([
                                                Forms\Components\Select::make('red')
                                                    ->label('Red Social')
                                                    ->options([
                                                        'facebook' => '📘 Facebook',
                                                        'instagram' => '📸 Instagram',
                                                        'tiktok' => '🎵 TikTok',
                                                        'twitter' => '🐦 Twitter/X',
                                                        'linkedin' => '💼 LinkedIn',
                                                        'youtube' => '▶️ YouTube',
                                                        'pinterest' => '📌 Pinterest',
                                                    ])
                                                    ->required()
                                                    ->native(false),
                                                Forms\Components\TextInput::make('url')
                                                    ->label('URL del Perfil')
                                                    ->url()
                                                    ->required()
                                                    ->placeholder('https://...'),
                                                Forms\Components\Toggle::make('activo')
                                                    ->label('Activo')
                                                    ->default(true)
                                                    ->inline(false),
                                            ]),
                                        ])
                                        ->defaultItems(0)
                                        ->addActionLabel('➕ Agregar red social')
                                        ->reorderable()
                                        ->collapsible()
                                        ->itemLabel(fn (array $state): ?string => $state['red'] ?? 'Nueva red'),
                                ]),
                        ]),

                    // Paso 6: Notas
                    Forms\Components\Wizard\Step::make('Notas')
                        ->icon('heroicon-o-document-text')
                        ->description('Notas y observaciones')
                        ->schema([
                            Forms\Components\Textarea::make('notas')
                                ->label('Notas del Cliente')
                                ->placeholder('Agrega cualquier nota o información adicional sobre el cliente...')
                                ->rows(6)
                                ->columnSpanFull(),
                        ]),
                ])
                ->skippable()
                ->persistStepInQueryString()
                ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre_empresa')
                    ->label('Empresa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('correo')
                    ->label('Correo')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('estado_cuenta')
                    ->label('Estado')
                    ->colors([
                        'success' => 'activo',
                        'warning' => 'pendiente',
                        'danger' => 'suspendido',
                        'gray' => 'cancelado',
                    ]),
                Tables\Columns\TextColumn::make('industria')
                    ->label('Industria')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nombre_sitio')
                    ->label('Sitio Web')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => static::getUrl('view', ['record' => $record])),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'success';
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
            'index' => Pages\ListClientes::route('/'),
            'create' => Pages\CreateCliente::route('/create'),
            'view' => Pages\ViewCliente::route('/{record}'),
            'edit' => Pages\EditCliente::route('/{record}/edit'),
        ];
    }
}
