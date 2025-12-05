<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura #{{ $factura->numero_factura ?? $factura->id }} - WebSolutions</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 h-full transition-colors duration-200">
    <div class="max-w-md mx-auto bg-white dark:bg-gray-800 min-h-screen transition-colors duration-200">
        <!-- Header -->
        <div class="bg-gradient-to-r from-yellow-600 to-yellow-700 dark:from-yellow-700 dark:to-yellow-800 text-white p-4 sticky top-0 z-10">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('ejemplo1.lista.facturas') }}" class="p-1.5 rounded-lg hover:bg-white/10 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-xl font-bold">Factura #{{ $factura->numero_factura ?? $factura->id }}</h1>
            </div>
        </div>

        <!-- Detalles -->
        <div class="p-4 space-y-4">
            <!-- Estado -->
            <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Estado:</span>
                    <span class="px-3 py-1 text-xs font-medium rounded-full
                        @if($factura->estado === 'pagada') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                        @elseif($factura->estado === 'pendiente') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                        @elseif($factura->estado === 'vencida') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                        @else bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200
                        @endif">
                        {{ ucfirst($factura->estado ?? 'N/A') }}
                    </span>
                </div>
            </div>

            <!-- Cliente -->
            @if($factura->cliente)
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Cliente</h2>
                    <p class="text-sm text-gray-900 dark:text-white">{{ $factura->cliente->nombre_empresa }}</p>
                </div>
            @endif

            <!-- Montos -->
            <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Montos</h2>
                <div class="space-y-2">
                    @if($factura->subtotal)
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-300">Subtotal:</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">${{ number_format($factura->subtotal, 2) }}</span>
                        </div>
                    @endif
                    @if($factura->total)
                        <div class="flex justify-between pt-2 border-t border-gray-200 dark:border-gray-600">
                            <span class="text-base font-semibold text-gray-900 dark:text-white">Total:</span>
                            <span class="text-base font-bold text-gray-900 dark:text-white">${{ number_format($factura->total, 2) }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Fechas -->
            <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Fechas</h2>
                <div class="space-y-3">
                    @if($factura->fecha_emision)
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Emisión</p>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $factura->fecha_emision->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    @endif
                    @if($factura->fecha_vencimiento)
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Vencimiento</p>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $factura->fecha_vencimiento->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Concepto -->
            @if($factura->concepto)
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Concepto</h2>
                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $factura->concepto }}</p>
                </div>
            @endif

            <!-- Método de Pago -->
            @if($factura->metodo_pago)
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Método de Pago</h2>
                    <p class="text-sm text-gray-900 dark:text-white">{{ $factura->metodo_pago }}</p>
                </div>
            @endif

            <!-- Notas -->
            @if($factura->notas)
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Notas</h2>
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $factura->notas }}</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>

