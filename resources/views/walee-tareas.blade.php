<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Tareas</title>
    <meta name="description" content="Gestión de Tareas">
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
                        },
                        purple: {
                            500: '#9333EA',
                            600: '#7C3AED',
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
        ::-webkit-scrollbar-thumb { background: rgba(147, 51, 234, 0.3); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(147, 51, 234, 0.5); }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">
    @php
        $listas = \App\Models\Lista::with(['tareas' => function($query) {
            $query->orderBy('created_at', 'asc');
        }])->get();
        
        // Obtener todas las tareas de todas las listas para "Mis tareas"
        // Separar pendientes y completadas, ordenar según el parámetro
        $orden = request()->get('orden', 'nuevas-primero'); // Por defecto: nuevas primero
        
        $queryPendientes = \App\Models\Tarea::with('lista')->where('estado', 'pending');
        $queryCompletadas = \App\Models\Tarea::with('lista')->where('estado', 'completado');
        
        // Aplicar ordenamiento según la opción seleccionada
        switch($orden) {
            case 'antiguas-primero':
                $queryPendientes->orderBy('created_at', 'asc');
                $queryCompletadas->orderBy('created_at', 'asc');
                break;
            case 'alfabetico':
                $queryPendientes->orderBy('texto', 'asc');
                $queryCompletadas->orderBy('texto', 'asc');
                break;
            case 'tipo':
                $queryPendientes->orderBy('tipo', 'asc')->orderBy('created_at', 'desc');
                $queryCompletadas->orderBy('tipo', 'asc')->orderBy('created_at', 'desc');
                break;
            default: // nuevas-primero
                $queryPendientes->orderBy('created_at', 'desc');
                $queryCompletadas->orderBy('created_at', 'desc');
        }
        
        $tareasPendientes = $queryPendientes->get();
        $tareasCompletadas = $queryCompletadas->get();
        
        // Combinar: pendientes primero, completadas después
        $todasLasTareas = $tareasPendientes->concat($tareasCompletadas);
        
        // Obtener tipos únicos de todas las tareas
        $tiposExistentes = \App\Models\Tarea::whereNotNull('tipo')
            ->distinct()
            ->pluck('tipo')
            ->filter()
            ->values()
            ->toArray();
    @endphp

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-400/20 dark:bg-purple-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-purple-400/10 dark:bg-purple-400/5 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
            @php $pageTitle = 'Tareas'; @endphp
            @include('partials.walee-navbar')
            
            <!-- Tabs -->
            <div class="mb-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="flex items-center gap-4 bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-xl p-2 shadow-sm dark:shadow-none">
                    <button onclick="showTab('favoritas')" id="tab-favoritas" class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-all">
                        <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </button>
                    <button onclick="showTab('mis-tareas')" id="tab-mis-tareas" class="flex-1 px-4 py-2 rounded-lg font-medium text-sm transition-all bg-purple-500 text-white">
                        Mis tareas
                    </button>
                    <button onclick="showTab('nueva-lista')" id="tab-nueva-lista" class="flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Nueva lista
                    </button>
                </div>
            </div>
            
            <!-- Content: Mis tareas -->
            <div id="content-mis-tareas" class="tab-content animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-4 shadow-sm dark:shadow-none">
                    <!-- Section Header -->
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Mis tareas</h2>
                        <div class="flex items-center gap-2">
                            <!-- Ordenamiento Dropdown -->
                            <div class="relative">
                                <button onclick="toggleOrdenDropdown()" class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-all flex items-center gap-1">
                                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                    </svg>
                                    <span class="text-xs text-slate-600 dark:text-slate-400 hidden sm:inline">Ordenar</span>
                                </button>
                                <div id="ordenDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-lg z-50">
                                    <a href="?orden=nuevas-primero" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 {{ request('orden', 'nuevas-primero') === 'nuevas-primero' ? 'bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400' : '' }}">
                                        Más nuevas primero
                                    </a>
                                    <a href="?orden=antiguas-primero" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 {{ request('orden') === 'antiguas-primero' ? 'bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400' : '' }}">
                                        Más antiguas primero
                                    </a>
                                    <a href="?orden=alfabetico" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 {{ request('orden') === 'alfabetico' ? 'bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400' : '' }}">
                                        Alfabético (A-Z)
                                    </a>
                                    <a href="?orden=tipo" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 {{ request('orden') === 'tipo' ? 'bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400' : '' }}">
                                        Por tipo
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tasks List -->
                    <div class="space-y-2">
                        @php
                            $mostrarSeparador = false;
                            $yaMostroSeparador = false;
                        @endphp
                        @forelse($todasLasTareas as $index => $tarea)
                            @if($tarea->estado === 'completado' && !$yaMostroSeparador && $tareasPendientes->count() > 0)
                                <div class="my-4 flex items-center gap-2">
                                    <div class="flex-1 border-t border-slate-200 dark:border-slate-700"></div>
                                    <span class="text-xs font-medium text-slate-500 dark:text-slate-400 px-2">Completadas</span>
                                    <div class="flex-1 border-t border-slate-200 dark:border-slate-700"></div>
                                </div>
                                @php $yaMostroSeparador = true; @endphp
                            @endif
                            <div class="tarea-item flex items-start gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-all animate-fade-in-up" style="animation-delay: {{ 0.1 + ($index * 0.05) }}s;" data-id="{{ $tarea->id }}">
                                <!-- Checkbox -->
                                <button onclick="toggleTarea({{ $tarea->id }})" class="mt-1 flex-shrink-0 w-6 h-6 rounded-full border-2 {{ $tarea->estado === 'completado' ? 'bg-purple-500 border-purple-500' : 'border-slate-300 dark:border-slate-600' }} flex items-center justify-center transition-all hover:border-purple-500">
                                    @if($tarea->estado === 'completado')
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @endif
                                </button>
                                
                                <!-- Task Content -->
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-slate-900 dark:text-white {{ $tarea->estado === 'completado' ? 'line-through opacity-60' : '' }}">
                                        {{ $tarea->texto }}
                                    </p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                        {{ $tarea->created_at->locale('es')->translatedFormat('D, d M, g:i a') }}
                                    </p>
                                </div>
                                
                                <!-- Actions -->
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <button onclick="toggleFavorito({{ $tarea->id }})" class="p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-all">
                                        <svg class="w-4 h-4 {{ $tarea->favorito ? 'text-yellow-500 fill-yellow-500' : 'text-slate-400' }}" fill="{{ $tarea->favorito ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 text-slate-300 dark:text-slate-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <p class="text-slate-600 dark:text-slate-400">No hay tareas aún</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- Content: Favoritas -->
            <div id="content-favoritas" class="tab-content hidden animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-4 shadow-sm dark:shadow-none">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Tareas Favoritas</h2>
                    <div class="space-y-2">
                        @php
                            $tareasFavoritas = $todasLasTareas->where('favorito', true);
                        @endphp
                        @forelse($tareasFavoritas as $index => $tarea)
                            <div class="tarea-item flex items-start gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-all">
                                <button onclick="toggleTarea({{ $tarea->id }})" class="mt-1 flex-shrink-0 w-6 h-6 rounded-full border-2 {{ $tarea->estado === 'completado' ? 'bg-purple-500 border-purple-500' : 'border-slate-300 dark:border-slate-600' }} flex items-center justify-center transition-all">
                                    @if($tarea->estado === 'completado')
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @endif
                                </button>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-slate-900 dark:text-white {{ $tarea->estado === 'completado' ? 'line-through opacity-60' : '' }}">
                                        {{ $tarea->texto }}
                                    </p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                        {{ $tarea->created_at->locale('es')->translatedFormat('D, d M, g:i a') }}
                                    </p>
                                </div>
                                <button onclick="toggleFavorito({{ $tarea->id }})" class="p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-all">
                                    <svg class="w-4 h-4 text-yellow-500 fill-yellow-500" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                </button>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <p class="text-slate-600 dark:text-slate-400">No hay tareas favoritas</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- Content: Nueva Lista -->
            <div id="content-nueva-lista" class="tab-content hidden animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-6 shadow-sm dark:shadow-none">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Nueva Lista</h2>
                    <form id="nueva-lista-form" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nombre de la lista</label>
                            <input 
                                type="text" 
                                name="nombre" 
                                required
                                placeholder="Ej: Trabajo, Personal, etc."
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 focus:outline-none transition-all"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Descripción (opcional)</label>
                            <textarea 
                                name="descripcion" 
                                rows="3"
                                placeholder="Descripción de la lista..."
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 focus:outline-none transition-all resize-none"
                            ></textarea>
                        </div>
                        <button 
                            type="submit"
                            class="w-full px-6 py-3 rounded-xl bg-purple-500 hover:bg-purple-600 text-white font-medium transition-all"
                        >
                            Crear Lista
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Floating Action Button -->
            <button 
                onclick="showNuevaTareaModal()"
                class="fixed bottom-24 right-6 w-14 h-14 bg-purple-500 hover:bg-purple-600 text-white rounded-full shadow-lg flex items-center justify-center transition-all z-40"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </button>
        </div>
    </div>
    
    <!-- Modal Nueva Tarea -->
    <div id="nuevaTareaModal" class="fixed inset-0 bg-black/60 dark:bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 max-w-md w-full max-h-[90vh] overflow-hidden shadow-xl">
            <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Nueva Tarea</h3>
                <button onclick="closeNuevaTareaModal()" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="nueva-tarea-form" class="p-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Lista</label>
                    <select 
                        name="lista_id" 
                        required
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 focus:outline-none transition-all"
                    >
                        <option value="">Selecciona una lista</option>
                        @foreach($listas as $lista)
                            <option value="{{ $lista->id }}">{{ $lista->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Texto de la tarea</label>
                    <textarea 
                        name="texto" 
                        required
                        rows="3"
                        placeholder="Describe la tarea..."
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 focus:outline-none transition-all resize-none"
                    ></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tipo</label>
                    <input 
                        type="text" 
                        name="tipo"
                        list="tipos-list"
                        placeholder="Selecciona un tipo existente o escribe uno nuevo"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 focus:outline-none transition-all"
                    >
                    <datalist id="tipos-list">
                        @foreach($tiposExistentes as $tipo)
                            <option value="{{ $tipo }}">
                        @endforeach
                    </datalist>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Escribe un tipo nuevo o selecciona uno existente de la lista</p>
                </div>
                <button 
                    type="submit"
                    class="w-full px-6 py-3 rounded-xl bg-purple-500 hover:bg-purple-600 text-white font-medium transition-all"
                >
                    Crear Tarea
                </button>
            </form>
        </div>
    </div>
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        function showTab(tabName) {
            // Hide all content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active from all tabs
            document.querySelectorAll('[id^="tab-"]').forEach(btn => {
                btn.classList.remove('bg-purple-500', 'text-white');
                btn.classList.add('text-slate-600', 'dark:text-slate-400');
            });
            
            // Show selected content
            document.getElementById(`content-${tabName}`).classList.remove('hidden');
            
            // Add active to selected tab
            const activeBtn = document.getElementById(`tab-${tabName}`);
            if (activeBtn) {
                activeBtn.classList.remove('text-slate-600', 'dark:text-slate-400');
                activeBtn.classList.add('bg-purple-500', 'text-white');
            }
        }
        
        function toggleOrdenDropdown() {
            const dropdown = document.getElementById('ordenDropdown');
            dropdown.classList.toggle('hidden');
        }
        
        // Cerrar dropdown al hacer click fuera
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('ordenDropdown');
            const button = event.target.closest('[onclick="toggleOrdenDropdown()"]');
            if (!button && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
        
        async function toggleTarea(tareaId) {
            try {
                const response = await fetch(`/tareas/${tareaId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });
                
                const data = await response.json();
                
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Error al actualizar'));
                }
            } catch (error) {
                alert('Error de conexión: ' + error.message);
            }
        }
        
        async function toggleFavorito(tareaId) {
            try {
                const response = await fetch(`/tareas/${tareaId}/favorito`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });
                
                const data = await response.json();
                
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Error al actualizar'));
                }
            } catch (error) {
                alert('Error de conexión: ' + error.message);
            }
        }
        
        function showNuevaTareaModal() {
            document.getElementById('nuevaTareaModal').classList.remove('hidden');
        }
        
        function closeNuevaTareaModal() {
            document.getElementById('nuevaTareaModal').classList.add('hidden');
        }
        
        // Nueva Lista Form
        document.getElementById('nueva-lista-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            
            try {
                const response = await fetch('/listas', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('Lista creada correctamente');
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Error al crear'));
                }
            } catch (error) {
                alert('Error de conexión: ' + error.message);
            }
        });
        
        // Nueva Tarea Form
        document.getElementById('nueva-tarea-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            
            try {
                const response = await fetch('/tareas', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    closeNuevaTareaModal();
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Error al crear'));
                }
            } catch (error) {
                alert('Error de conexión: ' + error.message);
            }
        });
        
        // Close modal on backdrop click
        document.getElementById('nuevaTareaModal').addEventListener('click', function(e) {
            if (e.target === this) closeNuevaTareaModal();
        });
        
        // Close modal on Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeNuevaTareaModal();
        });
    </script>
    @include('partials.walee-support-button')
</body>
</html>

