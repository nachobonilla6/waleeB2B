<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $cita->titulo }} - Detalle de Cita</title>
    @include('partials.walee-dark-mode-init')
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
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-400/20 dark:bg-emerald-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-emerald-400/10 dark:bg-emerald-400/5 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
            @php $pageTitle = 'Detalle de Cita'; @endphp
            @include('partials.walee-navbar')
            
            <!-- Header -->
            <div class="mb-6 animate-fade-in-up">
                <div class="flex items-center justify-between mb-4">
                    <a href="{{ route('walee.calendario.dia', ['ano' => $cita->fecha_inicio->year, 'mes' => $cita->fecha_inicio->month, 'dia' => $cita->fecha_inicio->day]) }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition-all border border-slate-300 dark:border-slate-700 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Volver</span>
                    </a>
                </div>
            </div>
            
            <!-- Detalle de Cita -->
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-xl overflow-hidden shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="p-6 sm:p-8">
                    <!-- Título y Estado -->
                    <div class="mb-6">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white flex-1">
                                {{ $cita->titulo }}
                            </h1>
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium {{ $cita->estado === 'completada' ? 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400' : ($cita->estado === 'cancelada' ? 'bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400' : 'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400') }}">
                                {{ ucfirst($cita->estado) }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Información Principal -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Fecha y Hora -->
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wide">Fecha y Hora</h3>
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <div>
                                            <p class="text-slate-900 dark:text-white font-medium">
                                                {{ $cita->fecha_inicio->format('l, d') }} de {{ $meses[$cita->fecha_inicio->month] }} de {{ $cita->fecha_inicio->year }}
                                            </p>
                                            <p class="text-slate-600 dark:text-slate-400 text-sm">
                                                {{ $cita->fecha_inicio->format('H:i') }}
                                                @if($cita->fecha_fin)
                                                    - {{ $cita->fecha_fin->format('H:i') }}
                                                    ({{ $cita->fecha_inicio->diffInMinutes($cita->fecha_fin) }} minutos)
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @if($cita->cliente)
                            <div>
                                <h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wide">Cliente</h3>
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <p class="text-slate-900 dark:text-white font-medium">{{ $cita->cliente->nombre_empresa }}</p>
                                </div>
                            </div>
                            @endif
                            
                            @if($cita->ubicacion)
                            <div>
                                <h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wide">Ubicación</h3>
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <p class="text-slate-900 dark:text-white">{{ $cita->ubicacion }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Información Adicional -->
                        <div class="space-y-4">
                            @if($cita->recurrencia && $cita->recurrencia !== 'none')
                            <div>
                                <h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wide">Recurrencia</h3>
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    <p class="text-slate-900 dark:text-white">
                                        {{ ucfirst($cita->recurrencia) }}
                                        @if($cita->recurrencia_fin)
                                            hasta {{ $cita->recurrencia_fin->format('d/m/Y') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @endif
                            
                            <div>
                                <h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wide">Color</h3>
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg border-2 border-slate-300 dark:border-slate-700" style="background-color: {{ $cita->color ?? '#10b981' }};"></div>
                                    <p class="text-slate-900 dark:text-white font-mono text-sm">{{ $cita->color ?? '#10b981' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($cita->descripcion)
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wide">Descripción</h3>
                        <div class="p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg border border-slate-200 dark:border-slate-700">
                            <p class="text-slate-700 dark:text-slate-300 whitespace-pre-wrap">{{ $cita->descripcion }}</p>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Acciones -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <button 
                            onclick="editCita()"
                            class="flex-1 px-6 py-3 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white font-medium transition-all flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Editar
                        </button>
                        <button 
                            onclick="deleteCitaConfirm()"
                            class="flex-1 px-6 py-3 rounded-xl bg-red-500 hover:bg-red-600 text-white font-medium transition-all flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Eliminar
                        </button>
                        <a href="{{ route('walee.calendario.dia', ['ano' => $cita->fecha_inicio->year, 'mes' => $cita->fecha_inicio->month, 'dia' => $cita->fecha_inicio->day]) }}" class="flex-1 px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition-all border border-slate-300 dark:border-slate-700 flex items-center justify-center gap-2">
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
    
    <!-- Modal Editar Cita -->
    <div id="citaModal" class="fixed inset-0 bg-black/60 dark:bg-black/80 backdrop-blur-sm z-50 hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="bg-white dark:bg-slate-900 rounded-t-2xl sm:rounded-2xl border-t sm:border border-slate-200 dark:border-slate-700 w-full sm:max-w-md max-h-[90vh] overflow-hidden shadow-xl">
            <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Editar Cita</h3>
                <button onclick="closeCitaModal()" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="cita-form" class="p-4 space-y-4 overflow-y-auto max-h-[70vh]">
                <input type="hidden" name="cita_id" id="cita_id" value="{{ $cita->id }}">
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Título</label>
                    <input type="text" name="titulo" id="titulo" required value="{{ $cita->titulo }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 focus:outline-none transition-all">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Cliente</label>
                    <select name="cliente_id" id="cliente_id" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 focus:outline-none transition-all">
                        <option value="">Sin cliente</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ $cita->cliente_id == $cliente->id ? 'selected' : '' }}>{{ $cliente->nombre_empresa }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Fecha y Hora de Inicio</label>
                    <input type="datetime-local" name="fecha_inicio" id="fecha_inicio" required value="{{ $cita->fecha_inicio->format('Y-m-d\TH:i') }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 focus:outline-none transition-all">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Fecha y Hora de Fin (opcional)</label>
                    <input type="datetime-local" name="fecha_fin" id="fecha_fin" value="{{ $cita->fecha_fin ? $cita->fecha_fin->format('Y-m-d\TH:i') : '' }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 focus:outline-none transition-all">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Ubicación (opcional)</label>
                    <input type="text" name="ubicacion" id="ubicacion" value="{{ $cita->ubicacion }}" placeholder="Ubicación de la cita" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 focus:outline-none transition-all">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Descripción (opcional)</label>
                    <textarea name="descripcion" id="descripcion" rows="3" placeholder="Descripción de la cita..." class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 focus:outline-none transition-all resize-none">{{ $cita->descripcion }}</textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Color</label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="color" id="color" value="{{ $cita->color ?? '#10b981' }}" class="w-16 h-12 rounded-lg border border-slate-300 dark:border-slate-700 cursor-pointer">
                        <input type="text" id="color_text" value="{{ $cita->color ?? '#10b981' }}" placeholder="#10b981" class="flex-1 px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 focus:outline-none transition-all" onchange="document.getElementById('color').value = this.value">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Estado</label>
                    <select name="estado" id="estado" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 focus:outline-none transition-all">
                        <option value="programada" {{ $cita->estado === 'programada' ? 'selected' : '' }}>Programada</option>
                        <option value="completada" {{ $cita->estado === 'completada' ? 'selected' : '' }}>Completada</option>
                        <option value="cancelada" {{ $cita->estado === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Recurrencia</label>
                    <select name="recurrencia" id="recurrencia" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 focus:outline-none transition-all" onchange="toggleRecurrenciaFin()">
                        <option value="none" {{ ($cita->recurrencia ?? 'none') === 'none' ? 'selected' : '' }}>Sin recurrencia</option>
                        <option value="semanal" {{ ($cita->recurrencia ?? 'none') === 'semanal' ? 'selected' : '' }}>Semanal</option>
                        <option value="mensual" {{ ($cita->recurrencia ?? 'none') === 'mensual' ? 'selected' : '' }}>Mensual</option>
                        <option value="anual" {{ ($cita->recurrencia ?? 'none') === 'anual' ? 'selected' : '' }}>Anual</option>
                    </select>
                </div>
                
                <div id="recurrencia_fin_container" class="{{ ($cita->recurrencia ?? 'none') !== 'none' ? '' : 'hidden' }}">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Fecha de Fin de Recurrencia (opcional)</label>
                    <input type="datetime-local" name="recurrencia_fin" id="recurrencia_fin" value="{{ $cita->recurrencia_fin ? $cita->recurrencia_fin->format('Y-m-d\TH:i') : '' }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 focus:outline-none transition-all">
                </div>
                
                <div class="flex gap-2 pt-2">
                    <button type="submit" class="flex-1 px-6 py-3 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white font-medium transition-all">Guardar</button>
                    <button type="button" onclick="deleteCitaConfirm()" class="px-6 py-3 rounded-xl bg-red-500 hover:bg-red-600 text-white font-medium transition-all">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const citaId = {{ $cita->id }};
        
        // Sincronizar color picker
        document.getElementById('color')?.addEventListener('input', function(e) {
            document.getElementById('color_text').value = e.target.value;
        });
        document.getElementById('color_text')?.addEventListener('input', function(e) {
            if (/^#[0-9A-F]{6}$/i.test(e.target.value)) {
                document.getElementById('color').value = e.target.value;
            }
        });
        
        function toggleRecurrenciaFin() {
            const recurrencia = document.getElementById('recurrencia').value;
            const container = document.getElementById('recurrencia_fin_container');
            if (recurrencia !== 'none') {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
            }
        }
        
        function editCita() {
            document.getElementById('citaModal').classList.remove('hidden');
        }
        
        function closeCitaModal() {
            document.getElementById('citaModal').classList.add('hidden');
        }
        
        function deleteCitaConfirm() {
            if (!confirm('¿Estás seguro de eliminar esta cita?')) return;
            deleteCita();
        }
        
        async function deleteCita() {
            try {
                const response = await fetch(`/citas/${citaId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    }
                });
                
                const data = await response.json();
                if (data.success) {
                    window.location.href = '{{ route("walee.calendario.dia", ["ano" => $cita->fecha_inicio->year, "mes" => $cita->fecha_inicio->month, "dia" => $cita->fecha_inicio->day]) }}';
                } else {
                    alert('Error al eliminar la cita');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al eliminar la cita');
            }
        }
        
        // Form handler
        document.getElementById('cita-form')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = {
                titulo: document.getElementById('titulo').value,
                cliente_id: document.getElementById('cliente_id').value || null,
                fecha_inicio: document.getElementById('fecha_inicio').value,
                fecha_fin: document.getElementById('fecha_fin').value || null,
                ubicacion: document.getElementById('ubicacion').value || null,
                descripcion: document.getElementById('descripcion').value || null,
                color: document.getElementById('color').value,
                estado: document.getElementById('estado').value,
                recurrencia: document.getElementById('recurrencia').value,
                recurrencia_fin: document.getElementById('recurrencia_fin').value || null
            };
            
            try {
                const response = await fetch(`/citas/${citaId}`, {
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
                    alert('Error al guardar la cita: ' + (data.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al guardar la cita');
            }
        });
        
        // Cerrar modal al hacer clic fuera
        document.getElementById('citaModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeCitaModal();
        });
    </script>
    
    @include('partials.walee-support-button')
</body>
</html>

