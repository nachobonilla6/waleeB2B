<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Facturas</title>
    <meta name="description" content="Lista de Facturas">
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
<body class="bg-slate-950 text-white min-h-screen">
    @php
        $facturas = \App\Models\Factura::with('cliente')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $totalFacturas = $facturas->count();
        $facturasEnviadas = $facturas->whereNotNull('enviada_at')->count();
        $facturasPendientes = $facturas->whereNull('enviada_at')->count();
        $totalMonto = $facturas->sum('total');
    @endphp

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-walee-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-emerald-400/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-walee-400/5 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-4xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <!-- Header -->
            <header class="flex items-center justify-between mb-6 animate-fade-in-up">
                <div class="flex items-center gap-4">
                    <a href="{{ route('walee.facturas') }}" class="w-10 h-10 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 flex items-center justify-center transition-all">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white flex items-center gap-2">
                            <svg class="w-7 h-7 text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Facturas
                        </h1>
                        <p class="text-sm text-slate-400">{{ $totalFacturas }} facturas en total</p>
                    </div>
                </div>
                
                <a href="{{ route('walee.facturas.crear') }}" class="w-10 h-10 rounded-xl bg-walee-500 hover:bg-walee-400 flex items-center justify-center transition-all">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </a>
            </header>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-3 gap-3 mb-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-white">{{ $totalFacturas }}</div>
                    <div class="text-xs text-slate-400">Total</div>
                </div>
                <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-emerald-400">{{ $facturasEnviadas }}</div>
                    <div class="text-xs text-emerald-400/70">Enviadas</div>
                </div>
                <div class="bg-amber-500/10 border border-amber-500/20 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-amber-400">{{ $facturasPendientes }}</div>
                    <div class="text-xs text-amber-400/70">Pendientes</div>
                </div>
            </div>
            
            <!-- Search -->
            <div class="mb-6 animate-fade-in-up" style="animation-delay: 0.15s;">
                <div class="relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" id="searchInput" placeholder="Buscar por cliente, número o concepto..." class="w-full pl-12 pr-4 py-3 bg-slate-800/50 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all">
                </div>
            </div>
            
            <!-- Notifications -->
            <div id="notifications" class="fixed top-4 right-4 z-50 space-y-2"></div>
            
            <!-- Facturas List -->
            <div id="facturasList" class="space-y-3">
                @forelse($facturas as $index => $factura)
                    <div class="factura-card bg-slate-800/50 border border-slate-700/50 rounded-2xl p-4 hover:border-walee-500/30 transition-all animate-fade-in-up" 
                         style="animation-delay: {{ 0.2 + ($index * 0.05) }}s;"
                         data-search="{{ strtolower($factura->cliente?->nombre_empresa ?? '') }} {{ strtolower($factura->numero_factura ?? '') }} {{ strtolower($factura->concepto ?? '') }} {{ strtolower($factura->correo ?? '') }}">
                        
                        <div class="flex items-start gap-4">
                            <!-- Icon/Avatar -->
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 {{ $factura->enviada_at ? 'bg-emerald-500/20' : 'bg-amber-500/20' }}">
                                @if($factura->enviada_at)
                                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @else
                                    <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @endif
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-xs font-mono text-walee-400 bg-walee-400/10 px-2 py-0.5 rounded">
                                                #{{ $factura->numero_factura }}
                                            </span>
                                            @if($factura->enviada_at)
                                                <span class="text-xs text-emerald-400 bg-emerald-400/10 px-2 py-0.5 rounded flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Enviada
                                                </span>
                                            @else
                                                <span class="text-xs text-amber-400 bg-amber-400/10 px-2 py-0.5 rounded">
                                                    Pendiente
                                                </span>
                                            @endif
                                            
                                            @if($factura->estado === 'pagada')
                                                <span class="text-xs text-emerald-400 bg-emerald-400/10 px-2 py-0.5 rounded">
                                                    Pagada
                                                </span>
                                            @elseif($factura->estado === 'vencida')
                                                <span class="text-xs text-red-400 bg-red-400/10 px-2 py-0.5 rounded">
                                                    Vencida
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <h3 class="font-semibold text-white truncate">
                                            {{ $factura->cliente?->nombre_empresa ?? 'Sin cliente' }}
                                        </h3>
                                        
                                        <p class="text-sm text-slate-400 truncate">
                                            {{ Str::limit($factura->concepto, 60) }}
                                        </p>
                                        
                                        <div class="flex items-center gap-3 mt-2 text-xs text-slate-500">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                {{ $factura->fecha_emision?->format('d/m/Y') ?? 'Sin fecha' }}
                                            </span>
                                            @if($factura->correo)
                                                <span class="flex items-center gap-1 truncate">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                    </svg>
                                                    {{ Str::limit($factura->correo, 20) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Total -->
                                    <div class="text-right flex-shrink-0">
                                        <div class="text-lg font-bold text-white">
                                            ₡{{ number_format($factura->total, 0, ',', '.') }}
                                        </div>
                                        @if($factura->enviada_at)
                                            <div class="text-xs text-slate-500">
                                                {{ $factura->enviada_at->diffForHumans() }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Actions -->
                                <div class="flex items-center gap-2 mt-3 pt-3 border-t border-slate-700/50">
                                    @if(!$factura->enviada_at)
                                        <button onclick="enviarFactura({{ $factura->id }})" class="flex-1 px-3 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-medium rounded-lg transition-all flex items-center justify-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                            </svg>
                                            Enviar
                                        </button>
                                    @else
                                        <button onclick="reenviarFactura({{ $factura->id }})" class="flex-1 px-3 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-medium rounded-lg transition-all flex items-center justify-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                            Reenviar
                                        </button>
                                    @endif
                                    
                                    <a href="{{ route('walee.factura.ver', $factura->id) }}" class="px-3 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-medium rounded-lg transition-all flex items-center justify-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Ver
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 animate-fade-in-up">
                        <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-slate-800 flex items-center justify-center">
                            <svg class="w-10 h-10 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-white mb-2">No hay facturas</h3>
                        <p class="text-slate-400 mb-6">Crea tu primera factura para comenzar</p>
                        <a href="{{ route('walee.facturas.crear') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-walee-500 hover:bg-walee-400 text-white font-medium rounded-xl transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Crear Factura
                        </a>
                    </div>
                @endforelse
            </div>
            
            <!-- Total Summary -->
            @if($facturas->count() > 0)
                <div class="mt-6 p-4 bg-gradient-to-r from-walee-500/10 to-walee-600/5 border border-walee-500/20 rounded-2xl animate-fade-in-up" style="animation-delay: 0.5s;">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-400">Total facturado</span>
                        <span class="text-xl font-bold text-walee-400">₡{{ number_format($totalMonto, 0, ',', '.') }}</span>
                    </div>
                </div>
            @endif
            
            <!-- Footer -->
            <footer class="text-center py-8 mt-4">
                <p class="text-sm text-slate-500">
                    <span class="text-walee-400 font-medium">Walee</span> · websolutions.work
                </p>
            </footer>
        </div>
    </div>
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const cards = document.querySelectorAll('.factura-card');
            
            cards.forEach(card => {
                const searchData = card.dataset.search;
                if (searchData.includes(searchTerm)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
        
        // Enviar factura
        async function enviarFactura(id) {
            if (!confirm('¿Enviar esta factura al cliente?')) return;
            
            try {
                const response = await fetch(`/walee-facturas/${id}/enviar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification('Factura enviada', 'La factura ha sido enviada correctamente', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification('Error', data.message || 'Error al enviar factura', 'error');
                }
            } catch (error) {
                showNotification('Error', 'Error de conexión: ' + error.message, 'error');
            }
        }
        
        // Reenviar factura
        async function reenviarFactura(id) {
            if (!confirm('¿Reenviar esta factura al cliente?')) return;
            
            try {
                const response = await fetch(`/walee-facturas/${id}/enviar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification('Factura reenviada', 'La factura ha sido reenviada correctamente', 'success');
                } else {
                    showNotification('Error', data.message || 'Error al reenviar factura', 'error');
                }
            } catch (error) {
                showNotification('Error', 'Error de conexión: ' + error.message, 'error');
            }
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
    </script>
</body>
</html>

