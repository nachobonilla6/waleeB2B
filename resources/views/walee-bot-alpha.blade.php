<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Bot Alpha</title>
    <meta name="description" content="Walee - Administración Bot Alpha">
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
        
        // Definir openConfigModal inmediatamente en el head para que esté disponible cuando se renderiza el HTML
        window.openConfigModal = function() {
            console.log('openConfigModal: función placeholder llamada. Esperando carga completa del script...');
            // Esta función será reemplazada por la función completa más adelante
            // Si se llama antes de que se cargue la función completa, esperar un momento y reintentar
            setTimeout(() => {
                if (window.openConfigModal && window.openConfigModal.toString().includes('async')) {
                    console.log('Reintentando con función completa...');
                    window.openConfigModal();
                } else {
                    console.error('openConfigModal aún no está completamente cargada');
                    const isDarkMode = document.documentElement.classList.contains('dark');
                    Swal.fire({
                        icon: 'info',
                        title: 'Cargando...',
                        text: 'Por favor, espera un momento y vuelve a intentar.',
                        timer: 2000,
                        showConfirmButton: false,
                        background: isDarkMode ? '#1e293b' : '#ffffff',
                        color: isDarkMode ? '#e2e8f0' : '#1e293b',
                    });
                }
            }, 500);
        };
    </script>
    <style>
        * {
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        /* Switch Toggle Styles */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }
        
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #cbd5e1;
            transition: .4s;
            border-radius: 34px;
        }
        
        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        
        input:checked + .slider {
            background-color: #3b82f6;
        }
        
        input:checked + .slider:before {
            transform: translateX(24px);
        }
        
        input:checked ~ #botToggleIcon,
        input:checked ~ #emailsToggleIcon,
        input:checked ~ #botToggleIconMobile,
        input:checked ~ #emailsToggleIconMobile {
            opacity: 1 !important;
        }
        
        input:not(:checked) ~ #botToggleIcon,
        input:not(:checked) ~ #emailsToggleIcon,
        input:not(:checked) ~ #botToggleIconMobile,
        input:not(:checked) ~ #emailsToggleIconMobile {
            opacity: 0 !important;
        }
        
        .dark .slider {
            background-color: #475569;
        }
        
        .dark input:checked + .slider {
            background-color: #60a5fa;
        }
        
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
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">
    @php
        use App\Models\Client;
        
        // Obtener filtros de la URL
        $idiomaFiltro = request()->get('idioma', '');
        $searchQuery = request()->get('search', '');
        
        // Obtener clientes en proceso
        $query = Client::whereIn('estado', ['pending', 'received']);
        
        // Aplicar filtro por idioma si existe
        if ($idiomaFiltro) {
            $query->where('idioma', $idiomaFiltro);
        }
        
        // Aplicar búsqueda si existe
        if ($searchQuery) {
            $query->where(function($q) use ($searchQuery) {
                $q->where('name', 'like', '%' . $searchQuery . '%')
                  ->orWhere('email', 'like', '%' . $searchQuery . '%')
                  ->orWhere('telefono_1', 'like', '%' . $searchQuery . '%')
                  ->orWhere('telefono_2', 'like', '%' . $searchQuery . '%');
            });
        }
        
        // Aplicar paginación con los query parameters - ordenar por más recientes primero
        // Incluir conteo de emails enviados (excluyendo extractor)
        $clientes = $query->withCount([
                'emails' => function($q) {
                    $q->where(function($query) {
                        $query->where('tipo', '!=', 'extractor')
                              ->orWhereNull('tipo');
                    });
                }
            ])
            ->orderBy('created_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(25)
            ->appends(request()->query());
        
        // Obtener lista de idiomas únicos de clientes en proceso
        $idiomasDisponibles = Client::whereIn('estado', ['pending', 'received'])
            ->whereNotNull('idioma')
            ->where('idioma', '!=', '')
            ->distinct()
            ->pluck('idioma')
            ->toArray();
        
        $idiomasNombres = [
            'es' => '🇪🇸 Español',
            'en' => '🇬🇧 English',
            'fr' => '🇫🇷 Français',
            'de' => '🇩🇪 Deutsch',
            'it' => '🇮🇹 Italiano',
            'pt' => '🇵🇹 Português'
        ];
    @endphp

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-400/20 dark:bg-blue-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-violet-400/10 dark:bg-violet-400/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-emerald-400/20 dark:bg-emerald-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-4 py-3 sm:px-6 sm:py-4 lg:px-8">
            @php $pageTitle = 'Bot Alpha'; @endphp
            @include('partials.walee-navbar')
            
            <!-- Header -->
            <header class="mb-3 sm:mb-4">
                <div class="mb-2 sm:mb-3">
                    <h1 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white">
                        Bot Alpha
                    </h1>
                </div>
                <!-- Botones: 5 columnas ocupando todo el ancho -->
                <div class="grid grid-cols-5 gap-2">
                    <a href="{{ route('walee.emails.dashboard') }}" class="flex items-center justify-center px-3 py-2 sm:px-4 sm:py-2.5 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white font-medium rounded-lg transition-all active:scale-95">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span class="hidden sm:inline ml-2 font-semibold">Volver</span>
                    </a>
                    <a href="{{ route('walee.emails.enviados') }}" class="flex items-center justify-center px-3 py-2 sm:px-4 sm:py-2.5 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-all shadow-sm hover:shadow active:scale-95">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="hidden sm:inline ml-2 font-semibold">Enviados</span>
                    </a>
                    <button onclick="openExtraerModal()" class="flex items-center justify-center px-3 py-2 sm:px-4 sm:py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-medium rounded-lg transition-all shadow-sm hover:shadow active:scale-95">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span class="hidden sm:inline ml-2 font-semibold">Extraer</span>
                    </button>
                    <button id="configButton" type="button" class="flex items-center justify-center px-3 py-2 sm:px-4 sm:py-2.5 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white font-medium rounded-lg transition-all active:scale-95">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="hidden sm:inline ml-2 font-semibold">Config</span>
                    </button>
                    <a href="{{ route('walee.bot.alpha.config') }}" class="flex items-center justify-center px-3 py-2 sm:px-4 sm:py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg transition-all shadow-sm hover:shadow active:scale-95">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M5 8h14M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="hidden sm:inline ml-2 font-semibold">Config Page</span>
                    </a>
                </div>
            </header>
            
            @if(isset($ordenExtraccion))
                @php
                    $config = $ordenExtraccion->configuracion ?? [];
                    $recurrenciaConfig = $config['recurrencia'] ?? $ordenExtraccion->recurrencia_horas ?? null;
                    $idiomaConfig = $config['idioma'] ?? null;
                    $industriaConfig = $config['industria'] ?? null;

                    $idiomasLabels = [
                        'es' => 'Español',
                        'en' => 'Inglés',
                        'fr' => 'Francés',
                        'de' => 'Alemán',
                        'it' => 'Italiano',
                        'pt' => 'Portugués',
                    ];

                    // Texto de recurrencia
                    $txtRecurrencia = 'Sin recurrencia';
                    if (!is_null($recurrenciaConfig)) {
                        if ((float)$recurrenciaConfig === 0.5) {
                            $txtRecurrencia = 'Cada media hora';
                        } elseif ((float)$recurrenciaConfig === 1.0) {
                            $txtRecurrencia = 'Cada 1 hora';
                        } else {
                            $txtRecurrencia = 'Cada ' . (float)$recurrenciaConfig . ' horas';
                        }
                    }

                    // Texto de idioma
                    $txtIdioma = $idiomaConfig
                        ? ($idiomasLabels[$idiomaConfig] ?? $idiomaConfig)
                        : 'Todos los idiomas';

                    // Texto de industria
                    $txtIndustria = $industriaConfig ?: 'Todas las industrias';

                    $botActivo = (bool)($ordenExtraccion->activo ?? false);
                @endphp
                <div class="mb-4">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 px-4 py-3 rounded-xl border text-xs sm:text-sm
                        {{ $botActivo 
                            ? 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-300 dark:border-emerald-600 text-emerald-900 dark:text-emerald-200' 
                            : 'bg-slate-100 dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300' }}">
                        <div class="flex items-start gap-2">
                            <span class="mt-0.5 sm:mt-0">
                                @if($botActivo)
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                                    </svg>
                                @endif
                            </span>
                            <div>
                                <p class="font-semibold flex items-center gap-1">
                                    Estado del extractor:
                                    @if($botActivo)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-500 text-white text-[11px] sm:text-xs font-semibold">
                                            Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-slate-500 text-white text-[11px] sm:text-xs font-semibold">
                                            Pausado
                                        </span>
                                    @endif
                                </p>
                                <p class="mt-0.5">
                                    <span class="font-medium">Recurrencia:</span> {{ $txtRecurrencia }} ·
                                    <span class="font-medium">Idioma:</span> {{ $txtIdioma }} ·
                                    <span class="font-medium">Industria:</span> {{ $txtIndustria }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('walee.bot.alpha.config') }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] sm:text-xs font-semibold
                                {{ $botActivo 
                                    ? 'bg-emerald-600 hover:bg-emerald-700 text-white' 
                                    : 'bg-slate-600 hover:bg-slate-700 text-white' }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Editar</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Search Bar y Filtros -->
            <div class="mb-4">
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-3 shadow-sm">
                    <div class="flex flex-col sm:flex-row gap-2">
                        <!-- Search Bar -->
                        <div class="relative flex-1">
                            <input 
                                type="text" 
                                id="searchInput"
                                value="{{ $searchQuery }}"
                                placeholder="Buscar por nombre, email o teléfono..."
                                class="w-full px-4 py-2.5 pl-10 rounded-lg bg-slate-50 dark:bg-slate-900/80 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm shadow-sm"
                                onkeyup="handleSearch()"
                            >
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Filtro por Idioma -->
                        <div class="sm:w-48">
                            <select 
                                id="idiomaFilter"
                                onchange="handleFilter()"
                                class="w-full px-3 py-2 rounded-lg bg-slate-100 dark:bg-slate-900/80 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-white focus:outline-none focus:border-blue-500/50 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm"
                            >
                                <option value="">Todos los idiomas</option>
                                @foreach($idiomasDisponibles as $idioma)
                                    <option value="{{ $idioma }}" {{ $idiomaFiltro == $idioma ? 'selected' : '' }}>
                                        {{ $idiomasNombres[$idioma] ?? strtoupper($idioma) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Header con acciones -->
            <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-0 mb-3">
                <div>
                    <h1 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white">
                        Clientes Extraídos
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
            
            <!-- Lista de Clientes -->
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-3 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span>Clientes Extraídos</span>
                        <span class="text-xs font-normal text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 px-2 py-0.5 rounded-full">
                            {{ $clientes->total() }}
                        </span>
                    </h2>
                </div>
                
                <div class="space-y-2.5" id="clientsList">
                    @forelse($clientes as $cliente)
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
                        <div 
                            class="flex items-center gap-3 p-3 rounded-xl bg-gradient-to-r from-slate-50 to-slate-100/50 dark:from-slate-800/50 dark:to-slate-800/30 border border-slate-200 dark:border-slate-700 hover:border-emerald-400 dark:hover:border-emerald-500/30 hover:shadow-md dark:hover:shadow-lg hover:from-emerald-50/50 hover:to-slate-50 dark:hover:from-emerald-500/10 dark:hover:to-slate-800/50 transition-all group client-card-selectable" 
                            data-client-id="{{ $cliente->id }}"
                            onclick="handleClientCardClick(event, {{ $cliente->id }}, '{{ addslashes($cliente->name ?? 'Cliente') }}')"
                        >
                            <!-- Checkbox -->
                            <input 
                                type="checkbox" 
                                class="client-checkbox w-4 h-4 rounded border-slate-300 dark:border-slate-600 text-emerald-500 focus:ring-emerald-500 focus:ring-offset-0 cursor-pointer hidden"
                                data-client-id="{{ $cliente->id }}"
                                data-client-name="{{ $cliente->name ?? 'Cliente' }}"
                                onchange="updateDeleteButton()"
                            >
                            
                            <a href="{{ route('walee.cliente.detalle', $cliente->id) }}" class="flex items-center gap-3 flex-1 min-w-0" onclick="handleClientLinkClick(event)">
                                <!-- Desktop: Foto -->
                                @if($fotoUrl)
                                    <img src="{{ $fotoUrl }}" alt="{{ $cliente->name }}" class="hidden sm:block w-11 h-11 rounded-xl object-cover border-2 border-emerald-500/30 flex-shrink-0 shadow-sm group-hover:border-emerald-400 transition-colors">
                                @else
                                    <div class="hidden sm:flex w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-100 to-emerald-200 dark:from-emerald-500/20 dark:to-emerald-600/10 border-2 border-emerald-500/30 items-center justify-center flex-shrink-0 shadow-sm group-hover:border-emerald-400 transition-colors">
                                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                @endif
                                <!-- Mobile: Bandera -->
                                @if($cliente->idioma)
                                    @php
                                        $banderas = [
                                            'es' => '🇪🇸',
                                            'en' => '🇬🇧',
                                            'fr' => '🇫🇷',
                                            'de' => '🇩🇪',
                                            'it' => '🇮🇹',
                                            'pt' => '🇵🇹'
                                        ];
                                        $bandera = $banderas[$cliente->idioma] ?? '';
                                    @endphp
                                    @if($bandera)
                                        <div class="sm:hidden w-11 h-11 rounded-xl bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-600 border-2 border-slate-300 dark:border-slate-600 flex items-center justify-center flex-shrink-0 shadow-sm group-hover:border-emerald-400 transition-colors">
                                            <span class="text-2xl">{{ $bandera }}</span>
                                        </div>
                                    @endif
                                @endif
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <p class="font-semibold text-sm text-slate-900 dark:text-white truncate group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors flex items-center gap-1.5">
                                            @if($cliente->idioma)
                                                @php
                                                    $banderas = [
                                                        'es' => '🇪🇸',
                                                        'en' => '🇬🇧',
                                                        'fr' => '🇫🇷',
                                                        'de' => '🇩🇪',
                                                        'it' => '🇮🇹',
                                                        'pt' => '🇵🇹'
                                                    ];
                                                    $bandera = $banderas[$cliente->idioma] ?? '';
                                                @endphp
                                                @if($bandera)
                                                    <span class="hidden sm:inline text-lg">{{ $bandera }}</span>
                                                @endif
                                            @endif
                                            <span>{{ $cliente->name ?: 'Sin nombre' }}</span>
                                        </p>
                                        @if($cliente->industria)
                                            <p class="text-xs text-slate-500 dark:text-slate-400 truncate">
                                                {{ $cliente->industria }}
                                            </p>
                                        @endif
                                        @if($cliente->emails_count > 0)
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded-lg border bg-emerald-500/20 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border-emerald-500/30 dark:border-emerald-500/20 flex-shrink-0">
                                                Email Enviado
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 truncate flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <span>{{ $cliente->email ?: 'Sin email' }}</span>
                                    </p>
                                </div>
                            </a>
                            <button onclick="openEmailModalForClient({{ $cliente->id }}, '{{ addslashes($cliente->email ?? '') }}', '{{ addslashes($cliente->name) }}', '{{ addslashes($cliente->website ?? '') }}')" class="p-2.5 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-slate-900 border border-yellow-600 hover:border-yellow-700 transition-all flex-shrink-0 shadow-sm hover:shadow flex items-center justify-center" title="Enviar email">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                            </button>
                            <button onclick="deleteCliente({{ $cliente->id }}, '{{ addslashes($cliente->name ?? 'Cliente') }}')" class="p-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white border border-red-600 hover:border-red-700 transition-all flex-shrink-0 shadow-sm hover:shadow flex items-center justify-center" title="Eliminar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-sm text-slate-500 dark:text-slate-400">No se encontraron clientes</p>
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
                            {{ $clientes->currentPage() }} / {{ $clientes->lastPage() }}
                        </span>
                        
                        @if($clientes->hasMorePages())
                            <a href="{{ $clientes->nextPageUrl() }}" class="px-3 py-1.5 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-900 dark:text-white rounded-md transition-colors border border-slate-200 dark:border-slate-700 text-xs">Siguiente</a>
                        @else
                            <span class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 rounded-md cursor-not-allowed text-xs">Siguiente</span>
                        @endif
                    </div>
                @endif
            </div>
            
            <!-- World Map with Clocks -->
            @include('partials.walee-world-map-clocks')
            
            <!-- Footer -->
            <footer class="text-center py-4 sm:py-6 md:py-8 mt-6">
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-500">
                    <span class="text-walee-600 dark:text-walee-400 font-medium">Walee</span> · websolutions.work
                </p>
            </footer>
        </div>
    </div>
    
    <script>
        // Variables para almacenar las recurrencias seleccionadas
        // Inicializar desde las órdenes guardadas en la base de datos
        let recurrenciaSeleccionada = @json($ordenExtraccion->recurrencia_horas ?? null); // Para extracción de clientes
        let recurrenciaEmailsSeleccionada = @json($ordenEmails->recurrencia_horas ?? null); // Para emails automáticos
        let botToggleChecked = @json($ordenExtraccion->activo ?? false); // Estado del toggle de bot
        let emailsToggleChecked = @json($ordenEmails->activo ?? false); // Estado del toggle de emails

        // Configuración extra para extracción (idioma e industria) desde la BD
        let configExtraccion = @json($ordenExtraccion->configuracion ?? []);
        let configIdiomaExtraccion = configExtraccion?.idioma || '';
        let configIndustriaExtraccion = configExtraccion?.industria || '';
        let configRecurrenciaExtraccion = configExtraccion?.recurrencia || recurrenciaSeleccionada || null;
        
        console.log('Configuración cargada desde BD:', {
            recurrenciaSeleccionada,
            recurrenciaEmailsSeleccionada,
            botToggleChecked,
            emailsToggleChecked,
            configExtraccion,
            configRecurrenciaExtraccion
        });
        
        // Variable para almacenar el webhook actual
        let currentWebhookUrl = '{{ $webhookUrl ?? '' }}';
        
        // Definir openConfigModal inmediatamente para que esté disponible cuando se renderiza el HTML
        window.openConfigModal = async function openConfigModal() {
            try {
                console.log('openConfigModal llamado');
                const isDarkMode = document.documentElement.classList.contains('dark');
                const isMobile = window.innerWidth < 640;
                const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
                if (!csrfTokenElement) {
                    console.error('CSRF token no encontrado');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo encontrar el token de seguridad. Por favor, recarga la página.',
                    });
                    return;
                }
                const csrfToken = csrfTokenElement.getAttribute('content');
                
                // Cargar webhook guardado si no lo tenemos
                if (!currentWebhookUrl) {
                    try {
                        const response = await fetch('{{ route("walee.bot.alpha.webhook.get") }}', {
                            method: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        });
                        const data = await response.json();
                        if (data.success) {
                            currentWebhookUrl = data.webhook_url || '';
                        }
                    } catch (error) {
                        console.error('Error al cargar webhook:', error);
                    }
                }
                
                let modalWidth = '95%';
                if (window.innerWidth >= 1024) {
                    modalWidth = '600px';
                } else if (window.innerWidth >= 640) {
                    modalWidth = '550px';
                }
                
                // Obtener estado actual de los toggles (usar variables globales)
                botToggleChecked = document.getElementById('botToggle')?.checked || botToggleChecked || false;
                emailsToggleChecked = document.getElementById('emailsToggle')?.checked || emailsToggleChecked || false;
                
                Swal.fire({
                    title: 'Configuración',
                    html: `
                        <form id="configForm" class="text-left space-y-4">
                            <!-- Webhook -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    URL del Webhook
                                </label>
                                <input 
                                    type="url" 
                                    id="webhookUrl" 
                                    name="webhook_url"
                                    value="${currentWebhookUrl || ''}"
                                    placeholder="https://ejemplo.com/webhook"
                                    class="w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                >
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                    Ingresa la URL del webhook para recibir notificaciones
                                </p>
                            </div>
                            
                            <!-- Extracción de Clientes -->
                            <div class="border-t border-slate-200 dark:border-slate-700 pt-4">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-slate-700 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Extracción de Clientes</h3>
                                    </div>
                                    <label class="switch relative" style="width: 56px; height: 32px;">
                                        <input type="checkbox" id="configBotToggle" ${botToggleChecked ? 'checked' : ''} onchange="toggleBot(this.checked); if(document.getElementById('botToggle')) document.getElementById('botToggle').checked = this.checked; if(document.getElementById('botToggleMobile')) document.getElementById('botToggleMobile').checked = this.checked;">
                                        <span class="slider"></span>
                                        <svg class="absolute left-1.5 top-1/2 -translate-y-1/2 w-4 h-4 text-white z-10 pointer-events-none transition-opacity" style="opacity: ${botToggleChecked ? '1' : '0'};" id="configBotToggleIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                    </label>
                                </div>
                                <div class="flex items-center gap-2 mb-2">
                                    <button type="button" onclick="openRecurrenciaModal()" id="configRecurrenciaBtn" class="px-2.5 py-1.5 ${botToggleChecked ? 'bg-blue-500 hover:bg-blue-600' : 'bg-slate-400 hover:bg-slate-500'} text-white font-medium rounded-md transition-all flex items-center gap-1.5 text-xs shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span id="configRecurrenciaText">Recurrencia</span>
                                    </button>
                                </div>
                                <p id="configResumenExtraccion" class="text-[11px] text-slate-500 dark:text-slate-400 mb-3">
                                    <!-- Se rellena dinámicamente con la configuración actual -->
                                </p>
                                <!-- Filtros de idioma e industria para extracción -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                                            Idioma para extracción
                                        </label>
                                        <select 
                                            id="configIdiomaExtraccion"
                                            class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        >
                                            <option value="" ${!configIdiomaExtraccion ? 'selected' : ''}>Todos</option>
                                            <option value="es" ${configIdiomaExtraccion === 'es' ? 'selected' : ''}>🇪🇸 Español</option>
                                            <option value="en" ${configIdiomaExtraccion === 'en' ? 'selected' : ''}>🇬🇧 English</option>
                                            <option value="fr" ${configIdiomaExtraccion === 'fr' ? 'selected' : ''}>🇫🇷 Français</option>
                                            <option value="de" ${configIdiomaExtraccion === 'de' ? 'selected' : ''}>🇩🇪 Deutsch</option>
                                            <option value="it" ${configIdiomaExtraccion === 'it' ? 'selected' : ''}>🇮🇹 Italiano</option>
                                            <option value="pt" ${configIdiomaExtraccion === 'pt' ? 'selected' : ''}>🇵🇹 Português</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                                            Industria para extracción
                                        </label>
                                        <select 
                                            id="configIndustriaExtraccion"
                                            class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        >
                                            <option value="" ${!configIndustriaExtraccion ? 'selected' : ''}>Todas</option>
                                            <option value="Turismo" ${configIndustriaExtraccion === 'Turismo' ? 'selected' : ''}>Turismo</option>
                                            <option value="Gastronomía" ${configIndustriaExtraccion === 'Gastronomía' ? 'selected' : ''}>Gastronomía</option>
                                            <option value="Retail" ${configIndustriaExtraccion === 'Retail' ? 'selected' : ''}>Retail</option>
                                            <option value="Salud" ${configIndustriaExtraccion === 'Salud' ? 'selected' : ''}>Salud</option>
                                            <option value="Educación" ${configIndustriaExtraccion === 'Educación' ? 'selected' : ''}>Educación</option>
                                            <option value="Tecnología" ${configIndustriaExtraccion === 'Tecnología' ? 'selected' : ''}>Tecnología</option>
                                            <option value="Servicios" ${configIndustriaExtraccion === 'Servicios' ? 'selected' : ''}>Servicios</option>
                                            <option value="Comercio" ${configIndustriaExtraccion === 'Comercio' ? 'selected' : ''}>Comercio</option>
                                            <option value="Manufactura" ${configIndustriaExtraccion === 'Manufactura' ? 'selected' : ''}>Manufactura</option>
                                            <option value="Inmobiliaria" ${configIndustriaExtraccion === 'Inmobiliaria' ? 'selected' : ''}>Inmobiliaria</option>
                                            <option value="Automotriz" ${configIndustriaExtraccion === 'Automotriz' ? 'selected' : ''}>Automotriz</option>
                                            <option value="Belleza y Estética" ${configIndustriaExtraccion === 'Belleza y Estética' ? 'selected' : ''}>Belleza y Estética</option>
                                            <option value="Fitness y Deportes" ${configIndustriaExtraccion === 'Fitness y Deportes' ? 'selected' : ''}>Fitness y Deportes</option>
                                            <option value="Arte y Cultura" ${configIndustriaExtraccion === 'Arte y Cultura' ? 'selected' : ''}>Arte y Cultura</option>
                                            <option value="Legal" ${configIndustriaExtraccion === 'Legal' ? 'selected' : ''}>Legal</option>
                                            <option value="Finanzas" ${configIndustriaExtraccion === 'Finanzas' ? 'selected' : ''}>Finanzas</option>
                                            <option value="Marketing" ${configIndustriaExtraccion === 'Marketing' ? 'selected' : ''}>Marketing</option>
                                            <option value="Construcción" ${configIndustriaExtraccion === 'Construcción' ? 'selected' : ''}>Construcción</option>
                                            <option value="Agricultura" ${configIndustriaExtraccion === 'Agricultura' ? 'selected' : ''}>Agricultura</option>
                                            <option value="Otro" ${configIndustriaExtraccion === 'Otro' ? 'selected' : ''}>Otro</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Emails Automáticos -->
                            <div class="border-t border-slate-200 dark:border-slate-700 pt-4">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-slate-700 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Emails Automáticos</h3>
                                    </div>
                                    <label class="switch relative" style="width: 56px; height: 32px;">
                                        <input type="checkbox" id="configEmailsToggle" ${emailsToggleChecked ? 'checked' : ''} onchange="toggleEmails(this.checked); if(document.getElementById('emailsToggle')) document.getElementById('emailsToggle').checked = this.checked; if(document.getElementById('emailsToggleMobile')) document.getElementById('emailsToggleMobile').checked = this.checked;">
                                        <span class="slider"></span>
                                        <svg class="absolute right-1.5 top-1/2 -translate-y-1/2 w-4 h-4 text-white z-10 pointer-events-none transition-opacity" style="opacity: ${emailsToggleChecked ? '1' : '0'};" id="configEmailsToggleIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </label>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="openRecurrenciaEmailsModal()" id="configRecurrenciaEmailsBtn" class="px-2.5 py-1.5 ${emailsToggleChecked ? 'bg-emerald-500 hover:bg-emerald-600' : 'bg-slate-400 hover:bg-slate-500'} text-white font-medium rounded-md transition-all flex items-center gap-1.5 text-xs shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <span id="configRecurrenciaEmailsText">Recurrencia</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    `,
                    width: modalWidth,
                    padding: isMobile ? '1rem' : '1.5rem',
                    showCancelButton: true,
                    confirmButtonText: 'Guardar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#D59F3B',
                    cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                    buttonsStyling: true,
                    reverseButtons: true,
                    customClass: {
                        popup: isDarkMode ? 'dark-swal' : 'light-swal',
                        title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                        htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                        confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                        cancelButton: isDarkMode ? 'dark-swal-cancel' : 'light-swal-cancel'
                    },
                    didOpen: () => {
                        // Helper para mostrar el resumen de configuración actual del extractor
                        const actualizarResumenExtraccion = () => {
                            const resumenEl = document.getElementById('configResumenExtraccion');
                            if (!resumenEl) return;

                            // Recurrencia
                            const r = recurrenciaSeleccionada || configRecurrenciaExtraccion || null;
                            let txtRecurrencia = 'sin recurrencia';
                            if (r) {
                                if (r === 0.5) txtRecurrencia = 'cada media hora';
                                else if (r === 1) txtRecurrencia = 'cada 1 hora';
                                else txtRecurrencia = `cada ${r} horas`;
                            }

                            // Idioma
                            const idiomas = {
                                'es': 'Español',
                                'en': 'Inglés',
                                'fr': 'Francés',
                                'de': 'Alemán',
                                'it': 'Italiano',
                                'pt': 'Portugués'
                            };
                            const txtIdioma = configIdiomaExtraccion
                                ? idiomas[configIdiomaExtraccion] || configIdiomaExtraccion
                                : 'todos los idiomas';

                            // Industria
                            const txtIndustria = configIndustriaExtraccion
                                ? configIndustriaExtraccion
                                : 'todas las industrias';

                            resumenEl.textContent = `Extractor configurado: ${txtRecurrencia}, idioma: ${txtIdioma}, industria: ${txtIndustria}.`;
                        };

                        // Guardar helper en window para reutilizar desde otros handlers si es necesario
                        window.actualizarResumenExtraccion = actualizarResumenExtraccion;

                        // Selects de idioma e industria para extracción
                        const idiomaSelect = document.getElementById('configIdiomaExtraccion');
                        const industriaSelect = document.getElementById('configIndustriaExtraccion');
                        if (idiomaSelect) {
                            idiomaSelect.addEventListener('change', (e) => {
                                configIdiomaExtraccion = e.target.value || '';
                                console.log('Idioma extracción (config):', configIdiomaExtraccion);
                                actualizarResumenExtraccion();
                            });
                        }
                        if (industriaSelect) {
                            industriaSelect.addEventListener('change', (e) => {
                                configIndustriaExtraccion = e.target.value || '';
                                console.log('Industria extracción (config):', configIndustriaExtraccion);
                                actualizarResumenExtraccion();
                            });
                        }
                        
                        // Sincronizar iconos de los toggles
                        const configBotToggle = document.getElementById('configBotToggle');
                        const configEmailsToggle = document.getElementById('configEmailsToggle');
                        
                        if (configBotToggle) {
                            const updateBotIcon = () => {
                                const icon = document.getElementById('configBotToggleIcon');
                                if (icon) {
                                    icon.style.opacity = configBotToggle.checked ? '1' : '0';
                                }
                                // Actualizar color del botón de recurrencia
                                const recurrenciaBtn = document.getElementById('configRecurrenciaBtn');
                                if (recurrenciaBtn) {
                                    if (configBotToggle.checked) {
                                        recurrenciaBtn.classList.remove('bg-slate-400', 'hover:bg-slate-500');
                                        recurrenciaBtn.classList.add('bg-blue-500', 'hover:bg-blue-600');
                                    } else {
                                        recurrenciaBtn.classList.remove('bg-blue-500', 'hover:bg-blue-600');
                                        recurrenciaBtn.classList.add('bg-slate-400', 'hover:bg-slate-500');
                                    }
                                }
                            };
                            configBotToggle.addEventListener('change', updateBotIcon);
                            updateBotIcon();
                        }
                        
                        if (configEmailsToggle) {
                            const updateEmailsIcon = () => {
                                const icon = document.getElementById('configEmailsToggleIcon');
                                if (icon) {
                                    icon.style.opacity = configEmailsToggle.checked ? '1' : '0';
                                }
                                // Actualizar color del botón de recurrencia
                                const recurrenciaEmailsBtn = document.getElementById('configRecurrenciaEmailsBtn');
                                if (recurrenciaEmailsBtn) {
                                    if (configEmailsToggle.checked) {
                                        recurrenciaEmailsBtn.classList.remove('bg-slate-400', 'hover:bg-slate-500');
                                        recurrenciaEmailsBtn.classList.add('bg-emerald-500', 'hover:bg-emerald-600');
                                    } else {
                                        recurrenciaEmailsBtn.classList.remove('bg-emerald-500', 'hover:bg-emerald-600');
                                        recurrenciaEmailsBtn.classList.add('bg-slate-400', 'hover:bg-slate-500');
                                    }
                                }
                            };
                            configEmailsToggle.addEventListener('change', updateEmailsIcon);
                            updateEmailsIcon();
                        }
                        
                        // Actualizar textos de recurrencia si hay valores
                        const configRecurrenciaText = document.getElementById('configRecurrenciaText');
                        if (configRecurrenciaText && recurrenciaSeleccionada) {
                            // Formatear el texto según el valor
                            let texto = '';
                            if (recurrenciaSeleccionada === 0.5) {
                                texto = 'Cada media hora';
                            } else if (recurrenciaSeleccionada === 1) {
                                texto = 'Cada una hora';
                            } else {
                                texto = `Cada ${recurrenciaSeleccionada} horas`;
                            }
                            configRecurrenciaText.textContent = texto;
                        }

                        // Pintar resumen inicial
                        actualizarResumenExtraccion();
                        
                        const configRecurrenciaEmailsText = document.getElementById('configRecurrenciaEmailsText');
                        if (configRecurrenciaEmailsText && recurrenciaEmailsSeleccionada) {
                            const recurrencias = [
                                { value: 0.5, label: 'Cada media hora' },
                                { value: 1, label: 'Cada 1 hora' },
                                { value: 2, label: 'Cada 2 horas' },
                                { value: 3, label: 'Cada 3 horas' },
                                { value: 4, label: 'Cada 4 horas' },
                                { value: 5, label: 'Cada 5 horas' },
                                { value: 6, label: 'Cada 6 horas' },
                                { value: 8, label: 'Cada 8 horas' },
                                { value: 12, label: 'Cada 12 horas' },
                                { value: 48, label: 'Cada 48 horas' },
                                { value: 72, label: 'Cada 72 horas' }
                            ];
                            const label = recurrencias.find(r => r.value == recurrenciaEmailsSeleccionada)?.label || `Cada ${recurrenciaEmailsSeleccionada} horas`;
                            configRecurrenciaEmailsText.textContent = label;
                        }
                        
                        // Focus en el input
                        document.getElementById('webhookUrl')?.focus();
                    },
                    preConfirm: () => {
                        const webhookUrl = document.getElementById('webhookUrl').value.trim();
                        const idioma = document.getElementById('configIdiomaExtraccion')?.value || '';
                        const industria = document.getElementById('configIndustriaExtraccion')?.value || '';
                        
                        if (webhookUrl && !isValidUrl(webhookUrl)) {
                            Swal.showValidationMessage('Por favor ingresa una URL válida');
                            return false;
                        }
                        
                        return { 
                            webhook_url: webhookUrl,
                            idioma_extraccion: idioma || null,
                            industria_extraccion: industria || null
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        const { webhook_url, idioma_extraccion, industria_extraccion } = result.value;
                        
                        // Guardar webhook si se proporcionó
                        if (typeof webhook_url !== 'undefined') {
                            saveWebhook(webhook_url);
                        }
                        
                        // Actualizar variables globales de configuración de extracción
                        configIdiomaExtraccion = idioma_extraccion || '';
                        configIndustriaExtraccion = industria_extraccion || '';
                        
                        // Guardar configuración de orden programada de extracción (incluye idioma, industria y recurrencia)
                        guardarOrdenProgramada('extraccion_clientes', botToggleChecked, recurrenciaSeleccionada, {
                            idioma: configIdiomaExtraccion || null,
                            industria: configIndustriaExtraccion || null,
                            recurrencia: recurrenciaSeleccionada || null,
                        });
                    }
                }).catch((error) => {
                    console.error('Error al abrir modal de configuración:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo abrir el modal de configuración. Por favor, recarga la página.',
                    });
                });
            } catch (error) {
                console.error('Error en openConfigModal:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al abrir el modal: ' + error.message,
                });
            }
        };
        
        // Función para guardar orden programada en la base de datos
        async function guardarOrdenProgramada(tipo, activo, recurrenciaHoras, configuracion = null) {
            try {
                console.log('Guardando orden programada:', { tipo, activo, recurrenciaHoras, configuracion });
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (!csrfToken) {
                    console.error('CSRF token no encontrado');
                }
                
                const response = await fetch('/api/ordenes-programadas', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || '',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        tipo: tipo,
                        activo: activo,
                        recurrencia_horas: recurrenciaHoras,
                        configuracion: configuracion,
                        user_id: null // El backend usará auth()->id() si está disponible
                    })
                });
                
                console.log('Response status:', response.status);
                const data = await response.json();
                console.log('Response data:', data);
                
                if (data.success) {
                    console.log(`✅ Orden programada ${tipo} guardada correctamente:`, data.data);
                    // Mostrar notificación de éxito
                    Swal.fire({
                        icon: 'success',
                        title: 'Guardado',
                        text: `Orden ${tipo === 'extraccion_clientes' ? 'de extracción' : 'de emails'} guardada correctamente`,
                        timer: 2000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                    return true;
                } else {
                    console.error('❌ Error al guardar orden programada:', data.message, data.errors);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al guardar la orden programada',
                        timer: 3000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                    return false;
                }
            } catch (error) {
                console.error('❌ Error de conexión al guardar orden programada:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor. Verifica tu conexión.',
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
                return false;
            }
        }
        
        // Toggle Bot - Extracción de Clientes
        async function toggleBot(enabled) {
            console.log('Extracción de Clientes:', enabled ? 'Activado' : 'Desactivado');
            botToggleChecked = enabled;
            
            // Cambiar estilo del botón de recurrencia en la modal de config
            const configRecurrenciaBtn = document.getElementById('configRecurrenciaBtn');
            if (configRecurrenciaBtn) {
                if (enabled) {
                    configRecurrenciaBtn.classList.remove('bg-slate-400', 'hover:bg-slate-500');
                    configRecurrenciaBtn.classList.add('bg-blue-500', 'hover:bg-blue-600');
                } else {
                    configRecurrenciaBtn.classList.remove('bg-blue-500', 'hover:bg-blue-600');
                    configRecurrenciaBtn.classList.add('bg-slate-400', 'hover:bg-slate-500');
                }
            }
            
            // SIEMPRE guardar orden programada en la base de datos (activo/inactivo, con o sin recurrencia)
            await guardarOrdenProgramada('extraccion_clientes', enabled, recurrenciaSeleccionada, {
                idioma: configIdiomaExtraccion || null,
                industria: configIndustriaExtraccion || null,
                recurrencia: recurrenciaSeleccionada || null,
            });
        }
        
        // Toggle Emails Automáticos
        async function toggleEmails(enabled) {
            console.log('Emails Automáticos:', enabled ? 'Activado' : 'Desactivado');
            emailsToggleChecked = enabled;
            
            // Cambiar estilo del botón de recurrencia en la modal de config
            const configRecurrenciaEmailsBtn = document.getElementById('configRecurrenciaEmailsBtn');
            if (configRecurrenciaEmailsBtn) {
                if (enabled) {
                    configRecurrenciaEmailsBtn.classList.remove('bg-slate-400', 'hover:bg-slate-500');
                    configRecurrenciaEmailsBtn.classList.add('bg-emerald-500', 'hover:bg-emerald-600');
                } else {
                    configRecurrenciaEmailsBtn.classList.remove('bg-emerald-500', 'hover:bg-emerald-600');
                    configRecurrenciaEmailsBtn.classList.add('bg-slate-400', 'hover:bg-slate-500');
                }
            }
            
            // SIEMPRE guardar orden programada en la base de datos (activo/inactivo, con o sin recurrencia)
            await guardarOrdenProgramada('emails_automaticos', enabled, recurrenciaEmailsSeleccionada, null);
        }
        
        // Abrir modal de recurrencia para Extracción de Clientes
        function openRecurrenciaModal() {
            const isDarkMode = document.documentElement.classList.contains('dark');
            const recurrencias = [
                { value: 0.5, label: 'Cada media hora' },
                { value: 1, label: 'Cada una hora' },
                { value: 2, label: 'Cada 2 horas' },
                { value: 4, label: 'Cada 4 horas' },
                { value: 6, label: 'Cada 6 horas' },
                { value: 8, label: 'Cada 8 horas' },
                { value: 12, label: 'Cada 12 horas' },
                { value: 24, label: 'Cada 24 horas' },
                { value: 48, label: 'Cada 48 horas' },
                { value: 76, label: 'Cada 76 horas' }
            ];
            
            let recurrenciasOptions = '';
            recurrencias.forEach(rec => {
                const selected = recurrenciaSeleccionada === rec.value ? 'selected' : '';
                recurrenciasOptions += `<option value="${rec.value}" ${selected}>${rec.label}</option>`;
            });
            
            const html = `
                <div class="space-y-3 text-left">
                    <div>
                        <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-2">
                            Seleccionar recurrencia de extracción
                        </label>
                        <select 
                            id="recurrenciaSelect" 
                            class="w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                        >
                            <option value="">Sin recurrencia</option>
                            ${recurrenciasOptions}
                        </select>
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: '<div class="flex items-center gap-2"><svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg><span>Configurar Recurrencia - Extracción</span></div>',
                html: html,
                width: window.innerWidth >= 640 ? '450px' : '90%',
                padding: window.innerWidth < 640 ? '1rem' : '1.5rem',
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#D59F3B',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                reverseButtons: false,
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                    cancelButton: isDarkMode ? 'dark-swal-cancel' : 'light-swal-cancel'
                },
                preConfirm: () => {
                    const select = document.getElementById('recurrenciaSelect');
                    return select ? select.value : null;
                }
            }).then(async (result) => {
                if (result.isConfirmed) {
                    const valor = result.value;
                    recurrenciaSeleccionada = valor ? parseFloat(valor) : null;
                    
                    // Actualizar el texto del botón en la modal de config
                    const configRecurrenciaText = document.getElementById('configRecurrenciaText');
                    if (configRecurrenciaText) {
                        if (recurrenciaSeleccionada) {
                            // Formatear el texto según el valor
                            let texto = '';
                            if (recurrenciaSeleccionada === 0.5) {
                                texto = 'Cada media hora';
                            } else if (recurrenciaSeleccionada === 1) {
                                texto = 'Cada una hora';
                            } else {
                                texto = `Cada ${recurrenciaSeleccionada} horas`;
                            }
                            configRecurrenciaText.textContent = texto;
                        } else {
                            configRecurrenciaText.textContent = 'Recurrencia';
                        }
                    }
                    
                    console.log('Recurrencia de extracción seleccionada:', recurrenciaSeleccionada ? `${recurrenciaSeleccionada} horas` : 'Sin recurrencia');
                    
                    // SIEMPRE guardar orden programada en la base de datos (con o sin bot activo)
                    await guardarOrdenProgramada('extraccion_clientes', botToggleChecked, recurrenciaSeleccionada, {
                        idioma: configIdiomaExtraccion || null,
                        industria: configIndustriaExtraccion || null,
                        recurrencia: recurrenciaSeleccionada || null,
                    });

                    // Actualizar resumen en la modal de configuración si existe
                    if (typeof window.actualizarResumenExtraccion === 'function') {
                        window.actualizarResumenExtraccion();
                    }
                }
            });
        }
        
        // Abrir modal de recurrencia para Emails Automáticos
        function openRecurrenciaEmailsModal() {
            const isDarkMode = document.documentElement.classList.contains('dark');
            const recurrencias = [
                { value: 0.5, label: 'Cada media hora' },
                { value: 1, label: 'Cada 1 hora' },
                { value: 2, label: 'Cada 2 horas' },
                { value: 3, label: 'Cada 3 horas' },
                { value: 4, label: 'Cada 4 horas' },
                { value: 5, label: 'Cada 5 horas' },
                { value: 6, label: 'Cada 6 horas' },
                { value: 8, label: 'Cada 8 horas' },
                { value: 12, label: 'Cada 12 horas' },
                { value: 48, label: 'Cada 48 horas' },
                { value: 72, label: 'Cada 72 horas' }
            ];
            
            let recurrenciasOptions = '';
            recurrencias.forEach(rec => {
                const selected = recurrenciaEmailsSeleccionada === rec.value ? 'selected' : '';
                recurrenciasOptions += `<option value="${rec.value}" ${selected}>${rec.label}</option>`;
            });
            
            const html = `
                <div class="space-y-3 text-left">
                    <div>
                        <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-2">
                            Seleccionar recurrencia de envío de emails
                        </label>
                        <select 
                            id="recurrenciaEmailsSelect" 
                            class="w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm"
                        >
                            <option value="">Sin recurrencia</option>
                            ${recurrenciasOptions}
                        </select>
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: '<div class="flex items-center gap-2"><svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg><span>Configurar Recurrencia - Emails</span></div>',
                html: html,
                width: window.innerWidth >= 640 ? '450px' : '90%',
                padding: window.innerWidth < 640 ? '1rem' : '1.5rem',
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#D59F3B',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                reverseButtons: false,
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                    cancelButton: isDarkMode ? 'dark-swal-cancel' : 'light-swal-cancel'
                },
                preConfirm: () => {
                    const select = document.getElementById('recurrenciaEmailsSelect');
                    return select ? select.value : null;
                }
            }).then(async (result) => {
                if (result.isConfirmed) {
                    const valor = result.value;
                    recurrenciaEmailsSeleccionada = valor ? parseFloat(valor) : null;
                    
                    // Actualizar el texto del botón en la modal de config
                    const configRecurrenciaEmailsText = document.getElementById('configRecurrenciaEmailsText');
                    if (configRecurrenciaEmailsText) {
                        if (recurrenciaEmailsSeleccionada) {
                            const label = recurrencias.find(r => r.value == recurrenciaEmailsSeleccionada)?.label || `Cada ${recurrenciaEmailsSeleccionada} horas`;
                            configRecurrenciaEmailsText.textContent = label;
                        } else {
                            configRecurrenciaEmailsText.textContent = 'Recurrencia';
                        }
                    }
                    
                    console.log('Recurrencia de emails seleccionada:', recurrenciaEmailsSeleccionada ? `${recurrenciaEmailsSeleccionada} horas` : 'Sin recurrencia');
                    
                    // SIEMPRE guardar orden programada en la base de datos (con o sin emails activos)
                    await guardarOrdenProgramada('emails_automaticos', emailsToggleChecked, recurrenciaEmailsSeleccionada, null);
                }
            });
        }
        
        // Inicializar valores al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Inicializando configuración desde BD...');
            
            // Agregar event listener al botón de config
            const configButton = document.getElementById('configButton');
            if (configButton) {
                configButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Botón Config clickeado, llamando openConfigModal...');
                    if (typeof window.openConfigModal === 'function') {
                        window.openConfigModal();
                    } else {
                        console.error('openConfigModal no está disponible');
                        const isDarkMode = document.documentElement.classList.contains('dark');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'La función de configuración no está disponible. Por favor, recarga la página.',
                            background: isDarkMode ? '#1e293b' : '#ffffff',
                            color: isDarkMode ? '#e2e8f0' : '#1e293b',
                        });
                    }
                });
                console.log('Event listener agregado al botón Config');
            } else {
                console.error('Botón Config no encontrado');
            }
            
            // Actualizar textos de recurrencia si existen
            const configRecurrenciaText = document.getElementById('configRecurrenciaText');
            if (configRecurrenciaText && recurrenciaSeleccionada) {
                let texto = '';
                if (recurrenciaSeleccionada === 0.5) {
                    texto = 'Cada media hora';
                } else if (recurrenciaSeleccionada === 1) {
                    texto = 'Cada una hora';
                } else {
                    texto = `Cada ${recurrenciaSeleccionada} horas`;
                }
                configRecurrenciaText.textContent = texto;
            }
            
            const configRecurrenciaEmailsText = document.getElementById('configRecurrenciaEmailsText');
            if (configRecurrenciaEmailsText && recurrenciaEmailsSeleccionada) {
                const recurrencias = [
                    { value: 0.5, label: 'Cada media hora' },
                    { value: 1, label: 'Cada 1 hora' },
                    { value: 2, label: 'Cada 2 horas' },
                    { value: 3, label: 'Cada 3 horas' },
                    { value: 4, label: 'Cada 4 horas' },
                    { value: 5, label: 'Cada 5 horas' },
                    { value: 6, label: 'Cada 6 horas' },
                    { value: 8, label: 'Cada 8 horas' },
                    { value: 12, label: 'Cada 12 horas' },
                    { value: 48, label: 'Cada 48 horas' },
                    { value: 72, label: 'Cada 72 horas' }
                ];
                const label = recurrencias.find(r => r.value == recurrenciaEmailsSeleccionada)?.label || `Cada ${recurrenciaEmailsSeleccionada} horas`;
                configRecurrenciaEmailsText.textContent = label;
            }
        });
        
        // Manejar búsqueda y filtros
        let searchTimeout;
        
        function handleSearch() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                updateURL();
            }, 500);
        }
        
        function handleFilter() {
            updateURL();
        }
        
        function updateURL() {
            const search = document.getElementById('searchInput').value;
            const idioma = document.getElementById('idiomaFilter').value;
            
            const params = new URLSearchParams();
            if (search) params.set('search', search);
            if (idioma) params.set('idioma', idioma);
            
            const newURL = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
            window.history.pushState({}, '', newURL);
            
            // Recargar la página para aplicar filtros
            window.location.href = newURL;
        }
        
        // Validar URL
        function isValidUrl(string) {
            try {
                const url = new URL(string);
                return url.protocol === 'http:' || url.protocol === 'https:';
            } catch (_) {
                return false;
            }
        }
        
        // Guardar webhook
        async function saveWebhook(webhookUrl) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            try {
                Swal.fire({
                    title: 'Guardando...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    background: isDarkMode ? '#1e293b' : '#ffffff',
                    color: isDarkMode ? '#e2e8f0' : '#1e293b'
                });
                
                const response = await fetch('{{ route("walee.bot.alpha.webhook.save") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        webhook_url: webhookUrl
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    currentWebhookUrl = webhookUrl;
                    Swal.fire({
                        icon: 'success',
                        title: '¡Webhook guardado!',
                        text: 'La configuración se ha guardado correctamente',
                        confirmButtonColor: '#D59F3B',
                        background: isDarkMode ? '#1e293b' : '#ffffff',
                        color: isDarkMode ? '#e2e8f0' : '#1e293b',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al guardar el webhook',
                        confirmButtonColor: '#ef4444',
                        background: isDarkMode ? '#1e293b' : '#ffffff',
                        color: isDarkMode ? '#e2e8f0' : '#1e293b'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: error.message,
                    confirmButtonColor: '#ef4444',
                    background: isDarkMode ? '#1e293b' : '#ffffff',
                    color: isDarkMode ? '#e2e8f0' : '#1e293b'
                });
            }
        }
        
        // Abrir modal de Extraer ahora con selector de idioma, país, ciudad e industria
        async function openExtraerModal() {
            const isDarkMode = document.documentElement.classList.contains('dark');
            const isMobile = window.innerWidth < 640;
            
            let modalWidth = '95%';
            let maxHeight = '90vh';
            if (window.innerWidth >= 1024) {
                modalWidth = '550px';
                maxHeight = 'auto';
            } else if (window.innerWidth >= 640) {
                modalWidth = '500px';
                maxHeight = 'auto';
            }
            
            const idiomas = {
                'es': '🇪🇸 Español',
                'en': '🇬🇧 English',
                'fr': '🇫🇷 Français',
                'de': '🇩🇪 Deutsch',
                'it': '🇮🇹 Italiano',
                'pt': '🇵🇹 Português'
            };
            
            // Países por idioma
            const paisesPorIdioma = {
                'es': [
                    { code: 'ES', name: 'España' },
                    { code: 'MX', name: 'México' },
                    { code: 'AR', name: 'Argentina' },
                    { code: 'CO', name: 'Colombia' },
                    { code: 'CL', name: 'Chile' },
                    { code: 'PE', name: 'Perú' },
                    { code: 'VE', name: 'Venezuela' },
                    { code: 'EC', name: 'Ecuador' },
                    { code: 'GT', name: 'Guatemala' },
                    { code: 'CU', name: 'Cuba' },
                    { code: 'BO', name: 'Bolivia' },
                    { code: 'DO', name: 'República Dominicana' },
                    { code: 'HN', name: 'Honduras' },
                    { code: 'PY', name: 'Paraguay' },
                    { code: 'SV', name: 'El Salvador' },
                    { code: 'NI', name: 'Nicaragua' },
                    { code: 'CR', name: 'Costa Rica' },
                    { code: 'PA', name: 'Panamá' },
                    { code: 'UY', name: 'Uruguay' },
                    { code: 'US', name: 'Estados Unidos (Español)' }
                ],
                'en': [
                    { code: 'US', name: 'United States' },
                    { code: 'GB', name: 'United Kingdom' },
                    { code: 'CA', name: 'Canada' },
                    { code: 'AU', name: 'Australia' },
                    { code: 'NZ', name: 'New Zealand' },
                    { code: 'IE', name: 'Ireland' },
                    { code: 'ZA', name: 'South Africa' },
                    { code: 'NG', name: 'Nigeria' },
                    { code: 'KE', name: 'Kenya' },
                    { code: 'GH', name: 'Ghana' },
                    { code: 'TZ', name: 'Tanzania' },
                    { code: 'UG', name: 'Uganda' },
                    { code: 'ZW', name: 'Zimbabwe' },
                    { code: 'ZM', name: 'Zambia' },
                    { code: 'BW', name: 'Botswana' },
                    { code: 'NA', name: 'Namibia' },
                    { code: 'MU', name: 'Mauritius' },
                    { code: 'RW', name: 'Rwanda' },
                    { code: 'SG', name: 'Singapore' },
                    { code: 'MY', name: 'Malaysia' },
                    { code: 'PH', name: 'Philippines' }
                ],
                'fr': [
                    { code: 'FR', name: 'France' },
                    { code: 'BE', name: 'Belgium' },
                    { code: 'CH', name: 'Switzerland' },
                    { code: 'CA', name: 'Canada (French)' },
                    { code: 'LU', name: 'Luxembourg' },
                    { code: 'MC', name: 'Monaco' },
                    { code: 'SN', name: 'Senegal' },
                    { code: 'CI', name: 'Ivory Coast' },
                    { code: 'CM', name: 'Cameroon' },
                    { code: 'MG', name: 'Madagascar' },
                    { code: 'MA', name: 'Morocco' },
                    { code: 'TN', name: 'Tunisia' },
                    { code: 'DZ', name: 'Algeria' },
                    { code: 'GA', name: 'Gabon' },
                    { code: 'CD', name: 'DR Congo' },
                    { code: 'CG', name: 'Congo' },
                    { code: 'BJ', name: 'Benin' },
                    { code: 'BF', name: 'Burkina Faso' },
                    { code: 'ML', name: 'Mali' },
                    { code: 'NE', name: 'Niger' },
                    { code: 'TD', name: 'Chad' },
                    { code: 'GN', name: 'Guinea' },
                    { code: 'TG', name: 'Togo' },
                    { code: 'BI', name: 'Burundi' },
                    { code: 'RW', name: 'Rwanda' },
                    { code: 'GQ', name: 'Equatorial Guinea' }
                ],
                'de': [
                    { code: 'DE', name: 'Germany' },
                    { code: 'AT', name: 'Austria' },
                    { code: 'CH', name: 'Switzerland' },
                    { code: 'LI', name: 'Liechtenstein' },
                    { code: 'LU', name: 'Luxembourg' },
                    { code: 'BE', name: 'Belgium (German)' }
                ],
                'it': [
                    { code: 'IT', name: 'Italy' },
                    { code: 'CH', name: 'Switzerland (Italian)' },
                    { code: 'SM', name: 'San Marino' },
                    { code: 'VA', name: 'Vatican City' }
                ],
                'pt': [
                    { code: 'BR', name: 'Brazil' },
                    { code: 'PT', name: 'Portugal' },
                    { code: 'AO', name: 'Angola' },
                    { code: 'MZ', name: 'Mozambique' },
                    { code: 'CV', name: 'Cape Verde' },
                    { code: 'GW', name: 'Guinea-Bissau' },
                    { code: 'ST', name: 'São Tomé and Príncipe' },
                    { code: 'TL', name: 'East Timor' }
                ]
            };
            
            // Industrias comunes
            const industrias = [
                'Turismo',
                'Gastronomía',
                'Retail',
                'Salud',
                'Educación',
                'Tecnología',
                'Servicios',
                'Comercio',
                'Manufactura',
                'Inmobiliaria',
                'Automotriz',
                'Belleza y Estética',
                'Fitness y Deportes',
                'Arte y Cultura',
                'Legal',
                'Finanzas',
                'Marketing',
                'Construcción',
                'Agricultura',
                'Otro'
            ];
            
            let idiomasOptions = '<option value="">Todos los idiomas</option>';
            for (const [code, name] of Object.entries(idiomas)) {
                idiomasOptions += `<option value="${code}">${name}</option>`;
            }
            
            let industriasOptions = '<option value="">Todas las industrias</option>';
            industrias.forEach(industria => {
                industriasOptions += `<option value="${industria}">${industria}</option>`;
            });
            
            Swal.fire({
                title: 'Extraer Clientes',
                html: `
                    <form id="extraerForm" class="text-left ${isMobile ? 'space-y-3' : 'space-y-4'}" style="${isMobile ? 'max-height: calc(90vh - 120px); overflow-y: auto; padding-right: 4px; width: 100%;' : 'width: 100%;'}">
                        <div style="width: 100%;">
                            <label class="block ${isMobile ? 'text-base' : 'text-sm'} font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                Idioma
                            </label>
                            <select 
                                id="extraerIdioma" 
                                name="idioma"
                                onchange="updatePaises()"
                                class="w-full ${isMobile ? 'px-4 py-3 text-base' : 'px-3 py-2 text-sm'} rounded-lg border-2 border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                style="width: 100%; box-sizing: border-box;"
                            >
                                ${idiomasOptions}
                            </select>
                        </div>
                        
                        <div style="width: 100%;">
                            <label class="block ${isMobile ? 'text-base' : 'text-sm'} font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                País
                            </label>
                            <select 
                                id="extraerPais" 
                                name="pais"
                                class="w-full ${isMobile ? 'px-4 py-3 text-base' : 'px-3 py-2 text-sm'} rounded-lg border-2 border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                style="width: 100%; box-sizing: border-box;"
                            >
                                <option value="">Todos los países</option>
                            </select>
                        </div>
                        
                        <div style="width: 100%;">
                            <label class="block ${isMobile ? 'text-base' : 'text-sm'} font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                Ciudad
                            </label>
                            <input 
                                type="text" 
                                id="extraerCiudad" 
                                name="ciudad"
                                placeholder="Ej: Madrid, Barcelona, Ciudad de México..."
                                class="w-full ${isMobile ? 'px-4 py-3 text-base' : 'px-3 py-2 text-sm'} rounded-lg border-2 border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                style="width: 100%; box-sizing: border-box;"
                            >
                            <p class="${isMobile ? 'text-sm' : 'text-xs'} text-slate-500 dark:text-slate-400 mt-1.5">
                                Deja vacío para todas las ciudades
                            </p>
                        </div>
                        
                        <div style="width: 100%;">
                            <label class="block ${isMobile ? 'text-base' : 'text-sm'} font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                Industria
                            </label>
                            <select 
                                id="extraerIndustria" 
                                name="industria"
                                onchange="toggleIndustriaPersonalizada()"
                                class="w-full ${isMobile ? 'px-4 py-3 text-base' : 'px-3 py-2 text-sm'} rounded-lg border-2 border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                style="width: 100%; box-sizing: border-box;"
                            >
                                ${industriasOptions}
                            </select>
                        </div>
                        
                        <div id="industriaPersonalizadaContainer" style="width: 100%; display: none;">
                            <label class="block ${isMobile ? 'text-base' : 'text-sm'} font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                Industria Personalizada
                            </label>
                            <input 
                                type="text" 
                                id="extraerIndustriaPersonalizada" 
                                name="industria_personalizada"
                                placeholder="Escribe la industria personalizada..."
                                class="w-full ${isMobile ? 'px-4 py-3 text-base' : 'px-3 py-2 text-sm'} rounded-lg border-2 border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                style="width: 100%; box-sizing: border-box;"
                            >
                        </div>
                    </form>
                `,
                width: modalWidth,
                padding: isMobile ? '1.25rem' : '1.5rem',
                heightAuto: !isMobile,
                showCancelButton: true,
                confirmButtonText: 'Extraer',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#D59F3B',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                buttonsStyling: true,
                reverseButtons: false,
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                allowOutsideClick: true,
                allowEscapeKey: true,
                backdrop: true,
                customClass: {
                    container: 'swal2-container-z-index-9999',
                    popup: isMobile ? 'swal2-popup-mobile ' + (isDarkMode ? 'dark-swal' : 'light-swal') : (isDarkMode ? 'dark-swal' : 'light-swal'),
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                    cancelButton: isDarkMode ? 'dark-swal-cancel' : 'light-swal-cancel'
                },
                didOpen: () => {
                    if (isMobile) {
                        const popup = document.querySelector('.swal2-popup');
                        if (popup) {
                            popup.style.maxHeight = maxHeight;
                            popup.style.overflowY = 'auto';
                        }
                        const htmlContainer = document.querySelector('.swal2-html-container');
                        if (htmlContainer) {
                            htmlContainer.style.maxHeight = 'calc(90vh - 140px)';
                            htmlContainer.style.overflowY = 'auto';
                        }
                    }
                    // Inicializar países según idioma por defecto
                    updatePaises();
                    // Focus en el select de idioma
                    document.getElementById('extraerIdioma')?.focus();
                },
                preConfirm: () => {
                    const idioma = document.getElementById('extraerIdioma').value;
                    const pais = document.getElementById('extraerPais').value;
                    const ciudad = document.getElementById('extraerCiudad').value.trim();
                    const industria = document.getElementById('extraerIndustria').value;
                    const industriaPersonalizada = document.getElementById('extraerIndustriaPersonalizada')?.value?.trim() || '';
                    const industriaFinal = industria === 'Otro' && industriaPersonalizada ? industriaPersonalizada : industria;
                    return { 
                        idioma: idioma || null,
                        pais: pais || null,
                        ciudad: ciudad || null,
                        industria: industriaFinal || null
                    };
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    extraerClientes(result.value);
                }
            });
            
            // Función para actualizar países según idioma seleccionado
            window.toggleIndustriaPersonalizada = function() {
                const industriaSelect = document.getElementById('extraerIndustria');
                const industriaPersonalizadaContainer = document.getElementById('industriaPersonalizadaContainer');
                const industriaPersonalizadaInput = document.getElementById('extraerIndustriaPersonalizada');
                
                if (industriaSelect && industriaPersonalizadaContainer) {
                    if (industriaSelect.value === 'Otro') {
                        industriaPersonalizadaContainer.style.display = 'block';
                        if (industriaPersonalizadaInput) {
                            industriaPersonalizadaInput.focus();
                        }
                    } else {
                        industriaPersonalizadaContainer.style.display = 'none';
                        if (industriaPersonalizadaInput) {
                            industriaPersonalizadaInput.value = '';
                        }
                    }
                }
            };
            
            window.updatePaises = function() {
                const idioma = document.getElementById('extraerIdioma').value;
                const paisSelect = document.getElementById('extraerPais');
                const paises = paisesPorIdioma[idioma] || [];
                
                // Limpiar opciones actuales
                paisSelect.innerHTML = '<option value="">Todos los países</option>';
                
                // Agregar países según idioma
                paises.forEach(pais => {
                    const option = document.createElement('option');
                    option.value = pais.code;
                    option.textContent = pais.name;
                    paisSelect.appendChild(option);
                });
            };
        }
        
        // Extraer clientes
        async function extraerClientes(filtros) {
            const isDarkMode = document.documentElement.classList.contains('dark');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Obtener nombres completos
            let paisNombre = '';
            if (filtros.pais) {
                const paisSelect = document.getElementById('extraerPais');
                if (paisSelect) {
                    paisNombre = paisSelect.options[paisSelect.selectedIndex].text;
                }
            }
            
            const ciudadNombre = filtros.ciudad ? filtros.ciudad.trim() : '';
            const industriaNombre = filtros.industria || '';
            
            // Crear texto con los 3 valores separados por un espacio
            const textoWebhook = [paisNombre, ciudadNombre, industriaNombre]
                .filter(val => val && val !== '') // Filtrar valores vacíos
                .join(' '); // Unir con un solo espacio
            
            const idiomaNombre = filtros.idioma ? {
                'es': 'Español',
                'en': 'English',
                'fr': 'Français',
                'de': 'Deutsch',
                'it': 'Italiano',
                'pt': 'Português'
            }[filtros.idioma] || filtros.idioma : 'todos los idiomas';
            
            let mensaje = `Extrayendo clientes para ${idiomaNombre}`;
            if (paisNombre) {
                mensaje += ` en ${paisNombre}`;
            }
            if (ciudadNombre) {
                mensaje += `, ciudad: ${ciudadNombre}`;
            }
            if (industriaNombre) {
                mensaje += `, industria: ${industriaNombre}`;
            }
            
            console.log('Extrayendo clientes con filtros:', filtros);
            console.log('Texto para webhook:', textoWebhook);
            
            // Enviar al webhook si está configurado
            if (currentWebhookUrl && textoWebhook) {
                try {
                    // Agregar el sufijo de idioma al texto
                    const idiomaSufijo = filtros.idioma || '';
                    const textoCompleto = textoWebhook + (idiomaSufijo ? ' ' + idiomaSufijo : '');
                    
                    await fetch(currentWebhookUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            pais_ciudad_industria: textoCompleto,
                            pais: paisNombre || '',
                            ciudad: ciudadNombre || '',
                            industria: industriaNombre || '',
                            idioma: idiomaSufijo || ''
                        })
                    });
                    console.log('Datos enviados al webhook:', textoCompleto);
                } catch (error) {
                    console.error('Error al enviar al webhook:', error);
                }
            }
            
            Swal.fire({
                icon: 'success',
                title: '¡Extracción iniciada!',
                text: mensaje,
                confirmButtonColor: '#D59F3B',
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                timer: 3000,
                showConfirmButton: false
            });
        }
        
        // Estilos para SweetAlert dark/light mode
        const style = document.createElement('style');
        style.textContent = `
            .swal2-container {
                z-index: 999999 !important;
            }
            .swal2-backdrop-show {
                z-index: 999998 !important;
                background-color: rgba(0, 0, 0, 0.75) !important;
            }
            .swal2-popup {
                z-index: 999999 !important;
            }
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
            .swal2-actions {
                flex-direction: row !important;
                justify-content: flex-end !important;
            }
            .swal2-confirm {
                background-color: #D59F3B !important;
                border-color: #D59F3B !important;
                color: white !important;
                order: 2 !important;
                margin-left: 10px !important;
            }
            .swal2-cancel {
                order: 1 !important;
            }
            .swal2-confirm:hover {
                background-color: #C78F2E !important;
                border-color: #C78F2E !important;
            }
            @media (max-width: 640px) {
                .swal2-popup {
                    width: 95% !important;
                    margin: 0.5rem !important;
                    padding: 1.25rem !important;
                    max-height: 90vh !important;
                    display: flex !important;
                    flex-direction: column !important;
                }
                .swal2-popup .swal2-html-container {
                    flex: 1;
                    overflow-y: auto;
                    max-height: calc(90vh - 140px);
                    padding: 0 !important;
                    width: 100% !important;
                }
                .swal2-popup .swal2-html-container form {
                    width: 100% !important;
                    max-width: 100% !important;
                }
                .swal2-popup .swal2-html-container form > div {
                    width: 100% !important;
                    max-width: 100% !important;
                }
                .swal2-popup .swal2-html-container select,
                .swal2-popup .swal2-html-container input[type="text"] {
                    width: 100% !important;
                    max-width: 100% !important;
                    box-sizing: border-box !important;
                }
                .swal2-popup .swal2-title {
                    font-size: 1.25rem !important;
                    margin-bottom: 1rem !important;
                }
            }
            
            /* Asegurar ancho completo en todos los tamaños */
            .swal2-html-container form {
                width: 100% !important;
            }
            
            .swal2-html-container form > div {
                width: 100% !important;
            }
            
            .swal2-html-container select,
            .swal2-html-container input[type="text"] {
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
            }
        `;
        document.head.appendChild(style);
        
        // Templates de email disponibles
        const emailTemplates = @json($templates ?? []);
        
        // Variables globales para el flujo de fases del modal de email
        let emailModalData = {
            clienteId: null,
            clienteEmail: '',
            clienteName: '',
            clienteWebsite: '',
            email: '',
            aiPrompt: '',
            subject: '',
            body: '',
            attachments: null
        };
        
        function openEmailModalForClient(clienteId, clienteEmail, clienteName, clienteWebsite) {
            // Configurar datos del cliente
            emailModalData.clienteId = clienteId;
            emailModalData.clienteEmail = clienteEmail || '';
            emailModalData.clienteName = clienteName || '';
            emailModalData.clienteWebsite = clienteWebsite || '';
            
            // Resetear datos
            emailModalData.email = emailModalData.clienteEmail;
            emailModalData.aiPrompt = '';
            emailModalData.subject = '';
            emailModalData.body = '';
            emailModalData.attachments = null;
            
            showEmailPhase1();
        }
        
        function loadEmailTemplate(templateId) {
            const aiGenerateContainer = document.getElementById('ai_generate_container');
            
            if (!templateId || !emailTemplates) {
                // Si no hay template seleccionado, mostrar el botón de AI
                if (aiGenerateContainer) {
                    aiGenerateContainer.style.display = 'block';
                }
                return;
            }
            
            const template = emailTemplates.find(t => t.id == templateId);
            if (!template) {
                if (aiGenerateContainer) {
                    aiGenerateContainer.style.display = 'block';
                }
                return;
            }
            
            // Cargar el template en los datos del modal
            emailModalData.aiPrompt = template.ai_prompt || '';
            emailModalData.subject = template.asunto || '';
            emailModalData.body = template.contenido || '';
            
            // Actualizar los campos visibles si existen
            const aiPromptField = document.getElementById('ai_prompt');
            if (aiPromptField) {
                aiPromptField.value = emailModalData.aiPrompt;
            }
            
            // Si estamos en fase 2, actualizar también esos campos
            const subjectField = document.getElementById('email_subject');
            const bodyField = document.getElementById('email_body');
            if (subjectField) {
                subjectField.value = emailModalData.subject;
            }
            if (bodyField) {
                bodyField.value = emailModalData.body;
            }
            
            // Ocultar el botón de generar con AI cuando hay template seleccionado
            if (aiGenerateContainer) {
                aiGenerateContainer.style.display = 'none';
            }
            
            // Mostrar el botón para volver a usar AI
            const showAiBtn = document.getElementById('show_ai_btn');
            if (showAiBtn) {
                showAiBtn.style.display = 'block';
            }
        }
        
        function showAIGenerateButton() {
            const aiGenerateContainer = document.getElementById('ai_generate_container');
            if (aiGenerateContainer) {
                aiGenerateContainer.style.display = 'block';
            }
            
            // Ocultar el botón "Usar AI en su lugar"
            const showAiBtn = document.getElementById('show_ai_btn');
            if (showAiBtn) {
                showAiBtn.style.display = 'none';
            }
            
            // Limpiar el template seleccionado
            const templateSelect = document.getElementById('email_template_select');
            if (templateSelect) {
                templateSelect.value = '';
            }
            
            // Limpiar los datos del template
            emailModalData.aiPrompt = '';
            emailModalData.subject = '';
            emailModalData.body = '';
            
            // Limpiar los campos
            const aiPromptField = document.getElementById('ai_prompt');
            if (aiPromptField) {
                aiPromptField.value = '';
            }
        }
        
        function showEmailPhase1() {
            const isMobile = window.innerWidth < 640;
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            let modalWidth = '95%';
            if (window.innerWidth >= 1024) {
                modalWidth = '700px';
            } else if (window.innerWidth >= 640) {
                modalWidth = '600px';
            }
            
            // Generar opciones de templates
            let templatesOptions = '<option value="">Seleccionar template (opcional)</option>';
            if (emailTemplates && emailTemplates.length > 0) {
                emailTemplates.forEach(template => {
                    templatesOptions += `<option value="${template.id}">${template.nombre}</option>`;
                });
            }
            
            const html = `
                <div class="space-y-3 text-left">
                    <div class="flex items-center justify-center gap-1 mb-3">
                        <div class="w-2 h-2 rounded-full bg-violet-600"></div>
                        <div class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-600"></div>
                        <div class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-600"></div>
                    </div>
                    
                    ${emailTemplates && emailTemplates.length > 0 ? `
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'}">Template guardado (opcional)</label>
                            <button type="button" onclick="showAIGenerateButton()" id="show_ai_btn" style="display: none;"
                                class="text-xs text-violet-600 dark:text-violet-400 hover:text-violet-700 dark:hover:text-violet-300 underline">
                                Usar AI en su lugar
                            </button>
                        </div>
                        <select id="email_template_select" onchange="loadEmailTemplate(this.value)"
                            class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none">
                            ${templatesOptions}
                        </select>
                    </div>
                    ` : ''}
                    
                    <div>
                        <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-2">Email destinatario <span class="text-red-500">*</span></label>
                        <input type="email" id="email_destinatario" value="${emailModalData.email}" required
                            class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-2">Instrucciones para AI (opcional)</label>
                        <textarea id="ai_prompt" rows="5" placeholder="Ej: Genera un email profesional..."
                            class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none resize-none">${emailModalData.aiPrompt}</textarea>
                        <div id="ai_generate_container">
                            <button type="button" onclick="generateEmailWithAI()" id="generateEmailBtn"
                                class="mt-2 w-full px-3 py-2 bg-violet-600 hover:bg-violet-500 text-white font-semibold rounded-lg transition-all flex items-center justify-center gap-2 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                                </svg>
                                <span>Generar con AI</span>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: '<div class="flex items-center gap-2"><svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M24 5.457v13.909c0 .904-.732 1.636-1.636 1.636h-3.819V11.73L12 16.64l-6.545-4.91v9.273H1.636A1.636 1.636 0 0 1 0 21.366V5.457c0-2.023 2.309-3.178 3.927-1.964L5.455 4.64 12 9.548l6.545-4.91 1.528-1.145C21.69 2.28 24 3.434 24 5.457z"/></svg><span>Crear Email - Paso 1</span></div>',
                html: html,
                width: modalWidth,
                padding: isMobile ? '1rem' : '1.5rem',
                showCancelButton: true,
                confirmButtonText: 'Siguiente',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#8b5cf6',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                reverseButtons: true,
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                    cancelButton: isDarkMode ? 'dark-swal-cancel' : 'light-swal-cancel'
                },
                preConfirm: () => {
                    const email = document.getElementById('email_destinatario').value;
                    if (!email) {
                        Swal.showValidationMessage('El email destinatario es requerido');
                        return false;
                    }
                    emailModalData.email = email;
                    const aiPromptField = document.getElementById('ai_prompt');
                    if (aiPromptField) {
                        emailModalData.aiPrompt = aiPromptField.value;
                    }
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    showEmailPhase2();
                }
            });
        }
        
        function showEmailPhase2() {
            const isMobile = window.innerWidth < 640;
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            let modalWidth = '95%';
            if (window.innerWidth >= 1024) {
                modalWidth = '700px';
            } else if (window.innerWidth >= 640) {
                modalWidth = '600px';
            }
            
            const html = `
                <div class="space-y-3 text-left">
                    <div class="flex items-center justify-center gap-1 mb-3">
                        <div class="w-2 h-2 rounded-full bg-violet-600"></div>
                        <div class="w-2 h-2 rounded-full bg-violet-600"></div>
                        <div class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-600"></div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-2">Asunto <span class="text-red-500">*</span></label>
                        <input type="text" id="email_subject" value="${emailModalData.subject}" required placeholder="Asunto del email"
                            class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-2">Mensaje <span class="text-red-500">*</span></label>
                        <textarea id="email_body" rows="10" required placeholder="Escribe o genera el contenido del email..."
                            class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none resize-none">${emailModalData.body}</textarea>
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: '<div class="flex items-center gap-2"><svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M24 5.457v13.909c0 .904-.732 1.636-1.636 1.636h-3.819V11.73L12 16.64l-6.545-4.91v9.273H1.636A1.636 1.636 0 0 1 0 21.366V5.457c0-2.023 2.309-3.178 3.927-1.964L5.455 4.64 12 9.548l6.545-4.91 1.528-1.145C21.69 2.28 24 3.434 24 5.457z"/></svg><span>Crear Email - Paso 2</span></div>',
                html: html,
                width: modalWidth,
                padding: isMobile ? '1rem' : '1.5rem',
                showCancelButton: true,
                confirmButtonText: 'Siguiente',
                cancelButtonText: 'Anterior',
                confirmButtonColor: '#8b5cf6',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                reverseButtons: true,
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                    cancelButton: isDarkMode ? 'dark-swal-cancel' : 'light-swal-cancel'
                },
                preConfirm: () => {
                    const subject = document.getElementById('email_subject').value;
                    const body = document.getElementById('email_body').value;
                    if (!subject || !body) {
                        Swal.showValidationMessage('Por favor, completa el asunto y el mensaje');
                        return false;
                    }
                    emailModalData.subject = subject;
                    emailModalData.body = body;
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    showEmailPhase3();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    showEmailPhase1();
                }
            });
        }
        
        function showEmailPhase3() {
            const isMobile = window.innerWidth < 640;
            const isDarkMode = document.documentElement.classList.contains('dark');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            let modalWidth = '95%';
            if (window.innerWidth >= 1024) {
                modalWidth = '700px';
            } else if (window.innerWidth >= 640) {
                modalWidth = '600px';
            }
            
            const html = `
                <div class="space-y-3 text-left">
                    <div class="flex items-center justify-center gap-1 mb-3">
                        <div class="w-2 h-2 rounded-full bg-violet-600"></div>
                        <div class="w-2 h-2 rounded-full bg-violet-600"></div>
                        <div class="w-2 h-2 rounded-full bg-violet-600"></div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-2">Adjuntar archivos (opcional)</label>
                        <input type="file" id="email_attachments" multiple accept=".pdf,.jpg,.jpeg,.png,.gif,.webp"
                            class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none">
                        <p class="text-xs ${isDarkMode ? 'text-slate-400' : 'text-slate-500'} mt-1">PDF o imágenes (máx. 10MB por archivo)</p>
                        <div id="email_files_list" class="mt-2 space-y-1.5"></div>
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: '<div class="flex items-center gap-2"><svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M24 5.457v13.909c0 .904-.732 1.636-1.636 1.636h-3.819V11.73L12 16.64l-6.545-4.91v9.273H1.636A1.636 1.636 0 0 1 0 21.366V5.457c0-2.023 2.309-3.178 3.927-1.964L5.455 4.64 12 9.548l6.545-4.91 1.528-1.145C21.69 2.28 24 3.434 24 5.457z"/></svg><span>Crear Email - Paso 3</span></div>',
                html: html,
                width: modalWidth,
                padding: isMobile ? '1rem' : '1.5rem',
                showCancelButton: true,
                confirmButtonText: 'Enviar Email',
                cancelButtonText: 'Anterior',
                confirmButtonColor: '#8b5cf6',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                reverseButtons: true,
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                    cancelButton: isDarkMode ? 'dark-swal-cancel' : 'light-swal-cancel'
                },
                didOpen: () => {
                    const fileInput = document.getElementById('email_attachments');
                    const filesList = document.getElementById('email_files_list');
                    if (fileInput) {
                        fileInput.addEventListener('change', function(e) {
                            if (filesList) {
                                filesList.innerHTML = '';
                                Array.from(e.target.files).forEach((file, index) => {
                                    const fileItem = document.createElement('div');
                                    fileItem.className = `flex items-center justify-between p-1.5 rounded ${isDarkMode ? 'bg-slate-700' : 'bg-slate-100'}`;
                                    fileItem.innerHTML = `
                                        <span class="text-xs ${isDarkMode ? 'text-slate-300' : 'text-slate-700'}">${file.name}</span>
                                        <span class="text-xs ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
                                    `;
                                    filesList.appendChild(fileItem);
                                });
                            }
                        });
                    }
                },
                preConfirm: async () => {
                    const attachments = document.getElementById('email_attachments');
                    emailModalData.attachments = attachments && attachments.files && attachments.files.length > 0 ? attachments.files : null;
                    
                    const formData = new FormData();
                    formData.append('cliente_id', emailModalData.clienteId);
                    formData.append('email', emailModalData.email);
                    formData.append('subject', emailModalData.subject);
                    formData.append('body', emailModalData.body);
                    formData.append('ai_prompt', emailModalData.aiPrompt || '');
                    formData.append('from_bot_alpha', 'true'); // Marcar que viene de bot-alpha
                    
                    if (emailModalData.attachments) {
                        Array.from(emailModalData.attachments).forEach((file, index) => {
                            formData.append(`archivos[${index}]`, file);
                        });
                    }
                    
                    try {
                        Swal.fire({
                            title: 'Enviando...',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                            background: isDarkMode ? '#1e293b' : '#ffffff',
                            color: isDarkMode ? '#e2e8f0' : '#1e293b'
                        });
                        
                        const response = await fetch('{{ route("walee.emails.enviar") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            body: formData
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Email enviado!',
                                text: data.message || 'El email se ha enviado correctamente',
                                confirmButtonColor: '#8b5cf6',
                                background: isDarkMode ? '#1e293b' : '#ffffff',
                                color: isDarkMode ? '#e2e8f0' : '#1e293b'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Error al enviar el email',
                                confirmButtonColor: '#ef4444',
                                background: isDarkMode ? '#1e293b' : '#ffffff',
                                color: isDarkMode ? '#e2e8f0' : '#1e293b'
                            });
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de conexión',
                            text: error.message,
                            confirmButtonColor: '#ef4444',
                            background: isDarkMode ? '#1e293b' : '#ffffff',
                            color: isDarkMode ? '#e2e8f0' : '#1e293b'
                        });
                    }
                    
                    return false;
                }
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.cancel) {
                    showEmailPhase2();
                }
            });
        }
        
        async function generateEmailWithAI() {
            const generateBtn = document.getElementById('generateEmailBtn');
            const aiPrompt = document.getElementById('ai_prompt').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const clienteId = emailModalData.clienteId;
            const clienteName = emailModalData.clienteName;
            const clienteWebsite = emailModalData.clienteWebsite;
            
            generateBtn.disabled = true;
            generateBtn.innerHTML = `
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Generando...</span>
            `;
            
            try {
                const response = await fetch('{{ route("walee.emails.generar") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        cliente_id: clienteId,
                        ai_prompt: aiPrompt,
                        client_name: clienteName,
                        client_website: clienteWebsite,
                    }),
                });
                
                const data = await response.json();
                
                if (data.success) {
                    emailModalData.subject = data.subject;
                    emailModalData.body = data.body;
                    // Mostrar mensaje y avanzar a fase 2
                    Swal.fire({
                        icon: 'success',
                        title: 'Email generado',
                        text: 'El contenido ha sido generado con AI',
                        confirmButtonColor: '#8b5cf6',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        Swal.close();
                        showEmailPhase2();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al generar email',
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: error.message,
                    confirmButtonColor: '#ef4444'
                });
            } finally {
                generateBtn.disabled = false;
                generateBtn.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                    </svg>
                    <span>Generar con AI</span>
                `;
            }
        }
        
        // Delete individual client
        async function deleteCliente(clienteId, clienteName) {
            const isDarkMode = document.documentElement.classList.contains('dark');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const result = await Swal.fire({
                title: '¿Eliminar cliente?',
                text: `¿Estás seguro de que deseas eliminar a ${clienteName}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
            });
            
            if (!result.isConfirmed) {
                return;
            }
            
            try {
                const response = await fetch('{{ route("walee.clientes.en-proceso.delete") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        client_ids: [clienteId]
                    }),
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Remove client from DOM
                    const card = document.querySelector(`[data-client-id="${clienteId}"]`);
                    if (card) {
                        card.style.transition = 'opacity 0.3s, transform 0.3s';
                        card.style.opacity = '0';
                        card.style.transform = 'translateX(-20px)';
                        setTimeout(() => card.remove(), 300);
                    } else {
                        // If card doesn't have data-client-id, try to find by other means
                        location.reload();
                    }
                    
                    Swal.fire({
                        icon: 'success',
                        title: '¡Cliente eliminado!',
                        text: 'El cliente ha sido eliminado correctamente',
                        confirmButtonColor: '#10b981',
                        timer: 2000,
                        showConfirmButton: false,
                        background: isDarkMode ? '#1e293b' : '#ffffff',
                        color: isDarkMode ? '#e2e8f0' : '#1e293b',
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al eliminar el cliente',
                        confirmButtonColor: '#ef4444',
                        background: isDarkMode ? '#1e293b' : '#ffffff',
                        color: isDarkMode ? '#e2e8f0' : '#1e293b',
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión: ' + error.message,
                    confirmButtonColor: '#ef4444',
                    background: isDarkMode ? '#1e293b' : '#ffffff',
                    color: isDarkMode ? '#e2e8f0' : '#1e293b',
                });
            }
        }
        
        // Handle client card click
        function handleClientCardClick(event, clientId, clientName) {
            // Only handle if actions menu is open
            const menu = document.getElementById('actionsMenu');
            if (!menu || menu.classList.contains('hidden')) {
                return;
            }
            
            // Don't handle if clicking on checkbox, link, or buttons
            if (event.target.closest('input[type="checkbox"]') || 
                event.target.closest('a') || 
                event.target.closest('button')) {
                return;
            }
            
            // Toggle checkbox
            const checkbox = document.querySelector(`.client-checkbox[data-client-id="${clientId}"]`);
            if (checkbox) {
                checkbox.checked = !checkbox.checked;
                updateDeleteButton();
            }
        }
        
        // Handle client link click
        function handleClientLinkClick(event) {
            // If actions menu is open, prevent navigation
            const menu = document.getElementById('actionsMenu');
            if (menu && !menu.classList.contains('hidden')) {
                event.preventDefault();
                return false;
            }
        }
        
        // Toggle actions menu
        function toggleActionsMenu() {
            const menu = document.getElementById('actionsMenu');
            
            if (menu) {
                if (menu.classList.contains('hidden')) {
                    // Opening menu - show checkboxes
                    menu.classList.remove('hidden');
                    const checkboxes = document.querySelectorAll('.client-checkbox');
                    checkboxes.forEach(checkbox => {
                        checkbox.classList.remove('hidden');
                    });
                    // Add cursor pointer to cards
                    const cards = document.querySelectorAll('.client-card-selectable');
                    cards.forEach(card => {
                        card.style.cursor = 'pointer';
                    });
                } else {
                    // Closing menu - hide checkboxes and uncheck them
                    menu.classList.add('hidden');
                    const checkboxes = document.querySelectorAll('.client-checkbox');
                    checkboxes.forEach(checkbox => {
                        checkbox.classList.add('hidden');
                        checkbox.checked = false;
                    });
                    // Remove cursor pointer from cards
                    const cards = document.querySelectorAll('.client-card-selectable');
                    cards.forEach(card => {
                        card.style.cursor = '';
                    });
                }
                
                // Update delete button and select all checkbox
                updateDeleteButton();
                const selectAllCheckbox = document.getElementById('selectAll');
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = false;
                }
            }
        }
        
        // Toggle select all
        function toggleSelectAll(checked) {
            const checkboxes = document.querySelectorAll('.client-checkbox');
            checkboxes.forEach(checkbox => {
                // Only check visible checkboxes
                const card = checkbox.closest('[data-client-id]');
                if (card && !card.classList.contains('hidden')) {
                    checkbox.checked = checked;
                }
            });
            updateDeleteButton();
        }
        
        // Update delete button
        function updateDeleteButton() {
            const checkedBoxes = document.querySelectorAll('.client-checkbox:checked');
            const deleteBtn = document.getElementById('deleteSelectedBtn');
            const deleteCount = document.getElementById('deleteCount');
            
            if (deleteBtn && deleteCount) {
                if (checkedBoxes.length > 0) {
                    deleteBtn.classList.remove('hidden');
                    deleteCount.textContent = `Borrar (${checkedBoxes.length})`;
                } else {
                    deleteBtn.classList.add('hidden');
                }
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
            
            const isDarkMode = document.documentElement.classList.contains('dark');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const confirmMessage = clientIds.length === 1 
                ? `¿Estás seguro de que deseas borrar a ${clientNames[0]}?`
                : `¿Estás seguro de que deseas borrar ${clientIds.length} clientes?`;
            
            const result = await Swal.fire({
                title: '¿Eliminar cliente(s)?',
                text: confirmMessage,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
            });
            
            if (!result.isConfirmed) {
                return;
            }
            
            const deleteBtn = document.getElementById('deleteSelectedBtn');
            deleteBtn.disabled = true;
            deleteBtn.innerHTML = `
                <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
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
                        const card = checkbox.closest('[data-client-id]');
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
                    Swal.fire({
                        icon: 'success',
                        title: '¡Clientes eliminados!',
                        text: `${clientIds.length} ${clientIds.length === 1 ? 'cliente ha sido' : 'clientes han sido'} eliminado${clientIds.length === 1 ? '' : 's'} exitosamente`,
                        confirmButtonColor: '#10b981',
                        timer: 2000,
                        showConfirmButton: false,
                        background: isDarkMode ? '#1e293b' : '#ffffff',
                        color: isDarkMode ? '#e2e8f0' : '#1e293b',
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al borrar clientes',
                        confirmButtonColor: '#ef4444',
                        background: isDarkMode ? '#1e293b' : '#ffffff',
                        color: isDarkMode ? '#e2e8f0' : '#1e293b',
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión: ' + error.message,
                    confirmButtonColor: '#ef4444',
                    background: isDarkMode ? '#1e293b' : '#ffffff',
                    color: isDarkMode ? '#e2e8f0' : '#1e293b',
                });
            } finally {
                deleteBtn.disabled = false;
                deleteBtn.innerHTML = `
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span id="deleteCount">Borrar (0)</span>
                `;
            }
        }
    </script>
    @include('partials.walee-support-button')
</body>
</html>

