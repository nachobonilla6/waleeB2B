<?php

namespace App\Filament\Pages;

use App\Models\Cita;
use App\Models\Cliente;
use App\Services\GoogleCalendarService;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;

class GoogleCalendar extends Page implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Calendario';
    protected static ?string $title = 'Citas';
    protected static ?string $navigationGroup = 'Clientes';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.google-calendar';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            \Filament\Forms\Components\Section::make('Información de la Cita')
                ->schema([
                    TextInput::make('titulo')
                        ->label('Título')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Ej: Reunión con cliente')
                        ->columnSpan(3),
                    Select::make('cliente_id')
                        ->label('Cliente')
                        ->options(function () {
                            return Cliente::orderBy('nombre_empresa')
                                ->get()
                                ->mapWithKeys(fn ($cliente) => [
                                    $cliente->id => $cliente->nombre_empresa . ' (' . $cliente->correo . ')'
                                ])
                                ->toArray();
                        })
                        ->searchable()
                        ->getSearchResultsUsing(fn (string $search): array => 
                            Cliente::where('nombre_empresa', 'like', "%{$search}%")
                                ->orWhere('correo', 'like', "%{$search}%")
                                ->limit(50)
                                ->get()
                                ->mapWithKeys(fn ($cliente) => [
                                    $cliente->id => $cliente->nombre_empresa . ' (' . $cliente->correo . ')'
                                ])
                                ->toArray()
                        )
                        ->getOptionLabelUsing(fn ($value): ?string => 
                            Cliente::find($value)?->nombre_empresa
                        )
                        ->createOptionForm([
                            TextInput::make('nombre_empresa')
                                ->label('Nombre de la Empresa')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('correo')
                                ->label('Correo Electrónico')
                                ->email()
                                ->required()
                                ->maxLength(255),
                            TextInput::make('telefono')
                                ->label('Teléfono')
                                ->maxLength(255),
                        ])
                        ->createOptionUsing(function (array $data): int {
                            $cliente = Cliente::create([
                                'nombre_empresa' => $data['nombre_empresa'],
                                'correo' => $data['correo'],
                                'telefono' => $data['telefono'] ?? null,
                                'estado_cuenta' => 'pendiente', // Estado no activo
                            ]);
                            return $cliente->id;
                        })
                        ->columnSpan(2),
                    Select::make('estado')
                        ->label('Estado')
                        ->options([
                            'programada' => 'Programada',
                            'completada' => 'Completada',
                            'cancelada' => 'Cancelada',
                        ])
                        ->default('programada')
                        ->required(),
                    DateTimePicker::make('fecha_inicio')
                        ->label('Fecha y Hora de Inicio')
                        ->required()
                        ->native(false)
                        ->seconds(false)
                        ->displayFormat('d/m/Y H:i')
                        ->timezone(config('app.timezone', 'America/Mexico_City'))
                        ->default(now()->addHour()->startOfHour())
                        ->columnSpan(3),
                    DateTimePicker::make('fecha_fin')
                        ->label('Fecha y Hora de Fin')
                        ->native(false)
                        ->seconds(false)
                        ->displayFormat('d/m/Y H:i')
                        ->timezone(config('app.timezone', 'America/Mexico_City'))
                        ->default(now()->addHours(2)->startOfHour())
                        ->after('fecha_inicio')
                        ->columnSpan(3),
                    TextInput::make('ubicacion')
                        ->label('Ubicación')
                        ->maxLength(255)
                        ->placeholder('Dirección o lugar de la cita')
                        ->columnSpan(3),
                    Textarea::make('descripcion')
                        ->label('Descripción')
                        ->rows(2)
                        ->placeholder('Detalles adicionales...')
                        ->columnSpanFull(),
                ])
                ->columns(6)
                ->compact(),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema($this->getFormSchema())
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('nueva_cita')
                ->label('Nueva Cita')
                ->icon('heroicon-o-plus')
                ->color('success')
                ->form($this->getFormSchema())
                ->modalWidth('4xl')
                ->action(function (array $data) {
                    $cita = Cita::create($data);
                    
                    $service = new GoogleCalendarService();
                    $eventId = $service->createEvent($cita);
                    if ($eventId) {
                        $cita->google_event_id = $eventId;
                        $cita->save();
                    }
                    
                    $this->dispatch('$refresh');
                    
                    Notification::make()
                        ->title('Cita creada')
                        ->body('La cita se ha creado y sincronizado con Google Calendar')
                        ->success()
                        ->send();
                }),
            Action::make('abrir_calendario')
                ->label('Abrir Google Calendar')
                ->icon('heroicon-o-calendar-days')
                ->url('https://calendar.google.com')
                ->openUrlInNewTab()
                ->color('info'),
        ];
    }

    public function getCitasProperty(): \Illuminate\Database\Eloquent\Collection
    {
        return Cita::with('cliente')
            ->where('estado', '!=', 'cancelada')
            ->orderBy('fecha_inicio', 'asc')
            ->get();
    }

    public function getCitas(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->getCitasProperty();
    }


    public function deleteCita(int $id): void
    {
        $cita = Cita::findOrFail($id);
        
        // Eliminar de Google Calendar si existe
        if ($cita->google_event_id) {
            $service = new GoogleCalendarService();
            $service->deleteEvent($cita);
        }
        
        $cita->delete();
        
        $this->dispatch('$refresh');
        
        Notification::make()
            ->title('Cita eliminada')
            ->body('La cita se ha eliminado correctamente')
            ->success()
            ->send();
    }

    protected function getActions(): array
    {
        return [
            Action::make('edit')
                ->label('Editar Cita')
                ->icon('heroicon-o-pencil')
                ->color('info')
                ->form($this->getFormSchema())
                ->modalWidth('4xl')
                ->fillForm(function (array $arguments) {
                    $cita = Cita::findOrFail($arguments['id']);
                    return [
                        'titulo' => $cita->titulo,
                        'descripcion' => $cita->descripcion,
                        'fecha_inicio' => $cita->fecha_inicio,
                        'fecha_fin' => $cita->fecha_fin,
                        'cliente_id' => $cita->cliente_id,
                        'ubicacion' => $cita->ubicacion,
                        'estado' => $cita->estado,
                    ];
                })
                ->action(function (array $data, array $arguments) {
                    $cita = Cita::findOrFail($arguments['id']);
                    $cita->update($data);
                    
                    $service = new GoogleCalendarService();
                    if ($cita->google_event_id) {
                        $service->updateEvent($cita);
                    } else {
                        $eventId = $service->createEvent($cita);
                        if ($eventId) {
                            $cita->google_event_id = $eventId;
                            $cita->save();
                        }
                    }
                    
                    $this->dispatch('$refresh');
                    
                    Notification::make()
                        ->title('Cita actualizada')
                        ->body('La cita se ha actualizado y sincronizado con Google Calendar')
                        ->success()
                        ->send();
                }),
        ];
    }

}
