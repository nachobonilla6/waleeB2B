<?php

namespace App\Filament\Pages;

use App\Filament\Resources\ClientResource;
use App\Filament\Resources\ClientPropuestaEnviadaResource;
use App\Models\Client;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class SiteScraper extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationLabel = 'Site Scraper';
    protected static ?string $title = 'Site Scraper';
    protected static ?string $navigationGroup = 'Herramientas';
    protected static ?int $navigationSort = 0;

    protected static string $view = 'filament.pages.site-scraper';
    
    public ?array $data = [];
    
    public function mount(): void
    {
        $this->form->fill();
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre_lugar')
                    ->label('Nombre del Lugar')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('industria')
                    ->label('Industria')
                    ->options([
                        'turismo' => 'Turismo',
                        'gastronomia' => 'Gastronomía',
                        'retail' => 'Retail',
                        'salud' => 'Salud',
                        'educacion' => 'Educación',
                        'tecnologia' => 'Tecnología',
                        'servicios' => 'Servicios',
                        'comercio' => 'Comercio',
                        'manufactura' => 'Manufactura',
                        'otro' => 'Otro',
                    ])
                    ->required()
                    ->native(false),
            ])
            ->statePath('data');
    }
    
    public function enviarWebhook(): void
    {
        $data = $this->form->getState();
        
        try {
            $response = Http::timeout(30)->post('https://n8n.srv1137974.hstgr.cloud/webhook-test/0c01d9a1-788c-44d2-9c1b-9457901d0a3c', [
                'nombre_lugar' => $data['nombre_lugar'] ?? '',
                'industria' => $data['industria'] ?? '',
            ]);

            if ($response->successful()) {
                Notification::make()
                    ->title('Datos enviados')
                    ->body('Los datos se han enviado correctamente al webhook')
                    ->success()
                    ->send();
                    
                $this->form->fill();
            } else {
                Notification::make()
                    ->title('Error al enviar')
                    ->body('El webhook respondió con error: ' . $response->status())
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
    }
}
