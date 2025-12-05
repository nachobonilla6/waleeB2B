<?php

namespace App\Filament\Resources;

use App\Filament\Resources\N8nErrorResource\Pages;
use App\Filament\Resources\N8nErrorResource\RelationManagers;
use App\Models\N8nError;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class N8nErrorResource extends Resource
{
    protected static ?string $model = N8nError::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static ?string $navigationLabel = 'Errores n8n';
    protected static ?string $modelLabel = 'Error';
    protected static ?string $pluralModelLabel = 'Errores n8n';
    protected static ?string $navigationGroup = 'Automatizaciones';
    protected static ?int $navigationSort = 15;

    public static function getNavigationBadge(): ?string
    {
        return static::getEloquentQuery()->where('status', 'new')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Error')
                    ->schema([
                        Forms\Components\TextInput::make('error_message')
                            ->label('Mensaje de Error')
                            ->columnSpanFull()
                            ->disabled(),
                        Forms\Components\Textarea::make('error_stack')
                            ->label('Stack Trace')
                            ->rows(5)
                            ->columnSpanFull()
                            ->disabled(),
                    ]),
                Forms\Components\Section::make('Detalles de Ejecución')
                    ->schema([
                        Forms\Components\TextInput::make('workflow_name')
                            ->label('Workflow')
                            ->disabled(),
                        Forms\Components\TextInput::make('last_node_executed')
                            ->label('Nodo')
                            ->disabled(),
                        Forms\Components\TextInput::make('mode')
                            ->label('Modo')
                            ->disabled(),
                        Forms\Components\TextInput::make('execution_url')
                            ->label('URL de Ejecución')
                            ->url()
                            ->suffixAction(
                                Forms\Components\Actions\Action::make('open')
                                    ->icon('heroicon-o-arrow-top-right-on-square')
                                    ->url(fn ($state) => $state)
                                    ->openUrlInNewTab()
                                    ->visible(fn ($state) => !empty($state))
                            )
                            ->disabled(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Estado')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options([
                                'new' => '🔴 Nuevo',
                                'reviewed' => '🟡 Revisado',
                                'resolved' => '🟢 Resuelto',
                                'ignored' => '⚪ Ignorado',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('notes')
                            ->label('Notas')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->columns([
                Tables\Columns\IconColumn::make('status_icon')
                    ->label('')
                    ->state(fn (N8nError $record): string => $record->status)
                    ->icon(fn (string $state): string => match ($state) {
                        'new' => 'heroicon-o-exclamation-circle',
                        'reviewed' => 'heroicon-o-eye',
                        'resolved' => 'heroicon-o-check-circle',
                        'ignored' => 'heroicon-o-minus-circle',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'danger',
                        'reviewed' => 'warning',
                        'resolved' => 'success',
                        'ignored' => 'gray',
                        default => 'gray',
                    })
                    ->size(Tables\Columns\IconColumn\IconColumnSize::Medium),
                Tables\Columns\TextColumn::make('workflow_name')
                    ->label('Workflow')
                    ->weight(FontWeight::SemiBold)
                    ->searchable()
                    ->description(fn (N8nError $record): string => $record->last_node_executed ? "Nodo: {$record->last_node_executed}" : ''),
                Tables\Columns\TextColumn::make('mode')
                    ->label('Modo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'manual' => 'info',
                        'trigger' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->since()
                    ->sortable()
                    ->description(fn (N8nError $record): string => $record->created_at->format('d/m/Y H:i')),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'new' => 'Nuevo',
                        'reviewed' => 'Revisado',
                        'resolved' => 'Resuelto',
                        'ignored' => 'Ignorado',
                    ]),
                Tables\Filters\SelectFilter::make('workflow_name')
                    ->label('Workflow')
                    ->options(fn () => N8nError::distinct()->pluck('workflow_name', 'workflow_name')->filter()),
            ])
            ->actions([
                Tables\Actions\Action::make('view_error')
                    ->tooltip('Ver detalle del error')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->modalHeading(fn (N8nError $record) => "Error en {$record->workflow_name}")
                    ->modalContent(fn (N8nError $record) => view('filament.components.error-detail', ['record' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar')
                    ->iconButton(),
                Tables\Actions\Action::make('open_n8n')
                    ->tooltip('Abrir en n8n')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('info')
                    ->url(fn (N8nError $record): ?string => $record->execution_url)
                    ->openUrlInNewTab()
                    ->visible(fn (N8nError $record): bool => !empty($record->execution_url))
                    ->iconButton(),
                Tables\Actions\Action::make('mark_resolved')
                    ->tooltip('Marcar como resuelto')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn (N8nError $record) => $record->update(['status' => 'resolved']))
                    ->visible(fn (N8nError $record): bool => $record->status !== 'resolved')
                    ->iconButton(),
                Tables\Actions\Action::make('mark_ignored')
                    ->tooltip('Ignorar')
                    ->icon('heroicon-o-x-circle')
                    ->color('gray')
                    ->action(fn (N8nError $record) => $record->update(['status' => 'ignored']))
                    ->visible(fn (N8nError $record): bool => !in_array($record->status, ['resolved', 'ignored']))
                    ->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('mark_resolved')
                        ->label('Marcar como resueltos')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['status' => 'resolved']))
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('mark_ignored')
                        ->label('Ignorar')
                        ->icon('heroicon-o-x-circle')
                        ->color('gray')
                        ->action(fn ($records) => $records->each->update(['status' => 'ignored']))
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Sin errores')
            ->emptyStateDescription('No hay errores registrados. ¡Todo funciona correctamente!')
            ->emptyStateIcon('heroicon-o-check-circle');
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
            'index' => Pages\ListN8nErrors::route('/'),
            'edit' => Pages\EditN8nError::route('/{record}/edit'),
        ];
    }
}
