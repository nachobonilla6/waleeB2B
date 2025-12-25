<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $tarea->texto }} - Detalle de Tarea</title>
    @include('partials.walee-dark-mode-init')
    @include('partials.walee-violet-light-mode')
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        walee: {
                            50: '#fef3e2',
                            100: '#fde4b8',
                            200: '#fbc98a',
                            300: '#f9a95c',
                            400: '#f78a3a',
                            500: '#d59f3b',
                            600: '#b8852e',
                            700: '#9a6b26',
                            800: '#7c5220',
                            900: '#5e3a1a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.4s ease-out forwards;
        }
        .walee-gradient {
            background: linear-gradient(135deg, #D59F3B 0%, #C78F2E 100%);
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(213, 159, 59, 0.4); }
            50% { box-shadow: 0 0 30px rgba(213, 159, 59, 0.6); }
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">
    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-violet-400/20 dark:bg-violet-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-violet-400/10 dark:bg-violet-400/5 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-4 py-6 sm:px-6 lg:px-8">
            @php $pageTitle = 'Detalle de Tarea'; @endphp
            @include('partials.walee-navbar')
            
            <!-- Header -->
            <div class="mb-6 animate-fade-in-up">
                <div class="flex items-center justify-between mb-4">
                    <a href="{{ route('walee.calendario.dia', ['ano' => $tarea->fecha_hora->year, 'mes' => $tarea->fecha_hora->month, 'dia' => $tarea->fecha_hora->day]) }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition-all border border-slate-300 dark:border-slate-700 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Volver</span>
                    </a>
                </div>
            </div>
            
            <!-- Detalle de Tarea -->
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-xl overflow-hidden shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="p-6 sm:p-8">
                    <!-- Título y Estado -->
                    <div class="mb-6">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white flex-1">
                                {{ $tarea->texto }}
                            </h1>
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium {{ $tarea->estado === 'completado' ? 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300' }}">
                                {{ $tarea->estado === 'completado' ? 'Completado' : 'Pendiente' }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Información Principal -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Fecha y Hora -->
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wide">Fecha y Hora</h3>
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <div>
                                        <p class="text-slate-900 dark:text-white font-medium">
                                            {{ $tarea->fecha_hora->format('l, d') }} de {{ $meses[$tarea->fecha_hora->month] }} de {{ $tarea->fecha_hora->year }}
                                        </p>
                                        <p class="text-slate-600 dark:text-slate-400 text-sm">
                                            {{ $tarea->fecha_hora->format('H:i') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            @if($tarea->lista)
                            <div>
                                <h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wide">Lista</h3>
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <p class="text-slate-900 dark:text-white font-medium">{{ $tarea->lista->nombre }}</p>
                                </div>
                            </div>
                            @endif
                            
                            @if($tarea->tipo)
                            <div>
                                <h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wide">Tipo</h3>
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    <p class="text-slate-900 dark:text-white">{{ $tarea->tipo }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Información Adicional -->
                        <div class="space-y-4">
                            @if($tarea->recurrencia && $tarea->recurrencia !== 'none')
                            <div>
                                <h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wide">Recurrencia</h3>
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    <p class="text-slate-900 dark:text-white">
                                        {{ ucfirst($tarea->recurrencia) }}
                                        @if($tarea->recurrencia_fin)
                                            hasta {{ $tarea->recurrencia_fin->format('d/m/Y') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @endif
                            
                            <div>
                                <h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wide">Fecha de Creación</h3>
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-slate-900 dark:text-white">
                                        {{ $tarea->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Acciones -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <button 
                            onclick="editTarea()"
                            class="flex-1 px-6 py-3 rounded-xl bg-violet-500 hover:bg-violet-600 text-white font-medium transition-all flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Editar
                        </button>
                        <button 
                            onclick="deleteTareaConfirm()"
                            class="flex-1 px-6 py-3 rounded-xl bg-red-500 hover:bg-red-600 text-white font-medium transition-all flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Eliminar
                        </button>
                        <a href="{{ route('walee.calendario.dia', ['ano' => $tarea->fecha_hora->year, 'mes' => $tarea->fecha_hora->month, 'dia' => $tarea->fecha_hora->day]) }}" class="flex-1 px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition-all border border-slate-300 dark:border-slate-700 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Editar Tarea -->
    <div id="tareaModal" class="fixed inset-0 bg-black/60 dark:bg-black/80 backdrop-blur-sm z-50 hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="bg-white dark:bg-slate-900 rounded-t-2xl sm:rounded-2xl border-t sm:border border-slate-200 dark:border-slate-700 w-full sm:max-w-md max-h-[90vh] overflow-hidden shadow-xl">
            <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Editar Tarea</h3>
                <button onclick="closeTareaModal()" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="tarea-form" class="p-4 space-y-4 overflow-y-auto max-h-[70vh]">
                <input type="hidden" name="tarea_id" id="tarea_id" value="{{ $tarea->id }}">
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Texto de la Tarea</label>
                    <input type="text" name="texto" id="tarea_texto" required value="{{ $tarea->texto }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Lista</label>
                    <select name="lista_id" id="tarea_lista_id" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all">
                        <option value="">Sin lista</option>
                        @foreach($listas as $lista)
                            <option value="{{ $lista->id }}" {{ $tarea->lista_id == $lista->id ? 'selected' : '' }}>{{ $lista->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Fecha y Hora</label>
                    <input type="datetime-local" name="fecha_hora" id="tarea_fecha_hora" required value="{{ $tarea->fecha_hora->format('Y-m-d\TH:i') }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tipo</label>
                    <input type="text" name="tipo" id="tarea_tipo" list="tipos-list" value="{{ $tarea->tipo }}" placeholder="Tipo de tarea (opcional)" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all">
                    <datalist id="tipos-list">
                        @foreach($tiposExistentes as $tipo)
                            <option value="{{ $tipo }}">
                        @endforeach
                    </datalist>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Recurrencia</label>
                    <select name="recurrencia" id="tarea_recurrencia" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all" onchange="toggleTareaRecurrenciaFin()">
                        <option value="none" {{ ($tarea->recurrencia ?? 'none') === 'none' ? 'selected' : '' }}>Sin recurrencia</option>
                        <option value="diaria" {{ ($tarea->recurrencia ?? 'none') === 'diaria' ? 'selected' : '' }}>Diaria</option>
                        <option value="semanal" {{ ($tarea->recurrencia ?? 'none') === 'semanal' ? 'selected' : '' }}>Semanal</option>
                        <option value="mensual" {{ ($tarea->recurrencia ?? 'none') === 'mensual' ? 'selected' : '' }}>Mensual</option>
                    </select>
                </div>
                
                <div id="tarea_recurrencia_fin_container" class="{{ ($tarea->recurrencia ?? 'none') !== 'none' ? '' : 'hidden' }}">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Fecha de Fin de Recurrencia (opcional)</label>
                    <input type="datetime-local" name="recurrencia_fin" id="tarea_recurrencia_fin" value="{{ $tarea->recurrencia_fin ? $tarea->recurrencia_fin->format('Y-m-d\TH:i') : '' }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all">
                </div>
                
                <div class="flex gap-2 pt-2">
                    <button type="submit" class="flex-1 px-6 py-3 rounded-xl bg-violet-500 hover:bg-violet-600 text-white font-medium transition-all">Guardar</button>
                    <button type="button" onclick="deleteTareaConfirm()" class="px-6 py-3 rounded-xl bg-red-500 hover:bg-red-600 text-white font-medium transition-all">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const tareaId = {{ $tarea->id }};
        
        function toggleTareaRecurrenciaFin() {
            const recurrencia = document.getElementById('tarea_recurrencia').value;
            const container = document.getElementById('tarea_recurrencia_fin_container');
            if (recurrencia !== 'none') {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
            }
        }
        
        function editTarea() {
            document.getElementById('tareaModal').classList.remove('hidden');
        }
        
        function closeTareaModal() {
            document.getElementById('tareaModal').classList.add('hidden');
        }
        
        function deleteTareaConfirm() {
            if (!confirm('¿Estás seguro de eliminar esta tarea?')) return;
            deleteTarea();
        }
        
        async function deleteTarea() {
            try {
                const response = await fetch(`/walee-tareas/${tareaId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    }
                });
                
                const data = await response.json();
                if (data.success) {
                    window.location.href = '{{ route("walee.calendario.dia", ["ano" => $tarea->fecha_hora->year, "mes" => $tarea->fecha_hora->month, "dia" => $tarea->fecha_hora->day]) }}';
                } else {
                    alert('Error al eliminar la tarea');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al eliminar la tarea');
            }
        }
        
        // Form handler
        document.getElementById('tarea-form')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = {
                texto: document.getElementById('tarea_texto').value,
                lista_id: document.getElementById('tarea_lista_id').value || null,
                fecha_hora: document.getElementById('tarea_fecha_hora').value,
                tipo: document.getElementById('tarea_tipo').value || null,
                recurrencia: document.getElementById('tarea_recurrencia').value,
                recurrencia_fin: document.getElementById('tarea_recurrencia_fin').value || null
            };
            
            try {
                const response = await fetch(`/walee-tareas/${tareaId}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });
                
                const data = await response.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error al guardar la tarea: ' + (data.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al guardar la tarea');
            }
        });
        
        // Cerrar modal al hacer clic fuera
        document.getElementById('tareaModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeTareaModal();
        });
    </script>
    
    @include('partials.walee-support-button')
</body>
</html>

