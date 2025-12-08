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
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(fn (Forms\Set $set) => $set('industria_otro', null)),
                Forms\Components\TextInput::make('industria_otro')
                    ->label('Especificar otro tipo de negocio')
                    ->placeholder('Escribe el tipo de negocio')
                    ->maxLength(255)
                    ->required(fn (Forms\Get $get) => $get('industria') === 'otro')
                    ->visible(fn (Forms\Get $get) => $get('industria') === 'otro')
                    ->helperText('Por favor, especifica el tipo de negocio'),
            ])
            ->statePath('data');
    }
    
    public function enviarWebhook(): void
    {
        $data = $this->form->getState();
        
        // Si se seleccionó "otro", usar el valor de industria_otro, sino usar el valor seleccionado
        $industria = ($data['industria'] ?? '') === 'otro' 
            ? ($data['industria_otro'] ?? 'Otro') 
            : ($data['industria'] ?? '');
        
        try {
            $response = Http::timeout(30)->post('https://n8n.srv1137974.hstgr.cloud/webhook-test/0c01d9a1-788c-44d2-9c1b-9457901d0a3c', [
                'nombre_lugar' => $data['nombre_lugar'] ?? '',
                'industria' => $industria,
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
