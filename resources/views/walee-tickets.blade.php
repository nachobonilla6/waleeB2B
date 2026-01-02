<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Tickets de Soporte</title>
    <meta name="description" content="Gestión de Tickets de Soporte">
    <meta name="theme-color" content="#D59F3B">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    @include('partials.walee-dark-mode-init')
    @include('partials.walee-violet-light-mode')
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        walee: {
                            50: '#FBF7EE',
                            100: '#F5ECD6',
                            200: '#EBD9AD',
                            300: '#E0C684',
                            400: '#D59F3B',
                            500: '#C78F2E',
                            600: '#A67524',
                            700: '#7F5A1C',
                            800: '#594013',
                            900: '#33250B',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        * {
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(213, 159, 59, 0.3); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(213, 159, 59, 0.5); }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">
    @php
        // Obtener TODOS los tickets para ticketsData (necesario para el modal de detalles)
        $allTicketsForData = \App\Models\Ticket::with('user')
            ->orderBy('a_discutir', 'asc')
            ->orderBy('urgente', 'desc')
            ->orderBy('prioritario', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Si hay ticketsTodos con paginación, usarlos; si no, obtener todos
        if (isset($ticketsTodos)) {
            $tickets = $ticketsTodos;
            $totalTickets = \App\Models\Ticket::count();
            $enviados = \App\Models\Ticket::where('estado', 'enviado')->count();
            $recibidos = \App\Models\Ticket::where('estado', 'recibido')->count();
            $resueltos = \App\Models\Ticket::where('estado', 'resuelto')->count();
        } else {
            // Obtener todos los tickets ordenados por prioridad: urgente > prioritario > fecha, y a_discutir al final
            $tickets = $allTicketsForData;
            
            $totalTickets = $tickets->count();
            $enviados = $tickets->where('estado', 'enviado')->count();
            $recibidos = $tickets->where('estado', 'recibido')->count();
            $resueltos = $tickets->where('estado', 'resuelto')->count();
        }
        
        // Usar variables paginadas si están disponibles, sino usar todas
        if (!isset($ticketsEnviados)) {
            $ticketsEnviados = $allTicketsForData->where('estado', 'enviado')
                ->sortBy([
                    ['a_discutir', 'asc'],
                    ['urgente', 'desc'],
                    ['prioritario', 'desc'],
                    ['created_at', 'desc']
                ])->values();
        }
        
        if (!isset($ticketsRecibidos)) {
            $ticketsRecibidos = $allTicketsForData->where('estado', 'recibido')
                ->sortBy([
                    ['a_discutir', 'asc'],
                    ['urgente', 'desc'],
                    ['prioritario', 'desc'],
                    ['created_at', 'desc']
                ])->values();
        }
        
        if (!isset($ticketsResueltos)) {
            $ticketsResueltos = $allTicketsForData->where('estado', 'resuelto')
                ->sortBy([
                    ['a_discutir', 'asc'],
                    ['urgente', 'desc'],
                    ['prioritario', 'desc'],
                    ['created_at', 'desc']
                ])->values();
        }
        
        // Helper function to get archivos array
        function getArchivos($ticket) {
            if (empty($ticket->imagen)) {
                return [];
            }
            $decoded = json_decode($ticket->imagen, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
            return [$ticket->imagen];
        }
    @endphp

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-orange-400/20 dark:bg-orange-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-walee-400/10 dark:bg-walee-400/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-orange-400/20 dark:bg-orange-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-4 py-6 sm:px-6 lg:px-8">
            @php $pageTitle = 'Tickets de Soporte'; @endphp
            @include('partials.walee-navbar')
            
            <!-- Notifications -->
            <div id="notifications" class="fixed top-4 right-4 z-50 space-y-2"></div>
            
            <!-- Tabs -->
            <div class="mb-6 animate-fade-in-up" style="animation-delay: 0.15s;">
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-xl p-2 flex flex-wrap gap-2 shadow-sm dark:shadow-none">
                    <a href="{{ route('walee.tickets.tab', ['tab' => 'todos']) }}" id="tab-todos" class="tab-button group flex-1 min-w-[calc(50%-0.5rem)] sm:min-w-0 px-4 sm:px-5 py-3 rounded-lg font-semibold text-sm transition-all duration-200 text-center relative overflow-hidden {{ (isset($activeTab) && $activeTab === 'todos') || (!isset($activeTab)) ? 'bg-gradient-to-r from-walee-500 to-walee-600 text-white shadow-md shadow-walee-500/30' : 'bg-slate-100 dark:bg-slate-700/50 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span>Todos</span>
                            <span class="px-2 py-0.5 rounded-full text-xs {{ (isset($activeTab) && $activeTab === 'todos') || (!isset($activeTab)) ? 'bg-white/20 text-white' : 'bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-slate-300' }}">{{ $totalTickets }}</span>
                        </span>
                    </a>
                    <a href="{{ route('walee.tickets.tab', ['tab' => 'enviados']) }}" id="tab-enviados" class="tab-button group flex-1 min-w-[calc(50%-0.5rem)] sm:min-w-0 px-4 sm:px-5 py-3 rounded-lg font-semibold text-sm transition-all duration-200 text-center relative overflow-hidden {{ (isset($activeTab) && $activeTab === 'enviados') ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-white shadow-md shadow-amber-500/30' : 'bg-slate-100 dark:bg-slate-700/50 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Enviados</span>
                            <span class="px-2 py-0.5 rounded-full text-xs {{ (isset($activeTab) && $activeTab === 'enviados') ? 'bg-white/20 text-white' : 'bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-slate-300' }}">{{ $enviados }}</span>
                        </span>
                    </a>
                    <a href="{{ route('walee.tickets.tab', ['tab' => 'recibidos']) }}" id="tab-recibidos" class="tab-button group flex-1 min-w-[calc(50%-0.5rem)] sm:min-w-0 px-4 sm:px-5 py-3 rounded-lg font-semibold text-sm transition-all duration-200 text-center relative overflow-hidden {{ (isset($activeTab) && $activeTab === 'recibidos') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md shadow-blue-500/30' : 'bg-slate-100 dark:bg-slate-700/50 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <span>Recibidos</span>
                            <span class="px-2 py-0.5 rounded-full text-xs {{ (isset($activeTab) && $activeTab === 'recibidos') ? 'bg-white/20 text-white' : 'bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-slate-300' }}">{{ $recibidos }}</span>
                        </span>
                    </a>
                    <a href="{{ route('walee.tickets.tab', ['tab' => 'resueltos']) }}" id="tab-resueltos" class="tab-button group flex-1 min-w-[calc(50%-0.5rem)] sm:min-w-0 px-4 sm:px-5 py-3 rounded-lg font-semibold text-sm transition-all duration-200 text-center relative overflow-hidden {{ (isset($activeTab) && $activeTab === 'resueltos') ? 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-md shadow-emerald-500/30' : 'bg-slate-100 dark:bg-slate-700/50 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Resueltos</span>
                            <span class="px-2 py-0.5 rounded-full text-xs {{ (isset($activeTab) && $activeTab === 'resueltos') ? 'bg-white/20 text-white' : 'bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-slate-300' }}">{{ $resueltos }}</span>
                        </span>
                    </a>
                </div>
            </div>
            
            <!-- Search Bar -->
            <div class="mb-6 animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="relative">
                    <input 
                        type="text" 
                        id="ticketSearchInput"
                        placeholder="Buscar en tickets (asunto, mensaje, nombre, email...)"
                        class="w-full px-4 py-3 pl-12 bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-400 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all text-sm"
                    >
                    <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <button 
                        id="clearSearchBtn"
                        onclick="clearSearch()"
                        class="absolute right-4 top-1/2 transform -translate-y-1/2 hidden text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 transition-colors"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Tickets List - Todos -->
            <div id="ticketsList-todos" class="tickets-container space-y-2 md:space-y-3 {{ (isset($activeTab) && $activeTab === 'todos') || (!isset($activeTab)) ? '' : 'hidden' }}">
                @php
                    $ticketsToShow = isset($ticketsTodos) ? $ticketsTodos : $tickets;
                @endphp
                @forelse($ticketsToShow as $index => $ticket)
                    <div class="ticket-card bg-white dark:bg-slate-800/50 border @if($ticket->urgente) border-red-500 dark:border-red-500 @elseif($ticket->prioritario) border-yellow-500 dark:border-yellow-500 @elseif($ticket->a_discutir) border-blue-500 dark:border-blue-500 @else border-slate-200 dark:border-slate-700/50 @endif rounded-lg overflow-hidden hover:border-orange-400 dark:hover:border-orange-500/30 transition-all animate-fade-in-up shadow-sm dark:shadow-none" style="animation-delay: {{ 0.15 + ($index * 0.05) }}s;" data-id="{{ $ticket->id }}">
                        <!-- Línea 1: Info del ticket -->
                        <div class="px-3 py-2 flex items-center justify-between gap-2" onclick="event.stopPropagation(); showTicketDetail({{ $ticket->id }})">
                            <div class="flex-1 min-w-0 flex items-center gap-2">
                                <span class="text-xs font-mono text-slate-500 dark:text-slate-400 flex-shrink-0">#{{ $ticket->id }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full flex-shrink-0
                                    @if($ticket->estado === 'enviado') bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400
                                    @elseif($ticket->estado === 'recibido') bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400
                                    @else bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400
                                    @endif">
                                    {{ ucfirst($ticket->estado) }}
                                </span>
                                @if($ticket->urgente)
                                    <span class="text-xs px-1.5 py-0.5 rounded-full bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400 flex-shrink-0">⚠️</span>
                                @endif
                                @if($ticket->prioritario)
                                    <span class="text-xs px-1.5 py-0.5 rounded-full bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400 flex-shrink-0">⭐</span>
                                @endif
                                @if($ticket->a_discutir)
                                    <span class="text-xs px-1.5 py-0.5 rounded-full bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 flex-shrink-0">💬</span>
                                @endif
                                <h3 class="text-sm font-semibold text-slate-900 dark:text-white truncate flex-1 min-w-0">{{ $ticket->asunto }}</h3>
                            </div>
                            <span class="text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap flex-shrink-0">{{ $ticket->created_at->diffForHumans() }}</span>
                        </div>
                        
                        <!-- Línea 2: Botones -->
                        <div class="px-3 py-1.5 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-700/50 flex items-center gap-1.5 flex-wrap" onclick="event.stopPropagation();">
                            <button onclick="changeStatus({{ $ticket->id }}, 'enviado')" class="px-2 py-1 text-xs rounded transition-all {{ $ticket->estado === 'enviado' ? 'bg-amber-500 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-400 hover:bg-amber-100 dark:hover:bg-amber-500/20' }}">
                                Enviado
                            </button>
                            <button onclick="changeStatus({{ $ticket->id }}, 'recibido')" class="px-2 py-1 text-xs rounded transition-all {{ $ticket->estado === 'recibido' ? 'bg-blue-500 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-400 hover:bg-blue-100 dark:hover:bg-blue-500/20' }}">
                                Recibido
                            </button>
                            <button onclick="changeStatus({{ $ticket->id }}, 'resuelto')" class="px-2 py-1 text-xs rounded transition-all {{ $ticket->estado === 'resuelto' ? 'bg-emerald-500 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-400 hover:bg-emerald-100 dark:hover:bg-emerald-500/20' }}">
                                Resuelto
                            </button>
                            @php $archivos = getArchivos($ticket); @endphp
                            @if(count($archivos) > 0)
                                @if(count($archivos) === 1)
                                    <a href="{{ asset('storage/' . $archivos[0]) }}" target="_blank" class="px-2 py-1 text-xs bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-800 dark:text-white rounded transition-all flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Ver
                                    </a>
                                @else
                                    <div class="px-2 py-1 text-xs bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-white rounded flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ count($archivos) }}
                                    </div>
                                @endif
                            @endif
                            <button onclick="showTicketDetail({{ $ticket->id }})" class="px-2 py-1 text-xs bg-walee-500 hover:bg-walee-400 text-white rounded transition-all">
                                Detalle
                            </button>
                            <button onclick="deleteTicket({{ $ticket->id }})" class="px-2 py-1 text-xs bg-red-500 hover:bg-red-400 text-white rounded transition-all">
                                Eliminar
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 animate-fade-in-up">
                        <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                            <svg class="w-10 h-10 text-slate-400 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">No hay tickets</h3>
                        <p class="text-slate-600 dark:text-slate-400">Aún no se han recibido tickets de soporte</p>
                    </div>
                @endforelse
                
                <!-- Paginación para tab "todos" -->
                @if(isset($ticketsTodos) && $ticketsTodos->hasPages())
                    <div class="mt-6 flex justify-center">
                        <div class="flex items-center gap-2">
                            {{ $ticketsTodos->links() }}
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Tickets List - Enviados -->
            <div id="ticketsList-enviados" class="tickets-container space-y-2 md:space-y-3 {{ (isset($activeTab) && $activeTab === 'enviados') ? '' : 'hidden' }}">
                @php
                    $enviadosToShow = isset($ticketsEnviados) ? $ticketsEnviados : $ticketsEnviados;
                @endphp
                @forelse($enviadosToShow as $index => $ticket)
                    <div class="ticket-card bg-white dark:bg-slate-800/50 border @if($ticket->urgente) border-red-500 dark:border-red-500 @elseif($ticket->prioritario) border-yellow-500 dark:border-yellow-500 @elseif($ticket->a_discutir) border-blue-500 dark:border-blue-500 @else border-slate-200 dark:border-slate-700/50 @endif rounded-lg overflow-hidden hover:border-orange-400 dark:hover:border-orange-500/30 transition-all animate-fade-in-up shadow-sm dark:shadow-none" style="animation-delay: {{ 0.15 + ($index * 0.05) }}s;" data-id="{{ $ticket->id }}">
                        <!-- Línea 1: Info del ticket -->
                        <div class="px-3 py-2 flex items-center justify-between gap-2" onclick="event.stopPropagation(); showTicketDetail({{ $ticket->id }})">
                            <div class="flex-1 min-w-0 flex items-center gap-2">
                                <span class="text-xs font-mono text-slate-500 dark:text-slate-400 flex-shrink-0">#{{ $ticket->id }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 flex-shrink-0">
                                    Enviado
                                </span>
                                @if($ticket->urgente)
                                    <span class="text-xs px-1.5 py-0.5 rounded-full bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400 flex-shrink-0">⚠️</span>
                                @endif
                                @if($ticket->prioritario)
                                    <span class="text-xs px-1.5 py-0.5 rounded-full bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400 flex-shrink-0">⭐</span>
                                @endif
                                @if($ticket->a_discutir)
                                    <span class="text-xs px-1.5 py-0.5 rounded-full bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 flex-shrink-0">💬</span>
                                @endif
                                <h3 class="text-sm font-semibold text-slate-900 dark:text-white truncate flex-1 min-w-0">{{ $ticket->asunto }}</h3>
                            </div>
                            <span class="text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap flex-shrink-0">{{ $ticket->created_at->diffForHumans() }}</span>
                        </div>
                        
                        <!-- Línea 2: Botones -->
                        <div class="px-3 py-1.5 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-700/50 flex items-center gap-1.5 flex-wrap" onclick="event.stopPropagation();">
                            <button onclick="changeStatus({{ $ticket->id }}, 'enviado')" class="px-2 py-1 text-xs rounded transition-all bg-amber-500 text-white">
                                Enviado
                            </button>
                            <button onclick="changeStatus({{ $ticket->id }}, 'recibido')" class="px-2 py-1 text-xs rounded transition-all {{ $ticket->estado === 'recibido' ? 'bg-blue-500 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-400 hover:bg-blue-100 dark:hover:bg-blue-500/20' }}">
                                Recibido
                            </button>
                            <button onclick="changeStatus({{ $ticket->id }}, 'resuelto')" class="px-2 py-1 text-xs rounded transition-all {{ $ticket->estado === 'resuelto' ? 'bg-emerald-500 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-400 hover:bg-emerald-100 dark:hover:bg-emerald-500/20' }}">
                                Resuelto
                            </button>
                            @php $archivos = getArchivos($ticket); @endphp
                            @if(count($archivos) > 0)
                                @if(count($archivos) === 1)
                                    <a href="{{ asset('storage/' . $archivos[0]) }}" target="_blank" class="px-2 py-1 text-xs bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-800 dark:text-white rounded transition-all flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Ver
                                    </a>
                                @else
                                    <div class="px-2 py-1 text-xs bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-white rounded flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ count($archivos) }}
                                    </div>
                                @endif
                            @endif
                            <button onclick="showTicketDetail({{ $ticket->id }})" class="px-2 py-1 text-xs bg-walee-500 hover:bg-walee-400 text-white rounded transition-all">
                                Detalle
                            </button>
                            <button onclick="deleteTicket({{ $ticket->id }})" class="px-2 py-1 text-xs bg-red-500 hover:bg-red-400 text-white rounded transition-all">
                                Eliminar
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 animate-fade-in-up">
                        <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center">
                            <svg class="w-10 h-10 text-amber-400 dark:text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">No hay tickets enviados</h3>
                        <p class="text-slate-600 dark:text-slate-400">No se han encontrado tickets con estado "enviado"</p>
                    </div>
                @endforelse
                
                <!-- Paginación para tab "enviados" -->
                @if(isset($ticketsEnviados) && method_exists($ticketsEnviados, 'hasPages') && $ticketsEnviados->hasPages())
                    <div class="mt-6 flex justify-center">
                        <div class="flex items-center gap-2">
                            {{ $ticketsEnviados->links() }}
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Tickets List - Recibidos -->
            <div id="ticketsList-recibidos" class="tickets-container space-y-2 md:space-y-3 {{ (isset($activeTab) && $activeTab === 'recibidos') ? '' : 'hidden' }}">
                @php
                    $recibidosToShow = isset($ticketsRecibidos) ? $ticketsRecibidos : $ticketsRecibidos;
                @endphp
                @forelse($recibidosToShow as $index => $ticket)
                    <div class="ticket-card bg-white dark:bg-slate-800/50 border @if($ticket->urgente) border-red-500 dark:border-red-500 @elseif($ticket->prioritario) border-yellow-500 dark:border-yellow-500 @elseif($ticket->a_discutir) border-blue-500 dark:border-blue-500 @else border-slate-200 dark:border-slate-700/50 @endif rounded-lg overflow-hidden hover:border-orange-400 dark:hover:border-orange-500/30 transition-all animate-fade-in-up shadow-sm dark:shadow-none" style="animation-delay: {{ 0.15 + ($index * 0.05) }}s;" data-id="{{ $ticket->id }}">
                        <!-- Línea 1: Info del ticket -->
                        <div class="px-3 py-2 flex items-center justify-between gap-2" onclick="event.stopPropagation(); showTicketDetail({{ $ticket->id }})">
                            <div class="flex-1 min-w-0 flex items-center gap-2">
                                <span class="text-xs font-mono text-slate-500 dark:text-slate-400 flex-shrink-0">#{{ $ticket->id }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 flex-shrink-0">
                                    Recibido
                                </span>
                                @if($ticket->urgente)
                                    <span class="text-xs px-1.5 py-0.5 rounded-full bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400 flex-shrink-0">⚠️</span>
                                @endif
                                @if($ticket->prioritario)
                                    <span class="text-xs px-1.5 py-0.5 rounded-full bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400 flex-shrink-0">⭐</span>
                                @endif
                                @if($ticket->a_discutir)
                                    <span class="text-xs px-1.5 py-0.5 rounded-full bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 flex-shrink-0">💬</span>
                                @endif
                                <h3 class="text-sm font-semibold text-slate-900 dark:text-white truncate flex-1 min-w-0">{{ $ticket->asunto }}</h3>
                            </div>
                            <span class="text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap flex-shrink-0">{{ $ticket->created_at->diffForHumans() }}</span>
                        </div>
                        
                        <!-- Línea 2: Botones -->
                        <div class="px-3 py-1.5 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-700/50 flex items-center gap-1.5 flex-wrap" onclick="event.stopPropagation();">
                            <button onclick="changeStatus({{ $ticket->id }}, 'enviado')" class="px-2 py-1 text-xs rounded transition-all {{ $ticket->estado === 'enviado' ? 'bg-amber-500 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-400 hover:bg-amber-100 dark:hover:bg-amber-500/20' }}">
                                Enviado
                            </button>
                            <button onclick="changeStatus({{ $ticket->id }}, 'recibido')" class="px-2 py-1 text-xs rounded transition-all bg-blue-500 text-white">
                                Recibido
                            </button>
                            <button onclick="changeStatus({{ $ticket->id }}, 'resuelto')" class="px-2 py-1 text-xs rounded transition-all {{ $ticket->estado === 'resuelto' ? 'bg-emerald-500 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-400 hover:bg-emerald-100 dark:hover:bg-emerald-500/20' }}">
                                Resuelto
                            </button>
                            @php $archivos = getArchivos($ticket); @endphp
                            @if(count($archivos) > 0)
                                @if(count($archivos) === 1)
                                    <a href="{{ asset('storage/' . $archivos[0]) }}" target="_blank" class="px-2 py-1 text-xs bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-800 dark:text-white rounded transition-all flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Ver
                                    </a>
                                @else
                                    <div class="px-2 py-1 text-xs bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-white rounded flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ count($archivos) }}
                                    </div>
                                @endif
                            @endif
                            <button onclick="showTicketDetail({{ $ticket->id }})" class="px-2 py-1 text-xs bg-walee-500 hover:bg-walee-400 text-white rounded transition-all">
                                Detalle
                            </button>
                            <button onclick="deleteTicket({{ $ticket->id }})" class="px-2 py-1 text-xs bg-red-500 hover:bg-red-400 text-white rounded transition-all">
                                Eliminar
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 animate-fade-in-up">
                        <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center">
                            <svg class="w-10 h-10 text-blue-400 dark:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">No hay tickets recibidos</h3>
                        <p class="text-slate-600 dark:text-slate-400">No se han encontrado tickets con estado "recibido"</p>
                    </div>
                @endforelse
                
                <!-- Paginación para tab "recibidos" -->
                @if(isset($ticketsRecibidos) && method_exists($ticketsRecibidos, 'hasPages') && $ticketsRecibidos->hasPages())
                    <div class="mt-6 flex justify-center">
                        <div class="flex items-center gap-2">
                            {{ $ticketsRecibidos->links() }}
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Tickets List - Resueltos -->
            <div id="ticketsList-resueltos" class="tickets-container space-y-2 md:space-y-3 {{ (isset($activeTab) && $activeTab === 'resueltos') ? '' : 'hidden' }}">
                @php
                    $resueltosToShow = isset($ticketsResueltos) ? $ticketsResueltos : $ticketsResueltos;
                @endphp
                @forelse($resueltosToShow as $index => $ticket)
                    <div class="ticket-card bg-white dark:bg-slate-800/50 border @if($ticket->urgente) border-red-500 dark:border-red-500 @elseif($ticket->prioritario) border-yellow-500 dark:border-yellow-500 @elseif($ticket->a_discutir) border-blue-500 dark:border-blue-500 @else border-slate-200 dark:border-slate-700/50 @endif rounded-lg overflow-hidden hover:border-orange-400 dark:hover:border-orange-500/30 transition-all animate-fade-in-up shadow-sm dark:shadow-none" style="animation-delay: {{ 0.15 + ($index * 0.05) }}s;" data-id="{{ $ticket->id }}">
                        <!-- Línea 1: Info del ticket -->
                        <div class="px-3 py-2 flex items-center justify-between gap-2" onclick="event.stopPropagation(); showTicketDetail({{ $ticket->id }})">
                            <div class="flex-1 min-w-0 flex items-center gap-2">
                                <span class="text-xs font-mono text-slate-500 dark:text-slate-400 flex-shrink-0">#{{ $ticket->id }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 flex-shrink-0">
                                    Resuelto
                                </span>
                                @if($ticket->urgente)
                                    <span class="text-xs px-1.5 py-0.5 rounded-full bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400 flex-shrink-0">⚠️</span>
                                @endif
                                @if($ticket->prioritario)
                                    <span class="text-xs px-1.5 py-0.5 rounded-full bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400 flex-shrink-0">⭐</span>
                                @endif
                                @if($ticket->a_discutir)
                                    <span class="text-xs px-1.5 py-0.5 rounded-full bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 flex-shrink-0">💬</span>
                                @endif
                                <h3 class="text-sm font-semibold text-slate-900 dark:text-white truncate flex-1 min-w-0">{{ $ticket->asunto }}</h3>
                            </div>
                            <span class="text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap flex-shrink-0">{{ $ticket->created_at->diffForHumans() }}</span>
                        </div>
                        
                        <!-- Línea 2: Botones -->
                        <div class="px-3 py-1.5 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-700/50 flex items-center gap-1.5 flex-wrap" onclick="event.stopPropagation();">
                            <button onclick="changeStatus({{ $ticket->id }}, 'enviado')" class="px-2 py-1 text-xs rounded transition-all {{ $ticket->estado === 'enviado' ? 'bg-amber-500 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-400 hover:bg-amber-100 dark:hover:bg-amber-500/20' }}">
                                Enviado
                            </button>
                            <button onclick="changeStatus({{ $ticket->id }}, 'recibido')" class="px-2 py-1 text-xs rounded transition-all {{ $ticket->estado === 'recibido' ? 'bg-blue-500 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-400 hover:bg-blue-100 dark:hover:bg-blue-500/20' }}">
                                Recibido
                            </button>
                            <button onclick="changeStatus({{ $ticket->id }}, 'resuelto')" class="px-2 py-1 text-xs rounded transition-all bg-emerald-500 text-white">
                                Resuelto
                            </button>
                            @php $archivos = getArchivos($ticket); @endphp
                            @if(count($archivos) > 0)
                                @if(count($archivos) === 1)
                                    <a href="{{ asset('storage/' . $archivos[0]) }}" target="_blank" class="px-2 py-1 text-xs bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-800 dark:text-white rounded transition-all flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Ver
                                    </a>
                                @else
                                    <div class="px-2 py-1 text-xs bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-white rounded flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ count($archivos) }}
                                    </div>
                                @endif
                            @endif
                            <button onclick="showTicketDetail({{ $ticket->id }})" class="px-2 py-1 text-xs bg-walee-500 hover:bg-walee-400 text-white rounded transition-all">
                                Detalle
                            </button>
                            <button onclick="deleteTicket({{ $ticket->id }})" class="px-2 py-1 text-xs bg-red-500 hover:bg-red-400 text-white rounded transition-all">
                                Eliminar
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 animate-fade-in-up">
                        <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center">
                            <svg class="w-10 h-10 text-emerald-400 dark:text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">No hay tickets resueltos</h3>
                        <p class="text-slate-600 dark:text-slate-400">No se han encontrado tickets con estado "resuelto"</p>
                    </div>
                @endforelse
                
                <!-- Paginación para tab "resueltos" -->
                @if(isset($ticketsResueltos) && method_exists($ticketsResueltos, 'hasPages') && $ticketsResueltos->hasPages())
                    <div class="mt-6 flex justify-center">
                        <div class="flex items-center gap-2">
                            {{ $ticketsResueltos->links() }}
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Footer -->
            <footer class="text-center py-8 mt-4">
                <p class="text-sm text-slate-600 dark:text-slate-500">
                    <span class="text-walee-600 dark:text-walee-400 font-medium">Walee</span> · websolutions.work
                </p>
            </footer>
        </div>
    </div>
    
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const ticketsData = @json($allTicketsForData);
        
        // Search functionality
        function filterTickets(searchTerm) {
            const term = searchTerm.toLowerCase().trim();
            const allContainers = document.querySelectorAll('.tickets-container');
            
            allContainers.forEach(container => {
                const tickets = container.querySelectorAll('.ticket-card');
                let visibleCount = 0;
                
                tickets.forEach(ticket => {
                    const asunto = ticket.querySelector('h3')?.textContent?.toLowerCase() || '';
                    const mensaje = ticket.querySelector('p')?.textContent?.toLowerCase() || '';
                    const ticketId = ticket.getAttribute('data-id') || '';
                    const nameElements = ticket.querySelectorAll('span');
                    let name = '';
                    nameElements.forEach(el => {
                        const text = el.textContent?.toLowerCase() || '';
                        if (text && !text.includes('#') && !text.includes('@') && text.length > 2) {
                            name += text + ' ';
                        }
                    });
                    
                    // Buscar en todos los campos
                    const matches = asunto.includes(term) || 
                                   mensaje.includes(term) || 
                                   ticketId.includes(term) ||
                                   name.includes(term);
                    
                    if (matches || term === '') {
                        ticket.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        ticket.classList.add('hidden');
                    }
                });
                
                // Mostrar mensaje si no hay resultados
                let noResultsMsg = container.querySelector('.no-results-message');
                if (visibleCount === 0 && term !== '') {
                    if (!noResultsMsg) {
                        noResultsMsg = document.createElement('div');
                        noResultsMsg.className = 'no-results-message text-center py-12 text-slate-500 dark:text-slate-400';
                        noResultsMsg.innerHTML = `
                            <svg class="w-16 h-16 mx-auto mb-4 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <p class="text-lg font-medium">No se encontraron tickets</p>
                            <p class="text-sm mt-2">Intenta con otros términos de búsqueda</p>
                        `;
                        container.appendChild(noResultsMsg);
                    }
                    noResultsMsg.classList.remove('hidden');
                } else {
                    if (noResultsMsg) {
                        noResultsMsg.classList.add('hidden');
                    }
                }
            });
        }
        
        function clearSearch() {
            const searchInput = document.getElementById('ticketSearchInput');
            const clearBtn = document.getElementById('clearSearchBtn');
            if (searchInput) {
                searchInput.value = '';
                filterTickets('');
                clearBtn.classList.add('hidden');
            }
        }
        
        // Event listeners para búsqueda
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('ticketSearchInput');
            const clearBtn = document.getElementById('clearSearchBtn');
            
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const term = e.target.value;
                    filterTickets(term);
                    
                    // Mostrar/ocultar botón de limpiar
                    if (term.trim() !== '') {
                        clearBtn.classList.remove('hidden');
                    } else {
                        clearBtn.classList.add('hidden');
                    }
                });
                
                // Permitir limpiar con Escape
                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        clearSearch();
                    }
                });
            }
        });
        
        function switchTab(tab) {
            // Ocultar todos los contenedores
            document.querySelectorAll('.tickets-container').forEach(container => {
                container.classList.add('hidden');
            });
            
            // Remover estilo activo de todos los botones
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('bg-walee-500', 'text-white');
                button.classList.add('text-slate-600', 'dark:text-slate-400', 'hover:bg-slate-100', 'dark:hover:bg-slate-700/50');
            });
            
            // Mostrar el contenedor seleccionado
            const container = document.getElementById(`ticketsList-${tab}`);
            if (container) {
                container.classList.remove('hidden');
            }
            
            // Activar el botón seleccionado
            const button = document.getElementById(`tab-${tab}`);
            if (button) {
                button.classList.remove('text-slate-600', 'dark:text-slate-400', 'hover:bg-slate-100', 'dark:hover:bg-slate-700/50');
                button.classList.add('bg-walee-500', 'text-white');
            }
        }
        
        async function changeStatus(ticketId, newStatus) {
            try {
                const response = await fetch(`/walee-tickets/${ticketId}/estado`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ estado: newStatus }),
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification('Estado actualizado', `Ticket #${ticketId} → ${newStatus}`, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification('Error', data.message || 'No se pudo actualizar', 'error');
                }
            } catch (error) {
                showNotification('Error', 'Error de conexión', 'error');
            }
        }
        
        async function togglePrioritario(ticketId, buttonElement) {
            try {
                const response = await fetch(`/walee-tickets/${ticketId}/prioritario`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification(
                        data.prioritario ? 'Marcado como prioritario' : 'Prioritario removido', 
                        data.message, 
                        'success'
                    );
                    // Recargar para actualizar todos los botones y badges
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(data.message || 'Error al actualizar prioritario', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error de conexión', 'error');
            }
        }
        
        async function toggleADiscutir(ticketId, buttonElement) {
            try {
                const response = await fetch(`/walee-tickets/${ticketId}/a-discutir`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification(
                        data.a_discutir ? 'Marcado como a discutir' : 'A discutir removido', 
                        data.message, 
                        'success'
                    );
                    // Recargar para actualizar todos los botones y badges
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(data.message || 'Error al actualizar a discutir', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error de conexión', 'error');
            }
        }
        
        async function toggleUrgente(ticketId, buttonElement) {
            try {
                const response = await fetch(`/walee-tickets/${ticketId}/urgente`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });
                
                const data = await response.json();
                
                if (data.success) {
                    const isUrgente = data.urgente;
                    const message = isUrgente 
                        ? `Ticket #${ticketId} marcado como urgente` 
                        : `Ticket #${ticketId} ya no es urgente`;
                    
                    showNotification(
                        isUrgente ? 'Marcado como urgente' : 'Urgente removido', 
                        message, 
                        'success'
                    );
                    
                    // Recargar la página después de un breve delay para mostrar la notificación
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    showNotification('Error', data.message || 'No se pudo actualizar', 'error');
                }
            } catch (error) {
                showNotification('Error', 'Error de conexión', 'error');
            }
        }
        
        function editTicket(ticketId) {
            const ticket = ticketsData.find(t => t.id === ticketId);
            if (!ticket) {
                showNotification('Error', 'Ticket no encontrado', 'error');
                return;
            }
            
            // Llenar el formulario con los datos del ticket
            document.getElementById('editTicketId').value = ticket.id;
            document.getElementById('editAsunto').value = ticket.asunto || '';
            document.getElementById('editMensaje').value = ticket.mensaje || '';
            document.getElementById('editName').value = ticket.name || '';
            document.getElementById('editEmail').value = ticket.email || '';
            document.getElementById('editTelefono').value = ticket.telefono || '';
            document.getElementById('editWebsite').value = ticket.website || '';
            document.getElementById('editEstado').value = ticket.estado || 'enviado';
            document.getElementById('editUrgente').checked = ticket.urgente || false;
            
            // Mostrar el modal
            document.getElementById('editTicketModal').classList.remove('hidden');
        }
        
        function closeEditModal() {
            document.getElementById('editTicketModal').classList.add('hidden');
            document.getElementById('editTicketForm').reset();
        }
        
        async function deleteTicket(ticketId) {
            if (!confirm(`¿Estás seguro de que quieres eliminar el ticket #${ticketId}?`)) {
                return;
            }
            
            try {
                const response = await fetch(`/walee-tickets/${ticketId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification('Ticket eliminado', `Ticket #${ticketId} eliminado correctamente`, 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    showNotification('Error', data.message || 'No se pudo eliminar', 'error');
                }
            } catch (error) {
                showNotification('Error', 'Error de conexión', 'error');
            }
        }
        
        // Manejar el envío del formulario de edición
        document.getElementById('editTicketForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const ticketId = document.getElementById('editTicketId').value;
            const formData = {
                asunto: document.getElementById('editAsunto').value,
                mensaje: document.getElementById('editMensaje').value,
                name: document.getElementById('editName').value,
                email: document.getElementById('editEmail').value,
                telefono: document.getElementById('editTelefono').value,
                website: document.getElementById('editWebsite').value,
                estado: document.getElementById('editEstado').value,
                urgente: document.getElementById('editUrgente').checked,
            };
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Guardando...';
            
            try {
                const response = await fetch(`/walee-tickets/${ticketId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify(formData),
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification('Ticket actualizado', `Ticket #${ticketId} actualizado correctamente`, 'success');
                    closeEditModal();
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    showNotification('Error', data.message || 'No se pudo actualizar', 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            } catch (error) {
                showNotification('Error', 'Error de conexión', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
        
        async function showTicketDetail(ticketId) {
            const ticket = ticketsData.find(t => t.id === ticketId);
            if (!ticket) return;
            
            const isMobile = window.innerWidth < 640;
            const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;
            const isDesktop = window.innerWidth >= 1024;
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            let modalWidth = '95%';
            if (isDesktop) {
                modalWidth = '700px';
            } else if (isTablet) {
                modalWidth = '600px';
            } else if (isMobile) {
                modalWidth = '95%';
            }
            
            const createdAt = new Date(ticket.created_at).toLocaleString('es-ES', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            // Procesar archivos
            let archivosHtml = '';
            if (ticket.imagen) {
                let archivos = [];
                try {
                    const decoded = JSON.parse(ticket.imagen);
                    if (Array.isArray(decoded)) {
                        archivos = decoded;
                    } else {
                        archivos = [ticket.imagen];
                    }
                } catch(e) {
                    archivos = [ticket.imagen];
                }
                
                if (archivos.length > 0) {
                    archivosHtml = `
                        <div class="mt-3 pt-3 border-t ${isDarkMode ? 'border-slate-700' : 'border-slate-200'}">
                            <p class="text-xs font-medium ${isDarkMode ? 'text-slate-400' : 'text-slate-500'} mb-2">Archivos adjuntos:</p>
                            <div class="flex flex-wrap gap-2">
                                ${archivos.map((archivo, idx) => {
                                    const isImage = /\.(jpg|jpeg|png|gif|webp)$/i.test(archivo);
                                    return `
                                        <a href="/storage/${archivo}" target="_blank" class="text-xs ${isDarkMode ? 'text-walee-400' : 'text-walee-600'} hover:underline">
                                            ${isImage ? '🖼️' : '📄'} Archivo ${idx + 1}
                                        </a>
                                    `;
                                }).join('')}
                            </div>
                        </div>
                    `;
                }
            }
            
            const html = `
                <div class="text-left space-y-2 ${isMobile ? 'text-xs' : 'text-sm'}">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-xs px-2 py-0.5 rounded-full
                            ${ticket.estado === 'enviado' ? 'bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400' : ''}
                            ${ticket.estado === 'recibido' ? 'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400' : ''}
                            ${ticket.estado === 'resuelto' ? 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400' : ''}">
                            ${ticket.estado.charAt(0).toUpperCase() + ticket.estado.slice(1)}
                        </span>
                        ${ticket.urgente ? '<span class="text-xs px-1.5 py-0.5 rounded-full bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400">⚠️ Urgente</span>' : ''}
                        ${ticket.prioritario ? '<span class="text-xs px-1.5 py-0.5 rounded-full bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400">⭐ Prioritario</span>' : ''}
                        ${ticket.a_discutir ? '<span class="text-xs px-1.5 py-0.5 rounded-full bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400">💬 A Discutir</span>' : ''}
                    </div>
                    <div>
                        <p class="text-xs ${isDarkMode ? 'text-slate-400' : 'text-slate-500'} mb-1">Fecha: ${createdAt}</p>
                        <h4 class="${isMobile ? 'text-sm' : 'text-base'} font-semibold ${isDarkMode ? 'text-white' : 'text-slate-900'} mb-2">${ticket.asunto}</h4>
                    </div>
                    ${ticket.name ? `<p class="text-xs ${isDarkMode ? 'text-slate-300' : 'text-slate-700'}"><strong>Empresa:</strong> ${ticket.name}</p>` : ''}
                    ${ticket.email ? `<p class="text-xs ${isDarkMode ? 'text-slate-300' : 'text-slate-700'}"><strong>Email:</strong> ${ticket.email}</p>` : ''}
                    ${ticket.telefono ? `<p class="text-xs ${isDarkMode ? 'text-slate-300' : 'text-slate-700'}"><strong>Teléfono:</strong> ${ticket.telefono}</p>` : ''}
                    ${ticket.website ? `<p class="text-xs ${isDarkMode ? 'text-slate-300' : 'text-slate-700'}"><strong>Web:</strong> <a href="${ticket.website}" target="_blank" class="${isDarkMode ? 'text-walee-400' : 'text-walee-600'} hover:underline">${ticket.website}</a></p>` : ''}
                    ${ticket.user && !ticket.name ? `<p class="text-xs ${isDarkMode ? 'text-slate-300' : 'text-slate-700'}">De: ${ticket.user.name} (${ticket.user.email})</p>` : ''}
                    <div class="mt-2 pt-2 border-t ${isDarkMode ? 'border-slate-700' : 'border-slate-200'}">
                        <p class="text-xs font-medium ${isDarkMode ? 'text-slate-400' : 'text-slate-500'} mb-1">Mensaje:</p>
                        <p class="${isDarkMode ? 'text-slate-200' : 'text-slate-800'} whitespace-pre-wrap ${isMobile ? 'text-xs' : 'text-sm'}">${ticket.mensaje}</p>
                    </div>
                    ${archivosHtml}
                </div>
            `;
            
            await Swal.fire({
                title: `Ticket #${ticket.id}`,
                html: html,
                width: modalWidth,
                padding: isMobile ? '1rem' : '1.5rem',
                showConfirmButton: true,
                confirmButtonText: 'Cerrar',
                confirmButtonColor: '#D59F3B',
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                }
            });
        }
        
        function showNotification(title, body, type = 'info') {
            const container = document.getElementById('notifications');
            const id = 'notif-' + Date.now();
            
            const bgClass = {
                'success': 'bg-emerald-600',
                'error': 'bg-red-600',
                'info': 'bg-blue-600',
            }[type] || 'bg-slate-600';
            
            const notification = document.createElement('div');
            notification.id = id;
            notification.className = `${bgClass} text-white px-4 py-3 rounded-xl shadow-lg transform translate-x-full transition-transform duration-300`;
            notification.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="flex-1">
                        <p class="font-medium text-sm">${title}</p>
                        <p class="text-xs opacity-90 mt-0.5">${body}</p>
                    </div>
                    <button onclick="document.getElementById('${id}').remove()" class="text-white/70 hover:text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;
            
            container.appendChild(notification);
            setTimeout(() => notification.classList.remove('translate-x-full'), 10);
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        }
        
        // Estilos para SweetAlert dark/light mode
        const style = document.createElement('style');
        style.textContent = `
            .dark-swal {
                background: #1e293b !important;
                color: #e2e8f0 !important;
            }
            .light-swal {
                background: #ffffff !important;
                color: #1e293b !important;
            }
            .dark-swal-title {
                color: #f1f5f9 !important;
            }
            .light-swal-title {
                color: #0f172a !important;
            }
            .dark-swal-html {
                color: #cbd5e1 !important;
            }
            .light-swal-html {
                color: #334155 !important;
            }
            .dark-swal-confirm {
                background: #D59F3B !important;
            }
            .light-swal-confirm {
                background: #D59F3B !important;
            }
            @media (max-width: 640px) {
                .swal2-popup {
                    width: 95% !important;
                    margin: 0.5rem !important;
                    padding: 1rem !important;
                }
                .swal2-title {
                    font-size: 1.125rem !important;
                    margin-bottom: 0.75rem !important;
                }
                .swal2-html-container {
                    margin: 0.5rem 0 !important;
                    font-size: 0.875rem !important;
                }
                .swal2-confirm {
                    font-size: 0.875rem !important;
                    padding: 0.5rem 1rem !important;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
    @include('partials.walee-support-button')
</body>
</html>

