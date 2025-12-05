<x-filament-panels::page>
    <div class="space-y-6">
        @if($isLoading)
            <div class="flex items-center justify-center py-12">
                <div class="text-center">
                    <svg class="animate-spin h-8 w-8 text-primary-600 mx-auto mb-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Cargando workflows...</p>
                </div>
            </div>
        @elseif($error)
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6">
                <div class="flex items-center gap-3 text-danger-600 dark:text-danger-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="font-semibold">Error al cargar workflows</p>
                        <p class="text-sm">{{ $error }}</p>
                    </div>
                </div>
            </div>
        @elseif(empty($workflows))
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-12 text-center">
                <svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No hay workflows disponibles</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">No se encontraron workflows en n8n o hay un problema de conexión.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($workflows as $workflow)
                    @php
                        $workflowId = $workflow['id'] ?? null;
                        $workflowName = $workflow['name'] ?? 'Sin nombre';
                        $isActive = $workflow['active'] ?? false;
                        $nodesCount = count($workflow['nodes'] ?? []);
                        $hasBot = $workflowId ? \App\Models\N8nBot::where('workflow_id', $workflowId)->exists() : false;
                    @endphp
                    <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                    {{ $workflowName }}
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-mono">
                                    ID: {{ Str::limit($workflowId, 20) }}
                                </p>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                @if($isActive) bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                @else bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200
                                @endif">
                                {{ $isActive ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>

                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                {{ $nodesCount }} nodos
                            </div>
                            @if($hasBot)
                                <div class="flex items-center text-sm text-blue-600 dark:text-blue-400">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Sincronizado como bot
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <x-filament::button
                                wire:click="syncWorkflowToBots('{{ $workflowId }}')"
                                size="sm"
                                color="{{ $hasBot ? 'info' : 'success' }}"
                                icon="heroicon-o-arrow-down-tray">
                                {{ $hasBot ? 'Actualizar' : 'Sincronizar' }}
                            </x-filament::button>
                            <x-filament::button
                                tag="a"
                                href="https://n8n.srv1137974.hstgr.cloud/workflow/{{ $workflowId }}"
                                target="_blank"
                                size="sm"
                                color="gray"
                                icon="heroicon-o-arrow-top-right-on-square">
                                Abrir
                            </x-filament::button>
                            @if($hasBot)
                                @php
                                    $bot = \App\Models\N8nBot::where('workflow_id', $workflowId)->first();
                                @endphp
                                @if($bot)
                                    <x-filament::button
                                        tag="a"
                                        href="{{ \App\Filament\Resources\N8nBotResource::getUrl('edit', ['record' => $bot->id]) }}"
                                        size="sm"
                                        color="primary"
                                        icon="heroicon-o-cpu-chip">
                                        Ver Bot
                                    </x-filament::button>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-filament-panels::page>

