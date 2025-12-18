<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Crear Email con AI</title>
    <meta name="description" content="Walee - Crear Email con Inteligencia Artificial">
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
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        .animate-spin {
            animation: spin 1s linear infinite;
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
<body class="bg-slate-950 text-white min-h-screen">
    @php
        $clientes = \App\Models\Client::orderBy('name')->get();
        
        // Obtener conteo de propuestas por cliente
        $propuestasPorCliente = \App\Models\PropuestaPersonalizada::selectRaw('cliente_id, COUNT(*) as total')
            ->whereNotNull('cliente_id')
            ->groupBy('cliente_id')
            ->pluck('total', 'cliente_id')
            ->toArray();
    @endphp

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-violet-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-walee-400/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-violet-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-4xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <!-- Header -->
            <header class="flex items-center justify-between mb-8 animate-fade-in-up">
                <div class="flex items-center gap-4">
                    <a href="{{ route('walee.emails') }}" class="w-10 h-10 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 flex items-center justify-center transition-all">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white flex items-center gap-2">
                            <svg class="w-7 h-7 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                            </svg>
                            Crear con AI
                        </h1>
                        <p class="text-sm text-slate-400">Genera emails personalizados</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center">
                        <span class="text-sm font-medium text-walee-400">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                </div>
            </header>
            
            <!-- Notifications -->
            <div id="notifications" class="fixed top-4 right-4 z-50 space-y-2"></div>
            
            <!-- Email Form -->
            <div class="animate-fade-in-up">
                <form id="emailForm" class="space-y-6">
                    @csrf
                    
                    <!-- Cliente Selection -->
                    <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6">
                        <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Destinatario
                        </h2>
                        
                        <!-- Custom Dropdown Container -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-300 mb-2">Cliente</label>
                            <input type="hidden" id="cliente_id" name="cliente_id" value="">
                            
                            <!-- Dropdown Trigger -->
                            <div class="relative">
                                <button 
                                    type="button" 
                                    id="dropdownTrigger"
                                    onclick="toggleDropdown()"
                                    class="w-full px-4 py-3 bg-slate-900/50 border border-slate-600 rounded-xl text-left text-white focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all flex items-center justify-between"
                                >
                                    <span id="selectedClientText" class="text-slate-400">Seleccionar cliente...</span>
                                    <svg class="w-5 h-5 text-slate-400 transition-transform" id="dropdownArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                
                                <!-- Dropdown Panel -->
                                <div id="dropdownPanel" class="hidden absolute z-50 mt-2 w-full bg-slate-900 border border-slate-700 rounded-xl shadow-2xl overflow-hidden">
                                    <!-- Search -->
                                    <div class="p-3 border-b border-slate-700">
                                        <div class="relative">
                                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                            </svg>
                                            <input 
                                                type="text" 
                                                id="clientSearch" 
                                                placeholder="Buscar cliente..."
                                                class="w-full pl-10 pr-4 py-2 bg-slate-800 border border-slate-600 rounded-lg text-white placeholder-slate-500 text-sm focus:border-violet-500 focus:outline-none"
                                                oninput="filterClients(this.value)"
                                            >
                                        </div>
                                    </div>
                                    
                                    <!-- Client List -->
                                    <div id="clientList" class="max-h-72 overflow-y-auto">
                                        @foreach($clientes as $cliente)
                                            @php
                                                $propuestasCount = $propuestasPorCliente[$cliente->id] ?? 0;
                                                $estadoConfig = match($cliente->estado) {
                                                    'propuesta_enviada' => ['bg' => 'bg-amber-500/20', 'text' => 'text-amber-400', 'label' => 'Propuesta'],
                                                    'propuesta_personalizada_enviada' => ['bg' => 'bg-violet-500/20', 'text' => 'text-violet-400', 'label' => 'Personalizada'],
                                                    'accepted' => ['bg' => 'bg-emerald-500/20', 'text' => 'text-emerald-400', 'label' => 'Activo'],
                                                    'pending' => ['bg' => 'bg-amber-500/20', 'text' => 'text-amber-400', 'label' => 'Pendiente'],
                                                    'listo_para_enviar' => ['bg' => 'bg-cyan-500/20', 'text' => 'text-cyan-400', 'label' => 'Listo'],
                                                    'rechazado' => ['bg' => 'bg-red-500/20', 'text' => 'text-red-400', 'label' => 'Rechazado'],
                                                    default => ['bg' => 'bg-slate-500/20', 'text' => 'text-slate-400', 'label' => 'Nuevo'],
                                                };
                                                $propuestasColor = $propuestasCount >= 3 ? 'bg-red-500' : ($propuestasCount >= 1 ? 'bg-amber-500' : 'bg-slate-600');
                                            @endphp
                                            <div 
                                                class="client-option p-3 hover:bg-slate-800 cursor-pointer transition-colors border-b border-slate-800 last:border-0"
                                                data-id="{{ $cliente->id }}"
                                                data-name="{{ $cliente->name }}"
                                                data-email="{{ $cliente->email }}"
                                                data-website="{{ $cliente->website }}"
                                                data-propuestas="{{ $propuestasCount }}"
                                                data-estado="{{ $cliente->estado }}"
                                                onclick="selectClient(this)"
                                            >
                                                <div class="flex items-center gap-3">
                                                    <!-- Avatar -->
                                                    <div class="w-10 h-10 rounded-xl {{ $estadoConfig['bg'] }} flex items-center justify-center flex-shrink-0">
                                                        <span class="text-sm font-bold {{ $estadoConfig['text'] }}">{{ strtoupper(substr($cliente->name, 0, 1)) }}</span>
                                                    </div>
                                                    
                                                    <!-- Info -->
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center gap-2">
                                                            <p class="font-medium text-white truncate">{{ $cliente->name }}</p>
                                                            @if($propuestasCount > 0)
                                                                <span class="px-1.5 py-0.5 text-[10px] font-bold {{ $propuestasColor }} text-white rounded">{{ $propuestasCount }}</span>
                                                            @endif
                                                        </div>
                                                        <div class="flex items-center gap-2 mt-0.5">
                                                            <span class="text-xs text-slate-400 truncate">{{ $cliente->email ?: 'Sin email' }}</span>
                                                            <span class="text-slate-600">·</span>
                                                            <span class="text-xs {{ $estadoConfig['text'] }}">{{ $estadoConfig['label'] }}</span>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Proposals indicator -->
                                                    @if($propuestasCount > 0)
                                                        <div class="flex-shrink-0 text-right">
                                                            <p class="text-xs text-slate-500">{{ $propuestasCount }} {{ $propuestasCount == 1 ? 'email' : 'emails' }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    <!-- No results -->
                                    <div id="noResults" class="hidden p-6 text-center">
                                        <p class="text-slate-400 text-sm">No se encontraron clientes</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Selected Client Card -->
                        <div id="clienteInfo" class="hidden mb-4 p-4 bg-gradient-to-r from-violet-500/10 to-slate-800/50 rounded-xl border border-violet-500/20">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div id="clienteAvatar" class="w-12 h-12 rounded-xl bg-violet-500/20 flex items-center justify-center">
                                        <span class="text-lg font-bold text-violet-400">?</span>
                                    </div>
                                    <div>
                                        <p id="clienteNombre" class="font-semibold text-white">Cliente</p>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span id="clienteEmail" class="text-xs text-slate-400">email@example.com</span>
                                            <span id="clienteEstadoBadge" class="px-2 py-0.5 text-[10px] font-medium bg-violet-500/20 text-violet-400 rounded-full">Estado</span>
                                        </div>
                                    </div>
                                </div>
                                <div id="propuestasInfo" class="text-right">
                                    <div class="flex items-center gap-2">
                                        <div>
                                            <p class="text-3xl font-bold text-violet-400" id="propuestasCount">0</p>
                                            <p class="text-[10px] text-slate-500 uppercase tracking-wide">enviados</p>
                                        </div>
                                        <button type="button" onclick="clearClient()" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-red-500/20 hover:text-red-400 flex items-center justify-center text-slate-400 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Email Input -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-300 mb-2">Email destinatario <span class="text-red-400">*</span></label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                required
                                placeholder="cliente@correo.com"
                                class="w-full px-4 py-3 bg-slate-900/50 border border-slate-600 rounded-xl text-white placeholder-slate-500 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all"
                            >
                        </div>
                    </div>
                    
                    <!-- AI Prompt -->
                    <div class="bg-gradient-to-br from-violet-500/10 to-violet-600/5 border border-violet-500/20 rounded-2xl p-6">
                        <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                            </svg>
                            Instrucciones para AI
                        </h2>
                        
                        <div class="mb-4">
                            <label for="ai_prompt" class="block text-sm font-medium text-slate-300 mb-2">¿Qué tipo de email necesitas?</label>
                            <textarea 
                                id="ai_prompt" 
                                name="ai_prompt" 
                                rows="3"
                                placeholder="Ej: Genera un email profesional de propuesta para un negocio de restaurante, mencionando servicios de diseño web y marketing digital..."
                                class="w-full px-4 py-3 bg-slate-900/50 border border-slate-600 rounded-xl text-white placeholder-slate-500 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all resize-none"
                            ></textarea>
                            <p class="text-xs text-slate-500 mt-2">Describe el tipo de email que quieres. Si está vacío, se generará una propuesta genérica.</p>
                        </div>
                        
                        <button 
                            type="button" 
                            id="generateBtn"
                            onclick="generateWithAI()"
                            class="w-full px-6 py-4 bg-violet-600 hover:bg-violet-500 text-white font-semibold rounded-xl transition-all flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                            </svg>
                            <span>Generar con AI</span>
                        </button>
                    </div>
                    
                    <!-- Email Content -->
                    <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6">
                        <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Contenido del Email
                        </h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="subject" class="block text-sm font-medium text-slate-300 mb-2">Asunto <span class="text-red-400">*</span></label>
                                <input 
                                    type="text" 
                                    id="subject" 
                                    name="subject" 
                                    required
                                    placeholder="Asunto del email"
                                    class="w-full px-4 py-3 bg-slate-900/50 border border-slate-600 rounded-xl text-white placeholder-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all"
                                >
                            </div>
                            
                            <div>
                                <label for="body" class="block text-sm font-medium text-slate-300 mb-2">Mensaje <span class="text-red-400">*</span></label>
                                <textarea 
                                    id="body" 
                                    name="body" 
                                    rows="10"
                                    required
                                    placeholder="Escribe o genera el contenido del email..."
                                    class="w-full px-4 py-3 bg-slate-900/50 border border-slate-600 rounded-xl text-white placeholder-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all resize-none"
                                ></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        id="submitBtn"
                        class="w-full px-6 py-4 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl transition-all flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        <span>Enviar Email</span>
                    </button>
                </form>
            </div>
            
            <!-- Footer -->
            <footer class="text-center py-8 mt-8">
                <p class="text-sm text-slate-500">
                    <span class="text-walee-400 font-medium">Walee</span> · websolutions.work
                </p>
            </footer>
        </div>
    </div>
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const estadoLabels = {
            'nuevo': { text: 'Nuevo', bg: 'bg-slate-500/20', color: 'text-slate-400' },
            'contactado': { text: 'Contactado', bg: 'bg-blue-500/20', color: 'text-blue-400' },
            'propuesta_enviada': { text: 'Propuesta', bg: 'bg-amber-500/20', color: 'text-amber-400' },
            'propuesta_personalizada_enviada': { text: 'Personalizada', bg: 'bg-violet-500/20', color: 'text-violet-400' },
            'accepted': { text: 'Activo', bg: 'bg-emerald-500/20', color: 'text-emerald-400' },
            'pending': { text: 'Pendiente', bg: 'bg-amber-500/20', color: 'text-amber-400' },
            'rechazado': { text: 'Rechazado', bg: 'bg-red-500/20', color: 'text-red-400' },
            'listo_para_enviar': { text: 'Listo', bg: 'bg-cyan-500/20', color: 'text-cyan-400' },
        };
        
        let isDropdownOpen = false;
        
        // Toggle dropdown
        function toggleDropdown() {
            const panel = document.getElementById('dropdownPanel');
            const arrow = document.getElementById('dropdownArrow');
            isDropdownOpen = !isDropdownOpen;
            
            if (isDropdownOpen) {
                panel.classList.remove('hidden');
                arrow.style.transform = 'rotate(180deg)';
                document.getElementById('clientSearch').focus();
            } else {
                panel.classList.add('hidden');
                arrow.style.transform = 'rotate(0deg)';
            }
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('dropdownTrigger');
            const panel = document.getElementById('dropdownPanel');
            
            if (!dropdown.contains(e.target) && !panel.contains(e.target)) {
                panel.classList.add('hidden');
                document.getElementById('dropdownArrow').style.transform = 'rotate(0deg)';
                isDropdownOpen = false;
            }
        });
        
        // Filter clients
        function filterClients(query) {
            const options = document.querySelectorAll('.client-option');
            const noResults = document.getElementById('noResults');
            let hasResults = false;
            
            query = query.toLowerCase();
            
            options.forEach(option => {
                const name = option.dataset.name.toLowerCase();
                const email = (option.dataset.email || '').toLowerCase();
                
                if (name.includes(query) || email.includes(query)) {
                    option.style.display = 'block';
                    hasResults = true;
                } else {
                    option.style.display = 'none';
                }
            });
            
            noResults.classList.toggle('hidden', hasResults);
        }
        
        // Select client
        function selectClient(element) {
            const id = element.dataset.id;
            const name = element.dataset.name;
            const email = element.dataset.email;
            const website = element.dataset.website;
            const propuestas = element.dataset.propuestas || '0';
            const estado = element.dataset.estado || 'nuevo';
            
            // Update hidden input
            document.getElementById('cliente_id').value = id;
            
            // Update trigger text
            document.getElementById('selectedClientText').innerHTML = `
                <span class="text-white">${name}</span>
                ${email ? `<span class="text-slate-400 text-sm ml-2">${email}</span>` : ''}
            `;
            
            // Update email field
            if (email) {
                document.getElementById('email').value = email;
            }
            
            // Show client info card
            const clienteInfo = document.getElementById('clienteInfo');
            const estadoInfo = estadoLabels[estado] || estadoLabels['nuevo'];
            
            document.getElementById('clienteAvatar').innerHTML = `<span class="text-lg font-bold text-violet-400">${name.charAt(0).toUpperCase()}</span>`;
            document.getElementById('clienteNombre').textContent = name;
            document.getElementById('clienteEmail').textContent = email || 'Sin email';
            
            const badge = document.getElementById('clienteEstadoBadge');
            badge.textContent = estadoInfo.text;
            badge.className = `px-2 py-0.5 text-[10px] font-medium ${estadoInfo.bg} ${estadoInfo.color} rounded-full`;
            
            const propuestasCount = document.getElementById('propuestasCount');
            propuestasCount.textContent = propuestas;
            
            // Color based on count
            const count = parseInt(propuestas);
            propuestasCount.classList.remove('text-violet-400', 'text-amber-400', 'text-red-400');
            if (count >= 3) {
                propuestasCount.classList.add('text-red-400');
            } else if (count >= 1) {
                propuestasCount.classList.add('text-amber-400');
            } else {
                propuestasCount.classList.add('text-violet-400');
            }
            
            clienteInfo.classList.remove('hidden');
            
            // Close dropdown
            document.getElementById('dropdownPanel').classList.add('hidden');
            document.getElementById('dropdownArrow').style.transform = 'rotate(0deg)';
            isDropdownOpen = false;
            
            // Clear search
            document.getElementById('clientSearch').value = '';
            filterClients('');
        }
        
        // Clear client selection
        function clearClient() {
            document.getElementById('cliente_id').value = '';
            document.getElementById('selectedClientText').innerHTML = '<span class="text-slate-400">Seleccionar cliente...</span>';
            document.getElementById('email').value = '';
            document.getElementById('clienteInfo').classList.add('hidden');
        }
        
        // Generate with AI
        async function generateWithAI() {
            const generateBtn = document.getElementById('generateBtn');
            const clienteId = document.getElementById('cliente_id').value;
            const aiPrompt = document.getElementById('ai_prompt').value;
            
            // Get client info from selected option in dropdown
            const selectedOption = document.querySelector(`.client-option[data-id="${clienteId}"]`);
            const clientName = selectedOption ? selectedOption.dataset.name : 'el cliente';
            const clientWebsite = selectedOption ? selectedOption.dataset.website : '';
            
            // Disable button
            generateBtn.disabled = true;
            generateBtn.innerHTML = `
                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Generando con AI...</span>
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
                        client_name: clientName,
                        client_website: clientWebsite,
                    }),
                });
                
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('subject').value = data.subject;
                    document.getElementById('body').value = data.body;
                    showNotification('Email generado', 'El contenido ha sido generado con AI', 'success');
                } else {
                    showNotification('Error', data.message || 'Error al generar email', 'error');
                }
            } catch (error) {
                showNotification('Error', 'Error de conexión: ' + error.message, 'error');
            } finally {
                generateBtn.disabled = false;
                generateBtn.innerHTML = `
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                    </svg>
                    <span>Generar con AI</span>
                `;
            }
        }
        
        // Form submission
        document.getElementById('emailForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const clienteId = document.getElementById('cliente_id').value;
            const email = document.getElementById('email').value;
            const subject = document.getElementById('subject').value;
            const body = document.getElementById('body').value;
            const aiPrompt = document.getElementById('ai_prompt').value;
            
            if (!email || !subject || !body) {
                showNotification('Error', 'Por favor completa todos los campos requeridos', 'error');
                return;
            }
            
            // Disable button
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Enviando...</span>
            `;
            
            try {
                const response = await fetch('{{ route("walee.emails.enviar") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        cliente_id: clienteId,
                        email: email,
                        subject: subject,
                        body: body,
                        ai_prompt: aiPrompt,
                    }),
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification('Email enviado', 'El email ha sido enviado correctamente', 'success');
                    // Reset form
                    document.getElementById('emailForm').reset();
                } else {
                    showNotification('Error', data.message || 'Error al enviar email', 'error');
                }
            } catch (error) {
                showNotification('Error', 'Error de conexión: ' + error.message, 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = `
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    <span>Enviar Email</span>
                `;
            }
        });
        
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
        
        // Auto-select client from URL parameter
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const clienteId = urlParams.get('cliente_id');
            
            if (clienteId) {
                const clientOption = document.querySelector(`.client-option[data-id="${clienteId}"]`);
                if (clientOption) {
                    selectClient(clientOption);
                    showNotification('Cliente seleccionado', 'El cliente ha sido seleccionado automáticamente', 'success');
                }
            }
        });
    </script>
</body>
</html>

