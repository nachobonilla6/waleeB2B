<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;

class BotConfir extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static string $view = 'filament.pages.bot-confir';
    protected static ?string $navigationLabel = 'Bot Confir';
    protected static ?string $navigationGroup = 'Herramientas';
    protected static ?int $navigationSort = 10;

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
        
        // Aquí puedes agregar la lógica para guardar los datos
        // Por ahora solo mostramos una notificación
        
        Notification::make()
            ->title('Configuración guardada')
            ->body('Los datos del Bot Confir se han guardado correctamente.')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            \Filament\Actions\Action::make('save')
                ->label('Guardar Configuración')
                ->submit('save'),
        ];
    }
}
