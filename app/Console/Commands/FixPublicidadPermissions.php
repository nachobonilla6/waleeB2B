<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FixPublicidadPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publicidad:fix-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corrige los permisos de los archivos en public/publicidad para que sean públicos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Detectar directorio público (public_html para Hostinger, public para desarrollo)
        $publicDir = is_dir(base_path('public_html')) 
            ? base_path('public_html/publicidad') 
            : public_path('publicidad');
        
        if (!file_exists($publicDir)) {
            $dirName = is_dir(base_path('public_html')) ? 'public_html/publicidad' : 'public/publicidad';
            $this->warn("El directorio {$dirName} no existe. Se creará cuando se suba la primera imagen.");
            return 0;
        }

        $this->info('Corrigiendo permisos de archivos en public/publicidad...');
        
        // Asegurar permisos del directorio
        chmod($publicDir, 0755);
        $this->info("✓ Permisos del directorio corregidos: 0755");
        
        // Obtener todos los archivos en el directorio
        $files = File::allFiles($publicDir);
        $count = 0;
        
        foreach ($files as $file) {
            $filePath = $file->getPathname();
            chmod($filePath, 0644);
            $count++;
            
            $this->line("  ✓ {$file->getFilename()} -> 0644");
        }
        
        if ($count > 0) {
            $this->info("\n✓ Permisos corregidos para {$count} archivo(s).");
        } else {
            $this->info("\n✓ No se encontraron archivos para corregir.");
        }
        
        return 0;
    }
}

