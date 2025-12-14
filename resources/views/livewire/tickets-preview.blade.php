<div>
    <div class="mb-4">
        <div class="flex items-center justify-between mb-2">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Tickets de Soporte
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Vista previa de todos los tickets del sistema
                </p>
            </div>
            <div class="flex gap-2">
                <a 
                    href="{{ \App\Filament\Resources\SupportCaseResource::getUrl('index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors text-sm font-medium"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Ver todos
                </a>
            </div>
        </div>
        <div class="grid grid-cols-4 gap-4 mb-4">
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-3">
                <div class="text-sm text-yellow-600 dark:text-yellow-400 font-medium">Abiertos</div>
                <div class="text-2xl font-bold text-yellow-700 dark:text-yellow-300">
                    {{ \App\Models\SupportCase::where('status', 'open')->count() }}
                </div>
            </div>
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
                <div class="text-sm text-blue-600 dark:text-blue-400 font-medium">En Progreso</div>
                <div class="text-2xl font-bold text-blue-700 dark:text-blue-300">
                    {{ \App\Models\SupportCase::where('status', 'in_progress')->count() }}
                </div>
            </div>
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-3">
                <div class="text-sm text-green-600 dark:text-green-400 font-medium">Resueltos</div>
                <div class="text-2xl font-bold text-green-700 dark:text-green-300">
                    {{ \App\Models\SupportCase::where('status', 'resolved')->count() }}
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-900/20 border border-gray-200 dark:border-gray-800 rounded-lg p-3">
                <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">Total</div>
                <div class="text-2xl font-bold text-gray-700 dark:text-gray-300">
                    {{ \App\Models\SupportCase::count() }}
                </div>
            </div>
        </div>
    </div>
    
    <div class="fi-ta-ctn divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">
        {{ $component->table }}
    </div>
</div>
