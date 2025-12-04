<?php

namespace Database\Seeders;

use App\Models\Factura;
use App\Models\Cliente;
use Illuminate\Database\Seeder;

class FacturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientes = Cliente::all();

        if ($clientes->isEmpty()) {
            return;
        }

        $facturas = [
            [
                'cliente_id' => $clientes[0]->id ?? 1,
                'numero_factura' => 'FAC-20241101-001',
                'fecha_emision' => '2024-11-01',
                'concepto' => 'diseno_web',
                'subtotal' => 500.00,
                'total' => 565.00,
                'metodo_pago' => 'transferencia',
                'estado' => 'pagada',
                'fecha_vencimiento' => '2024-11-15',
                'notas' => 'Diseño web completo con 5 páginas',
            ],
            [
                'cliente_id' => $clientes[1]->id ?? 1,
                'numero_factura' => 'FAC-20241115-002',
                'fecha_emision' => '2024-11-15',
                'concepto' => 'redes_sociales',
                'subtotal' => 199.00,
                'total' => 224.87,
                'metodo_pago' => 'sinpe',
                'estado' => 'pagada',
                'fecha_vencimiento' => '2024-11-30',
                'notas' => 'Gestión mensual de redes sociales',
            ],
            [
                'cliente_id' => $clientes[2]->id ?? 1,
                'numero_factura' => 'FAC-20241201-003',
                'fecha_emision' => '2024-12-01',
                'concepto' => 'mantenimiento',
                'subtotal' => 99.00,
                'total' => 111.87,
                'metodo_pago' => 'tarjeta',
                'estado' => 'pendiente',
                'fecha_vencimiento' => '2024-12-15',
                'notas' => 'Mantenimiento mensual del sitio web',
            ],
            [
                'cliente_id' => $clientes[0]->id ?? 1,
                'numero_factura' => 'FAC-20241202-004',
                'fecha_emision' => '2024-12-02',
                'concepto' => 'hosting',
                'subtotal' => 150.00,
                'total' => 169.50,
                'metodo_pago' => 'paypal',
                'estado' => 'pendiente',
                'fecha_vencimiento' => '2024-12-17',
                'notas' => 'Renovación anual de hosting y dominio',
            ],
            [
                'cliente_id' => $clientes[3]->id ?? 1,
                'numero_factura' => 'FAC-20241001-005',
                'fecha_emision' => '2024-10-01',
                'concepto' => 'seo',
                'subtotal' => 349.00,
                'total' => 394.37,
                'metodo_pago' => 'transferencia',
                'estado' => 'pagada',
                'fecha_vencimiento' => '2024-10-15',
                'notas' => 'Optimización SEO mensual',
            ],
            [
                'cliente_id' => $clientes[4]->id ?? 1,
                'numero_factura' => 'FAC-20240901-006',
                'fecha_emision' => '2024-09-01',
                'concepto' => 'publicidad',
                'subtotal' => 250.00,
                'total' => 282.50,
                'metodo_pago' => 'efectivo',
                'estado' => 'vencida',
                'fecha_vencimiento' => '2024-09-15',
                'notas' => 'Campaña publicitaria en Facebook e Instagram',
            ],
        ];

        foreach ($facturas as $factura) {
            Factura::create($factura);
        }
    }
}
