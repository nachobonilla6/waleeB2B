<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Filament\Actions;

class SiteScraper extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static string $view = 'filament.pages.site-scraper';
    protected static ?string $navigationLabel = 'Site Scraper';
    protected static ?string $title = 'Site Scraper';
    protected static ?string $navigationGroup = 'Herramientas';
    protected static ?int $navigationSort = 10;
    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];
    public bool $isSubmitting = false;
    public bool $isSuccess = false;

    // Webhook URL (producción)
    protected string $webhookUrl = 'https://n8n.srv1137974.hstgr.cloud/webhook/110bdb87-978a-4635-8783-cf9a9c80e322';

    public function mount(): void
    {
        $this->form->fill([
            'location' => '',
            'business_type' => '',
            'other_business_type' => '',
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('config_bot')
                ->label('Configuración del Bot')
                ->icon('heroicon-o-cog-6-tooth')
                ->url('https://n8n.srv1137974.hstgr.cloud/workflow/3OwxkPVt7soP2dzJ')
                ->openUrlInNewTab()
                ->color('gray'),
            Actions\Action::make('clientes')
                ->label('Clientes en Proceso')
                ->icon('heroicon-o-users')
                ->url(\App\Filament\Resources\ClientResource::getUrl('index'))
                ->color('pink'),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('location')
                    ->label('Ubicación')
                    ->placeholder('Ej: San José, Costa Rica, Nueva York, etc.')
                    ->required()
                    ->helperText('Ingresa una ciudad o dirección. El sistema buscará automáticamente negocios en esa ubicación.')
                    ->extraAttributes([
                        'id' => 'location-autocomplete',
                        'autocomplete' => 'off',
                        'class' => 'text-lg',
                    ])
                    ->columnSpanFull(),

                Select::make('business_type')
                    ->label('Tipo de Negocio')
                    ->placeholder('Selecciona el tipo de negocio')
                    ->options([
                        'restaurante' => '🍽️ Restaurante',
                        'cafe' => '☕ Café',
                        'bar' => '🍺 Bar / Pub',
                        'hotel' => '🏨 Hotel',
                        'hostal' => '🛏️ Hostal',
                        'tienda' => '🛍️ Tienda',
                        'supermercado' => '🛒 Supermercado',
                        'farmacia' => '💊 Farmacia',
                        'servicios' => '🔧 Servicios',
                        'taller_mecanico' => '🔩 Taller Mecánico',
                        'lavanderia' => '🧺 Lavandería',
                        'peluqueria' => '✂️ Peluquería',
                        'salud' => '🏥 Salud',
                        'clinica' => '🏥 Clínica',
                        'dentista' => '🦷 Dentista',
                        'veterinaria' => '🐾 Veterinaria',
                        'gimnasio' => '💪 Gimnasio',
                        'spa' => '🧘 Spa / Bienestar',
                        'educacion' => '🎓 Educación',
                        'escuela' => '📚 Escuela',
                        'universidad' => '🎓 Universidad',
                        'inmobiliaria' => '🏠 Inmobiliaria',
                        'abogado' => '⚖️ Abogado',
                        'contador' => '📊 Contador',
                        'arquitecto' => '🏗️ Arquitecto',
                        'diseño' => '🎨 Diseño Gráfico',
                        'marketing' => '📢 Marketing',
                        'tecnologia' => '💻 Tecnología',
                        'construccion' => '🏗️ Construcción',
                        'transporte' => '🚗 Transporte',
                        'turismo' => '✈️ Turismo',
                        'eventos' => '🎉 Eventos',
                        'fotografia' => '📷 Fotografía',
                        'musica' => '🎵 Música',
                        'arte' => '🖼️ Arte',
                        'otro' => '➕ Otro (Personalizado)',
                    ])
                    ->required()
                    ->reactive()
                    ->native(false)
                    ->searchable(true)
                    ->helperText('Elige la categoría que mejor describe el tipo de negocio que buscas. Puedes escribir para buscar o seleccionar "Otro (Personalizado)" para especificar.')
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state !== 'otro') {
                            $set('other_business_type', '');
                        }
                    }),

                TextInput::make('other_business_type')
                    ->label('Tipo de Negocio Personalizado')
                    ->placeholder('Ej: Taller de bicicletas, Estudio de yoga, Agencia de viajes, etc.')
                    ->visible(fn (callable $get) => $get('business_type') === 'otro')
                    ->required(fn (callable $get) => $get('business_type') === 'otro')
                    ->helperText('Ingresa el tipo de negocio específico que no aparece en la lista. Este será el tipo de negocio que se buscará.')
                    ->maxLength(255),
            ])
            ->statePath('data')
            ->columns(2);
    }

    public function submit(): void
    {
        $this->isSubmitting = true;
        $this->isSuccess = false;
        $data = $this->form->getState();

        try {
            if (empty($data['location']) || empty($data['business_type'])) {
                throw new \Exception('Por favor completa todos los campos requeridos.');
            }

            if ($data['business_type'] === 'otro' && !empty($data['other_business_type'])) {
                $data['business_type'] = $data['other_business_type'];
                unset($data['other_business_type']);
            } elseif ($data['business_type'] === 'otro' && empty($data['other_business_type'])) {
                throw new \Exception('Por favor especifica el tipo de negocio.');
            }

            $data['timestamp'] = now()->toIso8601String();

            $webhookData = [
                'location' => $data['location'],
                'business_type' => $data['business_type'],
                'timestamp' => $data['timestamp'],
            ];

            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($this->webhookUrl, $webhookData);

            if ($response->successful()) {
                $this->form->fill([
                    'location' => '',
                    'business_type' => '',
                    'other_business_type' => '',
                ]);
                
                $this->isSuccess = true;

                Notification::make()
                    ->title('Datos enviados')
                    ->body('Tus datos se han enviado. Pronto recibirás los resultados.')
                    ->success()
                    ->send();
            } else {
                $errorBody = $response->body();
                throw new \Exception('Error al enviar datos. Estado: ' . $response->status() . ($errorBody ? ' - ' . $errorBody : ''));
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Notification::make()
                ->title('Error de conexión')
                ->body('No se pudo conectar al webhook. Verifica tu conexión a internet.')
                ->danger()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error al enviar datos')
                ->body($e->getMessage())
                ->danger()
                ->send();
        } finally {
            $this->isSubmitting = false;
        }
    }
    
    public function resetForm(): void
    {
        $this->isSuccess = false;
        $this->form->fill([
            'location' => '',
            'business_type' => '',
            'other_business_type' => '',
        ]);
    }
}


