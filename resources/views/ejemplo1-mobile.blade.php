<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Móvil - WebSolutions</title>
    <meta name="description" content="Información del sistema optimizada para móviles">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Dark mode detection
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <style>
        * {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 h-full transition-colors duration-200">
    <div class="max-w-md mx-auto bg-white dark:bg-gray-800 min-h-screen transition-colors duration-200">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 dark:from-green-700 dark:to-green-800 text-white p-4">
            <div class="flex items-center justify-between mb-2">
                <h1 class="text-xl font-bold">Dashboard</h1>
                <div class="flex items-center gap-2">
                    <button id="darkModeToggle" class="p-1.5 rounded-lg hover:bg-white/10 transition">
                        <svg id="sunIcon" class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <svg id="moonIcon" class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </button>
                    <div class="text-xs text-green-100">{{ now()->format('d/m/Y') }}</div>
                </div>
            </div>
            <p class="text-sm text-green-100">Información del sistema</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 gap-3 p-4">
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-100 dark:border-blue-800">
                <div class="flex items-center justify-between mb-2">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $clientes }}</p>
                <p class="text-xs text-blue-700 dark:text-blue-300 mt-1">Total Clientes</p>
            </div>

            <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 border border-green-100 dark:border-green-800">
                <div class="flex items-center justify-between mb-2">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ $clientesActivos }}</p>
                <p class="text-xs text-green-700 dark:text-green-300 mt-1">Clientes Activos</p>
            </div>

            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4 border border-purple-100 dark:border-purple-800">
                <div class="flex items-center justify-between mb-2">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-purple-900 dark:text-purple-100">{{ $citasHoy }}</p>
                <p class="text-xs text-purple-700 dark:text-purple-300 mt-1">Citas Hoy</p>
            </div>

            <div class="bg-orange-50 dark:bg-orange-900/20 rounded-lg p-4 border border-orange-100 dark:border-orange-800">
                <div class="flex items-center justify-between mb-2">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-orange-900 dark:text-orange-100">{{ $usuarios }}</p>
                <p class="text-xs text-orange-700 dark:text-orange-300 mt-1">Usuarios</p>
            </div>
        </div>

        <!-- Próximas Citas -->
        <div class="px-4 mb-4">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Próximas Citas</h2>
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $citasTotal }} total</span>
            </div>
            <div class="space-y-2">
                @forelse($citasProximas as $cita)
                    <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-3">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">{{ $cita->titulo }}</h3>
                                <div class="flex items-center text-xs text-gray-600 dark:text-gray-300 mb-1">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $cita->fecha_inicio->format('d/m/Y H:i') }}
                                </div>
                                @php
                                    $clienteNombre = null;
                                    if ($cita->cliente_id && $cita->relationLoaded('cliente') && $cita->cliente) {
                                        $clienteNombre = $cita->cliente->nombre_empresa;
                                    } elseif ($cita->cliente_id) {
                                        $clienteObj = $cita->cliente()->first();
                                        if ($clienteObj) {
                                            $clienteNombre = $clienteObj->nombre_empresa;
                                        }
                                    } elseif ($cita->cliente && is_string($cita->cliente)) {
                                        $clienteNombre = $cita->cliente;
                                    }
                                @endphp
                                @if($clienteNombre)
                                    <div class="flex items-center text-xs text-gray-600 dark:text-gray-300">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        {{ $clienteNombre }}
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
                        @if($cita->ubicacion)
                            <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 mt-2">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $cita->ubicacion }}
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-300">No hay citas próximas</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recursos y Páginas -->
        @if(isset($groupedItems) && count($groupedItems) > 0)
            <div class="px-4 mb-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Recursos y Páginas</h2>
                <div class="space-y-4">
                    @foreach($groupedItems as $group => $items)
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 px-2">{{ $group }}</h3>
                            <div class="space-y-2">
                                @foreach($items as $item)
                                    <a href="{{ $item['url'] }}" class="block bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-3 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center flex-1">
                                                @if($item['icon'])
                                                    <div class="w-8 h-8 flex items-center justify-center mr-3 text-gray-600 dark:text-gray-300">
                                                        @if(str_contains($item['icon'], 'heroicon'))
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                            </svg>
                                                        @else
                                                            <span class="text-lg">{{ $item['icon'] }}</span>
                                                        @endif
                                                    </div>
                                                @endif
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $item['name'] }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Ver lista y funciones</p>
                                                </div>
                                            </div>
                                            @if(isset($item['badge']) && $item['badge'])
                                                <span class="ml-2 px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                                    {{ $item['badge'] }}
                                                </span>
                                            @endif
                                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Accesos Rápidos -->
        <div class="px-4 mb-4">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Accesos Rápidos</h2>
            <div class="grid grid-cols-2 gap-3">
                <a href="/ejemplo1/lista/clientes" class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 text-center hover:bg-blue-100 dark:hover:bg-blue-900/30 transition">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p class="text-xs font-semibold text-blue-900 dark:text-blue-100">Clientes</p>
                </a>

                <a href="/ejemplo1/lista/citas" class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4 text-center hover:bg-purple-100 dark:hover:bg-purple-900/30 transition">
                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-xs font-semibold text-purple-900 dark:text-purple-100">Citas</p>
                </a>

                <a href="/ejemplo1/lista/usuarios" class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 text-center hover:bg-green-100 dark:hover:bg-green-900/30 transition">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <p class="text-xs font-semibold text-green-900 dark:text-green-100">Usuarios</p>
                </a>

                <a href="/ejemplo1" class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-4 text-center hover:bg-orange-100 dark:hover:bg-orange-900/30 transition">
                    <svg class="w-8 h-8 text-orange-600 dark:text-orange-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <p class="text-xs font-semibold text-orange-900 dark:text-orange-100">Dashboard</p>
                </a>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="px-4 pb-6">
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Resumen</h3>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Total Citas:</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $citasTotal }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Citas Hoy:</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $citasHoy }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-300">Clientes Activos:</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $clientesActivos }} / {{ $clientes }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Dark mode toggle
        document.getElementById('darkModeToggle').addEventListener('click', function() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
        });
    </script>
</body>
</html>
