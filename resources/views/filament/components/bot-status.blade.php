<div class="flex items-center justify-between flex-wrap gap-4 py-2">
    {{-- Estado --}}
    <div class="flex items-center gap-2">
        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
        <span class="text-sm text-gray-600 dark:text-gray-400">Bot <span class="font-medium text-green-600 dark:text-green-400">Activo</span></span>
    </div>

    {{-- n8n --}}
    <div class="flex items-center gap-2">
        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
        </svg>
        <span class="text-sm text-gray-600 dark:text-gray-400">n8n <span class="font-medium text-blue-600 dark:text-blue-400">Conectado</span></span>
    </div>

    {{-- Posts --}}
    <div class="flex items-center gap-2">
        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
        </svg>
        <span class="text-sm text-gray-600 dark:text-gray-400">Hoy: <span class="font-medium text-purple-600 dark:text-purple-400">2/3 posts</span></span>
    </div>

    {{-- Última ejecución --}}
    <div class="flex items-center gap-2">
        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="text-sm text-gray-600 dark:text-gray-400">Última: <span class="font-medium text-amber-600 dark:text-amber-400">hace 2h</span></span>
    </div>

    {{-- Workflows --}}
    <div class="flex items-center gap-2">
        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
        </svg>
        <span class="text-sm text-gray-600 dark:text-gray-400">Workflows: <span class="font-medium">3 activos</span></span>
    </div>
</div>
