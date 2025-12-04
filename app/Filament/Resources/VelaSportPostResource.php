<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VelaSportPostResource\Pages;
use App\Models\N8nPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VelaSportPostResource extends Resource
{
    protected static ?string $model = N8nPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $navigationLabel = 'Vela Sport Fishing';
    protected static ?string $modelLabel = 'Post Vela Sport';
    protected static ?string $pluralModelLabel = 'Vela Sport Fishing & Tours';
    protected static ?string $navigationGroup = 'Clientes';
    protected static ?int $navigationSort = 11;
    
    protected static ?string $slug = 'vela-sport-posts';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('cliente', 'velasport');
    }

    public static function getNavigationBadge(): ?string
    {
        $pending = static::getEloquentQuery()->where('status', 'pending')->count();
        return $pending ? $pending : 'FB Post';
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        $pending = static::getEloquentQuery()->where('status', 'pending')->count();
        return $pending ? 'success' : 'info';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('cliente')->default('velasport'),
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
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('imagen')
                            ->label('URL de Imagen')
                            ->url(),
                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options([
                                'pending' => 'Pendiente',
                                'published' => 'Publicado',
                                'rejected' => 'Rechazado',
                            ])
                            ->default('pending'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('titulo')->limit(30),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('created_at')->since(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVelaSportPosts::route('/'),
            'edit' => Pages\EditVelaSportPost::route('/{record}/edit'),
        ];
    }
}

