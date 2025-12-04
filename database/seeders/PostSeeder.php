<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = [
            [
                'title' => 'Nuevo Diseño Web Moderno',
                'content' => 'Hemos lanzado nuestro nuevo diseño web con características innovadoras que mejoran la experiencia del usuario. La interfaz es más intuitiva y responsiva, adaptándose perfectamente a todos los dispositivos. Incluye nuevas funcionalidades de búsqueda avanzada y navegación mejorada. Los usuarios ahora pueden disfrutar de una experiencia más fluida y profesional.',
                'image_url' => 'https://images.unsplash.com/photo-1467232004584-a241de8bcf5d?w=800&h=600&fit=crop',
            ],
            [
                'title' => 'Estrategia de Marketing Digital',
                'content' => 'Nuestra nueva estrategia de marketing digital está generando resultados increíbles. Hemos aumentado el tráfico web en un 300% y las conversiones han mejorado significativamente. Utilizamos técnicas avanzadas de SEO, contenido de calidad y campañas en redes sociales. El engagement de nuestros clientes ha crecido exponencialmente.',
                'image_url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=600&fit=crop',
            ],
            [
                'title' => 'Lanzamiento de Nueva App',
                'content' => 'Estamos emocionados de anunciar el lanzamiento de nuestra nueva aplicación móvil. La app incluye todas las funcionalidades principales de nuestra plataforma web, con una interfaz optimizada para móviles. Los usuarios pueden acceder a sus cuentas, realizar transacciones y recibir notificaciones en tiempo real. Disponible en iOS y Android.',
                'image_url' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=800&h=600&fit=crop',
            ],
            [
                'title' => 'Mejoras en Seguridad',
                'content' => 'Hemos implementado mejoras significativas en la seguridad de nuestra plataforma. Nuevos protocolos de encriptación protegen los datos de nuestros usuarios. Sistema de autenticación de dos factores disponible para todas las cuentas. Auditorías de seguridad regulares y monitoreo continuo. Nuestro compromiso es mantener la información segura.',
                'image_url' => 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=800&h=600&fit=crop',
            ],
            [
                'title' => 'Nuevo Equipo de Desarrollo',
                'content' => 'Hemos expandido nuestro equipo de desarrollo con talentos excepcionales. Nuevos desarrolladores especializados en tecnologías modernas se han unido al proyecto. El equipo está trabajando en funcionalidades innovadoras que revolucionarán la experiencia del usuario. Estamos comprometidos con la excelencia y la innovación constante.',
                'image_url' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&h=600&fit=crop',
            ],
            [
                'title' => 'Actualización de Servicios',
                'content' => 'Hemos actualizado nuestros servicios con nuevas características y mejoras. Los usuarios ahora pueden disfrutar de tiempos de carga más rápidos y mayor estabilidad. Hemos optimizado nuestros servidores y mejorado la infraestructura. Nuevas funcionalidades están disponibles para mejorar la productividad. El rendimiento general ha mejorado significativamente.',
                'image_url' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=800&h=600&fit=crop',
            ],
            [
                'title' => 'Colaboración con Nuevos Socios',
                'content' => 'Estamos orgullosos de anunciar nuevas colaboraciones estratégicas con empresas líderes en la industria. Estas alianzas nos permiten ofrecer servicios más completos y de mayor calidad. Trabajamos juntos para crear soluciones innovadoras que beneficien a nuestros clientes. El futuro se ve prometedor con estas nuevas asociaciones.',
                'image_url' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=800&h=600&fit=crop',
            ],
            [
                'title' => 'Premio a la Innovación',
                'content' => 'Hemos recibido un prestigioso premio por nuestra innovación en tecnología. Este reconocimiento valida nuestro compromiso con la excelencia y la innovación constante. Nuestro equipo ha trabajado incansablemente para crear soluciones que marquen la diferencia. Estamos agradecidos por este reconocimiento y seguiremos innovando.',
                'image_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&h=600&fit=crop',
            ],
            [
                'title' => 'Nuevo Centro de Datos',
                'content' => 'Hemos inaugurado nuestro nuevo centro de datos con tecnología de última generación. Este centro garantiza mayor velocidad y confiabilidad para nuestros servicios. Implementamos sistemas de respaldo redundantes para máxima disponibilidad. El nuevo centro está diseñado para escalar con el crecimiento de nuestra plataforma.',
                'image_url' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=800&h=600&fit=crop',
            ],
            [
                'title' => 'Programa de Capacitación',
                'content' => 'Hemos lanzado un nuevo programa de capacitación para nuestros usuarios. Cursos gratuitos sobre cómo aprovechar al máximo nuestras herramientas. Webinars semanales con expertos de la industria. Materiales educativos actualizados regularmente. Nuestro objetivo es empoderar a nuestros usuarios con conocimiento y habilidades.',
                'image_url' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800&h=600&fit=crop',
            ],
            [
                'title' => 'Expansión Internacional',
                'content' => 'Estamos expandiendo nuestros servicios a nuevos mercados internacionales. Hemos abierto oficinas en varios países para servir mejor a nuestros clientes globales. Nuestros servicios ahora están disponibles en múltiples idiomas. Estamos emocionados de llevar nuestras soluciones a más personas alrededor del mundo.',
                'image_url' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=800&h=600&fit=crop',
            ],
            [
                'title' => 'Sostenibilidad y Medio Ambiente',
                'content' => 'Hemos implementado nuevas iniciativas de sostenibilidad en nuestras operaciones. Nuestro centro de datos ahora funciona completamente con energía renovable. Hemos reducido nuestra huella de carbono en un 50%. Estamos comprometidos con prácticas empresariales responsables. El futuro sostenible es nuestra prioridad.',
                'image_url' => 'https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?w=800&h=600&fit=crop',
            ],
        ];

        foreach ($posts as $post) {
            Post::create($post);
        }
    }
}
