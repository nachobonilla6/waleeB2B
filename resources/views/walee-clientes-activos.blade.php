<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Clientes Activos</title>
    <meta name="description" content="Walee - Clientes Activos">
    <meta name="theme-color" content="#D59F3B">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
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
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }
        
        .client-card {
            opacity: 0;
            animation: fadeInUp 0.4s ease-out forwards;
        }
        
        .client-card:nth-child(1) { animation-delay: 0.05s; }
        .client-card:nth-child(2) { animation-delay: 0.1s; }
        .client-card:nth-child(3) { animation-delay: 0.15s; }
        .client-card:nth-child(4) { animation-delay: 0.2s; }
        .client-card:nth-child(5) { animation-delay: 0.25s; }
        .client-card:nth-child(6) { animation-delay: 0.3s; }
        .client-card:nth-child(7) { animation-delay: 0.35s; }
        .client-card:nth-child(8) { animation-delay: 0.4s; }
        .client-card:nth-child(9) { animation-delay: 0.45s; }
        .client-card:nth-child(10) { animation-delay: 0.5s; }
        
        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(213, 159, 59, 0.3);
            border-radius: 3px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(213, 159, 59, 0.5);
        }
    </style>
</head>
<body class="bg-slate-950 text-white min-h-screen">
    @php
        use App\Models\Client;
        
        $clientes = Client::where('estado', 'accepted')
            ->orderBy('created_at', 'desc')
            ->get();
    @endphp

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 -left-20 w-60 h-60 bg-walee-400/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-emerald-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-4xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <!-- Header -->
            <header class="flex items-center justify-between mb-6 animate-fade-in-up">
                <div class="flex items-center gap-4">
                    <a href="{{ route('walee.clientes') }}" class="w-10 h-10 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 flex items-center justify-center transition-all">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-emerald-400 animate-pulse"></span>
                            Clientes Activos
                        </h1>
                        <p class="text-sm text-slate-400">{{ $clientes->count() }} clientes con estado aceptado</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('walee.dashboard') }}" class="hidden sm:flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 transition-all duration-300 border border-slate-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="text-sm font-medium">Dashboard</span>
                    </a>
                </div>
            </header>
            
            <!-- Search Bar -->
            <div class="mb-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="relative">
                    <input 
                        type="text" 
                        id="searchInput"
                        placeholder="Buscar cliente por nombre, email o teléfono..."
                        class="w-full px-4 py-3 pl-12 rounded-2xl bg-slate-900/80 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500/50 focus:ring-2 focus:ring-emerald-500/20 transition-all"
                    >
                    <svg class="w-5 h-5 text-slate-500 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
            
            <!-- Clients List -->
            <div class="space-y-3" id="clientsList">
                @forelse($clientes as $cliente)
                    <div class="client-card group relative overflow-hidden rounded-2xl bg-slate-900/60 border border-slate-800 hover:border-emerald-500/30 transition-all duration-300" data-search="{{ strtolower($cliente->name . ' ' . $cliente->email . ' ' . $cliente->phone . ' ' . $cliente->telefono_1) }}">
                        <a href="/admin/clientes-en-proceso/{{ $cliente->id }}" class="block p-4 sm:p-5">
                            <div class="flex items-start gap-4">
                                <!-- Avatar -->
                                <div class="flex-shrink-0">
                                    @if($cliente->foto)
                                        <img src="{{ $cliente->foto }}" alt="{{ $cliente->name }}" class="w-14 h-14 rounded-xl object-cover border-2 border-emerald-500/30">
                                    @else
                                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-emerald-500/20 to-emerald-600/10 border border-emerald-500/20 flex items-center justify-center">
                                            <span class="text-xl font-bold text-emerald-400">{{ strtoupper(substr($cliente->name, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            <h3 class="text-lg font-semibold text-white truncate group-hover:text-emerald-300 transition-colors">
                                                {{ $cliente->name }}
                                            </h3>
                                            @if($cliente->email)
                                                <p class="text-sm text-slate-400 truncate flex items-center gap-1.5 mt-1">
                                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                    </svg>
                                                    {{ $cliente->email }}
                                                </p>
                                            @endif
                                        </div>
                                        <span class="flex-shrink-0 px-2.5 py-1 text-xs font-medium bg-emerald-500/20 text-emerald-400 rounded-full border border-emerald-500/30">
                                            Activo
                                        </span>
                                    </div>
                                    
                                    <div class="flex flex-wrap items-center gap-3 mt-3">
                                        @if($cliente->phone || $cliente->telefono_1)
                                            <span class="text-xs text-slate-500 flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                </svg>
                                                {{ $cliente->phone ?: $cliente->telefono_1 }}
                                            </span>
                                        @endif
                                        
                                        @if($cliente->website)
                                            <span class="text-xs text-slate-500 flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                                </svg>
                                                {{ parse_url($cliente->website, PHP_URL_HOST) ?: $cliente->website }}
                                            </span>
                                        @endif
                                        
                                        <span class="text-xs text-slate-600 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $cliente->created_at->format('d/m/Y') }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Arrow -->
                                <div class="flex-shrink-0 hidden sm:flex w-8 h-8 rounded-lg bg-slate-800 items-center justify-center group-hover:bg-emerald-500/20 transition-colors">
                                    <svg class="w-4 h-4 text-slate-500 group-hover:text-emerald-400 transform group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="text-center py-16 animate-fade-in-up">
                        <div class="w-20 h-20 rounded-2xl bg-slate-800 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-400 mb-2">No hay clientes activos</h3>
                        <p class="text-sm text-slate-600">Los clientes con estado "accepted" aparecerán aquí</p>
                    </div>
                @endforelse
            </div>
            
            <!-- No Results Message -->
            <div id="noResults" class="hidden text-center py-12">
                <div class="w-16 h-16 rounded-2xl bg-slate-800 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <p class="text-slate-500">No se encontraron resultados</p>
            </div>
            
            <!-- Footer -->
            <footer class="text-center py-8 mt-6">
                <p class="text-sm text-slate-500">
                    <span class="text-walee-400 font-medium">Walee</span> · wesolutions.work
                </p>
            </footer>
        </div>
    </div>

    <script>
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const clientsList = document.getElementById('clientsList');
        const noResults = document.getElementById('noResults');
        const cards = clientsList.querySelectorAll('.client-card');
        
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            let visibleCount = 0;
            
            cards.forEach(card => {
                const searchText = card.dataset.search || '';
                const matches = searchText.includes(query);
                card.style.display = matches ? 'block' : 'none';
                if (matches) visibleCount++;
            });
            
            noResults.classList.toggle('hidden', visibleCount > 0 || query === '');
        });
    </script>
</body>
</html>

