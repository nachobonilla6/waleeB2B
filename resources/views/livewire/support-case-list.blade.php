<div class="container mx-auto px-0">
    <div class="space-y-6">
        <!-- Header and Tabs -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Tickets de Soporte</h1>
            <p class="mt-1 text-sm text-gray-500 mb-4">Administra y da seguimiento a los tickets de soporte</p>
            
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <button 
                        wire:click="$set('activeTab', 'open')"
                        class="{{ $activeTab === 'open' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                    >
                        Abiertos
                        @if($openCount > 0)
                            <span class="ml-2 bg-gray-100 text-gray-900 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ $openCount }}
                            </span>
                        @endif
                    </button>
                    <button 
                        wire:click="$set('activeTab', 'resolved')"
                        class="{{ $activeTab === 'resolved' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                    >
                        Resueltos
                        @if($resolvedCount > 0)
                            <span class="ml-2 bg-gray-100 text-gray-900 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ $resolvedCount }}
                            </span>
                        @endif
                    </button>
                </nav>
            </div>
        </div>

        <!-- Search -->
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar tickets {{ $activeTab === 'open' ? 'abiertos' : 'resueltos' }}..." 
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                >
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('message'))
            <div class="rounded-md p-4 mb-4 {{ session('message.type') === 'error' ? 'bg-red-50' : 'bg-green-50' }}">
                <div class="flex">
                    <div class="flex-shrink-0">
                        @if(session('message.type') === 'error')
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        @else
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        @endif
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium {{ session('message.type') === 'error' ? 'text-red-800' : 'text-green-800' }}">
                            {{ session('message.text') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Cases Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Solicitante</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Acciones</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($cases as $case)
                            @php
                                $caseData = [
                                    'id' => $case->id,
                                    'title' => $case->title,
                                    'name' => $case->name,
                                    'email' => $case->email,
                                    'status' => $case->status,
                                    'created_at' => $case->created_at ? (is_string($case->created_at) ? \Carbon\Carbon::parse($case->created_at)->format('d/m/Y H:i') : $case->created_at->format('d/m/Y H:i')) : null,
                                    'resolved_at' => $case->resolved_at ? (is_string($case->resolved_at) ? \Carbon\Carbon::parse($case->resolved_at)->format('d/m/Y H:i') : $case->resolved_at->format('d/m/Y H:i')) : null,
                                    'description' => $case->description,
                                    'image' => $case->image ? (str_starts_with($case->image, 'http') ? $case->image : asset('storage/' . $case->image)) : null
                                ];
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div 
                                        class="text-sm font-medium text-gray-900 cursor-pointer hover:text-indigo-600"
                                        onclick="showFullDetails(@js($caseData))"
                                    >
                                        {{ $case->title }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $case->created_at->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-indigo-100 text-indigo-700 font-semibold">
                                            {{ strtoupper(substr($case->name, 0, 1)) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $case->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $case->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span 
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full cursor-pointer transition-colors duration-200 ease-in-out {{ 
                                            $case->status === 'open' 
                                                ? 'bg-red-100 text-red-800 hover:bg-red-200' 
                                                : 'bg-green-100 text-green-800 hover:bg-green-200' 
                                        }}"
                                        wire:click="updateStatus('{{ $case->id }}', '{{ $case->status }}')"
                                        wire:loading.attr="disabled"
                                        title="Haz clic para {{ $case->status === 'open' ? 'marcar como resuelto' : 'reabrir' }}"
                                    >
                                        <span wire:loading.remove wire:target="updateStatus('{{ $case->id }}', '{{ $case->status }}')">
                                            {{ $statuses[$case->status] ?? $case->status }}
                                        </span>
                                        <span wire:loading wire:target="updateStatus('{{ $case->id }}', '{{ $case->status }}')">
                                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-500 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Procesando...
                                        </span>
                                    </span>
                                    @if($case->status === 'resolved' && $case->resolved_at)
                                        <div class="text-xs text-gray-500 mt-1">
                                            Resuelto: {{ \Carbon\Carbon::parse($case->resolved_at)->diffForHumans() }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($case->created_at)->format('d/m/Y') }}
                                    <div class="text-xs text-gray-400">
                                        {{ \Carbon\Carbon::parse($case->created_at)->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button 
                                        onclick="showFullDetails(@js($caseData))"
                                        class="text-indigo-600 hover:text-indigo-900 mr-4"
                                        title="Ver detalles"
                                    >
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No se encontraron tickets</h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ $search || $activeTab === 'resolved'
                                            ? 'Intenta con otros términos de búsqueda o filtros.' 
                                            : 'No hay tickets registrados. Crea uno nuevo para comenzar.' }}
                                    </p>

                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($cases->hasPages())
                <div class="px-6 py-4 bg-white border-t border-gray-200">
                    {{ $cases->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
