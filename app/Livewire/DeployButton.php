<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use App\Models\SupportCase;
use App\Models\Client;
use App\Models\Cliente;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;

class DeployButton extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public function ticketsPreviewAction(): Action
    {
        return Action::make('tickets_preview')
            ->label('Propuesta Personalizada')
            ->icon('heroicon-o-sparkles')
            ->color('primary')
            ->size('sm')
            ->modalHeading('💼 Propuesta Personalizada')
            ->modalWidth('4xl')
            ->form([
                Select::make('tipo_negocio')
                    ->label('Tipo de Negocio')
                    ->options([
                        'client' => 'Cliente Google (clientes_en_proceso)',
                        'cliente' => 'Cliente Activo (clientes)',
                        'otro' => 'Agregar Otro',
                    ])
                    ->default('client')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Set $set, $state) {
                        $set('negocio_id', null);
                        $set('email', null);
                        $set('website', null);
                    }),
                Select::make('negocio_id')
                    ->label('Seleccionar Negocio')
                    ->options(function (Get $get) {
                        $tipo = $get('tipo_negocio');
                        if ($tipo === 'client') {
                            return Client::orderBy('name')->pluck('name', 'id')->toArray();
                        } elseif ($tipo === 'cliente') {
                            return Cliente::orderBy('nombre_empresa')->pluck('nombre_empresa', 'id')->toArray();
                        }
                        return [];
                    })
                    ->searchable()
                    ->live()
                    ->visible(fn (Get $get) => in_array($get('tipo_negocio'), ['client', 'cliente']))
                    ->afterStateUpdated(function (Set $set, Get $get, $state) {
                        if (!$state) return;
                        
                        $tipo = $get('tipo_negocio');
                        if ($tipo === 'client') {
                            $client = Client::find($state);
                            if ($client) {
                                $set('email', $client->email ?? '');
                                $set('website', $client->website ?? '');
                            }
                        } elseif ($tipo === 'cliente') {
                            $cliente = Cliente::find($state);
                            if ($cliente) {
                                $set('email', $cliente->correo ?? '');
                                $set('website', $cliente->url_sitio ?? '');
                            }
                        }
                    }),
                TextInput::make('email')
                    ->label('📧 Correo Electrónico')
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('website')
                    ->label('🌐 Sitio Web')
                    ->url()
                    ->required()
                    ->maxLength(255)
                    ->helperText('URL del sitio web a analizar'),
                Textarea::make('email_generado')
                    ->label('📝 Email Generado con AI')
                    ->rows(10)
                    ->dehydrated()
                    ->helperText('El email se generará automáticamente después de analizar el sitio web. Puedes editarlo antes de enviar.'),
            ])
            ->modalSubmitActionLabel('Enviar al Webhook')
            ->modalCancelActionLabel('Cancelar')
            ->extraModalFooterActions([
                Action::make('analizar_ai')
                    ->label('✨ Analizar con AI')
                    ->icon('heroicon-o-sparkles')
                    ->color('success')
                    ->action(function (Get $get, Set $set) {
                        $website = $get('website');
                        
                        if (!$website) {
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
                            Notification::make()
                                ->title('Analizando sitio web...')
                                ->body('Por favor espera mientras AI analiza el sitio web.')
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
                                            'content' => 'Eres un experto en marketing digital y desarrollo web. Analiza sitios web y genera propuestas personalizadas profesionales. Genera un email completo y profesional que incluya: 1) Saludo personalizado, 2) Análisis del sitio web con feedback constructivo, 3) Propuesta de mejoras específicas, 4) Beneficios de trabajar con nosotros, 5) Llamado a la acción. El email debe ser profesional, persuasivo y enfocado en ayudar al cliente.',
                                        ],
                                        [
                                            'role' => 'user',
                                            'content' => 'Analiza el sitio web: ' . $website . ' y genera un email profesional completo con propuesta personalizada, feedback y mejoras sugeridas. El email debe ser profesional y persuasivo.',
                                        ],
                                    ],
                                ]);
                            
                            if ($response->successful()) {
                                $responseData = $response->json();
                                $emailContent = $responseData['choices'][0]['message']['content'] ?? '';
                                
                                if (empty($emailContent)) {
                                    throw new \RuntimeException('La respuesta de AI está vacía.');
                                }
                                
                                $set('email_generado', trim($emailContent));
                                
                                Notification::make()
                                    ->title('✅ Análisis completado')
                                    ->body('El email ha sido generado con AI exitosamente.')
                                    ->success()
                                    ->send();
                            } else {
                                throw new \Exception('Error en la respuesta de OpenAI: ' . $response->status());
                            }
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('❌ Error al analizar')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->action(function (array $data) {
                if (empty($data['email_generado'])) {
                    Notification::make()
                        ->title('⚠️ Email no generado')
                        ->body('Por favor, analiza el sitio web con AI antes de enviar.')
                        ->warning()
                        ->send();
                    return;
                }
                
                try {
                    $webhookData = [
                        'tipo_negocio' => $data['tipo_negocio'] ?? 'otro',
                        'negocio_id' => $data['negocio_id'] ?? null,
                        'email' => $data['email'] ?? '',
                        'website' => $data['website'] ?? '',
                        'email_generado' => $data['email_generado'] ?? '',
                        'timestamp' => now()->toIso8601String(),
                        'triggered_by' => auth()->user()->name ?? 'Admin',
                    ];
                    
                    $response = Http::timeout(120)->post(
                        'https://n8n.srv1137974.hstgr.cloud/webhook-test/011f979b-3755-436c-a212-2efad36e05e7',
                        $webhookData
                    );
                    
                    if ($response->successful()) {
                        Notification::make()
                            ->title('✅ Propuesta enviada')
                            ->body('La propuesta personalizada ha sido enviada al webhook correctamente.')
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('⚠️ Error en webhook')
                            ->body('El webhook respondió con error: ' . $response->status())
                            ->warning()
                            ->send();
                    }
                } catch (\Exception $e) {
                    Notification::make()
                        ->title('❌ Error')
                        ->body('Error al enviar al webhook: ' . $e->getMessage())
                        ->danger()
                        ->send();
                }
            });
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(SupportCase::query())
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Asunto')
                    ->searchable()
                    ->limit(40)
                    ->tooltip(fn (SupportCase $record): string => $record->title),
                Tables\Columns\TextColumn::make('status')
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
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo')
                    ->searchable()
                    ->icon('heroicon-o-envelope'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'open' => 'Abierto',
                        'in_progress' => 'En Progreso',
                        'resolved' => 'Resuelto',
                        'closed' => 'Cerrado',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->url(fn (SupportCase $record) => \App\Filament\Resources\SupportCaseResource::getUrl('view', ['record' => $record])),
            ])
            ->defaultPaginationPageOption(10)
            ->paginationPageOptions([5, 10, 25, 50]);
    }

    public function chatAction(): Action
    {
        return Action::make('chat')
            ->label('Walee Chat')
            ->icon('heroicon-o-chat-bubble-left-right')
            ->color('info')
            ->size('sm')
            ->url('/walee');
    }

    public function extraerClientesAction(): Action
    {
        return Action::make('extraer_clientes')
            ->label('Extraer Clientes')
            ->icon('heroicon-o-magnifying-glass')
            ->color('warning')
            ->size('sm')
            ->url('/admin/clientes-google-copias');
    }

    public function render()
    {
        return view('livewire.deploy-button');
    }
}
