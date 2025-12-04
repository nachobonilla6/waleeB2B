<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;

class GoogleCalendar extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Calendario';
    protected static ?string $title = 'Calendario';
    protected static ?string $navigationGroup = 'Clientes';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.google-calendar';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('nuevo_evento')
                ->label('Nuevo Evento')
                ->icon('heroicon-o-plus')
                ->url('https://calendar.google.com/calendar/r/eventedit')
                ->openUrlInNewTab()
                ->color('success'),
            Action::make('abrir_calendario')
                ->label('Abrir Google Calendar')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url('https://calendar.google.com')
                ->openUrlInNewTab()
                ->color('gray'),
        ];
    }
}
