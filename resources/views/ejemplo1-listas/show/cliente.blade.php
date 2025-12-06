<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $cliente->nombre_empresa }} - WebSolutions</title>
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
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('ejemplo1.lista.clientes') }}" class="p-1.5 rounded-lg hover:bg-white/10 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-xl font-bold">{{ $cliente->nombre_empresa }}</h1>
            </div>
        </div>

        <!-- Detalles -->
        <div class="p-4 space-y-4">
            <!-- Estado -->
            <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Estado:</span>
                    <span class="px-3 py-1 text-xs font-medium rounded-full
                        @if($cliente->estado_cuenta === 'activo') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                        @elseif($cliente->estado_cuenta === 'pendiente') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                        @elseif($cliente->estado_cuenta === 'suspendido') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                        @else bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200
                        @endif">
                        {{ ucfirst($cliente->estado_cuenta ?? 'N/A') }}
                    </span>
                </div>
            </div>

            <!-- Información de Contacto -->
            <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Contacto</h2>
                <div class="space-y-3">
                    @if($cliente->correo)
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Correo</p>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $cliente->correo }}</p>
                            </div>
                        </div>
                    @endif
                    @if($cliente->telefono)
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Teléfono</p>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $cliente->telefono }}</p>
                            </div>
                        </div>
                    @endif
                    @if($cliente->telefono_alternativo)
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Teléfono Alternativo</p>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $cliente->telefono_alternativo }}</p>
                            </div>
                        </div>
                    @endif
                    @if($cliente->whatsapp)
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 dark:text-gray-400">WhatsApp</p>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $cliente->whatsapp }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Ubicación -->
            @if($cliente->ciudad || $cliente->direccion)
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Ubicación</h2>
                    <div class="space-y-2">
                        @if($cliente->direccion)
                            <p class="text-sm text-gray-900 dark:text-white">{{ $cliente->direccion }}</p>
                        @endif
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            @if($cliente->ciudad){{ $cliente->ciudad }}, @endif
                            @if($cliente->estado){{ $cliente->estado }}, @endif
                            @if($cliente->pais){{ $cliente->pais }}@endif
                        </p>
                        @if($cliente->codigo_postal)
                            <p class="text-sm text-gray-600 dark:text-gray-300">CP: {{ $cliente->codigo_postal }}</p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Sitio Web -->
            @if($cliente->nombre_sitio || $cliente->url_sitio)
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Sitio Web</h2>
                    <div class="space-y-2">
                        @if($cliente->nombre_sitio)
                            <p class="text-sm text-gray-900 dark:text-white">{{ $cliente->nombre_sitio }}</p>
                        @endif
                        @if($cliente->url_sitio)
                            <a href="{{ $cliente->url_sitio }}" target="_blank" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                {{ $cliente->url_sitio }}
                            </a>
                        @endif
                        @if($cliente->hosting)
                            <p class="text-sm text-gray-600 dark:text-gray-300">Hosting: {{ $cliente->hosting }}</p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Notas -->
            @if($cliente->notas)
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Notas</h2>
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $cliente->notas }}</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>


