<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PublicacionVelaResource\Pages;
use App\Filament\Resources\PublicacionVelaResource\RelationManagers;
use App\Models\PublicacionVela;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Http;

class PublicacionVelaResource extends Resource
{
    protected static ?string $model = PublicacionVela::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Publicaciones Vela';
    protected static ?string $modelLabel = 'Publicación Vela';
    protected static ?string $pluralModelLabel = 'Publicaciones Vela';
    protected static ?string $navigationGroup = 'Administración';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Publicación')
                    ->compact()
                    ->collapsible(false)
                    ->schema([
                        Forms\Components\FileUpload::make('foto')
                            ->label('Foto')
                            ->image()
                            ->directory('publicaciones-vela')
                            ->imageEditor()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('texto')
                            ->label('Texto')
                            ->required()
                            ->rows(2)
                            ->maxLength(500)
                            ->helperText('Texto de la publicación (aproximadamente 25 palabras)')
                            ->columnSpanFull(),
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('crear_ai')
                                ->label('Crear con AI')
                                ->icon('heroicon-o-sparkles')
                                ->color('primary')
                                ->size('sm')
                                ->action(function (Set $set) {
                                    $apiKey = config('services.openai.api_key');
                                    if (empty($apiKey)) {
                                        Notification::make()
                                            ->title('Falta OPENAI_API_KEY')
                                            ->body('Configura la API key en el servidor para usar AI.')
                                            ->danger()
                                            ->send();
                                        return;
                                    }

                                    try {
                                        $response = Http::withToken($apiKey)
                                            ->acceptJson()
                                            ->post('https://api.openai.com/v1/chat/completions', [
                                                'model' => 'gpt-4o-mini',
                                                'response_format' => ['type' => 'json_object'],
                                                'messages' => [
                                                    [
                                                        'role' => 'system',
                                                        'content' => 'Eres un asistente que genera textos para publicaciones de Facebook sobre reservar tours. Responde SOLO JSON con la clave "texto". El texto debe ser exactamente 25 palabras, sin títulos ni estructura, solo texto plano sobre reservar tours en Question.',
                                                    ],
                                                    [
                                                        'role' => 'user',
                                                        'content' => 'Genera un texto de exactamente 25 palabras para una publicación de Facebook sobre reservar tours en Question. El texto debe ser atractivo y persuasivo. Devuelve JSON con "texto" como texto simple.',
                                                    ],
                                                ],
                                            ]);

                                        if ($response->successful()) {
                                            $responseData = $response->json();
                                            $data = $responseData['choices'][0]['message']['content'] ?? null;

                                            if (is_string($data)) {
                                                $data = json_decode($data, true);
                                            }

                                            if (! is_array($data)) {
                                                throw new \RuntimeException('La respuesta de AI no es JSON válido.');
                                            }

                                            $texto = trim((string) ($data['texto'] ?? ''));

                                            if (!empty($texto)) {
                                                $set('texto', $texto);
                                                Notification::make()
                                                    ->title('Texto generado')
                                                    ->body('El texto ha sido generado con AI.')
                                                    ->success()
                                                    ->send();
                                            } else {
                                                throw new \RuntimeException('No se generó texto.');
                                            }
                                        } else {
                                            Notification::make()
                                                ->title('Error al generar')
                                                ->body('La API respondió con estado ' . $response->status())
                                                ->danger()
                                                ->send();
                                        }
                                    } catch (\Exception $e) {
                                        Notification::make()
                                            ->title('Error al generar')
                                            ->body($e->getMessage())
                                            ->danger()
                                            ->send();
                                    }
                                }),
                        ])
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('hashtags')
                            ->label('Hashtags')
                            ->placeholder('#hashtag1 #hashtag2 #hashtag3')
                            ->helperText('Separar hashtags con espacios')
                            ->columnSpanFull(),
                        Forms\Components\DateTimePicker::make('fecha_publicacion')
                            ->label('Fecha')
                            ->default(now())
                            ->required()
                            ->displayFormat('d/m/Y H:i')
                            ->seconds(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto')
                    ->circular()
                    ->size(60),
                Tables\Columns\TextColumn::make('texto')
                    ->label('Texto')
                    ->limit(50)
                    ->wrap(),
                Tables\Columns\TextColumn::make('hashtags')
                    ->label('Hashtags')
                    ->limit(30),
                Tables\Columns\TextColumn::make('fecha_publicacion')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalWidth('4xl')
                    ->slideOver(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('fecha_publicacion', 'desc');
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
            'index' => Pages\ListPublicacionVelas::route('/'),
            'create' => Pages\CreatePublicacionVela::route('/create'),
            'edit' => Pages\EditPublicacionVela::route('/{record}/edit'),
        ];
    }
}
