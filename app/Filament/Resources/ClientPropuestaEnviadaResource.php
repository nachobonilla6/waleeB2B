<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientPropuestaEnviadaResource\Pages;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;

class ClientPropuestaEnviadaResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $modelLabel = 'Cliente con propuesta enviada';
    protected static ?string $navigationLabel = 'Propuestas Enviadas';
    protected static ?string $title = 'Propuestas Enviadas';
    protected static ?string $navigationGroup = 'Herramientas';
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return static::getEloquentQuery()->count();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('propuesta_enviada', true);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->label('Dirección')
                    ->columnSpanFull()
                    ->maxLength(500),
                Forms\Components\TextInput::make('telefono_1')
                    ->label('Teléfono 1')
                    ->tel()
                    ->maxLength(20),
                Forms\Components\TextInput::make('telefono_2')
                    ->label('Teléfono 2')
                    ->tel()
                    ->maxLength(20),
                Forms\Components\TextInput::make('website')
                    ->label('Sitio web')
                    ->formatStateUsing(fn ($state) => str_replace(['http://', 'https://'], '', $state))
                    ->prefix('https://')
                    ->maxLength(255)
                    ->readOnly()
                    ->disabled()
                    ->suffixAction(
                        fn ($state) => $state
                            ? Forms\Components\Actions\Action::make('open')
                                ->icon('heroicon-m-pencil')
                                ->url(fn () => (str_starts_with($state, 'http') ? $state : 'https://' . $state))
                                ->openUrlInNewTab()
                            : null
                    )
                    ->dehydrateStateUsing(function ($state) {
                        if (empty($state)) return null;
                        $state = trim($state);
                        if (str_starts_with($state, 'http://') || str_starts_with($state, 'https://')) {
                            return $state;
                        }
                        return 'https://' . $state;
                    }),
                Forms\Components\Select::make('proposed_site')
                    ->label('Sitio propuesto')
                    ->options(\App\Models\Sitio::all()->pluck('nombre', 'enlace'))
                    ->searchable()
                    ->required(false),
                Forms\Components\Toggle::make('propuesta_enviada')
                    ->label('Propuesta enviada')
                    ->default(true)
                    ->disabled(),
                Forms\Components\Textarea::make('feedback')
                    ->label('Feedback')
                    ->columnSpanFull()
                    ->readOnly()
                    ->dehydrated(fn ($state) => $state),
                Forms\Components\Textarea::make('propuesta')
                    ->label('Propuesta')
                    ->columnSpanFull()
                    ->readOnly()
                    ->rows(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc') // Ordena por fecha de creación descendente (más recientes primero)
            ->paginationPageOptions([5, 10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->heading('Propuestas Enviadas')
            ->description(fn () => 'Nombre, correo y fecha de propuesta enviada. Total: ' . static::getEloquentQuery()->count())
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->formatStateUsing(function ($state) {
                        return mb_strlen($state) > 25 ? mb_substr($state, 0, 25) . '…' : $state;
                    }),
                Tables\Columns\TextColumn::make('contacto')
                    ->label('Contacto')
                    ->html()
                    ->state(function ($record) {
                        $telefono1 = e($record->telefono_1 ?? '');
                        $telefono2 = e($record->telefono_2 ?? '');
                        $email = e($record->email ?? '');
                        
                        $result = '';
                        if ($telefono1) $result .= $telefono1;
                        if ($telefono2) $result .= ($telefono1 ? ' / ' : '') . $telefono2;
                        if ($email) $result .= '<br><span class="text-xs text-gray-500">' . $email . '</span>';
                        return $result ?: '-';
                    }),
                Tables\Columns\TextColumn::make('website')
                    ->label('Sitio Web')
                    ->html()
                    ->state(function($record) {
                        $webRaw = $record->website ?? '';
                        if ($webRaw) {
                            $webClean = str_replace(['http://', 'https://'], '', $webRaw);
                            $webLim = mb_strlen($webClean) > 20 ? mb_substr($webClean, 0, 20) . '…' : $webClean;
                            $webFull = str_starts_with($webRaw, 'http') ? $webRaw : 'https://' . $webRaw;
                            return '<a href="' . e($webFull) . '" target="_blank" class="text-blue-600 hover:underline">' . e($webLim) . '</a>';
                        }
                        return '-';
                    }),
                Tables\Columns\TextColumn::make('proposed_site')
                    ->label('Sitio Propuesto')
                    ->html()
                    ->state(function($record) {
                        $propRaw = $record->proposed_site ?? '';
                        if ($propRaw) {
                            $propClean = str_replace(['http://', 'https://'], '', $propRaw);
                            $propLim = mb_strlen($propClean) > 20 ? mb_substr($propClean, 0, 20) . '…' : $propClean;
                            $propFull = str_starts_with($propRaw, 'http') ? $propRaw : 'https://' . $propRaw;
                            return '<a href="' . e($propFull) . '" target="_blank" class="text-green-600 hover:underline">' . e($propLim) . '</a>';
                        }
                        return '-';
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->iconButton(),
            ])
            ->bulkActions([
                // Quitar acciones masivas
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
            'index' => Pages\ListClientPropuestaEnviadas::route('/'),
            'view' => Pages\ViewClientPropuestaEnviada::route('/{record}'),
            'edit' => Pages\EditClientPropuestaEnviada::route('/{record}/edit'),
        ];
    }
}

