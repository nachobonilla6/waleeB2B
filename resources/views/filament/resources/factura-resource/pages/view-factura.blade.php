<x-filament-panels::page>
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-900 shadow-lg rounded-lg overflow-hidden">
        <!-- Header de la factura -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold mb-2">FACTURA</h1>
                    <p class="text-blue-100">Web Solutions</p>
                    <p class="text-blue-100 text-sm mt-2">Servicios Digitales</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold">{{ $record->numero_factura }}</p>
                    <p class="text-blue-100 text-sm mt-1">Nº Factura</p>
                </div>
            </div>
        </div>

        <!-- Información de la empresa y cliente -->
        <div class="p-8 grid grid-cols-2 gap-8 border-b border-gray-200 dark:border-gray-700">
            <div>
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-3">De:</h3>
                <div class="text-gray-900 dark:text-white">
                    <p class="font-bold text-lg">Web Solutions</p>
                    <p class="text-sm mt-1">Servicios Digitales</p>
                    <p class="text-sm mt-1">Costa Rica</p>
                    <p class="text-sm mt-1">info@websolutions.work</p>
                </div>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-3">Para:</h3>
                <div class="text-gray-900 dark:text-white">
                    <p class="font-bold text-lg">{{ $record->cliente->nombre_empresa ?? 'N/A' }}</p>
                    @if($record->cliente)
                        @if($record->cliente->correo)
                            <p class="text-sm mt-1">{{ $record->cliente->correo }}</p>
                        @endif
                        @if($record->cliente->telefono)
                            <p class="text-sm mt-1">{{ $record->cliente->telefono }}</p>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Detalles de la factura -->
        <div class="p-8">
            <div class="grid grid-cols-3 gap-4 mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Fecha de Emisión</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ $record->fecha_emision?->format('d/m/Y') ?? 'N/A' }}
                    </p>
                </div>
                @if($record->fecha_vencimiento)
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Fecha de Vencimiento</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ $record->fecha_vencimiento->format('d/m/Y') }}
                    </p>
                </div>
                @endif
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Estado</p>
                    <p class="text-lg font-semibold">
                        @if($record->estado === 'pagada')
                            <span class="text-green-600 dark:text-green-400">🟢 Pagada</span>
                        @elseif($record->estado === 'pendiente')
                            <span class="text-yellow-600 dark:text-yellow-400">🟡 Pendiente</span>
                        @elseif($record->estado === 'vencida')
                            <span class="text-red-600 dark:text-red-400">🔴 Vencida</span>
                        @else
                            <span class="text-gray-600 dark:text-gray-400">⚫ {{ ucfirst($record->estado) }}</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Concepto/Servicio -->
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-3">Concepto</h3>
                <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                        @php
                            $conceptos = [
                                'diseno_web' => '🌐 Diseño Web',
                                'redes_sociales' => '📱 Gestión Redes Sociales',
                                'seo' => '🔍 SEO / Posicionamiento',
                                'publicidad' => '📢 Publicidad Digital',
                                'mantenimiento' => '🔧 Mantenimiento Mensual',
                                'hosting' => '☁️ Hosting & Dominio',
                            ];
                        @endphp
                        {{ $conceptos[$record->concepto] ?? $record->concepto }}
                    </p>
                </div>
            </div>

            <!-- Tabla de precios -->
            <div class="mb-6">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-300 dark:border-gray-600">
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Descripción</th>
                            <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Subtotal</th>
                            <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">IVA (13%)</th>
                            <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <td class="py-4 px-4 text-gray-900 dark:text-white">
                                @php
                                    $conceptos = [
                                        'diseno_web' => 'Diseño Web',
                                        'redes_sociales' => 'Gestión Redes Sociales',
                                        'seo' => 'SEO / Posicionamiento',
                                        'publicidad' => 'Publicidad Digital',
                                        'mantenimiento' => 'Mantenimiento Mensual',
                                        'hosting' => 'Hosting & Dominio',
                                    ];
                                @endphp
                                {{ $conceptos[$record->concepto] ?? $record->concepto }}
                            </td>
                            <td class="py-4 px-4 text-right text-gray-900 dark:text-white">
                                ${{ number_format($record->subtotal, 2) }}
                            </td>
                            <td class="py-4 px-4 text-right text-gray-900 dark:text-white">
                                ${{ number_format($record->total - $record->subtotal, 2) }}
                            </td>
                            <td class="py-4 px-4 text-right font-bold text-gray-900 dark:text-white">
                                ${{ number_format($record->total, 2) }}
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="py-4 px-4 text-right text-lg font-semibold text-gray-700 dark:text-gray-300">
                                TOTAL:
                            </td>
                            <td class="py-4 px-4 text-right text-2xl font-bold text-blue-600 dark:text-blue-400">
                                ${{ number_format($record->total, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Información de pago -->
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                    <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">Método de Pago</h4>
                    <p class="text-gray-900 dark:text-white font-semibold">
                        @php
                            $metodos = [
                                'transferencia' => '🏦 Transferencia Bancaria',
                                'sinpe' => '📲 SINPE Móvil',
                                'tarjeta' => '💳 Tarjeta de Crédito',
                                'efectivo' => '💵 Efectivo',
                                'paypal' => '🅿️ PayPal',
                            ];
                        @endphp
                        {{ $metodos[$record->metodo_pago] ?? $record->metodo_pago }}
                    </p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                    <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">Moneda</h4>
                    <p class="text-gray-900 dark:text-white font-semibold">USD (Dólares)</p>
                </div>
            </div>

            <!-- Notas -->
            @if($record->notas)
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400 p-4 rounded">
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase mb-2">Notas</h4>
                <p class="text-gray-700 dark:text-gray-300">{{ $record->notas }}</p>
            </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 dark:bg-gray-800 p-6 border-t border-gray-200 dark:border-gray-700">
            <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                Gracias por su confianza en nuestros servicios
            </p>
            <p class="text-center text-xs text-gray-400 dark:text-gray-500 mt-2">
                Web Solutions - Servicios Digitales
            </p>
        </div>
    </div>
</x-filament-panels::page>

