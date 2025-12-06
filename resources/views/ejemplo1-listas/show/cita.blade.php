<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $cita->titulo }} - Cita - WebSolutions</title>
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
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('ejemplo1.lista.citas') }}" class="p-1.5 rounded-lg hover:bg-white/10 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-xl font-bold">{{ $cita->titulo }}</h1>
            </div>
        </div>

        <!-- Detalles -->
        <div class="p-4 space-y-4">
            <!-- Estado -->
            <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Estado:</span>
                    <span class="px-3 py-1 text-xs font-medium rounded-full
                        @if($cita->estado === 'completada') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                        @elseif($cita->estado === 'programada') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                        @else bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200
                        @endif">
                        {{ ucfirst($cita->estado) }}
                    </span>
                </div>
            </div>

            <!-- Fechas -->
            <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Fechas</h2>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <div class="flex-1">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Inicio</p>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $cita->fecha_inicio->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @if($cita->fecha_fin)
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Fin</p>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $cita->fecha_fin->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Cliente -->
            @php
                $clienteNombre = null;
                if ($cita->cliente_id && $cita->cliente) {
                    $clienteNombre = $cita->cliente->nombre_empresa;
                } elseif ($cita->cliente && is_string($cita->cliente)) {
                    $clienteNombre = $cita->cliente;
                }
            @endphp
            @if($clienteNombre)
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Cliente</h2>
                    <p class="text-sm text-gray-900 dark:text-white">{{ $clienteNombre }}</p>
                </div>
            @endif

            <!-- Ubicación -->
            @if($cita->ubicacion)
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Ubicación</h2>
                    <p class="text-sm text-gray-900 dark:text-white">{{ $cita->ubicacion }}</p>
                </div>
            @endif

            <!-- Descripción -->
            @if($cita->descripcion)
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Descripción</h2>
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $cita->descripcion }}</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>


