<?php

namespace App\Filament\Resources\ClientesGoogleEnviadasResource\Pages;

use App\Filament\Resources\ClienteEnProcesoResource;
use App\Filament\Resources\ClientesGoogleEnviadasResource;
use App\Models\WorkflowRun;
use App\Models\Client;
use App\Models\PropuestaPersonalizada;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;
use App\Filament\Resources\ClientesGoogleEnviadasResource\Widgets\PropuestasEnviadasStatsWidget;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ListClientesGoogleEnviadas extends ListRecords
{
    protected static string $resource = ClientesGoogleEnviadasResource::class;

    public function getMaxContentWidth(): MaxWidth | string | null
    {
        return MaxWidth::Full;
    }

    public function getTitle(): string
    {
        return 'Propuestas Enviadas';
    }

    public function getHeading(): string
    {
        return 'Propuestas Enviadas';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PropuestasEnviadasStatsWidget::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        $clientesGoogleUrl = ClienteEnProcesoResource::getUrl('index');
        $listosUrl = ClienteEnProcesoResource::getUrl('listos');
        $propuestasUrl = ClientesGoogleEnviadasResource::getUrl('index');
        $siteScraperUrl = url('/admin/list-clientes-google-copias');
        $currentUrl = url()->current();

        // Contar clientes pendientes
        $pendingCount = \App\Models\Client::where('estado', 'pending')->count();
        $listosCount = \App\Models\Client::where('estado', 'listo_para_enviar')->count();
        $propuestasCount = \App\Models\Client::where('estado', 'propuesta_enviada')->count();

        return [
            Actions\Action::make('start_search')
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
            Actions\Action::make('site_scraper')
                ->label('Site Scraper')
                ->icon('heroicon-o-magnifying-glass-plus')
                ->url($siteScraperUrl)
                ->color($currentUrl === $siteScraperUrl ? 'primary' : 'gray'),
            Actions\Action::make('clientes_google')
                ->label('Clientes Google')
                ->url($clientesGoogleUrl)
                ->color($currentUrl === $clientesGoogleUrl ? 'primary' : 'gray')
                ->badge($pendingCount > 0 ? (string) $pendingCount : null)
                ->badgeColor('warning'),
            Actions\Action::make('listos_para_enviar')
                ->label('Listos para Enviar')
                ->url($listosUrl)
                ->color($currentUrl === $listosUrl ? 'primary' : 'gray')
                ->badge($listosCount > 0 ? (string) $listosCount : null)
                ->badgeColor('info'),
            Actions\Action::make('propuestas_enviadas')
                ->label('Propuestas Enviadas')
                ->url($propuestasUrl)
                ->color($currentUrl === $propuestasUrl ? 'primary' : 'gray')
                ->badge($propuestasCount > 0 ? (string) $propuestasCount : null)
                ->badgeColor('success'),
            Actions\Action::make('propuesta_personalizada')
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
                            ->action(function (Set $set, Get $get) {
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
                            'tipo' => 'propuesta_personalizada',
                        ]);
                        
                        // Marcar el contacto como propuesta personalizada enviada
                        if ($client) {
                            $client->update([
                                'propuesta_enviada' => true,
                                'estado' => 'propuesta_personalizada_enviada'
                            ]);
                        }
                        
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

