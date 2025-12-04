<?php

namespace App\Filament\Resources;

use App\Filament\Resources\N8nPostResource\Pages;
use App\Filament\Resources\N8nPostResource\RelationManagers;
use App\Models\N8nPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class N8nPostResource extends Resource
{
    protected static ?string $model = N8nPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationLabel = 'WebSolutions Posts';
    protected static ?string $modelLabel = 'Post de Facebook';
    protected static ?string $pluralModelLabel = 'WebSolutions Posts';
    protected static ?string $navigationGroup = 'Administración';
    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        $pending = static::getEloquentQuery()->where('status', 'pending')->count();
        return $pending ? $pending : 'FB Post';
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        $pending = static::getEloquentQuery()->where('status', 'pending')->count();
        return $pending ? 'warning' : 'info';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Contenido')
                    ->schema([
                        Forms\Components\TextInput::make('titulo')
                            ->label('Título')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('texto')
                            ->label('Texto')
                            ->rows(5)
                            ->columnSpanFull(),
                        Forms\Components\TagsInput::make('hashtags')
                            ->label('Hashtags')
                            ->placeholder('Agregar hashtag...')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('footer')
                            ->label('Footer')
                            ->maxLength(255),
                    ]),
                Forms\Components\Section::make('Imagen')
                    ->schema([
                        Forms\Components\TextInput::make('imagen')
                            ->label('URL de Imagen')
                            ->url()
                            ->maxLength(500)
                            ->suffixAction(
                                Forms\Components\Actions\Action::make('preview')
                                    ->icon('heroicon-o-eye')
                                    ->url(fn ($state) => $state)
                                    ->openUrlInNewTab()
                                    ->visible(fn ($state) => !empty($state))
                            )
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Estado')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options([
                                'pending' => '🟡 Pendiente',
                                'published' => '🟢 Publicado',
                                'scheduled' => '🔵 Programado',
                                'rejected' => '🔴 Rechazado',
                            ])
                            ->required()
                            ->default('pending'),
                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Fecha de Publicación')
                            ->displayFormat('d/m/Y H:i'),
                    ])
                    ->columns(2),
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
                    ->state(fn (N8nPost $record): string => $record->status)
                    ->icon(fn (string $state): string => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'published' => 'heroicon-o-check-circle',
                        'scheduled' => 'heroicon-o-calendar',
                        'rejected' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'published' => 'success',
                        'scheduled' => 'info',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->size(Tables\Columns\IconColumn\IconColumnSize::Medium),
                Tables\Columns\ImageColumn::make('imagen')
                    ->label('Imagen')
                    ->circular()
                    ->size(50)
                    ->defaultImageUrl(url('/images/placeholder.png')),
                Tables\Columns\TextColumn::make('titulo')
                    ->label('Título')
                    ->weight(FontWeight::SemiBold)
                    ->searchable()
                    ->limit(50)
                    ->description(fn (N8nPost $record): string => \Illuminate\Support\Str::limit($record->texto ?? '', 60)),
                Tables\Columns\TextColumn::make('hashtags_string')
                    ->label('Hashtags')
                    ->badge()
                    ->color('gray')
                    ->limit(30),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'published' => 'success',
                        'scheduled' => 'info',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pendiente',
                        'published' => 'Publicado',
                        'scheduled' => 'Programado',
                        'rejected' => 'Rechazado',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Recibido')
                    ->since()
                    ->sortable()
                    ->description(fn (N8nPost $record): string => $record->created_at->format('d/m/Y H:i')),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'pending' => 'Pendiente',
                        'published' => 'Publicado',
                        'scheduled' => 'Programado',
                        'rejected' => 'Rechazado',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('view_post')
                    ->tooltip('Ver publicación')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->modalHeading(fn (N8nPost $record) => $record->titulo)
                    ->modalContent(fn (N8nPost $record) => view('filament.components.post-preview', ['record' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar')
                    ->iconButton(),
                Tables\Actions\Action::make('mark_published')
                    ->tooltip('Marcar como publicado')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn (N8nPost $record) => $record->update(['status' => 'published', 'published_at' => now()]))
                    ->visible(fn (N8nPost $record): bool => $record->status === 'pending')
                    ->iconButton(),
                Tables\Actions\Action::make('mark_rejected')
                    ->tooltip('Rechazar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(fn (N8nPost $record) => $record->update(['status' => 'rejected']))
                    ->visible(fn (N8nPost $record): bool => $record->status === 'pending')
                    ->iconButton(),
                Tables\Actions\EditAction::make()
                    ->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('mark_published')
                        ->label('Marcar como publicados')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['status' => 'published', 'published_at' => now()]))
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('mark_rejected')
                        ->label('Rechazar')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['status' => 'rejected']))
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Sin publicaciones')
            ->emptyStateDescription('No hay publicaciones recibidas de n8n.')
            ->emptyStateIcon('heroicon-o-megaphone');
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
            'index' => Pages\ListN8nPosts::route('/'),
            'create' => Pages\CreateN8nPost::route('/create'),
            'edit' => Pages\EditN8nPost::route('/{record}/edit'),
        ];
    }
}
