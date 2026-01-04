<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Contratos - {{ $cliente->name }}</title>
    <meta name="description" content="Contratos de {{ $cliente->name }}">
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
        // Obtener contratos del cliente
        $contratos = collect();
        
        // Buscar contratos por cliente_id del modelo Cliente
        if (isset($clienteFacturas) && $clienteFacturas) {
            $contratos = \App\Models\Contrato::where('cliente_id', $clienteFacturas->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        // Si no hay contratos por cliente_id, buscar también por correo
        if ($contratos->isEmpty()) {
            $emails = [];
            
            // Agregar email del Client si existe
            if (isset($cliente) && $cliente->email) {
                $emails[] = $cliente->email;
            }
            
            // Agregar email del Cliente si existe
            if (isset($clienteFacturas) && $clienteFacturas->correo) {
                $emails[] = $clienteFacturas->correo;
            }
            
            // Buscar contratos por cualquier email encontrado
            if (!empty($emails)) {
                $contratos = \App\Models\Contrato::whereIn('correo', array_unique($emails))
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        }
        
        $totalContratos = $contratos->count();
        $contratosEnviados = $contratos->whereNotNull('enviada_at')->count();
        $contratosPendientes = $contratos->whereNull('enviada_at')->count();
        $totalMonto = $contratos->sum('precio');
        
        // Obtener foto del cliente
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
    @endphp

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-walee-400/20 dark:bg-walee-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-emerald-400/10 dark:bg-emerald-400/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-walee-400/10 dark:bg-walee-400/5 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-4 py-6 sm:px-6 lg:px-8">
            @php $pageTitle = 'Contratos - ' . $cliente->name; @endphp
            @include('partials.walee-navbar')
            
            <!-- Cliente Info -->
            <div class="mb-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="rounded-xl bg-walee-50 dark:bg-walee-900/20 border border-walee-200 dark:border-walee-800 p-4">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('walee.cliente.detalle', $cliente->id) }}" class="w-12 h-12 rounded-lg overflow-hidden flex-shrink-0 hover:opacity-90 transition-opacity cursor-pointer border-2 border-walee-500/30 dark:border-walee-500/20">
                            @if($fotoUrl)
                                <img src="{{ $fotoUrl }}" alt="{{ $cliente->name }}" class="w-full h-full object-cover">
                            @else
                                <img src="https://images.icon-icons.com/1188/PNG/512/1490201150-client_82317.png" alt="{{ $cliente->name }}" class="w-full h-full object-cover">
                            @endif
                        </a>
                        <div class="flex-1 min-w-0">
                            <h2 class="text-lg font-semibold text-walee-900 dark:text-walee-300 truncate">{{ $cliente->name }}</h2>
                            <p class="text-sm text-walee-700 dark:text-walee-400 truncate">Contratos del Cliente</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-4 gap-3 mb-6 animate-fade-in-up" style="animation-delay: 0.15s;">
                <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-3 text-center">
                    <div class="text-xl font-bold text-slate-900 dark:text-white">{{ $totalContratos }}</div>
                    <div class="text-xs text-slate-600 dark:text-slate-400">Total</div>
                </div>
                <div class="bg-emerald-50 dark:bg-emerald-500/10 rounded-lg p-3 text-center">
                    <div class="text-xl font-bold text-emerald-600 dark:text-emerald-400">{{ $contratosEnviados }}</div>
                    <div class="text-xs text-emerald-600/80 dark:text-emerald-400/70">Enviados</div>
                </div>
                <div class="bg-amber-50 dark:bg-amber-500/10 rounded-lg p-3 text-center">
                    <div class="text-xl font-bold text-amber-600 dark:text-amber-400">{{ $contratosPendientes }}</div>
                    <div class="text-xs text-amber-600/80 dark:text-amber-400/70">Pendientes</div>
                </div>
                <div class="bg-blue-50 dark:bg-blue-500/10 rounded-lg p-3 text-center">
                    <div class="text-xl font-bold text-blue-600 dark:text-blue-400">₡{{ number_format($totalMonto, 2) }}</div>
                    <div class="text-xs text-blue-600/80 dark:text-blue-400/70">Total</div>
                </div>
            </div>
            
            <!-- Search -->
            <div class="mb-6 animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" id="searchInput" placeholder="Buscar por servicios o correo..." class="w-full pl-12 pr-4 py-3 bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all shadow-sm dark:shadow-none">
                </div>
            </div>
            
            <!-- Notifications -->
            <div id="notifications" class="fixed top-4 right-4 z-50 space-y-2"></div>
            
            <!-- Contratos List -->
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-4 shadow-sm dark:shadow-none mb-6">
                <h3 class="text-base font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Contratos del Cliente
                </h3>
                
                <div id="contratosList" class="space-y-2 max-h-96 overflow-y-auto">
                @forelse($contratos as $index => $contrato)
                    @php
                        $serviciosTexto = '';
                        if ($contrato->servicios && is_array($contrato->servicios)) {
                            $serviciosTexto = implode(', ', $contrato->servicios);
                        } elseif ($contrato->servicios) {
                            $serviciosTexto = $contrato->servicios;
                        }
                    @endphp
                    <div class="contrato-item bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg p-3 sm:p-4 hover:border-walee-400 dark:hover:border-walee-500/30 transition-all" 
                         data-search="{{ strtolower($serviciosTexto) }} {{ strtolower($contrato->correo ?? '') }}">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    @if($contrato->enviada_at)
                                    <span class="text-xs text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-400/10 px-2 py-0.5 rounded flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span class="hidden sm:inline">Enviado </span>{{ \Carbon\Carbon::parse($contrato->enviada_at)->format('d/m/Y') }}
                                    </span>
                                    @else
                                    <span class="text-xs text-amber-600 dark:text-amber-400 bg-amber-100 dark:bg-amber-400/10 px-2 py-0.5 rounded">No enviado</span>
                                    @endif
                                    @if($contrato->estado)
                                    <span class="text-xs text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-700/50 px-2 py-0.5 rounded">{{ ucfirst($contrato->estado) }}</span>
                                    @endif
                                    @if($contrato->idioma)
                                    <span class="text-xs text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-400/10 px-2 py-0.5 rounded">{{ strtoupper($contrato->idioma) }}</span>
                                    @endif
                                </div>
                                <p class="text-sm text-slate-700 dark:text-slate-300 mb-2 line-clamp-2">
                                    {{ $serviciosTexto ?: 'Sin servicios especificados' }}
                                </p>
                                <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 text-xs text-slate-500 dark:text-slate-400">
                                    <span>{{ $contrato->created_at?->format('d/m/Y') ?? 'Sin fecha' }}</span>
                                    @if($contrato->precio)
                                    <span class="font-semibold text-slate-900 dark:text-white text-sm">₡{{ number_format($contrato->precio, 2) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="grid grid-cols-4 gap-1.5 sm:flex sm:flex-nowrap sm:gap-2 sm:ml-3">
                                <button onclick="eliminarContrato({{ $contrato->id }})" class="px-2 sm:px-2.5 sm:px-3 py-2 bg-red-700 hover:bg-red-800 text-white text-xs font-medium rounded-lg transition-all flex items-center justify-center gap-0.5 sm:gap-1 sm:gap-1.5">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    <span class="hidden xs:inline">Eliminar</span>
                                </button>
                                <button onclick="verContratoModal({{ $contrato->id }})" class="px-2 sm:px-2.5 sm:px-3 py-2 bg-walee-500 hover:bg-walee-400 text-white text-xs font-medium rounded-lg transition-all flex items-center justify-center gap-0.5 sm:gap-1 sm:gap-1.5">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <span class="hidden xs:inline">Ver</span>
                                </button>
                                @if($contrato->pdf_path)
                                <button onclick="verPDFContrato({{ $contrato->id }})" class="px-2 sm:px-2.5 sm:px-3 py-2 bg-red-600 hover:bg-red-500 text-white text-xs font-medium rounded-lg transition-all flex items-center justify-center gap-0.5 sm:gap-1 sm:gap-1.5">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="hidden xs:inline">PDF</span>
                                </button>
                                @else
                                <button disabled class="px-2 sm:px-2.5 sm:px-3 py-2 bg-slate-300 dark:bg-slate-700 text-slate-500 dark:text-slate-500 text-xs font-medium rounded-lg transition-all flex items-center justify-center gap-0.5 sm:gap-1 sm:gap-1.5 cursor-not-allowed opacity-50">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="hidden xs:inline">PDF</span>
                                </button>
                                @endif
                                <button onclick="enviarContratoEmail({{ $contrato->id }}, '{{ $contrato->correo }}', {{ $contrato->enviada_at ? 'true' : 'false' }})" class="px-2 sm:px-2.5 sm:px-3 py-2 {{ $contrato->enviada_at ? 'bg-blue-600 hover:bg-blue-500' : 'bg-emerald-600 hover:bg-emerald-500' }} text-white text-xs font-medium rounded-lg transition-all flex items-center justify-center gap-0.5 sm:gap-1 sm:gap-1.5">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="hidden xs:inline">{{ $contrato->enviada_at ? 'Reenviar' : 'Enviar' }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-6 shadow-sm dark:shadow-none text-center">
                        <svg class="w-12 h-12 text-slate-400 dark:text-slate-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-slate-600 dark:text-slate-400 mb-4">No hay contratos para este cliente</p>
                    </div>
                @endforelse
                </div>
            </div>
            @else
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-6 shadow-sm dark:shadow-none text-center">
                <svg class="w-12 h-12 text-slate-400 dark:text-slate-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-slate-600 dark:text-slate-400 mb-4">No hay contratos para este cliente</p>
            </div>
            @endif
            
        </div>
    </div>
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Dark mode helper
        function isDarkMode() {
            return document.documentElement.classList.contains('dark');
        }
        
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const items = document.querySelectorAll('.contrato-item');
            
            items.forEach(item => {
                const searchData = item.dataset.search;
                if (searchData && searchData.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
        
        // Eliminar contrato
        async function eliminarContrato(contratoId) {
            const result = await Swal.fire({
                title: '¿Eliminar contrato?',
                text: '¿Está seguro de que desea eliminar este contrato? Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
                background: isDarkMode() ? '#1e293b' : '#ffffff',
                color: isDarkMode() ? '#e2e8f0' : '#1e293b',
            });
            
            if (!result.isConfirmed) return;
            
            try {
                Swal.fire({
                    title: 'Eliminando...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    background: isDarkMode() ? '#1e293b' : '#ffffff',
                    color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                });
                
                const response = await fetch(`/walee-contratos/${contratoId}/eliminar`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Eliminado!',
                        text: data.message,
                        confirmButtonColor: '#C78F2E',
                        background: isDarkMode() ? '#1e293b' : '#ffffff',
                        color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Error al eliminar el contrato');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'No se pudo eliminar el contrato',
                    confirmButtonColor: '#ef4444',
                    background: isDarkMode() ? '#1e293b' : '#ffffff',
                    color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                });
            }
        }
        
        // Ver contrato modal
        async function verContratoModal(contratoId) {
            try {
                Swal.fire({
                    title: 'Cargando contrato...',
                    allowOutsideClick: false,
                    background: isDarkMode() ? '#1e293b' : '#ffffff',
                    color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                const response = await fetch(`/walee-contratos/${contratoId}/json`);
                if (!response.ok) throw new Error('Error al cargar el contrato');
                
                const data = await response.json();
                if (!data.success) throw new Error(data.message || 'Error al cargar el contrato');
                
                const contrato = data.contrato;
                
                const modalHtml = `
                    <div class="text-left space-y-4 max-h-[70vh] overflow-y-auto">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-walee-500/10 to-walee-600/5 rounded-lg p-4 border border-walee-200 dark:border-walee-500/20">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-bold text-walee-600 dark:text-walee-400">Contrato #${contrato.id}</h3>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Creado: ${contrato.created_at}</p>
                                </div>
                                <div class="text-right">
                                    ${contrato.enviada_at ? '<span class="text-xs bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 px-2 py-1 rounded">Enviado</span>' : '<span class="text-xs bg-amber-100 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 px-2 py-1 rounded">Pendiente</span>'}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Servicios -->
                        <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-3">
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Servicios</p>
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">${contrato.servicios || 'Sin servicios especificados'}</p>
                        </div>
                        
                        <!-- Información -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-3">
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Precio</p>
                                <p class="text-sm font-medium text-slate-900 dark:text-white">₡${contrato.precio ? parseFloat(contrato.precio).toLocaleString('es-CR', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '0.00'}</p>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-3">
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Idioma</p>
                                <p class="text-sm font-medium text-slate-900 dark:text-white">${contrato.idioma ? contrato.idioma.toUpperCase() : 'N/A'}</p>
                            </div>
                        </div>
                        
                        <!-- Estado -->
                        ${contrato.estado ? `
                        <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-3">
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Estado</p>
                            <p class="text-sm font-medium text-slate-900 dark:text-white">${contrato.estado}</p>
                        </div>
                        ` : ''}
                        
                        <!-- Correo -->
                        ${contrato.correo ? `
                        <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-3">
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Correo</p>
                            <p class="text-sm text-slate-700 dark:text-slate-300">${contrato.correo}</p>
                        </div>
                        ` : ''}
                        
                        <!-- Fecha de envío -->
                        ${contrato.enviada_at ? `
                        <div class="bg-emerald-50 dark:bg-emerald-500/10 rounded-lg p-3 border border-emerald-200 dark:border-emerald-500/20">
                            <p class="text-xs text-emerald-600 dark:text-emerald-400 mb-1">Enviado el</p>
                            <p class="text-sm font-medium text-emerald-700 dark:text-emerald-300">${contrato.enviada_at}</p>
                        </div>
                        ` : ''}
                    </div>
                `;
                
                Swal.fire({
                    title: '',
                    html: modalHtml,
                    width: '90%',
                    maxWidth: '600px',
                    showConfirmButton: false,
                    showCloseButton: false,
                    allowOutsideClick: true,
                    allowEscapeKey: true,
                    background: isDarkMode() ? '#1e293b' : '#ffffff',
                    color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                    customClass: {
                        popup: 'z-[9999] relative',
                        container: 'z-[9999]',
                    },
                    didOpen: () => {
                        const popup = document.querySelector('.swal2-popup');
                        if (popup) {
                            const closeButton = document.createElement('button');
                            closeButton.innerHTML = `
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            `;
                            closeButton.className = 'absolute -top-3 -right-3 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg z-[10000] transition-all hover:scale-110 cursor-pointer';
                            closeButton.style.zIndex = '10000';
                            closeButton.onclick = () => Swal.close();
                            popup.appendChild(closeButton);
                        }
                    }
                });
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'No se pudo cargar el contrato',
                    confirmButtonColor: '#ef4444',
                    background: isDarkMode() ? '#1e293b' : '#ffffff',
                    color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                });
            }
        }
        
        // Ver PDF contrato
        function verPDFContrato(contratoId) {
            const pdfUrl = `/walee-contratos/${contratoId}/pdf`;
            
            // En móvil, abrir directamente en la misma pestaña
            const isMobile = window.innerWidth < 640;
            if (isMobile) {
                window.location.href = pdfUrl;
                return;
            }
            
            Swal.fire({
                title: '',
                html: `
                    <div style="width: 100%; height: calc(100vh - 120px); max-height: 800px; overflow: hidden;">
                        <iframe src="${pdfUrl}#toolbar=0&navpanes=0&scrollbar=0" style="width: 100%; height: 100%; border: none;"></iframe>
                    </div>
                `,
                width: '95%',
                maxWidth: '900px',
                padding: '0',
                showConfirmButton: false,
                showCloseButton: false,
                allowOutsideClick: true,
                allowEscapeKey: true,
                customClass: {
                    popup: 'z-[9999] relative p-0',
                    container: 'z-[9999]',
                    htmlContainer: 'p-0 m-0',
                },
                didOpen: () => {
                    const popup = document.querySelector('.swal2-popup');
                    if (popup) {
                        popup.style.zIndex = '9999';
                        popup.style.position = 'relative';
                        popup.style.padding = '0';
                        popup.style.maxHeight = 'calc(100vh - 40px)';
                        
                        const closeButton = document.createElement('button');
                        closeButton.innerHTML = `
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        `;
                        closeButton.className = 'absolute -top-3 -right-3 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg z-[10000] transition-all hover:scale-110 cursor-pointer';
                        closeButton.style.zIndex = '10000';
                        closeButton.onclick = () => Swal.close();
                        popup.appendChild(closeButton);
                    }
                },
                background: isDarkMode() ? '#1e293b' : '#ffffff',
                color: isDarkMode() ? '#e2e8f0' : '#1e293b',
            });
        }
        
        // Enviar contrato por email
        async function enviarContratoEmail(contratoId, correo, yaEnviada) {
            if (!correo) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Sin correo',
                    text: 'Este contrato no tiene correo electrónico asociado.',
                    confirmButtonColor: '#C78F2E',
                    background: isDarkMode() ? '#1e293b' : '#ffffff',
                    color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                });
                return;
            }
            
            const confirmText = yaEnviada 
                ? `¿Desea reenviar el contrato a ${correo}?` 
                : `¿Desea enviar el contrato a ${correo}?`;
            
            const result = await Swal.fire({
                title: yaEnviada ? 'Reenviar Contrato' : 'Enviar Contrato',
                text: confirmText,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: yaEnviada ? 'Sí, Reenviar' : 'Sí, Enviar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: yaEnviada ? '#2563eb' : '#10b981',
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
                background: isDarkMode() ? '#1e293b' : '#ffffff',
                color: isDarkMode() ? '#e2e8f0' : '#1e293b',
            });
            
            if (!result.isConfirmed) return;
            
            try {
                Swal.fire({
                    title: 'Enviando contrato...',
                    text: 'Por favor espere',
                    allowOutsideClick: false,
                    background: isDarkMode() ? '#1e293b' : '#ffffff',
                    color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                const response = await fetch(`/walee-contratos/${contratoId}/enviar-email`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Enviado!',
                        text: data.message,
                        confirmButtonColor: '#10b981',
                        background: isDarkMode() ? '#1e293b' : '#ffffff',
                        color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Error al enviar el contrato');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'No se pudo enviar el contrato',
                    confirmButtonColor: '#ef4444',
                    background: isDarkMode() ? '#1e293b' : '#ffffff',
                    color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                });
            }
        }
    </script>
    @include('partials.walee-support-button')
</body>
</html>

