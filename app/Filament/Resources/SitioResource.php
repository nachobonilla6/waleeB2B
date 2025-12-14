<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SitioResource\Pages;
use App\Filament\Resources\SitioResource\RelationManagers;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use App\Models\Sitio;
use App\Models\Tag;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class SitioResource extends Resource
{
    protected static ?string $model = Sitio::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $modelLabel = 'Sitio';
    protected static ?string $navigationGroup = 'Contenido';
    protected static bool $shouldRegisterNavigation = true;

    public static function form(Form $form): Form
    {
        return $form->schema([
                Forms\Components\Section::make('Información del Sitio')
                    ->schema([
                        Forms\Components\TextInput::make('nombre')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                        
                        Forms\Components\Textarea::make('enlace')
                            ->label('Enlace')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull()
                            ->helperText('URL completa del sitio web'),
                        
                        // Imagen con vista previa mejorada
                        Forms\Components\Fieldset::make('Imagen del Sitio')
                            ->schema([
                                Forms\Components\FileUpload::make('imagen')
                                    ->label('')
                                    ->placeholder('Arrastra una imagen o haz clic para seleccionar')
                                    ->image()
                                    ->directory('sitios')
                                    ->imageEditor()
                                    ->imageEditorViewportWidth('1200')
                                    ->imageEditorViewportHeight('630')
                                    ->imageEditorMode(2) // 1 = cover, 2 = contain, 3 = auto
                                    ->imageCropAspectRatio('16:9')
                                    ->imageResizeTargetWidth('1200')
                                    ->imageResizeTargetHeight('630')
                                    ->imageResizeMode('cover')
                                    ->imagePreviewHeight('150')
                                    ->panelLayout('compact')
                                    ->removeUploadedFileButtonPosition('right')
                                    ->uploadProgressIndicatorPosition('left')
                                    ->downloadable()
                                    ->openable()
                                    ->previewable()
                                    ->loadingIndicatorPosition('left')
                                    ->hint('Tamaño recomendado: 1200×630px')
                                    ->hintIcon('heroicon-o-photo')
                                    ->hintColor('primary')
                                    ->hintIconTooltip('Haz clic para editar la imagen')
                                    ->extraAttributes([
                                        'class' => 'w-full cursor-pointer border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 hover:border-primary-500 transition-colors',
                                        'id' => 'imagen-upload',
                                    ])
                                    ->multiple(false)
                                    ->preserveFilenames()
                                    ->visibility('public')
                                    ->imageEditorEmptyFillColor('#94a3b8')
                                    ->imageEditorAspectRatios([
                                        '16:9',
                                        '4:3',
                                        '1:1',
                                    ])
                                    ->extraAttributes(['class' => 'w-full cursor-pointer border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 hover:border-primary-500 transition-colors'])
                                    ->extraInputAttributes(['class' => 'cursor-pointer'])
                                    ->deletable()
                                    ->deleteUploadedFileUsing(function ($state, $set) {
                                        // Delete the file from storage
                                        if ($state) {
                                            Storage::disk('public')->delete($state);
                                        }
                                        // Clear the field
                                        $set('imagen', null);
                                        
                                        // Show success notification
                                        Notification::make()
                                            ->title('Imagen eliminada')
                                            ->success()
                                            ->send();
                                    })
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set, $livewire) {
                                        // Force the image editor to open when an image is selected
                                        if ($state) {
                                            $livewire->dispatch('open-modal', id: 'filament-tables-image-editor');
                                        }
                                    }),
                                
                                // Vista previa personalizada
                                Forms\Components\ViewField::make('vista_previa')
                                    ->view('filament.forms.components.image-preview')
                                    ->hiddenLabel()
                                    ->visible(fn ($get) => $get('imagen')),
                            ])
                            ->columns(1)
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'bg-gray-50 dark:bg-gray-800 rounded-lg p-2 border border-dashed border-gray-300 dark:border-gray-600']),
                        
                        Forms\Components\Textarea::make('video_url')
                            ->label('🎬 Enlace de Video')
                            ->placeholder('https://www.youtube.com/watch?v=... o enlace de Vimeo, etc.')
                            ->rows(2)
                            ->columnSpanFull()
                            ->helperText('Pega aquí el enlace del video de YouTube, Vimeo u otra plataforma'),
                        
                        Forms\Components\Toggle::make('en_linea')
                            ->label('¿En línea?')
                            ->default(true),
                        
                        Forms\Components\Select::make('tags')
                            ->relationship('tags', 'nombre')
                            ->multiple()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('nombre')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        
                        Forms\Components\RichEditor::make('descripcion')
                            ->label('Descripción')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(fn (Sitio $record): string => static::getUrl('edit', ['record' => $record]))
            ->recordAction('edit')
            ->defaultPaginationPageOption(12)
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->label('')
                    ->formatStateUsing(function ($record) {
                        // Get initials from name
                        $words = explode(' ', $record->nombre);
                        $initials = '';
                        $maxInitials = 2;
                        
                        foreach ($words as $i => $word) {
                            if ($i >= $maxInitials) break;
                            $initials .= strtoupper(substr($word, 0, 1));
                        }
                        
                        // Generate a consistent color based on the name
                        $colors = [
                            'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                            'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                            'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200',
                            'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200',
                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                            'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
                        ];
                        $colorIndex = abs(crc32($record->nombre)) % count($colors);
                        $colorClass = $colors[$colorIndex];
                        
                        return '<div class="flex items-center justify-center w-10 h-10 rounded-lg ' . $colorClass . ' font-semibold text-sm">' . $initials . '</div>';
                    })
                    ->html()
                    ->grow(false)
                    ->width('60px'),
                
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->size('sm')
                    ->limit(40),
                
                Tables\Columns\TextColumn::make('enlace')
                    ->label('Enlace')
                    ->url(fn ($record) => $record->enlace)
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-link')
                    ->color('primary')
                    ->size('xs')
                    ->limit(30)
                    ->toggleable(),
                
                Tables\Columns\IconColumn::make('en_linea')
                    ->label('Estado')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->size('sm')
                    ->width('80px'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('en_linea')
                    ->label('Estado')
                    ->options([
                        '1' => 'En línea',
                        '0' => 'Fuera de línea',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->size('sm'),
                Tables\Actions\ViewAction::make()
                    ->iconButton()
                    ->size('sm'),
            ])
            ->recordClasses(fn ($record) => 'group hover:bg-gray-50 dark:hover:bg-gray-800 cursor-pointer')
            ->striped()
            ->paginated([12, 24, 48, 96])
            ->emptyStateHeading('No hay sitios')
            ->emptyStateDescription('Comienza creando tu primer sitio')
            ->emptyStateIcon('heroicon-o-globe-alt')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('marcarEnLinea')
                        ->label('Marcar como en línea')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function (Collection $records) {
                            $records->each->update(['en_linea' => true]);
                            
                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('Sitios actualizados')
                                ->body('Los sitios seleccionados han sido marcados como en línea.')
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    
                    Tables\Actions\BulkAction::make('marcarFueraDeLinea')
                        ->label('Marcar como fuera de línea')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function (Collection $records) {
                            $records->each->update(['en_linea' => false]);
                            
                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('Sitios actualizados')
                                ->body('Los sitios seleccionados han sido marcados como fuera de línea.')
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Eliminar seleccionados')
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('deleteImage')
                ->label('Eliminar imagen')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->action(function (Sitio $record) {
                    if ($record->imagen) {
                        // Delete the file from storage
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($record->imagen);
                        // Clear the image field
                        $record->imagen = null;
                        $record->save();
                        
                        // Show success notification
                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Imagen eliminada')
                            ->body('La imagen ha sido eliminada correctamente.')
                            ->send();
                            
                        // Redirect to refresh the form
                        return redirect(request()->header('Referer'));
                    }
                })
                ->hidden(fn (Sitio $record): bool => !$record->imagen),
            Actions\DeleteAction::make(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSitios::route('/'),
            'create' => Pages\CreateSitio::route('/create'),
            'edit' => Pages\EditSitio::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('en_linea', true)->count();
    }
    
    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'success';
    }
}
