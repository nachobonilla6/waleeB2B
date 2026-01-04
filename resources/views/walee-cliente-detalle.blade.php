<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - {{ $cliente->name }}</title>
    <meta name="description" content="Detalle del cliente">
    <meta name="theme-color" content="#D59F3B">
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
                            400: '#D59F3B',
                            500: '#C78F2E',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        * { font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif; }
        @keyframes fadeInUp {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .animate-fade-in-up { animation: fadeInUp 0.3s ease-out forwards; }
        
        /* Asegurar que el modal esté encima de todo */
        .swal2-container {
            z-index: 99999 !important;
        }
        .swal2-backdrop-show {
            z-index: 99999 !important;
        }
        .swal2-popup {
            z-index: 99999 !important;
        }
        .swal2-container.swal2-backdrop-show {
            z-index: 99999 !important;
        }
        
        /* Estilos para mobile - ancho completo con poco espacio */
        @media (max-width: 640px) {
            .swal2-popup-mobile {
                margin: 0.5rem !important;
                padding: 0 !important;
                width: calc(100% - 1rem) !important;
                max-width: calc(100% - 1rem) !important;
                max-height: 90vh !important;
                display: flex !important;
                flex-direction: column !important;
                overflow: hidden !important;
            }
            .swal2-html-container-mobile {
                padding: 0.75rem !important;
                margin: 0 !important;
                flex: 1 !important;
                overflow: visible !important;
                max-height: none !important;
            }
            .swal2-html-container-mobile form {
                width: 100% !important;
            }
            .swal2-html-container-mobile input,
            .swal2-html-container-mobile textarea,
            .swal2-html-container-mobile select {
                width: 100% !important;
                box-sizing: border-box !important;
            }
            .swal2-html-container-mobile .grid {
                gap: 0.75rem !important;
            }
            .swal2-html-container-mobile > * {
                width: 100% !important;
            }
            /* Mejorar botones en mobile - siempre visibles */
            .swal2-actions {
                margin: 0 !important;
                padding: 0.75rem !important;
                gap: 0.75rem !important;
                border-top: 1px solid rgba(203, 213, 225, 0.3) !important;
                flex-shrink: 0 !important;
                background: inherit !important;
            }
            html.dark .swal2-actions {
                border-top-color: rgba(51, 65, 85, 0.5) !important;
            }
            .swal2-confirm,
            .swal2-cancel {
                flex: 1 !important;
                padding: 0.75rem 1rem !important;
                font-size: 0.875rem !important;
                font-weight: 600 !important;
                border-radius: 0.5rem !important;
                transition: all 0.2s !important;
                margin: 0 !important;
            }
            .swal2-confirm {
                background: linear-gradient(135deg, #D59F3B 0%, #C78F2E 100%) !important;
                border: none !important;
                box-shadow: 0 2px 8px rgba(213, 159, 59, 0.3) !important;
            }
            .swal2-confirm:hover {
                transform: translateY(-1px) !important;
                box-shadow: 0 4px 12px rgba(213, 159, 59, 0.4) !important;
            }
            .swal2-cancel {
                background: #f1f5f9 !important;
                color: #475569 !important;
                border: 1px solid #e2e8f0 !important;
            }
            .swal2-cancel:hover {
                background: #e2e8f0 !important;
            }
            html.dark .swal2-cancel {
                background: #1e293b !important;
                color: #cbd5e1 !important;
                border-color: #334155 !important;
            }
            html.dark .swal2-cancel:hover {
                background: #334155 !important;
            }
        }
        
        /* Estilos para desktop - modal más ancho y compacto */
        @media (min-width: 1024px) {
            .swal2-popup {
                max-height: 90vh !important;
                overflow-y: auto !important;
            }
            .swal2-html-container {
                max-height: calc(90vh - 120px) !important;
                overflow-y: auto !important;
            }
        }
        
        /* Asegurar que el modal respete el tema dark/light */
        html.dark .swal2-popup {
            background-color: #0f172a !important;
            color: #e2e8f0 !important;
            border: 1px solid rgba(213, 159, 59, 0.2) !important;
        }
        
        html.dark .swal2-title {
            color: #e2e8f0 !important;
        }
        
        html.dark .swal2-html-container {
            color: #e2e8f0 !important;
        }
        
        html.dark .swal2-html-container label {
            color: #cbd5e1 !important;
        }
        
        html.dark .swal2-html-container input,
        html.dark .swal2-html-container textarea,
        html.dark .swal2-html-container select {
            background-color: #1e293b !important;
            border-color: #475569 !important;
            color: #e2e8f0 !important;
        }
        
        html.dark .swal2-html-container input:focus,
        html.dark .swal2-html-container textarea:focus,
        html.dark .swal2-html-container select:focus {
            border-color: #D59F3B !important;
            outline-color: #D59F3B !important;
            ring-color: #D59F3B !important;
            background-color: #334155 !important;
        }
        
        /* Light mode - fondo blanco */
        html:not(.dark) .swal2-popup {
            background-color: #ffffff !important;
            color: #1e293b !important;
            border: 1px solid rgba(203, 213, 225, 0.5) !important;
        }
        
        html:not(.dark) .swal2-title {
            color: #1e293b !important;
        }
        
        html:not(.dark) .swal2-html-container {
            color: #1e293b !important;
        }
        
        html:not(.dark) .swal2-html-container label {
            color: #334155 !important;
        }
        
        html:not(.dark) .swal2-html-container input,
        html:not(.dark) .swal2-html-container textarea,
        html:not(.dark) .swal2-html-container select {
            background-color: #ffffff !important;
            border-color: #cbd5e1 !important;
            color: #1e293b !important;
        }
        
        html:not(.dark) .swal2-html-container input:focus,
        html:not(.dark) .swal2-html-container textarea:focus,
        html:not(.dark) .swal2-html-container select:focus {
            border-color: #D59F3B !important;
            outline-color: #D59F3B !important;
            background-color: #f8fafc !important;
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white min-h-screen transition-colors duration-200">
    @php
        use App\Models\PropuestaPersonalizada;
        
        // Obtener teléfono para WhatsApp (intentar múltiples campos)
        $phone = $cliente->telefono_1 ?: $cliente->telefono_2 ?: $cliente->phone;
        $cleanPhone = $phone ? preg_replace('/[^0-9]/', '', $phone) : null;
        // Si el teléfono no empieza con código de país, agregar código por defecto (ej: 52 para México, 1 para USA)
        if ($cleanPhone && strlen($cleanPhone) == 10 && !str_starts_with($cleanPhone, '1') && !str_starts_with($cleanPhone, '52')) {
            // Asumir código de país por defecto si es necesario
            // $cleanPhone = '52' . $cleanPhone; // Descomentar y ajustar según necesidad
        }
        $whatsappLink = $cleanPhone ? "https://wa.me/{$cleanPhone}" : null;
        
        // Obtener contador de emails enviados
        $emailsEnviados = PropuestaPersonalizada::where('cliente_id', $cliente->id)->count();
        $emailsColor = $emailsEnviados >= 3 ? 'text-red-400' : ($emailsEnviados >= 1 ? 'text-amber-400' : 'text-slate-500');
        $emailsBg = $emailsEnviados >= 3 ? 'bg-red-500/20' : ($emailsEnviados >= 1 ? 'bg-amber-500/20' : 'bg-slate-800/50');
        $emailsBorder = $emailsEnviados >= 3 ? 'border-red-500/30' : ($emailsEnviados >= 1 ? 'border-amber-500/30' : 'border-slate-700');
    @endphp

    <div class="min-h-screen relative">
        <!-- Background -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-400/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 -left-20 w-60 h-60 bg-walee-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative max-w-[90rem] mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
            @php $pageTitle = $cliente->name; @endphp
            @include('partials.walee-navbar')
            
            <!-- Header Profesional -->
            <div class="mb-6 sm:mb-8 lg:mb-10">
                <div class="relative bg-white dark:bg-slate-900/60 rounded-2xl lg:rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-none">
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
                    
                    <!-- Botón de editar en esquina superior derecha -->
                    <button onclick="openEditClientModal()" class="absolute top-3 right-3 sm:top-4 sm:right-4 z-20 inline-flex items-center justify-center gap-1.5 px-2.5 py-1.5 sm:px-3 sm:py-2 rounded-lg bg-black/50 hover:bg-black/70 backdrop-blur-sm text-white border border-white/20 transition-all shadow-lg" style="position: absolute; top: 0.75rem; right: 0.75rem;">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    
                    <!-- Mobile: Layout reorganizado -->
                    <div class="block sm:hidden">
                        <div class="flex items-center gap-2 p-3">
                            <!-- Imagen a la izquierda (mitad de ancho y alto) -->
                            <div class="relative w-1/2 aspect-square flex-shrink-0">
                                @if($fotoUrl)
                                    <img src="{{ $fotoUrl }}" alt="{{ $cliente->name }}" class="w-full h-full object-cover rounded-xl">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-emerald-500/20 to-walee-500/20 flex items-center justify-center rounded-xl">
                                        <span class="text-4xl font-bold text-emerald-400">{{ strtoupper(substr($cliente->name, 0, 1)) }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Nombre a la derecha -->
                            <div class="flex-1 min-w-0">
                                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white break-words">{{ $cliente->name }}</h1>
                            </div>
                        </div>
                        
                        <!-- Acciones Rápidas Mobile -->
                        <div class="px-3 pb-3">
                            <div class="grid grid-cols-4 gap-1.5">
                                <!-- Website Button -->
                                @if($cliente->website)
                                    <a href="{{ $cliente->website }}" target="_blank" class="flex items-center justify-center p-2 rounded-lg bg-gradient-to-r from-blue-500/20 to-blue-600/20 hover:from-blue-500/30 hover:to-blue-600/30 text-blue-400 border border-blue-500/30 transition-all group shadow-sm">
                                        <svg class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                        </svg>
                                    </a>
                                @else
                                    <div class="flex items-center justify-center p-2 rounded-lg bg-slate-100 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 opacity-50 cursor-not-allowed">
                                        <svg class="w-5 h-5 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Email Button -->
                                <a href="{{ route('walee.emails.crear') }}?cliente_id={{ $cliente->id }}" class="flex items-center justify-center p-2 rounded-lg bg-gradient-to-r from-walee-500/20 to-walee-600/20 hover:from-walee-500/30 hover:to-walee-600/30 text-walee-400 border border-walee-500/30 transition-all group shadow-sm">
                                    <svg class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </a>
                                
                                <!-- Publicaciones Button (Facebook Icon) -->
                                @if($clientePlaneadorId)
                                    <a href="{{ route('walee.planeador.publicidad', $clientePlaneadorId) }}" class="flex items-center justify-center p-2 rounded-lg bg-gradient-to-r from-violet-500/20 to-violet-600/20 hover:from-violet-500/30 hover:to-violet-600/30 text-violet-400 border border-violet-500/30 transition-all group shadow-sm">
                                        <svg class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                    </a>
                                @else
                                    <div class="flex items-center justify-center p-2 rounded-lg bg-slate-100 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 opacity-50 cursor-not-allowed">
                                        <svg class="w-5 h-5 flex-shrink-0 text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- WhatsApp Button -->
                                <a href="{{ $whatsappLink ?: '#' }}" 
                                   @if($whatsappLink) target="_blank" @else onclick="event.preventDefault(); showWhatsAppError(); return false;" @endif
                                   class="flex items-center justify-center p-2 rounded-lg bg-gradient-to-r from-emerald-500/20 to-emerald-600/20 hover:from-emerald-500/30 hover:to-emerald-600/30 text-emerald-400 border border-emerald-500/30 transition-all group shadow-sm {{ !$whatsappLink ? 'opacity-60 cursor-not-allowed' : '' }}">
                                    <svg class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Alertas/Información -->
                        <div class="px-3 pb-3 space-y-1.5">
                            @php
                                $totalPublicaciones = $publicacionesProgramadas + $publicacionesPublicadas;
                                $totalCitas = $citasPendientes->count() + $citasPasadas->count();
                            @endphp
                            
                            <!-- Estado -->
                            <div class="flex items-center justify-between p-2 rounded-lg bg-emerald-500/10 border border-emerald-500/20">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Estado</span>
                                </div>
                                <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">{{ $cliente->estado === 'accepted' ? 'Activo' : ucfirst($cliente->estado) }}</span>
                            </div>
                            
                            <!-- Publicaciones -->
                            <div class="flex items-center justify-between p-2.5 rounded-lg bg-violet-500/10 border border-violet-500/20">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-violet-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Publicaciones en FB</span>
                                </div>
                                <span class="text-sm font-semibold text-violet-600 dark:text-violet-400">{{ $totalPublicaciones }}</span>
                            </div>
                            
                            <!-- Citas -->
                            <div class="flex items-center justify-between p-2.5 rounded-lg bg-walee-500/10 border border-walee-500/20">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-walee-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2zm-7 5h5v5h-5v-5z"/>
                                    </svg>
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Citas</span>
                                </div>
                                <span class="text-sm font-semibold text-walee-600 dark:text-walee-400">{{ $totalCitas }}</span>
                            </div>
                            
                            <!-- Facturas -->
                            <div class="flex items-center justify-between p-2.5 rounded-lg bg-red-500/10 border border-red-500/20">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Facturas</span>
                                </div>
                                <span class="text-sm font-semibold text-red-600 dark:text-red-400">{{ $facturas->count() }}</span>
                            </div>
                            
                            <!-- Cotizaciones -->
                            <div class="flex items-center justify-between p-2.5 rounded-lg bg-blue-500/10 border border-blue-500/20">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Cotizaciones</span>
                                </div>
                                <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">{{ $cotizaciones->count() }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Desktop: Layout original -->
                    <div class="hidden sm:block p-4 sm:p-5 lg:p-6">
                        <div class="flex flex-col lg:flex-row lg:items-center gap-4 lg:gap-6">
                            <div class="flex items-start sm:items-center gap-3 sm:gap-4 lg:gap-6 flex-1 min-w-0">
                                @if($fotoUrl)
                                    <img src="{{ $fotoUrl }}" alt="{{ $cliente->name }}" class="w-20 h-20 lg:w-24 lg:h-24 rounded-xl lg:rounded-2xl object-cover border-3 border-emerald-500/30 flex-shrink-0 shadow-md">
                                @else
                                    <div class="w-20 h-20 lg:w-24 lg:h-24 rounded-xl lg:rounded-2xl bg-gradient-to-br from-emerald-500/20 to-walee-500/20 border-3 border-emerald-500/30 flex items-center justify-center flex-shrink-0 shadow-md">
                                        <span class="text-3xl lg:text-4xl font-bold text-emerald-400">{{ strtoupper(substr($cliente->name, 0, 1)) }}</span>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <h1 class="text-2xl lg:text-4xl font-bold text-slate-900 dark:text-white mb-2 sm:mb-3 truncate">{{ $cliente->name }}</h1>
                                    <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-semibold bg-emerald-500/20 text-emerald-400 rounded-full border border-emerald-500/30">
                                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $cliente->estado === 'accepted' ? 'Activo' : ucfirst($cliente->estado) }}
                                        </span>
                                        @if($emailsEnviados > 0)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-semibold {{ $emailsBg }} {{ $emailsColor }} rounded-full border {{ $emailsBorder }}">
                                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                                {{ $emailsEnviados }} {{ $emailsEnviados == 1 ? 'email' : 'emails' }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <footer class="text-center py-3 sm:py-6 md:py-8 mt-3 sm:mt-6">
                <p class="text-[10px] sm:text-sm text-slate-600 dark:text-slate-500">
                    <span class="text-walee-400 font-medium">Walee</span> · websolutions.work
                </p>
            </footer>
        </div>
    </div>
    @include('partials.walee-support-button')
    
    <script>
        function showWhatsAppError() {
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            Swal.fire({
                icon: 'info',
                title: 'Número no disponible',
                text: 'Este cliente no tiene un número de teléfono registrado. Por favor, edita el cliente para agregar un número de teléfono.',
                confirmButtonText: 'Editar Cliente',
                confirmButtonColor: '#10b981',
                cancelButtonText: 'Cerrar',
                showCancelButton: true,
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                    cancelButton: isDarkMode ? 'dark-swal-cancel' : 'light-swal-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    openEditClientModal();
                }
            });
        }
        
        function showCitasTab(tabName) {
            // Hide all citas tab contents
            document.querySelectorAll('.tab-content-citas').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active class from all citas tabs
            document.querySelectorAll('.tab-button-citas').forEach(button => {
                button.classList.remove('active', 'text-walee-400', 'border-walee-400');
                button.classList.add('text-slate-500', 'dark:text-slate-400', 'border-transparent');
            });
            
            // Show selected tab content
            document.getElementById('content-citas-' + tabName).classList.remove('hidden');
            
            // Add active class to selected tab
            const selectedTab = document.getElementById('tab-citas-' + tabName);
            if (selectedTab) {
                selectedTab.classList.add('active', 'text-walee-400', 'border-walee-400');
                selectedTab.classList.remove('text-slate-500', 'dark:text-slate-400', 'border-transparent');
            }
        }
        
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active', 'text-walee-400', 'border-walee-400');
                button.classList.add('text-slate-500', 'dark:text-slate-400', 'border-transparent');
            });
            
            // Show selected tab content
            document.getElementById('content-' + tabName).classList.remove('hidden');
            
            // Add active class to selected tab
            const activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.add('active', 'text-walee-400', 'border-walee-400');
            activeTab.classList.remove('text-slate-500', 'dark:text-slate-400', 'border-transparent');
        }
        
        // Modal para editar cliente
        function openEditClientModal() {
            const isMobile = window.innerWidth < 640;
            const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;
            const isDesktop = window.innerWidth >= 1024;
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            // Datos del cliente
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
            
            const clienteData = {
                fotoUrl: @json($fotoUrl),
                name: @json($cliente->name ?? ''),
                email: @json($cliente->email ?? ''),
                telefono_1: @json($cliente->telefono_1 ?? ''),
                website: @json($cliente->website ?? ''),
                estado: @json($cliente->estado ?? 'pending'),
                address: @json($cliente->address ?? ''),
                feedback: @json($cliente->feedback ?? ''),
                inicial: @json(strtoupper(substr($cliente->name, 0, 1)))
            };
            
            const html = `
                <form id="editClientForm" class="space-y-3 sm:space-y-2.5 text-left">
                    <!-- Foto -->
                    <div class="mb-3 sm:mb-3">
                        <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-2 sm:mb-2">Foto del Cliente</label>
                        <div class="flex items-start gap-3 sm:gap-3">
                            <div class="flex-shrink-0 relative">
                                <div id="fotoPreviewContainer" class="w-20 h-20 sm:w-20 sm:h-20 rounded-xl overflow-hidden border-2 border-emerald-500/30 shadow-sm">
                                    ${clienteData.fotoUrl ? 
                                        `<img src="${clienteData.fotoUrl}" alt="Foto" id="fotoPreview" class="w-full h-full object-cover">` :
                                        `<img src="https://images.icon-icons.com/1188/PNG/512/1490201150-client_82317.png" alt="Foto" id="fotoPreview" class="w-full h-full object-cover opacity-80">`
                                    }
                                </div>
                                ${clienteData.fotoUrl ? `
                                    <button type="button" onclick="deleteClientPhoto()" class="absolute -top-1 -right-1 w-6 h-6 sm:w-6 sm:h-6 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center transition-all shadow-lg z-10" title="Eliminar foto">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                ` : ''}
                            </div>
                            <div class="flex-1 flex flex-col gap-2">
                                <div class="flex gap-2">
                                    <label for="foto_file" class="cursor-pointer flex-1 inline-flex items-center justify-center ${isMobile ? 'gap-1.5' : 'gap-1.5 sm:gap-2'} ${isMobile ? 'px-3 py-2' : 'px-2.5 sm:px-3 py-1.5 sm:py-2'} rounded-lg bg-walee-400/20 hover:bg-walee-400/30 text-walee-400 border border-walee-400/30 transition-all text-xs sm:text-sm font-medium">
                                        <svg class="${isMobile ? 'w-4 h-4' : 'w-3.5 h-3.5 sm:w-4 sm:h-4'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-xs">Cambiar</span>
                                    </label>
                                    ${clienteData.fotoUrl ? `
                                        <button type="button" onclick="deleteClientPhoto()" class="flex-1 inline-flex items-center justify-center ${isMobile ? 'gap-1.5' : 'gap-1.5 sm:gap-2'} ${isMobile ? 'px-3 py-2' : 'px-2.5 sm:px-3 py-1.5 sm:py-2'} rounded-lg bg-red-500/20 hover:bg-red-500/30 text-red-400 border border-red-500/30 transition-all text-xs sm:text-sm font-medium">
                                            <svg class="${isMobile ? 'w-4 h-4' : 'w-3.5 h-3.5 sm:w-4 sm:h-4'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            <span class="text-xs">Eliminar</span>
                                        </button>
                                    ` : ''}
                                </div>
                                <input type="file" name="foto_file" id="foto_file" accept="image/*" class="hidden" onchange="previewClientImage(this)">
                                <input type="hidden" name="delete_foto" id="delete_foto" value="0">
                                <p class="text-[10px] text-slate-500 dark:text-slate-400">JPG, PNG o GIF. Máx 2MB</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 ${isDesktop ? 'lg:grid-cols-3' : 'sm:grid-cols-2'} gap-3 sm:gap-2.5">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1 sm:mb-1.5">Nombre *</label>
                            <input type="text" id="clientName" name="name" required value="${clienteData.name}"
                                   class="w-full px-2.5 sm:px-3 py-1.5 sm:py-2 text-xs sm:text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-400">
                        </div>
                        
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1 sm:mb-1.5">Email</label>
                            <input type="email" id="clientEmail" name="email" value="${clienteData.email}"
                                   class="w-full px-2.5 sm:px-3 py-1.5 sm:py-2 text-xs sm:text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-400">
                        </div>
                        
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1 sm:mb-1.5">Estado</label>
                            <select id="clientEstado" name="estado"
                                    class="w-full px-2.5 sm:px-3 py-1.5 sm:py-2 text-xs sm:text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-400">
                                <option value="pending" ${clienteData.estado === 'pending' ? 'selected' : ''}>Pendiente</option>
                                <option value="contactado" ${clienteData.estado === 'contactado' ? 'selected' : ''}>Contactado</option>
                                <option value="propuesta_enviada" ${clienteData.estado === 'propuesta_enviada' ? 'selected' : ''}>Propuesta Enviada</option>
                                <option value="accepted" ${clienteData.estado === 'accepted' ? 'selected' : ''}>Aceptado</option>
                                <option value="activo" ${clienteData.estado === 'activo' ? 'selected' : ''}>Activo</option>
                                <option value="rechazado" ${clienteData.estado === 'rechazado' ? 'selected' : ''}>Rechazado</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 ${isDesktop ? 'lg:grid-cols-3' : 'sm:grid-cols-2'} gap-3 sm:gap-2.5">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-1.5">Teléfono</label>
                            <input type="tel" id="clientTelefono1" name="telefono_1" value="${clienteData.telefono_1}"
                                   class="w-full px-3 sm:px-3 py-2 sm:py-2 text-sm sm:text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-400">
                        </div>
                        
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-1.5">Sitio Web</label>
                            <input type="url" id="clientWebsite" name="website" value="${clienteData.website}"
                                   class="w-full px-3 sm:px-3 py-2 sm:py-2 text-sm sm:text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-400">
                        </div>
                        
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-1.5">Dirección</label>
                            <input type="text" id="clientAddress" name="address" value="${clienteData.address}"
                                   class="w-full px-3 sm:px-3 py-2 sm:py-2 text-sm sm:text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-400">
                        </div>
                    </div>
                </form>
            `;
            
            let modalWidth = '98%';
            if (isDesktop) {
                modalWidth = '750px';
            } else if (isTablet) {
                modalWidth = '600px';
            } else if (isMobile) {
                modalWidth = '98%';
            }
            
            Swal.fire({
                title: isMobile ? '' : '<svg class="w-6 h-6 text-walee-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>',
                html: html,
                width: modalWidth,
                padding: isMobile ? '0.75rem' : (isDesktop ? '1.25rem' : '1.5rem'),
                titleText: '',
                customClass: {
                    container: isMobile ? 'swal2-container-mobile' : '',
                    popup: isMobile ? 'swal2-popup-mobile' : (isDarkMode ? 'dark-swal' : 'light-swal'),
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isMobile ? 'swal2-html-container-mobile' : (isDarkMode ? 'dark-swal-html' : 'light-swal-html'),
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                    cancelButton: isDarkMode ? 'dark-swal-cancel' : 'light-swal-cancel'
                },
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                didOpen: () => {
                    // Aplicar tema dark/light al modal
                    const isDark = document.documentElement.classList.contains('dark');
                    const popup = document.querySelector('.swal2-popup');
                    const container = document.querySelector('.swal2-html-container');
                    const title = document.querySelector('.swal2-title');
                    
                    if (isDark) {
                        if (popup) {
                            popup.style.backgroundColor = '#0f172a';
                            popup.style.color = '#e2e8f0';
                            popup.style.border = '1px solid rgba(213, 159, 59, 0.2)';
                        }
                        if (title) {
                            title.style.color = '#e2e8f0';
                        }
                        if (container) {
                            container.style.color = '#e2e8f0';
                        }
                        // Aplicar estilos a inputs, textareas y selects
                        const inputs = container?.querySelectorAll('input, textarea, select');
                        inputs?.forEach(el => {
                            el.style.backgroundColor = '#1e293b';
                            el.style.borderColor = '#475569';
                            el.style.color = '#e2e8f0';
                        });
                        // Aplicar estilos a labels
                        const labels = container?.querySelectorAll('label');
                        labels?.forEach(el => {
                            el.style.color = '#cbd5e1';
                        });
                    } else {
                        if (popup) {
                            popup.style.backgroundColor = '#ffffff';
                            popup.style.color = '#1e293b';
                            popup.style.border = '1px solid rgba(203, 213, 225, 0.5)';
                        }
                        if (title) {
                            title.style.color = '#1e293b';
                        }
                        if (container) {
                            container.style.color = '#1e293b';
                        }
                        // Aplicar estilos a inputs, textareas y selects
                        const inputs = container?.querySelectorAll('input, textarea, select');
                        inputs?.forEach(el => {
                            el.style.backgroundColor = '#ffffff';
                            el.style.borderColor = '#cbd5e1';
                            el.style.color = '#1e293b';
                        });
                        // Aplicar estilos a labels
                        const labels = container?.querySelectorAll('label');
                        labels?.forEach(el => {
                            el.style.color = '#334155';
                        });
                    }
                    
                    document.getElementById('clientName')?.focus();
                },
                preConfirm: () => {
                    const form = document.getElementById('editClientForm');
                    const formData = new FormData(form);
                    
                    // Validar nombre requerido
                    if (!formData.get('name') || formData.get('name').trim() === '') {
                        Swal.showValidationMessage('El nombre es requerido');
                        return false;
                    }
                    
                    return formData;
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    updateClient(result.value);
                }
            });
        }
        
        function previewClientImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const container = document.getElementById('fotoPreviewContainer');
                    container.innerHTML = `<img src="${e.target.result}" alt="Preview" id="fotoPreview" class="w-full h-full object-cover">`;
                    
                    // Resetear el flag de eliminación si se sube una nueva foto
                    const deleteFotoInput = document.getElementById('delete_foto');
                    if (deleteFotoInput) {
                        deleteFotoInput.value = '0';
                    }
                    
                    // Ocultar botones de eliminar si existen
                    const deleteButtons = container.parentElement.querySelectorAll('button[onclick="deleteClientPhoto()"]');
                    deleteButtons.forEach(btn => btn.style.display = 'none');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        function deleteClientPhoto() {
            const container = document.getElementById('fotoPreviewContainer');
            const deleteFotoInput = document.getElementById('delete_foto');
            const fotoFileInput = document.getElementById('foto_file');
            
            // Mostrar imagen genérica
            container.innerHTML = `<img src="https://images.icon-icons.com/1188/PNG/512/1490201150-client_82317.png" alt="Foto" id="fotoPreview" class="w-full h-full object-cover opacity-80">`;
            
            // Marcar para eliminar
            if (deleteFotoInput) {
                deleteFotoInput.value = '1';
            }
            
            // Limpiar input de archivo
            if (fotoFileInput) {
                fotoFileInput.value = '';
            }
            
            // Ocultar botones de eliminar
            const deleteButtons = container.parentElement.querySelectorAll('button[onclick="deleteClientPhoto()"]');
            deleteButtons.forEach(btn => btn.style.display = 'none');
        }
        
        async function updateClient(formData) {
            try {
                const response = await fetch('{{ route("walee.cliente.actualizar", $cliente->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Cliente actualizado!',
                        text: 'Los cambios se han guardado correctamente',
                        confirmButtonColor: '#10b981',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.message || 'Error al actualizar el cliente',
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión. Por favor, intenta de nuevo.',
                    confirmButtonColor: '#ef4444'
                });
            }
        }
    </script>
</body>
</html>

