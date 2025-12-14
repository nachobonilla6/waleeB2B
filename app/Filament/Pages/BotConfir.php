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
                Forms\Components\Section::make('Configuración SQL')
                    ->icon('heroicon-o-database')
                    ->schema([
                        Forms\Components\Textarea::make('sql')
                            ->label('SQL')
                            ->rows(5)
                            ->placeholder('Ingresa tu consulta SQL aquí...')
                            ->columnSpanFull(),
                    ]),
                
                Forms\Components\Section::make('Trigger Rules')
                    ->icon('heroicon-o-clock')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Select::make('trigger_interval_1')
                                ->label('Trigger Interval')
                                ->options([
                                    'days' => 'Days',
                                    'hours' => 'Hours',
                                    'minutes' => 'Minutes',
                                ])
                                ->default('days')
                                ->required(),
                            Forms\Components\TextInput::make('days_between_triggers_1')
                                ->label('Days Between Triggers')
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(31)
                                ->default(1)
                                ->required()
                                ->helperText('Must be in range 1-31'),
                        ]),
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Select::make('trigger_at_hour_1')
                                ->label('Trigger at Hour')
                                ->options([
                                    '12am' => '12am', '1am' => '1am', '2am' => '2am', '3am' => '3am',
                                    '4am' => '4am', '5am' => '5am', '6am' => '6am', '7am' => '7am',
                                    '8am' => '8am', '9am' => '9am', '10am' => '10am', '11am' => '11am',
                                    '12pm' => '12pm', '1pm' => '1pm', '2pm' => '2pm', '3pm' => '3pm',
                                    '4pm' => '4pm', '5pm' => '5pm', '6pm' => '6pm', '7pm' => '7pm',
                                    '8pm' => '8pm', '9pm' => '9pm', '10pm' => '10pm', '11pm' => '11pm',
                                ])
                                ->default('9pm')
                                ->required(),
                            Forms\Components\TextInput::make('trigger_at_minute_1')
                                ->label('Trigger at Minute')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(59)
                                ->default(0)
                                ->required(),
                        ]),
                    ]),
                
                Forms\Components\Section::make('Trigger Rules 2')
                    ->icon('heroicon-o-clock')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Select::make('trigger_interval_2')
                                ->label('Trigger Interval')
                                ->options([
                                    'days' => 'Days',
                                    'hours' => 'Hours',
                                    'minutes' => 'Minutes',
                                ])
                                ->default('days')
                                ->required(),
                            Forms\Components\TextInput::make('days_between_triggers_2')
                                ->label('Days Between Triggers')
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(31)
                                ->default(1)
                                ->required()
                                ->helperText('Must be in range 1-31'),
                        ]),
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Select::make('trigger_at_hour_2')
                                ->label('Trigger at Hour')
                                ->options([
                                    '12am' => '12am', '1am' => '1am', '2am' => '2am', '3am' => '3am',
                                    '4am' => '4am', '5am' => '5am', '6am' => '6am', '7am' => '7am',
                                    '8am' => '8am', '9am' => '9am', '10am' => '10am', '11am' => '11am',
                                    '12pm' => '12pm', '1pm' => '1pm', '2pm' => '2pm', '3pm' => '3pm',
                                    '4pm' => '4pm', '5pm' => '5pm', '6pm' => '6pm', '7pm' => '7pm',
                                    '8pm' => '8pm', '9pm' => '9pm', '10pm' => '10pm', '11pm' => '11pm',
                                ])
                                ->default('7pm')
                                ->required(),
                            Forms\Components\TextInput::make('trigger_at_minute_2')
                                ->label('Trigger at Minute')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(59)
                                ->default(0)
                                ->required(),
                        ]),
                    ]),
                
                Forms\Components\Section::make('Configuración de Prompt')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->schema([
                        Forms\Components\Textarea::make('prompt')
                            ->label('PROMPT')
                            ->rows(5)
                            ->placeholder('Ingresa el prompt aquí...')
                            ->columnSpanFull(),
                    ]),
                
                Forms\Components\Section::make('Credenciales de Facebook Graph API')
                    ->icon('heroicon-o-key')
                    ->schema([
                        Forms\Components\TextInput::make('access_token')
                            ->label('Access Token')
                            ->password()
                            ->required()
                            ->columnSpanFull(),
                    ]),
                
                Forms\Components\Section::make('Credenciales de N8N')
                    ->icon('heroicon-o-shield-check')
                    ->schema([
                        Forms\Components\TextInput::make('n8n_api_key')
                            ->label('N8N API Key')
                            ->password()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('n8n_webhook_url')
                            ->label('N8N Webhook URL')
                            ->url()
                            ->placeholder('https://n8n.example.com/webhook/...')
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
