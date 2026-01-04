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
        
        /* Asegurar que el card de perfil use todo el ancho en mobile */
        @media (max-width: 640px) {
            /* Contenedor principal de la página - usar selector de atributo */
            div[class*="max-w-[90rem]"] {
                width: 100% !important;
                max-width: 100% !important;
            }
            
            /* Contenedor principal del header */
            .header-profesional-wrapper {
                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
            
            /* Card de perfil */
            .header-profesional-card {
                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;
                box-sizing: border-box !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
            
            /* Todos los contenedores dentro del card */
            .header-profesional-card > div {
                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;
            }
            
            /* Contenedor flex del layout mobile - usar selector más específico */
            .header-profesional-card > div.block {
                width: 100% !important;
                max-width: 100% !important;
                display: block !important;
            }
            
            .header-profesional-card > div.block > div {
                width: 100% !important;
                max-width: 100% !important;
            }
        }
        
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
                background: #D59F3B !important;
                border: none !important;
                box-shadow: 0 2px 8px rgba(213, 159, 59, 0.3) !important;
                color: #ffffff !important;
            }
            .swal2-confirm:hover {
                background: #C78F2E !important;
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
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white h-screen overflow-hidden transition-colors duration-200">
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

    <div class="h-screen overflow-hidden relative flex flex-col">
        <!-- Background -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-400/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 -left-20 w-60 h-60 bg-walee-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Navbar con ancho independiente -->
        <div class="relative max-w-[90rem] mx-auto px-3 py-4 sm:px-4 sm:py-6 lg:px-8">
            @php $pageTitle = $cliente->name; @endphp
            @include('partials.walee-navbar')
        </div>
            
        <!-- Contenido principal con ancho más amplio -->
        <div class="relative max-w-full mx-auto px-3 py-0 sm:px-4 sm:py-0 lg:px-12 xl:px-16 h-full overflow-hidden flex flex-col">
            <!-- Header Profesional -->
            <div class="mb-3 sm:mb-4 lg:mb-6 flex-1 overflow-hidden flex flex-col w-full header-profesional-wrapper">
                <div class="relative w-full bg-white dark:bg-slate-900/60 rounded-2xl lg:rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-none header-profesional-card">
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
                    
                    <!-- Botón de editar en esquina superior izquierda -->
                    <button onclick="openEditClientModal()" class="absolute top-3 left-3 sm:top-4 sm:left-4 z-20 inline-flex items-center justify-center gap-1.5 px-2.5 py-1.5 sm:px-3 sm:py-2 rounded-lg bg-black/50 hover:bg-black/70 backdrop-blur-sm text-white border border-white/20 transition-all shadow-lg" style="position: absolute; top: 0.75rem; left: 0.75rem;">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    
                    <!-- Mobile: Layout reorganizado -->
                    <div class="block sm:hidden w-full">
                        <div class="flex items-start gap-3 p-3 w-full">
                            <!-- Imagen a la izquierda -->
                            <div class="relative w-1/2 aspect-square flex-shrink-0">
                    @if($fotoUrl)
                                    <img src="{{ $fotoUrl }}" alt="{{ $cliente->name }}" class="w-full h-full object-cover rounded-xl">
                    @else
                                    <img src="https://images.icon-icons.com/1188/PNG/512/1490201150-client_82317.png" alt="{{ $cliente->name }}" class="w-full h-full object-cover rounded-xl opacity-80">
                    @endif
                            </div>
                            
                            <!-- Nombre y estado a la derecha -->
                    <div class="flex-1 min-w-0">
                                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white break-words mb-2">{{ $cliente->name }}</h1>
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Estado:</span>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-full border border-emerald-600 dark:border-emerald-500/30 w-fit mb-1.5">
                                        <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                                {{ $cliente->estado === 'accepted' ? 'Activo' : ucfirst($cliente->estado) }}
                            </span>
                            @if($cliente->ciudad)
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($cliente->ciudad . ', Costa Rica') }}&zoom=6" target="_blank" rel="noopener noreferrer" class="text-xs text-slate-600 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors cursor-pointer">{{ $cliente->ciudad }}</a>
                                </div>
                            @endif
                        </div>
                </div>
            </div>
            
                        <!-- Acciones Rápidas Mobile -->
                        <div class="px-3 pb-3">
                            <div class="grid grid-cols-4 gap-1.5">
                                <!-- Website Button -->
                                @if($cliente->website)
                                    <a href="{{ $cliente->website }}" target="_blank" class="flex items-center justify-center p-2 rounded-lg bg-blue-100 dark:bg-slate-800 hover:bg-blue-200 dark:hover:bg-slate-700 text-blue-600 dark:text-blue-600 border border-blue-600 dark:border-slate-700 transition-all group shadow-sm">
                                        <svg class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform text-blue-600 dark:text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <button onclick="openEmailModal()" class="flex items-center justify-center p-2 rounded-lg bg-amber-100 dark:bg-slate-800 hover:bg-amber-200 dark:hover:bg-slate-700 text-amber-600 dark:text-walee-600 border border-amber-600 dark:border-slate-700 transition-all group shadow-sm">
                                    <svg class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform text-amber-600 dark:text-walee-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                </button>
                                
                                <!-- Facebook Button -->
                                @if($cliente->facebook)
                                    <a href="{{ $cliente->facebook }}" target="_blank" class="flex items-center justify-center p-2 rounded-lg bg-violet-100 dark:bg-slate-800 hover:bg-violet-200 dark:hover:bg-slate-700 text-violet-600 dark:text-violet-600 border border-violet-600 dark:border-slate-700 transition-all group shadow-sm">
                                        <svg class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform text-violet-600 dark:text-violet-700" fill="currentColor" viewBox="0 0 24 24">
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
                                <button onclick="openWhatsAppModal()" 
                                        class="flex items-center justify-center p-2 rounded-lg bg-emerald-100 dark:bg-slate-800 hover:bg-emerald-200 dark:hover:bg-slate-700 text-emerald-600 dark:text-emerald-600 border border-emerald-600 dark:border-slate-700 transition-all group shadow-sm {{ !$whatsappLink ? 'opacity-60 cursor-not-allowed' : '' }}"
                                        {{ !$whatsappLink ? 'disabled' : '' }}>
                                    <svg class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform text-emerald-600 dark:text-emerald-700" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                </button>
                            </div>
                            </div>
                        
                        <!-- Alertas/Información -->
                        <div class="px-3 pb-3 space-y-1.5">
                            @php
                                $totalPublicaciones = $publicacionesProgramadas + $publicacionesPublicadas;
                                $totalCitas = $citasPendientes->count() + $citasPasadas->count();
                            @endphp
                            
                            <!-- Publicaciones -->
                            @php
                                $planeadorId = $clientePlaneadorId ?? $cliente->id;
                                $planeadorUrl = route('walee.planeador.publicidad', $planeadorId);
                            @endphp
                            <a href="{{ $planeadorUrl }}" class="flex items-center justify-between p-2.5 rounded-lg bg-violet-100 dark:bg-violet-500/10 border border-violet-600 dark:border-violet-500/20 hover:bg-violet-200 dark:hover:bg-violet-500/20 transition-colors cursor-pointer">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Publicaciones</span>
                            </div>
                                <span class="text-sm font-semibold text-violet-700 dark:text-violet-400">{{ $publicacionesPublicadas }}/{{ $publicacionesProgramadas }}</span>
                    </a>
                            
                            <!-- Citas -->
                            <a href="{{ route('walee.calendario', ['cliente_id' => $cliente->id]) }}" class="flex items-center justify-between p-2.5 rounded-lg bg-amber-100 dark:bg-walee-500/10 border border-amber-600 dark:border-walee-500/20 hover:bg-amber-200 dark:hover:bg-walee-500/20 transition-colors cursor-pointer">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-amber-600 dark:text-walee-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2zm-7 5h5v5h-5v-5z"/>
                            </svg>
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Citas</span>
                        </div>
                                <span class="text-sm font-semibold text-amber-700 dark:text-walee-400">{{ $totalCitas }}</span>
                            </a>
                            
                            <!-- Facturas -->
                            <div class="flex items-center justify-between p-2.5 rounded-lg bg-red-100 dark:bg-red-500/10 border border-red-600 dark:border-red-500/20">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Facturas</span>
                            </div>
                                <span class="text-sm font-semibold text-red-700 dark:text-red-400">{{ $facturas->count() }}</span>
                            </div>
                
                            <!-- Cotizaciones -->
                            <div class="flex items-center justify-between p-2.5 rounded-lg bg-blue-100 dark:bg-blue-500/10 border border-blue-600 dark:border-blue-500/20">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Cotizaciones</span>
                        </div>
                                <span class="text-sm font-semibold text-blue-700 dark:text-blue-400">{{ $cotizaciones->count() }}</span>
                    </div>
                
                            <!-- Contratos -->
                            <div class="flex items-center justify-between p-2.5 rounded-lg bg-walee-100 dark:bg-walee-500/10 border border-walee-600 dark:border-walee-500/20">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Contratos</span>
                            </div>
                                <span class="text-sm font-semibold text-walee-700 dark:text-walee-400">{{ $contratos->count() }}</span>
                            </div>
                        </div>
                    </div>
                
                    <!-- Desktop: Layout original -->
                    <div class="hidden sm:block p-4 sm:p-6 lg:p-8">
                        <div class="flex flex-col gap-4 lg:gap-6">
                            <!-- Header con imagen y nombre -->
                            <div class="flex items-start gap-3 sm:gap-4 lg:gap-6">
                                <!-- Imagen -->
                                @if($fotoUrl)
                                    <img src="{{ $fotoUrl }}" alt="{{ $cliente->name }}" class="w-20 h-20 lg:w-24 lg:h-24 rounded-xl lg:rounded-2xl object-cover border-3 border-emerald-500/30 flex-shrink-0 shadow-md">
                        @else
                                    <img src="https://images.icon-icons.com/1188/PNG/512/1490201150-client_82317.png" alt="{{ $cliente->name }}" class="w-20 h-20 lg:w-24 lg:h-24 rounded-xl lg:rounded-2xl object-cover border-3 border-emerald-500/30 flex-shrink-0 shadow-md opacity-80">
                @endif
                
                                <!-- Nombre y estado a la derecha -->
                        <div class="flex-1 min-w-0">
                                    <h1 class="text-2xl lg:text-4xl font-bold text-slate-900 dark:text-white mb-2 sm:mb-3 truncate">{{ $cliente->name }}</h1>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Estado:</span>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-semibold bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-full border border-emerald-600 dark:border-emerald-500/30 w-fit mb-2">
                                            <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                                            {{ $cliente->estado === 'accepted' ? 'Activo' : ucfirst($cliente->estado) }}
                                    </span>
                            @if($cliente->ciudad)
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($cliente->ciudad . ', Costa Rica') }}&zoom=6" target="_blank" rel="noopener noreferrer" class="text-sm text-slate-600 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors cursor-pointer">{{ $cliente->ciudad }}</a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                            <!-- Acciones Rápidas Desktop -->
                            <div class="flex gap-2">
                                <!-- Website Button -->
                                @if($cliente->website)
                                    <a href="{{ $cliente->website }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-100 dark:bg-slate-800 hover:bg-blue-200 dark:hover:bg-slate-700 text-blue-600 dark:text-blue-600 border border-blue-600 dark:border-slate-700 transition-all group shadow-sm">
                                        <svg class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform text-blue-600 dark:text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                                        <span class="text-sm font-medium">Website</span>
                                </a>
                        @else
                                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-slate-100 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 opacity-50 cursor-not-allowed">
                                        <svg class="w-5 h-5 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                </svg>
                                        <span class="text-sm font-medium text-slate-400">Website</span>
                            </div>
                        @endif
            
                                <!-- Email Button -->
                                <button onclick="openEmailModal()" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-amber-100 dark:bg-slate-800 hover:bg-amber-200 dark:hover:bg-slate-700 text-amber-600 dark:text-walee-600 border border-amber-600 dark:border-slate-700 transition-all group shadow-sm">
                                    <svg class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform text-amber-600 dark:text-walee-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                                    <span class="text-sm font-medium">Email</span>
                        </button>
                                
                                <!-- Facebook Button -->
                                @if($cliente->facebook)
                                    <a href="{{ $cliente->facebook }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-violet-100 dark:bg-slate-800 hover:bg-violet-200 dark:hover:bg-slate-700 text-violet-600 dark:text-violet-600 border border-violet-600 dark:border-slate-700 transition-all group shadow-sm">
                                        <svg class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform text-violet-600 dark:text-violet-700" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                                </svg>
                                        <span class="text-sm font-medium">Facebook</span>
                                    </a>
                        @else
                                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-slate-100 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 opacity-50 cursor-not-allowed">
                                        <svg class="w-5 h-5 flex-shrink-0 text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                        <span class="text-sm font-medium text-slate-400">Facebook</span>
                            </div>
                        @endif
                    
                                <!-- WhatsApp Button -->
                                <button onclick="openWhatsAppModal()" 
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-100 dark:bg-slate-800 hover:bg-emerald-200 dark:hover:bg-slate-700 text-emerald-600 dark:text-emerald-600 border border-emerald-600 dark:border-slate-700 transition-all group shadow-sm {{ !$whatsappLink ? 'opacity-60 cursor-not-allowed' : '' }}"
                                        {{ !$whatsappLink ? 'disabled' : '' }}>
                                    <svg class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform text-emerald-600 dark:text-emerald-700" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                                </svg>
                                    <span class="text-sm font-medium">WhatsApp</span>
                        </button>
            </div>
            
                            <!-- Alertas Desktop -->
                            <div class="grid grid-cols-2 lg:grid-cols-5 gap-3">
                                @php
                                    $totalPublicaciones = $publicacionesProgramadas + $publicacionesPublicadas;
                                    $totalCitas = $citasPendientes->count() + $citasPasadas->count();
                                @endphp
                                
                                <!-- Publicaciones -->
                                @php
                                    $planeadorId = $clientePlaneadorId ?? $cliente->id;
                                    $planeadorUrl = route('walee.planeador.publicidad', $planeadorId);
                                @endphp
                                <a href="{{ $planeadorUrl }}" class="flex items-center justify-between p-3 rounded-xl bg-violet-500/10 border border-violet-500/20 hover:bg-violet-500/20 transition-colors cursor-pointer">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                                </svg>
                                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Publicaciones</span>
                    </div>
                                    <span class="text-sm font-semibold text-violet-700 dark:text-violet-400">{{ $publicacionesPublicadas }}/{{ $publicacionesProgramadas }}</span>
                                            </a>
                                
                                <!-- Citas -->
                                <a href="{{ route('walee.calendario', ['cliente_id' => $cliente->id]) }}" class="flex items-center justify-between p-3 rounded-xl bg-walee-500/10 border border-walee-500/20 hover:bg-walee-500/20 transition-colors cursor-pointer">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-amber-600 dark:text-walee-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2zm-7 5h5v5h-5v-5z"/>
                                                </svg>
                                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Citas</span>
                                            </div>
                                    <span class="text-sm font-semibold text-amber-700 dark:text-walee-400">{{ $totalCitas }}</span>
                    </a>
                    
                                <!-- Facturas -->
                                <div class="flex items-center justify-between p-3 rounded-xl bg-red-500/10 border border-red-500/20">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Facturas</span>
                            </div>
                                    <span class="text-sm font-semibold text-red-700 dark:text-red-400">{{ $facturas->count() }}</span>
                    </div>
                    
                                <!-- Cotizaciones -->
                                <div class="flex items-center justify-between p-3 rounded-xl bg-blue-500/10 border border-blue-500/20">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Cotizaciones</span>
                                            </div>
                                    <span class="text-sm font-semibold text-blue-700 dark:text-blue-400">{{ $cotizaciones->count() }}</span>
                    </div>
                    
                                <!-- Contratos -->
                                <div class="flex items-center justify-between p-3 rounded-xl bg-walee-500/10 border border-walee-500/20">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Contratos</span>
                                            </div>
                                    <span class="text-sm font-semibold text-walee-700 dark:text-walee-400">{{ $contratos->count() }}</span>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <footer class="text-center py-2 sm:py-3 flex-shrink-0">
                <p class="text-[10px] sm:text-sm text-slate-600 dark:text-slate-500">
                    <span class="text-walee-400 font-medium">Walee</span> · websolutions.work
                </p>
            </footer>
        </div>
    </div>
    @include('partials.walee-support-button')
    
    <script>
        // Pull to refresh functionality
        (function() {
            let startY = 0;
            let currentY = 0;
            let isPulling = false;
            let pullDistance = 0;
            const pullThreshold = 80; // Distancia mínima para activar refresh
            const maxPullDistance = 120;
            
            const refreshIndicator = document.createElement('div');
            refreshIndicator.id = 'pull-to-refresh-indicator';
            refreshIndicator.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                height: 60px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, #D59F3B 0%, #C78F2E 100%);
                color: white;
                font-weight: 600;
                font-size: 14px;
                z-index: 10000;
                transform: translateY(-100%);
                transition: transform 0.3s ease;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            `;
            refreshIndicator.innerHTML = `
                <svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <span id="refresh-text">Arrastra hacia abajo para actualizar</span>
            `;
            document.body.appendChild(refreshIndicator);
            
            const refreshText = document.getElementById('refresh-text');
            const refreshIcon = refreshIndicator.querySelector('svg');
            
            function handleTouchStart(e) {
                if (window.scrollY === 0) {
                    startY = e.touches[0].clientY;
                    isPulling = true;
                }
            }
            
            function handleTouchMove(e) {
                if (!isPulling) return;
                
                currentY = e.touches[0].clientY;
                pullDistance = currentY - startY;
                
                if (pullDistance > 0 && window.scrollY === 0) {
                    e.preventDefault();
                    const pullPercent = Math.min(pullDistance / maxPullDistance, 1);
                    const translateY = Math.min(pullDistance, maxPullDistance) - 60;
                    
                    refreshIndicator.style.transform = `translateY(${translateY}px)`;
                    
                    if (pullDistance >= pullThreshold) {
                        refreshText.textContent = 'Suelta para actualizar';
                        refreshIndicator.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
                    } else {
                        refreshText.textContent = 'Arrastra hacia abajo para actualizar';
                        refreshIndicator.style.background = 'linear-gradient(135deg, #D59F3B 0%, #C78F2E 100%)';
                    }
                } else {
                    resetPull();
                }
            }
            
            function handleTouchEnd(e) {
                if (!isPulling) return;
                
                if (pullDistance >= pullThreshold) {
                    refreshText.textContent = 'Actualizando...';
                    refreshIcon.style.display = 'block';
                    refreshIndicator.style.background = 'linear-gradient(135deg, #D59F3B 0%, #C78F2E 100%)';
                    
                    setTimeout(() => {
                        window.location.reload();
                    }, 300);
                } else {
                    resetPull();
                }
                
                isPulling = false;
                startY = 0;
                currentY = 0;
                pullDistance = 0;
            }
            
            function resetPull() {
                refreshIndicator.style.transform = 'translateY(-100%)';
                refreshText.textContent = 'Arrastra hacia abajo para actualizar';
                refreshIcon.style.display = 'none';
                refreshIndicator.style.background = 'linear-gradient(135deg, #D59F3B 0%, #C78F2E 100%)';
            }
            
            // También soportar scroll con mouse (para desktop)
            let lastScrollTop = 0;
            function handleScroll() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                if (scrollTop < lastScrollTop && scrollTop === 0 && !isPulling) {
                    // Scroll hacia arriba desde la parte superior
                    const pullPercent = Math.min(Math.abs(scrollTop - lastScrollTop) / 10, 1);
                    if (pullPercent > 0.5) {
                        refreshIndicator.style.transform = 'translateY(0)';
                        refreshText.textContent = 'Suelta para actualizar';
                        
                        setTimeout(() => {
                            if (window.scrollY === 0) {
                                refreshText.textContent = 'Actualizando...';
                                refreshIcon.style.display = 'block';
                                setTimeout(() => {
                                    window.location.reload();
                                }, 300);
                            }
                        }, 500);
                    }
                }
                
                lastScrollTop = scrollTop;
            }
            
            document.addEventListener('touchstart', handleTouchStart, { passive: false });
            document.addEventListener('touchmove', handleTouchMove, { passive: false });
            document.addEventListener('touchend', handleTouchEnd);
            window.addEventListener('scroll', handleScroll);
        })();
        
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
        
        function openWhatsAppModal() {
            @if(!$whatsappLink)
                showWhatsAppError();
                return;
            @endif
            
            const isMobile = window.innerWidth < 640;
            const isDesktop = window.innerWidth >= 1024;
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            const clienteName = @json($cliente->name ?? 'Cliente');
            const whatsappLink = @json($whatsappLink);
            
            let modalWidth = '600px';
            if (isMobile) {
                modalWidth = '98%';
            }
            
            const html = `
                <form id="whatsappForm" class="space-y-4 text-left">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Redactar mensaje para ${clienteName}</label>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">Prompt</label>
                        <textarea id="whatsappPrompt" rows="4" placeholder="Describe el mensaje que quieres enviar (ej: saludar y preguntar sobre disponibilidad para una reunión)"
                                  class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                    </div>
                    <div>
                        <button type="button" onclick="generateWhatsAppMessage()" 
                                class="w-full px-4 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-semibold rounded-lg transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Generar con AI
                        </button>
                    </div>
                    <div id="generatedMessageContainer" class="hidden">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Mensaje generado:</label>
                        <textarea id="generatedMessage" rows="4" readonly
                                  class="w-full px-3 py-2 text-sm rounded-lg border border-emerald-300 dark:border-emerald-600 bg-emerald-50 dark:bg-emerald-900/20 text-slate-900 dark:text-white"></textarea>
                    </div>
                </form>
            `;
            
            Swal.fire({
                title: '',
                html: html,
                width: modalWidth,
                padding: isMobile ? '0.75rem' : (isDesktop ? '1.5rem' : '1.5rem'),
                heightAuto: true,
                customClass: {
                    container: isMobile ? 'swal2-container-mobile' : '',
                    popup: isMobile ? 'swal2-popup-mobile' : (isDarkMode ? 'dark-swal' : 'light-swal'),
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isMobile ? 'swal2-html-container-mobile' : (isDarkMode ? 'dark-swal-html' : 'light-swal-html'),
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                    cancelButton: isDarkMode ? 'dark-swal-cancel' : 'light-swal-cancel'
                },
                showCancelButton: true,
                confirmButtonText: 'Abrir WhatsApp',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#25D366',
                reverseButtons: true,
                didOpen: () => {
                    // Aplicar tema dark/light al modal
                    const popup = Swal.getPopup();
                    if (isDarkMode) {
                        popup.style.backgroundColor = '#0f172a';
                        popup.style.color = '#e2e8f0';
                        popup.style.borderColor = 'rgba(213, 159, 59, 0.2)';
                    } else {
                        popup.style.backgroundColor = '#ffffff';
                        popup.style.color = '#1e293b';
                        popup.style.borderColor = 'rgba(203, 213, 225, 0.5)';
                    }
                    // Hacer el modal más alto
                    popup.style.minHeight = isMobile ? 'auto' : '500px';
                    popup.style.maxHeight = isMobile ? '90vh' : '80vh';
                },
                preConfirm: () => {
                    const generatedMessage = document.getElementById('generatedMessage')?.value;
                    if (!generatedMessage || generatedMessage.trim() === '') {
                        Swal.showValidationMessage('Primero debes generar un mensaje con AI');
                        return false;
                    }
                    return generatedMessage;
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    const message = encodeURIComponent(result.value);
                    // Construir URL correctamente: usar ? si no tiene parámetros, & si ya tiene
                    const separator = whatsappLink.includes('?') ? '&' : '?';
                    const whatsappUrl = `${whatsappLink}${separator}text=${message}`;
                    window.open(whatsappUrl, '_blank');
                }
            });
        }
        
        async function generateWhatsAppMessage() {
            const prompt = document.getElementById('whatsappPrompt')?.value;
            if (!prompt || prompt.trim() === '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campo vacío',
                    text: 'Por favor, describe el mensaje que quieres enviar.',
                    confirmButtonColor: '#D59F3B'
                });
                return;
            }
            
            // Deshabilitar botón y mostrar loading
            const generateButton = event.target;
            const originalText = generateButton.innerHTML;
            generateButton.disabled = true;
            generateButton.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Generando...';
            
            try {
                const response = await fetch('{{ route("walee.whatsapp.generar-mensaje") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ prompt: prompt })
                });
                
                const result = await response.json();
                
                // Restaurar botón
                generateButton.disabled = false;
                generateButton.innerHTML = originalText;
                
                if (result.success) {
                    // Mostrar mensaje generado
                    const container = document.getElementById('generatedMessageContainer');
                    const textarea = document.getElementById('generatedMessage');
                    if (container && textarea) {
                        container.classList.remove('hidden');
                        textarea.value = result.message;
                        // Scroll al mensaje generado
                        container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.message || 'Error al generar el mensaje',
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                // Restaurar botón
                generateButton.disabled = false;
                generateButton.innerHTML = originalText;
                
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión. Por favor, intenta nuevamente.',
                    confirmButtonColor: '#ef4444'
                });
            }
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
                facebook: @json($cliente->facebook ?? ''),
                estado: @json($cliente->estado ?? 'pending'),
                ciudad: @json($cliente->ciudad ?? ''),
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
                            <input type="url" id="clientWebsite" name="website" value="${clienteData.website || ''}"
                                   class="w-full px-3 sm:px-3 py-2 sm:py-2 text-sm sm:text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-400">
                        </div>
                        
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-1.5">Facebook</label>
                            <input type="url" id="clientFacebook" name="facebook" value="${clienteData.facebook || ''}"
                                   class="w-full px-3 sm:px-3 py-2 sm:py-2 text-sm sm:text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-400">
                        </div>
                        
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-1.5">Ciudad</label>
                            <input type="text" id="clientCiudad" name="ciudad" value="${clienteData.ciudad || ''}"
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
                title: '',
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
                confirmButtonColor: '#D59F3B',
                reverseButtons: true,
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
                        window.location.href = '{{ route("walee.cliente.detalle", $cliente->id) }}';
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
        
        // Función para actualizar estado del cliente
        async function updateClientStatus(newStatus) {
            try {
                const response = await fetch('{{ route("walee.cliente.actualizar", $cliente->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        estado: newStatus
                    })
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Estado actualizado!',
                        text: 'El estado del cliente se ha actualizado correctamente',
                        confirmButtonColor: '#D59F3B',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.message || 'Error al actualizar el estado',
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
        
        // Función para eliminar cliente
        function deleteClient() {
            const isDarkMode = document.documentElement.classList.contains('dark');
            const clienteName = @json($cliente->name ?? 'este cliente');
            
            Swal.fire({
                icon: 'warning',
                title: '¿Eliminar cliente?',
                html: `¿Estás seguro de que deseas eliminar <strong>${clienteName}</strong>?<br><br>Esta acción no se puede deshacer.`,
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    performDeleteClient();
                }
            });
        }
        
        async function performDeleteClient() {
            try {
                const response = await fetch('{{ route("walee.clientes.en-proceso.delete") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        client_ids: [{{ $cliente->id }}]
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Cliente eliminado!',
                        text: 'El cliente ha sido eliminado correctamente',
                        confirmButtonColor: '#D59F3B',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '{{ route("walee.clientes.dashboard") }}';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.message || 'Error al eliminar el cliente',
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
        
        // Variables globales para el flujo de fases
        let emailModalData = {
            clienteId: {{ $cliente->id }},
            clienteEmail: '{{ $cliente->email ?? '' }}',
            clienteName: '{{ $cliente->name }}',
            clienteWebsite: '{{ $cliente->website ?? '' }}',
            email: '',
            aiPrompt: '',
            subject: '',
            body: '',
            attachments: null
        };
        
        function openEmailModal() {
            // Resetear datos
            emailModalData.email = emailModalData.clienteEmail;
            emailModalData.aiPrompt = '';
            emailModalData.subject = '';
            emailModalData.body = '';
            emailModalData.attachments = null;
            
            showEmailPhase1();
        }
        
        function showEmailPhase1() {
            const isMobile = window.innerWidth < 640;
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            let modalWidth = '90%';
            if (window.innerWidth >= 1024) {
                modalWidth = '500px';
            } else if (window.innerWidth >= 640) {
                modalWidth = '450px';
            }
            
            const html = `
                <div class="space-y-2.5 text-left">
                    <div class="flex items-center justify-center gap-1 mb-2">
                        <div class="w-2 h-2 rounded-full bg-violet-600"></div>
                        <div class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-600"></div>
                        <div class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-600"></div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Email destinatario <span class="text-red-500">*</span></label>
                        <input type="email" id="email_destinatario" value="${emailModalData.email}" required
                            class="w-full px-2.5 py-1.5 text-xs ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Instrucciones para AI (opcional)</label>
                        <textarea id="ai_prompt" rows="3" placeholder="Ej: Genera un email profesional..."
                            class="w-full px-2.5 py-1.5 text-xs ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none resize-none"></textarea>
                        <button type="button" onclick="generateEmailWithAI()" id="generateEmailBtn"
                            class="mt-1.5 w-full px-2.5 py-1.5 bg-violet-600 hover:bg-violet-500 text-white font-semibold rounded-lg transition-all flex items-center justify-center gap-1.5 text-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                            </svg>
                            <span>Generar con AI</span>
                        </button>
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: '<div class="flex items-center gap-2"><svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M24 5.457v13.909c0 .904-.732 1.636-1.636 1.636h-3.819V11.73L12 16.64l-6.545-4.91v9.273H1.636A1.636 1.636 0 0 1 0 21.366V5.457c0-2.023 2.309-3.178 3.927-1.964L5.455 4.64 12 9.548l6.545-4.91 1.528-1.145C21.69 2.28 24 3.434 24 5.457z"/></svg><span>Crear Email - Paso 1</span></div>',
                html: html,
                width: modalWidth,
                padding: isMobile ? '0.75rem' : '1rem',
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
                    emailModalData.aiPrompt = document.getElementById('ai_prompt').value;
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
            
            let modalWidth = '90%';
            if (window.innerWidth >= 1024) {
                modalWidth = '500px';
            } else if (window.innerWidth >= 640) {
                modalWidth = '450px';
            }
            
            const html = `
                <div class="space-y-2.5 text-left">
                    <div class="flex items-center justify-center gap-1 mb-2">
                        <div class="w-2 h-2 rounded-full bg-violet-600"></div>
                        <div class="w-2 h-2 rounded-full bg-violet-600"></div>
                        <div class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-600"></div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Asunto <span class="text-red-500">*</span></label>
                        <input type="text" id="email_subject" value="${emailModalData.subject}" required placeholder="Asunto del email"
                            class="w-full px-2.5 py-1.5 text-xs ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Mensaje <span class="text-red-500">*</span></label>
                        <textarea id="email_body" rows="6" required placeholder="Escribe o genera el contenido del email..."
                            class="w-full px-2.5 py-1.5 text-xs ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none resize-none">${emailModalData.body}</textarea>
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: '<div class="flex items-center gap-2"><svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M24 5.457v13.909c0 .904-.732 1.636-1.636 1.636h-3.819V11.73L12 16.64l-6.545-4.91v9.273H1.636A1.636 1.636 0 0 1 0 21.366V5.457c0-2.023 2.309-3.178 3.927-1.964L5.455 4.64 12 9.548l6.545-4.91 1.528-1.145C21.69 2.28 24 3.434 24 5.457z"/></svg><span>Crear Email - Paso 2</span></div>',
                html: html,
                width: modalWidth,
                padding: isMobile ? '0.75rem' : '1rem',
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
            
            let modalWidth = '90%';
            if (window.innerWidth >= 1024) {
                modalWidth = '500px';
            } else if (window.innerWidth >= 640) {
                modalWidth = '450px';
            }
            
            const html = `
                <div class="space-y-2.5 text-left">
                    <div class="flex items-center justify-center gap-1 mb-2">
                        <div class="w-2 h-2 rounded-full bg-violet-600"></div>
                        <div class="w-2 h-2 rounded-full bg-violet-600"></div>
                        <div class="w-2 h-2 rounded-full bg-violet-600"></div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Adjuntar archivos (opcional)</label>
                        <input type="file" id="email_attachments" multiple accept=".pdf,.jpg,.jpeg,.png,.gif,.webp"
                            class="w-full px-2.5 py-1.5 text-xs ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none">
                        <p class="text-[10px] ${isDarkMode ? 'text-slate-400' : 'text-slate-500'} mt-0.5">PDF o imágenes (máx. 10MB por archivo)</p>
                        <div id="email_files_list" class="mt-1.5 space-y-1"></div>
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: '<div class="flex items-center gap-2"><svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M24 5.457v13.909c0 .904-.732 1.636-1.636 1.636h-3.819V11.73L12 16.64l-6.545-4.91v9.273H1.636A1.636 1.636 0 0 1 0 21.366V5.457c0-2.023 2.309-3.178 3.927-1.964L5.455 4.64 12 9.548l6.545-4.91 1.528-1.145C21.69 2.28 24 3.434 24 5.457z"/></svg><span>Crear Email - Paso 3</span></div>',
                html: html,
                width: modalWidth,
                padding: isMobile ? '0.75rem' : '1rem',
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
                                        <span class="text-[10px] ${isDarkMode ? 'text-slate-300' : 'text-slate-700'}">${file.name}</span>
                                        <span class="text-[10px] ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
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
    </script>
</body>
</html>

