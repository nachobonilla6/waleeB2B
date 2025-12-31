<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddTextoToPublicidadEventos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publicidad:add-texto-column';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Agrega la columna texto a la tabla publicidad_eventos si no existe';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!Schema::hasTable('publicidad_eventos')) {
            $this->error('La tabla publicidad_eventos no existe.');
            return 1;
        }

        if (Schema::hasColumn('publicidad_eventos', 'texto')) {
            $this->info('La columna texto ya existe en la tabla publicidad_eventos.');
            return 0;
        }

        try {
            DB::statement('ALTER TABLE publicidad_eventos ADD COLUMN texto TEXT NULL AFTER descripcion');
            $this->info('Columna texto agregada exitosamente a la tabla publicidad_eventos.');
            return 0;
        } catch (\Exception $e) {
            $this->error('Error al agregar la columna: ' . $e->getMessage());
            return 1;
        }
    }
}
