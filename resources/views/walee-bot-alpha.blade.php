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
            transform: translateX(26px);
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
        
        // Aplicar paginación con los query parameters
        $clientes = $query->orderBy('updated_at', 'desc')
            ->paginate(5)
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
        <div class="relative max-w-[90rem] mx-auto px-3 py-4 sm:px-4 sm:py-6 lg:px-8">
            @php $pageTitle = 'Bot Alpha'; @endphp
            @include('partials.walee-navbar')
            
            <!-- Header -->
            <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0 mb-4 sm:mb-6 md:mb-8 animate-fade-in-up">
                <div>
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">
                        Bot Alpha
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1 hidden sm:block">Administración y configuración del Bot Alpha</p>
                </div>
                <div class="flex items-center gap-2 sm:gap-3">
                    <button onclick="openConfigModal()" class="px-3 py-1.5 sm:px-4 sm:py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white font-medium rounded-lg sm:rounded-xl transition-all flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Config</span>
                    </button>
                    <a href="{{ route('walee.emails.dashboard') }}" class="px-3 py-1.5 sm:px-4 sm:py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white font-medium rounded-lg sm:rounded-xl transition-all flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span class="hidden sm:inline">Volver</span>
                    </a>
                </div>
            </header>
            
            <!-- Switch de Encendido/Apagado -->
            <div class="mb-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm dark:shadow-none">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-base sm:text-lg font-semibold text-slate-900 dark:text-white mb-1">
                                Estado del Bot
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400">
                                Activa o desactiva el Bot Alpha
                            </p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" id="botToggle" onchange="toggleBot(this.checked)">
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Search Bar y Filtros -->
            <div class="mb-6 animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl sm:rounded-2xl p-4 shadow-sm dark:shadow-none">
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                        <!-- Search Bar -->
                        <div class="relative flex-1">
                            <input 
                                type="text" 
                                id="searchInput"
                                value="{{ $searchQuery }}"
                                placeholder="Buscar cliente por nombre, email o teléfono..."
                                class="w-full px-3 sm:px-4 py-2 sm:py-3 pl-10 sm:pl-12 rounded-xl sm:rounded-2xl bg-slate-100 dark:bg-slate-900/80 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500/50 focus:ring-2 focus:ring-blue-500/20 transition-all text-xs sm:text-sm"
                                onkeyup="handleSearch()"
                            >
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-slate-400 dark:text-slate-500 absolute left-3 sm:left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        
                        <!-- Filtro por Idioma -->
                        <div class="sm:w-64">
                            <select 
                                id="idiomaFilter"
                                onchange="handleFilter()"
                                class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-xl sm:rounded-2xl bg-slate-100 dark:bg-slate-900/80 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-white focus:outline-none focus:border-blue-500/50 focus:ring-2 focus:ring-blue-500/20 transition-all text-xs sm:text-sm"
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
            
            <!-- Lista de Clientes -->
            <div class="animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl sm:rounded-2xl p-4 shadow-sm dark:shadow-none">
                    <h2 class="text-base sm:text-lg font-semibold text-slate-900 dark:text-white mb-4">
                        Clientes en Proceso
                        <span class="text-xs sm:text-sm font-normal text-slate-500 dark:text-slate-400">
                            ({{ $clientes->total() }} {{ $clientes->total() == 1 ? 'cliente' : 'clientes' }})
                        </span>
                    </h2>
                    
                    <div class="space-y-2 sm:space-y-3" id="clientsList">
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
                            <a href="{{ route('walee.cliente.detalle', $cliente->id) }}" class="flex items-center gap-3 p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:border-blue-400 dark:hover:border-blue-500/30 hover:bg-blue-50/50 dark:hover:bg-blue-500/10 transition-all cursor-pointer group">
                                @if($fotoUrl)
                                    <img src="{{ $fotoUrl }}" alt="{{ $cliente->name }}" class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg object-cover border-2 border-blue-500/30 flex-shrink-0 group-hover:scale-110 transition-transform">
                                @else
                                    <img src="https://images.icon-icons.com/1188/PNG/512/1490201150-client_82317.png" alt="{{ $cliente->name }}" class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg object-cover border-2 border-blue-500/30 flex-shrink-0 group-hover:scale-110 transition-transform opacity-80">
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-xs sm:text-sm md:text-base text-slate-900 dark:text-white truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ $cliente->name ?: 'Sin nombre' }}</p>
                                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 truncate">{{ $cliente->email ?: 'Sin email' }}</p>
                                    @if($cliente->idioma)
                                        <p class="text-xs text-slate-500 dark:text-slate-500 mt-0.5">
                                            {{ $idiomasNombres[$cliente->idioma] ?? strtoupper($cliente->idioma) }}
                                        </p>
                                    @endif
                                </div>
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400 opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @empty
                            <div class="text-center py-12">
                                <p class="text-sm text-slate-500 dark:text-slate-400">No se encontraron clientes</p>
                            </div>
                        @endforelse
                    </div>
                    
                    <!-- Pagination -->
                    @if($clientes->hasPages())
                        <div class="mt-6 flex justify-center gap-2">
                            @if($clientes->onFirstPage())
                                <span class="px-4 py-2 bg-slate-100 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 rounded-lg cursor-not-allowed text-sm">Anterior</span>
                            @else
                                <a href="{{ $clientes->previousPageUrl() }}" class="px-4 py-2 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-900 dark:text-white rounded-lg transition-colors border border-slate-200 dark:border-slate-700 shadow-sm text-sm">Anterior</a>
                            @endif
                            
                            <span class="px-4 py-2 bg-slate-100 dark:bg-slate-800/50 text-slate-600 dark:text-slate-400 rounded-lg border border-slate-200 dark:border-slate-700 text-sm">
                                Página {{ $clientes->currentPage() }} de {{ $clientes->lastPage() }}
                            </span>
                            
                            @if($clientes->hasMorePages())
                                <a href="{{ $clientes->nextPageUrl() }}" class="px-4 py-2 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-900 dark:text-white rounded-lg transition-colors border border-slate-200 dark:border-slate-700 shadow-sm text-sm">Siguiente</a>
                            @else
                                <span class="px-4 py-2 bg-slate-100 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 rounded-lg cursor-not-allowed text-sm">Siguiente</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Footer -->
            <footer class="text-center py-4 sm:py-6 md:py-8 mt-6">
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-500">
                    <span class="text-walee-600 dark:text-walee-400 font-medium">Walee</span> · websolutions.work
                </p>
            </footer>
        </div>
    </div>
    
    <script>
        // Toggle Bot (solo diseño por ahora)
        function toggleBot(enabled) {
            console.log('Bot Alpha:', enabled ? 'Activado' : 'Desactivado');
            // Aquí se implementará la lógica real más adelante
        }
        
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
        
        // Abrir modal de configuración para webhook
        function openConfigModal() {
            const isDarkMode = document.documentElement.classList.contains('dark');
            const isMobile = window.innerWidth < 640;
            
            let modalWidth = '90%';
            if (window.innerWidth >= 1024) {
                modalWidth = '500px';
            } else if (window.innerWidth >= 640) {
                modalWidth = '450px';
            }
            
            Swal.fire({
                title: 'Configuración Webhook',
                html: `
                    <form id="webhookForm" class="text-left">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                URL del Webhook
                            </label>
                            <input 
                                type="url" 
                                id="webhookUrl" 
                                name="webhook_url"
                                placeholder="https://ejemplo.com/webhook"
                                class="w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            >
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                Ingresa la URL del webhook para recibir notificaciones
                            </p>
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
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                allowOutsideClick: true,
                allowEscapeKey: true,
                backdrop: true,
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                    cancelButton: isDarkMode ? 'dark-swal-cancel' : 'light-swal-cancel'
                },
                didOpen: () => {
                    // Focus en el input
                    document.getElementById('webhookUrl')?.focus();
                },
                preConfirm: () => {
                    const webhookUrl = document.getElementById('webhookUrl').value.trim();
                    
                    if (webhookUrl && !isValidUrl(webhookUrl)) {
                        Swal.showValidationMessage('Por favor ingresa una URL válida');
                        return false;
                    }
                    
                    return { webhook_url: webhookUrl };
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    saveWebhook(result.value.webhook_url);
                }
            });
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
        
        // Guardar webhook (solo diseño por ahora)
        function saveWebhook(webhookUrl) {
            console.log('Webhook guardado:', webhookUrl);
            // Aquí se implementará la lógica real para guardar el webhook más adelante
            
            Swal.fire({
                icon: 'success',
                title: '¡Webhook guardado!',
                text: 'La configuración se ha guardado correctamente',
                confirmButtonColor: '#D59F3B',
                timer: 2000,
                showConfirmButton: false
            });
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
            .swal2-actions {
                flex-direction: row-reverse !important;
            }
            .swal2-confirm {
                background-color: #D59F3B !important;
                border-color: #D59F3B !important;
                color: white !important;
            }
            .swal2-confirm:hover {
                background-color: #C78F2E !important;
                border-color: #C78F2E !important;
            }
            @media (max-width: 640px) {
                .swal2-popup {
                    width: 90% !important;
                    margin: 0.5rem !important;
                    padding: 1rem !important;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
    @include('partials.walee-support-button')
</body>
</html>

