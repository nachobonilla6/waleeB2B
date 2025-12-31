<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Configuración - {{ $cliente->name }}</title>
    <meta name="description" content="Configuración del cliente">
    <meta name="theme-color" content="#D59F3B">
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
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fadeInUp 0.5s ease-out forwards; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">
    @php
        $publicaciones = $cliente->posts()->orderBy('created_at', 'desc')->paginate(5);
    @endphp

    <div class="min-h-screen relative">
        <!-- Background -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-400/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 -left-20 w-60 h-60 bg-walee-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative max-w-[90rem] mx-auto px-4 py-6">
            @php $pageTitle = 'Configuración'; @endphp
            @include('partials.walee-navbar')

            <!-- Header con botón de Dashboard -->
            <div class="flex items-center justify-between mb-4 md:mb-6 animate-fade-in-up">
                <div>
                    <h1 class="text-xl md:text-2xl font-bold text-slate-900 dark:text-white mb-1">{{ $cliente->name }}</h1>
                    <p class="text-xs md:text-sm text-slate-600 dark:text-slate-400">Configuración y Publicaciones</p>
                </div>
                <a 
                    href="{{ route('walee.facebook.clientes') }}" 
                    class="inline-flex items-center gap-2 px-3 md:px-4 py-2 md:py-2.5 rounded-lg md:rounded-xl bg-blue-500 hover:bg-blue-600 text-white font-medium transition-all text-xs md:text-sm shadow-sm"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="hidden sm:inline">Dashboard</span>
                </a>
            </div>

            <!-- Tabs -->
            <div class="mb-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="flex gap-2 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 rounded-2xl p-1.5">
                    <a href="{{ route('walee.cliente.settings.publicaciones', $cliente->id) }}" class="flex-1 px-4 py-2.5 rounded-xl font-medium text-sm transition-all tab-button {{ request()->routeIs('walee.cliente.settings.publicaciones') ? 'active bg-violet-500 text-white' : 'bg-transparent text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                        Publicaciones
                    </a>
                    <a href="{{ route('walee.cliente.settings.planeador', $cliente->id) }}" class="flex-1 px-4 py-2.5 rounded-xl font-medium text-sm transition-all tab-button {{ request()->routeIs('walee.cliente.settings.planeador') ? 'active bg-violet-500 text-white' : 'bg-transparent text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                        Planeador
                    </a>
                </div>
            </div>

            <!-- Publicaciones Tab -->
            <div id="content-publicaciones" class="tab-content {{ request()->routeIs('walee.cliente.settings.publicaciones') || request()->routeIs('walee.cliente.settings') ? '' : 'hidden' }} animate-fade-in-up" style="animation-delay: 0.2s;">
                <!-- Create Publicación -->
                <div class="rounded-2xl md:rounded-3xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 p-4 md:p-6 mb-4 md:mb-6">
                    <div class="flex items-center gap-2 md:gap-3 mb-3 md:mb-4">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg md:rounded-xl bg-blue-500/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 md:w-6 md:h-6 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </div>
                        <h2 class="text-base md:text-lg font-bold text-slate-800 dark:text-white">Crear Publicación para Facebook</h2>
                    </div>
                    
                    <form id="publicacion-form" class="space-y-3 md:space-y-4" enctype="multipart/form-data">
                        <!-- AI Prompt -->
                        <div>
                            <label for="ai_prompt" class="block text-xs md:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 md:mb-2">¿Qué tipo de publicación necesitas?</label>
                            <textarea 
                                id="ai_prompt" 
                                name="ai_prompt" 
                                rows="2"
                                placeholder="Ej: Publicación promocionando nuestros servicios..."
                                class="w-full px-3 md:px-4 py-2 md:py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg md:rounded-xl text-slate-800 dark:text-white placeholder-slate-500 dark:placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all resize-none text-xs md:text-sm"
                            ></textarea>
                            <p class="text-[10px] md:text-xs text-slate-600 dark:text-slate-500 mt-1">Describe el tipo de publicación. Si lo dejas vacío, se generará una genérica.</p>
                        </div>
                        
                        <div>
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-1.5 md:mb-2">
                                <label class="block text-xs md:text-sm font-medium text-slate-700 dark:text-slate-300">Texto de la publicación</label>
                                <button 
                                    type="button"
                                    id="generateAIBtn"
                                    onclick="generatePublicacionWithAI()"
                                    class="inline-flex items-center gap-1.5 md:gap-2 px-2.5 md:px-3 py-1 md:py-1.5 rounded-lg bg-walee-500/20 hover:bg-walee-500/30 text-walee-400 border border-walee-500/30 transition-all text-[10px] md:text-xs font-medium self-start sm:self-auto"
                                >
                                    <svg class="w-3.5 h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                                    </svg>
                                    <span class="whitespace-nowrap">Generar con AI</span>
                                </button>
                            </div>
                            <textarea 
                                name="content" 
                                id="publicacion_content"
                                rows="4"
                                required
                                placeholder="Escribe el texto que aparecerá en la publicación..."
                                class="w-full px-3 md:px-4 py-2.5 md:py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg md:rounded-xl text-slate-800 dark:text-white placeholder-slate-500 dark:placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all resize-none text-sm md:text-base"
                            ></textarea>
                            <p class="text-[10px] md:text-xs text-slate-600 dark:text-slate-500 mt-1">Máximo recomendado: 500 caracteres</p>
                        </div>
                        
                        <div>
                            <label class="block text-xs md:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 md:mb-2">Imágenes / Fotos</label>
                            <div class="relative">
                                <input 
                                    type="file" 
                                    name="fotos[]" 
                                    id="fotos"
                                    accept="image/*"
                                    multiple
                                    class="hidden"
                                    onchange="updateFileNames(this)"
                                >
                                <label 
                                    for="fotos" 
                                    class="flex items-center justify-center gap-2 w-full px-3 md:px-4 py-2.5 md:py-3 bg-slate-50 dark:bg-slate-800 border border-dashed border-slate-300 dark:border-slate-600 rounded-lg md:rounded-xl text-slate-600 dark:text-slate-400 hover:border-walee-500/50 hover:text-walee-500 dark:hover:text-walee-400 cursor-pointer transition-all"
                                >
                                    <svg class="w-4 h-4 md:w-5 md:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span id="fileNames" class="text-xs md:text-sm truncate">Subir imágenes (máx. 10)</span>
                                </label>
                            </div>
                            <p class="text-[10px] md:text-xs text-slate-600 dark:text-slate-500 mt-1">Puedes subir múltiples imágenes. Formatos: JPG, PNG, GIF</p>
                        </div>
                        
                        <div class="flex items-start gap-2 p-2.5 md:p-3 bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 rounded-lg md:rounded-xl">
                            <svg class="w-4 h-4 md:w-5 md:h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-[10px] md:text-xs text-blue-700 dark:text-blue-300 leading-relaxed">Esta publicación se enviará automáticamente a Facebook a través del webhook configurado.</p>
                        </div>
                        
                        <button 
                            type="submit"
                            class="w-full px-4 md:px-6 py-2.5 md:py-3 rounded-lg md:rounded-xl bg-blue-500 hover:bg-blue-400 text-white font-medium transition-all flex items-center justify-center gap-2 text-sm md:text-base"
                        >
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            <span class="whitespace-nowrap">Publicar en Facebook</span>
                        </button>
                    </form>
                </div>

                <!-- Lista de Publicaciones -->
                <div class="rounded-3xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 p-6">
                    <div class="flex items-center justify-between mb-4 flex-wrap gap-4">
                        <h2 class="text-lg font-bold text-slate-800 dark:text-white">Publicaciones Existentes</h2>
                        
                        <!-- Searchbar -->
                        <div class="relative flex-1 max-w-md">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                id="searchPublicaciones"
                                placeholder="Buscar publicaciones..."
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 dark:placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all text-sm"
                                onkeyup="filterPublicaciones()"
                            >
                        </div>
                    </div>
                    
                    @if($publicaciones->count() > 0)
                        <div id="publicaciones-list" class="space-y-4">
                            @foreach($publicaciones as $publicacion)
                                <div class="publicacion-item rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 p-3 md:p-4" data-title="{{ strtolower($publicacion->title) }}" data-content="{{ strtolower($publicacion->content) }}">
                                    <!-- Mobile Layout: Vertical -->
                                    <div class="flex flex-col md:hidden gap-3">
                                        <!-- Foto miniatura -->
                                        <div class="flex items-start justify-between gap-2">
                                            @if($publicacion->image_url)
                                                <img 
                                                    src="{{ $publicacion->image_url }}" 
                                                    alt="{{ $publicacion->title }}" 
                                                    class="w-16 h-16 rounded-lg object-cover flex-shrink-0 cursor-pointer hover:opacity-80 transition-opacity"
                                                    onclick="window.open('{{ $publicacion->image_url }}', '_blank')"
                                                    title="Haz clic para ver imagen completa"
                                                >
                                            @else
                                                <div class="w-16 h-16 rounded-lg bg-slate-200 dark:bg-slate-700 flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-6 h-6 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            <button 
                                                onclick="deletePublicacion({{ $publicacion->id }})"
                                                class="w-7 h-7 rounded-lg bg-red-500/20 hover:bg-red-500/30 border border-red-500/30 flex items-center justify-center transition-all flex-shrink-0"
                                                title="Eliminar publicación"
                                            >
                                                <svg class="w-3.5 h-3.5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <!-- Título y texto -->
                                        <div>
                                            <h3 class="text-sm font-semibold text-slate-800 dark:text-white mb-1.5">{{ $publicacion->title }}</h3>
                                            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed mb-2">{{ $publicacion->content }}</p>
                                            <p class="text-[10px] text-slate-500 dark:text-slate-500 mb-3">{{ $publicacion->created_at->format('d/m/Y H:i') }}</p>
                                            
                                            <!-- Botones solo iconos en fila -->
                                            <div class="flex items-center gap-2">
                                                <button 
                                                    onclick="republicarEnFacebook({{ $publicacion->id }})"
                                                    class="w-9 h-9 rounded-lg bg-blue-500/20 hover:bg-blue-500/30 border border-blue-500/30 text-blue-400 transition-all flex items-center justify-center"
                                                    title="Republicar en Facebook"
                                                >
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                                    </svg>
                                                </button>
                                                
                                                <button 
                                                    onclick="shareToWhatsApp({{ $publicacion->id }})"
                                                    class="w-9 h-9 rounded-lg bg-green-500/20 hover:bg-green-500/30 border border-green-500/30 text-green-400 transition-all flex items-center justify-center"
                                                    title="Compartir en WhatsApp"
                                                >
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                                    </svg>
                                                </button>
                                                
                                                <button 
                                                    onclick="shareToLinkedIn({{ $publicacion->id }}, '{{ addslashes($publicacion->title) }}', '{{ addslashes($publicacion->content) }}', '{{ $publicacion->image_url ?? '' }}')"
                                                    class="w-9 h-9 rounded-lg bg-blue-600/20 hover:bg-blue-600/30 border border-blue-600/30 text-blue-500 transition-all flex items-center justify-center"
                                                    title="Compartir en LinkedIn"
                                                >
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Desktop Layout: Horizontal -->
                                    <div class="hidden md:flex items-start gap-4">
                                        @if($publicacion->image_url)
                                            <img 
                                                src="{{ $publicacion->image_url }}" 
                                                alt="{{ $publicacion->title }}" 
                                                class="w-20 h-20 rounded-xl object-cover flex-shrink-0 cursor-pointer hover:opacity-80 transition-opacity"
                                                onclick="window.open('{{ $publicacion->image_url }}', '_blank')"
                                                title="Haz clic para ver imagen completa"
                                            >
                                        @else
                                            <div class="w-20 h-20 rounded-xl bg-slate-200 dark:bg-slate-700 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-8 h-8 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-base font-semibold text-slate-800 dark:text-white mb-1">{{ $publicacion->title }}</h3>
                                            <p class="text-sm text-slate-600 dark:text-slate-400 mb-2 line-clamp-2">{{ $publicacion->content }}</p>
                                            <p class="text-xs text-slate-600 dark:text-slate-500 mb-3">{{ $publicacion->created_at->format('d/m/Y H:i') }}</p>
                                            
                                            <!-- Botones de Compartir -->
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <button 
                                                    onclick="republicarEnFacebook({{ $publicacion->id }})"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-500/20 hover:bg-blue-500/30 border border-blue-500/30 text-blue-400 transition-all text-xs font-medium"
                                                    title="Republicar en Facebook"
                                                >
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                                    </svg>
                                                    <span>Republicar en FB</span>
                                                </button>
                                                
                                                <button 
                                                    onclick="shareToWhatsApp({{ $publicacion->id }})"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-green-500/20 hover:bg-green-500/30 border border-green-500/30 text-green-400 transition-all text-xs font-medium"
                                                    title="Compartir en WhatsApp"
                                                >
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                                    </svg>
                                                    <span>WhatsApp</span>
                                                </button>
                                                
                                                <button 
                                                    onclick="shareToLinkedIn({{ $publicacion->id }}, '{{ addslashes($publicacion->title) }}', '{{ addslashes($publicacion->content) }}', '{{ $publicacion->image_url ?? '' }}')"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-600/20 hover:bg-blue-600/30 border border-blue-600/30 text-blue-500 transition-all text-xs font-medium"
                                                    title="Compartir en LinkedIn"
                                                >
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                                    </svg>
                                                    <span>LinkedIn</span>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <button 
                                            onclick="deletePublicacion({{ $publicacion->id }})"
                                            class="w-8 h-8 rounded-lg bg-red-500/20 hover:bg-red-500/30 border border-red-500/30 flex items-center justify-center transition-all flex-shrink-0"
                                            title="Eliminar publicación"
                                        >
                                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Paginación -->
                        @if($publicaciones->hasPages())
                            <div class="mt-6 flex items-center justify-center gap-2 flex-wrap">
                                @if($publicaciones->onFirstPage())
                                    <span class="px-4 py-2 bg-slate-100 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 rounded-xl cursor-not-allowed text-sm">Anterior</span>
                                @else
                                    <a href="{{ $publicaciones->previousPageUrl() }}" class="px-4 py-2 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-900 dark:text-white rounded-xl transition-colors border border-slate-200 dark:border-slate-700 shadow-sm dark:shadow-none text-sm">Anterior</a>
                                @endif
                                
                                <span class="px-4 py-2 bg-slate-100 dark:bg-slate-800/50 text-slate-600 dark:text-slate-400 rounded-xl border border-slate-200 dark:border-slate-700 text-sm">
                                    Página {{ $publicaciones->currentPage() }} de {{ $publicaciones->lastPage() }}
                                </span>
                                
                                @if($publicaciones->hasMorePages())
                                    <a href="{{ $publicaciones->nextPageUrl() }}" class="px-4 py-2 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-900 dark:text-white rounded-xl transition-colors border border-slate-200 dark:border-slate-700 shadow-sm dark:shadow-none text-sm">Siguiente</a>
                                @else
                                    <span class="px-4 py-2 bg-slate-100 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 rounded-xl cursor-not-allowed text-sm">Siguiente</span>
                                @endif
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-slate-400 dark:text-slate-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-slate-600 dark:text-slate-400 text-sm">No hay publicaciones aún</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Planeador Tab -->
            <div id="content-planeador" class="tab-content {{ request()->routeIs('walee.cliente.settings.planeador') ? '' : 'hidden' }} animate-fade-in-up" style="animation-delay: 0.2s;">
                @php
                    // Convertir el cliente de Client a Cliente si es necesario
                    $clientePlaneador = \App\Models\Cliente::where('correo', $cliente->email)
                        ->orWhere('nombre_empresa', 'like', '%' . $cliente->name . '%')
                        ->first();
                    
                    if (!$clientePlaneador) {
                        // Si no existe, intentar usar el primero disponible, pero solo si existe
                        $clientePlaneador = \App\Models\Cliente::first();
                    }
                @endphp
                
                @if($clientePlaneador)
                    @php
                        // Forzar vista semanal siempre
                        $vista = 'semanal';
                        $mes = request()->get('mes', now()->month);
                        $ano = request()->get('ano', now()->year);
                        
                        // Calcular la semana
                        $semanaParam = request()->get('semana', now()->format('Y-W'));
                        if ($semanaParam && strpos($semanaParam, '-') !== false) {
                            list($anoSemana, $numSemana) = explode('-', $semanaParam);
                            try {
                                $fechaSemana = \Carbon\Carbon::now()->setISODate((int)$anoSemana, (int)$numSemana);
                                $inicioSemana = $fechaSemana->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
                                $finSemana = $fechaSemana->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);
                            } catch (\Exception $e) {
                                $inicioSemana = now()->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
                                $finSemana = now()->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);
                            }
                        } else {
                            $inicioSemana = now()->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
                            $finSemana = now()->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);
                        }
                        
                        $fechaActual = \Carbon\Carbon::create($ano, $mes, 1);
                        $primerDia = $fechaActual->copy()->startOfMonth()->startOfWeek(\Carbon\Carbon::SUNDAY);
                        $ultimoDia = $fechaActual->copy()->endOfMonth()->endOfWeek(\Carbon\Carbon::SATURDAY);
                        
                        // Determinar rango de fechas según la vista
                        if ($vista === 'semanal') {
                            $fechaInicio = $inicioSemana->copy()->startOfDay();
                            $fechaFin = $finSemana->copy()->endOfDay();
                        } else {
                            $fechaInicio = $fechaActual->copy()->startOfMonth()->startOfDay();
                            $fechaFin = $fechaActual->copy()->endOfMonth()->endOfDay();
                        }
                        
                        // Obtener eventos de publicidad del cliente
                        // Primero obtener todos los eventos del cliente para debug
                        $todosEventos = \App\Models\PublicidadEvento::where('cliente_id', $clientePlaneador->id)->get();
                        \Log::info('Planeador - Total eventos del cliente ' . $clientePlaneador->id . ': ' . $todosEventos->count());
                        foreach ($todosEventos as $ev) {
                            \Log::info('Evento ID: ' . $ev->id . ', Fecha: ' . $ev->fecha_inicio . ', Recurrencia: ' . ($ev->recurrencia ?? 'null'));
                        }
                        
                        // Obtener eventos de publicidad del cliente dentro del rango
                        // Simplificar: obtener todos los eventos y filtrar después
                        $eventosBase = \App\Models\PublicidadEvento::where('cliente_id', $clientePlaneador->id)
                            ->where(function($query) use ($fechaInicio, $fechaFin) {
                                $query->where(function($q) use ($fechaInicio, $fechaFin) {
                                    // Eventos que están dentro del rango (sin recurrencia o recurrencia null)
                                    $q->where(function($subQ) {
                                        $subQ->where('recurrencia', 'none')
                                             ->orWhereNull('recurrencia');
                                    })
                                      ->where('fecha_inicio', '>=', $fechaInicio->copy()->startOfDay())
                                      ->where('fecha_inicio', '<=', $fechaFin->copy()->endOfDay());
                                })
                                ->orWhere(function($q) use ($fechaInicio, $fechaFin) {
                                    // Eventos recurrentes
                                    $q->whereNotNull('recurrencia')
                                      ->where('recurrencia', '!=', 'none')
                                      ->where('fecha_inicio', '<=', $fechaFin->copy()->endOfDay())
                                      ->where(function($subQ) use ($fechaInicio) {
                                          $subQ->whereNull('recurrencia_fin')
                                                ->orWhere('recurrencia_fin', '>=', $fechaInicio->copy()->startOfDay());
                                      });
                                });
                            })
                            ->orderBy('fecha_inicio', 'asc')
                            ->get();
                        
                        \Log::info('Planeador - Eventos en rango: ' . $eventosBase->count() . ' (Rango: ' . $fechaInicio->format('Y-m-d H:i') . ' a ' . $fechaFin->format('Y-m-d H:i') . ')');
                        
                        // Generar eventos recurrentes
                        $eventos = collect();
                        foreach ($eventosBase as $evento) {
                            if ($evento->recurrencia === 'none' || !$evento->recurrencia) {
                                // Eventos sin recurrencia - siempre mostrarlos si están en el rango
                                if ($evento->fecha_inicio) {
                                    $fechaEvento = \Carbon\Carbon::parse($evento->fecha_inicio);
                                    if ($fechaEvento->gte($inicioSemana->copy()->startOfDay()) && $fechaEvento->lte($finSemana->copy()->endOfDay())) {
                                        $eventos->push($evento);
                                    }
                                }
                            } else {
                                $fechaInicioRec = $evento->fecha_inicio->copy();
                                $fechaFinRec = $evento->recurrencia_fin ? \Carbon\Carbon::parse($evento->recurrencia_fin) : ($vista === 'semanal' ? $finSemana : $fechaActual->copy()->endOfMonth());
                                $periodoInicio = $vista === 'semanal' ? $inicioSemana : $fechaActual->copy()->startOfMonth();
                                $periodoFin = $vista === 'semanal' ? $finSemana : $fechaActual->copy()->endOfMonth();
                                
                                if ($fechaInicioRec->lt($periodoInicio)) {
                                    if ($evento->recurrencia === 'semanal') {
                                        $semanas = ceil($periodoInicio->diffInWeeks($fechaInicioRec));
                                        $fechaInicioRec = $fechaInicioRec->copy()->addWeeks($semanas);
                                    } elseif ($evento->recurrencia === 'mensual') {
                                        $meses = ceil($periodoInicio->diffInMonths($fechaInicioRec));
                                        $fechaInicioRec = $fechaInicioRec->copy()->addMonths($meses);
                                    }
                                }
                                
                                $fechaActualEvento = $fechaInicioRec->copy();
                                while ($fechaActualEvento->lte($periodoFin) && $fechaActualEvento->lte($fechaFinRec)) {
                                    if ($fechaActualEvento->gte($evento->fecha_inicio->copy()->startOfDay())) {
                                        $condicionFecha = $vista === 'semanal' ? true : ($fechaActualEvento->month == $mes && $fechaActualEvento->year == $ano);
                                        if ($condicionFecha) {
                                            $eventoRecurrente = clone $evento;
                                            $eventoRecurrente->fecha_inicio = $fechaActualEvento->copy();
                                            if ($evento->fecha_fin) {
                                                $duracion = $evento->fecha_inicio->diffInMinutes($evento->fecha_fin);
                                                $eventoRecurrente->fecha_fin = $fechaActualEvento->copy()->addMinutes($duracion);
                                            }
                                            $eventos->push($eventoRecurrente);
                                        }
                                    }
                                    
                                    if ($evento->recurrencia === 'semanal') {
                                        $fechaActualEvento->addWeek();
                                    } elseif ($evento->recurrencia === 'mensual') {
                                        $fechaActualEvento->addMonth();
                                    } elseif ($evento->recurrencia === 'anual') {
                                        $fechaActualEvento->addYear();
                                    } else {
                                        break;
                                    }
                                }
                            }
                        }
                        
                        // Debug: Log de eventos encontrados
                        \Log::info('Planeador - Total eventos encontrados: ' . $eventos->count());
                        foreach ($eventos as $evento) {
                            \Log::info('Evento: ' . $evento->id . ' - Fecha: ' . $evento->fecha_inicio . ' - Título: ' . ($evento->titulo ?? 'Sin título'));
                        }
                        
                        $eventos = $eventos->groupBy(function($evento) {
                            return $evento->fecha_inicio->format('Y-m-d');
                        });
                        
                        // Debug: Log de eventos agrupados por día
                        \Log::info('Planeador - Eventos agrupados: ' . json_encode($eventos->keys()->toArray()));
                        
                        $meses = [
                            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                        ];
                        
                        $tiposPublicidad = ['post', 'historia', 'reel', 'anuncio', 'video', 'carousel'];
                        $plataformas = ['facebook', 'instagram', 'tiktok', 'twitter', 'linkedin', 'youtube'];
                    @endphp
                    
                    <div class="space-y-4">
                        <!-- Debug Info (temporal) -->
                        @if(config('app.debug'))
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4 text-xs">
                            <p class="font-semibold text-yellow-800 dark:text-yellow-200 mb-2">Debug Info:</p>
                            <p>Cliente ID: {{ $clientePlaneador->id }}</p>
                            <p>Total eventos del cliente: {{ $todosEventos->count() }}</p>
                            <p>Eventos en rango: {{ $eventosBase->count() }}</p>
                            <p>Rango: {{ $fechaInicio->format('Y-m-d H:i') }} a {{ $fechaFin->format('Y-m-d H:i') }}</p>
                            <p>Semana: {{ $inicioSemana->format('Y-m-d') }} a {{ $finSemana->format('Y-m-d') }}</p>
                            @if($todosEventos->count() > 0)
                                <p class="mt-2 font-semibold">Eventos encontrados:</p>
                                <ul class="list-disc list-inside ml-2">
                                    @foreach($todosEventos as $ev)
                                        <li>ID: {{ $ev->id }}, Fecha: {{ $ev->fecha_inicio ? $ev->fecha_inicio->format('Y-m-d H:i') : 'N/A' }}, Recurrencia: {{ $ev->recurrencia ?? 'null' }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        @endif
                        
                        <!-- Controles del Planeador -->
                        <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-4">
                            <div class="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
                                <div class="flex gap-2 items-center">
                                    <button onclick="abrirModalProgramarPublicacion()" class="px-4 py-2 rounded-lg bg-violet-500 hover:bg-violet-600 text-white font-medium transition-all text-sm flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Programar Publicación
                                    </button>
                                </div>
                                
                                <div class="flex items-center gap-2">
                                    <button onclick="navegarSemana(-1)" class="px-3 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                    </button>
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                        {{ $inicioSemana->format('d/m') }} - {{ $finSemana->format('d/m/Y') }}
                                    </span>
                                    <button onclick="navegarSemana(1)" class="px-3 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Calendario del Planeador (Solo Vista Semanal) -->
                        <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-4">
                            <div class="grid grid-cols-7 gap-2">
                                @foreach(['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'] as $dia)
                                    <div class="text-center text-xs font-semibold text-slate-600 dark:text-slate-400 py-2">{{ $dia }}</div>
                                @endforeach
                                
                                @for($i = 0; $i < 7; $i++)
                                    @php
                                        $fecha = $inicioSemana->copy()->addDays($i);
                                        $fechaStr = $fecha->format('Y-m-d');
                                        $eventosDia = $eventos->get($fechaStr, collect());
                                    @endphp
                                    <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-3 min-h-[200px]">
                                        <div class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                            {{ $fecha->format('d') }}
                                        </div>
                                        <div class="space-y-2">
                                            @forelse($eventosDia as $evento)
                                                <button onclick="abrirDetalleEvento({{ $evento->id }})" class="w-full text-left text-xs p-2 rounded-lg bg-violet-100 dark:bg-violet-500/20 text-violet-700 dark:text-violet-300 border border-violet-200 dark:border-violet-500/30 hover:bg-violet-200 dark:hover:bg-violet-500/30 transition-all cursor-pointer group">
                                                    <div class="font-medium truncate mb-1">{{ $evento->titulo ?? 'Publicación programada' }}</div>
                                                    <div class="text-[10px] opacity-75 space-y-0.5">
                                                        @if($evento->fecha_inicio)
                                                            <div class="flex items-center gap-1">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                                {{ $evento->fecha_inicio->format('H:i') }}
                                                            </div>
                                                        @endif
                                                        @if($evento->plataforma)
                                                            <div class="flex items-center gap-1">
                                                                @if($evento->plataforma === 'facebook')
                                                                    <svg class="w-3 h-3 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                                                    </svg>
                                                                @elseif($evento->plataforma === 'instagram')
                                                                    <svg class="w-3 h-3 text-pink-600" fill="currentColor" viewBox="0 0 24 24">
                                                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                                                    </svg>
                                                                @elseif($evento->plataforma === 'linkedin')
                                                                    <svg class="w-3 h-3 text-blue-700" fill="currentColor" viewBox="0 0 24 24">
                                                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                                                    </svg>
                                                                @endif
                                                                <span>{{ ucfirst($evento->plataforma) }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </button>
                                            @empty
                                                <div class="text-xs text-slate-400 dark:text-slate-500 text-center py-2">Sin eventos</div>
                                            @endforelse
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                    
                    <script>
                        function navegarSemana(direccion) {
                            const semanaActual = '{{ request()->get("semana", now()->format("Y-W")) }}';
                            const [ano, numSemana] = semanaActual.split('-');
                            const nuevaSemana = parseInt(numSemana) + direccion;
                            const nuevoAno = parseInt(ano);
                            const nuevaSemanaStr = nuevoAno + '-' + (nuevaSemana < 10 ? '0' + nuevaSemana : nuevaSemana);
                            let url = '{{ route("walee.cliente.settings.planeador", $cliente->id) }}';
                            url += '?vista=semanal&semana=' + nuevaSemanaStr;
                            window.location.href = url;
                        }
                        
                        async function abrirDetalleEvento(eventoId) {
                            try {
                                const response = await fetch(`/publicidad-eventos/${eventoId}`, {
                                    method: 'GET',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                    }
                                });
                                
                                const data = await response.json();
                                
                                if (data.success) {
                                    const evento = data.evento;
                                    
                                    // Título
                                    document.getElementById('detalleEventoTitulo').textContent = evento.titulo || 'Publicación programada';
                                    
                                    // Texto (usar texto o descripcion como fallback)
                                    const textoEvento = evento.texto || evento.descripcion || 'Sin texto';
                                    document.getElementById('detalleEventoTexto').textContent = textoEvento;
                                    
                                    // Plataforma con icono
                                    const plataformaText = evento.plataforma ? ucfirst(evento.plataforma) : 'No especificada';
                                    const plataformaElement = document.getElementById('detalleEventoPlataforma');
                                    plataformaElement.innerHTML = '';
                                    if (evento.plataforma === 'facebook') {
                                        plataformaElement.innerHTML = '<span class="flex items-center gap-2"><svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>' + plataformaText + '</span>';
                                    } else if (evento.plataforma === 'instagram') {
                                        plataformaElement.innerHTML = '<span class="flex items-center gap-2"><svg class="w-4 h-4 text-pink-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>' + plataformaText + '</span>';
                                    } else if (evento.plataforma === 'linkedin') {
                                        plataformaElement.innerHTML = '<span class="flex items-center gap-2"><svg class="w-4 h-4 text-blue-700" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>' + plataformaText + '</span>';
                                    } else {
                                        plataformaElement.textContent = plataformaText;
                                    }
                                    
                                    // Fecha y hora formateada
                                    if (evento.fecha_inicio) {
                                        const fecha = new Date(evento.fecha_inicio);
                                        const fechaFormateada = fecha.toLocaleDateString('es-ES', {
                                            weekday: 'long',
                                            year: 'numeric',
                                            month: 'long',
                                            day: 'numeric',
                                            hour: '2-digit',
                                            minute: '2-digit'
                                        });
                                        document.getElementById('detalleEventoFecha').textContent = fechaFormateada;
                                    } else {
                                        document.getElementById('detalleEventoFecha').textContent = 'No especificada';
                                    }
                                    
                                    // Estado con badge
                                    const estadoText = evento.estado ? ucfirst(evento.estado) : 'Programado';
                                    const estadoElement = document.getElementById('detalleEventoEstado');
                                    estadoElement.textContent = estadoText;
                                    estadoElement.className = 'inline-block px-2 py-1 rounded text-xs font-medium ' + 
                                        (evento.estado === 'publicado' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                         evento.estado === 'cancelado' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' :
                                         'bg-violet-100 text-violet-800 dark:bg-violet-900 dark:text-violet-200');
                                    
                                    // Imagen
                                    const imagenContainer = document.getElementById('detalleEventoImagen');
                                    if (evento.imagen_url) {
                                        imagenContainer.innerHTML = `<img src="/storage/${evento.imagen_url}" alt="Imagen de publicación" class="w-full h-auto rounded-lg border border-slate-200 dark:border-slate-700">`;
                                        imagenContainer.classList.remove('hidden');
                                    } else {
                                        imagenContainer.innerHTML = '';
                                        imagenContainer.classList.add('hidden');
                                    }
                                    
                                    // Mostrar modal
                                    document.getElementById('detalleEventoModal').classList.remove('hidden');
                                } else {
                                    alert('Error al cargar los detalles del evento: ' + (data.message || 'Error desconocido'));
                                }
                            } catch (error) {
                                console.error('Error:', error);
                                alert('Error de conexión: ' + error.message);
                            }
                        }
                        
                        function cerrarDetalleEvento() {
                            document.getElementById('detalleEventoModal').classList.add('hidden');
                        }
                        
                        function ucfirst(str) {
                            return str.charAt(0).toUpperCase() + str.slice(1);
                        }
                        
                        // Form submit para programar publicación
                        function inicializarFormProgramar() {
                            const programarForm = document.getElementById('programar-publicacion-form');
                            if (programarForm) {
                                // Remover listener anterior si existe
                                const newForm = programarForm.cloneNode(true);
                                programarForm.parentNode.replaceChild(newForm, programarForm);
                                
                                newForm.addEventListener('submit', async (e) => {
                                    e.preventDefault();
                                    
                                    // Validaciones
                                    const clienteId = newForm.querySelector('input[name="cliente_id"]')?.value;
                                    const plataforma = newForm.querySelector('input[name="plataforma_publicacion"]')?.value;
                                    const fechaPublicacion = newForm.querySelector('input[name="fecha_publicacion"]')?.value;
                                    const texto = newForm.querySelector('textarea[name="texto"]')?.value;
                                    
                                    if (!clienteId || clienteId.trim() === '') {
                                        alert('Error: No se encontró el cliente. Por favor, recarga la página.');
                                        return;
                                    }
                                    
                                    if (!plataforma || plataforma.trim() === '') {
                                        alert('Por favor selecciona una plataforma (Facebook, Instagram o LinkedIn)');
                                        return;
                                    }
                                    
                                    if (!fechaPublicacion || fechaPublicacion.trim() === '') {
                                        alert('Por favor selecciona una fecha y hora para la publicación');
                                        return;
                                    }
                                    
                                    if (!texto || texto.trim() === '') {
                                        if (!confirm('No has escrito texto para la publicación. ¿Deseas continuar sin texto?')) {
                                            return;
                                        }
                                    }
                                    
                                    const formData = new FormData(newForm);
                                    const submitBtn = newForm.querySelector('button[type="submit"]');
                                    const originalText = submitBtn ? submitBtn.innerHTML : 'Programar Publicación';
                                    
                                    if (submitBtn) {
                                        submitBtn.disabled = true;
                                        submitBtn.innerHTML = 'Programando...';
                                    }
                                    
                                    try {
                                        console.log('Enviando formulario...', {
                                            cliente_id: clienteId,
                                            plataforma: plataforma,
                                            fecha_publicacion: fechaPublicacion,
                                            tiene_texto: !!texto,
                                            tiene_imagen: formData.has('imagen')
                                        });
                                        
                                        const response = await fetch('/publicidad-eventos/programar', {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                            },
                                            body: formData
                                        });
                                        
                                        console.log('Respuesta recibida:', response.status);
                                        
                                        if (!response.ok) {
                                            const errorText = await response.text();
                                            console.error('Error del servidor:', errorText);
                                            throw new Error(`Error del servidor: ${response.status} - ${errorText}`);
                                        }
                                        
                                        const data = await response.json();
                                        console.log('Datos recibidos:', data);
                                        
                                        if (data.success) {
                                            alert('✅ Publicación programada exitosamente');
                                            closeProgramarPublicacionModal();
                                            setTimeout(() => {
                                                location.reload();
                                            }, 500);
                                        } else {
                                            alert('Error: ' + (data.message || 'Error al programar publicación'));
                                        }
                                    } catch (error) {
                                        console.error('Error completo:', error);
                                        alert('Error de conexión: ' + error.message);
                                    } finally {
                                        if (submitBtn) {
                                            submitBtn.disabled = false;
                                            submitBtn.innerHTML = originalText;
                                        }
                                    }
                                });
                            }
                        }
                        
                        // Inicializar cuando el DOM esté listo
                        if (document.readyState === 'loading') {
                            document.addEventListener('DOMContentLoaded', inicializarFormProgramar);
                        } else {
                            inicializarFormProgramar();
                        }
                        
                        // También inicializar cuando se abre el modal
                        const originalAbrirModal = window.abrirModalProgramarPublicacion;
                        if (originalAbrirModal) {
                            window.abrirModalProgramarPublicacion = function() {
                                originalAbrirModal();
                                setTimeout(inicializarFormProgramar, 100);
                            };
                        }
                    </script>
                @else
                    <div class="text-center py-8">
                        <p class="text-slate-600 dark:text-slate-400">No se encontró un cliente asociado para el planeador.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Funciones globales para programar publicación
        function abrirModalProgramarPublicacion() {
            @php
                $clientePlaneador = \App\Models\Cliente::where('correo', $cliente->email)
                    ->orWhere('nombre_empresa', 'like', '%' . $cliente->name . '%')
                    ->first();
                
                if (!$clientePlaneador) {
                    $clientePlaneador = \App\Models\Cliente::first();
                }
            @endphp
            
            const modal = document.getElementById('programarPublicacionModal');
            const clienteIdInput = document.querySelector('#programar-publicacion-form input[name="cliente_id"]');
            
            @if(isset($clientePlaneador) && $clientePlaneador)
                if (clienteIdInput) {
                    clienteIdInput.value = '{{ $clientePlaneador->id }}';
                    console.log('Cliente ID establecido:', '{{ $clientePlaneador->id }}');
                } else {
                    console.error('No se encontró el input cliente_id');
                }
            @else
                console.error('No se encontró cliente planeador');
                alert('Error: No se encontró un cliente asociado. Por favor, verifica la configuración.');
                return;
            @endif
            
            if (modal) {
                modal.classList.remove('hidden');
                console.log('Modal abierto');
                
                // Seleccionar Facebook por defecto
                setTimeout(() => {
                    seleccionarPlataforma('facebook');
                }, 100);
            } else {
                console.error('No se encontró el modal');
            }
        }
        
        function closeProgramarPublicacionModal() {
            document.getElementById('programarPublicacionModal').classList.add('hidden');
            const form = document.getElementById('programar-publicacion-form');
            if (form) {
                form.reset();
            }
            const imagePreview = document.getElementById('imagePreview');
            const imagePlaceholder = document.getElementById('imagePlaceholder');
            const removeImageBtn = document.getElementById('removeImageBtn');
            const imagenInput = document.getElementById('imagen_publicacion');
            if (imagePreview) imagePreview.classList.add('hidden');
            if (imagePlaceholder) imagePlaceholder.classList.remove('hidden');
            if (removeImageBtn) removeImageBtn.classList.add('hidden');
            if (imagenInput) imagenInput.value = '';
        }
        
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewImg = document.getElementById('previewImg');
                    const imagePreview = document.getElementById('imagePreview');
                    const imagePlaceholder = document.getElementById('imagePlaceholder');
                    const removeImageBtn = document.getElementById('removeImageBtn');
                    if (previewImg) previewImg.src = e.target.result;
                    if (imagePreview) imagePreview.classList.remove('hidden');
                    if (imagePlaceholder) imagePlaceholder.classList.add('hidden');
                    if (removeImageBtn) removeImageBtn.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }
        
        function removeImage() {
            const imagenInput = document.getElementById('imagen_publicacion');
            const imagePreview = document.getElementById('imagePreview');
            const imagePlaceholder = document.getElementById('imagePlaceholder');
            const removeImageBtn = document.getElementById('removeImageBtn');
            if (imagenInput) imagenInput.value = '';
            if (imagePreview) imagePreview.classList.add('hidden');
            if (imagePlaceholder) imagePlaceholder.classList.remove('hidden');
            if (removeImageBtn) removeImageBtn.classList.add('hidden');
        }
        
        // Seleccionar plataforma
        function seleccionarPlataforma(plataforma) {
            // Actualizar input hidden
            document.getElementById('plataforma_publicacion').value = plataforma;
            
            // Actualizar estilos de botones
            document.querySelectorAll('.plataforma-btn').forEach(btn => {
                btn.classList.remove('border-violet-500', 'bg-violet-50', 'dark:bg-violet-500/20', 'ring-2', 'ring-violet-500/20');
                btn.classList.add('border-slate-300', 'dark:border-slate-700', 'bg-slate-50', 'dark:bg-slate-800');
            });
            
            // Activar botón seleccionado
            const btnSeleccionado = document.querySelector(`.plataforma-btn[data-plataforma="${plataforma}"]`);
            if (btnSeleccionado) {
                btnSeleccionado.classList.remove('border-slate-300', 'dark:border-slate-700', 'bg-slate-50', 'dark:bg-slate-800');
                btnSeleccionado.classList.add('border-violet-500', 'bg-violet-50', 'dark:bg-violet-500/20', 'ring-2', 'ring-violet-500/20');
            }
        }
        
        async function generarTextoAI() {
            const textoTextarea = document.getElementById('texto_publicacion');
            const promptInput = document.getElementById('prompt_personalizado');
            const aiLoading = document.getElementById('aiLoading');
            const btnGenerarTexto = document.getElementById('btnGenerarTexto');
            
            // Usar siempre el ID del cliente principal (Client), no el del planeador (Cliente)
            const clienteId = {{ $cliente->id }};
            
            const prompt = promptInput ? promptInput.value.trim() : '';
            
            if (!textoTextarea) {
                alert('Error: No se encontró el campo de texto');
                return;
            }
            
            if (btnGenerarTexto) btnGenerarTexto.disabled = true;
            if (aiLoading) aiLoading.classList.remove('hidden');
            
            try {
                const response = await fetch(`/walee-cliente/${clienteId}/publicaciones/generar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        ai_prompt: prompt
                    }),
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                if (data.success && textoTextarea) {
                    textoTextarea.value = data.content;
                } else {
                    alert('Error: ' + (data.message || 'Error al generar texto'));
                }
            } catch (error) {
                console.error('Error generando texto con AI:', error);
                alert('Error de conexión: ' + error.message);
            } finally {
                if (btnGenerarTexto) btnGenerarTexto.disabled = false;
                if (aiLoading) aiLoading.classList.add('hidden');
            }
        }
        
        // Tab switching
        function showTab(tabName) {
            // Hide all content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active from all tabs
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active', 'bg-walee-500/20', 'text-walee-400', 'border', 'border-walee-500/30');
                btn.classList.add('text-slate-600', 'dark:text-slate-400');
            });
            
            // Show selected content
            document.getElementById(`content-${tabName}`).classList.remove('hidden');
            
            // Add active to selected tab
            const activeBtn = document.getElementById(`tab-${tabName}`);
            activeBtn.classList.add('active', 'bg-walee-500/20', 'text-walee-400', 'border', 'border-walee-500/30');
            activeBtn.classList.remove('text-slate-600', 'dark:text-slate-400');
        }


        // Update file names display
        function updateFileNames(input) {
            const label = document.getElementById('fileNames');
            if (input.files && input.files.length > 0) {
                const fileCount = input.files.length;
                if (fileCount === 1) {
                    label.textContent = input.files[0].name;
                } else {
                    label.textContent = `${fileCount} archivos seleccionados`;
                }
            } else {
                label.textContent = 'Subir fotos';
            }
        }

        // Generate publicación with AI
        async function generatePublicacionWithAI() {
            const generateBtn = document.getElementById('generateAIBtn');
            const contentTextarea = document.getElementById('publicacion_content');
            const aiPromptInput = document.getElementById('ai_prompt');
            const aiPrompt = aiPromptInput ? aiPromptInput.value.trim() : '';
            
            if (!generateBtn || !contentTextarea) return;
            
            // Disable button
            generateBtn.disabled = true;
            generateBtn.innerHTML = `
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Generando...</span>
            `;
            
            try {
                const response = await fetch(`/walee-cliente/{{ $cliente->id }}/publicaciones/generar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        ai_prompt: aiPrompt
                    }),
                });
                
                const data = await response.json();
                
                if (data.success) {
                    contentTextarea.value = data.content;
                    alert('✅ Publicación generada con AI correctamente');
                } else {
                    alert('Error: ' + (data.message || 'Error al generar publicación'));
                }
            } catch (error) {
                alert('Error de conexión: ' + error.message);
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

        // Publicación form
        document.getElementById('publicacion-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Validación
            const content = formData.get('content').trim();
            const fotos = document.getElementById('fotos').files;
            
            if (!content) {
                alert('Por favor escribe el texto de la publicación');
                return;
            }
            
            if (fotos.length === 0) {
                if (!confirm('No has seleccionado imágenes. ¿Deseas continuar sin imágenes?')) {
                    return;
                }
            }
            
            if (fotos.length > 10) {
                alert('Máximo 10 imágenes permitidas');
                return;
            }
            
            // Deshabilitar botón y mostrar loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Publicando...
            `;
            
            try {
                const response = await fetch(`/walee-cliente/{{ $cliente->id }}/publicaciones`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });
                
                const responseText = await response.text();
                let data;
                
                try {
                    data = JSON.parse(responseText);
                } catch (parseError) {
                    console.error('Respuesta no JSON del servidor:', responseText);
                    alert('Error de conexión: respuesta no válida del servidor');
                    return;
                }
                
                if (data.success) {
                    alert('✅ Publicación creada y enviada a Facebook correctamente');
                    e.target.reset();
                    document.getElementById('fileNames').textContent = 'Subir imágenes (máx. 10)';
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Error al crear la publicación'));
                }
            } catch (error) {
                alert('Error de conexión: ' + error.message);
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });

        // Delete publicación
        async function deletePublicacion(id) {
            if (!confirm('¿Estás seguro de eliminar esta publicación?')) return;
            
            try {
                const response = await fetch(`/walee-cliente/{{ $cliente->id }}/publicaciones/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Error al eliminar'));
                }
            } catch (error) {
                alert('Error de conexión: ' + error.message);
            }
        }

        // Filtrar publicaciones
        function filterPublicaciones() {
            const searchInput = document.getElementById('searchPublicaciones');
            const searchTerm = searchInput.value.toLowerCase().trim();
            const publicaciones = document.querySelectorAll('.publicacion-item');
            let visibleCount = 0;
            
            publicaciones.forEach(function(publicacion) {
                const title = publicacion.getAttribute('data-title') || '';
                const content = publicacion.getAttribute('data-content') || '';
                
                if (searchTerm === '' || title.includes(searchTerm) || content.includes(searchTerm)) {
                    publicacion.style.display = '';
                    visibleCount++;
                } else {
                    publicacion.style.display = 'none';
                }
            });
            
            // Mostrar mensaje si no hay resultados
            const listContainer = document.getElementById('publicaciones-list');
            let noResultsMsg = document.getElementById('no-results-message');
            
            if (visibleCount === 0 && searchTerm !== '') {
                if (!noResultsMsg) {
                    noResultsMsg = document.createElement('div');
                    noResultsMsg.id = 'no-results-message';
                    noResultsMsg.className = 'text-center py-8';
                    noResultsMsg.innerHTML = `
                        <svg class="w-12 h-12 text-slate-400 dark:text-slate-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <p class="text-slate-600 dark:text-slate-400 text-sm">No se encontraron publicaciones que coincidan con "${searchTerm}"</p>
                    `;
                    listContainer.appendChild(noResultsMsg);
                }
            } else {
                if (noResultsMsg) {
                    noResultsMsg.remove();
                }
            }
        }

        // Republicar en Facebook
        async function republicarEnFacebook(id) {
            if (!confirm('¿Deseas republicar esta publicación en Facebook?')) {
                return;
            }
            
            try {
                const response = await fetch(`/walee-cliente/{{ $cliente->id }}/publicaciones/${id}/republicar`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                    },
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('✅ Publicación republicada en Facebook correctamente');
                } else {
                    alert('Error: ' + (data.message || 'Error al republicar'));
                }
            } catch (error) {
                alert('Error de conexión: ' + error.message);
            }
        }

        // Share to WhatsApp - Abre página con imagen visible en nueva pestaña
        function shareToWhatsApp(publicacionId) {
            // Abrir página de compartir por WhatsApp con imagen visible
            const clienteId = {{ $cliente->id }};
            const baseUrl = window.location.origin;
            const shareUrl = baseUrl + '/walee-cliente/' + clienteId + '/publicaciones/' + publicacionId + '/whatsapp';
            
            // Abrir en nueva pestaña (no ventana popup)
            window.open(shareUrl, '_blank');
        }

        // Share to LinkedIn
        function shareToLinkedIn(id, title, content, imageUrl) {
            // Usar la URL de vista previa con Open Graph tags
            const shareUrl = window.location.origin + '/walee-cliente/{{ $cliente->id }}/publicaciones/' + id + '/share';
            // LinkedIn share dialog - ahora mostrará la imagen y texto correctamente gracias a Open Graph
            const linkedInShareUrl = 'https://www.linkedin.com/sharing/share-offsite/?url=' + encodeURIComponent(shareUrl);
            window.open(linkedInShareUrl, '_blank', 'width=600,height=400,scrollbars=yes,resizable=yes');
        }
    </script>
    
    <!-- Modal Programar Publicación -->
    <div id="programarPublicacionModal" class="fixed inset-0 bg-black/80 dark:bg-black/90 backdrop-blur-sm z-[9999] hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="bg-white dark:bg-slate-900 rounded-t-2xl sm:rounded-2xl border-t sm:border border-slate-200 dark:border-slate-700 w-full sm:max-w-4xl lg:max-w-5xl max-h-[85vh] overflow-hidden shadow-xl">
            <div class="flex items-center justify-between p-3 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Programar Publicación</h3>
                <button onclick="closeProgramarPublicacionModal()" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="programar-publicacion-form" class="p-4 md:p-5 space-y-4 overflow-y-auto max-h-[calc(85vh-80px)]">
                <input type="hidden" name="cliente_id" value="{{ $clientePlaneador ? $clientePlaneador->id : '' }}">
                
                <!-- Imagen (más alta) -->
                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Imagen</label>
                    <div class="relative">
                        <input type="file" name="imagen" id="imagen_publicacion" accept="image/*" class="hidden" onchange="previewImage(event)">
                        <label for="imagen_publicacion" class="flex flex-col items-center justify-center w-full min-h-64 border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-xl cursor-pointer hover:border-violet-500 dark:hover:border-violet-500 transition-all bg-slate-50 dark:bg-slate-800/50 relative">
                            <div id="imagePreview" class="hidden w-full rounded-xl overflow-hidden flex items-center justify-center p-2">
                                <img id="previewImg" src="" alt="Preview" class="max-w-full max-h-96 w-auto h-auto object-contain rounded-lg">
                            </div>
                            <div id="imagePlaceholder" class="flex flex-col items-center justify-center p-4 h-64">
                                <svg class="w-12 h-12 text-slate-400 dark:text-slate-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Subir imagen</p>
                                <p class="text-xs text-slate-500 dark:text-slate-500 mt-1">Haz clic o arrastra una imagen aquí</p>
                            </div>
                        </label>
                        <button type="button" id="removeImageBtn" onclick="removeImage()" class="hidden mt-2 px-3 py-1.5 rounded-lg bg-red-500 hover:bg-red-600 text-white text-xs font-medium transition-all">
                            Eliminar imagen
                        </button>
                    </div>
                </div>
                
                <!-- Prompt y Texto en la misma fila -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Prompt personalizado -->
                    <div>
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Prompt Personalizado (opcional)</label>
                        <textarea name="prompt_personalizado" id="prompt_personalizado" rows="4" placeholder="Describe el tipo de publicación que quieres generar con AI..." class="w-full px-4 py-3 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all resize-none"></textarea>
                    </div>
                    
                    <!-- Texto con AI -->
                    <div>
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Texto</label>
                        <div class="flex gap-2">
                            <textarea name="texto" id="texto_publicacion" rows="4" placeholder="Escribe el texto o genera con AI..." class="flex-1 px-4 py-3 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all resize-none"></textarea>
                            <button type="button" onclick="generarTextoAI()" id="btnGenerarTexto" class="px-4 py-3 rounded-xl bg-violet-500 hover:bg-violet-600 text-white text-sm font-medium transition-all flex items-center gap-2 whitespace-nowrap h-fit shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                                <span class="hidden sm:inline">AI</span>
                            </button>
                        </div>
                        <div id="aiLoading" class="hidden text-xs text-violet-600 dark:text-violet-400 flex items-center gap-2 mt-2">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Generando...
                        </div>
                    </div>
                </div>
                
                <!-- Plataforma y Fecha -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Plataforma con iconos -->
                    <div>
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Plataforma</label>
                        <input type="hidden" name="plataforma_publicacion" id="plataforma_publicacion" required value="facebook">
                        <div class="flex gap-2">
                            <button type="button" onclick="seleccionarPlataforma('facebook')" class="plataforma-btn flex-1 px-4 py-3 rounded-xl border-2 border-violet-500 bg-violet-50 dark:bg-violet-500/20 ring-2 ring-violet-500/20 transition-all flex items-center justify-center gap-2" data-plataforma="facebook">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </button>
                            <button type="button" onclick="seleccionarPlataforma('instagram')" class="plataforma-btn flex-1 px-4 py-3 rounded-xl border-2 border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 hover:border-pink-500 hover:bg-pink-50 dark:hover:bg-pink-500/10 transition-all flex items-center justify-center gap-2" data-plataforma="instagram">
                                <svg class="w-6 h-6 text-pink-600 dark:text-pink-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </button>
                            <button type="button" onclick="seleccionarPlataforma('linkedin')" class="plataforma-btn flex-1 px-4 py-3 rounded-xl border-2 border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 hover:border-blue-600 hover:bg-blue-50 dark:hover:bg-blue-600/10 transition-all flex items-center justify-center gap-2" data-plataforma="linkedin">
                                <svg class="w-6 h-6 text-blue-700 dark:text-blue-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Fecha y Hora mejorado -->
                    <div>
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Fecha y Hora</label>
                        <div class="relative">
                            @php
                                $fechaDefault = now()->addDay()->setTime(9, 0, 0)->format('Y-m-d\TH:i');
                            @endphp
                            <input type="datetime-local" name="fecha_publicacion" id="fecha_publicacion" required value="{{ $fechaDefault }}" class="w-full px-4 py-3 text-sm bg-slate-50 dark:bg-slate-800 border-2 border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all shadow-sm">
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex gap-2 pt-1">
                    <button type="submit" class="flex-1 px-4 py-2 rounded-lg bg-violet-500 hover:bg-violet-600 text-white text-sm font-medium transition-all">
                        Programar Publicación
                    </button>
                    <button type="button" onclick="closeProgramarPublicacionModal()" class="px-4 py-2 rounded-lg bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 text-sm font-medium transition-all">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Modal Detalle Evento -->
    <div id="detalleEventoModal" class="fixed inset-0 bg-black/80 dark:bg-black/90 backdrop-blur-sm z-[9999] hidden flex items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 w-full max-w-2xl max-h-[85vh] overflow-hidden shadow-xl">
            <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Detalles de la Publicación</h3>
                <button onclick="cerrarDetalleEvento()" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-6 overflow-y-auto max-h-[calc(85vh-80px)] space-y-5">
                <!-- Imagen (si existe) -->
                <div id="detalleEventoImagen" class="hidden">
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Imagen de la Publicación</label>
                </div>
                
                <!-- Título -->
                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Título</label>
                    <p id="detalleEventoTitulo" class="text-base font-semibold text-slate-900 dark:text-white"></p>
                </div>
                
                <!-- Texto -->
                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Texto de la Publicación</label>
                    <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
                        <p id="detalleEventoTexto" class="text-sm text-slate-900 dark:text-white whitespace-pre-wrap leading-relaxed"></p>
                    </div>
                </div>
                
                <!-- Información adicional en grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Plataforma</label>
                        <div id="detalleEventoPlataforma" class="text-sm text-slate-900 dark:text-white"></div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Estado</label>
                        <div id="detalleEventoEstado" class="text-sm"></div>
                    </div>
                </div>
                
                <!-- Fecha y Hora -->
                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Fecha y Hora de Publicación</label>
                    <div class="flex items-center gap-2 text-sm text-slate-900 dark:text-white">
                        <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span id="detalleEventoFecha"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @include('partials.walee-support-button')
</body>
</html>

