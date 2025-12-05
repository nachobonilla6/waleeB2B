<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $case->title }} - Caso - WebSolutions</title>
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
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('ejemplo1.lista.support-cases') }}" class="p-1.5 rounded-lg hover:bg-white/10 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-xl font-bold">{{ $case->title }}</h1>
            </div>
        </div>

        <!-- Detalles -->
        <div class="p-4 space-y-4">
            <!-- Estado -->
            <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Estado:</span>
                    <span class="px-3 py-1 text-xs font-medium rounded-full
                        @if($case->status === 'resolved') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                        @elseif($case->status === 'open') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                        @else bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200
                        @endif">
                        {{ ucfirst($case->status ?? 'N/A') }}
                    </span>
                </div>
            </div>

            <!-- Información de Contacto -->
            <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Contacto</h2>
                <div class="space-y-3">
                    @if($case->name)
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Nombre</p>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $case->name }}</p>
                            </div>
                        </div>
                    @endif
                    @if($case->email)
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Correo</p>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $case->email }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Descripción -->
            @if($case->description)
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Descripción</h2>
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $case->description }}</p>
                </div>
            @endif

            <!-- Imagen -->
            @if($case->image)
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Imagen</h2>
                    <img src="{{ asset('storage/' . $case->image) }}" alt="Imagen del caso" class="w-full rounded-lg">
                </div>
            @endif

            <!-- Fechas -->
            <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Fechas</h2>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <div class="flex-1">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Creado</p>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $case->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @if($case->resolved_at)
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Resuelto</p>
                                <p class="text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($case->resolved_at)->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>

