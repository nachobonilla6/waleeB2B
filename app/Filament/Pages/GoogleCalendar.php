<?php

namespace App\Filament\Pages;

use App\Models\Cita;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;

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
            Action::make('nuevo_evento')
                ->label('Nuevo Evento en Google')
                ->icon('heroicon-o-plus')
                ->url('https://calendar.google.com/calendar/r/eventedit')
                ->openUrlInNewTab()
                ->color('gray'),
            Action::make('abrir_calendario')
                ->label('Abrir Google Calendar')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url('https://calendar.google.com')
                ->openUrlInNewTab()
                ->color('gray'),
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
            ])
            ->filters([
                //
            ])
            ->actions([
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
                    ]),
                DeleteAction::make(),
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
                            ->default('programada')
                            ->required(),
                    ]),
            ])
            ->defaultSort('fecha_inicio', 'asc');
    }
}
