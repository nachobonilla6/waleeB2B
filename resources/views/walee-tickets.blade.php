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
        // Obtener todos los tickets ordenados por prioridad: urgente > prioritario > fecha, y a_discutir al final
        $tickets = \App\Models\Ticket::with('user')
            ->orderBy('a_discutir', 'asc') // A discutir al final (asc = false primero)
            ->orderBy('urgente', 'desc') // Urgentes primero
            ->orderBy('prioritario', 'desc') // Luego prioritarios
            ->orderBy('created_at', 'desc') // Finalmente por fecha más reciente
            ->get();
        
        $totalTickets = $tickets->count();
        $enviados = $tickets->where('estado', 'enviado')->count();
        $recibidos = $tickets->where('estado', 'recibido')->count();
        $resueltos = $tickets->where('estado', 'resuelto')->count();
        
        // Ordenar cada grupo también por prioridad: urgente > prioritario > fecha, y a_discutir al final
        $ticketsEnviados = $tickets->where('estado', 'enviado')
            ->sortBy([
                ['a_discutir', 'asc'], // A discutir al final
                ['urgente', 'desc'],
                ['prioritario', 'desc'],
                ['created_at', 'desc']
            ])->values();
        
        $ticketsRecibidos = $tickets->where('estado', 'recibido')
            ->sortBy([
                ['a_discutir', 'asc'], // A discutir al final
                ['urgente', 'desc'],
                ['prioritario', 'desc'],
                ['created_at', 'desc']
            ])->values();
        
        $ticketsResueltos = $tickets->where('estado', 'resuelto')
            ->sortBy([
                ['a_discutir', 'asc'], // A discutir al final
                ['urgente', 'desc'],
                ['prioritario', 'desc'],
                ['created_at', 'desc']
            ])->values();
        
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
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-xl p-1.5 flex flex-wrap gap-1.5 shadow-sm dark:shadow-none">
                    <a href="{{ route('walee.tickets.tab', ['tab' => 'todos']) }}" id="tab-todos" class="tab-button flex-1 min-w-[calc(50%-0.375rem)] sm:min-w-0 px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg font-medium text-xs sm:text-sm transition-all text-center {{ (isset($activeTab) && $activeTab === 'todos') || (!isset($activeTab)) ? 'bg-walee-500 text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50' }}">
                        <span class="hidden sm:inline">Todos </span>({{ $totalTickets }})
                    </a>
                    <a href="{{ route('walee.tickets.tab', ['tab' => 'enviados']) }}" id="tab-enviados" class="tab-button flex-1 min-w-[calc(50%-0.375rem)] sm:min-w-0 px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg font-medium text-xs sm:text-sm transition-all text-center {{ (isset($activeTab) && $activeTab === 'enviados') ? 'bg-walee-500 text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50' }}">
                        <span class="hidden sm:inline">Enviados </span>({{ $enviados }})
                    </a>
                    <a href="{{ route('walee.tickets.tab', ['tab' => 'recibidos']) }}" id="tab-recibidos" class="tab-button flex-1 min-w-[calc(50%-0.375rem)] sm:min-w-0 px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg font-medium text-xs sm:text-sm transition-all text-center {{ (isset($activeTab) && $activeTab === 'recibidos') ? 'bg-walee-500 text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50' }}">
                        <span class="hidden sm:inline">Recibidos </span>({{ $recibidos }})
                    </a>
                    <a href="{{ route('walee.tickets.tab', ['tab' => 'resueltos']) }}" id="tab-resueltos" class="tab-button flex-1 min-w-[calc(50%-0.375rem)] sm:min-w-0 px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg font-medium text-xs sm:text-sm transition-all text-center {{ (isset($activeTab) && $activeTab === 'resueltos') ? 'bg-walee-500 text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50' }}">
                        <span class="hidden sm:inline">Resueltos </span>({{ $resueltos }})
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
            <div id="ticketsList-todos" class="tickets-container space-y-4 {{ (isset($activeTab) && $activeTab === 'todos') || (!isset($activeTab)) ? '' : 'hidden' }}">
                @forelse($tickets as $index => $ticket)
                    <div class="ticket-card bg-white dark:bg-slate-800/50 border @if($ticket->urgente) border-red-500 dark:border-red-500 @elseif($ticket->prioritario) border-yellow-500 dark:border-yellow-500 @elseif($ticket->a_discutir) border-blue-500 dark:border-blue-500 @else border-slate-200 dark:border-slate-700/50 @endif rounded-2xl overflow-hidden hover:border-orange-400 dark:hover:border-orange-500/30 transition-all animate-fade-in-up shadow-sm dark:shadow-none" style="animation-delay: {{ 0.15 + ($index * 0.05) }}s;" data-id="{{ $ticket->id }}">
                        <div class="p-4">
                            <div class="flex items-start gap-4">
                                <!-- Status Icon -->
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0
                                    @if($ticket->estado === 'enviado') bg-amber-100 dark:bg-amber-500/20
                                    @elseif($ticket->estado === 'recibido') bg-blue-100 dark:bg-blue-500/20
                                    @else bg-emerald-100 dark:bg-emerald-500/20
                                    @endif">
                                    @if($ticket->estado === 'enviado')
                                        <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @elseif($ticket->estado === 'recibido')
                                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @endif
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="text-xs font-mono text-slate-500 dark:text-slate-400">#{{ $ticket->id }}</span>
                                                <span class="text-xs px-2 py-0.5 rounded-full
                                                    @if($ticket->estado === 'enviado') bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400
                                                    @elseif($ticket->estado === 'recibido') bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400
                                                    @else bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400
                                                    @endif">
                                                    {{ ucfirst($ticket->estado) }}
                                                </span>
                                                @if($ticket->urgente)
                                                    <span class="text-xs px-2 py-0.5 rounded-full bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400 border border-red-300 dark:border-red-500/30">
                                                        ⚠️ Urgente
                                                    </span>
                                                @endif
                                                @if($ticket->prioritario)
                                                    <span class="text-xs px-2 py-0.5 rounded-full bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400 border border-yellow-300 dark:border-yellow-500/30">
                                                        ⭐ Prioritario
                                                    </span>
                                                @endif
                                                @if($ticket->a_discutir)
                                                    <span class="text-xs px-2 py-0.5 rounded-full bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 border border-blue-300 dark:border-blue-500/30">
                                                        💬 A Discutir
                                                    </span>
                                                @endif
                                            </div>
                                            <h3 class="font-semibold text-slate-900 dark:text-white truncate">{{ $ticket->asunto }}</h3>
                                        </div>
                                        <div class="flex items-center gap-2 flex-shrink-0">
                                            <button 
                                                onclick="toggleUrgente({{ $ticket->id }}, this)"
                                                class="p-1.5 rounded-lg transition-all hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ $ticket->urgente ? 'text-red-500 dark:text-red-400' : 'text-slate-400 dark:text-slate-600' }}"
                                                title="{{ $ticket->urgente ? 'Quitar urgente' : 'Marcar como urgente' }}"
                                            >
                                                <svg class="w-5 h-5 {{ $ticket->urgente ? 'fill-current' : 'fill-none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                                </svg>
                                            </button>
                                            <button 
                                                onclick="togglePrioritario({{ $ticket->id }}, this)"
                                                class="p-1.5 rounded-lg transition-all hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ $ticket->prioritario ? 'text-yellow-500 dark:text-yellow-400' : 'text-slate-400 dark:text-slate-600' }}"
                                                title="{{ $ticket->prioritario ? 'Quitar prioritario' : 'Marcar como prioritario' }}"
                                            >
                                                <svg class="w-5 h-5 {{ $ticket->prioritario ? 'fill-current' : 'fill-none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                                </svg>
                                            </button>
                                            <button 
                                                onclick="toggleADiscutir({{ $ticket->id }}, this)"
                                                class="p-1.5 rounded-lg transition-all hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ $ticket->a_discutir ? 'text-blue-500 dark:text-blue-400' : 'text-slate-400 dark:text-slate-600' }}"
                                                title="{{ $ticket->a_discutir ? 'Quitar a discutir' : 'Marcar como a discutir' }}"
                                            >
                                                <svg class="w-5 h-5 {{ $ticket->a_discutir ? 'fill-current' : 'fill-none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                                </svg>
                                            </button>
                                            <span class="text-xs text-slate-500 dark:text-slate-400">
                                                {{ $ticket->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <p class="text-sm text-slate-600 dark:text-slate-400 line-clamp-2 mb-3">{{ $ticket->mensaje }}</p>
                                    
                                    <div class="flex flex-wrap items-center gap-3 text-xs text-slate-600 dark:text-slate-500">
                                        @if($ticket->name)
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                                {{ $ticket->name }}
                                            </span>
                                        @elseif($ticket->user)
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                {{ $ticket->user->name }}
                                            </span>
                                        @endif
                                        @if($ticket->email)
                                            <span class="flex items-center gap-1 text-blue-600 dark:text-blue-400">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                                {{ $ticket->email }}
                                            </span>
                                        @endif
                                        @if($ticket->telefono)
                                            <span class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                </svg>
                                                {{ $ticket->telefono }}
                                            </span>
                                        @endif
                                        @if($ticket->website)
                                            <a href="{{ $ticket->website }}" target="_blank" class="flex items-center gap-1 text-walee-600 dark:text-walee-400 hover:underline">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                                </svg>
                                                Sitio web
                                            </a>
                                        @endif
                                        @php $archivos = getArchivos($ticket); @endphp
                                        @if(count($archivos) > 0)
                                            <span class="flex items-center gap-1 text-violet-600 dark:text-violet-400">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                {{ count($archivos) }} {{ count($archivos) === 1 ? 'archivo' : 'archivos' }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions Bar -->
                        <div class="px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-700/50 flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-slate-600 dark:text-slate-500">Cambiar estado:</span>
                                <div class="flex gap-1">
                                    <button onclick="changeStatus({{ $ticket->id }}, 'enviado')" class="px-3 py-1.5 text-xs rounded-lg transition-all {{ $ticket->estado === 'enviado' ? 'bg-amber-500 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-400 hover:bg-amber-100 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400' }}">
                                        Enviado
                                    </button>
                                    <button onclick="changeStatus({{ $ticket->id }}, 'recibido')" class="px-3 py-1.5 text-xs rounded-lg transition-all {{ $ticket->estado === 'recibido' ? 'bg-blue-500 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-400 hover:bg-blue-100 dark:hover:bg-blue-500/20 hover:text-blue-600 dark:hover:text-blue-400' }}">
                                        Recibido
                                    </button>
                                    <button onclick="changeStatus({{ $ticket->id }}, 'resuelto')" class="px-3 py-1.5 text-xs rounded-lg transition-all {{ $ticket->estado === 'resuelto' ? 'bg-emerald-500 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-400 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 hover:text-emerald-600 dark:hover:text-emerald-400' }}">
                                        Resuelto
                                    </button>
                                </div>
                            </div>
                            
                            <div class="flex gap-2">
                                @php $archivos = getArchivos($ticket); @endphp
                                @if(count($archivos) > 0)
                                    @if(count($archivos) === 1)
                                        <a href="{{ asset('storage/' . $archivos[0]) }}" target="_blank" class="px-3 py-1.5 text-xs bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-800 dark:text-white rounded-lg transition-all flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            Ver
                                        </a>
                                    @else
                                        <div class="px-3 py-1.5 text-xs bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-white rounded-lg flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ count($archivos) }} archivos
                                        </div>
                                    @endif
                                @endif
                                <button onclick="deleteTicket({{ $ticket->id }})" class="px-3 py-1.5 text-xs bg-red-500 hover:bg-red-400 text-white rounded-lg transition-all flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Eliminar
                                </button>
                                <button onclick="showTicketDetail({{ $ticket->id }})" class="px-3 py-1.5 text-xs bg-walee-500 hover:bg-walee-400 text-white rounded-lg transition-all">
                                    Detalle
                                </button>
                            </div>
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
            </div>
            
            <!-- Tickets List - Enviados -->
            <div id="ticketsList-enviados" class="tickets-container space-y-4 {{ (isset($activeTab) && $activeTab === 'enviados') ? '' : 'hidden' }}">
                @forelse($ticketsEnviados as $index => $ticket)
                    <div class="ticket-card bg-white dark:bg-slate-800/50 border @if($ticket->urgente) border-red-500 dark:border-red-500 @elseif($ticket->prioritario) border-yellow-500 dark:border-yellow-500 @elseif($ticket->a_discutir) border-blue-500 dark:border-blue-500 @else border-slate-200 dark:border-slate-700/50 @endif rounded-2xl overflow-hidden hover:border-orange-400 dark:hover:border-orange-500/30 transition-all animate-fade-in-up shadow-sm dark:shadow-none" style="animation-delay: {{ 0.15 + ($index * 0.05) }}s;" data-id="{{ $ticket->id }}">
                        <div class="p-4">
                            <div class="flex items-start gap-4">
                                <!-- Status Icon -->
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 bg-amber-100 dark:bg-amber-500/20">
                                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="text-xs font-mono text-slate-500 dark:text-slate-400">#{{ $ticket->id }}</span>
                                                <span class="text-xs px-2 py-0.5 rounded-full bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400">
                                                    Enviado
                                                </span>
                                                @if($ticket->urgente)
                                                    <span class="text-xs px-2 py-0.5 rounded-full bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400 border border-red-300 dark:border-red-500/30">
                                                        ⚠️ Urgente
                                                    </span>
                                                @endif
                                                @if($ticket->prioritario)
                                                    <span class="text-xs px-2 py-0.5 rounded-full bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400 border border-yellow-300 dark:border-yellow-500/30">
                                                        ⭐ Prioritario
                                                    </span>
                                                @endif
                                                @if($ticket->a_discutir)
                                                    <span class="text-xs px-2 py-0.5 rounded-full bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 border border-blue-300 dark:border-blue-500/30">
                                                        💬 A Discutir
                                                    </span>
                                                @endif
                                            </div>
                                            <h3 class="font-semibold text-slate-900 dark:text-white truncate">{{ $ticket->asunto }}</h3>
                                        </div>
                                        <div class="flex items-center gap-2 flex-shrink-0">
                                            <button 
                                                onclick="toggleUrgente({{ $ticket->id }}, this)"
                                                class="p-1.5 rounded-lg transition-all hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ $ticket->urgente ? 'text-red-500 dark:text-red-400' : 'text-slate-400 dark:text-slate-600' }}"
                                                title="{{ $ticket->urgente ? 'Quitar urgente' : 'Marcar como urgente' }}"
                                            >
                                                <svg class="w-5 h-5 {{ $ticket->urgente ? 'fill-current' : 'fill-none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                                </svg>
                                            </button>
                                            <button 
                                                onclick="togglePrioritario({{ $ticket->id }}, this)"
                                                class="p-1.5 rounded-lg transition-all hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ $ticket->prioritario ? 'text-yellow-500 dark:text-yellow-400' : 'text-slate-400 dark:text-slate-600' }}"
                                                title="{{ $ticket->prioritario ? 'Quitar prioritario' : 'Marcar como prioritario' }}"
                                            >
                                                <svg class="w-5 h-5 {{ $ticket->prioritario ? 'fill-current' : 'fill-none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                                </svg>
                                            </button>
                                            <button 
                                                onclick="toggleADiscutir({{ $ticket->id }}, this)"
                                                class="p-1.5 rounded-lg transition-all hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ $ticket->a_discutir ? 'text-blue-500 dark:text-blue-400' : 'text-slate-400 dark:text-slate-600' }}"
                                                title="{{ $ticket->a_discutir ? 'Quitar a discutir' : 'Marcar como a discutir' }}"
                                            >
                                                <svg class="w-5 h-5 {{ $ticket->a_discutir ? 'fill-current' : 'fill-none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                                </svg>
                                            </button>
                                            <span class="text-xs text-slate-500 dark:text-slate-400">
                                                {{ $ticket->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <p class="text-sm text-slate-600 dark:text-slate-400 line-clamp-2 mb-3">{{ $ticket->mensaje }}</p>
                                    
                                    <div class="flex flex-wrap items-center gap-3 text-xs text-slate-600 dark:text-slate-500">
                                        @if($ticket->name)
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                                {{ $ticket->name }}
                                            </span>
                                        @elseif($ticket->user)
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                {{ $ticket->user->name }}
                                            </span>
                                        @endif
                                        @if($ticket->email)
                                            <span class="flex items-center gap-1 text-blue-600 dark:text-blue-400">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                                {{ $ticket->email }}
                                            </span>
                                        @endif
                                        @if($ticket->telefono)
                                            <span class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                </svg>
                                                {{ $ticket->telefono }}
                                            </span>
                                        @endif
                                        @if($ticket->website)
                                            <a href="{{ $ticket->website }}" target="_blank" class="flex items-center gap-1 text-walee-600 dark:text-walee-400 hover:underline">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                                </svg>
                                                Sitio web
                                            </a>
                                        @endif
                                        @php $archivos = getArchivos($ticket); @endphp
                                        @if(count($archivos) > 0)
                                            <span class="flex items-center gap-1 text-violet-600 dark:text-violet-400">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                {{ count($archivos) }} {{ count($archivos) === 1 ? 'archivo' : 'archivos' }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions Bar -->
                        <div class="px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-700/50 flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-slate-600 dark:text-slate-500">Cambiar estado:</span>
                                <div class="flex gap-1">
                                    <button onclick="changeStatus({{ $ticket->id }}, 'enviado')" class="px-3 py-1.5 text-xs rounded-lg transition-all bg-amber-500 text-white">
                                        Enviado
                                    </button>
                                    <button onclick="changeStatus({{ $ticket->id }}, 'recibido')" class="px-3 py-1.5 text-xs rounded-lg transition-all bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-400 hover:bg-blue-100 dark:hover:bg-blue-500/20 hover:text-blue-600 dark:hover:text-blue-400">
                                        Recibido
                                    </button>
                                    <button onclick="changeStatus({{ $ticket->id }}, 'resuelto')" class="px-3 py-1.5 text-xs rounded-lg transition-all bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-400 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 hover:text-emerald-600 dark:hover:text-emerald-400">
                                        Resuelto
                                    </button>
                                </div>
                            </div>
                            
                            <div class="flex gap-2">
                                @php $archivos = getArchivos($ticket); @endphp
                                @if(count($archivos) > 0)
                                    @if(count($archivos) === 1)
                                        <a href="{{ asset('storage/' . $archivos[0]) }}" target="_blank" class="px-3 py-1.5 text-xs bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-800 dark:text-white rounded-lg transition-all flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            Ver
                                        </a>
                                    @else
                                        <div class="px-3 py-1.5 text-xs bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-white rounded-lg flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ count($archivos) }} archivos
                                        </div>
                                    @endif
                                @endif
                                <button onclick="deleteTicket({{ $ticket->id }})" class="px-3 py-1.5 text-xs bg-red-500 hover:bg-red-400 text-white rounded-lg transition-all flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Eliminar
                                </button>
                                <button onclick="showTicketDetail({{ $ticket->id }})" class="px-3 py-1.5 text-xs bg-walee-500 hover:bg-walee-400 text-white rounded-lg transition-all">
                                    Detalle
                                </button>
                            </div>
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
            </div>
            
            <!-- Tickets List - Recibidos -->
            <div id="ticketsList-recibidos" class="tickets-container space-y-4 {{ (isset($activeTab) && $activeTab === 'recibidos') ? '' : 'hidden' }}">
                @forelse($ticketsRecibidos as $index => $ticket)
                    <div class="ticket-card bg-white dark:bg-slate-800/50 border @if($ticket->urgente) border-red-500 dark:border-red-500 @elseif($ticket->prioritario) border-yellow-500 dark:border-yellow-500 @elseif($ticket->a_discutir) border-blue-500 dark:border-blue-500 @else border-slate-200 dark:border-slate-700/50 @endif rounded-2xl overflow-hidden hover:border-orange-400 dark:hover:border-orange-500/30 transition-all animate-fade-in-up shadow-sm dark:shadow-none" style="animation-delay: {{ 0.15 + ($index * 0.05) }}s;" data-id="{{ $ticket->id }}">
                        <div class="p-4">
                            <div class="flex items-start gap-4">
                                <!-- Status Icon -->
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 bg-blue-100 dark:bg-blue-500/20">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="text-xs font-mono text-slate-500 dark:text-slate-400">#{{ $ticket->id }}</span>
                                                <span class="text-xs px-2 py-0.5 rounded-full bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400">
                                                    Recibido
                                                </span>
                                                @if($ticket->urgente)
                                                    <span class="text-xs px-2 py-0.5 rounded-full bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400 border border-red-300 dark:border-red-500/30">
                                                        ⚠️ Urgente
                                                    </span>
                                                @endif
                                                @if($ticket->prioritario)
                                                    <span class="text-xs px-2 py-0.5 rounded-full bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400 border border-yellow-300 dark:border-yellow-500/30">
                                                        ⭐ Prioritario
                                                    </span>
                                                @endif
                                                @if($ticket->a_discutir)
                                                    <span class="text-xs px-2 py-0.5 rounded-full bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 border border-blue-300 dark:border-blue-500/30">
                                                        💬 A Discutir
                                                    </span>
                                                @endif
                                            </div>
                                            <h3 class="font-semibold text-slate-900 dark:text-white truncate">{{ $ticket->asunto }}</h3>
                                        </div>
                                        <div class="flex items-center gap-2 flex-shrink-0">
                                            <button 
                                                onclick="toggleUrgente({{ $ticket->id }}, this)"
                                                class="p-1.5 rounded-lg transition-all hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ $ticket->urgente ? 'text-red-500 dark:text-red-400' : 'text-slate-400 dark:text-slate-600' }}"
                                                title="{{ $ticket->urgente ? 'Quitar urgente' : 'Marcar como urgente' }}"
                                            >
                                                <svg class="w-5 h-5 {{ $ticket->urgente ? 'fill-current' : 'fill-none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                                </svg>
                                            </button>
                                            <button 
                                                onclick="togglePrioritario({{ $ticket->id }}, this)"
                                                class="p-1.5 rounded-lg transition-all hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ $ticket->prioritario ? 'text-yellow-500 dark:text-yellow-400' : 'text-slate-400 dark:text-slate-600' }}"
                                                title="{{ $ticket->prioritario ? 'Quitar prioritario' : 'Marcar como prioritario' }}"
                                            >
                                                <svg class="w-5 h-5 {{ $ticket->prioritario ? 'fill-current' : 'fill-none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                                </svg>
                                            </button>
                                            <button 
                                                onclick="toggleADiscutir({{ $ticket->id }}, this)"
                                                class="p-1.5 rounded-lg transition-all hover:bg-slate-100 dark:hover:bg-slate-700/50 {{ $ticket->a_discutir ? 'text-blue-500 dark:text-blue-400' : 'text-slate-400 dark:text-slate-600' }}"
                                                title="{{ $ticket->a_discutir ? 'Quitar a discutir' : 'Marcar como a discutir' }}"
                                            >
                                                <svg class="w-5 h-5 {{ $ticket->a_discutir ? 'fill-current' : 'fill-none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                                </svg>
                                            </button>
                                            <span class="text-xs text-slate-500 dark:text-slate-400">
                                                {{ $ticket->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <p class="text-sm text-slate-600 dark:text-slate-400 line-clamp-2 mb-3">{{ $ticket->mensaje }}</p>
                                    
                                    <div class="flex flex-wrap items-center gap-3 text-xs text-slate-600 dark:text-slate-500">
                                        @if($ticket->name)
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                                {{ $ticket->name }}
                                            </span>
                                        @elseif($ticket->user)
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                {{ $ticket->user->name }}
                                            </span>
                                        @endif
                                        @if($ticket->email)
                                            <span class="flex items-center gap-1 text-blue-600 dark:text-blue-400">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                                {{ $ticket->email }}
                                            </span>
                                        @endif
                                        @if($ticket->telefono)
                                            <span class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                </svg>
                                                {{ $ticket->telefono }}
                                            </span>
                                        @endif
                                        @if($ticket->website)
                                            <a href="{{ $ticket->website }}" target="_blank" class="flex items-center gap-1 text-walee-600 dark:text-walee-400 hover:underline">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                                </svg>
                                                Sitio web
                                            </a>
                                        @endif
                                        @php $archivos = getArchivos($ticket); @endphp
                                        @if(count($archivos) > 0)
                                            <span class="flex items-center gap-1 text-violet-600 dark:text-violet-400">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                {{ count($archivos) }} {{ count($archivos) === 1 ? 'archivo' : 'archivos' }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions Bar -->
                        <div class="px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-700/50 flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-slate-600 dark:text-slate-500">Cambiar estado:</span>
                                <div class="flex gap-1">
                                    <button onclick="changeStatus({{ $ticket->id }}, 'enviado')" class="px-3 py-1.5 text-xs rounded-lg transition-all bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-400 hover:bg-amber-100 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400">
                                        Enviado
                                    </button>
                                    <button onclick="changeStatus({{ $ticket->id }}, 'recibido')" class="px-3 py-1.5 text-xs rounded-lg transition-all bg-blue-500 text-white">
                                        Recibido
                                    </button>
                                    <button onclick="changeStatus({{ $ticket->id }}, 'resuelto')" class="px-3 py-1.5 text-xs rounded-lg transition-all bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-400 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 hover:text-emerald-600 dark:hover:text-emerald-400">
                                        Resuelto
                                    </button>
                                </div>
                            </div>
                            
                            <div class="flex gap-2">
                                @php $archivos = getArchivos($ticket); @endphp
                                @if(count($archivos) > 0)
                                    @if(count($archivos) === 1)
                                        <a href="{{ asset('storage/' . $archivos[0]) }}" target="_blank" class="px-3 py-1.5 text-xs bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-800 dark:text-white rounded-lg transition-all flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            Ver
                                        </a>
                                    @else
                                        <div class="px-3 py-1.5 text-xs bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-white rounded-lg flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ count($archivos) }} archivos
                                        </div>
                                    @endif
                                @endif
                                <button onclick="deleteTicket({{ $ticket->id }})" class="px-3 py-1.5 text-xs bg-red-500 hover:bg-red-400 text-white rounded-lg transition-all flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Eliminar
                                </button>
                                <button onclick="showTicketDetail({{ $ticket->id }})" class="px-3 py-1.5 text-xs bg-walee-500 hover:bg-walee-400 text-white rounded-lg transition-all">
                                    Detalle
                                </button>
                            </div>
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
            </div>
            
            <!-- Tickets List - Resueltos -->
            <div id="ticketsList-resueltos" class="tickets-container space-y-4 {{ (isset($activeTab) && $activeTab === 'resueltos') ? '' : 'hidden' }}">
                @forelse($ticketsResueltos as $index => $ticket)
                    <div class="ticket-card bg-white dark:bg-slate-800/50 border @if($ticket->urgente) border-red-500 dark:border-red-500 @elseif($ticket->prioritario) border-yellow-500 dark:border-yellow-500 @elseif($ticket->a_discutir) border-blue-500 dark:border-blue-500 @else border-slate-200 dark:border-slate-700/50 @endif rounded-2xl overflow-hidden hover:border-orange-400 dark:hover:border-orange-500/30 transition-all animate-fade-in-up shadow-sm dark:shadow-none" style="animation-delay: {{ 0.15 + ($index * 0.05) }}s;" data-id="{{ $ticket->id }}">
                        <div class="p-4">
                            <div class="flex items-start gap-4">
                                <!-- Status Icon -->
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 bg-emerald-100 dark:bg-emerald-500/20">
                                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="text-xs font-mono text-slate-500 dark:text-slate-400">#{{ $ticket->id }}</span>
                                                <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400">
                                                    Resuelto
                                                </span>
                                                @if($ticket->urgente)
                                                    <span class="text-xs px-2 py-0.5 rounded-full bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400 border border-red-300 dark:border-red-500/30">
                                                        ⚠️ Urgente
                                                    </span>
                                                @endif
                                                @if($ticket->prioritario)
                                                    <span class="text-xs px-2 py-0.5 rounded-full bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400 border border-yellow-300 dark:border-yellow-500/30">
                                                        ⭐ Prioritario
                                                    </span>
                                                @endif
                                                @if($ticket->a_discutir)
                                                    <span class="text-xs px-2 py-0.5 rounded-full bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 border border-blue-300 dark:border-blue-500/30">
                                                        💬 A Discutir
                                                    </span>
                                                @endif
                                            </div>
                                            <h3 class="font-semibold text-slate-900 dark:text-white truncate">{{ $ticket->asunto }}</h3>
                                        </div>
                                        <span class="text-xs text-slate-500 dark:text-slate-400 flex-shrink-0">
                                            {{ $ticket->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    
                                    <p class="text-sm text-slate-600 dark:text-slate-400 line-clamp-2 mb-3">{{ $ticket->mensaje }}</p>
                                    
                                    <div class="flex flex-wrap items-center gap-3 text-xs text-slate-600 dark:text-slate-500">
                                        @if($ticket->name)
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                                {{ $ticket->name }}
                                            </span>
                                        @elseif($ticket->user)
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                {{ $ticket->user->name }}
                                            </span>
                                        @endif
                                        @if($ticket->email)
                                            <span class="flex items-center gap-1 text-blue-600 dark:text-blue-400">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                                {{ $ticket->email }}
                                            </span>
                                        @endif
                                        @if($ticket->telefono)
                                            <span class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                </svg>
                                                {{ $ticket->telefono }}
                                            </span>
                                        @endif
                                        @if($ticket->website)
                                            <a href="{{ $ticket->website }}" target="_blank" class="flex items-center gap-1 text-walee-600 dark:text-walee-400 hover:underline">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                                </svg>
                                                Sitio web
                                            </a>
                                        @endif
                                        @php $archivos = getArchivos($ticket); @endphp
                                        @if(count($archivos) > 0)
                                            <span class="flex items-center gap-1 text-violet-600 dark:text-violet-400">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                {{ count($archivos) }} {{ count($archivos) === 1 ? 'archivo' : 'archivos' }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions Bar -->
                        <div class="px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-700/50 flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-slate-600 dark:text-slate-500">Cambiar estado:</span>
                                <div class="flex gap-1">
                                    <button onclick="changeStatus({{ $ticket->id }}, 'enviado')" class="px-3 py-1.5 text-xs rounded-lg transition-all bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-400 hover:bg-amber-100 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400">
                                        Enviado
                                    </button>
                                    <button onclick="changeStatus({{ $ticket->id }}, 'recibido')" class="px-3 py-1.5 text-xs rounded-lg transition-all bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-400 hover:bg-blue-100 dark:hover:bg-blue-500/20 hover:text-blue-600 dark:hover:text-blue-400">
                                        Recibido
                                    </button>
                                    <button onclick="changeStatus({{ $ticket->id }}, 'resuelto')" class="px-3 py-1.5 text-xs rounded-lg transition-all bg-emerald-500 text-white">
                                        Resuelto
                                    </button>
                                </div>
                            </div>
                            
                            <div class="flex gap-2">
                                @php $archivos = getArchivos($ticket); @endphp
                                @if(count($archivos) > 0)
                                    @if(count($archivos) === 1)
                                        <a href="{{ asset('storage/' . $archivos[0]) }}" target="_blank" class="px-3 py-1.5 text-xs bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-800 dark:text-white rounded-lg transition-all flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            Ver
                                        </a>
                                    @else
                                        <div class="px-3 py-1.5 text-xs bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-white rounded-lg flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ count($archivos) }} archivos
                                        </div>
                                    @endif
                                @endif
                                <button onclick="deleteTicket({{ $ticket->id }})" class="px-3 py-1.5 text-xs bg-red-500 hover:bg-red-400 text-white rounded-lg transition-all flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Eliminar
                                </button>
                                <button onclick="showTicketDetail({{ $ticket->id }})" class="px-3 py-1.5 text-xs bg-walee-500 hover:bg-walee-400 text-white rounded-lg transition-all">
                                    Detalle
                                </button>
                            </div>
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
            </div>
            
            <!-- Footer -->
            <footer class="text-center py-8 mt-4">
                <p class="text-sm text-slate-600 dark:text-slate-500">
                    <span class="text-walee-600 dark:text-walee-400 font-medium">Walee</span> · websolutions.work
                </p>
            </footer>
        </div>
    </div>
    
    <!-- Ticket Detail Modal -->
    <div id="ticketModal" class="fixed inset-0 bg-black/80 dark:bg-black/90 backdrop-blur-sm z-[9999] hidden flex items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 max-w-2xl w-full max-h-[90vh] overflow-hidden shadow-xl">
            <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white" id="modalTitle">Ticket</h3>
                <button onclick="closeModal()" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-4 overflow-y-auto max-h-[70vh]" id="modalContent">
                <!-- Content will be inserted here -->
            </div>
        </div>
    </div>
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const ticketsData = @json($tickets);
        
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
        
        function showTicketDetail(ticketId) {
            const ticket = ticketsData.find(t => t.id === ticketId);
            if (!ticket) return;
            
            const createdAt = new Date(ticket.created_at).toLocaleString('es-ES', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            document.getElementById('modalTitle').textContent = `Ticket #${ticket.id}`;
            document.getElementById('modalContent').innerHTML = `
                <div class="space-y-4">
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-4 border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs px-2 py-1 rounded-full
                                ${ticket.estado === 'enviado' ? 'bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400' : ''}
                                ${ticket.estado === 'recibido' ? 'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400' : ''}
                                ${ticket.estado === 'resuelto' ? 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400' : ''}">
                                ${ticket.estado.charAt(0).toUpperCase() + ticket.estado.slice(1)}
                            </span>
                            <span class="text-xs text-slate-600 dark:text-slate-500">${createdAt}</span>
                        </div>
                        <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">${ticket.asunto}</h4>
                        ${ticket.name ? `<p class="text-sm text-walee-600 dark:text-walee-400 mb-1"><strong>Empresa:</strong> ${ticket.name}</p>` : ''}
                        ${ticket.email ? `<p class="text-sm text-blue-600 dark:text-blue-400 mb-1"><strong>Email:</strong> ${ticket.email}</p>` : ''}
                        ${ticket.website ? `<p class="text-sm text-slate-600 dark:text-slate-400 mb-1"><strong>Web:</strong> <a href="${ticket.website}" target="_blank" class="text-walee-600 dark:text-walee-400 hover:underline">${ticket.website}</a></p>` : ''}
                        ${ticket.user && !ticket.name ? `<p class="text-sm text-slate-600 dark:text-slate-400">De: ${ticket.user.name} (${ticket.user.email})</p>` : ''}
                    </div>
                    
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-4 border border-slate-200 dark:border-slate-700">
                        <h5 class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Mensaje</h5>
                        <p class="text-slate-900 dark:text-white whitespace-pre-wrap">${ticket.mensaje}</p>
                    </div>
                    
                    ${ticket.imagen ? (() => {
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
                        
                        if (archivos.length === 0) return '';
                        
                        return `
                            <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-4 border border-slate-200 dark:border-slate-700">
                                <h5 class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">
                                    ${archivos.length === 1 ? 'Archivo adjunto' : archivos.length + ' archivos adjuntos'}
                                </h5>
                                <div class="space-y-3">
                                    ${archivos.map((archivo, idx) => {
                                        const isImage = /\.(jpg|jpeg|png|gif|webp)$/i.test(archivo);
                                        return `
                                            <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-3">
                                                ${isImage ? `
                                                    <a href="/storage/${archivo}" target="_blank" class="block">
                                                        <img src="/storage/${archivo}" alt="Archivo ${idx + 1}" class="rounded-lg max-h-64 object-contain mx-auto border border-slate-300 dark:border-slate-700 hover:border-walee-500 dark:hover:border-walee-500 transition-colors">
                                                    </a>
                                                ` : `
                                                    <a href="/storage/${archivo}" target="_blank" class="flex items-center gap-2 text-walee-600 dark:text-walee-400 hover:text-walee-500 dark:hover:text-walee-300">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                        <span>Ver archivo ${idx + 1}</span>
                                                    </a>
                                                `}
                                            </div>
                                        `;
                                    }).join('')}
                                </div>
                            </div>
                        `;
                    })() : ''}
                </div>
            `;
            document.getElementById('ticketModal').classList.remove('hidden');
        }
        
        function closeModal() {
            document.getElementById('ticketModal').classList.add('hidden');
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
        
        // Close modal on backdrop click
        document.getElementById('ticketModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
        
        // Close modal on Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeModal();
        });
    </script>
    @include('partials.walee-support-button')
</body>
</html>

