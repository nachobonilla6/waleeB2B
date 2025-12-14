<?php

namespace App\Filament\Resources\ClientPropuestaEnviadaResource\Pages;

use App\Filament\Resources\ClientPropuestaEnviadaResource;
use App\Models\Client;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class ListClientPropuestaEnviadas extends ListRecords
{
    protected static string $resource = ClientPropuestaEnviadaResource::class;
    
    protected static string $view = 'filament.resources.client-propuesta-enviada-resource.pages.list-client-propuesta-enviadas';

    protected function getHeaderActions(): array
    {
        return [
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
                                            'messages' => [
                                                [
                                                    'role' => 'system',
                                                    'content' => 'Eres un experto en marketing digital y redacción de emails comerciales. Genera emails profesionales, persuasivos y enfocados en ayudar al cliente.',
                                                ],
                                                [
                                                    'role' => 'user',
                                                    'content' => $prompt,
                                                ],
                                            ],
                                        ]);
                                    
                                    if ($response->successful()) {
                                        $responseData = $response->json();
                                        $emailContent = $responseData['choices'][0]['message']['content'] ?? '';
                                        
                                        if (empty($emailContent)) {
                                            throw new \RuntimeException('La respuesta de AI está vacía.');
                                        }
                                        
                                        $set('body', trim($emailContent));
                                        
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
                        
                        Mail::raw($data['body'], function ($message) use ($data, $client) {
                            $message->to($data['email'])
                                    ->subject($data['subject']);
                        });
                        
                        Notification::make()
                            ->title('✅ Email enviado')
                            ->body('La propuesta personalizada ha sido enviada a ' . $data['email'])
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







