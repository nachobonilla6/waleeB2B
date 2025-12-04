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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\Section::make('Empresa')
                        ->icon('heroicon-o-building-office')
                        ->columnSpan(1)
                        ->schema([
                            Forms\Components\TextInput::make('nombre_empresa')
                                ->label('Nombre de Empresa')
                                ->required(),
                            Forms\Components\Select::make('tipo_empresa')
                                ->label('Tipo')
                                ->options([
                                    'servicios' => 'Servicios',
                                    'comercio' => 'Comercio',
                                    'manufactura' => 'Manufactura',
                                    'tecnologia' => 'Tecnología',
                                    'otro' => 'Otro',
                                ]),
                            Forms\Components\Select::make('industria')
                                ->label('Industria')
                                ->options([
                                    'turismo' => 'Turismo',
                                    'gastronomia' => 'Gastronomía',
                                    'retail' => 'Retail',
                                    'salud' => 'Salud',
                                    'educacion' => 'Educación',
                                    'tecnologia' => 'Tecnología',
                                    'otro' => 'Otro',
                                ]),
                            Forms\Components\Textarea::make('descripcion')
                                ->label('Descripción')
                                ->rows(3),
                        ]),

                    Forms\Components\Section::make('Fechas')
                        ->icon('heroicon-o-calendar')
                        ->columnSpan(1)
                        ->schema([
                            Forms\Components\DatePicker::make('fecha_registro')
                                ->label('Fecha de Registro')
                                ->displayFormat('d/m/Y'),
                            Forms\Components\DatePicker::make('fecha_creacion')
                                ->label('Fecha de Creación')
                                ->displayFormat('d/m/Y'),
                            Forms\Components\DatePicker::make('fecha_cotizacion')
                                ->label('Fecha Cotización')
                                ->displayFormat('d/m/Y'),
                            Forms\Components\DatePicker::make('fecha_factura')
                                ->label('Fecha Factura')
                                ->displayFormat('d/m/Y'),
                            Forms\Components\Select::make('estado_cuenta')
                                ->label('Estado')
                                ->options([
                                    'activo' => '🟢 Activo',
                                    'pendiente' => '🟡 Pendiente',
                                    'suspendido' => '🔴 Suspendido',
                                    'cancelado' => '⚫ Cancelado',
                                ]),
                        ]),

                    Forms\Components\Section::make('Contacto')
                        ->icon('heroicon-o-phone')
                        ->columnSpan(1)
                        ->schema([
                            Forms\Components\TextInput::make('correo')
                                ->label('Correo')
                                ->email()
                                ->required(),
                            Forms\Components\TextInput::make('telefono')
                                ->label('Teléfono')
                                ->tel(),
                            Forms\Components\TextInput::make('telefono_alternativo')
                                ->label('Tel. Alternativo')
                                ->tel(),
                            Forms\Components\TextInput::make('whatsapp')
                                ->label('WhatsApp')
                                ->tel(),
                        ]),
                ]),

                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Section::make('Ubicación')
                        ->icon('heroicon-o-map-pin')
                        ->schema([
                            Forms\Components\Grid::make(3)->schema([
                                Forms\Components\TextInput::make('pais')
                                    ->label('País'),
                                Forms\Components\TextInput::make('estado')
                                    ->label('Estado'),
                                Forms\Components\TextInput::make('ciudad')
                                    ->label('Ciudad'),
                            ]),
                            Forms\Components\TextInput::make('direccion')
                                ->label('Dirección')
                                ->columnSpanFull(),
                            Forms\Components\TextInput::make('codigo_postal')
                                ->label('C.P.'),
                        ]),

                    Forms\Components\Section::make('Sitio Web')
                        ->icon('heroicon-o-globe-alt')
                        ->schema([
                            Forms\Components\TextInput::make('nombre_sitio')
                                ->label('Dominio'),
                            Forms\Components\TextInput::make('url_sitio')
                                ->label('URL')
                                ->url(),
                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\TextInput::make('hosting')
                                    ->label('Hosting'),
                                Forms\Components\DatePicker::make('dominio_expira')
                                    ->label('Expira')
                                    ->displayFormat('d/m/Y'),
                            ]),
                        ]),
                ]),

                Forms\Components\Section::make('Redes Sociales')
                    ->icon('heroicon-o-share')
                    ->schema([
                        Forms\Components\Repeater::make('redes_sociales')
                            ->label('')
                            ->schema([
                                Forms\Components\Grid::make(3)->schema([
                                    Forms\Components\Select::make('red')
                                        ->label('Red')
                                        ->options([
                                            'facebook' => '📘 Facebook',
                                            'instagram' => '📸 Instagram',
                                            'tiktok' => '🎵 TikTok',
                                            'twitter' => '🐦 Twitter/X',
                                            'linkedin' => '💼 LinkedIn',
                                            'youtube' => '▶️ YouTube',
                                            'pinterest' => '📌 Pinterest',
                                        ])
                                        ->required(),
                                    Forms\Components\TextInput::make('url')
                                        ->label('URL')
                                        ->url()
                                        ->required(),
                                    Forms\Components\Toggle::make('activo')
                                        ->label('Activo')
                                        ->inline(false),
                                ]),
                            ])
                            ->defaultItems(0)
                            ->addActionLabel('Agregar red social')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['red'] ?? null),
                    ]),

                Forms\Components\Section::make('Notas')
                    ->icon('heroicon-o-document-text')
                    ->collapsed()
                    ->schema([
                        Forms\Components\Textarea::make('notas')
                            ->label('')
                            ->rows(4),
                    ]),
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
