<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Extraer Clientes</title>
    <meta name="description" content="Walee - Extracción de Clientes desde Google Maps">
    <meta name="theme-color" content="#D59F3B">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    @include('partials.walee-dark-mode-init')
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
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
        
        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(213, 159, 59, 0.3);
            }
            50% {
                box-shadow: 0 0 40px rgba(213, 159, 59, 0.5);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        .animate-spin {
            animation: spin 1s linear infinite;
        }
        
        .workflow-card {
            opacity: 0;
            animation: fadeInUp 0.5s ease-out forwards;
        }
        
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
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">
    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-walee-400/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-blue-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-4xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
            @php $pageTitle = 'Extraer Clientes'; @endphp
            @include('partials.walee-navbar')
            
            <!-- Search Form -->
            <div class="animate-fade-in-up mb-8">
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-3xl p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-xl bg-blue-500/10 dark:bg-blue-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-slate-800 dark:text-white">Iniciar Búsqueda</h2>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Encuentra negocios por ubicación e industria</p>
                        </div>
                    </div>
                    
                    <form id="searchForm" class="space-y-4">
                        @csrf
                        <div>
                            <label for="nombre_lugar" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Lugar</label>
                            <input 
                                type="text" 
                                id="nombre_lugar" 
                                name="nombre_lugar" 
                                placeholder="Ej: Heredia, San José, etc."
                                required
                                class="w-full px-4 py-3 bg-white dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all"
                            >
                        </div>
                        
                        <div>
                            <label for="industria" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tipo de Negocio</label>
                            <select 
                                id="industria" 
                                name="industria" 
                                required
                                class="w-full px-4 py-3 bg-white dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all"
                            >
                                <option value="" disabled selected>Selecciona un tipo...</option>
                                <option value="tienda_ropa">👕 Tienda de Ropa</option>
                                <option value="pizzeria">🍕 Pizzería</option>
                                <option value="restaurante">🍽️ Restaurante</option>
                                <option value="cafeteria">☕ Cafetería</option>
                                <option value="farmacia">💊 Farmacia</option>
                                <option value="supermercado">🛒 Supermercado</option>
                                <option value="peluqueria">✂️ Peluquería / Salón de Belleza</option>
                                <option value="gimnasio">💪 Gimnasio</option>
                                <option value="veterinaria">🐾 Veterinaria</option>
                                <option value="taller_mecanico">🔧 Taller Mecánico</option>
                                <option value="otro">📝 Otro</option>
                            </select>
                        </div>
                        
                        <div id="otroContainer" class="hidden">
                            <label for="industria_otro" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Especificar Tipo</label>
                            <input 
                                type="text" 
                                id="industria_otro" 
                                name="industria_otro" 
                                placeholder="Escribe el tipo de negocio..."
                                class="w-full px-4 py-3 bg-white dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all"
                            >
                        </div>
                        
                        <button 
                            type="submit" 
                            id="submitBtn"
                            class="w-full px-6 py-4 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl transition-all flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span>Iniciar Búsqueda</span>
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Notifications -->
            <div id="notifications" class="fixed top-4 right-4 z-50 space-y-2"></div>
            
            <!-- Workflows List -->
            <div class="animate-fade-in-up">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-slate-800 dark:text-white">Búsquedas Recientes</h2>
                    <button 
                        id="refreshBtn"
                        onclick="loadWorkflows()"
                        class="px-3 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-700 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all flex items-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Actualizar
                    </button>
                </div>
                
                <div id="workflowsList" class="space-y-4">
                    <!-- Workflows will be loaded here -->
                    <div class="text-center py-8">
                        <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <p class="text-slate-600 dark:text-slate-500">Cargando búsquedas...</p>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <footer class="text-center py-8 mt-8">
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    <span class="text-walee-400 dark:text-walee-400 font-medium">Walee</span> · websolutions.work
                </p>
            </footer>
        </div>
    </div>
    
    <!-- Result Modal -->
    <div id="resultModal" class="fixed inset-0 bg-black/80 dark:bg-black/90 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 max-w-2xl w-full max-h-[80vh] overflow-hidden">
            <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white" id="modalTitle">Resultado</h3>
                <button onclick="closeModal()" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-4 overflow-y-auto max-h-[60vh]" id="modalContent">
                <!-- Modal content will be inserted here -->
            </div>
        </div>
    </div>
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let autoRefreshInterval = null;
        
        // Industry display names
        const industryNames = {
            'tienda_ropa': '👕 Tienda de Ropa',
            'pizzeria': '🍕 Pizzería',
            'restaurante': '🍽️ Restaurante',
            'cafeteria': '☕ Cafetería',
            'farmacia': '💊 Farmacia',
            'supermercado': '🛒 Supermercado',
            'peluqueria': '✂️ Peluquería / Salón de Belleza',
            'gimnasio': '💪 Gimnasio',
            'veterinaria': '🐾 Veterinaria',
            'taller_mecanico': '🔧 Taller Mecánico',
            'otro': '📝 Otro',
        };
        
        // Show/hide "otro" input
        document.getElementById('industria').addEventListener('change', function() {
            const otroContainer = document.getElementById('otroContainer');
            if (this.value === 'otro') {
                otroContainer.classList.remove('hidden');
                document.getElementById('industria_otro').required = true;
            } else {
                otroContainer.classList.add('hidden');
                document.getElementById('industria_otro').required = false;
            }
        });
        
        // Form submission
        document.getElementById('searchForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const nombreLugar = document.getElementById('nombre_lugar').value;
            let industria = document.getElementById('industria').value;
            const industriaOtro = document.getElementById('industria_otro').value;
            
            if (industria === 'otro' && industriaOtro) {
                industria = industriaOtro;
            }
            
            // Disable button
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Iniciando búsqueda...</span>
            `;
            
            try {
                const response = await fetch('{{ route("walee.extraer.iniciar") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        nombre_lugar: nombreLugar,
                        industria: industria,
                    }),
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification('Búsqueda iniciada', 'El proceso ha comenzado. ID: ' + data.job_id.substring(0, 8), 'success');
                    document.getElementById('searchForm').reset();
                    document.getElementById('otroContainer').classList.add('hidden');
                    loadWorkflows();
                    startAutoRefresh();
                } else {
                    showNotification('Error', data.message || 'Error al iniciar la búsqueda', 'error');
                }
            } catch (error) {
                showNotification('Error', 'Error de conexión: ' + error.message, 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = `
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span>Iniciar Búsqueda</span>
                `;
            }
        });
        
        // Load workflows
        async function loadWorkflows() {
            const container = document.getElementById('workflowsList');
            
            try {
                const response = await fetch('{{ route("walee.extraer.workflows") }}');
                const data = await response.json();
                
                if (data.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-8">
                            <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <p class="text-slate-600 dark:text-slate-400">No hay búsquedas recientes</p>
                            <p class="text-sm text-slate-500 dark:text-slate-500 mt-1">Inicia una nueva búsqueda para ver resultados aquí</p>
                        </div>
                    `;
                    return;
                }
                
                let hasRunning = false;
                
                container.innerHTML = data.map((workflow, index) => {
                    if (workflow.status === 'running') hasRunning = true;
                    
                    const statusConfig = getStatusConfig(workflow.status);
                    const industryLabel = industryNames[workflow.data?.industria] || workflow.data?.industria || 'N/A';
                    const message = workflow.data?.message || workflow.step || 'N/A';
                    
                    return `
                        <div class="workflow-card bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 ${statusConfig.borderClass} rounded-2xl p-4" style="animation-delay: ${index * 0.1}s">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-lg">📍</span>
                                        <h3 class="font-semibold text-slate-900 dark:text-white truncate">${workflow.data?.nombre_lugar || 'Sin ubicación'}</h3>
                                    </div>
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        <span class="px-2 py-1 bg-slate-100 dark:bg-slate-700/50 rounded-lg text-xs text-slate-700 dark:text-slate-300">${industryLabel}</span>
                                        <span class="px-2 py-1 ${statusConfig.bgClass} rounded-lg text-xs ${statusConfig.textClass}">${statusConfig.label}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                                        <span class="${statusConfig.textClass}">${statusConfig.icon}</span>
                                        <span class="truncate">${message}</span>
                                    </div>
                                    ${workflow.status === 'running' ? `
                                        <div class="mt-3 w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                                            <div class="bg-blue-500 h-2 rounded-full transition-all duration-500" style="width: ${workflow.progress}%"></div>
                                        </div>
                                        <p class="text-xs text-slate-500 dark:text-slate-500 mt-1">${workflow.progress}% completado</p>
                                    ` : ''}
                                </div>
                                <div class="flex flex-col gap-2">
                                    ${workflow.status === 'completed' && workflow.result ? `
                                        <button onclick="showResult(${workflow.id})" class="px-3 py-2 bg-emerald-600/20 hover:bg-emerald-600/30 text-emerald-400 rounded-lg text-xs font-medium transition-colors">
                                            Ver Resultado
                                        </button>
                                    ` : ''}
                                    ${workflow.status === 'failed' ? `
                                        <button onclick="showError(${workflow.id})" class="px-3 py-2 bg-red-600/20 hover:bg-red-600/30 text-red-400 rounded-lg text-xs font-medium transition-colors">
                                            Ver Error
                                        </button>
                                        <button onclick="retryWorkflow(${workflow.id})" class="px-3 py-2 bg-amber-600/20 hover:bg-amber-600/30 text-amber-400 rounded-lg text-xs font-medium transition-colors">
                                            Reintentar
                                        </button>
                                    ` : ''}
                                    ${workflow.status === 'running' ? `
                                        <button onclick="stopWorkflow(${workflow.id})" class="px-3 py-2 bg-red-600/20 hover:bg-red-600/30 text-red-400 rounded-lg text-xs font-medium transition-colors">
                                            Detener
                                        </button>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');
                
                // Auto-refresh if there are running workflows
                if (hasRunning) {
                    startAutoRefresh();
                } else {
                    stopAutoRefresh();
                }
                
            } catch (error) {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <div class="w-12 h-12 mx-auto rounded-full bg-red-500/20 flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <p class="text-red-400">Error al cargar búsquedas</p>
                        <p class="text-sm text-slate-500 mt-1">${error.message}</p>
                    </div>
                `;
            }
        }
        
        function getStatusConfig(status) {
            switch(status) {
                case 'completed':
                    return {
                        label: '✅ Completado',
                        bgClass: 'bg-emerald-500/20',
                        textClass: 'text-emerald-400',
                        borderClass: 'border-emerald-500/30',
                        icon: '✓'
                    };
                case 'running':
                    return {
                        label: '🔄 Ejecutando',
                        bgClass: 'bg-blue-500/20',
                        textClass: 'text-blue-400',
                        borderClass: 'border-blue-500/30',
                        icon: '⟳'
                    };
                case 'failed':
                    return {
                        label: '❌ Fallido',
                        bgClass: 'bg-red-500/20',
                        textClass: 'text-red-400',
                        borderClass: 'border-red-500/30',
                        icon: '✕'
                    };
                case 'pending':
                    return {
                        label: '⏳ En Cola',
                        bgClass: 'bg-amber-500/20',
                        textClass: 'text-amber-400',
                        borderClass: 'border-amber-500/30',
                        icon: '⏳'
                    };
                default:
                    return {
                        label: status,
                        bgClass: 'bg-slate-500/20',
                        textClass: 'text-slate-400',
                        borderClass: 'border-slate-500/30',
                        icon: '•'
                    };
            }
        }
        
        function startAutoRefresh() {
            if (autoRefreshInterval) return;
            autoRefreshInterval = setInterval(loadWorkflows, 3000);
        }
        
        function stopAutoRefresh() {
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
                autoRefreshInterval = null;
            }
        }
        
        async function showResult(id) {
            try {
                const response = await fetch(`{{ url('/walee-extraer/workflow') }}/${id}`);
                const workflow = await response.json();
                
                document.getElementById('modalTitle').textContent = 'Resultado de la Búsqueda';
                document.getElementById('modalContent').innerHTML = `
                    <div class="space-y-4">
                        <div class="bg-slate-100 dark:bg-slate-800 rounded-xl p-4">
                            <h4 class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">Lugar</h4>
                            <p class="text-slate-900 dark:text-white">${workflow.data?.nombre_lugar || 'N/A'}</p>
                        </div>
                        <div class="bg-slate-100 dark:bg-slate-800 rounded-xl p-4">
                            <h4 class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">Industria</h4>
                            <p class="text-slate-900 dark:text-white">${industryNames[workflow.data?.industria] || workflow.data?.industria || 'N/A'}</p>
                        </div>
                        ${workflow.result ? `
                            <div class="bg-slate-100 dark:bg-slate-800 rounded-xl p-4">
                                <h4 class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">Clientes Encontrados</h4>
                                <pre class="text-slate-900 dark:text-white text-sm overflow-x-auto whitespace-pre-wrap">${JSON.stringify(workflow.result, null, 2)}</pre>
                            </div>
                        ` : '<p class="text-slate-600 dark:text-slate-400">Sin resultados disponibles</p>'}
                    </div>
                `;
                document.getElementById('resultModal').classList.remove('hidden');
            } catch (error) {
                showNotification('Error', 'Error al cargar resultado: ' + error.message, 'error');
            }
        }
        
        async function showError(id) {
            try {
                const response = await fetch(`{{ url('/walee-extraer/workflow') }}/${id}`);
                const workflow = await response.json();
                
                document.getElementById('modalTitle').textContent = 'Detalles del Error';
                document.getElementById('modalContent').innerHTML = `
                    <div class="space-y-4">
                        <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 rounded-xl p-4">
                            <h4 class="text-sm font-medium text-red-600 dark:text-red-400 mb-2">Mensaje de Error</h4>
                            <p class="text-slate-900 dark:text-white">${workflow.error_message || 'Error desconocido'}</p>
                        </div>
                        <div class="bg-slate-100 dark:bg-slate-800 rounded-xl p-4">
                            <h4 class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">Último Paso</h4>
                            <p class="text-slate-900 dark:text-white">${workflow.step || 'N/A'}</p>
                        </div>
                        <div class="bg-slate-100 dark:bg-slate-800 rounded-xl p-4">
                            <h4 class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">Datos de la Búsqueda</h4>
                            <p class="text-slate-900 dark:text-white">📍 ${workflow.data?.nombre_lugar || 'N/A'}</p>
                            <p class="text-slate-600 dark:text-slate-400 text-sm">${industryNames[workflow.data?.industria] || workflow.data?.industria || 'N/A'}</p>
                        </div>
                    </div>
                `;
                document.getElementById('resultModal').classList.remove('hidden');
            } catch (error) {
                showNotification('Error', 'Error al cargar detalles: ' + error.message, 'error');
            }
        }
        
        async function stopWorkflow(id) {
            if (!confirm('¿Estás seguro de que deseas detener esta búsqueda?')) return;
            
            try {
                const response = await fetch(`{{ url('/walee-extraer/workflow') }}/${id}/stop`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification('Detenido', 'La búsqueda ha sido detenida', 'success');
                    loadWorkflows();
                } else {
                    showNotification('Error', data.message || 'Error al detener', 'error');
                }
            } catch (error) {
                showNotification('Error', 'Error de conexión: ' + error.message, 'error');
            }
        }
        
        async function retryWorkflow(id) {
            if (!confirm('¿Estás seguro de que deseas reintentar esta búsqueda?')) return;
            
            try {
                const response = await fetch(`{{ url('/walee-extraer/workflow') }}/${id}/retry`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification('Reintentando', 'La búsqueda se ha reenviado. ID: ' + data.job_id.substring(0, 8), 'success');
                    loadWorkflows();
                    startAutoRefresh();
                } else {
                    showNotification('Error', data.message || 'Error al reintentar', 'error');
                }
            } catch (error) {
                showNotification('Error', 'Error de conexión: ' + error.message, 'error');
            }
        }
        
        function closeModal() {
            document.getElementById('resultModal').classList.add('hidden');
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
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 10);
            
            // Auto remove
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        }
        
        // Close modal on escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
        
        // Close modal on backdrop click
        document.getElementById('resultModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
        
        // Initial load
        loadWorkflows();
    </script>
    @include('partials.walee-support-button')
</body>
</html>

