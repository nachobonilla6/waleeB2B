<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casos de Soporte - WebSolutions</title>
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
        <div class="bg-gradient-to-r from-red-600 to-red-700 dark:from-red-700 dark:to-red-800 text-white p-4 sticky top-0 z-10">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-3">
                    <a href="/ejemplo1" class="p-1.5 rounded-lg hover:bg-white/10 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <h1 class="text-xl font-bold">Casos de Soporte</h1>
                </div>
                <span class="text-xs text-red-100">{{ $cases->total() }} total</span>
            </div>
        </div>

        <!-- Lista -->
        <div class="p-4 space-y-3">
            @forelse($cases as $case)
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <a href="{{ route('ejemplo1.show.support-case', $case->id) }}" class="block">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">
                                    {{ $case->title }}
                                </h3>
                                @if($case->name)
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300 mb-1">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        {{ $case->name }}
                                    </div>
                                @endif
                                @if($case->email)
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300 mb-1">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $case->email }}
                                    </div>
                                @endif
                                @if($case->description)
                                    <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2">
                                        {{ Str::limit($case->description, 100) }}
                                    </p>
                                @endif
                            </div>
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                @if($case->status === 'resolved') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                @elseif($case->status === 'open') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                @else bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200
                                @endif">
                                {{ ucfirst($case->status ?? 'N/A') }}
                            </span>
                        </div>
                        <div class="mt-2 pt-2 border-t border-gray-200 dark:border-gray-600">
                            <span class="text-xs text-red-600 dark:text-red-400 font-medium">Ver detalles →</span>
                        </div>
                    </a>
                </div>
            @empty
                <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-8 text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-300">No hay casos de soporte</p>
                </div>
            @endforelse
        </div>

        <!-- Paginación -->
        @if($cases->hasPages())
            <div class="p-4">
                <div class="flex justify-between items-center">
                    @if($cases->onFirstPage())
                        <span class="px-4 py-2 text-sm text-gray-400 dark:text-gray-500">Anterior</span>
                    @else
                        <a href="{{ $cases->previousPageUrl() }}" class="px-4 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700 transition">Anterior</a>
                    @endif
                    
                    <span class="text-sm text-gray-600 dark:text-gray-300">
                        Página {{ $cases->currentPage() }} de {{ $cases->lastPage() }}
                    </span>
                    
                    @if($cases->hasMorePages())
                        <a href="{{ $cases->nextPageUrl() }}" class="px-4 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700 transition">Siguiente</a>
                    @else
                        <span class="px-4 py-2 text-sm text-gray-400 dark:text-gray-500">Siguiente</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</body>
</html>


