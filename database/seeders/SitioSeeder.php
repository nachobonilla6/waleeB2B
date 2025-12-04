<?php

namespace Database\Seeders;

use App\Models\Sitio;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SitioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear directorio de imágenes si no existe
        if (!Storage::exists('public/sitios')) {
            Storage::makeDirectory('public/sitios');
        }

        // Obtener tags existentes
        $tags = [
            'tecnologia' => Tag::where('slug', 'tecnologia')->first(),
            'noticias' => Tag::where('slug', 'noticias')->first(),
            'educacion' => Tag::where('slug', 'educacion')->first(),
            'negocios' => Tag::where('slug', 'negocios')->first(),
            'entretenimiento' => Tag::where('slug', 'entretenimiento')->first(),
            'e-commerce' => Tag::where('slug', 'e-commerce')->first(),
            'salud' => Tag::where('slug', 'salud')->first(),
            'turismo' => Tag::where('slug', 'turismo')->first(),
            'gastronomia' => Tag::where('slug', 'gastronomia')->first(),
            'inmobiliaria' => Tag::where('slug', 'inmobiliaria')->first(),
            'belleza' => Tag::where('slug', 'belleza')->first(),
            'fitness' => Tag::where('slug', 'fitness')->first(),
            'moda' => Tag::where('slug', 'moda')->first(),
            'automotriz' => Tag::where('slug', 'automotriz')->first(),
            'servicios-profesionales' => Tag::where('slug', 'servicios-profesionales')->first(),
            'arte-y-diseno' => Tag::where('slug', 'arte-y-diseno')->first(),
            'musica' => Tag::where('slug', 'musica')->first(),
            'deportes' => Tag::where('slug', 'deportes')->first(),
            'medios-de-comunicacion' => Tag::where('slug', 'medios-de-comunicacion')->first(),
            'finanzas' => Tag::where('slug', 'finanzas')->first(),
        ];

        // Crear sitios de ejemplo
        $sitios = [
            // Tecnología
            [
                'nombre' => 'Google',
                'descripcion' => 'El buscador más popular del mundo',
                'enlace' => 'https://www.google.com',
                'en_linea' => true,
                'imagen' => 'https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png',
                'tags' => ['tecnologia', 'medios-de-comunicacion']
            ],
            [
                'nombre' => 'GitHub',
                'descripcion' => 'Plataforma de desarrollo colaborativo para alojar proyectos',
                'enlace' => 'https://github.com',
                'en_linea' => true,
                'imagen' => 'https://github.githubassets.com/images/modules/logos_page/GitHub-Mark.png',
                'tags' => ['tecnologia', 'educacion', 'servicios-profesionales']
            ],
            [
                'nombre' => 'Laravel',
                'descripcion' => 'Framework de PHP para desarrollo web',
                'enlace' => 'https://laravel.com',
                'en_linea' => true,
                'imagen' => 'https://laravel.com/img/logomark.min.svg',
                'tags' => ['tecnologia', 'educacion']
            ],
            [
                'nombre' => 'Stack Overflow',
                'descripcion' => 'Comunidad de programadores y desarrolladores',
                'enlace' => 'https://stackoverflow.com',
                'en_linea' => true,
                'imagen' => null,
                'tags' => ['tecnologia', 'educacion']
            ],
            
            // Noticias y Medios
            [
                'nombre' => 'Twitter',
                'descripcion' => 'Red social de microblogging',
                'enlace' => 'https://twitter.com',
                'en_linea' => true,
                'imagen' => 'https://abs.twimg.com/favicons/twitter.3.ico',
                'tags' => ['noticias', 'entretenimiento', 'medios-de-comunicacion']
            ],
            [
                'nombre' => 'Forbes',
                'descripcion' => 'Revista de negocios y finanzas',
                'enlace' => 'https://www.forbes.com',
                'en_linea' => true,
                'imagen' => 'https://www.forbes.com/favicon.ico',
                'tags' => ['noticias', 'negocios', 'finanzas']
            ],
            [
                'nombre' => 'BBC News',
                'descripcion' => 'Servicio de noticias internacional',
                'enlace' => 'https://www.bbc.com/news',
                'en_linea' => true,
                'imagen' => null,
                'tags' => ['noticias', 'medios-de-comunicacion']
            ],
            
            // E-commerce
            [
                'nombre' => 'Amazon',
                'descripcion' => 'Plataforma de comercio electrónico global',
                'enlace' => 'https://www.amazon.com',
                'en_linea' => true,
                'imagen' => null,
                'tags' => ['e-commerce', 'negocios', 'tecnologia']
            ],
            [
                'nombre' => 'Mercado Libre',
                'descripcion' => 'Plataforma de e-commerce en Latinoamérica',
                'enlace' => 'https://www.mercadolibre.com.mx',
                'en_linea' => true,
                'imagen' => null,
                'tags' => ['e-commerce', 'negocios']
            ],
            
            // Educación
            [
                'nombre' => 'Coursera',
                'descripcion' => 'Plataforma de cursos en línea',
                'enlace' => 'https://www.coursera.org',
                'en_linea' => true,
                'imagen' => null,
                'tags' => ['educacion', 'tecnologia']
            ],
            [
                'nombre' => 'Khan Academy',
                'descripcion' => 'Plataforma educativa gratuita',
                'enlace' => 'https://www.khanacademy.org',
                'en_linea' => true,
                'imagen' => null,
                'tags' => ['educacion']
            ],
            
            // Salud
            [
                'nombre' => 'WebMD',
                'descripcion' => 'Portal de información sobre salud',
                'enlace' => 'https://www.webmd.com',
                'en_linea' => true,
                'imagen' => null,
                'tags' => ['salud', 'noticias']
            ],
            
            // Turismo
            [
                'nombre' => 'Booking.com',
                'descripcion' => 'Plataforma de reservas de hoteles',
                'enlace' => 'https://www.booking.com',
                'en_linea' => true,
                'imagen' => null,
                'tags' => ['turismo', 'e-commerce']
            ],
            [
                'nombre' => 'TripAdvisor',
                'descripcion' => 'Plataforma de viajes y reseñas',
                'enlace' => 'https://www.tripadvisor.com',
                'en_linea' => true,
                'imagen' => null,
                'tags' => ['turismo', 'gastronomia']
            ],
            
            // Gastronomía
            [
                'nombre' => 'Yelp',
                'descripcion' => 'Plataforma de reseñas de restaurantes y negocios',
                'enlace' => 'https://www.yelp.com',
                'en_linea' => true,
                'imagen' => null,
                'tags' => ['gastronomia', 'turismo']
            ],
            
            // Inmobiliaria
            [
                'nombre' => 'Zillow',
                'descripcion' => 'Plataforma de bienes raíces',
                'enlace' => 'https://www.zillow.com',
                'en_linea' => true,
                'imagen' => null,
                'tags' => ['inmobiliaria', 'e-commerce']
            ],
            
            // Belleza
            [
                'nombre' => 'Sephora',
                'descripcion' => 'Tienda de cosméticos y belleza',
                'enlace' => 'https://www.sephora.com',
                'en_linea' => true,
                'imagen' => null,
                'tags' => ['belleza', 'e-commerce', 'moda']
            ],
            
            // Fitness
            [
                'nombre' => 'MyFitnessPal',
                'descripcion' => 'Aplicación de seguimiento de fitness y nutrición',
                'enlace' => 'https://www.myfitnesspal.com',
                'en_linea' => true,
                'imagen' => null,
                'tags' => ['fitness', 'salud']
            ],
            
            // Música
            [
                'nombre' => 'Spotify',
                'descripcion' => 'Plataforma de streaming de música',
                'enlace' => 'https://www.spotify.com',
                'en_linea' => true,
                'imagen' => null,
                'tags' => ['musica', 'entretenimiento']
            ],
            
            // Deportes
            [
                'nombre' => 'ESPN',
                'descripcion' => 'Red de deportes y entretenimiento',
                'enlace' => 'https://www.espn.com',
                'en_linea' => true,
                'imagen' => null,
                'tags' => ['deportes', 'noticias', 'entretenimiento']
            ],
            
            // Finanzas
            [
                'nombre' => 'Bloomberg',
                'descripcion' => 'Servicio de noticias financieras',
                'enlace' => 'https://www.bloomberg.com',
                'en_linea' => true,
                'imagen' => null,
                'tags' => ['finanzas', 'negocios', 'noticias']
            ],
        ];

        foreach ($sitios as $sitioData) {
            // Extraer los tags antes de crear el sitio
            $tagSlugs = $sitioData['tags'];
            unset($sitioData['tags']);
            
            // Crear el sitio
            $sitio = Sitio::firstOrCreate(
                ['enlace' => $sitioData['enlace']],
                $sitioData
            );
            
            // Obtener IDs de tags y adjuntarlos
            $tagIds = [];
            foreach ($tagSlugs as $slug) {
                if (isset($tags[$slug]) && $tags[$slug]) {
                    $tagIds[] = $tags[$slug]->id;
                }
            }
            
            if (!empty($tagIds)) {
                $sitio->tags()->syncWithoutDetaching($tagIds);
            }
            
            $this->command->info("Sitio creado: " . $sitio->nombre);
        }
        
        $this->command->info('¡Se han creado los sitios de ejemplo exitosamente!');
    }
}
