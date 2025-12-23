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
        $publicaciones = $cliente->posts()->orderBy('created_at', 'desc')->get();
    @endphp

    <div class="min-h-screen relative">
        <!-- Background -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-400/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 -left-20 w-60 h-60 bg-walee-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 py-6">
            @php $pageTitle = 'Configuración'; @endphp
            @include('partials.walee-navbar')

            <!-- Tabs -->
            <div class="mb-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="flex gap-2 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 rounded-2xl p-1.5">
                    <button onclick="showTab('publicaciones')" id="tab-publicaciones" class="flex-1 px-4 py-2.5 rounded-xl font-medium text-sm transition-all tab-button active">
                        Publicaciones
                    </button>
                    <button onclick="showTab('webhook')" id="tab-webhook" class="flex-1 px-4 py-2.5 rounded-xl font-medium text-sm transition-all tab-button">
                        Webhook
                    </button>
                </div>
            </div>

            <!-- Publicaciones Tab -->
            <div id="content-publicaciones" class="tab-content animate-fade-in-up" style="animation-delay: 0.2s;">
                <!-- Create Publicación -->
                <div class="rounded-3xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 p-6 mb-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-slate-800 dark:text-white">Crear Publicación para Facebook</h2>
                    </div>
                    
                    <form id="publicacion-form" class="space-y-4" enctype="multipart/form-data">
                        <!-- AI Prompt -->
                        <div>
                            <label for="ai_prompt" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">¿Qué tipo de publicación necesitas?</label>
                            <textarea 
                                id="ai_prompt" 
                                name="ai_prompt" 
                                rows="2"
                                placeholder="Ej: Publicación promocionando nuestros servicios de diseño web y marketing digital..."
                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 dark:placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all resize-none text-sm"
                            ></textarea>
                            <p class="text-xs text-slate-600 dark:text-slate-500 mt-1">Describe el tipo de publicación que quieres generar. Si lo dejas vacío, se generará una publicación genérica.</p>
                        </div>
                        
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Texto de la publicación</label>
                                <button 
                                    type="button"
                                    id="generateAIBtn"
                                    onclick="generatePublicacionWithAI()"
                                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-walee-500/20 hover:bg-walee-500/30 text-walee-400 border border-walee-500/30 transition-all text-xs font-medium"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                                    </svg>
                                    <span>Generar con AI</span>
                                </button>
                            </div>
                            <textarea 
                                name="content" 
                                id="publicacion_content"
                                rows="5"
                                required
                                placeholder="Escribe el texto que aparecerá en la publicación de Facebook..."
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 dark:placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all resize-none"
                            ></textarea>
                            <p class="text-xs text-slate-600 dark:text-slate-500 mt-1">Máximo recomendado: 500 caracteres</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Imágenes / Fotos</label>
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
                                    class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-dashed border-slate-300 dark:border-slate-600 rounded-xl text-slate-600 dark:text-slate-400 hover:border-walee-500/50 hover:text-walee-500 dark:hover:text-walee-400 cursor-pointer transition-all"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span id="fileNames" class="text-sm">Subir imágenes (máx. 10)</span>
                                </label>
                            </div>
                            <p class="text-xs text-slate-600 dark:text-slate-500 mt-1">Puedes subir múltiples imágenes. Formatos: JPG, PNG, GIF</p>
                        </div>
                        
                        <div class="flex items-center gap-2 p-3 bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 rounded-xl">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-xs text-blue-700 dark:text-blue-300">Esta publicación se enviará automáticamente a Facebook a través del webhook configurado.</p>
                        </div>
                        
                        <button 
                            type="submit"
                            class="w-full px-6 py-3 rounded-xl bg-blue-500 hover:bg-blue-400 text-white font-medium transition-all flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            Publicar en Facebook
                        </button>
                    </form>
                </div>

                <!-- Lista de Publicaciones -->
                <div class="rounded-3xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 p-6">
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white mb-4">Publicaciones Existentes</h2>
                    
                    @if($publicaciones->count() > 0)
                        <div class="space-y-4">
                            @foreach($publicaciones as $publicacion)
                                <div class="rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 p-4">
                                    <div class="flex items-start gap-4">
                                        @if($publicacion->image_url)
                                            <img src="{{ $publicacion->image_url }}" alt="{{ $publicacion->title }}" class="w-20 h-20 rounded-xl object-cover flex-shrink-0">
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
                                                    onclick="shareToFacebook({{ $publicacion->id }}, '{{ addslashes($publicacion->title) }}', '{{ addslashes($publicacion->content) }}', '{{ $publicacion->image_url ?? '' }}')"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-500/20 hover:bg-blue-500/30 border border-blue-500/30 text-blue-400 transition-all text-xs font-medium"
                                                    title="Compartir en Facebook"
                                                >
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                                    </svg>
                                                    <span>Facebook</span>
                                                </button>
                                                
                                                <button 
                                                    onclick="shareToWhatsApp({{ $publicacion->id }}, '{{ addslashes($publicacion->title) }}', '{{ addslashes($publicacion->content) }}')"
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

            <!-- Webhook Tab -->
            <div id="content-webhook" class="tab-content hidden animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="rounded-3xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 p-6">
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white mb-2">Configuración de Webhook</h2>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">Ingresa la URL del webhook para recibir notificaciones de este cliente.</p>
                    
                    <form id="webhook-form" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">URL del Webhook</label>
                            <input 
                                type="url" 
                                name="webhook_url" 
                                id="webhook_url"
                                value="{{ $cliente->webhook_url ?? 'https://n8n.srv1137974.hstgr.cloud/webhook-test/6368cb37-0292-4232-beab-69e98e910df6' }}"
                                placeholder="https://ejemplo.com/webhook"
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 dark:placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all"
                            >
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Page ID</label>
                            <input 
                                type="text" 
                                name="page_id" 
                                id="page_id"
                                value="{{ $cliente->page_id ?? '' }}"
                                placeholder="Ingresa el Page ID de Facebook"
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 dark:placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all"
                            >
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Token</label>
                            <input 
                                type="text" 
                                name="token" 
                                id="token"
                                value="{{ $cliente->token ?? '' }}"
                                placeholder="Ingresa el Token de acceso"
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 dark:placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all"
                            >
                        </div>
                        
                        <button 
                            type="submit"
                            class="w-full px-6 py-3 rounded-xl bg-walee-500 hover:bg-walee-400 text-white font-medium transition-all"
                        >
                            Guardar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
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

        // Webhook form
        document.getElementById('webhook-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const webhookUrl = formData.get('webhook_url');
            const pageId = formData.get('page_id');
            const token = formData.get('token');
            
            try {
                const response = await fetch(`/walee-cliente/{{ $cliente->id }}/webhook`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ 
                        webhook_url: webhookUrl,
                        page_id: pageId,
                        token: token
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('Configuración guardada correctamente');
                } else {
                    alert('Error: ' + (data.message || 'Error al guardar'));
                }
            } catch (error) {
                alert('Error de conexión: ' + error.message);
            }
        });

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

        // Share to Facebook
        function shareToFacebook(id, title, content, imageUrl) {
            const url = window.location.origin + '/walee-cliente/{{ $cliente->id }}/settings';
            const text = title + '\n\n' + content;
            // Facebook share dialog
            const shareUrl = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(url) + '&quote=' + encodeURIComponent(text);
            window.open(shareUrl, '_blank', 'width=600,height=400,scrollbars=yes,resizable=yes');
        }

        // Share to WhatsApp
        function shareToWhatsApp(id, title, content) {
            const text = title + '\n\n' + content;
            // Detectar si es móvil o desktop
            const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
            let url;
            
            if (isMobile) {
                // Para móviles usar whatsapp://
                url = 'whatsapp://send?text=' + encodeURIComponent(text);
            } else {
                // Para desktop usar web.whatsapp.com
                url = 'https://web.whatsapp.com/send?text=' + encodeURIComponent(text);
            }
            
            window.open(url, '_blank');
        }

        // Share to LinkedIn
        function shareToLinkedIn(id, title, content, imageUrl) {
            const url = window.location.origin + '/walee-cliente/{{ $cliente->id }}/settings';
            const summary = content.length > 200 ? content.substring(0, 200) + '...' : content;
            // LinkedIn share dialog
            const shareUrl = 'https://www.linkedin.com/sharing/share-offsite/?url=' + encodeURIComponent(url) + '&summary=' + encodeURIComponent(summary) + '&title=' + encodeURIComponent(title);
            window.open(shareUrl, '_blank', 'width=600,height=400,scrollbars=yes,resizable=yes');
        }
    </script>
    @include('partials.walee-support-button')
</body>
</html>

