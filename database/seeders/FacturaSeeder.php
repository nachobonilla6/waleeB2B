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

        // Limpiar facturas existentes para evitar duplicados
        Factura::truncate();

        $conceptos = [
            'diseno_web' => ['subtotal' => 500.00, 'notas' => 'Diseño web completo con 5 páginas'],
            'redes_sociales' => ['subtotal' => 199.00, 'notas' => 'Gestión mensual de redes sociales'],
            'seo' => ['subtotal' => 349.00, 'notas' => 'Optimización SEO mensual'],
            'publicidad' => ['subtotal' => 250.00, 'notas' => 'Campaña publicitaria en Facebook e Instagram'],
            'mantenimiento' => ['subtotal' => 99.00, 'notas' => 'Mantenimiento mensual del sitio web'],
            'hosting' => ['subtotal' => 150.00, 'notas' => 'Renovación anual de hosting y dominio'],
        ];

        $metodosPago = ['transferencia', 'sinpe', 'tarjeta', 'efectivo', 'paypal'];
        $estados = ['pagada', 'pendiente', 'vencida', 'cancelada'];
        
        $facturaCounter = 1;

        // Crear 3-5 facturas por cliente
        foreach ($clientes as $cliente) {
            $numFacturas = rand(3, 5);
            
            for ($i = 0; $i < $numFacturas; $i++) {
                $conceptoKey = array_rand($conceptos);
                $concepto = $conceptos[$conceptoKey];
                $subtotal = $concepto['subtotal'];
                $total = round($subtotal * 1.13, 2);
                
                $fechaEmision = now()->subDays(rand(0, 180));
                $fechaVencimiento = $fechaEmision->copy()->addDays(rand(15, 30));
                
                $estado = $estados[array_rand($estados)];
                $metodoPago = $metodosPago[array_rand($metodosPago)];
                
                $numeroFactura = 'FAC-' . $fechaEmision->format('Ymd') . '-' . str_pad($facturaCounter, 3, '0', STR_PAD_LEFT);
                
                Factura::create([
                    'cliente_id' => $cliente->id,
                    'numero_factura' => $numeroFactura,
                    'fecha_emision' => $fechaEmision,
                    'concepto' => $conceptoKey,
                    'subtotal' => $subtotal,
                    'total' => $total,
                    'metodo_pago' => $metodoPago,
                    'estado' => $estado,
                    'fecha_vencimiento' => $fechaVencimiento,
                    'notas' => $concepto['notas'],
                ]);
                
                $facturaCounter++;
            }
        }
    }
}
