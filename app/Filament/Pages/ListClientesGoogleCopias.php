<?php

namespace App\Filament\Pages;
use App\Models\WorkflowRun;
use App\Models\Client;
use App\Models\PropuestaPersonalizada;
use App\Filament\Resources\ClienteEnProcesoResource;
use App\Filament\Resources\ClientesGoogleEnviadasResource;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ListClientesGoogleCopias extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $view = 'filament.resources.clientes-google-copia-resource.pages.list-clientes-google-copias';
    
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Site Scraper';
    protected static ?string $title = 'Site Scraper';
    protected static ?string $navigationGroup = 'Herramientas';
    protected static ?int $navigationSort = 5;

    protected static ?string $slug = 'list-clientes-google-copias';

    public function mount(): void
    {
        // Abrir automáticamente la modal de "Iniciar Búsqueda" cuando se accede a la página
        $this->mountAction('start_search');
    }

    public static function getNavigationBadge(): ?string
    {
        try {
            $count = Client::count();
            return $count > 0 ? (string) $count : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public function getMaxContentWidth(): MaxWidth | string | null
    {
        return MaxWidth::Full;
    }

    public function getTitle(): string
    {
        return 'Site Scraper';
    }

    public function getHeading(): string
    {
        return 'Site Scraper';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(WorkflowRun::query()->orderBy('created_at', 'desc'))
            ->columns([
                Tables\Columns\TextColumn::make('data.nombre_lugar')
                    ->label('Lugar')
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A')
                    ->icon('heroicon-o-map-pin')
                    ->color('info')
                    ->weight('bold')
                    ->getStateUsing(fn ($record) => $record->data['nombre_lugar'] ?? null),
                Tables\Columns\TextColumn::make('data.industria')
                    ->label('Industria')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'tienda_ropa' => '👕 Tienda de Ropa',
                        'pizzeria' => '🍕 Pizzería',
                        'restaurante' => '🍽️ Restaurante',
                        'cafeteria' => '☕ Cafetería',
                        'farmacia' => '💊 Farmacia',
                        'supermercado' => '🛒 Supermercado',
                        'peluqueria' => '✂️ Peluquería / Salón de Belleza',
                        'gimnasio' => '💪 Gimnasio',
                        'veterinaria' => '🐾 Veterinaria',
                        'taller_mecanico' => '🔧 Taller Mecánico',
                        'otro' => '📝 Otro',
                        default => $state ?? 'N/A',
                    })
                    ->getStateUsing(fn ($record) => $record->data['industria'] ?? null),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'running' => 'info',
                        'failed' => 'danger',
                        'pending' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'completed' => '✅ Completado',
                        'running' => '🔄 Ejecutando',
                        'failed' => '❌ Fallido',
                        'pending' => '⏳ Pendiente',
                        default => $state,
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('progress')
                    ->label('Progreso')
                    ->badge()
                    ->color(fn ($state, $record) => match(true) {
                        $record && $record->status === 'completed' => 'success',
                        $record && $record->status === 'failed' => 'danger',
                        $state >= 75 => 'success',
                        $state >= 50 => 'info',
                        $state >= 25 => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state, $record) => 
                        $record && $record->status === 'completed' ? '100%' : 
                        ($state . '%')
                    )
                    ->sortable()
                    ->icon(fn ($state, $record) => match(true) {
                        $record && $record->status === 'completed' => 'heroicon-o-check-circle',
                        $record && $record->status === 'failed' => 'heroicon-o-x-circle',
                        $state >= 75 => 'heroicon-o-arrow-trending-up',
                        $state >= 50 => 'heroicon-o-clock',
                        $state >= 25 => 'heroicon-o-play',
                        default => 'heroicon-o-pause',
                    }),
                Tables\Columns\TextColumn::make('data.message')
                    ->label('Mensaje')
                    ->searchable()
                    ->wrap()
                    ->placeholder('N/A')
                    ->color(fn ($state, $record) => match($record->status ?? 'pending') {
                        'completed' => 'success',
                        'failed' => 'danger',
                        'running' => match(true) {
                            ($record->progress ?? 0) > 75 => 'success',
                            ($record->progress ?? 0) == 75 => 'warning',
                            ($record->progress ?? 0) >= 50 => 'info',
                            ($record->progress ?? 0) >= 25 => 'warning',
                            default => 'gray',
                        },
                        'pending' => 'warning',
                        default => 'gray',
                    })
                    ->icon(fn ($state, $record) => match($record->status ?? 'pending') {
                        'completed' => 'heroicon-o-check-circle',
                        'failed' => 'heroicon-o-exclamation-triangle',
                        'running' => 'heroicon-o-arrow-path',
                        'pending' => 'heroicon-o-clock',
                        default => null,
                    })
                    ->weight(fn ($record) => $record->status === 'running' ? 'bold' : 'normal')
                    ->getStateUsing(fn ($record) => $record->data['message'] ?? $record->step ?? 'N/A'),
                Tables\Columns\TextColumn::make('step')
                    ->label('Paso Actual')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->color(fn ($state, $record) => match($record->status ?? 'pending') {
                        'completed' => 'success',
                        'failed' => 'danger',
                        'running' => 'info',
                        'pending' => 'warning',
                        default => 'gray',
                    })
                    ->placeholder('N/A')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'pending' => 'Pendiente',
                        'running' => 'Ejecutando',
                        'completed' => 'Completado',
                        'failed' => 'Fallido',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('view_result')
                    ->label('Ver Resultado')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->visible(fn ($record) => $record && $record->result !== null)
                    ->modalHeading('Resultado del Workflow')
                    ->modalContent(fn ($record) => $record ? view('filament.pages.workflow-result', [
                        'result' => $record->result,
                        'data' => $record->data,
                    ]) : 'No hay datos disponibles')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar'),
                Tables\Actions\Action::make('view_error')
                    ->label('Ver Error')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('danger')
                    ->visible(fn ($record) => $record && $record->status === 'failed')
                    ->modalHeading('Detalles del Error')
                    ->modalContent(fn ($record) => view('filament.pages.workflow-error', [
                        'record' => $record,
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar'),
                Tables\Actions\Action::make('stop')
                    ->label('Detener')
                    ->icon('heroicon-o-stop-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record && $record->status === 'running')
                    ->requiresConfirmation()
                    ->modalHeading('Detener Workflow')
                    ->modalDescription('¿Estás seguro de que deseas detener este workflow? El proceso se cancelará y no se completará.')
                    ->action(function ($record) {
                        try {
                            $record->update([
                                'status' => 'failed',
                                'step' => 'Cancelado manualmente',
                                'error_message' => 'Workflow detenido manualmente por el usuario',
                                'completed_at' => now(),
                            ]);

                            // Procesar el siguiente workflow en cola
                            WorkflowRun::processNextPendingWorkflow();

                            Notification::make()
                                ->title('Workflow detenido')
                                ->body('El workflow ha sido detenido correctamente.')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Error al detener workflow')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                Tables\Actions\Action::make('retry')
                    ->label('Reintentar')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn ($record) => $record && $record->status === 'failed')
                    ->requiresConfirmation()
                    ->modalHeading('Reintentar Workflow')
                    ->modalDescription('¿Estás seguro de que deseas reintentar este workflow? Se creará un nuevo registro y se enviará automáticamente al webhook.')
                    ->action(function ($record) {
                        try {
                            $jobId = Str::uuid();
                            $webhookUrl = 'https://n8n.srv1137974.hstgr.cloud/webhook/0c01d9a1-788c-44d2-9c1b-9457901d0a3c';
                            
                            // Obtener datos originales si existen
                            $originalData = $record->data ?? [];
                            
                            // Crear nuevo registro
                            $newWorkflowRun = WorkflowRun::create([
                                'job_id' => $jobId,
                                'status' => 'pending',
                                'progress' => 0,
                                'step' => 'En cola',
                                'workflow_name' => $record->workflow_name,
                                'data' => $originalData,
                            ]);

                            // Preparar payload para n8n
                            $payload = [
                                'job_id' => $jobId,
                                'progress_url' => url('/api/n8n/progress'),
                                'nombre_lugar' => $originalData['nombre_lugar'] ?? '',
                                'industria' => $originalData['industria'] ?? '',
                            ];

                            // Llamar al webhook de n8n
                            $response = Http::timeout(120)->post($webhookUrl, $payload);

                            if ($response->successful()) {
                                $newWorkflowRun->update([
                                    'status' => 'running',
                                    'step' => 'Iniciado - Buscando lugares',
                                    'started_at' => now(),
                                ]);

                                Notification::make()
                                    ->title('✅ Workflow reintentado')
                                    ->body('El workflow se ha reenviado correctamente. ID: ' . substr($jobId, 0, 8))
                                    ->success()
                                    ->send();
                            } else {
                                $newWorkflowRun->update([
                                    'status' => 'failed',
                                    'step' => 'Error al iniciar búsqueda',
                                    'error_message' => 'Error al iniciar workflow: ' . $response->status(),
                                    'completed_at' => null,
                                ]);

                                Notification::make()
                                    ->title('⚠️ Error al reintentar')
                                    ->body('El webhook respondió con error: ' . $response->status())
                                    ->danger()
                                    ->send();
                            }
                        } catch (\Exception $e) {
                            if (isset($newWorkflowRun)) {
                                $newWorkflowRun->update([
                                    'status' => 'failed',
                                    'step' => 'Error al iniciar',
                                    'error_message' => $e->getMessage(),
                                    'completed_at' => null,
                                ]);
                            }

                            Notification::make()
                                ->title('Error al reintentar')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->recordClasses(fn ($record) => match($record->status) {
                'failed' => 'border-l-4 border-l-danger-500 bg-danger-50/50 dark:bg-danger-900/10',
                'running' => 'border-l-4 border-l-primary-500 bg-primary-50/50 dark:bg-primary-900/10',
                'completed' => 'border-l-4 border-l-success-500 bg-success-50/50 dark:bg-success-900/10',
                'pending' => 'border-l-4 border-l-warning-500 bg-warning-50/50 dark:bg-warning-900/10',
                default => '',
            })
            ->poll('3s')
            ->defaultSort('created_at', 'desc');
    }

    protected function getHeaderActions(): array
    {
        $clientesGoogleUrl = ClienteEnProcesoResource::getUrl('index');
        $listosUrl = ClienteEnProcesoResource::getUrl('listos');
        $propuestasUrl = ClientesGoogleEnviadasResource::getUrl('index');
        $siteScraperUrl = url('/admin/list-clientes-google-copias');
        $currentUrl = url()->current();

        // Contar clientes pendientes
        $pendingCount = Client::where('estado', 'pending')->count();
        $listosCount = Client::where('estado', 'listo_para_enviar')->count();
        $propuestasCount = Client::where('estado', 'propuesta_enviada')->count();

        return [
            Action::make('start_search')
                ->label('Iniciar Búsqueda')
                ->icon('heroicon-o-magnifying-glass')
                ->color('gray')
                ->form([
                    Forms\Components\TextInput::make('nombre_lugar')
                        ->label('Lugar')
                        ->placeholder('Ej: Heredia, San José, etc.')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('industria')
                        ->label('Tipo de Negocio')
                        ->options([
                            'tienda_ropa' => '👕 Tienda de Ropa',
                            'pizzeria' => '🍕 Pizzería',
                            'restaurante' => '🍽️ Restaurante',
                            'cafeteria' => '☕ Cafetería',
                            'farmacia' => '💊 Farmacia',
                            'supermercado' => '🛒 Supermercado',
                            'peluqueria' => '✂️ Peluquería / Salón de Belleza',
                            'gimnasio' => '💪 Gimnasio',
                            'veterinaria' => '🐾 Veterinaria',
                            'taller_mecanico' => '🔧 Taller Mecánico',
                            'otro' => '📝 Otro',
                        ])
                        ->required()
                        ->native(false),
                ])
                ->action(function (array $data) {
                    try {
                        $jobId = Str::uuid();
                        $webhookUrl = 'https://n8n.srv1137974.hstgr.cloud/webhook/0c01d9a1-788c-44d2-9c1b-9457901d0a3c';

                        // Crear el registro del workflow
                        $workflowRun = WorkflowRun::create([
                            'job_id' => $jobId,
                            'status' => 'pending',
                            'progress' => 0,
                            'step' => 'En cola',
                            'workflow_name' => 'Búsqueda: ' . ($data['nombre_lugar'] ?? 'Sin nombre'),
                            'data' => [
                                'nombre_lugar' => $data['nombre_lugar'],
                                'industria' => $data['industria'],
                            ],
                        ]);

                        // Preparar payload para n8n
                        $payload = [
                            'job_id' => $jobId,
                            'progress_url' => url('/api/n8n/progress'),
                            'nombre_lugar' => $data['nombre_lugar'],
                            'industria' => $data['industria'],
                        ];

                        // Llamar al webhook de n8n
                        $response = Http::timeout(120)->post($webhookUrl, $payload);

                        if ($response->successful()) {
                            $workflowRun->update([
                                'status' => 'running',
                                'step' => 'Iniciado - Buscando lugares',
                                'started_at' => now(),
                            ]);

                            Notification::make()
                                ->title('Búsqueda iniciada')
                                ->body('La búsqueda se ha iniciado correctamente. ID: ' . substr($jobId, 0, 8))
                                ->success()
                                ->send();
                        } else {
                            $workflowRun->update([
                                'status' => 'failed',
                                'step' => 'Error al iniciar búsqueda',
                                'error_message' => 'Error al iniciar workflow: ' . $response->status(),
                                'completed_at' => null,
                            ]);

                            Notification::make()
                                ->title('Error al iniciar búsqueda')
                                ->body('El webhook respondió con error: ' . $response->status())
                                ->danger()
                                ->send();
                        }
                    } catch (\Exception $e) {
                        if (isset($workflowRun)) {
                            $workflowRun->update([
                                'status' => 'failed',
                                'step' => 'Error al iniciar',
                                'error_message' => $e->getMessage(),
                                'completed_at' => null,
                            ]);
                        }

                        Notification::make()
                            ->title('Error')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            Action::make('site_scraper')
                ->label('Site Scraper')
                ->icon('heroicon-o-magnifying-glass-plus')
                ->url($siteScraperUrl)
                ->color($currentUrl === $siteScraperUrl ? 'primary' : 'gray'),
            Action::make('clientes_google')
                ->label('Clientes Google')
                ->url($clientesGoogleUrl)
                ->color($currentUrl === $clientesGoogleUrl ? 'primary' : 'gray')
                ->badge($pendingCount > 0 ? (string) $pendingCount : null)
                ->badgeColor('warning'),
            Action::make('listos_para_enviar')
                ->label('Listos para Enviar')
                ->url($listosUrl)
                ->color($currentUrl === $listosUrl ? 'primary' : 'gray')
                ->badge($listosCount > 0 ? (string) $listosCount : null)
                ->badgeColor('info'),
            Action::make('propuestas_enviadas')
                ->label('Propuestas Enviadas')
                ->url($propuestasUrl)
                ->color($currentUrl === $propuestasUrl ? 'primary' : 'gray')
                ->badge($propuestasCount > 0 ? (string) $propuestasCount : null)
                ->badgeColor('success'),
            Action::make('propuesta_personalizada')
                ->label('Propuesta Personalizada')
                ->icon('heroicon-o-envelope')
                ->color('gray')
                ->modalHeading('📧 Enviar Propuesta Personalizada')
                ->modalWidth('2xl')
                ->form([
                    Forms\Components\Select::make('cliente_id')
                        ->label('Cliente')
                        ->options(Client::orderBy('name')->pluck('name', 'id'))
                        ->searchable()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function (Forms\Set $set, $state) {
                            if ($state) {
                                $client = Client::find($state);
                                if ($client?->email) {
                                    $set('email', $client->email);
                                }
                            }
                        }),
                    Forms\Components\TextInput::make('email')
                        ->label('📧 Correo Electrónico')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('subject')
                        ->label('Asunto')
                        ->required()
                        ->maxLength(255)
                        ->default('Propuesta Personalizada'),
                    Forms\Components\Textarea::make('ai_prompt')
                        ->label('✨ Prompt para AI (Opcional)')
                        ->placeholder('Ej: Genera un email profesional de propuesta para un negocio de restaurante, mencionando servicios de diseño web y marketing digital...')
                        ->rows(3)
                        ->helperText('Describe el tipo de email y lo que quieres que diga. Si está vacío, se generará una propuesta genérica.')
                        ->columnSpanFull(),
                    Forms\Components\Actions::make([
                        Forms\Components\Actions\Action::make('llenar_con_ai')
                            ->label('✨ Llenar con AI')
                            ->icon('heroicon-o-sparkles')
                            ->color('primary')
                            ->action(function (Forms\Set $set, Forms\Get $get) {
                                $apiKey = config('services.openai.api_key');
                                if (empty($apiKey)) {
                                    Notification::make()
                                        ->title('Falta OPENAI_API_KEY')
                                        ->body('Configura la API key en el servidor para usar AI.')
                                        ->danger()
                                        ->send();
                                    return;
                                }
                                
                                $clienteId = $get('cliente_id');
                                $client = $clienteId ? Client::find($clienteId) : null;
                                $clientName = $client?->name ?? 'el cliente';
                                $clientWebsite = $client?->website ?? '';
                                
                                $prompt = $get('ai_prompt');
                                if (empty($prompt)) {
                                    $prompt = "Genera un email profesional de propuesta personalizada para {$clientName}";
                                    if ($clientWebsite) {
                                        $prompt .= " cuyo sitio web es {$clientWebsite}";
                                    }
                                    $prompt .= ". El email debe ser persuasivo, profesional y enfocado en ofrecer servicios de diseño web, marketing digital y desarrollo de software.";
                                } else {
                                    $prompt = "Genera un email profesional. {$prompt}";
                                    if ($clientName !== 'el cliente') {
                                        $prompt .= " El cliente se llama {$clientName}.";
                                    }
                                    if ($clientWebsite) {
                                        $prompt .= " Su sitio web es {$clientWebsite}.";
                                    }
                                }
                                
                                try {
                                    Notification::make()
                                        ->title('Generando email con AI...')
                                        ->body('Por favor espera mientras AI genera el contenido.')
                                        ->info()
                                        ->send();
                                    
                                    $response = Http::withToken($apiKey)
                                        ->acceptJson()
                                        ->timeout(120)
                                        ->post('https://api.openai.com/v1/chat/completions', [
                                            'model' => 'gpt-4o-mini',
                                            'response_format' => ['type' => 'json_object'],
                                            'messages' => [
                                                [
                                                    'role' => 'system',
                                                    'content' => 'Eres un experto en marketing digital y redacción de emails comerciales. Genera emails profesionales, persuasivos y directos. Responde SOLO con JSON que contenga "subject" (asunto del email, máximo 10 palabras) y "body" (cuerpo del email completo). NO incluyas mensajes de cierre como "Si necesitas alguna modificación", "No dudes en contactarme", etc. Al final del body, SIEMPRE incluye esta firma: "\n\nWeb Solutions\nwebsolutionscrnow@gmail.com\n+506 8806 1829 (WhatsApp)\nwebsolutions.work"',
                                                ],
                                                [
                                                    'role' => 'user',
                                                    'content' => $prompt . ' Responde en JSON con "subject" y "body".',
                                                ],
                                            ],
                                        ]);
                                    
                                    if ($response->successful()) {
                                        $responseData = $response->json();
                                        $content = $responseData['choices'][0]['message']['content'] ?? '';
                                        
                                        if (empty($content)) {
                                            throw new \RuntimeException('La respuesta de AI está vacía.');
                                        }
                                        
                                        $data = is_string($content) ? json_decode($content, true) : $content;
                                        
                                        if (!is_array($data)) {
                                            throw new \RuntimeException('La respuesta de AI no es JSON válido.');
                                        }
                                        
                                        $emailSubject = trim($data['subject'] ?? 'Propuesta Personalizada');
                                        $emailBody = trim($data['body'] ?? '');
                                        
                                        if (empty($emailBody)) {
                                            throw new \RuntimeException('El cuerpo del email está vacío.');
                                        }
                                        
                                        // Limpiar mensajes de cierre comunes
                                        $emailBody = preg_replace('/\s*(Si necesitas alguna modificación.*?\.|No dudes en.*?\.|Estoy a tu disposición.*?\.|Quedo a la espera.*?\.).*/is', '', $emailBody);
                                        $emailBody = trim($emailBody);
                                        
                                        $set('subject', $emailSubject);
                                        $set('body', $emailBody);
                                        
                                        Notification::make()
                                            ->title('✅ Email generado con AI')
                                            ->body('El contenido del email ha sido generado exitosamente.')
                                            ->success()
                                            ->send();
                                    } else {
                                        throw new \Exception('Error en la respuesta de OpenAI: ' . $response->status());
                                    }
                                } catch (\Exception $e) {
                                    Notification::make()
                                        ->title('❌ Error al generar con AI')
                                        ->body($e->getMessage())
                                        ->danger()
                                        ->send();
                                }
                            }),
                    ])->columnSpanFull(),
                    Forms\Components\Textarea::make('body')
                        ->label('Mensaje')
                        ->required()
                        ->rows(10)
                        ->columnSpanFull(),
                ])
                ->action(function (array $data) {
                    try {
                        $client = Client::find($data['cliente_id']);
                        
                        // Enviar email
                        Mail::raw($data['body'], function ($message) use ($data, $client) {
                            $message->from('websolutionscrnow@gmail.com', 'Web Solutions')
                                    ->to($data['email'])
                                    ->subject($data['subject']);
                        });
                        
                        // Guardar en la base de datos
                        PropuestaPersonalizada::create([
                            'cliente_id' => $data['cliente_id'] ?? null,
                            'cliente_nombre' => $client?->name ?? 'N/A',
                            'email' => $data['email'],
                            'subject' => $data['subject'],
                            'body' => $data['body'],
                            'ai_prompt' => $data['ai_prompt'] ?? null,
                            'user_id' => auth()->id(),
                        ]);
                        
                        Notification::make()
                            ->title('✅ Email enviado')
                            ->body('La propuesta personalizada ha sido enviada a ' . $data['email'] . ' y guardada en el registro.')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('❌ Error')
                            ->body('Error al enviar el email: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}

