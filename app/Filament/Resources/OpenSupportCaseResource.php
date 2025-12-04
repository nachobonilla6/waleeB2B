<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OpenSupportCaseResource\Pages;
use App\Models\SupportCase;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;

class OpenSupportCaseResource extends Resource
{
    protected static ?string $model = SupportCase::class;
    protected static ?string $navigationIcon = 'heroicon-o-inbox';
    protected static ?string $modelLabel = 'Ticket Abierto';
    protected static ?string $pluralModelLabel = 'Tickets Abiertos';
    protected static ?string $navigationGroup = 'Soporte';
    protected static ?int $navigationSort = 2;
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereIn('status', ['open', 'in_progress'])->count();
    }
    
    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información del Ticket')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre del Cliente')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('title')
                            ->label('Asunto')
                            ->required()
                            ->maxLength(255),
                        Select::make('status')
                            ->label('Estado')
                            ->options([
                                'open' => 'Abierto',
                                'in_progress' => 'En Progreso',
                                'resolved' => 'Resuelto',
                                'closed' => 'Cerrado',
                            ])
                            ->default('open')
                            ->required(),
                        DateTimePicker::make('resolved_at')
                            ->label('Fecha de Resolución')
                            ->displayFormat('d/m/Y H:i'),
                    ])->columns(2),
                
                Section::make('Detalles del Ticket')
                    ->schema([
                        Textarea::make('description')
                            ->label('Descripción')
                            ->required()
                            ->columnSpanFull(),
                        FileUpload::make('image')
                            ->label('Imagen Adjunta')
                            ->image()
                            ->directory('support-cases')
                            ->visibility('public')
                            ->imageEditor()
                            ->openable()
                            ->downloadable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->whereIn('status', ['open', 'in_progress']))
            ->paginationPageOptions([5, 10, 25, 50, 'all'])
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'asc')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Asunto')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn (SupportCase $record): string => $record->title),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'warning',
                        'in_progress' => 'info',
                        'resolved' => 'success',
                        'closed' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'open' => 'Abierto',
                        'in_progress' => 'En Progreso',
                    ])
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('resolver')
                    ->label('Marcar como Resuelto')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (SupportCase $record) {
                        $record->update([
                            'status' => 'resolved',
                            'resolved_at' => now(),
                        ]);
                        
                        return redirect(self::getUrl('index'));
                    })
                    ->visible(fn (SupportCase $record): bool => $record->status !== 'resolved'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOpenSupportCases::route('/'),
            'create' => Pages\CreateOpenSupportCase::route('/create'),
            'view' => Pages\ViewOpenSupportCase::route('/{record}'),
            'edit' => Pages\EditOpenSupportCase::route('/{record}/edit'),
        ];
    }
}
