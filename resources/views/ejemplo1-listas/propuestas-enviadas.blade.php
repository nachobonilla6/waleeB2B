<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Propuestas Enviadas - WebSolutions</title>
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
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-3">
                    <a href="/ejemplo1" class="p-1.5 rounded-lg hover:bg-white/10 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <h1 class="text-xl font-bold">Propuestas Enviadas</h1>
                </div>
                <span class="text-xs text-green-100">{{ $propuestas->total() }} total</span>
            </div>
        </div>

        <!-- Lista -->
        <div class="p-4 space-y-3">
            @forelse($propuestas as $propuesta)
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <a href="{{ route('ejemplo1.show.propuesta-enviada', $propuesta->id) }}" class="block">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">
                                    {{ $propuesta->name }}
                                </h3>
                                @if($propuesta->email)
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300 mb-1">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $propuesta->email }}
                                    </div>
                                @endif
                                @if($propuesta->website)
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300 mb-1">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                        </svg>
                                        {{ str_replace(['http://', 'https://'], '', $propuesta->website) }}
                                    </div>
                                @endif
                                @if($propuesta->telefono_1 || $propuesta->telefono_2)
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        {{ $propuesta->telefono_1 ?? $propuesta->telefono_2 }}
                                    </div>
                                @endif
                            </div>
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                Enviada
                            </span>
                        </div>
                        <div class="mt-2 pt-2 border-t border-gray-200 dark:border-gray-600">
                            <span class="text-xs text-green-600 dark:text-green-400 font-medium">Ver detalles →</span>
                        </div>
                    </a>
                </div>
            @empty
                <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-8 text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-300">No hay propuestas enviadas</p>
                </div>
            @endforelse
        </div>

        <!-- Paginación -->
        @if($propuestas->hasPages())
            <div class="p-4">
                <div class="flex justify-between items-center">
                    @if($propuestas->onFirstPage())
                        <span class="px-4 py-2 text-sm text-gray-400 dark:text-gray-500">Anterior</span>
                    @else
                        <a href="{{ $propuestas->previousPageUrl() }}" class="px-4 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700 transition">Anterior</a>
                    @endif
                    
                    <span class="text-sm text-gray-600 dark:text-gray-300">
                        Página {{ $propuestas->currentPage() }} de {{ $propuestas->lastPage() }}
                    </span>
                    
                    @if($propuestas->hasMorePages())
                        <a href="{{ $propuestas->nextPageUrl() }}" class="px-4 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700 transition">Siguiente</a>
                    @else
                        <span class="px-4 py-2 text-sm text-gray-400 dark:text-gray-500">Siguiente</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</body>
</html>

