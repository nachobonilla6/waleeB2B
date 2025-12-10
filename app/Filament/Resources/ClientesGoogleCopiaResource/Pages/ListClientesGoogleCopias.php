<?php

namespace App\Filament\Resources\ClientesGoogleCopiaResource\Pages;

use App\Filament\Resources\ClientesGoogleCopiaResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Http;

class ListClientesGoogleCopias extends ListRecords implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.resources.clientes-google-copia-resource.pages.list-clientes-google-copias';
    protected static string $resource = ClientesGoogleCopiaResource::class;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('nombre_lugar')
                            ->label('Nombre del Lugar')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
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
                            ->columnSpanFull()
                            ->afterStateUpdated(fn (Set $set) => $set('industria_otro', null)),
                        Forms\Components\TextInput::make('industria_otro')
                            ->label('Especificar otro tipo de negocio')
                            ->placeholder('Escribe el tipo de negocio')
                            ->maxLength(255)
                            ->required(fn (Get $get) => $get('industria') === 'otro')
                            ->visible(fn (Get $get) => $get('industria') === 'otro')
                            ->helperText('Por favor, especifica el tipo de negocio')
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
            ])
            ->statePath('data');
    }

    public function enviar(): void
    {
        $data = $this->form->getState();

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

    public function getMaxContentWidth(): MaxWidth | string | null
    {
        return MaxWidth::Full;
    }

    protected function hasTable(): bool
    {
        return false;
    }

    public function getTitle(): string
    {
        return 'Site Scraper';
    }

    public function getHeading(): string
    {
        return 'Site Scraper';
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}

