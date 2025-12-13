<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Illuminate\Support\HtmlString;

class BotConfiguracion extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';
    protected static ?string $navigationLabel = 'Configuración App';
    protected static ?string $title = 'Configuración de Aplicación';
    protected static ?string $navigationGroup = 'Soporte';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
    protected static ?int $navigationSort = 50;

    protected static string $view = 'filament.pages.bot-configuracion';

    public static function getNavigationBadge(): ?string
    {
        return '1';
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            // Step 1 - Conexión
            'app_status' => true,
            'n8n_url' => '',
            'webhook_url' => '',
            'api_timeout' => 30,
            
            // Step 2 - Facebook
            'fb_page_id' => '61580389037992',
            'fb_page_name' => 'WebSolutions',
            'fb_access_token' => 'EAAx...TOKEN',
            'fb_api_version' => 'v18.0',
            
            // Step 3 - Publicación
            'auto_post' => true,
            'post_frequency' => 'daily',
            'post_time' => '09:00',
            'max_posts_day' => 3,
            'ai_model' => 'gpt-4',
            'content_tone' => 'professional',
            'default_hashtags' => ['#WebSolutions', '#MarketingDigital', '#DiseñoWeb'],
            'notify_on_error' => true,
            'notify_email' => 'admin@websolutions.work',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Conexión')
                        ->icon('heroicon-o-signal')
                        ->description('Configuración de automatizaciones (deshabilitado)')
                        ->schema([
                            Section::make()
                                ->columns(2)
                                ->schema([
                                    Toggle::make('app_status')
                                        ->label('Aplicación Activa')
                                        ->helperText('Activa o desactiva toda la aplicación')
                                        ->onColor('success')
                                        ->offColor('danger')
                                        ->columnSpanFull(),
                                    TextInput::make('n8n_url')
                                        ->label('URL de n8n')
                                        ->url()
                                        ->required()
                                        ->placeholder('https://n8n.ejemplo.com'),
                                    TextInput::make('webhook_url')
                                        ->label('Webhook URL')
                                        ->url()
                                        ->required()
                                        ->placeholder('https://n8n.ejemplo.com/webhook/...'),
                                    TextInput::make('api_timeout')
                                        ->label('Timeout (segundos)')
                                        ->numeric()
                                        ->default(30)
                                        ->minValue(5)
                                        ->maxValue(120),
                                    Placeholder::make('connection_status')
                                        ->label('Estado')
                                        ->content(new HtmlString('<span class="inline-flex items-center gap-1.5 text-sm"><span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Conectado</span>')),
                                ]),
                        ]),

                    Wizard\Step::make('Facebook')
                        ->icon('heroicon-o-globe-alt')
                        ->description('Credenciales de Facebook')
                        ->schema([
                            Section::make()
                                ->columns(2)
                                ->schema([
                                    TextInput::make('fb_page_id')
                                        ->label('Page ID')
                                        ->required()
                                        ->placeholder('123456789'),
                                    TextInput::make('fb_page_name')
                                        ->label('Nombre de Página')
                                        ->placeholder('Mi Página'),
                                    TextInput::make('fb_access_token')
                                        ->label('Access Token')
                                        ->password()
                                        ->revealable()
                                        ->required()
                                        ->columnSpanFull(),
                                    Select::make('fb_api_version')
                                        ->label('Versión de API')
                                        ->options([
                                            'v17.0' => 'v17.0',
                                            'v18.0' => 'v18.0 (Recomendado)',
                                            'v19.0' => 'v19.0',
                                        ])
                                        ->default('v18.0'),
                                    Placeholder::make('fb_status')
                                        ->label('Estado')
                                        ->content(new HtmlString('<span class="inline-flex items-center gap-1.5 text-sm"><span class="w-2 h-2 bg-blue-500 rounded-full"></span> Verificado</span>')),
                                ]),
                        ]),

                    Wizard\Step::make('Publicación')
                        ->icon('heroicon-o-pencil-square')
                        ->description('Contenido y automatización')
                        ->schema([
                            Section::make('Automatización')
                                ->columns(3)
                                ->schema([
                                    Toggle::make('auto_post')
                                        ->label('Auto-publicar')
                                        ->helperText('Publicar automáticamente')
                                        ->onColor('success'),
                                    Select::make('post_frequency')
                                        ->label('Frecuencia')
                                        ->options([
                                            'hourly' => 'Cada hora',
                                            'daily' => 'Diario',
                                            'weekly' => 'Semanal',
                                        ]),
                                    TextInput::make('max_posts_day')
                                        ->label('Máx. posts/día')
                                        ->numeric()
                                        ->minValue(1)
                                        ->maxValue(10),
                                ]),
                            Section::make('Contenido IA')
                                ->columns(2)
                                ->schema([
                                    Select::make('ai_model')
                                        ->label('Modelo IA')
                                        ->options([
                                            'gpt-3.5-turbo' => 'GPT-3.5 Turbo',
                                            'gpt-4' => 'GPT-4',
                                            'gpt-4-turbo' => 'GPT-4 Turbo',
                                            'claude-3' => 'Claude 3',
                                        ]),
                                    Select::make('content_tone')
                                        ->label('Tono')
                                        ->options([
                                            'professional' => 'Profesional',
                                            'casual' => 'Casual',
                                            'friendly' => 'Amigable',
                                            'formal' => 'Formal',
                                        ]),
                                    TagsInput::make('default_hashtags')
                                        ->label('Hashtags predeterminados')
                                        ->placeholder('Agregar hashtag...')
                                        ->columnSpanFull(),
                                ]),
                            Section::make('Notificaciones')
                                ->columns(2)
                                ->schema([
                                    Toggle::make('notify_on_error')
                                        ->label('Notificar errores')
                                        ->onColor('danger'),
                                    TextInput::make('notify_email')
                                        ->label('Email')
                                        ->email()
                                        ->placeholder('admin@ejemplo.com'),
                                ]),
                        ]),
                ])
                ->submitAction(new HtmlString('<button type="submit" class="fi-btn fi-btn-size-md relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus:ring-2 rounded-lg fi-btn-color-primary bg-primary-600 text-white hover:bg-primary-500 focus:ring-primary-500/50 dark:bg-primary-500 dark:hover:bg-primary-400 gap-1.5 px-3 py-2 text-sm"><span class="fi-btn-label">Guardar Configuración</span></button>'))
                ->skippable()
                ->persistStepInQueryString(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        
        // Aquí guardarías la configuración
        
        Notification::make()
            ->title('✅ Configuración guardada')
            ->body('Los cambios se han aplicado correctamente.')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('test')
                ->label('Probar conexión')
                ->icon('heroicon-o-signal')
                ->color('gray')
                ->action(function () {
                    Notification::make()
                        ->title('⚠️ Recursos de n8n eliminados')
                        ->body('Los recursos de automatizaciones n8n han sido eliminados.')
                        ->warning()
                        ->send();
                }),
        ];
    }
}
