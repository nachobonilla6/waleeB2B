<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            ['nombre' => 'Tecnología'],
            ['nombre' => 'Noticias'],
            ['nombre' => 'Educación'],
            ['nombre' => 'Negocios'],
            ['nombre' => 'Entretenimiento'],
            ['nombre' => 'E-commerce'],
            ['nombre' => 'Salud'],
            ['nombre' => 'Turismo'],
            ['nombre' => 'Gastronomía'],
            ['nombre' => 'Inmobiliaria'],
            ['nombre' => 'Belleza'],
            ['nombre' => 'Fitness'],
            ['nombre' => 'Moda'],
            ['nombre' => 'Automotriz'],
            ['nombre' => 'Servicios Profesionales'],
            ['nombre' => 'Arte y Diseño'],
            ['nombre' => 'Música'],
            ['nombre' => 'Deportes'],
            ['nombre' => 'Medios de Comunicación'],
            ['nombre' => 'Finanzas'],
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($tag['nombre'])],
                ['nombre' => $tag['nombre']]
            );
        }

        $this->command->info('¡Se han creado las etiquetas exitosamente!');
    }
}

