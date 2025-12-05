<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Citas - WebSolutions</title>
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
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 dark:from-purple-700 dark:to-purple-800 text-white p-4 sticky top-0 z-10">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-3">
                    <a href="/ejemplo1" class="p-1.5 rounded-lg hover:bg-white/10 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <h1 class="text-xl font-bold">Citas</h1>
                </div>
                <span class="text-xs text-purple-100">{{ $citas->total() }} total</span>
            </div>
        </div>

        <!-- Lista -->
        <div class="p-4 space-y-3">
            @forelse($citas as $cita)
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex-1">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">
                                {{ $cita->titulo }}
                            </h3>
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-300 mb-1">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $cita->fecha_inicio->format('d/m/Y H:i') }}
                                @if($cita->fecha_fin)
                                    - {{ $cita->fecha_fin->format('H:i') }}
                                @endif
                            </div>
                            @php
                                $clienteNombre = null;
                                if ($cita->cliente_id && $cita->cliente) {
                                    $clienteNombre = $cita->cliente->nombre_empresa;
                                } elseif ($cita->cliente && is_string($cita->cliente)) {
                                    $clienteNombre = $cita->cliente;
                                }
                            @endphp
                            @if($clienteNombre)
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300 mb-1">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    {{ $clienteNombre }}
                                </div>
                            @endif
                            @if($cita->ubicacion)
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $cita->ubicacion }}
                                </div>
                            @endif
                        </div>
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            @if($cita->estado === 'completada') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                            @elseif($cita->estado === 'programada') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                            @else bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200
                            @endif">
                            {{ ucfirst($cita->estado) }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-8 text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-300">No hay citas registradas</p>
                </div>
            @endforelse
        </div>

        <!-- Paginación -->
        @if($citas->hasPages())
            <div class="p-4">
                <div class="flex justify-between items-center">
                    @if($citas->onFirstPage())
                        <span class="px-4 py-2 text-sm text-gray-400 dark:text-gray-500">Anterior</span>
                    @else
                        <a href="{{ $citas->previousPageUrl() }}" class="px-4 py-2 text-sm bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">Anterior</a>
                    @endif
                    
                    <span class="text-sm text-gray-600 dark:text-gray-300">
                        Página {{ $citas->currentPage() }} de {{ $citas->lastPage() }}
                    </span>
                    
                    @if($citas->hasMorePages())
                        <a href="{{ $citas->nextPageUrl() }}" class="px-4 py-2 text-sm bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">Siguiente</a>
                    @else
                        <span class="px-4 py-2 text-sm text-gray-400 dark:text-gray-500">Siguiente</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</body>
</html>

