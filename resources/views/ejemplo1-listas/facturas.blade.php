<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facturas - WebSolutions</title>
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
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-3">
                    <a href="/ejemplo1" class="p-1.5 rounded-lg hover:bg-white/10 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <h1 class="text-xl font-bold">Facturas</h1>
                </div>
                <span class="text-xs text-yellow-100">{{ $facturas->total() }} total</span>
            </div>
        </div>

        <!-- Lista -->
        <div class="p-4 space-y-3">
            @forelse($facturas as $factura)
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <a href="{{ route('ejemplo1.show.factura', $factura->id) }}" class="block">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">
                                    @if($factura->numero_factura)
                                        Factura #{{ $factura->numero_factura }}
                                    @else
                                        Factura #{{ $factura->id }}
                                    @endif
                                </h3>
                                @if($factura->cliente)
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300 mb-1">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        {{ $factura->cliente->nombre_empresa }}
                                    </div>
                                @endif
                                @if($factura->fecha_emision)
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300 mb-1">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $factura->fecha_emision->format('d/m/Y') }}
                                    </div>
                                @endif
                                @if($factura->total)
                                    <div class="flex items-center text-sm font-semibold text-gray-900 dark:text-white">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        ${{ number_format($factura->total, 2) }}
                                    </div>
                                @endif
                            </div>
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                @if($factura->estado === 'pagada') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                @elseif($factura->estado === 'pendiente') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                @elseif($factura->estado === 'vencida') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                                @else bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200
                                @endif">
                                {{ ucfirst($factura->estado ?? 'N/A') }}
                            </span>
                        </div>
                        <div class="mt-2 pt-2 border-t border-gray-200 dark:border-gray-600">
                            <span class="text-xs text-yellow-600 dark:text-yellow-400 font-medium">Ver detalles →</span>
                        </div>
                    </a>
                </div>
            @empty
                <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-8 text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-300">No hay facturas registradas</p>
                </div>
            @endforelse
        </div>

        <!-- Paginación -->
        @if($facturas->hasPages())
            <div class="p-4">
                <div class="flex justify-between items-center">
                    @if($facturas->onFirstPage())
                        <span class="px-4 py-2 text-sm text-gray-400 dark:text-gray-500">Anterior</span>
                    @else
                        <a href="{{ $facturas->previousPageUrl() }}" class="px-4 py-2 text-sm bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">Anterior</a>
                    @endif
                    
                    <span class="text-sm text-gray-600 dark:text-gray-300">
                        Página {{ $facturas->currentPage() }} de {{ $facturas->lastPage() }}
                    </span>
                    
                    @if($facturas->hasMorePages())
                        <a href="{{ $facturas->nextPageUrl() }}" class="px-4 py-2 text-sm bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">Siguiente</a>
                    @else
                        <span class="px-4 py-2 text-sm text-gray-400 dark:text-gray-500">Siguiente</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</body>
</html>


