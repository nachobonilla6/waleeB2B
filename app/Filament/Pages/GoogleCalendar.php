<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class GoogleCalendar extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Calendario';
    protected static ?string $title = 'Google Calendar';
    protected static ?string $navigationGroup = 'Clientes';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.google-calendar';

    // Puedes cambiar este ID por el de tu calendario de Google
    public string $calendarId = 'es.cr#holiday@group.v.calendar.google.com';
}
