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
                Forms\Components\Select::make('client_id')
                    ->label('Cliente')
                    ->options(Client::pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Forms\Set $set, $state) {
                        if ($state) {
                            $cliente = Client::find($state);
                            if ($cliente?->email) {
                                $set('email', $cliente->email);
                            }
                        }
                    }),
                Forms\Components\TextInput::make('email')
                    ->label('Correo Electrónico')
                    ->email()
                    ->required()
                    ->maxLength(255),
            ])
            ->statePath('data');
    }
    
    public function enviarPropuesta(): void
    {
        $data = $this->form->getState();
        $client = Client::find($data['client_id'] ?? null);
        
        if (!$client) {
            Notification::make()
                ->title('Error')
                ->body('Cliente no encontrado')
                ->danger()
                ->send();
            return;
        }
        
        try {
            $videoUrl = '';
            if ($client->proposed_site) {
                $sitio = \App\Models\Sitio::where('enlace', $client->proposed_site)->first();
                $videoUrl = $sitio?->video_url ?? '';
            }
            
            $response = Http::post('https://n8n.srv1137974.hstgr.cloud/webhook-test/92c5f4ef-f206-4e3d-a613-5874c7dbc8bd', [
                'name' => $client->name ?? '',
                'email' => $data['email'] ?? $client->email ?? '',
                'website' => $client->website ?? '',
                'proposed_site' => $client->proposed_site ?? '',
                'video_url' => $videoUrl,
                'feedback' => $client->feedback ?? '',
                'propuesta' => $client->propuesta ?? '',
            ]);

            if ($response->successful()) {
                if (\Illuminate\Support\Facades\Schema::hasColumn('clients', 'propuesta_enviada')) {
                    $client->update(['propuesta_enviada' => true]);
                }
                
                Notification::make()
                    ->title('Propuesta enviada')
                    ->body('La propuesta se ha enviado a ' . ($data['email'] ?? $client->email ?? 'el cliente'))
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
