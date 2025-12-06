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
                'name' => 'Restaurante La Casona',
                'email' => 'contacto@lacasona.com',
                'phone' => '+506 2222-1111',
                'website' => 'https://www.lacasona.com',
                'proposed_site' => 'https://www.lacasona.com',
                'message' => 'Necesitamos un sitio web moderno para nuestro restaurante.',
                'address' => 'Avenida Central, San José',
                'telefono_1' => '+506 2222-1111',
                'telefono_2' => '+506 8888-1111',
                'feedback' => 'Cliente interesado en diseño web',
                'propuesta' => 'Propuesta de diseño web con menú interactivo',
                'propuesta_enviada' => false,
            ],
            [
                'name' => 'Hotel Playa Hermosa',
                'email' => 'reservas@playahermosa.com',
                'phone' => '+506 2555-2222',
                'website' => 'https://www.playahermosa.com',
                'proposed_site' => 'https://www.playahermosa.com/nuevo',
                'message' => 'Queremos renovar nuestro sitio web para mejorar las reservas online.',
                'address' => 'Playa Hermosa, Guanacaste',
                'telefono_1' => '+506 2555-2222',
                'telefono_2' => null,
                'feedback' => 'Cliente necesita sistema de reservas',
                'propuesta' => 'Sitio web con sistema de reservas integrado',
                'propuesta_enviada' => true,
            ],
            [
                'name' => 'Tienda de Ropa Fashion',
                'email' => 'ventas@fashion.com',
                'phone' => '+506 2333-3333',
                'website' => 'https://www.fashion.com',
                'proposed_site' => 'https://www.fashion.com/tienda',
                'message' => 'Necesitamos una tienda online para vender nuestros productos.',
                'address' => 'Centro Comercial Multiplaza, Escazú',
                'telefono_1' => '+506 2333-3333',
                'telefono_2' => '+506 8999-3333',
                'feedback' => 'Cliente interesado en e-commerce',
                'propuesta' => 'Tienda online con carrito de compras',
                'propuesta_enviada' => false,
            ],
            [
                'name' => 'Clínica Dental Sonrisa',
                'email' => 'info@sonrisa.com',
                'phone' => '+506 2444-4444',
                'website' => 'https://www.sonrisa.com',
                'proposed_site' => 'https://www.sonrisa.com/nuevo',
                'message' => 'Buscamos un sitio web profesional para nuestra clínica dental.',
                'address' => 'Barrio Escalante, San José',
                'telefono_1' => '+506 2444-4444',
                'telefono_2' => null,
                'feedback' => 'Cliente necesita sitio informativo',
                'propuesta' => 'Sitio web con información de servicios y citas online',
                'propuesta_enviada' => true,
            ],
            [
                'name' => 'Academia de Inglés English Plus',
                'email' => 'admision@englishplus.com',
                'phone' => '+506 2666-5555',
                'website' => 'https://www.englishplus.com',
                'proposed_site' => 'https://www.englishplus.com/academia',
                'message' => 'Queremos un sitio web para promocionar nuestros cursos de inglés.',
                'address' => 'San Pedro, San José',
                'telefono_1' => '+506 2666-5555',
                'telefono_2' => '+506 8111-5555',
                'feedback' => 'Cliente necesita sitio educativo',
                'propuesta' => 'Sitio web con información de cursos y registro online',
                'propuesta_enviada' => false,
            ],
            [
                'name' => 'Gimnasio FitZone',
                'email' => 'contacto@fitzone.com',
                'phone' => '+506 2777-6666',
                'website' => 'https://www.fitzone.com',
                'proposed_site' => 'https://www.fitzone.com/gimnasio',
                'message' => 'Necesitamos un sitio web para mostrar nuestras instalaciones y planes.',
                'address' => 'Curridabat, San José',
                'telefono_1' => '+506 2777-6666',
                'telefono_2' => null,
                'feedback' => 'Cliente interesado en sitio promocional',
                'propuesta' => 'Sitio web con galería de imágenes y planes de membresía',
                'propuesta_enviada' => false,
            ],
        ];

        foreach ($clients as $client) {
            Client::create($client);
        }
    }
}
