<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupportCaseResource\Pages;
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

class SupportCaseResource extends Resource
{
    protected static ?string $model = SupportCase::class;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $modelLabel = 'Ticket';
    protected static ?string $navigationGroup = 'Soporte';
    protected static ?int $navigationSort = 1;

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
                            ->options([
                                'open' => 'Abierto',
                                'in_progress' => 'En Progreso',
                                'resolved' => 'Resuelto',
                                'closed' => 'Cerrado',
                            ])
                            ->required()
                            ->default('open'),
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
                    })
                    ->formatStateUsing(fn (string $state): string => 
                        match ($state) {
                            'open' => 'Abierto',
                            'in_progress' => 'En Progreso',
                            'resolved' => 'Resuelto',
                            'closed' => 'Cerrado',
                            default => $state,
                        }
                    ),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('resolved_at')
                    ->label('Resuelto')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('email')
                    ->label('Correo')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('image')
                    ->label('Imagen')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'open' => 'Abierto',
                        'in_progress' => 'En Progreso',
                        'resolved' => 'Resuelto',
                        'closed' => 'Cerrado',
                    ]),
            ])
            ->actions([
                ViewAction::make()
                    ->icon('heroicon-o-eye'),
                EditAction::make()
                    ->icon('heroicon-o-pencil'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Eliminar seleccionados'),
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
            'index' => Pages\ListSupportCases::route('/'),
            'create' => Pages\CreateSupportCase::route('/create'),
            'view' => Pages\ViewSupportCase::route('/{record}'),
            'edit' => Pages\EditSupportCase::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }
}
