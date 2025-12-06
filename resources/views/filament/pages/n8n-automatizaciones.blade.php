<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Barra de búsqueda y filtros --}}
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar workflows..."
                    class="fi-input block w-full rounded-lg border-none bg-white px-3 py-1.5 text-base text-gray-950 outline-none transition duration-75 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-white/5 dark:text-white dark:placeholder:text-gray-500 dark:focus:ring-primary-500 sm:text-sm sm:leading-6"
                />
            </div>
            <div class="sm:w-48">
                <select
                    wire:model.live="filterActive"
                    class="fi-input block w-full rounded-lg border-none bg-white px-3 py-1.5 text-base text-gray-950 outline-none transition duration-75 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-white/5 dark:text-white dark:focus:ring-primary-500 sm:text-sm sm:leading-6"
                >
                    <option value="">Todos los estados</option>
                    <option value="true">Activos</option>
                    <option value="false">Inactivos</option>
                </select>
            </div>
        </div>

        {{-- Lista de workflows en filas --}}
        @if($this->getFilteredWorkflows()->isEmpty())
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                </svg>
                <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">No hay workflows</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    No se encontraron workflows en n8n o hay un problema de conexión.
                </p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($this->getFilteredWorkflows() as $workflow)
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $workflow['name'] ?? 'Sin nombre' }}
                                        </h3>
                                        @if($workflow['active'] ?? false)
                                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Activo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                </svg>
                                                Inactivo
                                            </span>
                                        @endif
                                    </div>
                                    
                                    @if(!empty($workflow['description']))
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                            {{ $workflow['description'] }}
                                        </p>
                                    @endif

                                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                            </svg>
                                            <span>{{ count($workflow['nodes'] ?? []) }} nodos</span>
                                        </div>
                                        
                                        @if(isset($workflow['updatedAt']) || isset($workflow['updated_at']))
                                            <div class="flex items-center gap-1.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span>{{ \Carbon\Carbon::parse($workflow['updatedAt'] ?? $workflow['updated_at'] ?? now())->format('d/m/Y H:i') }}</span>
                                            </div>
                                        @endif

                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                            </svg>
                                            <span>ID: {{ $workflow['id'] ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Acciones --}}
                                <div class="flex items-center gap-2 ml-4 flex-wrap">
                                    {{-- Editar --}}
                                    <a
                                        href="https://n8n.srv1137974.hstgr.cloud/workflow/{{ $workflow['id'] ?? '' }}"
                                        target="_blank"
                                        class="fi-btn fi-btn-size-sm fi-btn-color-primary inline-flex items-center justify-center gap-1.5 rounded-lg border border-transparent px-2.5 py-1.5 text-sm font-semibold text-white shadow-sm transition duration-75 bg-primary-600 hover:bg-primary-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-primary-600"
                                        title="Editar workflow en n8n"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Editar
                                    </a>

                                    {{-- Activar/Desactivar --}}
                                    <button
                                        wire:click="toggleWorkflow('{{ $workflow['id'] ?? '' }}', {{ ($workflow['active'] ?? false) ? 'false' : 'true' }})"
                                        wire:confirm="¿Estás seguro de que quieres {{ ($workflow['active'] ?? false) ? 'desactivar' : 'activar' }} este workflow?"
                                        class="fi-btn fi-btn-size-sm inline-flex items-center justify-center gap-1.5 rounded-lg border border-transparent px-2.5 py-1.5 text-sm font-semibold text-white shadow-sm transition duration-75 {{ ($workflow['active'] ?? false) ? 'bg-warning-600 hover:bg-warning-500' : 'bg-success-600 hover:bg-success-500' }} focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-primary-600"
                                        title="{{ ($workflow['active'] ?? false) ? 'Desactivar' : 'Activar' }} workflow"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($workflow['active'] ?? false)
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0011 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            @endif
                                        </svg>
                                        {{ ($workflow['active'] ?? false) ? 'Desactivar' : 'Activar' }}
                                    </button>

                                    {{-- Eliminar --}}
                                    <button
                                        wire:click="deleteWorkflow('{{ $workflow['id'] ?? '' }}')"
                                        wire:confirm="¿Estás seguro de que quieres eliminar este workflow? Esta acción no se puede deshacer."
                                        class="fi-btn fi-btn-size-sm inline-flex items-center justify-center gap-1.5 rounded-lg border border-transparent px-2.5 py-1.5 text-sm font-semibold text-white shadow-sm transition duration-75 bg-danger-600 hover:bg-danger-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-primary-600"
                                        title="Eliminar workflow"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Eliminar
                                    </button>

                                    {{-- Ejecutar --}}
                                    <button
                                        wire:click="executeWorkflow('{{ $workflow['id'] ?? '' }}')"
                                        wire:confirm="¿Deseas ejecutar este workflow ahora?"
                                        class="fi-btn fi-btn-size-sm fi-btn-color-info inline-flex items-center justify-center gap-1.5 rounded-lg border border-transparent px-2.5 py-1.5 text-sm font-semibold text-white shadow-sm transition duration-75 bg-info-600 hover:bg-info-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-primary-600"
                                        title="Ejecutar workflow"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0011 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Ejecutar
                                    </button>

                                    {{-- Ver Nodos --}}
                                    <a
                                        href="https://n8n.srv1137974.hstgr.cloud/workflow/{{ $workflow['id'] ?? '' }}"
                                        target="_blank"
                                        class="fi-btn fi-btn-size-sm inline-flex items-center justify-center gap-1.5 rounded-lg border border-transparent px-2.5 py-1.5 text-sm font-semibold text-gray-700 shadow-sm transition duration-75 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-primary-600"
                                        title="Ver y editar nodos en n8n"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zM14 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1v-3z"/>
                                        </svg>
                                        Nodos
                                    </a>
                                </div>
                            </div>
                            
                            {{-- Información de nodos expandible --}}
                            @if(!empty($workflow['nodes']))
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <details class="group">
                                        <summary class="cursor-pointer text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 flex items-center gap-2">
                                            <svg class="w-4 h-4 transition-transform group-open:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                            Ver nodos ({{ count($workflow['nodes']) }})
                                        </summary>
                                        <div class="mt-3 space-y-2 pl-6">
                                            @foreach($workflow['nodes'] ?? [] as $node)
                                                <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                                                    <div class="flex items-start justify-between">
                                                        <div>
                                                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">
                                                                {{ $node['name'] ?? 'Sin nombre' }}
                                                            </h4>
                                                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                                                Tipo: <span class="font-mono">{{ $node['type'] ?? 'N/A' }}</span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </details>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Contador de resultados --}}
        @if($this->getFilteredWorkflows()->isNotEmpty())
            <div class="text-sm text-gray-500 dark:text-gray-400 text-center">
                Mostrando {{ $this->getFilteredWorkflows()->count() }} de {{ $this->workflows->count() }} workflows
            </div>
        @endif
    </div>
</x-filament-panels::page>
