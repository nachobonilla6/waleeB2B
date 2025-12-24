<?php

namespace App\Console\Commands;

use App\Services\GoogleCalendarService;
use Illuminate\Console\Command;

class ListGoogleCalendars extends Command
{
    protected $signature = 'google-calendar:list';
    protected $description = 'Listar todos los calendarios de Google Calendar disponibles';

    public function handle()
    {
        $this->info('Buscando calendarios en Google Calendar...');

        $service = new GoogleCalendarService();

        if (!$service->isAuthorized()) {
            $this->error('❌ No hay autorización de Google Calendar configurada.');
            $this->warn('Por favor, autoriza Google Calendar primero desde /admin/google-calendar-auth');
            return Command::FAILURE;
        }

        $calendars = $service->listCalendars();

        if (empty($calendars)) {
            $this->warn('No se encontraron calendarios.');
            return Command::SUCCESS;
        }

        $this->info('✅ Calendarios encontrados:');
        $this->newLine();

        $headers = ['ID', 'Nombre', 'Descripción', 'Principal'];
        $rows = [];

        foreach ($calendars as $calendar) {
            $rows[] = [
                $calendar['id'],
                $calendar['summary'],
                $calendar['description'] ?? '-',
                $calendar['primary'] ? 'Sí' : 'No',
            ];
        }

        $this->table($headers, $rows);

        $this->newLine();
        $this->info('💡 Para usar un calendario específico, agrega en tu .env:');
        $this->info('   GOOGLE_CALENDAR_ID="ID_DEL_CALENDARIO"');
        $this->info('   (Usa el ID de la primera columna)');

        return Command::SUCCESS;
    }
}

