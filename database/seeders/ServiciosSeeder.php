<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Servicio;

class ServiciosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $servicios = [
            [
                'codigo' => 'diseno_web',
                'nombre' => 'Web Design',
                'descripcion' => 'Professional website design and development, including responsive design, basic SEO optimization, and source code delivery.',
                'tipo' => 'predefinido',
                'activo' => true,
            ],
            [
                'codigo' => 'redes_sociales',
                'nombre' => 'Social Media Management',
                'descripcion' => 'Complete social media management, including content creation, post scheduling, follower interaction, and metrics analysis.',
                'tipo' => 'predefinido',
                'activo' => true,
            ],
            [
                'codigo' => 'seo',
                'nombre' => 'SEO / Positioning',
                'descripcion' => 'Search engine optimization services, including keyword research, on-page optimization, link building, and results analysis.',
                'tipo' => 'predefinido',
                'activo' => true,
            ],
            [
                'codigo' => 'publicidad',
                'nombre' => 'Digital Advertising',
                'descripcion' => 'Digital advertising campaigns on platforms including Google Ads, Facebook Ads, Instagram Ads, and advertising budget management.',
                'tipo' => 'predefinido',
                'activo' => true,
            ],
            [
                'codigo' => 'mantenimiento',
                'nombre' => 'Web Maintenance',
                'descripcion' => 'Continuous website maintenance service, including security updates, backups, monitoring, and technical support.',
                'tipo' => 'predefinido',
                'activo' => true,
            ],
            [
                'codigo' => 'hosting',
                'nombre' => 'Hosting & Domain',
                'descripcion' => 'Web hosting and domain registration services, including hosting, SSL certificates, email, and technical support.',
                'tipo' => 'predefinido',
                'activo' => true,
            ],
            [
                'codigo' => 'combo',
                'nombre' => 'Complete Package',
                'descripcion' => 'Complete package including web design, hosting, domain, social media management, basic SEO, and monthly maintenance.',
                'tipo' => 'predefinido',
                'activo' => true,
            ],
        ];

        foreach ($servicios as $servicio) {
            Servicio::updateOrCreate(
                ['codigo' => $servicio['codigo']],
                $servicio
            );
        }
    }
}
