<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClienteEnProcesoResource\Pages;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class ClienteEnProcesoResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    /**
     * Extrae solo el texto de un JSON, sin títulos ni claves
     */
    protected static function jsonToText($data): string
    {
        if (is_string($data)) {
            $decoded = json_decode($data, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $data = $decoded;
            } else {
                return trim($data);
            }
        }

        if (!is_array($data) && !is_object($data)) {
            return trim((string) $data);
        }

        $texts = [];
        
        foreach ($data as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $texts[] = static::jsonToText($value);
            } else {
                $texts[] = trim((string) $value);
            }
        }

        return trim(implode(' ', $texts));
    }
    protected static ?string $navigationLabel = 'Clientes Google';
    protected static ?string $modelLabel = 'Cliente Google';
    protected static ?string $pluralModelLabel = 'Clientes Google';
    protected static ?string $navigationGroup = 'Administración';
    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        try {
            $query = parent::getEloquentQuery();
            if (Schema::hasColumn('clientes_en_proceso', 'estado')) {
                $query->where('estado', 'pending');
            }
            return $query->count();
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Cliente')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Correo Electrónico')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('telefono_1')
                            ->label('Teléfono 1')
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\TextInput::make('telefono_2')
                            ->label('Teléfono 2')
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\Textarea::make('address')
                            ->label('Dirección')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Sitio Web')
                    ->schema([
                        Forms\Components\TextInput::make('website')
                            ->label('Sitio Web Actual')
                            ->formatStateUsing(fn ($state) => str_replace(['http://', 'https://'], '', $state))
                            ->prefix('https://')
                            ->maxLength(255)
                            ->suffixAction(
                                fn ($state) => $state
                                    ? Forms\Components\Actions\Action::make('open')
                                        ->icon('heroicon-o-eye')
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
                            ->label('Sitio Propuesto')
                            ->options(\App\Models\Sitio::all()->pluck('nombre', 'enlace'))
                            ->searchable()
                            ->helperText('Selecciona un sitio propuesto para este cliente'),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Propuesta y Feedback')
                    ->schema([
                        Forms\Components\Textarea::make('feedback')
                            ->label('Feedback')
                            ->rows(3)
                            ->columnSpanFull()
                            ->helperText('Feedback del cliente'),
                        Forms\Components\Textarea::make('propuesta')
                            ->label('Propuesta')
                            ->rows(4)
                            ->columnSpanFull()
                            ->helperText('Propuesta personalizada para este cliente'),
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('llenar_ai')
                                ->label('Llenar con AI')
                                ->icon('heroicon-o-sparkles')
                                ->color('primary')
                                ->action(function (Set $set, Get $get, ?Client $record) {
                                    $website = $get('website') ?: $record?->website;

                                    if (! $website) {
                                        Notification::make()
                                            ->title('Falta sitio web')
                                            ->body('Agrega el sitio web antes de usar AI.')
                                            ->warning()
                                            ->send();
                                        return;
                                    }

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
                                                        'content' => 'Eres un asistente que genera propuesta y feedback en JSON para un sitio web. Responde SOLO JSON con las claves "propuesta" y "feedback". Cada valor debe ser un texto simple de exactamente 25 palabras, sin títulos, sin estructura, solo texto plano.',
                                                    ],
                                                    [
                                                        'role' => 'user',
                                                        'content' => 'Genera feedback y propuesta (cada uno de exactamente 25 palabras, solo texto) para el sitio: ' . $website . '. Devuelve JSON con "feedback" y "propuesta" como texto simple.',
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

                                            $propuestaRaw = $data['propuesta'] ?? null;
                                            $feedbackRaw = $data['feedback'] ?? null;

                                            // Convertir a texto plano, sin títulos ni estructura
                                            $propuesta = is_array($propuestaRaw) || is_object($propuestaRaw)
                                                ? static::jsonToText($propuestaRaw)
                                                : trim((string) ($propuestaRaw ?? ''));

                                            $feedback = is_array($feedbackRaw) || is_object($feedbackRaw)
                                                ? static::jsonToText($feedbackRaw)
                                                : trim((string) ($feedbackRaw ?? ''));

                                            if ($record) {
                                                $record->update([
                                                    'feedback' => $feedback ?? $record->feedback,
                                                    'propuesta' => $propuesta ?? $record->propuesta,
                                                ]);
                                            }

                                            if ($feedback !== null) {
                                                $set('feedback', $feedback);
                                            }

                                            if ($propuesta !== null) {
                                                $set('propuesta', $propuesta);
                                            }

                                            Notification::make()
                                                ->title('Enviado a AI')
                                                ->body('Propuesta y feedback actualizados desde AI.')
                                                ->success()
                                                ->send();
                                        } else {
                                            Notification::make()
                                                ->title('Error al enviar')
                                                ->body('El webhook respondió con estado ' . $response->status())
                                                ->danger()
                                                ->send();
                                        }
                                    } catch (\Exception $e) {
                                        Notification::make()
                                            ->title('Error al enviar')
                                            ->body($e->getMessage())
                                            ->danger()
                                            ->send();
                                    }
                                }),
                        ])->columnSpanFull()
                          ->visibleOn('edit'),
                        Forms\Components\Hidden::make('propuesta_enviada')
                            ->default(false)
                            ->visible(fn () => Schema::hasColumn('clientes_en_proceso', 'propuesta_enviada')),
                        Forms\Components\Select::make('estado')
                            ->label('Estado')
                            ->options([
                                'pending' => 'Pending',
                                'accepted' => 'Accepted',
                                'rejected' => 'Rejected',
                                'listo_para_enviar' => 'Listo para enviar',
                            ])
                            ->default('pending')
                            ->required()
                            ->native(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->paginationPageOptions([5, 10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('website')
                    ->label('Sitio Web')
                    ->url(fn ($record) => $record->website ? (str_starts_with($record->website, 'http') ? $record->website : 'https://' . $record->website) : null)
                    ->openUrlInNewTab()
                    ->formatStateUsing(function ($state) {
                        if (! $state) {
                            return null;
                        }
                        // Quitar protocolo y www.
                        $clean = preg_replace('/^https?:\\/\\//i', '', $state);
                        $clean = preg_replace('/^www\\./i', '', $clean);
                        return rtrim($clean, '/');
                    })
                    ->limit(40)
                    ->icon('heroicon-o-globe-alt'),
                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'accepted' => 'success',
                        'rejected' => 'danger',
                        'listo_para_enviar' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                        'listo_para_enviar' => 'Listo para enviar',
                        default => $state,
                    })
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('propuesta_enviada')
                    ->label('Propuesta Enviada')
                    ->placeholder('Todos')
                    ->trueLabel('Con propuesta enviada')
                    ->falseLabel('Sin propuesta enviada')
                    ->queries(
                        true: function (Builder $query) {
                            if (Schema::hasColumn('clientes_en_proceso', 'propuesta_enviada')) {
                                return $query->where('propuesta_enviada', true);
                            }
                            return $query;
                        },
                        false: function (Builder $query) {
                            if (Schema::hasColumn('clientes_en_proceso', 'propuesta_enviada')) {
                                return $query->where('propuesta_enviada', false);
                            }
                            return $query;
                        },
                        blank: fn (Builder $query) => $query,
                    )
                    ->visible(fn () => Schema::hasColumn('clientes_en_proceso', 'propuesta_enviada')),
            ])
            ->actions([
                Tables\Actions\Action::make('enviar_propuesta')
                    ->label('Enviar Propuesta')
                    ->icon('heroicon-o-envelope')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Enviar propuesta')
                    ->modalDescription('¿Estás seguro de que deseas enviar la propuesta a este cliente?')
                    ->action(function (Client $record) {
                        if (empty($record->email)) {
                            Notification::make()
                                ->title('Error')
                                ->body('El cliente no tiene un correo electrónico.')
                                ->danger()
                                ->send();
                            return;
                        }

                        try {
                            $videoUrl = '';
                            if ($record->proposed_site) {
                                $sitio = \App\Models\Sitio::where('enlace', $record->proposed_site)->first();
                                $videoUrl = $sitio?->video_url ?? '';
                            }

                            $response = Http::timeout(30)->post('https://n8n.srv1137974.hstgr.cloud/webhook/f1d17b9f-5def-4ee1-b539-d0cd5ec6be6a', [
                                'name' => $record->name ?? '',
                                'email' => $record->email ?? '',
                                'website' => $record->website ?? '',
                                'proposed_site' => $record->proposed_site ?? '',
                                'video_url' => $videoUrl,
                                'feedback' => $record->feedback ?? '',
                                'propuesta' => $record->propuesta ?? '',
                                'cliente_id' => $record->id ?? null,
                                'cliente_nombre' => $record->name ?? '',
                                'cliente_correo' => $record->email ?? '',
                            ]);

                            if ($response->successful()) {
                                if (Schema::hasColumn('clientes_en_proceso', 'propuesta_enviada')) {
                                    $record->update(['propuesta_enviada' => true]);
                                }
                                Notification::make()
                                    ->title('Propuesta enviada')
                                    ->body('La propuesta se ha enviado correctamente a ' . $record->email)
                                    ->success()
                                    ->send();
                            } else {
                                $errorBody = $response->body();
                                $errorData = json_decode($errorBody, true);
                                
                                \Log::error('Error al enviar propuesta al webhook', [
                                    'status' => $response->status(),
                                    'body' => $errorBody,
                                    'cliente_id' => $record->id,
                                    'email' => $record->email,
                                ]);
                                
                                $errorMessage = 'Error ' . $response->status();
                                if (isset($errorData['message'])) {
                                    $errorMessage .= ': ' . $errorData['message'];
                                    if (str_contains($errorData['message'], 'Respond to Webhook')) {
                                        $errorMessage .= ' - El workflow de n8n necesita tener un nodo "Respond to Webhook" configurado.';
                                    }
                                } else {
                                    $errorMessage .= ': ' . substr($errorBody, 0, 150);
                                }
                                
                                Notification::make()
                                    ->title('Error al enviar propuesta')
                                    ->body($errorMessage)
                                    ->danger()
                                    ->persistent()
                                    ->send();
                            }
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Error al enviar')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->visible(function (Client $record): bool {
                        // En la página \"Listos para Enviar\" siempre debe mostrarse el botón,
                        // independientemente de si ya se marcó como propuesta_enviada.
                        if (request()->is('admin/cliente-en-procesos/listos-para-enviar*')) {
                            return true;
                        }

                        // En el resto de páginas, mantener la lógica original:
                        return !($record->propuesta_enviada ?? false);
                    }),
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-o-eye')
                    ->modalWidth('4xl')
                    ->modalHeading(fn (?Client $record) => 'Ver Cliente: ' . ($record->name ?? 'Cliente')),
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil-square')
                    ->modalWidth('4xl')
                    ->modalHeading(fn (?Client $record) => 'Editar Cliente: ' . ($record->name ?? 'Cliente'))
                    ->form(fn (Form $form) => static::form($form))
                    ->modalSubmitActionLabel('Guardar')
                    ->modalCancelActionLabel('Cancelar')
                    ->extraModalFooterActions(function ($action) {
                        $record = $action->getRecord();
                        
                        if (!$record || ($record->propuesta_enviada ?? false)) {
                            return [];
                        }
                        
                        return [
                            Tables\Actions\Action::make('enviar_propuesta')
                                ->label('Enviar Propuesta por Email')
                                ->icon('heroicon-o-envelope')
                                ->color('success')
                                ->requiresConfirmation()
                                ->modalHeading('Enviar propuesta por email')
                                ->modalDescription('¿Estás seguro de que deseas enviar la propuesta por email a este cliente?')
                                ->action(function (array $data) use ($action) {
                                    $record = $action->getRecord();

                                    if (! $record) {
                                        Notification::make()
                                            ->title('Error')
                                            ->body('No se encontró el registro.')
                                            ->danger()
                                            ->send();
                                        return;
                                    }

                                    // Tomar los datos actuales del formulario del modal y guardarlos primero
                                    $formData = $action->getFormData() ?? [];
                                    $data = $data ?: $formData;

                                    $record->fill($data);
                                    $record->save();
                                    $record->refresh();
                                    
                                    if (empty($record->email)) {
                                        Notification::make()
                                            ->title('Error')
                                            ->body('El cliente no tiene un correo electrónico.')
                                            ->danger()
                                            ->send();
                                        return;
                                    }

                                    try {
                                        $videoUrl = '';
                                        $proposedSite = $record->proposed_site ?? ($data['proposed_site'] ?? null);
                                        if ($proposedSite) {
                                            $sitio = \App\Models\Sitio::where('enlace', $proposedSite)->first();
                                            $videoUrl = $sitio?->video_url ?? '';
                                        }

                                        // Usar siempre los datos ya guardados/actualizados
                                        $response = Http::timeout(30)->post('https://n8n.srv1137974.hstgr.cloud/webhook/f1d17b9f-5def-4ee1-b539-d0cd5ec6be6a', [
                                            'name' => $record->name ?? '',
                                            'email' => $record->email ?? '',
                                            'website' => $record->website ?? '',
                                            'proposed_site' => $proposedSite ?? '',
                                            'video_url' => $videoUrl,
                                            'feedback' => $record->feedback ?? '',
                                            'propuesta' => $record->propuesta ?? '',
                                            'cliente_id' => $record->id ?? null,
                                            'cliente_nombre' => $record->name ?? '',
                                            'cliente_correo' => $record->email ?? '',
                                        ]);

                                        if ($response->successful()) {
                                            if (Schema::hasColumn('clientes_en_proceso', 'propuesta_enviada')) {
                                                $record->update(['propuesta_enviada' => true]);
                                            }
                                            
                                            Notification::make()
                                                ->title('Propuesta enviada')
                                                ->body('La propuesta se ha enviado correctamente a ' . ($record->email))
                                                ->success()
                                                ->send();

                                            // Redirigir a la lista después de enviar
                                            return redirect(static::getUrl('index'));
                                        } else {
                                            $errorBody = $response->body();
                                            $errorData = json_decode($errorBody, true);
                                            
                                            \Log::error('Error al enviar propuesta desde edit al webhook', [
                                                'status' => $response->status(),
                                                'body' => $errorBody,
                                                'cliente_id' => $record->id,
                                                'email' => $data['email'] ?? $record->email,
                                            ]);
                                            
                                            $errorMessage = 'Error ' . $response->status();
                                            if (isset($errorData['message'])) {
                                                $errorMessage .= ': ' . $errorData['message'];
                                                if (str_contains($errorData['message'], 'Respond to Webhook')) {
                                                    $errorMessage .= ' - El workflow de n8n necesita tener un nodo "Respond to Webhook" configurado.';
                                                }
                                            } else {
                                                $errorMessage .= ': ' . substr($errorBody, 0, 150);
                                            }
                                            
                                            Notification::make()
                                                ->title('Error al enviar propuesta')
                                                ->body($errorMessage)
                                                ->danger()
                                                ->persistent()
                                                ->send();
                                        }
                                    } catch (\Exception $e) {
                                        Notification::make()
                                            ->title('Error al enviar')
                                            ->body($e->getMessage())
                                            ->danger()
                                            ->send();
                                    }
                                }),
                        ];
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('enviar_propuestas')
                        ->label('Enviar propuestas seleccionadas')
                        ->icon('heroicon-o-envelope')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $enviados = 0;
                            $errores = 0;
                            $sinEmail = 0;

                            foreach ($records as $record) {
                                if (empty($record->email)) {
                                    $sinEmail++;
                                    continue;
                                }

                                try {
                                    $videoUrl = '';
                                    if ($record->proposed_site) {
                                        $sitio = \App\Models\Sitio::where('enlace', $record->proposed_site)->first();
                                        $videoUrl = $sitio?->video_url ?? '';
                                    }

                                    $response = Http::timeout(30)->post('https://n8n.srv1137974.hstgr.cloud/webhook/f1d17b9f-5def-4ee1-b539-d0cd5ec6be6a', [
                                        'name' => $record->name ?? '',
                                        'email' => $record->email ?? '',
                                        'website' => $record->website ?? '',
                                        'proposed_site' => $record->proposed_site ?? '',
                                        'video_url' => $videoUrl,
                                        'feedback' => $record->feedback ?? '',
                                        'propuesta' => $record->propuesta ?? '',
                                        'cliente_id' => $record->id ?? null,
                                        'cliente_nombre' => $record->name ?? '',
                                        'cliente_correo' => $record->email ?? '',
                                    ]);

                                    if ($response->successful()) {
                                        if (Schema::hasColumn('clientes_en_proceso', 'propuesta_enviada')) {
                                            $record->update(['propuesta_enviada' => true]);
                                        }
                                        $enviados++;
                                    } else {
                                        \Log::error('Error al enviar propuesta masiva al webhook', [
                                            'status' => $response->status(),
                                            'body' => $response->body(),
                                            'cliente_id' => $record->id,
                                            'email' => $record->email,
                                        ]);
                                        $errores++;
                                    }
                                } catch (\Exception $e) {
                                    $errores++;
                                }
                            }

                            $mensaje = [];
                            if ($enviados > 0) {
                                $mensaje[] = "{$enviados} propuesta(s) enviada(s) exitosamente";
                            }
                            if ($errores > 0) {
                                $mensaje[] = "{$errores} error(es) al enviar";
                            }
                            if ($sinEmail > 0) {
                                $mensaje[] = "{$sinEmail} cliente(s) sin email omitido(s)";
                            }

                            Notification::make()
                                ->title('Proceso completado')
                                ->body(implode('. ', $mensaje))
                                ->success($errores === 0 && $sinEmail === 0)
                                ->warning($errores > 0 || $sinEmail > 0)
                                ->send();
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        try {
            if (!Schema::hasTable('clientes_en_proceso')) {
                return parent::getEloquentQuery()->whereRaw('1 = 0');
            }

            $query = parent::getEloquentQuery()
                ->orderByDesc('created_at')
                ->orderByDesc('id');

            if (Schema::hasColumn('clientes_en_proceso', 'estado')) {
                $query->where('estado', 'pending');
            }

            return $query;
        } catch (\Exception $e) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Información del Cliente')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Nombre')
                            ->weight('bold')
                            ->size('lg'),
                        Infolists\Components\TextEntry::make('email')
                            ->label('Correo Electrónico')
                            ->url(fn ($record) => $record->email ? 'mailto:' . $record->email : null)
                            ->openUrlInNewTab()
                            ->icon('heroicon-o-envelope'),
                        Infolists\Components\TextEntry::make('telefono_1')
                            ->label('Teléfono 1')
                            ->url(fn ($record) => $record->telefono_1 ? 'tel:' . $record->telefono_1 : null)
                            ->icon('heroicon-o-phone'),
                        Infolists\Components\TextEntry::make('telefono_2')
                            ->label('Teléfono 2')
                            ->url(fn ($record) => $record->telefono_2 ? 'tel:' . $record->telefono_2 : null)
                            ->icon('heroicon-o-phone'),
                        Infolists\Components\TextEntry::make('address')
                            ->label('Dirección')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make('Sitio Web')
                    ->icon('heroicon-o-globe-alt')
                    ->schema([
                        Infolists\Components\TextEntry::make('website')
                            ->label('Sitio Web Actual')
                            ->url(fn ($record) => $record->website ? (str_starts_with($record->website, 'http') ? $record->website : 'https://' . $record->website) : null)
                            ->openUrlInNewTab()
                            ->icon('heroicon-o-link'),
                        Infolists\Components\TextEntry::make('proposed_site')
                            ->label('Sitio Propuesto')
                            ->url(fn ($record) => $record->proposed_site ? (str_starts_with($record->proposed_site, 'http') ? $record->proposed_site : 'https://' . $record->proposed_site) : null)
                            ->openUrlInNewTab()
                            ->color('success')
                            ->icon('heroicon-o-sparkles'),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make('Propuesta y Feedback')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Infolists\Components\TextEntry::make('propuesta')
                            ->label('Propuesta')
                            ->columnSpanFull()
                            ->visible(fn ($record) => !empty($record->propuesta)),
                        Infolists\Components\TextEntry::make('feedback')
                            ->label('Feedback')
                            ->columnSpanFull()
                            ->visible(fn ($record) => !empty($record->feedback)),
                        Infolists\Components\IconEntry::make('propuesta_enviada')
                            ->label('Propuesta Enviada')
                            ->boolean()
                            ->visible(fn () => Schema::hasColumn('clientes_en_proceso', 'propuesta_enviada')),
                        Infolists\Components\TextEntry::make('estado')
                            ->label('Estado')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending' => 'warning',
                                'accepted' => 'success',
                                'rejected' => 'danger',
                                'listo_para_enviar' => 'info',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'pending' => 'Pending',
                                'accepted' => 'Accepted',
                                'rejected' => 'Rejected',
                                'listo_para_enviar' => 'Listo para enviar',
                                default => $state,
                            }),
                    ]),
                Infolists\Components\Section::make('Información Adicional')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Fecha de Registro')
                            ->dateTime('d/m/Y H:i'),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Última Actualización')
                            ->dateTime('d/m/Y H:i'),
                    ])
                    ->columns(2),
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
            'index' => Pages\ListClienteEnProcesos::route('/'),
            'create' => Pages\CreateClienteEnProceso::route('/create'),
            'listos' => Pages\ListClientesListosParaEnviar::route('/listos-para-enviar'),
            // 'view' => Pages\ViewClienteEnProceso::route('/{record}'),
            // 'edit' => Pages\EditClienteEnProceso::route('/{record}/edit'),
        ];
    }
}
