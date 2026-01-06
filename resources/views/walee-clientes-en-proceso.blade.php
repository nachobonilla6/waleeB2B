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
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 -left-20 w-60 h-60 bg-walee-400/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-emerald-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-2.5 py-2.5 sm:px-4 sm:py-6 lg:px-8">
            @php $pageTitle = 'Clientes en Proceso'; @endphp
            @include('partials.walee-navbar')
            
            <!-- Search Bar and Delete Actions -->
            <div class="mb-3 sm:mb-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="flex items-center gap-2 sm:gap-3 mb-2 sm:mb-3">
                    <div class="relative flex-1">
                        <input 
                            type="text" 
                            id="searchInput"
                            placeholder="Buscar cliente por nombre o teléfono..."
                            class="w-full px-3 sm:px-4 py-2 sm:py-3 pl-10 sm:pl-12 rounded-xl sm:rounded-2xl bg-slate-100 dark:bg-slate-900/80 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-emerald-500/50 focus:ring-2 focus:ring-emerald-500/20 transition-all text-xs sm:text-sm"
                        >
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-slate-400 dark:text-slate-500 absolute left-3 sm:left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <button 
                        id="actionsMenuBtn"
                        onclick="toggleActionsMenu()"
                        class="px-2.5 sm:px-4 py-2 sm:py-3 rounded-xl sm:rounded-2xl bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium transition-all flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm"
                    >
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                        </svg>
                        <span class="hidden sm:inline">Acciones</span>
                    </button>
                    <button 
                        id="deleteSelectedBtn"
                        onclick="deleteSelectedClients()"
                        class="hidden px-2.5 sm:px-4 py-2 sm:py-3 rounded-xl sm:rounded-2xl bg-red-600 hover:bg-red-700 text-white font-medium transition-all flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm"
                    >
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <span id="deleteCount">Borrar (0)</span>
                    </button>
                </div>
                <!-- Actions Menu (hidden by default) -->
                <div id="actionsMenu" class="hidden mb-2 sm:mb-3 p-2.5 sm:p-4 rounded-xl sm:rounded-2xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 shadow-lg">
                    <div class="flex items-center gap-2">
                        <label class="flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm text-slate-700 dark:text-slate-300 cursor-pointer">
                            <input 
                                type="checkbox" 
                                id="selectAll"
                                onchange="toggleSelectAll(this.checked)"
                                class="w-3.5 h-3.5 sm:w-4 sm:h-4 rounded border-slate-300 dark:border-slate-600 text-emerald-500 focus:ring-emerald-500 focus:ring-offset-0"
                            >
                            <span>Seleccionar todos</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Clients List -->
            <div class="space-y-2 sm:space-y-3" id="clientsList">
                @forelse($clientes as $cliente)
                    @php
                        $phone = $cliente->telefono_1 ?: $cliente->telefono_2;
                        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                        $whatsappLink = $cleanPhone ? "https://wa.me/{$cleanPhone}" : null;
                        
                        // Obtener contador de propuestas
                        $propuestasCount = $propuestasPorCliente[$cliente->id] ?? 0;
                        $propuestasColor = $propuestasCount >= 3 ? 'bg-red-500' : ($propuestasCount >= 1 ? 'bg-emerald-500' : 'bg-slate-600');
                        $propuestasBorder = $propuestasCount >= 3 ? 'border-red-500/30' : ($propuestasCount >= 1 ? 'border-emerald-500/30' : 'border-slate-600/30');
                    @endphp
                    <div class="client-card group" data-search="{{ strtolower($cliente->name . ' ' . $phone) }}" data-client-id="{{ $cliente->id }}">
                        <div class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 hover:border-emerald-400/50 dark:hover:border-emerald-500/40 transition-all duration-300 p-2.5 sm:p-4">
                            <div class="flex items-center gap-2 sm:gap-3 lg:gap-4">
                                <!-- Checkbox -->
                                <input 
                                    type="checkbox" 
                                    class="client-checkbox w-4 h-4 sm:w-5 sm:h-5 rounded border-slate-300 dark:border-slate-600 text-emerald-500 focus:ring-emerald-500 focus:ring-offset-0 cursor-pointer hidden"
                                    data-client-id="{{ $cliente->id }}"
                                    data-client-name="{{ $cliente->name }}"
                                    onchange="updateDeleteButton()"
                                >
                                
                                <!-- Avatar + Name (clickable) -->
                                <a href="{{ route('walee.cliente.detalle', $cliente->id) }}" class="flex items-center gap-2 sm:gap-3 lg:gap-4 flex-1 min-w-0">
                                    <div class="flex-shrink-0">
                                        @php
                                            $fotoPath = $cliente->foto ?? null;
                                            $fotoUrl = null;
                                            
                                            if ($fotoPath) {
                                                if (\Illuminate\Support\Str::startsWith($fotoPath, ['http://', 'https://'])) {
                                                    $fotoUrl = $fotoPath;
                                                } else {
                                                    $filename = basename($fotoPath);
                                                    $fotoUrl = route('storage.clientes', ['filename' => $filename]);
                                                }
                                            }
                                        @endphp
                                        @if($fotoUrl)
                                            <img src="{{ $fotoUrl }}" alt="{{ $cliente->name }}" class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 rounded-lg sm:rounded-xl object-cover border-2 border-emerald-500/30 group-hover:border-emerald-400/50 transition-all">
                                        @else
                                            <img src="https://img.icons8.com/color/1200/my-bussiness.jpg" alt="{{ $cliente->name }}" class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 rounded-lg sm:rounded-xl object-cover border-2 border-emerald-500/30 group-hover:border-emerald-400/40 transition-all opacity-80">
                                        @endif
                                    </div>
                                    
                                    <!-- Name & Estado -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap">
                                            <h3 class="text-sm sm:text-base font-semibold text-slate-800 dark:text-white truncate group-hover:text-emerald-600 dark:group-hover:text-emerald-300 transition-colors">
                                                {{ $cliente->name }}
                                            </h3>
                                            @if($propuestasCount > 0)
                                                <span class="flex-shrink-0 px-1.5 sm:px-2 py-0.5 text-[10px] sm:text-xs font-bold {{ $propuestasColor }} text-white rounded-full border {{ $propuestasBorder }}">
                                                    {{ $propuestasCount }} {{ $propuestasCount == 1 ? 'email' : 'emails' }}
                                                </span>
                                            @endif
                                        </div>
                                        @if($cliente->email)
                                            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 truncate mt-0.5 sm:mt-1">
                                                {{ $cliente->email }}
                                            </p>
                                        @endif
                                        @if($cliente->ciudad)
                                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-500 truncate mt-0.5">
                                                📍 {{ $cliente->ciudad }}
                                            </p>
                                        @endif
                                        @if($cliente->idioma)
                                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-500 truncate mt-0.5">
                                                @php
                                                    $idiomas = [
                                                        'es' => '🇪🇸 Español',
                                                        'en' => '🇬🇧 English',
                                                        'fr' => '🇫🇷 Français',
                                                        'de' => '🇩🇪 Deutsch',
                                                        'it' => '🇮🇹 Italiano',
                                                        'pt' => '🇵🇹 Português'
                                                    ];
                                                    echo $idiomas[$cliente->idioma] ?? strtoupper($cliente->idioma);
                                                @endphp
                                            </p>
                                        @endif
                                        <div class="flex items-center gap-1.5 sm:gap-2 mt-0.5 sm:mt-1 flex-wrap">
                                            @if($cliente->estado == 'pending' && $cliente->created_at->isToday())
                                                <span class="inline-block px-1.5 sm:px-2 py-0.5 text-[10px] sm:text-xs font-medium rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 whitespace-nowrap">
                                                    Enviados
                                                </span>
                                            @elseif($cliente->estado == 'received' && $cliente->created_at->isToday())
                                                <span class="inline-block px-1.5 sm:px-2 py-0.5 text-[10px] sm:text-xs font-medium rounded-full bg-blue-500/20 text-blue-400 border border-blue-500/30 whitespace-nowrap">
                                                    Emails pendientes
                                                </span>
                                            @else
                                                <span class="inline-block px-1.5 sm:px-2 py-0.5 text-[10px] sm:text-xs font-medium rounded-full bg-slate-500/20 text-slate-400 border border-slate-500/30 whitespace-nowrap">
                                                    {{ ucfirst($cliente->estado ?? 'Sin estado') }}
                                                </span>
                                            @endif
                                            @if($propuestasCount > 0)
                                                <span class="text-[10px] sm:text-xs text-slate-600 dark:text-slate-500">
                                                    @if($propuestasCount >= 3)
                                                        ⚠️ Múltiples propuestas
                                                    @else
                                                        ✉️ Propuesta enviada
                                                    @endif
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                                
                                <!-- Email Counter -->
                                <div class="flex-shrink-0 flex items-center gap-1.5 sm:gap-2 mr-1 sm:mr-2">
                                    <div class="bg-blue-100 dark:bg-blue-500/20 border border-blue-200 dark:border-blue-500/30 rounded-lg sm:rounded-xl px-2 sm:px-2.5 py-1 sm:py-1.5 flex items-center gap-1 sm:gap-1.5">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-xs sm:text-sm font-semibold text-blue-600 dark:text-blue-400">{{ $cliente->emails_count ?? 0 }}</span>
                                    </div>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex items-center gap-1.5 sm:gap-2 flex-shrink-0">
                                    <!-- Email with AI Button -->
                                    <a href="{{ route('walee.emails.crear') }}?cliente_id={{ $cliente->id }}" class="flex-shrink-0 inline-flex items-center justify-center gap-1 sm:gap-2 px-2 sm:px-3 lg:px-4 py-1.5 sm:py-2 lg:py-2.5 rounded-lg sm:rounded-xl bg-walee-500/20 hover:bg-walee-500/30 text-walee-400 hover:text-walee-300 border border-walee-500/30 hover:border-walee-400/50 transition-all duration-300 group/email">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                        </svg>
                                        <span class="text-xs sm:text-sm font-medium hidden lg:inline">Email AI</span>
                                    </a>
                                    
                                    <!-- WhatsApp Button -->
                                    @if($whatsappLink)
                                        <a href="{{ $whatsappLink }}" target="_blank" rel="noopener noreferrer" class="flex-shrink-0 inline-flex items-center justify-center gap-1 sm:gap-2 px-2 sm:px-3 lg:px-4 py-1.5 sm:py-2 lg:py-2.5 rounded-lg sm:rounded-xl bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-400 hover:text-emerald-300 border border-emerald-500/30 hover:border-emerald-400/50 transition-all duration-300">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                            </svg>
                                            <span class="text-xs sm:text-sm font-medium hidden sm:inline">WhatsApp</span>
                                        </a>
                                    @else
                                        <div class="flex-shrink-0 inline-flex items-center justify-center gap-1 sm:gap-2 px-2 sm:px-3 lg:px-4 py-1.5 sm:py-2 lg:py-2.5 rounded-lg sm:rounded-xl bg-slate-800/50 text-slate-500 border border-slate-700">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                            </svg>
                                            <span class="text-xs sm:text-sm hidden sm:inline">Sin teléfono</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 animate-fade-in-up">
                        <div class="w-20 h-20 rounded-2xl bg-slate-800 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-400 mb-2">No hay clientes en proceso</h3>
                        <p class="text-sm text-slate-600">Los clientes en seguimiento aparecerán aquí</p>
                    </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            @if($clientes->hasPages())
                <div class="mt-4 sm:mt-8 flex justify-center gap-1.5 sm:gap-2 flex-wrap">
                    @if($clientes->onFirstPage())
                        <span class="px-3 sm:px-4 py-1.5 sm:py-2 bg-slate-100 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 rounded-lg sm:rounded-xl cursor-not-allowed text-xs sm:text-sm">Anterior</span>
                    @else
                        <a href="{{ $clientes->previousPageUrl() }}" class="px-3 sm:px-4 py-1.5 sm:py-2 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-900 dark:text-white rounded-lg sm:rounded-xl transition-colors border border-slate-200 dark:border-slate-700 shadow-sm dark:shadow-none text-xs sm:text-sm">Anterior</a>
                    @endif
                    
                    <span class="px-3 sm:px-4 py-1.5 sm:py-2 bg-slate-100 dark:bg-slate-800/50 text-slate-600 dark:text-slate-400 rounded-lg sm:rounded-xl border border-slate-200 dark:border-slate-700 text-xs sm:text-sm">
                        Página {{ $clientes->currentPage() }} de {{ $clientes->lastPage() }}
                    </span>
                    
                    @if($clientes->hasMorePages())
                        <a href="{{ $clientes->nextPageUrl() }}" class="px-3 sm:px-4 py-1.5 sm:py-2 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-900 dark:text-white rounded-lg sm:rounded-xl transition-colors border border-slate-200 dark:border-slate-700 shadow-sm dark:shadow-none text-xs sm:text-sm">Siguiente</a>
                    @else
                        <span class="px-3 sm:px-4 py-1.5 sm:py-2 bg-slate-100 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 rounded-lg sm:rounded-xl cursor-not-allowed text-xs sm:text-sm">Siguiente</span>
                    @endif
                </div>
            @endif
            
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
            <footer class="text-center py-4 sm:py-8 mt-4 sm:mt-6">
                <p class="text-xs sm:text-sm text-slate-500">
                    <span class="text-walee-400 font-medium">Walee</span> · websolutions.work
                </p>
            </footer>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
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

