<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $sitio->nombre }} - Sitio - WebSolutions</title>
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
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('ejemplo1.lista.sitios') }}" class="p-1.5 rounded-lg hover:bg-white/10 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-xl font-bold">{{ $sitio->nombre }}</h1>
            </div>
        </div>

        <!-- Detalles -->
        <div class="p-4 space-y-4">
            <!-- Estado -->
            <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Estado:</span>
                    <span class="px-3 py-1 text-xs font-medium rounded-full
                        @if($sitio->en_linea) bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                        @else bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200
                        @endif">
                        {{ $sitio->en_linea ? 'En línea' : 'Offline' }}
                    </span>
                </div>
            </div>

            <!-- Descripción -->
            @if($sitio->descripcion)
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Descripción</h2>
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $sitio->descripcion }}</p>
                </div>
            @endif

            <!-- Tags -->
            @if($sitio->tags && $sitio->tags->count() > 0)
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Etiquetas</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($sitio->tags as $tag)
                            <span class="px-3 py-1 text-xs bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 rounded-full">
                                {{ $tag->nombre ?? $tag->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Enlace -->
            @if($sitio->enlace)
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Enlace</h2>
                    <a href="{{ $sitio->enlace }}" target="_blank" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                        {{ $sitio->enlace }}
                    </a>
                </div>
            @endif

            <!-- Video -->
            @if($sitio->video_url)
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Video</h2>
                    <a href="{{ $sitio->video_url }}" target="_blank" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                        Ver video
                    </a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>

