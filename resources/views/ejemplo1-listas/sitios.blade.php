<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sitios - WebSolutions</title>
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
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 dark:from-indigo-700 dark:to-indigo-800 text-white p-4 sticky top-0 z-10">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-3">
                    <a href="/ejemplo1" class="p-1.5 rounded-lg hover:bg-white/10 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <h1 class="text-xl font-bold">Sitios</h1>
                </div>
                <span class="text-xs text-indigo-100">{{ $sitios->total() }} total</span>
            </div>
        </div>

        <!-- Lista -->
        <div class="p-4 space-y-3">
            @forelse($sitios as $sitio)
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <a href="{{ route('ejemplo1.show.sitio', $sitio->id) }}" class="block">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">
                                    {{ $sitio->nombre }}
                                </h3>
                                @if($sitio->descripcion)
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-2 line-clamp-2">
                                        {{ Str::limit($sitio->descripcion, 100) }}
                                    </p>
                                @endif
                                @if($sitio->tags && $sitio->tags->count() > 0)
                                    <div class="flex flex-wrap gap-1 mb-2">
                                        @foreach($sitio->tags->take(3) as $tag)
                                            <span class="px-2 py-0.5 text-xs bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 rounded-full">
                                                {{ $tag->nombre ?? $tag->name }}
                                            </span>
                                        @endforeach
                                        @if($sitio->tags->count() > 3)
                                            <span class="px-2 py-0.5 text-xs text-gray-500 dark:text-gray-400">
                                                +{{ $sitio->tags->count() - 3 }} más
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                @if($sitio->en_linea) bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                @else bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200
                                @endif">
                                {{ $sitio->en_linea ? 'En línea' : 'Offline' }}
                            </span>
                        </div>
                        <div class="mt-2 pt-2 border-t border-gray-200 dark:border-gray-600">
                            <span class="text-xs text-indigo-600 dark:text-indigo-400 font-medium">Ver detalles →</span>
                        </div>
                    </a>
                </div>
            @empty
                <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-8 text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-300">No hay sitios registrados</p>
                </div>
            @endforelse
        </div>

        <!-- Paginación -->
        @if($sitios->hasPages())
            <div class="p-4">
                <div class="flex justify-between items-center">
                    @if($sitios->onFirstPage())
                        <span class="px-4 py-2 text-sm text-gray-400 dark:text-gray-500">Anterior</span>
                    @else
                        <a href="{{ $sitios->previousPageUrl() }}" class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">Anterior</a>
                    @endif
                    
                    <span class="text-sm text-gray-600 dark:text-gray-300">
                        Página {{ $sitios->currentPage() }} de {{ $sitios->lastPage() }}
                    </span>
                    
                    @if($sitios->hasMorePages())
                        <a href="{{ $sitios->nextPageUrl() }}" class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">Siguiente</a>
                    @else
                        <span class="px-4 py-2 text-sm text-gray-400 dark:text-gray-500">Siguiente</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</body>
</html>

