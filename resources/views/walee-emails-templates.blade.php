<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Templates de Emails</title>
    <meta name="description" content="Walee - Gestión de Templates de Emails">
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
        
        .walee-gradient {
            background: linear-gradient(135deg, #D59F3B 0%, #C78F2E 100%);
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">
    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-walee-400/20 dark:bg-walee-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-walee-400/10 dark:bg-walee-400/5 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-3 py-4 sm:px-4 sm:py-6 lg:px-8">
            @php $pageTitle = 'Templates de Emails'; @endphp
            @include('partials.walee-navbar')
            
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6 md:mb-8 gap-3 sm:gap-4 animate-fade-in-up">
                <div>
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-900 dark:text-white mb-1 sm:mb-2">
                        Templates de Emails
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 hidden sm:block">
                        Crea, guarda y envía templates de emails con AI
                    </p>
                </div>
                <button 
                    onclick="showNuevoTemplateModal()"
                    class="px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg sm:rounded-xl walee-gradient text-white font-medium hover:opacity-90 transition-all flex items-center justify-center gap-1.5 sm:gap-2 shadow-lg w-full sm:w-auto text-xs sm:text-sm"
                >
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Nuevo Template</span>
                </button>
            </div>
            
            <!-- Search Bar -->
            <div class="mb-4 sm:mb-6 animate-fade-in-up" style="animation-delay: 0.05s;">
                <div class="relative">
                    <input 
                        type="text" 
                        id="templateSearchInput"
                        placeholder="Buscar templates..."
                        class="w-full px-3 py-2 sm:px-4 sm:py-3 pl-10 sm:pl-12 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg sm:rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all text-xs sm:text-sm"
                        oninput="filterTemplates()"
                    >
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-slate-400 absolute left-3 sm:left-4 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <button 
                        id="clearSearchBtn"
                        onclick="clearTemplateSearch()"
                        class="absolute right-3 sm:right-4 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 hidden"
                        style="display: none;"
                    >
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Templates Grid -->
            <div id="templatesContainer" class="grid grid-cols-1 gap-2.5 sm:gap-3 md:gap-4 animate-fade-in-up" style="animation-delay: 0.1s;">
                @forelse($templates as $template)
                    <div class="template-card bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-lg sm:rounded-xl p-3 sm:p-4 md:p-6 hover:shadow-lg dark:hover:shadow-none transition-all group w-full" data-nombre="{{ strtolower($template->nombre) }}" data-asunto="{{ strtolower($template->asunto) }}" data-contenido="{{ strtolower($template->contenido) }}">
                        <div class="flex items-start justify-between mb-2 sm:mb-3 md:mb-4">
                            <div class="flex-1 min-w-0 pr-2">
                                <h3 class="text-sm sm:text-base md:text-lg font-semibold text-slate-900 dark:text-white mb-0.5 sm:mb-1 truncate">{{ $template->nombre }}</h3>
                                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 truncate">{{ $template->asunto }}</p>
                            </div>
                            <div class="flex items-center gap-1.5 sm:gap-2 flex-shrink-0">
                                <button 
                                    onclick="editTemplate({{ $template->id }})"
                                    class="p-1.5 sm:p-2 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-400 transition-all"
                                    title="Editar"
                                >
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button 
                                    onclick="deleteTemplate({{ $template->id }})"
                                    class="p-1.5 sm:p-2 rounded-lg bg-red-100 dark:bg-red-500/20 hover:bg-red-200 dark:hover:bg-red-500/30 text-red-600 dark:text-red-400 transition-all"
                                    title="Eliminar"
                                >
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-2 sm:mb-3 md:mb-4 line-clamp-2 sm:line-clamp-3">{{ \Illuminate\Support\Str::limit($template->contenido, 120) }}</p>
                        <div class="flex items-center gap-1.5 sm:gap-2">
                            <button 
                                onclick="enviarTemplate({{ $template->id }})"
                                class="flex-1 px-2.5 py-1.5 sm:px-3 sm:py-2 md:px-4 md:py-2 rounded-lg bg-walee-500 hover:bg-walee-600 text-white font-medium transition-all text-xs sm:text-sm"
                            >
                                Enviar
                            </button>
                            <button 
                                onclick="verTemplate({{ $template->id }})"
                                class="px-2.5 py-1.5 sm:px-3 sm:py-2 md:px-4 md:py-2 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-400 transition-all text-xs sm:text-sm"
                            >
                                Ver
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-8 sm:py-12">
                        <svg class="w-12 h-12 sm:w-16 sm:h-16 text-slate-300 dark:text-slate-700 mx-auto mb-3 sm:mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                        </svg>
                        <h3 class="text-base sm:text-lg font-semibold text-slate-900 dark:text-white mb-1 sm:mb-2">No hay templates guardados</h3>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-3 sm:mb-4">
                            Crea tu primer template usando inteligencia artificial
                        </p>
                        <button 
                            onclick="showNuevoTemplateModal()"
                            class="px-4 py-2 sm:px-6 sm:py-3 rounded-lg sm:rounded-xl walee-gradient text-white font-medium hover:opacity-90 transition-all text-xs sm:text-sm"
                        >
                            Crear Template
                        </button>
                    </div>
                @endforelse
            </div>
            
            <!-- No Results Message -->
            <div id="noResultsMessage" class="hidden text-center py-8 sm:py-12">
                <svg class="w-12 h-12 sm:w-16 sm:h-16 text-slate-300 dark:text-slate-700 mx-auto mb-3 sm:mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <h3 class="text-base sm:text-lg font-semibold text-slate-900 dark:text-white mb-1 sm:mb-2">No se encontraron templates</h3>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400">
                    Intenta con otros términos de búsqueda
                </p>
            </div>
            </div>
        </div>
    </div>
    
    
    
    @php
        $templatesData = $templates->map(function($template) {
            return [
                'id' => $template->id,
                'nombre' => $template->nombre,
                'asunto' => $template->asunto,
                'contenido' => $template->contenido,
            ];
        })->values();
    @endphp
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const templatesData = @json($templatesData);
        const clientes = @json(\App\Models\Client::orderBy('name')->get(['id', 'name', 'email']));
        
        async function showNuevoTemplateModal() {
            const isMobile = window.innerWidth < 640;
            const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;
            const isDesktop = window.innerWidth >= 1024;
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            let modalWidth = '95%';
            if (isDesktop) {
                modalWidth = '900px'; // Ancho en vistas grandes
            } else if (isTablet) {
                modalWidth = '700px';
            } else if (isMobile) {
                modalWidth = '95%';
            }
            
            // Restaurar datos guardados
            const savedData = localStorage.getItem('templateFormData');
            let formData = {
                nombre: '',
                asunto: '',
                contenido: '',
                ai_prompt: ''
            };
            
            if (savedData) {
                try {
                    formData = JSON.parse(savedData);
                } catch (e) {
                    console.error('Error restaurando datos:', e);
                }
            }
            
            const html = `
                <form id="template-form" class="space-y-3 sm:space-y-4 text-left">
                    <input type="hidden" name="template_id" id="template_id" value="">
                    
                    <div>
                        <label class="block text-xs sm:text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1.5 sm:mb-2">Nombre del Template *</label>
                        <input 
                            type="text" 
                            name="nombre" 
                            id="template_nombre"
                            required
                            placeholder="Ej: Propuesta para restaurantes"
                            value="${formData.nombre || ''}"
                            class="w-full px-3 py-2 sm:px-4 sm:py-3 text-xs sm:text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg sm:rounded-xl placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all"
                        >
                    </div>
                    
                    <div>
                        <label class="block text-xs sm:text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1.5 sm:mb-2">Asunto *</label>
                        <input 
                            type="text" 
                            name="asunto" 
                            id="template_asunto"
                            required
                            placeholder="Asunto del email"
                            value="${formData.asunto || ''}"
                            class="w-full px-3 py-2 sm:px-4 sm:py-3 text-xs sm:text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg sm:rounded-xl placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all"
                        >
                    </div>
                    
                    <div class="bg-gradient-to-br ${isDarkMode ? 'from-violet-500/10 to-violet-600/5 border-violet-500/20' : 'from-violet-50 to-violet-100/50 border-violet-200'} border rounded-lg sm:rounded-xl p-3 sm:p-4">
                        <h4 class="text-xs sm:text-sm font-semibold ${isDarkMode ? 'text-white' : 'text-slate-900'} mb-2 sm:mb-3 flex items-center gap-1.5 sm:gap-2">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                            </svg>
                            Generar con AI
                        </h4>
                        <div class="mb-2 sm:mb-3">
                            <label for="ai_prompt" class="block text-xs sm:text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1.5 sm:mb-2">Instrucciones para AI</label>
                            <textarea 
                                id="ai_prompt" 
                                name="ai_prompt"
                                rows="3"
                                placeholder="Ej: Genera un email profesional de propuesta para un negocio de restaurante..."
                                value="${formData.ai_prompt || ''}"
                                class="w-full px-3 py-2 sm:px-4 sm:py-3 text-xs sm:text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-white border-slate-300 text-slate-800'} border rounded-lg sm:rounded-xl placeholder-slate-500 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all resize-none"
                            >${formData.ai_prompt || ''}</textarea>
                        </div>
                        <button 
                            type="button" 
                            id="generateBtn"
                            onclick="generateTemplateWithAI()"
                            class="w-full px-3 py-2 sm:px-4 sm:py-3 bg-violet-600 hover:bg-violet-500 text-white font-medium rounded-lg sm:rounded-xl transition-all flex items-center justify-center gap-1.5 sm:gap-2 text-xs sm:text-sm"
                        >
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                            </svg>
                            Generar Contenido con AI
                        </button>
                    </div>
                    
                    <div>
                        <label class="block text-xs sm:text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1.5 sm:mb-2">Contenido *</label>
                        <textarea 
                            name="contenido" 
                            id="template_contenido"
                            required
                            rows="6"
                            placeholder="Contenido del email..."
                            class="w-full px-3 py-2 sm:px-4 sm:py-3 text-xs sm:text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg sm:rounded-xl placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all resize-none"
                        >${formData.contenido || ''}</textarea>
                    </div>
                </form>
            `;
            
            const result = await Swal.fire({
                title: 'Nuevo Template',
                html: html,
                width: modalWidth,
                padding: isMobile ? '1rem' : '1.5rem',
                showConfirmButton: true,
                confirmButtonText: 'Guardar',
                confirmButtonColor: '#D59F3B',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                    cancelButton: isDarkMode ? 'dark-swal-cancel' : 'light-swal-cancel',
                },
                didOpen: () => {
                    // Agregar event listeners para guardar datos mientras se escribe
                    const form = document.getElementById('template-form');
                    if (form) {
                        const inputs = form.querySelectorAll('input, textarea');
                        inputs.forEach(input => {
                            input.addEventListener('input', saveTemplateFormDataFromModal);
                        });
                    }
                },
                preConfirm: () => {
                    const form = document.getElementById('template-form');
                    if (!form) return false;
                    
                    const nombre = form.querySelector('[name="nombre"]')?.value;
                    const asunto = form.querySelector('[name="asunto"]')?.value;
                    const contenido = form.querySelector('[name="contenido"]')?.value;
                    
                    if (!nombre || nombre.trim() === '') {
                        Swal.showValidationMessage('El nombre es requerido');
                        return false;
                    }
                    if (!asunto || asunto.trim() === '') {
                        Swal.showValidationMessage('El asunto es requerido');
                        return false;
                    }
                    if (!contenido || contenido.trim() === '') {
                        Swal.showValidationMessage('El contenido es requerido');
                        return false;
                    }
                    
                    return {
                        nombre: nombre.trim(),
                        asunto: asunto.trim(),
                        contenido: contenido.trim(),
                        ai_prompt: form.querySelector('[name="ai_prompt"]')?.value || null,
                        template_id: form.querySelector('[name="template_id"]')?.value || ''
                    };
                }
            });
            
            if (result.isConfirmed && result.value) {
                await saveTemplate(result.value);
            } else {
                // Guardar datos antes de cerrar
                saveTemplateFormDataFromModal();
            }
        }
        
        function saveTemplateFormDataFromModal() {
            const form = document.getElementById('template-form');
            if (!form) return;
            
            const formData = {
                nombre: form.querySelector('[name="nombre"]')?.value || '',
                asunto: form.querySelector('[name="asunto"]')?.value || '',
                contenido: form.querySelector('[name="contenido"]')?.value || '',
                ai_prompt: form.querySelector('[name="ai_prompt"]')?.value || ''
            };
            
            localStorage.setItem('templateFormData', JSON.stringify(formData));
        }
        
        function clearTemplateFormData() {
            localStorage.removeItem('templateFormData');
        }
        
        async function editTemplate(templateId) {
            const template = templatesData.find(t => t.id === templateId);
            if (!template) return;
            
            const isMobile = window.innerWidth < 640;
            const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;
            const isDesktop = window.innerWidth >= 1024;
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            let modalWidth = '95%';
            if (isDesktop) {
                modalWidth = '900px'; // Ancho en vistas grandes
            } else if (isTablet) {
                modalWidth = '700px';
            } else if (isMobile) {
                modalWidth = '95%';
            }
            
            const html = `
                <form id="template-form" class="space-y-3 sm:space-y-4 text-left">
                    <input type="hidden" name="template_id" id="template_id" value="${template.id}">
                    
                    <div>
                        <label class="block text-xs sm:text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1.5 sm:mb-2">Nombre del Template *</label>
                        <input 
                            type="text" 
                            name="nombre" 
                            id="template_nombre"
                            required
                            placeholder="Ej: Propuesta para restaurantes"
                            value="${template.nombre || ''}"
                            class="w-full px-3 py-2 sm:px-4 sm:py-3 text-xs sm:text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg sm:rounded-xl placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all"
                        >
                    </div>
                    
                    <div>
                        <label class="block text-xs sm:text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1.5 sm:mb-2">Asunto *</label>
                        <input 
                            type="text" 
                            name="asunto" 
                            id="template_asunto"
                            required
                            placeholder="Asunto del email"
                            value="${template.asunto || ''}"
                            class="w-full px-3 py-2 sm:px-4 sm:py-3 text-xs sm:text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg sm:rounded-xl placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all"
                        >
                    </div>
                    
                    <div class="bg-gradient-to-br ${isDarkMode ? 'from-violet-500/10 to-violet-600/5 border-violet-500/20' : 'from-violet-50 to-violet-100/50 border-violet-200'} border rounded-lg sm:rounded-xl p-3 sm:p-4">
                        <h4 class="text-xs sm:text-sm font-semibold ${isDarkMode ? 'text-white' : 'text-slate-900'} mb-2 sm:mb-3 flex items-center gap-1.5 sm:gap-2">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                            </svg>
                            Generar con AI
                        </h4>
                        <div class="mb-2 sm:mb-3">
                            <label for="ai_prompt" class="block text-xs sm:text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1.5 sm:mb-2">Instrucciones para AI</label>
                            <textarea 
                                id="ai_prompt" 
                                name="ai_prompt"
                                rows="3"
                                placeholder="Ej: Genera un email profesional de propuesta para un negocio de restaurante..."
                                class="w-full px-3 py-2 sm:px-4 sm:py-3 text-xs sm:text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-white border-slate-300 text-slate-800'} border rounded-lg sm:rounded-xl placeholder-slate-500 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all resize-none"
                            ></textarea>
                        </div>
                        <button 
                            type="button" 
                            id="generateBtn"
                            onclick="generateTemplateWithAI()"
                            class="w-full px-3 py-2 sm:px-4 sm:py-3 bg-violet-600 hover:bg-violet-500 text-white font-medium rounded-lg sm:rounded-xl transition-all flex items-center justify-center gap-1.5 sm:gap-2 text-xs sm:text-sm"
                        >
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                            </svg>
                            Generar Contenido con AI
                        </button>
                    </div>
                    
                    <div>
                        <label class="block text-xs sm:text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1.5 sm:mb-2">Contenido *</label>
                        <textarea 
                            name="contenido" 
                            id="template_contenido"
                            required
                            rows="6"
                            placeholder="Contenido del email..."
                            class="w-full px-3 py-2 sm:px-4 sm:py-3 text-xs sm:text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg sm:rounded-xl placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all resize-none"
                        >${template.contenido || ''}</textarea>
                    </div>
                </form>
            `;
            
            const result = await Swal.fire({
                title: 'Editar Template',
                html: html,
                width: modalWidth,
                padding: isMobile ? '1rem' : '1.5rem',
                showConfirmButton: true,
                confirmButtonText: 'Guardar',
                confirmButtonColor: '#D59F3B',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                showDenyButton: true,
                denyButtonText: 'Eliminar',
                denyButtonColor: '#ef4444',
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                    cancelButton: isDarkMode ? 'dark-swal-cancel' : 'light-swal-cancel',
                    denyButton: isDarkMode ? 'dark-swal-deny' : 'light-swal-deny',
                },
                didOpen: () => {
                    // Agregar event listeners para guardar datos mientras se escribe
                    const form = document.getElementById('template-form');
                    if (form) {
                        const inputs = form.querySelectorAll('input, textarea');
                        inputs.forEach(input => {
                            input.addEventListener('input', saveTemplateFormDataFromModal);
                        });
                    }
                },
                preConfirm: () => {
                    const form = document.getElementById('template-form');
                    if (!form) return false;
                    
                    const nombre = form.querySelector('[name="nombre"]')?.value;
                    const asunto = form.querySelector('[name="asunto"]')?.value;
                    const contenido = form.querySelector('[name="contenido"]')?.value;
                    
                    if (!nombre || nombre.trim() === '') {
                        Swal.showValidationMessage('El nombre es requerido');
                        return false;
                    }
                    if (!asunto || asunto.trim() === '') {
                        Swal.showValidationMessage('El asunto es requerido');
                        return false;
                    }
                    if (!contenido || contenido.trim() === '') {
                        Swal.showValidationMessage('El contenido es requerido');
                        return false;
                    }
                    
                    return {
                        nombre: nombre.trim(),
                        asunto: asunto.trim(),
                        contenido: contenido.trim(),
                        ai_prompt: form.querySelector('[name="ai_prompt"]')?.value || null,
                        template_id: template.id
                    };
                }
            });
            
            if (result.isConfirmed && result.value) {
                await saveTemplate(result.value);
            } else if (result.isDenied) {
                // Eliminar template
                deleteTemplate(templateId);
            } else {
                // Guardar datos antes de cerrar
                saveTemplateFormDataFromModal();
            }
        }
        
        function deleteTemplate(templateId) {
            if (!confirm('¿Estás seguro de eliminar este template?')) return;
            
            fetch(`/email-templates/${templateId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error al eliminar el template');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al eliminar el template');
            });
        }
        
        async function enviarTemplate(templateId) {
            const template = templatesData.find(t => t.id === templateId);
            if (!template) return;
            
            const isMobile = window.innerWidth < 640;
            const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;
            const isDesktop = window.innerWidth >= 1024;
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            let modalWidth = '95%';
            if (isDesktop) {
                modalWidth = '600px';
            } else if (isTablet) {
                modalWidth = '550px';
            } else if (isMobile) {
                modalWidth = '95%';
            }
            
            const html = `
                <form id="enviar-template-form" class="space-y-3 sm:space-y-4 text-left">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1.5 sm:mb-2">Email destinatario *</label>
                        <input 
                            type="email" 
                            name="email" 
                            id="enviar_email"
                            required
                            placeholder="cliente@correo.com"
                            class="w-full px-3 py-2 sm:px-4 sm:py-3 text-xs sm:text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg sm:rounded-xl placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all"
                        >
                    </div>
                    
                    <div>
                        <label class="block text-xs sm:text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1.5 sm:mb-2">Asunto *</label>
                        <input 
                            type="text" 
                            name="asunto" 
                            id="enviar_asunto"
                            required
                            value="${template.asunto || ''}"
                            class="w-full px-3 py-2 sm:px-4 sm:py-3 text-xs sm:text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg sm:rounded-xl placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all"
                        >
                    </div>
                    
                    <div>
                        <label class="block text-xs sm:text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1.5 sm:mb-2">Contenido *</label>
                        <textarea 
                            name="contenido" 
                            id="enviar_contenido"
                            required
                            rows="5"
                            class="w-full px-3 py-2 sm:px-4 sm:py-3 text-xs sm:text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg sm:rounded-xl placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all resize-none"
                        >${template.contenido || ''}</textarea>
                    </div>
                </form>
            `;
            
            const result = await Swal.fire({
                title: 'Enviar Template',
                html: html,
                width: modalWidth,
                padding: isMobile ? '1rem' : '1.5rem',
                showConfirmButton: true,
                confirmButtonText: 'Enviar',
                confirmButtonColor: '#D59F3B',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                    cancelButton: isDarkMode ? 'dark-swal-cancel' : 'light-swal-cancel',
                },
                preConfirm: () => {
                    const form = document.getElementById('enviar-template-form');
                    if (!form) return false;
                    
                    const email = form.querySelector('[name="email"]')?.value;
                    const asunto = form.querySelector('[name="asunto"]')?.value;
                    const contenido = form.querySelector('[name="contenido"]')?.value;
                    
                    if (!email || email.trim() === '') {
                        Swal.showValidationMessage('El email es requerido');
                        return false;
                    }
                    if (!asunto || asunto.trim() === '') {
                        Swal.showValidationMessage('El asunto es requerido');
                        return false;
                    }
                    if (!contenido || contenido.trim() === '') {
                        Swal.showValidationMessage('El contenido es requerido');
                        return false;
                    }
                    
                    return {
                        email: email.trim(),
                        subject: asunto.trim(),
                        body: contenido.trim()
                    };
                }
            });
            
            if (result.isConfirmed && result.value) {
                await enviarEmail(result.value);
            }
        }
        
        async function enviarEmail(formData) {
            try {
                const response = await fetch('/walee-emails/enviar', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });
                
                const data = await response.json();
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Email enviado',
                        text: 'El email se ha enviado correctamente',
                        confirmButtonColor: '#D59F3B',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al enviar el email',
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión al enviar el email',
                    confirmButtonColor: '#ef4444'
                });
            }
        }
        
        async function saveTemplate(formData) {
            try {
                const templateId = formData.template_id;
                const url = templateId ? `/email-templates/${templateId}` : '/email-templates';
                const method = templateId ? 'PUT' : 'POST';
                
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        nombre: formData.nombre,
                        asunto: formData.asunto,
                        contenido: formData.contenido,
                        ai_prompt: formData.ai_prompt || null,
                    })
                });
                
                const data = await response.json();
                if (data.success) {
                    // Limpiar datos guardados después de enviar exitosamente
                    clearTemplateFormData();
                    Swal.fire({
                        icon: 'success',
                        title: 'Template guardado',
                        text: templateId ? 'El template se ha actualizado correctamente' : 'El template se ha creado correctamente',
                        confirmButtonColor: '#D59F3B',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al guardar el template',
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión al guardar el template',
                    confirmButtonColor: '#ef4444'
                });
            }
        }
        
        function verTemplate(templateId) {
            const template = templatesData.find(t => t.id === templateId);
            if (!template) return;
            
            const isMobile = window.innerWidth < 640;
            const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;
            const isDesktop = window.innerWidth >= 1024;
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            let modalWidth = '95%';
            if (isDesktop) {
                modalWidth = '900px'; // Ancho en vistas grandes
            } else if (isTablet) {
                modalWidth = '700px';
            } else if (isMobile) {
                modalWidth = '95%';
            }
            
            const html = `
                <div class="text-left space-y-3 sm:space-y-4 ${isMobile ? 'text-xs' : 'text-sm'}">
                    <div>
                        <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1.5 sm:mb-2">Asunto:</label>
                        <p class="font-semibold ${isMobile ? 'text-sm' : 'text-base md:text-lg'} ${isDarkMode ? 'text-white' : 'text-slate-900'}">${template.asunto || 'Sin asunto'}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1.5 sm:mb-2">Contenido:</label>
                        <div class="bg-slate-50 dark:bg-slate-800 rounded-lg sm:rounded-xl p-3 sm:p-4 border border-slate-200 dark:border-slate-700">
                            <p class="${isDarkMode ? 'text-slate-200' : 'text-slate-800'} whitespace-pre-wrap ${isMobile ? 'text-xs' : 'text-sm'}">${template.contenido || 'Sin contenido'}</p>
                        </div>
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: template.nombre || 'Template',
                html: html,
                width: modalWidth,
                padding: isMobile ? '1rem' : '1.5rem',
                showConfirmButton: true,
                confirmButtonText: 'Cerrar',
                confirmButtonColor: '#D59F3B',
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                }
            });
        }
        
        async function generateTemplateWithAI() {
            const aiPromptInput = document.getElementById('ai_prompt');
            const generateBtn = document.getElementById('generateBtn');
            const contenidoInput = document.getElementById('template_contenido');
            const asuntoInput = document.getElementById('template_asunto');
            
            if (!aiPromptInput || !generateBtn || !contenidoInput) return;
            
            const aiPrompt = aiPromptInput.value;
            const originalText = generateBtn.innerHTML;
            
            generateBtn.disabled = true;
            generateBtn.innerHTML = `
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Generando...
            `;
            
            try {
                const response = await fetch('/walee-emails/generar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        ai_prompt: aiPrompt || 'Genera un email profesional de propuesta personalizada',
                        client_name: 'el cliente',
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    contenidoInput.value = data.body || '';
                    if (data.subject && asuntoInput) {
                        asuntoInput.value = data.subject;
                    }
                    // Guardar datos después de generar
                    saveTemplateFormDataFromModal();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'No se pudo generar el email',
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión al generar el email',
                    confirmButtonColor: '#ef4444'
                });
            } finally {
                generateBtn.disabled = false;
                generateBtn.innerHTML = originalText;
            }
        }
        
        
        // Estilos para SweetAlert dark/light mode
        const style = document.createElement('style');
        style.textContent = `
            .dark-swal {
                background: #1e293b !important;
                color: #e2e8f0 !important;
            }
            .light-swal {
                background: #ffffff !important;
                color: #1e293b !important;
            }
            .dark-swal-title {
                color: #f1f5f9 !important;
            }
            .light-swal-title {
                color: #0f172a !important;
            }
            .dark-swal-html {
                color: #cbd5e1 !important;
            }
            .light-swal-html {
                color: #334155 !important;
            }
            .dark-swal-confirm {
                background: #D59F3B !important;
            }
            .light-swal-confirm {
                background: #D59F3B !important;
            }
            .dark-swal-cancel {
                background: #475569 !important;
            }
            .light-swal-cancel {
                background: #6b7280 !important;
            }
            .dark-swal-deny {
                background: #ef4444 !important;
            }
            .light-swal-deny {
                background: #ef4444 !important;
            }
            @media (min-width: 1024px) {
                .swal2-popup {
                    max-height: 90vh !important;
                    overflow-y: auto !important;
                }
                .swal2-html-container {
                    max-height: calc(90vh - 200px) !important;
                    overflow-y: auto !important;
                }
            }
            
            @media (max-width: 640px) {
                .swal2-popup {
                    width: 95% !important;
                    margin: 0.5rem !important;
                    padding: 1rem !important;
                }
                .swal2-title {
                    font-size: 1.125rem !important;
                    margin-bottom: 0.75rem !important;
                }
                .swal2-html-container {
                    margin: 0.5rem 0 !important;
                    font-size: 0.875rem !important;
                }
                .swal2-confirm,
                .swal2-cancel,
                .swal2-deny {
                    font-size: 0.875rem !important;
                    padding: 0.5rem 1rem !important;
                }
                .swal2-actions {
                    margin-top: 1rem !important;
                    gap: 0.5rem !important;
                }
            }
        `;
        document.head.appendChild(style);
        
        // Template search functionality
        function filterTemplates() {
            const searchInput = document.getElementById('templateSearchInput');
            const searchTerm = searchInput.value.toLowerCase().trim();
            const templateCards = document.querySelectorAll('.template-card');
            const templatesContainer = document.getElementById('templatesContainer');
            const noResultsMessage = document.getElementById('noResultsMessage');
            const clearBtn = document.getElementById('clearSearchBtn');
            
            let visibleCount = 0;
            
            templateCards.forEach(card => {
                const nombre = card.getAttribute('data-nombre') || '';
                const asunto = card.getAttribute('data-asunto') || '';
                const contenido = card.getAttribute('data-contenido') || '';
                
                const matches = nombre.includes(searchTerm) || 
                               asunto.includes(searchTerm) || 
                               contenido.includes(searchTerm);
                
                if (matches || searchTerm === '') {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Mostrar/ocultar mensaje de no resultados
            if (searchTerm !== '' && visibleCount === 0) {
                templatesContainer.style.display = 'none';
                noResultsMessage.classList.remove('hidden');
            } else {
                templatesContainer.style.display = 'grid';
                noResultsMessage.classList.add('hidden');
            }
            
            // Mostrar/ocultar botón de limpiar búsqueda
            if (searchTerm !== '') {
                clearBtn.style.display = 'block';
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.style.display = 'none';
                clearBtn.classList.add('hidden');
            }
        }
        
        function clearTemplateSearch() {
            const searchInput = document.getElementById('templateSearchInput');
            searchInput.value = '';
            filterTemplates();
            searchInput.focus();
        }
    </script>
    
    @include('partials.walee-support-button')
</body>
</html>

