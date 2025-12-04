<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            // Restaurantes
            [
                'name' => 'Roberto Sánchez',
                'email' => 'contacto@restaurante-mexicano.com',
                'telefono_1' => '5555123456',
                'website' => 'restaurante-mexicano.com',
                'proposed_site' => 'reservaciones.restaurante-mexicano.com',
                'feedback' => 'Queremos un sistema de reservaciones en línea para nuestro restaurante de comida mexicana.',
                'propuesta_enviada' => false,
            ],
            [
                'name' => 'María González',
                'email' => 'info@italian-kitchen.com',
                'telefono_1' => '5555234567',
                'website' => 'italian-kitchen.com',
                'proposed_site' => 'menu.italian-kitchen.com',
                'feedback' => 'Necesitamos un sitio web para mostrar nuestro menú y recibir pedidos en línea.',
                'propuesta_enviada' => true,
            ],
            [
                'name' => 'Carlos Ramírez',
                'email' => 'carlos@sushi-bar.com',
                'telefono_1' => '5555345678',
                'website' => 'sushi-bar.com',
                'proposed_site' => 'delivery.sushi-bar.com',
                'feedback' => 'Buscamos una plataforma de pedidos en línea para nuestro servicio de delivery.',
                'propuesta_enviada' => false,
            ],
            
            // Hoteles
            [
                'name' => 'Ana Martínez',
                'email' => 'reservas@hotel-paraiso.com',
                'telefono_1' => '5555456789',
                'website' => 'hotel-paraiso.com',
                'proposed_site' => 'reservas.hotel-paraiso.com',
                'feedback' => 'Queremos un sistema de reservas en línea para nuestro hotel boutique.',
                'propuesta_enviada' => true,
            ],
            [
                'name' => 'Luis Hernández',
                'email' => 'contacto@hostal-centro.com',
                'telefono_1' => '5555567890',
                'website' => 'hostal-centro.com',
                'proposed_site' => 'bookings.hostal-centro.com',
                'feedback' => 'Necesitamos un sitio web moderno para gestionar nuestras reservas.',
                'propuesta_enviada' => false,
            ],
            
            // Comercio Minorista
            [
                'name' => 'Laura Torres',
                'email' => 'laura@belleza-natural.com',
                'telefono_1' => '5555678901',
                'website' => 'belleza-natural.com',
                'proposed_site' => 'tienda.belleza-natural.com',
                'feedback' => 'Queremos vender nuestros productos de belleza orgánicos en línea.',
                'propuesta_enviada' => true,
            ],
            [
                'name' => 'Pedro Jiménez',
                'email' => 'pedro@electronica-mx.com',
                'telefono_1' => '5555789012',
                'website' => 'electronica-mx.com',
                'proposed_site' => 'shop.electronica-mx.com',
                'feedback' => 'Buscamos una tienda en línea para nuestros productos electrónicos.',
                'propuesta_enviada' => false,
            ],
            [
                'name' => 'Sofía Ruiz',
                'email' => 'sofia@moda-femenina.com',
                'telefono_1' => '5555890123',
                'website' => 'moda-femenina.com',
                'proposed_site' => 'tienda.moda-femenina.com',
                'feedback' => 'Necesitamos un e-commerce para nuestra línea de ropa femenina.',
                'propuesta_enviada' => true,
            ],
            
            // Salud
            [
                'name' => 'Dr. Miguel Ángel',
                'email' => 'contacto@clinica-dental.com',
                'telefono_1' => '5555901234',
                'website' => 'clinica-dental.com',
                'proposed_site' => 'citas.clinica-dental.com',
                'feedback' => 'Queremos un sistema de citas en línea para nuestra clínica dental.',
                'propuesta_enviada' => false,
            ],
            [
                'name' => 'Dra. Patricia López',
                'email' => 'info@fisioterapia-pro.com',
                'telefono_1' => '5555012345',
                'website' => 'fisioterapia-pro.com',
                'proposed_site' => 'agenda.fisioterapia-pro.com',
                'feedback' => 'Necesitamos una plataforma para gestionar citas de fisioterapia.',
                'propuesta_enviada' => true,
            ],
            
            // Educación
            [
                'name' => 'María Fernández',
                'email' => 'maria@consultoria-educativa.com',
                'telefono_1' => '5555123456',
                'website' => 'consultoria-educativa.com',
                'proposed_site' => 'cursos.consultoria-educativa.com',
                'feedback' => 'Necesitamos una plataforma para nuestros cursos en línea de consultoría.',
                'propuesta_enviada' => false,
            ],
            [
                'name' => 'Prof. Juan Pérez',
                'email' => 'juan@academia-online.com',
                'telefono_1' => '5555234567',
                'website' => 'academia-online.com',
                'proposed_site' => 'aula.academia-online.com',
                'feedback' => 'Buscamos un sistema de aprendizaje en línea para nuestros estudiantes.',
                'propuesta_enviada' => true,
            ],
            
            // Inmobiliaria
            [
                'name' => 'Javier López',
                'email' => 'javier@inmobiliaria-lopez.com',
                'telefono_1' => '5555345678',
                'website' => 'inmobiliaria-lopez.com',
                'proposed_site' => 'propiedades.inmobiliaria-lopez.com',
                'feedback' => 'Buscamos un catálogo en línea para nuestras propiedades en venta y renta.',
                'propuesta_enviada' => false,
            ],
            [
                'name' => 'Carmen Silva',
                'email' => 'carmen@casas-premium.com',
                'telefono_1' => '5555456789',
                'website' => 'casas-premium.com',
                'proposed_site' => 'listings.casas-premium.com',
                'feedback' => 'Queremos mostrar nuestras propiedades de lujo en un sitio web elegante.',
                'propuesta_enviada' => true,
            ],
            
            // Servicios Profesionales
            [
                'name' => 'Carlos Méndez',
                'email' => 'carlos@empresa-tecnologia.com',
                'telefono_1' => '5555567890',
                'website' => 'empresa-tecnologia.com',
                'proposed_site' => 'tienda.empresa-tecnologia.com',
                'feedback' => 'Necesitamos una tienda en línea para nuestros productos de tecnología.',
                'propuesta_enviada' => false,
            ],
            [
                'name' => 'Ana García',
                'email' => 'ana@garcia-design.com',
                'telefono_1' => '5555678901',
                'website' => 'garcia-design.com',
                'proposed_site' => 'portafolio.garcia-design.com',
                'feedback' => 'Buscamos mostrar nuestro portafolio de diseño gráfico de manera profesional.',
                'propuesta_enviada' => true,
            ],
            [
                'name' => 'Ricardo Morales',
                'email' => 'ricardo@abogados-mx.com',
                'telefono_1' => '5555789012',
                'website' => 'abogados-mx.com',
                'proposed_site' => 'consultas.abogados-mx.com',
                'feedback' => 'Necesitamos un sitio web profesional para nuestro despacho jurídico.',
                'propuesta_enviada' => false,
            ],
            
            // Automotriz
            [
                'name' => 'Fernando Castro',
                'email' => 'fernando@autos-usados.com',
                'telefono_1' => '5555890123',
                'website' => 'autos-usados.com',
                'proposed_site' => 'inventario.autos-usados.com',
                'feedback' => 'Queremos mostrar nuestro inventario de autos usados en línea.',
                'propuesta_enviada' => true,
            ],
            [
                'name' => 'Mario Sánchez',
                'email' => 'mario@taller-mecanico.com',
                'telefono_1' => '5555901234',
                'website' => 'taller-mecanico.com',
                'proposed_site' => 'citas.taller-mecanico.com',
                'feedback' => 'Buscamos un sistema de citas para nuestro taller mecánico.',
                'propuesta_enviada' => false,
            ],
            
            // Belleza y Cuidado Personal
            [
                'name' => 'Isabel Mendoza',
                'email' => 'isabel@salon-belleza.com',
                'telefono_1' => '5555012345',
                'website' => 'salon-belleza.com',
                'proposed_site' => 'reservas.salon-belleza.com',
                'feedback' => 'Necesitamos un sistema de reservas para nuestro salón de belleza.',
                'propuesta_enviada' => true,
            ],
            [
                'name' => 'Roberto Vega',
                'email' => 'roberto@barberia-moderna.com',
                'telefono_1' => '5555123456',
                'website' => 'barberia-moderna.com',
                'proposed_site' => 'citas.barberia-moderna.com',
                'feedback' => 'Queremos gestionar las citas de nuestros clientes en línea.',
                'propuesta_enviada' => false,
            ],
            
            // Nuevos clientes agregados
            [
                'name' => 'La Herradura Supermarket',
                'email' => 'laherradurafood@gmail.com',
                'telefono_1' => '+56994498870',
                'telefono_2' => '(202) 291-1458',
                'address' => '3412 Georgia Ave NW, Washington, DC 20010, USA',
                'website' => 'http://www.laherradurafood.com/',
                'proposed_site' => 'pedidos.laherradurafood.com',
                'feedback' => 'El sitio web de La Herradura Food presenta un diseño visualmente atractivo y fácil de navegar. Destaca su menú y la información de contacto. Sin embargo, carece de opciones de pedido online, lo que podría limitar la conveniencia para los clientes. La información sobre la ubicación podría ser más prominente.',
                'propuesta' => 'Implementar un sistema de pedidos online para facilitar las compras y ampliar el alcance del negocio. Considerar la integración con plataformas de delivery. Añadir un mapa interactivo para una mejor indicación de la ubicación física.',
                'propuesta_enviada' => false,
            ],
            [
                'name' => 'Tech Solutions Inc',
                'email' => 'contacto@techsolutions.com',
                'telefono_1' => '+1-555-123-4567',
                'telefono_2' => '+1-555-123-4568',
                'address' => '123 Main Street, New York, NY 10001, USA',
                'website' => 'https://www.techsolutions.com',
                'proposed_site' => 'tienda.techsolutions.com',
                'feedback' => 'El sitio web actual es funcional pero necesita una actualización visual. La navegación es clara pero el diseño se ve desactualizado. Los productos están bien organizados pero falta integración de e-commerce.',
                'propuesta' => 'Rediseño completo del sitio web con enfoque en e-commerce. Implementar carrito de compras, sistema de pagos en línea y panel de administración para gestión de productos. Mejorar la experiencia móvil con diseño responsive.',
                'propuesta_enviada' => false,
            ],
            [
                'name' => 'Green Garden Restaurant',
                'email' => 'info@greengarden.com',
                'telefono_1' => '+1-555-987-6543',
                'telefono_2' => '+1-555-987-6544',
                'address' => '456 Oak Avenue, Los Angeles, CA 90001, USA',
                'website' => 'https://www.greengarden.com',
                'proposed_site' => 'reservas.greengarden.com',
                'feedback' => 'El restaurante tiene una presencia online básica. El menú está disponible pero no es interactivo. No hay opción de reservas online ni pedidos para llevar. La galería de fotos es limitada.',
                'propuesta' => 'Desarrollar un sistema completo de reservas online con calendario integrado. Implementar menú digital interactivo con fotos de alta calidad. Agregar sistema de pedidos para llevar y delivery. Crear galería de fotos mejorada y sección de testimonios.',
                'propuesta_enviada' => true,
            ],
        ];

        foreach ($clients as $client) {
            Client::firstOrCreate(
                [
                    'email' => $client['email'],
                ],
                $client
            );
        }

        $this->command->info('¡Se han creado los clientes extraídos exitosamente!');
    }
}
