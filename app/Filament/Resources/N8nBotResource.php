<?php

namespace App\Filament\Resources;

use App\Filament\Resources\N8nBotResource\Pages;
use App\Filament\Resources\N8nBotResource\RelationManagers;
use App\Models\N8nBot;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class N8nBotResource extends Resource
{
    protected static ?string $model = N8nBot::class;

    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';
    protected static ?string $navigationLabel = 'Bots n8n';
    protected static ?string $modelLabel = 'Bot n8n';
    protected static ?string $pluralModelLabel = 'Bots n8n';
    protected static ?string $navigationGroup = 'Soporte';
    protected static ?int $navigationSort = 10;

    public static function getNavigationBadge(): ?string
    {
        return static::getEloquentQuery()->count();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre del Bot')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Ej: Bot de extracción de clientes')
                    ->helperText('Nombre descriptivo para identificar el bot'),
                Forms\Components\TextInput::make('workflow_id')
                    ->label('ID del Workflow')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Ej: 3OwxkPVt7soP2dzJ')
                    ->helperText('ID del workflow en n8n'),
                Forms\Components\Select::make('trigger_type')
                    ->label('Tipo de Activación')
                    ->options([
                        'manual' => 'Manual',
                        'webhook' => 'Webhook',
                    ])
                    ->default('manual')
                    ->required()
                    ->reactive()
                    ->helperText('Cómo se activa el bot'),
                Forms\Components\TextInput::make('webhook_url')
                    ->label('URL del Webhook')
                    ->url()
                    ->maxLength(500)
                    ->placeholder('https://n8n.srv1137974.hstgr.cloud/webhook/...')
                    ->visible(fn (callable $get) => $get('trigger_type') === 'webhook')
                    ->required(fn (callable $get) => $get('trigger_type') === 'webhook')
                    ->helperText('URL completa del webhook de n8n'),
                Forms\Components\KeyValue::make('settings')
                    ->label('Configuración Adicional')
                    ->keyLabel('Clave')
                    ->valueLabel('Valor')
                    ->helperText('Configuraciones adicionales en formato clave-valor')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\BadgeColumn::make('trigger_type')
                    ->label('Tipo')
                    ->colors([
                        'primary' => 'manual',
                        'success' => 'webhook',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'manual' => 'Manual',
                        'webhook' => 'Webhook',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('workflow_id')
                    ->label('Workflow ID')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('ID copiado')
                    ->icon('heroicon-o-clipboard-document')
                    ->badge()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('webhook_url')
                    ->label('Webhook URL')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->webhook_url)
                    ->copyable()
                    ->icon('heroicon-o-link')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('trigger_type')
                    ->label('Tipo de Activación')
                    ->options([
                        'manual' => 'Manual',
                        'webhook' => 'Webhook',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton(),
                Tables\Actions\Action::make('open_workflow')
                    ->label('Abrir Workflow')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (N8nBot $record): string => "https://n8n.srv1137974.hstgr.cloud/workflow/{$record->workflow_id}")
                    ->openUrlInNewTab()
                    ->color('info')
                    ->iconButton(),
                Tables\Actions\Action::make('run_now')
                    ->label('Ejecutar Ahora')
                    ->icon('heroicon-o-play')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Ejecutar Bot')
                    ->modalDescription('¿Estás seguro de que deseas ejecutar este bot ahora?')
                    ->action(function (N8nBot $record) {
                        try {
                            $response = $record->runNow();
                            
                            if ($response->successful()) {
                                Notification::make()
                                    ->title('Bot ejecutado exitosamente')
                                    ->body("El bot '{$record->name}' se ha ejecutado correctamente.")
                                    ->success()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('Error al ejecutar el bot')
                                    ->body('El bot respondió con el código: ' . $response->status())
                                    ->danger()
                                    ->send();
                            }
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Error al ejecutar el bot')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListN8nBots::route('/'),
            'create' => Pages\CreateN8nBot::route('/create'),
            'edit' => Pages\EditN8nBot::route('/{record}/edit'),
        ];
    }
}
