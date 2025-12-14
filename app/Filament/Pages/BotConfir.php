<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class BotConfir extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static string $view = 'filament.pages.bot-confir';
    protected static ?string $navigationLabel = 'Bot Confir';
    protected static ?string $navigationGroup = 'Herramientas';
    protected static ?int $navigationSort = 10;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Configuración del Webhook')
                    ->icon('heroicon-o-paper-airplane')
                    ->schema([
                        Forms\Components\TextInput::make('webhook_url')
                            ->label('Webhook URL de N8N')
                            ->url()
                            ->placeholder('https://n8n.example.com/webhook/...')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('sql')
                            ->label('SQL')
                            ->rows(5)
                            ->placeholder('Ingresa tu consulta SQL aquí...')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('prompt')
                            ->label('PROMPT')
                            ->rows(5)
                            ->placeholder('Ingresa el prompt aquí...')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('access_token')
                            ->label('Access Token (Facebook Graph API)')
                            ->password()
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        
        // Aquí puedes agregar la lógica para guardar los datos en base de datos si es necesario
        // Por ahora solo mostramos una notificación
        
        Notification::make()
            ->title('Configuración guardada')
            ->body('Los datos del Bot Confir se han guardado correctamente.')
            ->success()
            ->send();
    }

    public function sendWebhook(): void
    {
        $data = $this->form->getState();
        
        // Validar que el webhook URL esté presente
        if (empty($data['webhook_url'])) {
            Notification::make()
                ->title('Error')
                ->body('Debes ingresar la URL del webhook.')
                ->danger()
                ->send();
            return;
        }
        
        // Preparar los datos para enviar al webhook
        $webhookData = [
            'sql' => $data['sql'] ?? '',
            'prompt' => $data['prompt'] ?? '',
            'access_token' => $data['access_token'] ?? '',
            'timestamp' => now()->toIso8601String(),
        ];
        
        try {
            $response = Http::timeout(30)->post($data['webhook_url'], $webhookData);
            
            if ($response->successful()) {
                Notification::make()
                    ->title('✅ Webhook enviado')
                    ->body('Los datos se han enviado al webhook de N8N correctamente.')
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('❌ Error al enviar webhook')
                    ->body('El webhook respondió con código: ' . $response->status())
                    ->danger()
                    ->persistent()
                    ->send();
            }
        } catch (\Exception $e) {
            \Log::error('Error enviando webhook desde Bot Confir', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'webhook_url' => $data['webhook_url'] ?? 'N/A',
            ]);
            
            Notification::make()
                ->title('❌ Error al enviar webhook')
                ->body('No se pudo enviar el webhook: ' . $e->getMessage())
                ->danger()
                ->persistent()
                ->send();
        }
    }

    protected function getFormActions(): array
    {
        return [
            \Filament\Actions\Action::make('save')
                ->label('Guardar Configuración')
                ->submit('save'),
            \Filament\Actions\Action::make('sendWebhook')
                ->label('Enviar Webhook')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->action('sendWebhook')
                ->requiresConfirmation()
                ->modalHeading('Enviar webhook a N8N')
                ->modalDescription('¿Estás seguro de que deseas enviar estos datos al webhook de N8N?')
                ->modalSubmitActionLabel('Sí, enviar'),
        ];
    }
}
