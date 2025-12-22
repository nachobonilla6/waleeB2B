<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Factura #{{ $factura->numero_factura }}</title>
    <meta name="description" content="Ver Factura">
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
        
        @media print {
            body { background: white !important; }
            .no-print { display: none !important; }
            .print-only { display: block !important; }
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">
    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none no-print">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-walee-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-emerald-400/5 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-3xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
            @php $pageTitle = 'Factura #' . $factura->numero_factura; @endphp
            @include('partials.walee-navbar')
            
            <div class="flex items-center justify-end gap-2 mb-6 no-print">
                @if($factura->enviada_at)
                    <span class="px-3 py-1 text-xs font-medium bg-emerald-500/20 text-emerald-400 rounded-full">
                        Enviada
                    </span>
                @else
                    <span class="px-3 py-1 text-xs font-medium bg-amber-500/20 text-amber-400 rounded-full">
                        Pendiente
                    </span>
                @endif
            </div>
            
            <!-- Notifications -->
            <div id="notifications" class="fixed top-4 right-4 z-50 space-y-2 no-print"></div>
            
            <!-- Invoice Card -->
            <div class="bg-slate-800/50 border border-slate-700/50 rounded-2xl overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s;">
                <!-- Header Section -->
                <div class="bg-gradient-to-r from-walee-500/10 to-walee-600/5 border-b border-slate-700/50 p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-walee-400">Web Solutions</h2>
                            <p class="text-sm text-slate-400">websolutionscrnow@gmail.com</p>
                            <p class="text-sm text-slate-400">+506 8806 1829</p>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-white mb-1">
                                #{{ $factura->numero_factura }}
                            </div>
                            <div class="text-sm text-slate-400">
                                Serie {{ $factura->serie ?? 'A' }}
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Client & Dates -->
                <div class="p-6 border-b border-slate-700/50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Cliente</h3>
                            <p class="text-lg font-semibold text-white">{{ $factura->cliente?->nombre_empresa ?? 'Sin cliente' }}</p>
                            <p class="text-sm text-slate-400">{{ $factura->correo }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <h3 class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Fecha Emisión</h3>
                                <p class="text-white font-medium">{{ $factura->fecha_emision?->format('d/m/Y') ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <h3 class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Vencimiento</h3>
                                <p class="text-white font-medium">{{ $factura->fecha_vencimiento?->format('d/m/Y') ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Concept -->
                <div class="p-6 border-b border-slate-700/50">
                    <h3 class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-3">Concepto</h3>
                    <div class="bg-slate-900/50 rounded-xl p-4">
                        <p class="text-white whitespace-pre-line">{{ $factura->concepto }}</p>
                    </div>
                </div>
                
                <!-- Amounts -->
                <div class="p-6 border-b border-slate-700/50">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400">Subtotal</span>
                            <span class="text-white font-medium">₡{{ number_format($factura->subtotal ?? $factura->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t border-slate-700/50">
                            <span class="text-lg font-semibold text-white">Total</span>
                            <span class="text-2xl font-bold text-walee-400">₡{{ number_format($factura->total, 0, ',', '.') }}</span>
                        </div>
                        @if($factura->monto_pagado > 0)
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400">Monto Pagado</span>
                                <span class="text-emerald-400 font-medium">₡{{ number_format($factura->monto_pagado, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400">Saldo Pendiente</span>
                                <span class="text-amber-400 font-medium">₡{{ number_format($factura->total - $factura->monto_pagado, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Payment & Status -->
                <div class="p-6 border-b border-slate-700/50">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Método de Pago</h3>
                            <p class="text-white font-medium">{{ ucfirst($factura->metodo_pago ?? 'No especificado') }}</p>
                        </div>
                        <div>
                            <h3 class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Estado</h3>
                            @php
                                $estadoColors = [
                                    'pendiente' => 'bg-amber-500/20 text-amber-400',
                                    'pagada' => 'bg-emerald-500/20 text-emerald-400',
                                    'vencida' => 'bg-red-500/20 text-red-400',
                                    'cancelada' => 'bg-slate-500/20 text-slate-400',
                                ];
                                $color = $estadoColors[$factura->estado] ?? 'bg-slate-500/20 text-slate-400';
                            @endphp
                            <span class="inline-flex px-3 py-1 text-sm font-medium {{ $color }} rounded-full">
                                {{ ucfirst($factura->estado ?? 'Pendiente') }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Notes -->
                @if($factura->notas)
                    <div class="p-6 border-b border-slate-700/50">
                        <h3 class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-3">Notas</h3>
                        <p class="text-slate-300">{{ $factura->notas }}</p>
                    </div>
                @endif
                
                <!-- Sent Info -->
                @if($factura->enviada_at)
                    <div class="p-6 bg-emerald-500/5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-emerald-500/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-emerald-400">Factura enviada</p>
                                <p class="text-xs text-slate-400">{{ $factura->enviada_at->format('d/m/Y H:i') }} · {{ $factura->enviada_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Actions -->
            <div class="flex gap-3 mt-6 animate-fade-in-up no-print" style="animation-delay: 0.2s;">
                @if(!$factura->enviada_at)
                    <button onclick="enviarFactura()" class="flex-1 px-4 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-medium rounded-xl transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Enviar Factura
                    </button>
                @else
                    <button onclick="enviarFactura()" class="flex-1 px-4 py-3 bg-slate-700 hover:bg-slate-600 text-white font-medium rounded-xl transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reenviar
                    </button>
                @endif
                
                <button onclick="window.print()" class="px-4 py-3 bg-slate-700 hover:bg-slate-600 text-white font-medium rounded-xl transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Imprimir
                </button>
            </div>
            
            <!-- Footer -->
            <footer class="text-center py-8 mt-4 no-print">
                <p class="text-sm text-slate-500">
                    <span class="text-walee-400 font-medium">Walee</span> · websolutions.work
                </p>
            </footer>
        </div>
    </div>
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        async function enviarFactura() {
            if (!confirm('¿Enviar esta factura por email?')) return;
            
            try {
                const response = await fetch('/walee-facturas/{{ $factura->id }}/enviar', {
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
    @include('partials.walee-support-button')
</body>
</html>

