<?php

namespace App\Console\Commands;

use App\Models\Cita;
use App\Services\GoogleCalendarService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncCitasToGoogleCalendar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'citas:sync-google {--force : Sincronizar incluso si ya tienen google_event_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincronizar citas existentes con Google Calendar';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando sincronización de citas con Google Calendar...');

        $googleService = new GoogleCalendarService();

        if (!$googleService->isAuthorized()) {
            $this->error('❌ No hay autorización de Google Calendar configurada.');
            $this->warn('Por favor, configura las credenciales de Google Calendar primero.');
            return Command::FAILURE;
        }

        $this->info('✅ Autorización de Google Calendar verificada.');

        // Obtener citas sin google_event_id
        $query = Cita::whereNull('google_event_id');
        
        if (!$this->option('force')) {
            $query->whereNull('google_event_id');
        }

        $citas = $query->get();
        $total = $citas->count();

        if ($total === 0) {
            $this->info('✅ No hay citas pendientes de sincronizar.');
            return Command::SUCCESS;
        }

        $this->info("📅 Encontradas {$total} citas para sincronizar.");

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $exitosas = 0;
        $fallidas = 0;

        foreach ($citas as $cita) {
            try {
                $eventId = $googleService->createEvent($cita);
                
                if ($eventId) {
                    $cita->google_event_id = $eventId;
                    $cita->save();
                    $exitosas++;
                } else {
                    $fallidas++;
                    Log::warning("No se pudo crear evento en Google Calendar para cita ID: {$cita->id}");
                }
            } catch (\Exception $e) {
                $fallidas++;
                Log::error("Error sincronizando cita ID {$cita->id}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("✅ Sincronización completada:");
        $this->info("   - Exitosas: {$exitosas}");
        if ($fallidas > 0) {
            $this->warn("   - Fallidas: {$fallidas}");
        }

        return Command::SUCCESS;
    }
}

