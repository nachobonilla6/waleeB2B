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
        <div class="relative max-w-[90rem] mx-auto px-4 py-6 sm:px-6 lg:px-8">
            @php $pageTitle = 'Templates de Emails'; @endphp
            @include('partials.walee-navbar')
            
            <!-- Header -->
            <div class="flex items-center justify-between mb-8 animate-fade-in-up">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white mb-2">
                        Templates de Emails
                    </h1>
                    <p class="text-sm text-slate-600 dark:text-slate-400">
                        Crea, guarda y envía templates de emails con AI
                    </p>
                </div>
                <button 
                    onclick="showNuevoTemplateModal()"
                    class="px-4 py-2 rounded-xl walee-gradient text-white font-medium hover:opacity-90 transition-all flex items-center gap-2 shadow-lg"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Nuevo Template</span>
                </button>
            </div>
            
            <!-- Templates Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 animate-fade-in-up" style="animation-delay: 0.1s;">
                @forelse($templates as $template)
                    <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-xl p-6 hover:shadow-lg dark:hover:shadow-none transition-all group">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-1">{{ $template->nombre }}</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-400">{{ $template->asunto }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button 
                                    onclick="editTemplate({{ $template->id }})"
                                    class="p-2 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-400 transition-all"
                                    title="Editar"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button 
                                    onclick="deleteTemplate({{ $template->id }})"
                                    class="p-2 rounded-lg bg-red-100 dark:bg-red-500/20 hover:bg-red-200 dark:hover:bg-red-500/30 text-red-600 dark:text-red-400 transition-all"
                                    title="Eliminar"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-4 line-clamp-3">{{ \Illuminate\Support\Str::limit($template->contenido, 150) }}</p>
                        <div class="flex items-center gap-2">
                            <button 
                                onclick="enviarTemplate({{ $template->id }})"
                                class="flex-1 px-4 py-2 rounded-lg bg-walee-500 hover:bg-walee-600 text-white font-medium transition-all text-sm"
                            >
                                Enviar
                            </button>
                            <button 
                                onclick="verTemplate({{ $template->id }})"
                                class="px-4 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-400 transition-all text-sm"
                            >
                                Ver
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="w-16 h-16 text-slate-300 dark:text-slate-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">No hay templates guardados</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">
                            Crea tu primer template usando inteligencia artificial
                        </p>
                        <button 
                            onclick="showNuevoTemplateModal()"
                            class="px-6 py-3 rounded-xl walee-gradient text-white font-medium hover:opacity-90 transition-all"
                        >
                            Crear Template
                        </button>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Modal Nuevo/Editar Template -->
    <div id="templateModal" class="fixed inset-0 bg-black/80 dark:bg-black/90 backdrop-blur-sm z-[9999] hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="bg-white dark:bg-slate-900 rounded-t-2xl sm:rounded-2xl border-t sm:border border-slate-200 dark:border-slate-700 w-full sm:max-w-6xl max-h-[70vh] overflow-hidden shadow-xl">
            <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white" id="templateModalTitle">Nuevo Template</h3>
                <button onclick="closeTemplateModal()" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="template-form" class="p-4 md:p-6 space-y-4 overflow-y-auto max-h-[55vh]">
                <input type="hidden" name="template_id" id="template_id">
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nombre del Template</label>
                    <input 
                        type="text" 
                        name="nombre" 
                        id="template_nombre"
                        required
                        placeholder="Ej: Propuesta para restaurantes"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all"
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Asunto</label>
                    <input 
                        type="text" 
                        name="asunto" 
                        id="template_asunto"
                        required
                        placeholder="Asunto del email"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all"
                    >
                </div>
                
                <div class="bg-gradient-to-br from-violet-50 to-violet-100/50 dark:from-violet-500/10 dark:to-violet-600/5 border border-violet-200 dark:border-violet-500/20 rounded-xl p-4">
                    <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                        </svg>
                        Generar con AI
                    </h4>
                    <div class="mb-3">
                        <label for="ai_prompt" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Instrucciones para AI</label>
                        <textarea 
                            id="ai_prompt" 
                            name="ai_prompt"
                            rows="3"
                            placeholder="Ej: Genera un email profesional de propuesta para un negocio de restaurante, mencionando servicios de diseño web y marketing digital..."
                            class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all resize-none"
                        ></textarea>
                    </div>
                    <button 
                        type="button" 
                        id="generateBtn"
                        onclick="generateTemplateWithAI()"
                        class="w-full px-4 py-3 bg-violet-600 hover:bg-violet-500 text-white font-medium rounded-xl transition-all flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                        </svg>
                        Generar Contenido con AI
                    </button>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Contenido</label>
                    <textarea 
                        name="contenido" 
                        id="template_contenido"
                        required
                        rows="8"
                        placeholder="Contenido del email..."
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all resize-none"
                    ></textarea>
                </div>
                
                <div class="flex gap-2 pt-2">
                    <button 
                        type="submit"
                        class="flex-1 px-6 py-3 rounded-xl walee-gradient text-white font-medium transition-all hover:opacity-90"
                    >
                        Guardar Template
                    </button>
                    <button 
                        type="button"
                        id="deleteTemplateBtn"
                        onclick="deleteTemplateFromModal()"
                        class="px-6 py-3 rounded-xl bg-red-500 hover:bg-red-600 text-white font-medium transition-all hidden"
                    >
                        Eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Modal Enviar Template -->
    <div id="enviarTemplateModal" class="fixed inset-0 bg-black/80 dark:bg-black/90 backdrop-blur-sm z-[9999] hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="bg-white dark:bg-slate-900 rounded-t-2xl sm:rounded-2xl border-t sm:border border-slate-200 dark:border-slate-700 w-full sm:max-w-md max-h-[90vh] overflow-hidden shadow-xl">
            <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Enviar Template</h3>
                <button onclick="closeEnviarTemplateModal()" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="enviar-template-form" class="p-4 space-y-4 overflow-y-auto max-h-[70vh]">
                <input type="hidden" name="template_id_enviar" id="template_id_enviar">
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Email destinatario</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="enviar_email"
                        required
                        placeholder="cliente@correo.com"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all"
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Asunto</label>
                    <input 
                        type="text" 
                        name="asunto" 
                        id="enviar_asunto"
                        required
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all"
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Contenido</label>
                    <textarea 
                        name="contenido" 
                        id="enviar_contenido"
                        required
                        rows="6"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all resize-none"
                    ></textarea>
                </div>
                
                <div class="flex gap-2 pt-2">
                    <button 
                        type="submit"
                        class="flex-1 px-6 py-3 rounded-xl walee-gradient text-white font-medium transition-all hover:opacity-90"
                    >
                        Enviar Email
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Modal Ver Template -->
    <div id="verTemplateModal" class="fixed inset-0 bg-black/80 dark:bg-black/90 backdrop-blur-sm z-[9999] hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="bg-white dark:bg-slate-900 rounded-t-2xl sm:rounded-2xl border-t sm:border border-slate-200 dark:border-slate-700 w-full sm:max-w-2xl max-h-[90vh] overflow-hidden shadow-xl">
            <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white" id="ver_template_nombre">Template</h3>
                <button onclick="closeVerTemplateModal()" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-6 overflow-y-auto max-h-[70vh]">
                <div class="mb-4">
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Asunto:</p>
                    <p class="text-lg font-semibold text-slate-900 dark:text-white" id="ver_template_asunto"></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">Contenido:</p>
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-4 border border-slate-200 dark:border-slate-700">
                        <p class="text-slate-800 dark:text-white whitespace-pre-wrap" id="ver_template_contenido"></p>
                    </div>
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
        
        function showNuevoTemplateModal() {
            document.getElementById('templateModalTitle').textContent = 'Nuevo Template';
            document.getElementById('template-form').reset();
            document.getElementById('template_id').value = '';
            document.getElementById('deleteTemplateBtn').classList.add('hidden');
            document.getElementById('templateModal').classList.remove('hidden');
            // Restaurar datos guardados
            restoreTemplateFormData();
        }
        
        function closeTemplateModal() {
            // Guardar datos antes de cerrar
            saveTemplateFormData();
            document.getElementById('templateModal').classList.add('hidden');
        }
        
        function saveTemplateFormData() {
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
        
        function restoreTemplateFormData() {
            const form = document.getElementById('template-form');
            if (!form) return;
            
            // Solo restaurar si no hay un template_id (es un nuevo template, no edición)
            const templateId = form.querySelector('[name="template_id"]')?.value;
            if (templateId) return; // No restaurar si estamos editando
            
            const savedData = localStorage.getItem('templateFormData');
            if (savedData) {
                try {
                    const formData = JSON.parse(savedData);
                    
                    const nombreInput = form.querySelector('[name="nombre"]');
                    const asuntoInput = form.querySelector('[name="asunto"]');
                    const contenidoInput = form.querySelector('[name="contenido"]');
                    const aiPromptInput = form.querySelector('[name="ai_prompt"]');
                    
                    if (nombreInput) nombreInput.value = formData.nombre || '';
                    if (asuntoInput) asuntoInput.value = formData.asunto || '';
                    if (contenidoInput) contenidoInput.value = formData.contenido || '';
                    if (aiPromptInput) aiPromptInput.value = formData.ai_prompt || '';
                } catch (e) {
                    console.error('Error restaurando datos del formulario:', e);
                }
            }
        }
        
        function clearTemplateFormData() {
            localStorage.removeItem('templateFormData');
            const form = document.getElementById('template-form');
            if (form) {
                form.reset();
            }
        }
        
        function editTemplate(templateId) {
            const template = templatesData.find(t => t.id === templateId);
            if (!template) return;
            
            document.getElementById('templateModalTitle').textContent = 'Editar Template';
            document.getElementById('template_id').value = template.id;
            document.getElementById('template_nombre').value = template.nombre;
            document.getElementById('template_asunto').value = template.asunto;
            document.getElementById('template_contenido').value = template.contenido;
            document.getElementById('deleteTemplateBtn').classList.remove('hidden');
            document.getElementById('templateModal').classList.remove('hidden');
            // No restaurar datos guardados cuando se edita un template existente
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
        
        function deleteTemplateFromModal() {
            const templateId = document.getElementById('template_id').value;
            if (templateId) {
                deleteTemplate(templateId);
            }
        }
        
        function enviarTemplate(templateId) {
            const template = templatesData.find(t => t.id === templateId);
            if (!template) return;
            
            document.getElementById('template_id_enviar').value = template.id;
            document.getElementById('enviar_asunto').value = template.asunto;
            document.getElementById('enviar_contenido').value = template.contenido;
            document.getElementById('enviar_email').value = '';
            document.getElementById('enviarTemplateModal').classList.remove('hidden');
        }
        
        function closeEnviarTemplateModal() {
            document.getElementById('enviarTemplateModal').classList.add('hidden');
        }
        
        function verTemplate(templateId) {
            const template = templatesData.find(t => t.id === templateId);
            if (!template) return;
            
            document.getElementById('ver_template_nombre').textContent = template.nombre;
            document.getElementById('ver_template_asunto').textContent = template.asunto;
            document.getElementById('ver_template_contenido').textContent = template.contenido;
            document.getElementById('verTemplateModal').classList.remove('hidden');
        }
        
        function closeVerTemplateModal() {
            document.getElementById('verTemplateModal').classList.add('hidden');
        }
        
        async function generateTemplateWithAI() {
            const aiPrompt = document.getElementById('ai_prompt').value;
            const generateBtn = document.getElementById('generateBtn');
            const originalText = generateBtn.innerHTML;
            
            generateBtn.disabled = true;
            generateBtn.innerHTML = `
                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
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
                    document.getElementById('template_contenido').value = data.body || '';
                    if (data.subject) {
                        document.getElementById('template_asunto').value = data.subject;
                    }
                } else {
                    alert('Error: ' + (data.message || 'No se pudo generar el email'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error de conexión al generar el email');
            } finally {
                generateBtn.disabled = false;
                generateBtn.innerHTML = originalText;
            }
        }
        
        // Template form handler
        const templateForm = document.getElementById('template-form');
        if (templateForm) {
            // Guardar datos mientras se escribe
            const nombreInput = templateForm.querySelector('[name="nombre"]');
            const asuntoInput = templateForm.querySelector('[name="asunto"]');
            const contenidoInput = templateForm.querySelector('[name="contenido"]');
            const aiPromptInput = templateForm.querySelector('[name="ai_prompt"]');
            
            if (nombreInput) {
                nombreInput.addEventListener('input', saveTemplateFormData);
            }
            if (asuntoInput) {
                asuntoInput.addEventListener('input', saveTemplateFormData);
            }
            if (contenidoInput) {
                contenidoInput.addEventListener('input', saveTemplateFormData);
            }
            if (aiPromptInput) {
                aiPromptInput.addEventListener('input', saveTemplateFormData);
            }
            
            templateForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = {
                    nombre: document.getElementById('template_nombre').value,
                    asunto: document.getElementById('template_asunto').value,
                    contenido: document.getElementById('template_contenido').value,
                    ai_prompt: document.getElementById('ai_prompt').value || null,
                };
                
                const templateId = document.getElementById('template_id').value;
                const url = templateId ? `/email-templates/${templateId}` : '/email-templates';
                const method = templateId ? 'PUT' : 'POST';
                
                try {
                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    });
                    
                    const data = await response.json();
                    if (data.success) {
                        // Limpiar datos guardados después de enviar exitosamente
                        clearTemplateFormData();
                        location.reload();
                    } else {
                        alert('Error al guardar el template: ' + (data.message || 'Error desconocido'));
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error al guardar el template');
                }
            });
        }
        
        // Enviar template form handler
        document.getElementById('enviar-template-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = {
                email: document.getElementById('enviar_email').value,
                subject: document.getElementById('enviar_asunto').value,
                body: document.getElementById('enviar_contenido').value,
            };
            
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
                    alert('Email enviado correctamente');
                    closeEnviarTemplateModal();
                } else {
                    alert('Error al enviar el email: ' + (data.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al enviar el email');
            }
        });
        
        // Close modals on backdrop click
        document.getElementById('templateModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeTemplateModal();
        });
        
        document.getElementById('enviarTemplateModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeEnviarTemplateModal();
        });
        
        document.getElementById('verTemplateModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeVerTemplateModal();
        });
    </script>
    
    @include('partials.walee-support-button')
</body>
</html>

