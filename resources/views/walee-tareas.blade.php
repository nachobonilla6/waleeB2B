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
                        },
                        pink: {
                            400: '#F472B6',
                            500: '#EC4899',
                            600: '#DB2777',
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
        
        /* Fondo rosa para light mode - página completa */
        html:not(.dark) body {
            background-color: #FDF2F8 !important; /* pink-50 */
        }
        
        html:not(.dark) .bg-slate-50 {
            background-color: #FDF2F8 !important; /* pink-50 */
        }
        
        html:not(.dark) .min-h-screen {
            background-color: #FDF2F8 !important; /* pink-50 */
        }
        
        /* Estilos rosa para light mode */
        html:not(.dark) .bg-purple-500,
        html:not(.dark) .bg-purple-600 {
            background-color: #EC4899 !important; /* pink-500 */
        }
        
        html:not(.dark) .bg-purple-400\/20,
        html:not(.dark) .bg-purple-400\/10,
        html:not(.dark) .bg-purple-400\/5 {
            background-color: rgba(244, 114, 182, 0.2) !important; /* pink-400 con opacidad */
        }
        
        html:not(.dark) .text-purple-600,
        html:not(.dark) .text-purple-400 {
            color: #EC4899 !important; /* pink-500 */
        }
        
        html:not(.dark) .bg-purple-50,
        html:not(.dark) .bg-purple-500\/10 {
            background-color: #FDF2F8 !important; /* pink-50 */
        }
        
        html:not(.dark) .border-purple-500,
        html:not(.dark) .hover\:border-purple-500:hover {
            border-color: #EC4899 !important; /* pink-500 */
        }
        
        html:not(.dark) .focus\:border-purple-500:focus {
            border-color: #EC4899 !important; /* pink-500 */
        }
        
        html:not(.dark) .focus\:ring-purple-500\/20:focus {
            --tw-ring-color: rgba(236, 72, 153, 0.2) !important; /* pink-500 con opacidad */
        }
        
        html:not(.dark) .hover\:bg-purple-600:hover {
            background-color: #DB2777 !important; /* pink-600 */
        }
        
        html:not(.dark) ::-webkit-scrollbar-thumb {
            background: rgba(236, 72, 153, 0.3) !important; /* pink-500 */
        }
        
        html:not(.dark) ::-webkit-scrollbar-thumb:hover {
            background: rgba(236, 72, 153, 0.5) !important; /* pink-500 */
        }
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
        $listaFiltro = request()->get('lista'); // Filtro por lista
        
        $queryPendientes = \App\Models\Tarea::with('lista')->where('estado', 'pending');
        $queryCompletadas = \App\Models\Tarea::with('lista')->where('estado', 'completado');
        
        // Aplicar filtro por lista si está seleccionado
        if ($listaFiltro) {
            $queryPendientes->where('lista_id', $listaFiltro);
            $queryCompletadas->where('lista_id', $listaFiltro);
        }
        
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
                    <button onclick="showTab('mis-tareas')" id="tab-mis-tareas" class="flex-1 px-4 py-2 rounded-lg font-medium text-sm transition-all bg-purple-500 text-white">
                        Mis tareas
                    </button>
                    <button onclick="showTab('listas')" id="tab-listas" class="flex-1 px-4 py-2 rounded-lg font-medium text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-all">
                        Listas
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
                        <div class="flex items-center gap-3">
                            <h2 class="text-lg font-bold text-slate-900 dark:text-white">Mis tareas</h2>
                            <!-- Filtro por Lista -->
                            <div class="relative">
                                <select 
                                    id="filtro-lista" 
                                    onchange="filtrarPorLista()"
                                    class="px-3 py-1.5 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-700 dark:text-slate-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 focus:outline-none transition-all"
                                >
                                    <option value="">Todas las listas</option>
                                    @foreach($listas as $lista)
                                        <option value="{{ $lista->id }}" {{ request('lista') == $lista->id ? 'selected' : '' }}>
                                            {{ $lista->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
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
                                    @php
                                        $listaParam = request('lista') ? '&lista=' . request('lista') : '';
                                    @endphp
                                    <a href="?orden=nuevas-primero{{ $listaParam }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 {{ request('orden', 'nuevas-primero') === 'nuevas-primero' ? 'bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400' : '' }}">
                                        Más nuevas primero
                                    </a>
                                    <a href="?orden=antiguas-primero{{ $listaParam }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 {{ request('orden') === 'antiguas-primero' ? 'bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400' : '' }}">
                                        Más antiguas primero
                                    </a>
                                    <a href="?orden=alfabetico{{ $listaParam }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 {{ request('orden') === 'alfabetico' ? 'bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400' : '' }}">
                                        Alfabético (A-Z)
                                    </a>
                                    <a href="?orden=tipo{{ $listaParam }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 {{ request('orden') === 'tipo' ? 'bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400' : '' }}">
                                        Por tipo
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Search Bar -->
                    <div class="mb-4">
                        <div class="relative">
                            <input 
                                type="text" 
                                id="searchTareasInput"
                                placeholder="Buscar tareas..."
                                class="w-full px-4 py-2.5 pl-11 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-500 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 focus:outline-none transition-all"
                                onkeyup="filterTareas()"
                            >
                            <svg class="w-5 h-5 text-slate-400 absolute left-3.5 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
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
                            <div class="tarea-item flex items-start gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-all animate-fade-in-up" style="animation-delay: {{ 0.1 + ($index * 0.05) }}s;" data-id="{{ $tarea->id }}" data-search="{{ strtolower($tarea->texto . ' ' . ($tarea->tipo ?? '') . ' ' . ($tarea->lista ? $tarea->lista->nombre : '')) }}">
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
                                    <div class="flex items-center gap-2 mt-1 flex-wrap">
                                        @if($tarea->lista)
                                            <span class="text-xs text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-500/10 px-2 py-0.5 rounded-full font-medium">
                                                {{ $tarea->lista->nombre }}
                                            </span>
                                        @endif
                                        <p class="text-xs text-slate-500 dark:text-slate-400">
                                            {{ $tarea->created_at->locale('es')->translatedFormat('D, d M, g:i a') }}
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Actions -->
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <button onclick="editarTarea({{ $tarea->id }}, '{{ addslashes($tarea->texto) }}', {{ $tarea->lista_id ? $tarea->lista_id : 'null' }}, '{{ $tarea->tipo ? addslashes($tarea->tipo) : '' }}')" class="p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-all" title="Editar tarea">
                                        <svg class="w-4 h-4 text-slate-400 hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button onclick="eliminarTarea({{ $tarea->id }})" class="p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-all" title="Eliminar tarea">
                                        <svg class="w-4 h-4 text-slate-400 hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                    <button onclick="toggleFavorito({{ $tarea->id }})" class="p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-all" title="Marcar como favorito">
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
                            <div class="tarea-item flex items-start gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-all" data-search="{{ strtolower($tarea->texto . ' ' . ($tarea->tipo ?? '') . ' ' . ($tarea->lista ? $tarea->lista->nombre : '')) }}">
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
                                    <div class="flex items-center gap-2 mt-1 flex-wrap">
                                        @if($tarea->lista)
                                            <span class="text-xs text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-500/10 px-2 py-0.5 rounded-full font-medium">
                                                {{ $tarea->lista->nombre }}
                                            </span>
                                        @endif
                                        <p class="text-xs text-slate-500 dark:text-slate-400">
                                            {{ $tarea->created_at->locale('es')->translatedFormat('D, d M, g:i a') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <button onclick="editarTarea({{ $tarea->id }}, '{{ addslashes($tarea->texto) }}', {{ $tarea->lista_id ? $tarea->lista_id : 'null' }}, '{{ $tarea->tipo ? addslashes($tarea->tipo) : '' }}')" class="p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-all" title="Editar tarea">
                                        <svg class="w-4 h-4 text-slate-400 hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button onclick="eliminarTarea({{ $tarea->id }})" class="p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-all" title="Eliminar tarea">
                                        <svg class="w-4 h-4 text-slate-400 hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                    <button onclick="toggleFavorito({{ $tarea->id }})" class="p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-all" title="Marcar como favorito">
                                        <svg class="w-4 h-4 text-yellow-500 fill-yellow-500" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <p class="text-slate-600 dark:text-slate-400">No hay tareas favoritas</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- Content: Listas -->
            <div id="content-listas" class="tab-content hidden animate-fade-in-up" style="animation-delay: 0.2s;">
                <!-- Vista de Listas -->
                <div id="vista-listas" class="space-y-4">
                    @forelse($listas as $lista)
                        <div onclick="verTareasLista({{ $lista->id }})" class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-4 shadow-sm dark:shadow-none cursor-pointer hover:border-purple-500 dark:hover:border-purple-500 transition-all">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $lista->nombre }}</h3>
                                    @if($lista->descripcion)
                                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $lista->descripcion }}</p>
                                    @endif
                                    <span class="text-xs text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded-full mt-2 inline-block">
                                        {{ $lista->tareas->count() }} tareas
                                    </span>
                                </div>
                                <div class="flex items-center gap-2" onclick="event.stopPropagation()">
                                    <button onclick="editarLista({{ $lista->id }}, '{{ addslashes($lista->nombre) }}', '{{ addslashes($lista->descripcion ?? '') }}')" class="p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-all" title="Editar lista">
                                        <svg class="w-4 h-4 text-slate-400 hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button onclick="eliminarLista({{ $lista->id }})" class="p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-all" title="Eliminar lista">
                                        <svg class="w-4 h-4 text-slate-400 hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-12 shadow-sm dark:shadow-none text-center">
                            <svg class="w-16 h-16 text-slate-300 dark:text-slate-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="text-slate-600 dark:text-slate-400 mb-2">No hay listas aún</p>
                            <p class="text-sm text-slate-500 dark:text-slate-500">Crea una nueva lista para organizar tus tareas</p>
                        </div>
                    @endforelse
                </div>
                
                <!-- Vista de Tareas de una Lista -->
                <div id="vista-tareas-lista" class="hidden">
                    <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-2xl p-4 shadow-sm dark:shadow-none">
                        <div class="flex items-center gap-3 mb-4">
                            <button onclick="volverAListas()" class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-all" title="Volver a listas">
                                <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <div class="flex-1">
                                <h2 id="nombre-lista-actual" class="text-lg font-bold text-slate-900 dark:text-white"></h2>
                                <p id="descripcion-lista-actual" class="text-sm text-slate-500 dark:text-slate-400 mt-1"></p>
                            </div>
                        </div>
                        <div id="tareas-lista-container" class="space-y-2">
                            <!-- Las tareas se cargarán aquí dinámicamente -->
                        </div>
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
                class="fixed right-6 w-14 h-14 bg-purple-500 hover:bg-purple-600 text-white rounded-full shadow-lg flex items-center justify-center transition-all z-40"
                style="bottom: 18rem;"
                title="Nueva tarea"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </button>
        </div>
    </div>
    
    <!-- Modal Nueva Tarea -->
    <div id="nuevaTareaModal" class="fixed inset-0 bg-black/80 dark:bg-black/90 backdrop-blur-sm z-[9999] hidden flex items-center justify-center p-4">
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
    
    <!-- Modal Editar Lista -->
    <div id="editarListaModal" class="fixed inset-0 bg-black/80 dark:bg-black/90 backdrop-blur-sm z-[9999] hidden flex items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 max-w-md w-full max-h-[90vh] overflow-hidden shadow-xl">
            <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Editar Lista</h3>
                <button onclick="closeEditarListaModal()" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="editar-lista-form" class="p-4 space-y-4">
                <input type="hidden" name="lista_id" id="editar-lista-id">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nombre de la lista</label>
                    <input 
                        type="text" 
                        name="nombre" 
                        id="editar-lista-nombre"
                        required
                        placeholder="Ej: Trabajo, Personal, etc."
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 focus:outline-none transition-all"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Descripción (opcional)</label>
                    <textarea 
                        name="descripcion" 
                        id="editar-lista-descripcion"
                        rows="3"
                        placeholder="Descripción de la lista..."
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 focus:outline-none transition-all resize-none"
                    ></textarea>
                </div>
                <button 
                    type="submit"
                    class="w-full px-6 py-3 rounded-xl bg-purple-500 hover:bg-purple-600 text-white font-medium transition-all"
                >
                    Guardar Cambios
                </button>
            </form>
        </div>
    </div>
    
    <!-- Modal Editar Tarea -->
    <div id="editarTareaModal" class="fixed inset-0 bg-black/80 dark:bg-black/90 backdrop-blur-sm z-[9999] hidden flex items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 max-w-md w-full max-h-[90vh] overflow-hidden shadow-xl">
            <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Editar Tarea</h3>
                <button onclick="closeEditarTareaModal()" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="editar-tarea-form" class="p-4 space-y-4">
                <input type="hidden" name="tarea_id" id="editar-tarea-id">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Lista</label>
                    <select 
                        name="lista_id" 
                        id="editar-lista-id"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 focus:outline-none transition-all"
                    >
                        <option value="">Sin lista</option>
                        @foreach($listas as $lista)
                            <option value="{{ $lista->id }}">{{ $lista->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Texto de la tarea</label>
                    <textarea 
                        name="texto" 
                        id="editar-texto"
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
                        id="editar-tipo"
                        list="tipos-list-editar"
                        placeholder="Selecciona un tipo existente o escribe uno nuevo"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 focus:outline-none transition-all"
                    >
                    <datalist id="tipos-list-editar">
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
                    Guardar Cambios
                </button>
            </form>
        </div>
    </div>
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const listasData = @json($listas);
        
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
            
            // Si se cambia al tab de listas, asegurar que se muestre la vista de listas
            if (tabName === 'listas') {
                document.getElementById('vista-listas').classList.remove('hidden');
                document.getElementById('vista-tareas-lista').classList.add('hidden');
            }
            
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
        
        function editarTarea(tareaId, texto, listaId, tipo) {
            document.getElementById('editar-tarea-id').value = tareaId;
            document.getElementById('editar-texto').value = texto;
            document.getElementById('editar-lista-id').value = listaId || '';
            document.getElementById('editar-tipo').value = tipo || '';
            document.getElementById('editarTareaModal').classList.remove('hidden');
        }
        
        function closeEditarTareaModal() {
            document.getElementById('editarTareaModal').classList.add('hidden');
        }
        
        async function eliminarTarea(tareaId) {
            if (!confirm('¿Estás seguro de que deseas eliminar esta tarea?')) {
                return;
            }
            
            try {
                const response = await fetch(`/tareas/${tareaId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
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
            if (e.key === 'Escape') {
                closeNuevaTareaModal();
                closeEditarTareaModal();
            }
        });
        
        // Editar Tarea Form
        document.getElementById('editar-tarea-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const tareaId = document.getElementById('editar-tarea-id').value;
            const formData = new FormData(e.target);
            
            // Convert FormData to JSON
            const data = {
                texto: formData.get('texto'),
                lista_id: formData.get('lista_id') || null,
                tipo: formData.get('tipo') || null,
            };
            
            try {
                const response = await fetch(`/tareas/${tareaId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    closeEditarTareaModal();
                    location.reload();
                } else {
                    alert('Error: ' + (result.message || 'Error al actualizar'));
                }
            } catch (error) {
                alert('Error de conexión: ' + error.message);
            }
        });
        
        // Close edit modal on backdrop click
        document.getElementById('editarTareaModal').addEventListener('click', function(e) {
            if (e.target === this) closeEditarTareaModal();
        });
        
        document.getElementById('editarListaModal').addEventListener('click', function(e) {
            if (e.target === this) closeEditarListaModal();
        });
        
        // Editar Lista Form
        document.getElementById('editar-lista-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const listaId = document.getElementById('editar-lista-id').value;
            const nombre = document.getElementById('editar-lista-nombre').value;
            const descripcion = document.getElementById('editar-lista-descripcion').value;
            
            const data = {
                nombre: nombre,
                descripcion: descripcion || null,
            };
            
            try {
                const response = await fetch(`/listas/${listaId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    closeEditarListaModal();
                    location.reload();
                } else {
                    alert('Error: ' + (result.message || 'Error al actualizar'));
                }
            } catch (error) {
                alert('Error de conexión: ' + error.message);
            }
        });
        
        // Filtrar por lista
        function filtrarPorLista() {
            const listaId = document.getElementById('filtro-lista').value;
            const url = new URL(window.location.href);
            const orden = url.searchParams.get('orden') || 'nuevas-primero';
            
            if (listaId) {
                url.searchParams.set('lista', listaId);
            } else {
                url.searchParams.delete('lista');
            }
            
            // Mantener el parámetro de orden
            url.searchParams.set('orden', orden);
            
            window.location.href = url.toString();
        }
        
        // Navegación en listas
        function verTareasLista(listaId) {
            const lista = listasData.find(l => l.id === listaId);
            if (!lista) return;
            
            // Ocultar vista de listas y mostrar vista de tareas
            document.getElementById('vista-listas').classList.add('hidden');
            document.getElementById('vista-tareas-lista').classList.remove('hidden');
            
            // Actualizar información de la lista
            document.getElementById('nombre-lista-actual').textContent = lista.nombre;
            document.getElementById('descripcion-lista-actual').textContent = lista.descripcion || '';
            
            // Cargar tareas
            const container = document.getElementById('tareas-lista-container');
            container.innerHTML = '';
            
            if (lista.tareas && lista.tareas.length > 0) {
                lista.tareas.forEach(tarea => {
                    const tareaDiv = document.createElement('div');
                    tareaDiv.className = 'flex items-start gap-3 p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-all';
                    const textoEscapado = (tarea.texto || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
                    const tipoEscapado = (tarea.tipo || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
                    tareaDiv.innerHTML = `
                        <button onclick="toggleTarea(${tarea.id})" class="mt-1 flex-shrink-0 w-5 h-5 rounded-full border-2 ${tarea.estado === 'completado' ? 'bg-purple-500 border-purple-500' : 'border-slate-300 dark:border-slate-600'} flex items-center justify-center transition-all hover:border-purple-500">
                            ${tarea.estado === 'completado' ? '<svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>' : ''}
                        </button>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-900 dark:text-white ${tarea.estado === 'completado' ? 'line-through opacity-60' : ''}">
                                ${textoEscapado}
                            </p>
                            ${tarea.tipo ? `<span class="text-xs text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-500/10 px-2 py-0.5 rounded-full mt-1 inline-block">${tipoEscapado}</span>` : ''}
                        </div>
                        <button onclick="editarTarea(${tarea.id}, '${textoEscapado}', ${tarea.lista_id || 'null'}, '${tipoEscapado}')" class="p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-all" title="Editar tarea">
                            <svg class="w-3 h-3 text-slate-400 hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                    `;
                    container.appendChild(tareaDiv);
                });
            } else {
                container.innerHTML = '<p class="text-sm text-slate-500 dark:text-slate-400 text-center py-4">No hay tareas en esta lista</p>';
            }
        }
        
        function volverAListas() {
            document.getElementById('vista-listas').classList.remove('hidden');
            document.getElementById('vista-tareas-lista').classList.add('hidden');
        }
        
        // Filter tareas function
        function filterTareas() {
            const searchInput = document.getElementById('searchTareasInput');
            const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
            const tareaItems = document.querySelectorAll('.tarea-item');
            
            tareaItems.forEach(item => {
                const searchText = item.getAttribute('data-search') || '';
                if (searchText.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Hide/show separador de "Completadas" si no hay resultados
            const separador = document.querySelector('.my-4.flex.items-center.gap-2');
            if (separador) {
                const tareasVisibles = Array.from(tareaItems).filter(item => 
                    item.style.display !== 'none' && 
                    item.closest('.tarea-item')?.getAttribute('data-search')?.includes(searchTerm)
                );
                separador.style.display = tareasVisibles.length > 0 ? 'flex' : 'none';
            }
        }
        
        function editarLista(listaId, nombre, descripcion) {
            document.getElementById('editar-lista-id').value = listaId;
            document.getElementById('editar-lista-nombre').value = nombre;
            document.getElementById('editar-lista-descripcion').value = descripcion || '';
            document.getElementById('editarListaModal').classList.remove('hidden');
        }
        
        function closeEditarListaModal() {
            document.getElementById('editarListaModal').classList.add('hidden');
        }
        
        async function eliminarLista(listaId) {
            if (!confirm('¿Estás seguro de que deseas eliminar esta lista? Esto eliminará todas las tareas asociadas.')) {
                return;
            }
            
            try {
                const response = await fetch(`/listas/${listaId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
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
    </script>
    @include('partials.walee-support-button')
</body>
</html>

