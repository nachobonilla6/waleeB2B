<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            [
                'name' => 'Undefined Wellness',
                'website' => 'https://undefinedwellness.com/',
                'email' => 'info@undefinedwellness.com',
                'address' => '58 Bridge St Suite 5, White River Junction, VT 05001, USA',
                'telefono_1' => null,
                'telefono_2' => null,
                'feedback' => 'El sitio web de Undefined Wellness presenta un diseño moderno y atractivo, con imágenes de alta calidad y una navegación intuitiva. La información sobre los productos y servicios es clara y concisa. Sin embargo, la ausencia de precios visibles podría ser una barrera para algunos clientes. Sería beneficioso incluir testimonios de clientes para generar confianza.',
                'propuesta' => 'Considerar ofrecer consultas virtuales gratuitas de 15 minutos para ayudar a los clientes a elegir los productos adecuados, aumentando así la tasa de conversión.',
                'proposed_site' => null,
                'phone' => null,
                'message' => null,
                'propuesta_enviada' => false,
            ],
            [
                'name' => 'Web Undefined',
                'website' => 'https://www.webundefined.com/',
                'email' => 'info@webundefined.com',
                'address' => '73 Virginia Ave, East Greenwich, RI 02818, USA',
                'telefono_1' => null,
                'telefono_2' => '(401) 523-3295',
                'feedback' => 'El sitio web WebUndefined tiene un diseño limpio y profesional, aunque la información sobre los servicios ofrecidos es algo vaga. Destaca la sección de portafolio, que muestra ejemplos de trabajos realizados. La falta de una sección de \'sobre nosotros\' o \'equipo\' dificulta la conexión con los visitantes. La velocidad de carga es buena, pero podría mejorar.',
                'propuesta' => 'Añadir una sección \'sobre nosotros\' con fotos del equipo y sus especialidades para humanizar la marca y generar mayor confianza en los clientes potenciales.',
                'proposed_site' => null,
                'phone' => null,
                'message' => null,
                'propuesta_enviada' => false,
            ],
        ];

        foreach ($clients as $client) {
            Client::create($client);
        }
    }
}
