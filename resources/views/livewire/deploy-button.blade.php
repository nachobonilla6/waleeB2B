<div class="relative flex items-center">
    {{-- Botón de Deploy --}}
    <button 
        wire:click="confirmDeploy"
        wire:loading.attr="disabled"
        class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-500 hover:from-emerald-600 hover:to-green-600 text-white text-sm font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 disabled:opacity-50"
        {{ $isDeploying ? 'disabled' : '' }}
    >
        @if($isDeploying)
            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Deploying...</span>
        @else
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
            </svg>
            <span>🚀 Deploy</span>
        @endif
    </button>

    {{-- Modal de confirmación --}}
    @if($showConfirm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" wire:click.self="cancelDeploy">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6 max-w-md mx-4 transform transition-all">
                <div class="text-center">
                    <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">🚀 Deploy a Producción</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">¿Estás seguro de que quieres hacer deploy? Esto ejecutará <code class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-sm">git pull</code> en el servidor.</p>
                    
                    <div class="flex gap-3 justify-center">
                        <button 
                            wire:click="cancelDeploy"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors font-medium"
                        >
                            Cancelar
                        </button>
                        <button 
                            wire:click="deploy"
                            class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-500 text-white rounded-lg hover:from-emerald-600 hover:to-green-600 transition-all font-medium shadow-lg"
                        >
                            Sí, hacer deploy
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

