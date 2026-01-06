<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Clientes Pendientes</title>
    <meta name="description" content="Walee - Clientes Pendientes">
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
        
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(213, 159, 59, 0.3); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(213, 159, 59, 0.5); }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">
    @php
        use App\Models\Client;
        use App\Models\PropuestaPersonalizada;
        
        $clientes = Client::whereIn('estado', ['pending', 'received'])
            ->withCount('emails')
            ->orderBy('updated_at', 'desc')
            ->paginate(5);
        
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
            @php $pageTitle = 'Clientes en Proceso'; @endphp
            @include('partials.walee-navbar')
            
            <!-- Header -->
            <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-0 mb-3 sm:mb-4">
                <div>
                    <h1 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white">
                        Clientes en Proceso
                    </h1>
                </div>
                <div class="flex items-center gap-2">
                    <button 
                        id="actionsMenuBtn"
                        onclick="toggleActionsMenu()"
                        class="px-3 py-1.5 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white font-medium rounded-lg transition-all flex items-center gap-1.5 text-xs"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                        </svg>
                        <span>Acciones</span>
                    </button>
                    <button 
                        id="deleteSelectedBtn"
                        onclick="deleteSelectedClients()"
                        class="hidden px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-all flex items-center gap-1.5 text-xs shadow-sm"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <span id="deleteCount">Borrar (0)</span>
                    </button>
                </div>
            </header>
            
            <!-- Actions Menu (hidden by default) -->
            <div id="actionsMenu" class="hidden mb-3 bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-3 shadow-sm">
                <div class="flex items-center gap-2">
                    <label class="flex items-center gap-1.5 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input 
                            type="checkbox" 
                            id="selectAll"
                            onchange="toggleSelectAll(this.checked)"
                            class="w-3.5 h-3.5 rounded border-slate-300 dark:border-slate-600 text-emerald-500 focus:ring-emerald-500 focus:ring-offset-0"
                        >
                        <span>Seleccionar todos</span>
                    </label>
                </div>
            </div>
            
            <!-- Search Bar -->
            <div class="mb-3">
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-3 shadow-sm">
                    <div class="flex gap-2 items-center">
                        <div class="relative flex-1">
                            <input 
                                type="text" 
                                id="searchInput"
                                placeholder="Buscar por nombre, email o teléfono..."
                                class="w-full px-3 py-2 pl-9 rounded-lg bg-slate-100 dark:bg-slate-900/80 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500/50 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm"
                            >
                            <svg class="w-4 h-4 text-slate-400 dark:text-slate-500 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Clientes List -->
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-3 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">
                        Clientes en Proceso
                        <span class="text-xs font-normal text-slate-500 dark:text-slate-400">
                            ({{ $clientes->total() }})
                        </span>
                    </h2>
                </div>
                
                <div class="grid grid-cols-1 gap-3" id="clientsList">
                @forelse($clientes as $cliente)
                    @php
                        $phone = $cliente->telefono_1 ?: $cliente->telefono_2;
                        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                        $whatsappLink = $cleanPhone ? "https://wa.me/{$cleanPhone}" : null;
                        
                        // Obtener URL de la foto del cliente
                        $fotoUrl = null;
                        if ($cliente->foto) {
                            $fotoPath = $cliente->foto;
                            if (\Illuminate\Support\Str::startsWith($fotoPath, ['http://', 'https://'])) {
                                $fotoUrl = $fotoPath;
                            } else {
                                $filename = basename($fotoPath);
                                $fotoUrl = route('storage.clientes', ['filename' => $filename]);
                            }
                        }
                        
                        // Bandera del idioma
                        $banderas = [
                            'es' => '🇪🇸',
                            'en' => '🇬🇧',
                            'fr' => '🇫🇷',
                            'de' => '🇩🇪',
                            'it' => '🇮🇹',
                            'pt' => '🇵🇹'
                        ];
                        $bandera = ($cliente->idioma && !$fotoUrl) ? ($banderas[$cliente->idioma] ?? '') : '';
                    @endphp
                    <div class="client-card group" data-search="{{ strtolower($cliente->name . ' ' . ($phone ?? '') . ' ' . ($cliente->email ?? '')) }}" data-client-id="{{ $cliente->id }}">
                        <div class="relative bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-4 hover:border-violet-400 dark:hover:border-violet-500/30 hover:shadow-md dark:hover:shadow-lg transition-all">
                            <div class="flex items-start gap-4">
                                <!-- Checkbox -->
                                <input 
                                    type="checkbox" 
                                    class="client-checkbox w-4 h-4 rounded border-slate-300 dark:border-slate-600 text-emerald-500 focus:ring-emerald-500 focus:ring-offset-0 cursor-pointer hidden mt-1"
                                    data-client-id="{{ $cliente->id }}"
                                    data-client-name="{{ $cliente->name }}"
                                    onchange="updateDeleteButton()"
                                >
                                
                                <!-- Avatar/Icon -->
                                <div class="w-12 h-12 rounded-xl bg-violet-100 dark:bg-violet-500/20 flex-shrink-0 flex items-center justify-center border border-violet-500/30 overflow-hidden">
                                    @if($fotoUrl)
                                        <img src="{{ $fotoUrl }}" alt="{{ $cliente->name }}" class="w-full h-full object-cover rounded-xl">
                                    @elseif($bandera)
                                        <span class="text-xl">{{ $bandera }}</span>
                                    @else
                                        <svg class="w-6 h-6 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    @endif
                                </div>
                                
                                <!-- Content -->
                                <a href="{{ route('walee.cliente.detalle', $cliente->id) }}" class="flex-1 min-w-0">
                                    <div class="flex-1 min-w-0">
                                        <!-- Header: Name -->
                                        <div class="flex items-center gap-2 mb-2">
                                            @if($bandera)
                                                <span class="text-base flex-shrink-0">{{ $bandera }}</span>
                                            @endif
                                            <h3 class="font-semibold text-base text-slate-900 dark:text-white truncate group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">
                                                {{ $cliente->name ?: 'Sin nombre' }}
                                            </h3>
                                        </div>
                                        
                                        <!-- Details Grid -->
                                        <div class="space-y-1.5">
                                            @if($cliente->email)
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-3.5 h-3.5 text-slate-400 dark:text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                    </svg>
                                                    <span class="text-xs text-slate-600 dark:text-slate-400 truncate">{{ $cliente->email }}</span>
                                                </div>
                                            @endif
                                            
                                            @if($phone)
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-3.5 h-3.5 text-slate-400 dark:text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                    </svg>
                                                    <span class="text-xs text-slate-500 dark:text-slate-500 truncate">{{ $phone }}</span>
                                                </div>
                                            @endif
                                            
                                            @if($cliente->website)
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-3.5 h-3.5 text-slate-400 dark:text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                                    </svg>
                                                    <span class="text-xs text-slate-500 dark:text-slate-500 truncate">{{ $cliente->website }}</span>
                                                </div>
                                            @endif
                                            
                                            <div class="flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 text-slate-400 dark:text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span class="text-xs text-slate-500 dark:text-slate-500">{{ $cliente->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                
                                <!-- Email Counter -->
                                <div class="flex-shrink-0 flex items-center gap-1.5">
                                    <div class="bg-blue-100 dark:bg-blue-500/20 border border-blue-200 dark:border-blue-500/30 rounded-lg px-2.5 py-1.5 flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">{{ $cliente->emails_count ?? 0 }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Buttons Row -->
                            <div class="flex items-center gap-2 mt-3 pt-3 border-t border-slate-200 dark:border-slate-700">
                                <!-- Email with AI Button -->
                                <a href="{{ route('walee.emails.crear') }}?cliente_id={{ $cliente->id }}" class="flex-1 px-3 py-1.5 bg-walee-500/20 hover:bg-walee-500/30 text-walee-400 hover:text-walee-300 border border-walee-500/30 hover:border-walee-400/50 rounded-lg transition-all flex items-center justify-center gap-1.5 text-xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                    <span>Email AI</span>
                                </a>
                                
                                <!-- WhatsApp Button -->
                                @if($whatsappLink)
                                    <a href="{{ $whatsappLink }}" target="_blank" rel="noopener noreferrer" class="flex-1 px-3 py-1.5 bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-400 hover:text-emerald-300 border border-emerald-500/30 hover:border-emerald-400/50 rounded-lg transition-all flex items-center justify-center gap-1.5 text-xs">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                        </svg>
                                        <span>WhatsApp</span>
                                    </a>
                                @else
                                    <div class="flex-1 px-3 py-1.5 bg-slate-800/50 text-slate-500 border border-slate-700 rounded-lg flex items-center justify-center gap-1.5 text-xs">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                        <span>Sin teléfono</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl">
                        <p class="text-sm text-slate-500 dark:text-slate-400">No se encontraron clientes en proceso</p>
                    </div>
                @endforelse
                </div>
                
                <!-- Pagination -->
                @if($clientes->hasPages())
                    <div class="mt-4 flex justify-center gap-2">
                        @if($clientes->onFirstPage())
                            <span class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 rounded-md cursor-not-allowed text-xs">Anterior</span>
                        @else
                            <a href="{{ $clientes->previousPageUrl() }}" class="px-3 py-1.5 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-900 dark:text-white rounded-md transition-colors border border-slate-200 dark:border-slate-700 text-xs">Anterior</a>
                        @endif
                        
                        <span class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800/50 text-slate-600 dark:text-slate-400 rounded-md border border-slate-200 dark:border-slate-700 text-xs">
                            Página {{ $clientes->currentPage() }} de {{ $clientes->lastPage() }}
                        </span>
                        
                        @if($clientes->hasMorePages())
                            <a href="{{ $clientes->nextPageUrl() }}" class="px-3 py-1.5 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-900 dark:text-white rounded-md transition-colors border border-slate-200 dark:border-slate-700 text-xs">Siguiente</a>
                        @else
                            <span class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 rounded-md cursor-not-allowed text-xs">Siguiente</span>
                        @endif
                    </div>
                @endif
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
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const searchInput = document.getElementById('searchInput');
        const clientsList = document.getElementById('clientsList');
        
        if (searchInput && clientsList) {
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
        
        // Toggle actions menu
        function toggleActionsMenu() {
            const menu = document.getElementById('actionsMenu');
            const checkboxes = document.querySelectorAll('.client-checkbox');
            
            if (menu) {
                const isHidden = menu.classList.contains('hidden');
                menu.classList.toggle('hidden');
                
                // Show/hide checkboxes based on menu state
                checkboxes.forEach(checkbox => {
                    if (isHidden) {
                        // Opening menu - show checkboxes
                        checkbox.classList.remove('hidden');
                    } else {
                        // Closing menu - hide checkboxes and uncheck them
                        checkbox.classList.add('hidden');
                        checkbox.checked = false;
                    }
                });
                
                // Update delete button and select all checkbox
                updateDeleteButton();
                const selectAllCheckbox = document.getElementById('selectAll');
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = false;
                }
            }
        }
        
        // Close actions menu when clicking outside
        document.addEventListener('click', function(e) {
            const menuBtn = document.getElementById('actionsMenuBtn');
            const menu = document.getElementById('actionsMenu');
            
            if (menu && menuBtn && !menu.contains(e.target) && !menuBtn.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });
        
        // Toggle select all
        function toggleSelectAll(checked) {
            const checkboxes = document.querySelectorAll('.client-checkbox');
            checkboxes.forEach(checkbox => {
                // Only check visible checkboxes
                const card = checkbox.closest('.client-card');
                if (card && card.style.display !== 'none') {
                    checkbox.checked = checked;
                }
            });
            updateDeleteButton();
        }
        
        // Update delete button visibility and count
        function updateDeleteButton() {
            const checkedBoxes = document.querySelectorAll('.client-checkbox:checked');
            const deleteBtn = document.getElementById('deleteSelectedBtn');
            const deleteCount = document.getElementById('deleteCount');
            
            if (!deleteBtn || !deleteCount) {
                return;
            }
            
            if (checkedBoxes.length > 0) {
                deleteBtn.classList.remove('hidden');
                deleteCount.textContent = `Borrar (${checkedBoxes.length})`;
            } else {
                deleteBtn.classList.add('hidden');
            }
        }
        
        // Delete selected clients
        async function deleteSelectedClients() {
            const checkedBoxes = document.querySelectorAll('.client-checkbox:checked');
            
            if (checkedBoxes.length === 0) {
                return;
            }
            
            const clientIds = Array.from(checkedBoxes).map(cb => cb.dataset.clientId);
            const clientNames = Array.from(checkedBoxes).map(cb => cb.dataset.clientName);
            
            const confirmMessage = clientIds.length === 1 
                ? `¿Estás seguro de que deseas borrar a ${clientNames[0]}?`
                : `¿Estás seguro de que deseas borrar ${clientIds.length} clientes?`;
            
            if (!confirm(confirmMessage)) {
                return;
            }
            
            const deleteBtn = document.getElementById('deleteSelectedBtn');
            deleteBtn.disabled = true;
            deleteBtn.innerHTML = `
                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Borrando...</span>
            `;
            
            try {
                const response = await fetch('{{ route("walee.clientes.en-proceso.delete") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        client_ids: clientIds
                    }),
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Remove deleted clients from DOM
                    checkedBoxes.forEach(checkbox => {
                        const card = checkbox.closest('.client-card');
                        if (card) {
                            card.style.transition = 'opacity 0.3s, transform 0.3s';
                            card.style.opacity = '0';
                            card.style.transform = 'translateX(-20px)';
                            setTimeout(() => card.remove(), 300);
                        }
                    });
                    
                    // Reset select all checkbox
                    const selectAllCheckbox = document.getElementById('selectAll');
                    if (selectAllCheckbox) {
                        selectAllCheckbox.checked = false;
                    }
                    updateDeleteButton();
                    
                    // Show success message
                    showNotification('Clientes borrados', `${clientIds.length} ${clientIds.length === 1 ? 'cliente ha sido' : 'clientes han sido'} borrado${clientIds.length === 1 ? '' : 's'} exitosamente`, 'success');
                } else {
                    showNotification('Error', data.message || 'Error al borrar clientes', 'error');
                }
            } catch (error) {
                showNotification('Error', 'Error de conexión: ' + error.message, 'error');
            } finally {
                deleteBtn.disabled = false;
                deleteBtn.innerHTML = `
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span id="deleteCount">Borrar (0)</span>
                `;
            }
        }
        
        // Show notification
        function showNotification(title, message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-2xl shadow-2xl transform transition-all ${
                type === 'success' ? 'bg-emerald-600' : 
                type === 'error' ? 'bg-red-600' : 
                'bg-blue-600'
            } text-white max-w-md`;
            notification.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="flex-1">
                        <p class="font-semibold">${title}</p>
                        <p class="text-sm opacity-90 mt-1">${message}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-white/70 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        }
    </script>
    @include('partials.walee-support-button')
</body>
</html>

