<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista Previa - Factura</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { margin: 0; padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body class="bg-white p-8">
    @php
        $cliente = \App\Models\Cliente::find($data['cliente_id'] ?? null);
        $items = json_decode($data['items_json'] ?? '[]', true);
        $subtotal = $data['subtotal'] ?? 0;
        $iva = $data['iva'] ?? 0;
        $total = $data['total'] ?? 0;
    @endphp
    
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900">Web Solutions</h1>
                    <p class="text-slate-600">Factura</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-slate-600">Número de Factura</p>
                    <p class="text-xl font-bold text-slate-900">{{ $data['numero_factura'] ?? 'N/A' }}</p>
                    <p class="text-sm text-slate-600 mt-2">Fecha: {{ isset($data['fecha_emision']) ? \Carbon\Carbon::parse($data['fecha_emision'])->format('d/m/Y') : date('d/m/Y') }}</p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-8">
                <div>
                    <h3 class="font-semibold text-slate-900 mb-2">De:</h3>
                    <p class="text-slate-700">Web Solutions</p>
                    <p class="text-slate-700">Costa Rica</p>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900 mb-2">Para:</h3>
                    @if($cliente)
                        <p class="text-slate-700">{{ $cliente->nombre_empresa }}</p>
                        @if($cliente->correo)
                            <p class="text-slate-700">{{ $cliente->correo }}</p>
                        @endif
                    @else
                        <p class="text-slate-700">{{ $data['correo'] ?? 'N/A' }}</p>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Items Table -->
        <div class="mb-8">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-slate-100">
                        <th class="border border-slate-300 px-4 py-3 text-left text-sm font-semibold text-slate-900">Descripción</th>
                        <th class="border border-slate-300 px-4 py-3 text-center text-sm font-semibold text-slate-900">Cantidad</th>
                        <th class="border border-slate-300 px-4 py-3 text-right text-sm font-semibold text-slate-900">Precio Unit.</th>
                        <th class="border border-slate-300 px-4 py-3 text-right text-sm font-semibold text-slate-900">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td class="border border-slate-300 px-4 py-3 text-slate-700">{{ $item['descripcion'] ?? '' }}</td>
                            <td class="border border-slate-300 px-4 py-3 text-center text-slate-700">{{ $item['cantidad'] ?? 1 }}</td>
                            <td class="border border-slate-300 px-4 py-3 text-right text-slate-700">₡{{ number_format($item['precio_unitario'] ?? 0, 2) }}</td>
                            <td class="border border-slate-300 px-4 py-3 text-right text-slate-700 font-semibold">₡{{ number_format($item['subtotal'] ?? 0, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Totals -->
        <div class="flex justify-end mb-8">
            <div class="w-64">
                <div class="flex justify-between py-2 border-b border-slate-300">
                    <span class="text-slate-700">Subtotal:</span>
                    <span class="text-slate-900 font-semibold">₡{{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-300">
                    <span class="text-slate-700">IVA (13%):</span>
                    <span class="text-slate-900 font-semibold">₡{{ number_format($iva, 2) }}</span>
                </div>
                <div class="flex justify-between py-3 bg-slate-100 px-4 rounded">
                    <span class="text-slate-900 font-bold text-lg">Total:</span>
                    <span class="text-slate-900 font-bold text-lg">₡{{ number_format($total, 2) }}</span>
                </div>
            </div>
        </div>
        
        @if(!empty($data['notas']))
        <div class="mb-8">
            <h3 class="font-semibold text-slate-900 mb-2">Notas:</h3>
            <p class="text-slate-700">{{ $data['notas'] }}</p>
        </div>
        @endif
        
        <div class="text-center text-sm text-slate-500 mt-12">
            <p>Gracias por su negocio</p>
        </div>
    </div>
</body>
</html>

