<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Clientes Activos</title>
    <meta name="description" content="Walee - Clientes Activos">
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
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white min-h-screen transition-colors duration-200">
    @php
        use App\Models\Client;
        use App\Models\PropuestaPersonalizada;
        
        $idiomaFilter = request()->get('idioma', '');
        
        $query = Client::where('estado', 'activo');
        
        // Aplicar filtro por idioma si existe
        if ($idiomaFilter) {
            $query->where('idioma', $idiomaFilter);
        }
        
        $clientes = $query->withCount('emails')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Obtener conteo de propuestas por cliente
        $propuestasPorCliente = PropuestaPersonalizada::selectRaw('cliente_id, COUNT(*) as total')
            ->whereNotNull('cliente_id')
            ->groupBy('cliente_id')
            ->pluck('total', 'cliente_id')
            ->toArray();
    @endphp

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-400/20 dark:bg-blue-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-walee-400/10 dark:bg-walee-400/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-blue-400/20 dark:bg-blue-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-3 py-4 sm:px-4 sm:py-6 lg:px-8">
            @php $pageTitle = 'Clientes Activos'; @endphp
            @include('partials.walee-navbar')
            
            <!-- Header -->
            <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-0 mb-3 sm:mb-4">
                <div>
                    <h1 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white">
                        Clientes Activos
                    </h1>
                </div>
            </header>
            
            <!-- Search Bar -->
            <div class="mb-3">
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-3 shadow-sm">
                    <div class="flex gap-2 items-center">
                        <form method="GET" action="{{ route('walee.clientes.activos') }}" class="flex gap-2 flex-1">
                            <div class="relative flex-1">
                                <input 
                                    type="text" 
                                    id="searchInput"
                                    name="search"
                                    value="{{ request()->get('search', '') }}"
                                    placeholder="Buscar por nombre, email o teléfono..."
                                    class="w-full px-3 py-2 pl-9 rounded-lg bg-slate-100 dark:bg-slate-900/80 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500/50 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm"
                                >
                                <svg class="w-4 h-4 text-slate-400 dark:text-slate-500 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <select name="idioma" onchange="this.form.submit()" class="px-3 py-2 rounded-lg bg-slate-100 dark:bg-slate-900/80 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-white focus:outline-none focus:border-blue-500/50 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm">
                                <option value="">Todos los idiomas</option>
                                <option value="es" {{ $idiomaFilter == 'es' ? 'selected' : '' }}>🇪🇸 Español</option>
                                <option value="en" {{ $idiomaFilter == 'en' ? 'selected' : '' }}>🇬🇧 English</option>
                                <option value="fr" {{ $idiomaFilter == 'fr' ? 'selected' : '' }}>🇫🇷 Français</option>
                                <option value="de" {{ $idiomaFilter == 'de' ? 'selected' : '' }}>🇩🇪 Deutsch</option>
                                <option value="it" {{ $idiomaFilter == 'it' ? 'selected' : '' }}>🇮🇹 Italiano</option>
                                <option value="pt" {{ $idiomaFilter == 'pt' ? 'selected' : '' }}>🇵🇹 Português</option>
                            </select>
                            @if(request()->get('search') || $idiomaFilter)
                                <a href="{{ route('walee.clientes.activos') }}" class="px-3 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white font-medium rounded-lg transition-all flex items-center gap-1.5 text-xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <span>Limpiar</span>
                                </a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Clientes List -->
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-3 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">
                        Clientes Activos
                        <span class="text-xs font-normal text-slate-500 dark:text-slate-400">
                            ({{ $clientes->count() }})
                        </span>
                    </h2>
                </div>
                
                <div class="space-y-2" id="clientsList">
                @forelse($clientes as $cliente)
                    @php
                        $phone = $cliente->phone ?: $cliente->telefono_1 ?: $cliente->telefono_2;
                        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                        $whatsappLink = $cleanPhone ? "https://wa.me/{$cleanPhone}" : null;
                        
                        // Bandera del idioma
                        $banderas = [
                            'es' => '🇪🇸',
                            'en' => '🇬🇧',
                            'fr' => '🇫🇷',
                            'de' => '🇩🇪',
                            'it' => '🇮🇹',
                            'pt' => '🇵🇹'
                        ];
                        $bandera = ($cliente->idioma) ? ($banderas[$cliente->idioma] ?? '') : '';
                    @endphp
                    <div class="client-card group" data-search="{{ strtolower($cliente->name . ' ' . ($phone ?? '') . ' ' . ($cliente->email ?? '')) }}">
                        <div class="flex items-center gap-2.5 p-2.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:border-blue-400 dark:hover:border-blue-500/30 hover:bg-blue-50/50 dark:hover:bg-blue-500/10 transition-all">
                            <!-- Icon -->
                            <div class="w-9 h-9 rounded-lg bg-blue-100 dark:bg-blue-500/20 flex-shrink-0 flex items-center justify-center border border-blue-500/30">
                                @if($bandera)
                                    <span class="text-lg">{{ $bandera }}</span>
                                @else
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                @endif
                            </div>
                            
                            <!-- Content -->
                            <a href="{{ route('walee.cliente.detalle', $cliente->id) }}" class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-0.5">
                                    <p class="font-medium text-sm text-slate-900 dark:text-white truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ $cliente->name }}</p>
                                </div>
                                @if($cliente->email)
                                    <p class="text-xs text-slate-600 dark:text-slate-400 truncate">{{ $cliente->email }}</p>
                                @endif
                                @if($phone)
                                    <p class="text-xs text-slate-500 dark:text-slate-500 truncate">{{ $phone }}</p>
                                @endif
                                <p class="text-xs text-slate-500 dark:text-slate-500 mt-0.5">{{ $cliente->created_at->diffForHumans() }}</p>
                            </a>
                            
                            <!-- Email Counter -->
                            <div class="flex-shrink-0 flex items-center gap-1.5">
                                <div class="bg-blue-100 dark:bg-blue-500/20 border border-blue-200 dark:border-blue-500/30 rounded-lg px-2 py-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-xs font-semibold text-blue-600 dark:text-blue-400">{{ $cliente->emails_count ?? 0 }}</span>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex-shrink-0 flex items-center gap-1.5">
                                @php
                                    // Buscar cliente en tabla clientes por email o nombre
                                    $clientePrincipal = \App\Models\Cliente::where('correo', $cliente->email)
                                        ->orWhere('nombre_empresa', 'like', '%' . $cliente->name . '%')
                                        ->first();
                                @endphp
                                @if($clientePrincipal)
                                    <!-- Planeador de Publicidad Button -->
                                    <a href="{{ route('walee.planeador.publicidad', $clientePrincipal->id) }}" class="p-1.5 rounded-md bg-violet-500/20 hover:bg-violet-500/30 text-violet-600 dark:text-violet-400 border border-violet-500/30 hover:border-violet-400/50 transition-all" title="Publicidad">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </a>
                                @endif
                                <!-- Email with AI Button -->
                                <a href="{{ route('walee.emails.crear') }}?cliente_id={{ $cliente->id }}" class="p-1.5 rounded-md bg-walee-500/20 hover:bg-walee-500/30 text-walee-600 dark:text-walee-400 border border-walee-500/30 hover:border-walee-400/50 transition-all" title="Email AI">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </a>
                                
                                <!-- WhatsApp Button -->
                                @if($whatsappLink)
                                    <a href="{{ $whatsappLink }}" target="_blank" rel="noopener noreferrer" class="p-1.5 rounded-md bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30 hover:border-emerald-400/50 transition-all" title="WhatsApp">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                        </svg>
                                    </a>
                                @else
                                    <div class="p-1.5 rounded-md bg-slate-800/50 text-slate-500 border border-slate-700" title="Sin teléfono">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-sm text-slate-500 dark:text-slate-400">No se encontraron clientes activos</p>
                    </div>
                @endforelse
                </div>
            </div>
            
            <!-- Footer -->
            <footer class="text-center py-4 sm:py-6 md:py-8 mt-4 sm:mt-6 md:mt-8">
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-500">
                    <span class="text-walee-600 dark:text-walee-400 font-medium">Walee</span> · websolutions.work
                </p>
            </footer>
        </div>
    </div>

    <script>
        // Search functionality - ahora se hace mediante el formulario
        const searchInput = document.getElementById('searchInput');
        const clientsList = document.getElementById('clientsList');
        const idiomaFilter = '{{ $idiomaFilter }}';
        
        // La búsqueda ahora se hace mediante el formulario, pero mantenemos la funcionalidad de búsqueda en tiempo real si no hay filtro de idioma
        if (searchInput && clientsList && !idiomaFilter) {
            const cards = clientsList.querySelectorAll('.client-card');
            
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                
                cards.forEach(card => {
                    const searchText = card.dataset.search || '';
                    const matches = searchText.includes(query);
                    card.style.display = matches ? 'block' : 'none';
                });
            });
        }
    </script>
    @include('partials.walee-support-button')
</body>
</html>

