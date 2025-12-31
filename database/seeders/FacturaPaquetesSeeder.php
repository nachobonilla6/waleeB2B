<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FacturaPaquete;

class FacturaPaquetesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paquetes = [
            // Desarrollo Web
            [
                'nombre' => 'Sitio Web Básico',
                'descripcion' => 'Sitio web de hasta 5 páginas, diseño responsive, formulario de contacto',
                'categoria' => 'desarrollo_web',
                'precio' => 150000.00,
                'activo' => true,
                'items_incluidos' => [
                    'Diseño de hasta 5 páginas',
                    'Diseño responsive (móvil, tablet, desktop)',
                    'Formulario de contacto',
                    'Optimización básica SEO',
                    'Entrega de código fuente',
                ],
                'orden' => 1,
            ],
            [
                'nombre' => 'Sitio Web Profesional',
                'descripcion' => 'Sitio web de hasta 10 páginas, panel de administración, blog integrado',
                'categoria' => 'desarrollo_web',
                'precio' => 300000.00,
                'activo' => true,
                'items_incluidos' => [
                    'Diseño de hasta 10 páginas',
                    'Panel de administración',
                    'Blog integrado',
                    'Diseño responsive',
                    'Optimización SEO avanzada',
                    'Integración con redes sociales',
                    'Entrega de código fuente',
                ],
                'orden' => 2,
            ],
            [
                'nombre' => 'E-commerce Básico',
                'descripcion' => 'Tienda online con hasta 50 productos, pasarela de pagos, gestión de inventario',
                'categoria' => 'desarrollo_web',
                'precio' => 500000.00,
                'activo' => true,
                'items_incluidos' => [
                    'Catálogo de hasta 50 productos',
                    'Carrito de compras',
                    'Pasarela de pagos',
                    'Gestión de inventario',
                    'Panel de administración',
                    'Diseño responsive',
                ],
                'orden' => 3,
            ],
            // Marketing
            [
                'nombre' => 'Gestión Redes Sociales Básica',
                'descripcion' => 'Gestión mensual de 2 redes sociales, 12 publicaciones, análisis básico',
                'categoria' => 'marketing',
                'precio' => 75000.00,
                'activo' => true,
                'items_incluidos' => [
                    'Gestión de 2 redes sociales',
                    '12 publicaciones mensuales',
                    'Diseño de contenido',
                    'Análisis básico de métricas',
                ],
                'orden' => 1,
            ],
            [
                'nombre' => 'Gestión Redes Sociales Completa',
                'descripcion' => 'Gestión mensual de 4 redes sociales, 24 publicaciones, estrategia de contenido',
                'categoria' => 'marketing',
                'precio' => 150000.00,
                'activo' => true,
                'items_incluidos' => [
                    'Gestión de 4 redes sociales',
                    '24 publicaciones mensuales',
                    'Estrategia de contenido',
                    'Diseño profesional de contenido',
                    'Análisis avanzado de métricas',
                    'Respuesta a comentarios',
                ],
                'orden' => 2,
            ],
            // Mantenimiento
            [
                'nombre' => 'Mantenimiento Básico Mensual',
                'descripcion' => 'Mantenimiento mensual: actualizaciones, backups, monitoreo básico',
                'categoria' => 'mantenimiento',
                'precio' => 35000.00,
                'activo' => true,
                'items_incluidos' => [
                    'Actualizaciones de seguridad',
                    'Backups semanales',
                    'Monitoreo básico',
                    'Soporte técnico (5 horas/mes)',
                ],
                'orden' => 1,
            ],
            [
                'nombre' => 'Mantenimiento Completo Mensual',
                'descripcion' => 'Mantenimiento mensual completo: actualizaciones, backups, optimización, soporte',
                'categoria' => 'mantenimiento',
                'precio' => 75000.00,
                'activo' => true,
                'items_incluidos' => [
                    'Actualizaciones de seguridad',
                    'Backups diarios',
                    'Monitoreo 24/7',
                    'Optimización de rendimiento',
                    'Soporte técnico ilimitado',
                    'Reportes mensuales',
                ],
                'orden' => 2,
            ],
            // Hosting
            [
                'nombre' => 'Hosting Básico Anual',
                'descripcion' => 'Hosting compartido, dominio incluido, SSL gratuito, 10GB de almacenamiento',
                'categoria' => 'hosting',
                'precio' => 120000.00,
                'activo' => true,
                'items_incluidos' => [
                    'Hosting compartido',
                    'Dominio .com/.cr incluido',
                    'Certificado SSL gratuito',
                    '10GB de almacenamiento',
                    'Ancho de banda ilimitado',
                    'Soporte técnico',
                ],
                'orden' => 1,
            ],
        ];

        foreach ($paquetes as $paquete) {
            FacturaPaquete::create($paquete);
        }
    }
}
