<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Clientes - WebSolutions</title>
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
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-700 dark:to-blue-800 text-white p-4 sticky top-0 z-10">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-3">
                    <a href="/ejemplo1" class="p-1.5 rounded-lg hover:bg-white/10 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <h1 class="text-xl font-bold">Clientes</h1>
                </div>
                <span class="text-xs text-blue-100">{{ $clientes->total() }} total</span>
            </div>
        </div>

        <!-- Lista -->
        <div class="p-4 space-y-3">
            @forelse($clientes as $cliente)
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex-1">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">
                                {{ $cliente->nombre_empresa }}
                            </h3>
                            @if($cliente->correo)
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300 mb-1">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $cliente->correo }}
                                </div>
                            @endif
                            @if($cliente->telefono)
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300 mb-1">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    {{ $cliente->telefono }}
                                </div>
                            @endif
                            @if($cliente->ciudad)
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $cliente->ciudad }}
                                </div>
                            @endif
                        </div>
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            @if($cliente->estado_cuenta === 'activo') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                            @elseif($cliente->estado_cuenta === 'pendiente') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                            @elseif($cliente->estado_cuenta === 'suspendido') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                            @else bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200
                            @endif">
                            {{ ucfirst($cliente->estado_cuenta ?? 'N/A') }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-8 text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-300">No hay clientes registrados</p>
                </div>
            @endforelse
        </div>

        <!-- Paginación -->
        @if($clientes->hasPages())
            <div class="p-4">
                <div class="flex justify-between items-center">
                    @if($clientes->onFirstPage())
                        <span class="px-4 py-2 text-sm text-gray-400 dark:text-gray-500">Anterior</span>
                    @else
                        <a href="{{ $clientes->previousPageUrl() }}" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Anterior</a>
                    @endif
                    
                    <span class="text-sm text-gray-600 dark:text-gray-300">
                        Página {{ $clientes->currentPage() }} de {{ $clientes->lastPage() }}
                    </span>
                    
                    @if($clientes->hasMorePages())
                        <a href="{{ $clientes->nextPageUrl() }}" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Siguiente</a>
                    @else
                        <span class="px-4 py-2 text-sm text-gray-400 dark:text-gray-500">Siguiente</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</body>
</html>

