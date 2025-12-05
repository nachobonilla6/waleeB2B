<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $propuesta->name }} - Propuesta - WebSolutions</title>
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
        <div class="bg-gradient-to-r from-green-600 to-green-700 dark:from-green-700 dark:to-green-800 text-white p-4 sticky top-0 z-10">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('ejemplo1.lista.propuestas-enviadas') }}" class="p-1.5 rounded-lg hover:bg-white/10 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-xl font-bold">{{ $propuesta->name }}</h1>
            </div>
        </div>

        <!-- Detalles -->
        <div class="p-4 space-y-4">
            <!-- Estado -->
            <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Estado:</span>
                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                        Propuesta Enviada
                    </span>
                </div>
            </div>

            <!-- Información de Contacto -->
            <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Contacto</h2>
                <div class="space-y-3">
                    @if($propuesta->email)
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Correo</p>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $propuesta->email }}</p>
                            </div>
                        </div>
                    @endif
                    @if($propuesta->telefono_1)
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Teléfono 1</p>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $propuesta->telefono_1 }}</p>
                            </div>
                        </div>
                    @endif
                    @if($propuesta->telefono_2)
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Teléfono 2</p>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $propuesta->telefono_2 }}</p>
                            </div>
                        </div>
                    @endif
                    @if($propuesta->address)
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Dirección</p>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $propuesta->address }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sitio Web -->
            @if($propuesta->website)
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Sitio Web</h2>
                    <a href="{{ str_starts_with($propuesta->website, 'http') ? $propuesta->website : 'https://' . $propuesta->website }}" 
                       target="_blank" 
                       class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                        {{ $propuesta->website }}
                    </a>
                </div>
            @endif

            <!-- Sitio Propuesto -->
            @if($propuesta->proposed_site)
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Sitio Propuesto</h2>
                    <a href="{{ str_starts_with($propuesta->proposed_site, 'http') ? $propuesta->proposed_site : 'https://' . $propuesta->proposed_site }}" 
                       target="_blank" 
                       class="text-sm text-green-600 dark:text-green-400 hover:underline">
                        {{ $propuesta->proposed_site }}
                    </a>
                </div>
            @endif

            <!-- Propuesta -->
            @if($propuesta->propuesta)
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Propuesta</h2>
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $propuesta->propuesta }}</p>
                </div>
            @endif

            <!-- Feedback -->
            @if($propuesta->feedback)
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Feedback</h2>
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $propuesta->feedback }}</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>

