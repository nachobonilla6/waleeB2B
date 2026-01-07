<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Notas</title>
    <meta name="description" content="Gestión de Notas">
    <meta name="theme-color" content="#D59F3B">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    @include('partials.walee-dark-mode-init')
    @include('partials.walee-violet-light-mode')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        
        html:not(.dark) body {
            background-color: rgb(245, 243, 255) !important;
        }
        
        html:not(.dark) .min-h-screen {
            background-color: rgb(245, 243, 255) !important;
        }
        
        html:not(.dark) .bg-white {
            background-color: rgb(245, 243, 255) !important;
        }
        
        html:not(.dark) .bg-slate-50 {
            background-color: rgb(245, 243, 255) !important;
        }
        
        html.dark,
        html.dark body,
        html.dark body.bg-slate-50,
        .dark body,
        .dark body.bg-slate-50,
        html.dark #html-root,
        .dark #html-root {
            background-color: rgb(15, 23, 42) !important;
        }
        
        .dark .bg-white,
        .dark .bg-white\/50,
        html.dark .bg-white,
        html.dark .bg-white\/50 {
            background-color: rgb(30, 41, 59) !important;
        }
        
        .dark .bg-slate-50,
        .dark body.bg-slate-50,
        html.dark .bg-slate-50,
        html.dark body.bg-slate-50 {
            background-color: rgb(15, 23, 42) !important;
        }
        
        .note-card {
            min-height: 200px;
            transition: all 0.3s ease;
        }
        
        .note-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .note-content {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">
    @php
        $notas = \App\Models\Note::where('user_id', auth()->id())
            ->whereNull('cliente_id')
            ->whereNull('client_id')
            ->orderBy('pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    @endphp
    
    <div class="min-h-screen relative overflow-hidden">
        <div class="relative max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            @include('partials.walee-navbar')
            
            <div class="mt-6 sm:mt-8 animate-fade-in-up">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">Notas</h2>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Gestiona tus notas personales</p>
                    </div>
                    <button 
                        onclick="openCreateNoteModal()"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-walee-500 hover:bg-walee-600 text-white rounded-lg font-medium transition-colors shadow-lg"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span class="hidden sm:inline">Nueva Nota</span>
                    </button>
                </div>
                
                @if($notas->isEmpty())
                    <div class="text-center py-16">
                        <svg class="w-16 h-16 mx-auto text-slate-400 dark:text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-slate-600 dark:text-slate-400 text-lg mb-2">No tienes notas aún</p>
                        <p class="text-slate-500 dark:text-slate-500 text-sm">Crea tu primera nota para comenzar</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                        @foreach($notas as $nota)
                            <div 
                                class="note-card bg-white dark:bg-slate-800 rounded-xl p-4 sm:p-5 border border-slate-200 dark:border-slate-700 shadow-md relative {{ $nota->pinned ? 'ring-2 ring-walee-400' : '' }}"
                                data-note-id="{{ $nota->id }}"
                            >
                                @if($nota->pinned)
                                    <div class="absolute top-3 right-3">
                                        <svg class="w-5 h-5 text-walee-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M5 5a2 2 0 012-2h6a2 2 0 012 2v6l-4 4v-6H5V5z"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1 min-w-0">
                                        @if($nota->type)
                                            <span class="inline-block px-2 py-1 text-xs font-medium rounded-md 
                                                @if($nota->type === 'note') bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300
                                                @elseif($nota->type === 'call') bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300
                                                @elseif($nota->type === 'meeting') bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300
                                                @elseif($nota->type === 'email') bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300
                                                @else bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300
                                                @endif">
                                                {{ ucfirst($nota->type) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-1 ml-2">
                                        <button 
                                            onclick="togglePin({{ $nota->id }}, {{ $nota->pinned ? 'false' : 'true' }})"
                                            class="p-1.5 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                                            title="{{ $nota->pinned ? 'Desfijar' : 'Fijar' }}"
                                        >
                                            <svg class="w-4 h-4 text-slate-500 dark:text-slate-400 {{ $nota->pinned ? 'text-walee-500' : '' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M5 5a2 2 0 012-2h6a2 2 0 012 2v6l-4 4v-6H5V5z"/>
                                            </svg>
                                        </button>
                                        <button 
                                            onclick="openEditNoteModal({{ $nota->id }})"
                                            class="p-1.5 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                                            title="Editar"
                                        >
                                            <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button 
                                            onclick="deleteNote({{ $nota->id }})"
                                            class="p-1.5 rounded-md hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors"
                                            title="Eliminar"
                                        >
                                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                
                                <div 
                                    class="note-content text-slate-700 dark:text-slate-300 mb-3 text-sm leading-relaxed cursor-pointer hover:text-walee-600 dark:hover:text-walee-400 transition-colors"
                                    onclick="viewNote({{ $nota->id }})"
                                    title="Haz clic para ver el contenido completo"
                                >
                                    {{ Str::limit($nota->content, 200) }}
                                    @if(strlen($nota->content) > 200)
                                        <span class="text-walee-500 text-xs">... (ver más)</span>
                                    @endif
                                </div>
                                
                                <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 pt-3 border-t border-slate-200 dark:border-slate-700">
                                    <span>{{ $nota->created_at->diffForHumans() }}</span>
                                    @if($nota->fecha)
                                        <span>{{ \Carbon\Carbon::parse($nota->fecha)->format('d/m/Y') }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
            @include('partials.walee-world-map-clocks')
        </div>
    </div>
    
    @include('partials.walee-support-button')
    
    <!-- Modal para crear/editar nota -->
    <div id="noteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                <h3 id="modalTitle" class="text-xl font-bold text-slate-900 dark:text-white">Nueva Nota</h3>
            </div>
            <form id="noteForm" class="p-6">
                @csrf
                <input type="hidden" id="noteId" name="note_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tipo</label>
                    <select 
                        id="noteType" 
                        name="type" 
                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-walee-500 focus:border-transparent"
                    >
                        <option value="note">Nota</option>
                        <option value="call">Llamada</option>
                        <option value="meeting">Reunión</option>
                        <option value="email">Email</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Contenido</label>
                    <textarea 
                        id="noteContent" 
                        name="content" 
                        rows="8" 
                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-walee-500 focus:border-transparent resize-none"
                        placeholder="Escribe tu nota aquí..."
                        required
                    ></textarea>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Fecha</label>
                    <input 
                        type="date" 
                        id="noteFecha" 
                        name="fecha" 
                        value="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-walee-500 focus:border-transparent"
                    >
                </div>
                
                <div class="mb-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input 
                            type="checkbox" 
                            id="notePinned" 
                            name="pinned" 
                            class="w-4 h-4 text-walee-500 border-slate-300 dark:border-slate-600 rounded focus:ring-walee-500"
                        >
                        <span class="text-sm text-slate-700 dark:text-slate-300">Fijar nota</span>
                    </label>
                </div>
                
                <div class="flex items-center justify-end gap-3">
                    <button 
                        type="button" 
                        onclick="closeNoteModal()"
                        class="px-4 py-2 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors"
                    >
                        Cancelar
                    </button>
                    <button 
                        type="submit"
                        class="px-4 py-2 bg-walee-500 hover:bg-walee-600 text-white rounded-lg font-medium transition-colors"
                    >
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        function openCreateNoteModal() {
            document.getElementById('modalTitle').textContent = 'Nueva Nota';
            document.getElementById('noteForm').reset();
            document.getElementById('noteId').value = '';
            document.getElementById('noteFecha').value = '{{ date('Y-m-d') }}';
            document.getElementById('noteModal').classList.remove('hidden');
        }
        
        function openEditNoteModal(noteId) {
            fetch(`/notas/${noteId}`, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Error al cargar la nota');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.nota) {
                    document.getElementById('modalTitle').textContent = 'Editar Nota';
                    document.getElementById('noteId').value = data.nota.id;
                    document.getElementById('noteContent').value = data.nota.content || '';
                    document.getElementById('noteType').value = data.nota.type || 'note';
                    document.getElementById('noteFecha').value = data.nota.fecha || '{{ date('Y-m-d') }}';
                    document.getElementById('notePinned').checked = data.nota.pinned || false;
                    document.getElementById('noteModal').classList.remove('hidden');
                } else {
                    Swal.fire('Error', data.message || 'No se pudo cargar la nota', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', error.message || 'No se pudo cargar la nota', 'error');
            });
        }
        
        function closeNoteModal() {
            document.getElementById('noteModal').classList.add('hidden');
        }
        
        function viewNote(noteId) {
            fetch(`/notas/${noteId}`, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Error al cargar la nota');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.nota) {
                    // Mostrar el contenido completo en un modal de solo lectura
                    Swal.fire({
                        title: 'Nota',
                        html: `
                            <div class="text-left">
                                <div class="mb-3">
                                    <span class="inline-block px-2 py-1 text-xs font-medium rounded-md 
                                        ${data.nota.type === 'note' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300' : 
                                          data.nota.type === 'call' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300' :
                                          data.nota.type === 'meeting' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' :
                                          data.nota.type === 'email' ? 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300' :
                                          'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300'}">
                                        ${data.nota.type ? data.nota.type.charAt(0).toUpperCase() + data.nota.type.slice(1) : 'Nota'}
                                    </span>
                                </div>
                                <div class="text-slate-700 dark:text-slate-300 whitespace-pre-wrap mb-4">${data.nota.content || ''}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">
                                    <p>Creada: ${new Date(data.nota.created_at).toLocaleString('es-ES')}</p>
                                    ${data.nota.fecha ? `<p>Fecha: ${data.nota.fecha}</p>` : ''}
                                </div>
                            </div>
                        `,
                        width: '600px',
                        showCancelButton: true,
                        confirmButtonText: 'Editar',
                        cancelButtonText: 'Cerrar',
                        confirmButtonColor: '#D59F3B',
                        cancelButtonColor: '#6b7280'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            openEditNoteModal(noteId);
                        }
                    });
                } else {
                    Swal.fire('Error', data.message || 'No se pudo cargar la nota', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', error.message || 'No se pudo cargar la nota', 'error');
            });
        }
        
        function togglePin(noteId, pinned) {
            // Primero obtener la nota completa desde la base de datos
            fetch(`/notas/${noteId}`, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.nota) {
                    // Actualizar solo el pinned
                    return fetch(`/notas/${noteId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            pinned: pinned,
                            content: data.nota.content,
                            type: data.nota.type || 'note',
                            fecha: data.nota.fecha || '{{ date('Y-m-d') }}'
                        })
                    });
                } else {
                    throw new Error(data.message || 'No se pudo cargar la nota');
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    Swal.fire('Error', data.message || 'No se pudo actualizar la nota', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', error.message || 'No se pudo actualizar la nota', 'error');
            });
        }
        
        function deleteNote(noteId) {
            Swal.fire({
                title: '¿Eliminar nota?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/notas/${noteId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Eliminada', 'La nota ha sido eliminada', 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', data.message || 'No se pudo eliminar la nota', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error', 'No se pudo eliminar la nota', 'error');
                    });
                }
            });
        }
        
        document.getElementById('noteForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const noteId = document.getElementById('noteId').value;
            const url = noteId ? `/notas/${noteId}` : '/notas';
            const method = noteId ? 'PUT' : 'POST';
            
            const formData = {
                content: document.getElementById('noteContent').value,
                type: document.getElementById('noteType').value,
                fecha: document.getElementById('noteFecha').value,
                pinned: document.getElementById('notePinned').checked
            };
            
            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Éxito', noteId ? 'Nota actualizada' : 'Nota creada', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message || 'No se pudo guardar la nota', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'No se pudo guardar la nota', 'error');
            });
        });
        
        // Cerrar modal al hacer clic fuera
        document.getElementById('noteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeNoteModal();
            }
        });
    </script>
</body>
</html>

