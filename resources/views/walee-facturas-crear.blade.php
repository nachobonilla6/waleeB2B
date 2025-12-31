<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Crear Factura</title>
    <meta name="description" content="Crear nueva factura">
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
        $clientes = \App\Models\Cliente::orderBy('nombre_empresa')->get();
        $ultimaFactura = \App\Models\Factura::orderBy('id', 'desc')->first();
        $siguienteNumero = $ultimaFactura ? intval($ultimaFactura->numero_factura) + 1 : 1;
    @endphp

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-violet-400/20 dark:bg-violet-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-walee-400/10 dark:bg-walee-400/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-violet-400/20 dark:bg-violet-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-4 py-6 sm:px-6 lg:px-8">
            @php $pageTitle = 'Crear Factura'; @endphp
            @include('partials.walee-navbar')
            
            <!-- Notifications -->
            <div id="notifications" class="fixed top-4 right-4 z-50 space-y-2"></div>
            
            <!-- Form -->
            <form id="facturaForm" class="space-y-6 animate-fade-in-up" style="animation-delay: 0.1s;" enctype="multipart/form-data">
                @csrf
                
                <!-- Cliente Section -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 shadow-sm dark:shadow-none">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Cliente
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Cliente</label>
                            <select id="cliente_id" name="cliente_id" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all">
                                <option value="">Seleccionar cliente...</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" data-email="{{ $cliente->correo }}">{{ $cliente->nombre_empresa }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Correo <span class="text-red-500 dark:text-red-400">*</span></label>
                            <input type="email" id="correo" name="correo" required placeholder="correo@ejemplo.com" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-500 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all">
                        </div>
                    </div>
                </div>
                
                <!-- Factura Info -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 shadow-sm dark:shadow-none">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Información de Factura
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Número de Factura <span class="text-red-500 dark:text-red-400">*</span></label>
                            <input type="text" id="numero_factura" name="numero_factura" required value="{{ str_pad($siguienteNumero, 4, '0', STR_PAD_LEFT) }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Serie</label>
                            <input type="text" id="serie" name="serie" value="A" placeholder="A, B, C..." class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Fecha de Emisión <span class="text-red-500 dark:text-red-400">*</span></label>
                            <input type="date" id="fecha_emision" name="fecha_emision" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Fecha de Vencimiento</label>
                            <input type="date" id="fecha_vencimiento" name="fecha_vencimiento" value="{{ date('Y-m-d', strtotime('+30 days')) }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Estado</label>
                            <select id="estado" name="estado" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all">
                                <option value="pendiente">Pendiente</option>
                                <option value="pagada">Pagada</option>
                                <option value="vencida">Vencida</option>
                                <option value="cancelada">Cancelada</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Información del Cliente (Cálculo Automático) -->
                <div id="clienteInfoCard" class="bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 rounded-2xl p-6 shadow-sm dark:shadow-none hidden">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Resumen del Cliente
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-xs text-slate-600 dark:text-slate-400 mb-1">Total Facturado</p>
                            <p class="text-lg font-bold text-slate-900 dark:text-white" id="infoTotalFacturado">₡0</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600 dark:text-slate-400 mb-1">Total Pagado</p>
                            <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400" id="infoTotalPagado">₡0</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600 dark:text-slate-400 mb-1">Saldo Pendiente</p>
                            <p class="text-lg font-bold text-red-600 dark:text-red-400" id="infoSaldoPendiente">₡0</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600 dark:text-slate-400 mb-1">Facturas</p>
                            <p class="text-lg font-bold text-slate-900 dark:text-white" id="infoFacturasCount">0</p>
                        </div>
                    </div>
                </div>
                
                <!-- Paquetes Predefinidos -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 shadow-sm dark:shadow-none">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Paquetes Predefinidos
                    </h2>
                    <div class="mb-4">
                        <select id="paqueteSelect" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 focus:outline-none transition-all">
                            <option value="">Seleccionar paquete...</option>
                        </select>
                    </div>
                    <button type="button" onclick="agregarPaquete()" class="px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white rounded-lg transition-all text-sm">
                        Agregar Paquete
                    </button>
                </div>
                
                <!-- Items de Factura -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 shadow-sm dark:shadow-none">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Items de Factura <span class="text-red-500 dark:text-red-400 text-sm font-normal">*</span>
                    </h2>
                    
                    <div id="itemsContainer" class="space-y-3 mb-4">
                        <!-- Items se agregarán dinámicamente aquí -->
                    </div>
                    
                    <button type="button" onclick="agregarItem()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg transition-all text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Agregar Item
                    </button>
                </div>
                
                <!-- Resumen y Totales -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 shadow-sm dark:shadow-none">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Resumen y Totales
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Número de Orden</label>
                                <input type="text" id="numero_orden" name="numero_orden" placeholder="Ej: 1_191125 cliente" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Subtotal</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400">₡</span>
                                    <input type="number" step="0.01" id="subtotal" name="subtotal" value="0" readonly class="w-full pl-8 pr-4 py-3 bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Descuento Antes Impuestos</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400">₡</span>
                                    <input type="number" step="0.01" id="descuento_antes_impuestos" name="descuento_antes_impuestos" value="0" oninput="calcularTotales()" class="w-full pl-8 pr-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none transition-all">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">IVA (13%)</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400">₡</span>
                                    <input type="number" step="0.01" id="iva" name="iva" value="0" readonly class="w-full pl-8 pr-4 py-3 bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white">
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Descuento Después Impuestos</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400">₡</span>
                                    <input type="number" step="0.01" id="descuento_despues_impuestos" name="descuento_despues_impuestos" value="0" oninput="calcularTotales()" class="w-full pl-8 pr-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none transition-all">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Total <span class="text-red-500 dark:text-red-400">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400">₡</span>
                                    <input type="number" step="0.01" id="total" name="total" required value="0" readonly class="w-full pl-8 pr-4 py-3 bg-emerald-50 dark:bg-emerald-500/10 border-2 border-emerald-500 dark:border-emerald-500 rounded-xl text-emerald-700 dark:text-emerald-400 font-bold">
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Monto Pagado</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400">₡</span>
                                    <input type="number" step="0.01" id="monto_pagado" name="monto_pagado" value="0" oninput="calcularSaldo()" class="w-full pl-8 pr-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Saldo Pendiente</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400">₡</span>
                                    <input type="number" step="0.01" id="saldo_pendiente" name="saldo_pendiente" value="0" readonly class="w-full pl-8 pr-4 py-3 bg-red-50 dark:bg-red-500/10 border border-red-300 dark:border-red-500/30 rounded-xl text-red-700 dark:text-red-400 font-semibold">
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Método de Pago</label>
                                <select id="metodo_pago" name="metodo_pago" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all">
                                    <option value="">Sin especificar</option>
                                    <option value="transferencia">Transferencia Bancaria</option>
                                    <option value="efectivo">Efectivo</option>
                                    <option value="tarjeta">Tarjeta de Crédito/Débito</option>
                                    <option value="sinpe">SINPE Móvil</option>
                                    <option value="paypal">PayPal</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Concepto de Pago</label>
                                <input type="text" id="concepto_pago" name="concepto_pago" placeholder="Ej: Pago inicial, Pago final..." class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Concepto General <span class="text-red-500 dark:text-red-400">*</span></label>
                            <textarea id="concepto" name="concepto" rows="2" placeholder="Descripción general de la factura (se generará automáticamente si se deja vacío)..." class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all resize-none"></textarea>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Si se deja vacío, se generará automáticamente basado en los items</p>
                        </div>
                    </div>
                </div>
                
                <!-- Pagos Recibidos -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 shadow-sm dark:shadow-none">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Pagos Recibidos
                    </h2>
                    
                    <div id="pagosContainer" class="space-y-3 mb-4">
                        <!-- Pagos se agregarán dinámicamente aquí -->
                    </div>
                    
                    <button type="button" onclick="agregarPago()" class="px-4 py-2 bg-green-600 hover:bg-green-500 text-white rounded-lg transition-all text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Agregar Pago
                    </button>
                </div>
                
                <!-- Notas -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 shadow-sm dark:shadow-none">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Notas Adicionales
                    </h2>
                    
                    <textarea id="notas" name="notas" rows="3" placeholder="Notas adicionales para la factura..." class="w-full px-4 py-3 bg-slate-900/50 border border-slate-600 rounded-xl text-white placeholder-slate-500 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 focus:outline-none transition-all resize-none"></textarea>
                </div>
                
                <!-- Archivos Adjuntos Section -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 shadow-sm dark:shadow-none">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                        Archivos Adjuntos (Opcional)
                    </h2>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Seleccionar Archivos</label>
                        <input type="file" id="archivos" name="archivos[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip,.rar" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all @error('archivos') border-red-500 @enderror">
                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Formatos permitidos: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG, ZIP, RAR</p>
                        @error('archivos')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        @error('archivos.*')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        
                        <!-- Lista de archivos seleccionados -->
                        <div id="archivos-lista" class="mt-3 space-y-2 hidden">
                            <p class="text-xs font-medium text-slate-600 dark:text-slate-400">Archivos seleccionados:</p>
                            <div id="archivos-nombres" class="space-y-1"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Submit Buttons -->
                <div class="space-y-3">
                    <p class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1">
                        <span class="text-red-500 dark:text-red-400">*</span>
                        <span>Campos obligatorios</span>
                    </p>
                    <div class="flex gap-4">
                        <button type="button" onclick="mostrarVistaPrevia()" class="px-6 py-4 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <span>Vista Previa</span>
                        </button>
                        <button type="submit" id="submitBtn" class="flex-1 px-6 py-4 bg-violet-600 hover:bg-violet-500 text-white font-semibold rounded-xl transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Crear Factura</span>
                        </button>
                    </div>
                </div>
            </form>
            
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
        let items = [];
        let paquetes = [];
        let pagos = [];
        let itemCounter = 0;
        let pagoCounter = 0;
        
        // Cargar paquetes al iniciar
        async function cargarPaquetes() {
            try {
                const response = await fetch('{{ route("walee.facturas.paquetes") }}');
                const data = await response.json();
                if (data.success) {
                    paquetes = data.paquetes;
                    const select = document.getElementById('paqueteSelect');
                    select.innerHTML = '<option value="">Seleccionar paquete...</option>';
                    paquetes.forEach(paquete => {
                        const option = document.createElement('option');
                        option.value = paquete.id;
                        option.textContent = `${paquete.nombre} - ₡${parseFloat(paquete.precio).toLocaleString()}`;
                        option.dataset.paquete = JSON.stringify(paquete);
                        select.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error cargando paquetes:', error);
            }
        }
        
        // Cargar información del cliente
        async function cargarInfoCliente(clienteId) {
            if (!clienteId) {
                document.getElementById('clienteInfoCard').classList.add('hidden');
                return;
            }
            
            try {
                const response = await fetch(`/walee-facturas/cliente/${clienteId}/info`);
                const data = await response.json();
                if (data.success) {
                    document.getElementById('infoTotalFacturado').textContent = `₡${parseFloat(data.resumen.total_facturado).toLocaleString()}`;
                    document.getElementById('infoTotalPagado').textContent = `₡${parseFloat(data.resumen.total_pagado).toLocaleString()}`;
                    document.getElementById('infoSaldoPendiente').textContent = `₡${parseFloat(data.resumen.saldo_pendiente).toLocaleString()}`;
                    document.getElementById('infoFacturasCount').textContent = data.resumen.facturas_count;
                    document.getElementById('clienteInfoCard').classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error cargando info del cliente:', error);
            }
        }
        
        // Auto-fill correo when cliente changes
        document.getElementById('cliente_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const email = selectedOption.dataset.email;
            if (email) {
                document.getElementById('correo').value = email;
            }
            cargarInfoCliente(this.value);
        });
        
        // Agregar paquete
        function agregarPaquete() {
            const select = document.getElementById('paqueteSelect');
            const selectedOption = select.options[select.selectedIndex];
            if (!selectedOption.value) return;
            
            const paquete = JSON.parse(selectedOption.dataset.paquete);
            agregarItem(paquete.nombre, paquete.precio, 1, paquete.descripcion);
            select.value = '';
        }
        
        // Agregar item
        function agregarItem(descripcion = '', precio = 0, cantidad = 1, notas = '') {
            const itemId = itemCounter++;
            const item = {
                id: itemId,
                descripcion: descripcion,
                cantidad: cantidad,
                precio_unitario: precio,
                subtotal: cantidad * precio
            };
            items.push(item);
            renderizarItems();
            calcularTotales();
        }
        
        // Eliminar item
        function eliminarItem(itemId) {
            items = items.filter(item => item.id !== itemId);
            renderizarItems();
            calcularTotales();
        }
        
        // Renderizar items
        function renderizarItems() {
            const container = document.getElementById('itemsContainer');
            container.innerHTML = '';
            
            items.forEach((item, index) => {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-xl p-4';
                itemDiv.innerHTML = `
                    <div class="grid grid-cols-12 gap-3 items-end">
                        <div class="col-span-12 md:col-span-5">
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Descripción</label>
                            <input type="text" value="${item.descripcion}" oninput="actualizarItem(${item.id}, 'descripcion', this.value)" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-white text-sm">
                        </div>
                        <div class="col-span-4 md:col-span-2">
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Cantidad</label>
                            <input type="number" value="${item.cantidad}" min="1" oninput="actualizarItem(${item.id}, 'cantidad', parseFloat(this.value) || 1)" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-white text-sm">
                        </div>
                        <div class="col-span-4 md:col-span-2">
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Precio Unit.</label>
                            <input type="number" step="0.01" value="${item.precio_unitario}" oninput="actualizarItem(${item.id}, 'precio_unitario', parseFloat(this.value) || 0)" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-white text-sm">
                        </div>
                        <div class="col-span-4 md:col-span-2">
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Subtotal</label>
                            <input type="number" step="0.01" value="${item.subtotal.toFixed(2)}" readonly class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-white text-sm font-semibold">
                        </div>
                        <div class="col-span-12 md:col-span-1">
                            <button type="button" onclick="eliminarItem(${item.id})" class="w-full px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-all text-sm">
                                <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                `;
                container.appendChild(itemDiv);
            });
        }
        
        // Actualizar item
        function actualizarItem(itemId, campo, valor) {
            const item = items.find(i => i.id === itemId);
            if (item) {
                item[campo] = valor;
                if (campo === 'cantidad' || campo === 'precio_unitario') {
                    item.subtotal = (item.cantidad || 1) * (item.precio_unitario || 0);
                }
                renderizarItems();
                calcularTotales();
            }
        }
        
        // Agregar pago
        function agregarPago() {
            const pagoId = pagoCounter++;
            const pago = {
                id: pagoId,
                descripcion: '',
                fecha: new Date().toISOString().split('T')[0],
                importe: 0,
                metodo_pago: ''
            };
            pagos.push(pago);
            renderizarPagos();
        }
        
        // Eliminar pago
        function eliminarPago(pagoId) {
            pagos = pagos.filter(p => p.id !== pagoId);
            renderizarPagos();
            calcularSaldo();
        }
        
        // Renderizar pagos
        function renderizarPagos() {
            const container = document.getElementById('pagosContainer');
            container.innerHTML = '';
            
            pagos.forEach((pago) => {
                const pagoDiv = document.createElement('div');
                pagoDiv.className = 'bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-xl p-4';
                pagoDiv.innerHTML = `
                    <div class="grid grid-cols-12 gap-3 items-end">
                        <div class="col-span-12 md:col-span-4">
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Descripción</label>
                            <input type="text" value="${pago.descripcion}" oninput="actualizarPago(${pago.id}, 'descripcion', this.value)" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-white text-sm">
                        </div>
                        <div class="col-span-6 md:col-span-2">
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Fecha</label>
                            <input type="date" value="${pago.fecha}" oninput="actualizarPago(${pago.id}, 'fecha', this.value)" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-white text-sm">
                        </div>
                        <div class="col-span-6 md:col-span-2">
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Importe</label>
                            <input type="number" step="0.01" value="${pago.importe}" oninput="actualizarPago(${pago.id}, 'importe', parseFloat(this.value) || 0)" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-white text-sm">
                        </div>
                        <div class="col-span-10 md:col-span-3">
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Método</label>
                            <select onchange="actualizarPago(${pago.id}, 'metodo_pago', this.value)" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-white text-sm">
                                <option value="">Seleccionar...</option>
                                <option value="sinpe" ${pago.metodo_pago === 'sinpe' ? 'selected' : ''}>SINPE</option>
                                <option value="transferencia" ${pago.metodo_pago === 'transferencia' ? 'selected' : ''}>Transferencia</option>
                                <option value="efectivo" ${pago.metodo_pago === 'efectivo' ? 'selected' : ''}>Efectivo</option>
                                <option value="tarjeta" ${pago.metodo_pago === 'tarjeta' ? 'selected' : ''}>Tarjeta</option>
                            </select>
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <button type="button" onclick="eliminarPago(${pago.id})" class="w-full px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-all text-sm">
                                <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                `;
                container.appendChild(pagoDiv);
            });
        }
        
        // Actualizar pago
        function actualizarPago(pagoId, campo, valor) {
            const pago = pagos.find(p => p.id === pagoId);
            if (pago) {
                pago[campo] = valor;
                if (campo === 'importe') {
                    calcularSaldo();
                }
            }
        }
        
        // Calcular totales
        function calcularTotales() {
            const subtotal = items.reduce((sum, item) => sum + (item.subtotal || 0), 0);
            const descuentoAntes = parseFloat(document.getElementById('descuento_antes_impuestos').value) || 0;
            const subtotalConDescuento = subtotal - descuentoAntes;
            const iva = subtotalConDescuento * 0.13;
            const descuentoDespues = parseFloat(document.getElementById('descuento_despues_impuestos').value) || 0;
            const total = subtotalConDescuento + iva - descuentoDespues;
            
            document.getElementById('subtotal').value = subtotal.toFixed(2);
            document.getElementById('iva').value = iva.toFixed(2);
            document.getElementById('total').value = total.toFixed(2);
            
            calcularSaldo();
        }
        
        // Calcular saldo pendiente
        function calcularSaldo() {
            const total = parseFloat(document.getElementById('total').value) || 0;
            const montoPagadoInput = parseFloat(document.getElementById('monto_pagado').value) || 0;
            const totalPagos = pagos.reduce((sum, pago) => sum + (parseFloat(pago.importe) || 0), 0);
            const montoPagado = montoPagadoInput + totalPagos;
            const saldoPendiente = total - montoPagado;
            document.getElementById('saldo_pendiente').value = saldoPendiente.toFixed(2);
        }
        
        // Vista previa
        async function mostrarVistaPrevia() {
            if (items.length === 0) {
                showNotification('Error', 'Debe agregar al menos un item', 'error');
                return;
            }
            
            const formData = new FormData(document.getElementById('facturaForm'));
            const data = Object.fromEntries(formData.entries());
            data.items_json = JSON.stringify(items);
            data.pagos = JSON.stringify(pagos.filter(p => p.descripcion && p.importe > 0));
            
            // Abrir modal de vista previa
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black/80 dark:bg-black/90 backdrop-blur-sm z-50 flex items-center justify-center p-4';
            modal.innerHTML = `
                <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-auto">
                    <div class="p-6 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Vista Previa de Factura</h3>
                        <button onclick="this.closest('.fixed').remove()" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="p-6">
                        <iframe id="previewFrame" name="previewFrame" style="width: 100%; height: 600px; border: none;"></iframe>
                    </div>
                    <div class="p-6 border-t border-slate-200 dark:border-slate-700 flex gap-3 justify-end">
                        <button onclick="this.closest('.fixed').remove()" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-900 dark:text-white rounded-lg">Cerrar</button>
                        <button onclick="generarPDF()" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg">Descargar PDF</button>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
            
            // Enviar datos a la ruta de previsualización
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("walee.facturas.preview") }}';
            form.target = 'previewFrame';
            form.innerHTML = `<input type="hidden" name="_token" value="${csrfToken}">`;
            Object.keys(data).forEach(key => {
                form.innerHTML += `<input type="hidden" name="${key}" value="${data[key]}">`;
            });
            document.body.appendChild(form);
            form.submit();
            setTimeout(() => form.remove(), 1000);
        }
        
        // Generar PDF
        function generarPDF() {
            const formData = new FormData(document.getElementById('facturaForm'));
            const data = Object.fromEntries(formData.entries());
            data.items_json = JSON.stringify(items);
            data.pagos = JSON.stringify(pagos.filter(p => p.descripcion && p.importe > 0));
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("walee.facturas.generar-pdf") }}';
            form.innerHTML = `<input type="hidden" name="_token" value="${csrfToken}">`;
            Object.keys(data).forEach(key => {
                form.innerHTML += `<input type="hidden" name="${key}" value="${data[key]}">`;
            });
            document.body.appendChild(form);
            form.submit();
            setTimeout(() => form.remove(), 1000);
        }
        
        // Form submission
        document.getElementById('facturaForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (items.length === 0) {
                showNotification('Error', 'Debe agregar al menos un item', 'error');
                return;
            }
            
            const submitBtn = document.getElementById('submitBtn');
            const formData = new FormData(this);
            
            // Agregar items al formData
            items.forEach((item, index) => {
                formData.append(`items[${index}][descripcion]`, item.descripcion);
                formData.append(`items[${index}][cantidad]`, item.cantidad);
                formData.append(`items[${index}][precio_unitario]`, item.precio_unitario);
                formData.append(`items[${index}][subtotal]`, item.subtotal);
                formData.append(`items[${index}][orden]`, index);
            });
            
            // Agregar pagos al formData
            pagos.forEach((pago, index) => {
                if (pago.descripcion && pago.importe > 0) {
                    formData.append(`pagos[${index}][descripcion]`, pago.descripcion);
                    formData.append(`pagos[${index}][fecha]`, pago.fecha);
                    formData.append(`pagos[${index}][importe]`, pago.importe);
                    formData.append(`pagos[${index}][metodo_pago]`, pago.metodo_pago || '');
                }
            });
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Creando...</span>
            `;
            
            try {
                const response = await fetch('{{ route("walee.facturas.guardar") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: formData,
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification('Factura creada', 'La factura ha sido creada correctamente', 'success');
                    setTimeout(() => {
                        window.location.href = '{{ route("walee.facturas") }}';
                    }, 1500);
                } else {
                    showNotification('Error', data.message || 'Error al crear factura', 'error');
                }
            } catch (error) {
                showNotification('Error', 'Error de conexión: ' + error.message, 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = `
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Crear Factura</span>
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
            setTimeout(() => notification.classList.remove('translate-x-full'), 10);
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        }
        
        // Mostrar lista de archivos seleccionados
        const archivosInput = document.getElementById('archivos');
        const archivosLista = document.getElementById('archivos-lista');
        const archivosNombres = document.getElementById('archivos-nombres');
        
        if (archivosInput) {
            archivosInput.addEventListener('change', function() {
                const files = Array.from(this.files);
                
                if (files.length > 0) {
                    archivosNombres.innerHTML = '';
                    files.forEach((file, index) => {
                        const fileItem = document.createElement('div');
                        fileItem.className = 'flex items-center gap-2 p-2 rounded-lg bg-slate-100 dark:bg-slate-800/50 text-xs';
                        fileItem.innerHTML = `
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="flex-1 text-slate-700 dark:text-slate-300">${file.name}</span>
                            <span class="text-slate-500 dark:text-slate-400">${(file.size / 1024).toFixed(2)} KB</span>
                        `;
                        archivosNombres.appendChild(fileItem);
                    });
                    archivosLista.classList.remove('hidden');
                } else {
                    archivosLista.classList.add('hidden');
                }
            });
        }
        
        // Inicializar
        cargarPaquetes();
        agregarItem(); // Agregar un item inicial
        agregarPago(); // Agregar un pago inicial
    </script>
    @include('partials.walee-support-button')
</body>
</html>

