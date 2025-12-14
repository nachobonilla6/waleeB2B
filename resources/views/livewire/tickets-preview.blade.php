@php
    $tickets = \App\Models\SupportCase::orderBy('created_at', 'desc')->limit(20)->get();
    $openCount = \App\Models\SupportCase::where('status', 'open')->count();
    $inProgressCount = \App\Models\SupportCase::where('status', 'in_progress')->count();
    $resolvedCount = \App\Models\SupportCase::where('status', 'resolved')->count();
    $totalCount = \App\Models\SupportCase::count();
@endphp

<div class="space-y-4">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                Tickets de Soporte
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Vista previa de los últimos tickets del sistema
            </p>
        </div>
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
    
    <div class="grid grid-cols-4 gap-4 mb-4">
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-3">
            <div class="text-sm text-yellow-600 dark:text-yellow-400 font-medium">Abiertos</div>
            <div class="text-2xl font-bold text-yellow-700 dark:text-yellow-300">
                {{ $openCount }}
            </div>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
            <div class="text-sm text-blue-600 dark:text-blue-400 font-medium">En Progreso</div>
            <div class="text-2xl font-bold text-blue-700 dark:text-blue-300">
                {{ $inProgressCount }}
            </div>
        </div>
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-3">
            <div class="text-sm text-green-600 dark:text-green-400 font-medium">Resueltos</div>
            <div class="text-2xl font-bold text-green-700 dark:text-green-300">
                {{ $resolvedCount }}
            </div>
        </div>
        <div class="bg-gray-50 dark:bg-gray-900/20 border border-gray-200 dark:border-gray-800 rounded-lg p-3">
            <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">Total</div>
            <div class="text-2xl font-bold text-gray-700 dark:text-gray-300">
                {{ $totalCount }}
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full divide-y divide-gray-200 dark:divide-white/10">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cliente</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Asunto</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estado</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Creado</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acción</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-white/10">
                @forelse($tickets as $ticket)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            #{{ $ticket->id }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $ticket->name }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                            <div class="max-w-xs truncate" title="{{ $ticket->title }}">
                                {{ $ticket->title }}
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'open' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                    'in_progress' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                    'resolved' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                    'closed' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300',
                                ];
                                $statusLabels = [
                                    'open' => 'Abierto',
                                    'in_progress' => 'En Progreso',
                                    'resolved' => 'Resuelto',
                                    'closed' => 'Cerrado',
                                ];
                                $color = $statusColors[$ticket->status] ?? $statusColors['closed'];
                                $label = $statusLabels[$ticket->status] ?? 'Desconocido';
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $color }}">
                                {{ $label }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $ticket->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                            <a 
                                href="{{ \App\Filament\Resources\SupportCaseResource::getUrl('view', ['record' => $ticket]) }}"
                                class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 font-medium"
                            >
                                Ver
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                            No hay tickets registrados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($totalCount > 20)
        <div class="text-center pt-4">
            <a 
                href="{{ \App\Filament\Resources\SupportCaseResource::getUrl('index') }}"
                class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 font-medium text-sm"
            >
                Ver todos los tickets ({{ $totalCount }})
            </a>
        </div>
    @endif
</div>
