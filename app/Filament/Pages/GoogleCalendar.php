<?php

namespace App\Filament\Pages;

use App\Models\Cita;
use App\Services\GoogleCalendarService;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;

class GoogleCalendar extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Calendario';
    protected static ?string $title = 'Citas';
    protected static ?string $navigationGroup = 'Clientes';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.google-calendar';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sincronizar')
                ->label('Sincronizar con Google Calendar')
                ->icon('heroicon-o-arrow-path')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Sincronizar Citas')
                ->modalDescription('¿Deseas sincronizar todas las citas programadas con Google Calendar?')
                ->action(function () {
                    $service = new GoogleCalendarService();
                    $result = $service->syncAllEvents();
                    
                    Notification::make()
                        ->title('Sincronización completada')
                        ->body("{$result['synced']} citas sincronizadas. {$result['errors']} errores.")
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

    public function table(Table $table): Table
    {
        return $table
            ->query(Cita::query())
            ->columns([
                TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cliente')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('fecha_inicio')
                    ->label('Fecha y Hora')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('fecha_fin')
                    ->label('Hasta')
                    ->dateTime('H:i')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('ubicacion')
                    ->label('Ubicación')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completada' => 'success',
                        'programada' => 'warning',
                        'cancelada' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'completada' => 'Completada',
                        'programada' => 'Programada',
                        'cancelada' => 'Cancelada',
                        default => $state,
                    }),
                IconColumn::make('google_event_id')
                    ->label('Google Calendar')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->tooltip(fn ($record) => $record->google_event_id 
                        ? 'Sincronizado con Google Calendar' 
                        : 'No sincronizado'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('ver_en_google')
                    ->label('Ver en Google Calendar')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('info')
                    ->url(fn (Cita $record) => (new GoogleCalendarService())->getCreateEventUrl($record))
                    ->openUrlInNewTab()
                    ->visible(fn (Cita $record) => true),
                Action::make('sincronizar_evento')
                    ->label('Sincronizar con Google')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->action(function (Cita $record) {
                        $service = new GoogleCalendarService();
                        $eventId = $service->createEvent($record);
                        if ($eventId) {
                            $record->google_event_id = $eventId;
                            $record->save();
                            
                            Notification::make()
                                ->title('Evento sincronizado')
                                ->body('La cita se ha sincronizado con Google Calendar')
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Error al sincronizar')
                                ->body('No se pudo sincronizar la cita con Google Calendar')
                                ->danger()
                                ->send();
                        }
                    })
                    ->visible(fn (Cita $record) => empty($record->google_event_id)),
                EditAction::make()
                    ->form([
                        TextInput::make('titulo')
                            ->label('Título')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('descripcion')
                            ->label('Descripción')
                            ->rows(3),
                        DateTimePicker::make('fecha_inicio')
                            ->label('Fecha y Hora de Inicio')
                            ->required(),
                        DateTimePicker::make('fecha_fin')
                            ->label('Fecha y Hora de Fin'),
                        TextInput::make('cliente')
                            ->label('Cliente')
                            ->maxLength(255),
                        TextInput::make('ubicacion')
                            ->label('Ubicación')
                            ->maxLength(255),
                        Select::make('estado')
                            ->label('Estado')
                            ->options([
                                'programada' => 'Programada',
                                'completada' => 'Completada',
                                'cancelada' => 'Cancelada',
                            ])
                            ->required(),
                    ])
                    ->after(function (Cita $record) {
                        // Sincronizar después de editar
                        if ($record->google_event_id) {
                            $service = new GoogleCalendarService();
                            $service->updateEvent($record);
                        }
                    }),
                DeleteAction::make()
                    ->after(function (Cita $record) {
                        // Eliminar de Google Calendar si existe
                        if ($record->google_event_id) {
                            $service = new GoogleCalendarService();
                            $service->deleteEvent($record);
                        }
                    }),
            ])
                    ->headerActions([
                CreateAction::make()
                    ->label('Nueva Cita')
                    ->form([
                        TextInput::make('titulo')
                            ->label('Título')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('descripcion')
                            ->label('Descripción')
                            ->rows(3),
                        DateTimePicker::make('fecha_inicio')
                            ->label('Fecha y Hora de Inicio')
                            ->required()
                            ->native(false)
                            ->seconds(false),
                        DateTimePicker::make('fecha_fin')
                            ->label('Fecha y Hora de Fin')
                            ->native(false)
                            ->seconds(false),
                        TextInput::make('cliente')
                            ->label('Cliente')
                            ->maxLength(255),
                        TextInput::make('ubicacion')
                            ->label('Ubicación')
                            ->maxLength(255),
                        Select::make('estado')
                            ->label('Estado')
                            ->options([
                                'programada' => 'Programada',
                                'completada' => 'Completada',
                                'cancelada' => 'Cancelada',
                            ])
                            ->default('programada')
                            ->required(),
                    ])
                    ->after(function (Cita $record) {
                        // Opcional: sincronizar automáticamente al crear
                        // $service = new GoogleCalendarService();
                        // $eventId = $service->createEvent($record);
                        // if ($eventId) {
                        //     $record->google_event_id = $eventId;
                        //     $record->save();
                        // }
                    }),
            ])
            ->defaultSort('fecha_inicio', 'asc');
    }
}
