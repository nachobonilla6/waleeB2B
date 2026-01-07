<!-- Walee Navbar -->
<header class="flex items-center justify-between mb-6 sm:mb-8 animate-fade-in-up relative" style="z-index: 9999;">
    <div class="flex items-center gap-2 sm:gap-4 flex-1 min-w-0">
        <a href="{{ route('walee.dashboard') }}" class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl walee-gradient flex items-center justify-center shadow-lg flex-shrink-0" style="animation: pulse-glow 3s infinite;">
            <img src="https://i.postimg.cc/RVw3wk3Y/wa-(Edited).jpg" alt="Walee" class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl object-cover">
        </a>
        <div class="min-w-0 flex-1">
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold bg-gradient-to-r from-walee-300 via-walee-400 to-walee-500 bg-clip-text text-transparent truncate">
                Walee
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 truncate">
                @php
                    $date = now()->format('d M, Y');
                @endphp
                {{ $date }}
            </p>
        </div>
    </div>
    
    <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0 relative">
        @include('partials.walee-dark-mode-toggle')
        
        <!-- Menu Toggle Button (Mobile & Desktop) -->
        <button 
            id="mobileMenuToggle"
            onclick="toggleMobileMenu()"
            class="w-10 h-10 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 flex flex-col items-center justify-center gap-1.5 transition-colors"
            aria-label="Toggle menu"
            aria-expanded="false"
            aria-controls="mobileMenu"
        >
            <span class="block w-5 h-0.5 bg-slate-700 dark:bg-slate-300 rounded transition-all duration-300" id="menuLine1"></span>
            <span class="block w-5 h-0.5 bg-slate-700 dark:bg-slate-300 rounded transition-all duration-300" id="menuLine2"></span>
            <span class="block w-5 h-0.5 bg-slate-700 dark:bg-slate-300 rounded transition-all duration-300" id="menuLine3"></span>
        </button>
        
        <!-- Menu Dropdown (Mobile & Desktop) -->
        <div 
            id="mobileMenu" 
            class="absolute top-full right-0 mt-2 w-64 sm:w-72 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg shadow-2xl overflow-hidden hidden z-50"
            style="display: none; position: absolute;"
        >
        <div class="py-2">
            <a 
                href="{{ route('walee.dashboard') }}" 
                onclick="closeMobileMenu()"
                class="flex items-center gap-3 px-4 py-3 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-slate-200 to-slate-300 dark:from-slate-700 dark:to-slate-800 hover:from-slate-300 hover:to-slate-400 dark:hover:from-slate-600 dark:hover:to-slate-700"
            >
                <svg class="w-5 h-5 text-slate-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span class="font-medium text-slate-900 dark:text-white">Volver</span>
            </a>
            
            <a 
                href="{{ route('walee') }}" 
                onclick="closeMobileMenu()"
                class="flex items-center gap-3 px-4 py-3 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors border-b border-slate-200 dark:border-slate-700 last:border-b-0"
            >
                <svg class="w-5 h-5 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <span class="font-medium">Chat</span>
            </a>
            
            <a 
                href="{{ route('walee.tickets.dashboard') }}" 
                onclick="closeMobileMenu()"
                class="flex items-center gap-3 px-4 py-3 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors border-b border-slate-200 dark:border-slate-700 last:border-b-0"
            >
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="font-medium">Tickets</span>
            </a>
            
            <div class="border-b border-slate-200 dark:border-slate-700">
                <a 
                    href="{{ route('walee.tareas') }}" 
                    onclick="closeMobileMenu()"
                    class="flex items-center gap-3 px-4 py-3 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                >
                    <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <span class="font-medium flex-1">Tasks</span>
                </a>
                
                <!-- Formulario para agregar task rápida -->
                <div class="px-4 py-2 border-b border-slate-200 dark:border-slate-700">
                    <form id="quickTaskForm" onsubmit="agregarQuickTask(event)" class="flex gap-2">
                        <input 
                            type="text" 
                            id="quickTaskInput"
                            placeholder="Agregar task rápida..." 
                            class="flex-1 px-2 py-1.5 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-md focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent text-slate-900 dark:text-white placeholder-slate-400"
                            required
                        />
                        <button 
                            type="submit"
                            class="px-3 py-1.5 bg-violet-600 hover:bg-violet-700 text-white rounded-md transition-colors text-sm font-medium flex items-center justify-center"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>
                    </form>
                </div>
                
                @php
                    $userTasks = \App\Models\Tarea::whereNull('lista_id')
                        ->where('estado', 'pending')
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();
                @endphp
                
                @if($userTasks->count() > 0)
                    <button 
                        onclick="toggleTasksMenu()"
                        class="w-full flex items-center justify-between px-4 py-2 text-xs text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                    >
                        <span class="font-semibold uppercase tracking-wider">Tasks Recientes</span>
                        <svg id="tasksMenuIcon" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <div id="tasksMenuContent" class="tasks-menu-content hidden max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
                        @foreach($userTasks as $task)
                            <div class="task-item flex items-start gap-2 px-4 py-2.5 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors group">
                                <input 
                                    type="checkbox" 
                                    class="mt-1 w-4 h-4 text-violet-600 border-slate-300 rounded focus:ring-violet-500 task-checkbox"
                                    data-task-id="{{ $task->id }}"
                                    onchange="toggleTaskEstado(event, {{ $task->id }})"
                                    {{ $task->estado === 'completado' ? 'checked' : '' }}
                                />
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-slate-600 dark:text-slate-400 break-words {{ $task->estado === 'completado' ? 'line-through opacity-60' : '' }}">
                                        {{ $task->texto }}
                                    </p>
                                    <p class="text-xs text-slate-500 dark:text-slate-500 mt-1">
                                        {{ $task->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <button 
                                    onclick="eliminarQuickTask(event, {{ $task->id }})"
                                    class="flex-shrink-0 p-1.5 rounded-md hover:bg-red-100 dark:hover:bg-red-900/20 transition-colors opacity-60 group-hover:opacity-100"
                                    title="Eliminar task"
                                >
                                    <svg class="w-4 h-4 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                        @if($userTasks->count() >= 5)
                            <a 
                                href="{{ route('walee.tareas') }}" 
                                onclick="closeMobileMenu()"
                                class="block px-4 py-2 text-xs text-center text-violet-600 dark:text-violet-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors border-t border-slate-200 dark:border-slate-700"
                            >
                                Ver todas las tasks →
                            </a>
                        @endif
                    </div>
                @endif
            </div>
            
            <a 
                href="{{ route('walee.calendario.aplicaciones') }}" 
                onclick="closeMobileMenu()"
                class="flex items-center gap-3 px-4 py-3 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors border-b border-slate-200 dark:border-slate-700 last:border-b-0"
            >
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="font-medium">Calendario</span>
            </a>
            
            <div class="border-b border-slate-200 dark:border-slate-700">
                <a 
                    href="{{ route('walee.notas') }}" 
                    onclick="closeMobileMenu()"
                    class="flex items-center gap-3 px-4 py-3 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                >
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="font-medium flex-1">Notas</span>
                </a>
                
                @php
                    $userNotes = \App\Models\Note::where('user_id', auth()->id())
                        ->whereNull('cliente_id')
                        ->whereNull('client_id')
                        ->orderBy('pinned', 'desc')
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();
                @endphp
                
                @if($userNotes->count() > 0)
                    <button 
                        onclick="toggleNotesMenu()"
                        class="w-full flex items-center justify-between px-4 py-2 text-xs text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                    >
                        <span class="font-semibold uppercase tracking-wider">Mis Notas Recientes</span>
                        <svg id="notesMenuIcon" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <div id="notesMenuContent" class="notes-menu-content hidden max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
                        @foreach($userNotes as $nota)
                            <div class="note-item flex items-start gap-2 px-4 py-2.5 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors group">
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-2 break-words">
                                        {{ Str::limit($nota->content, 80) }}
                                    </p>
                                    <p class="text-xs text-slate-500 dark:text-slate-500 mt-1">
                                        {{ $nota->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <button 
                                    onclick="copyNoteFromMenu(event)"
                                    class="flex-shrink-0 p-1.5 rounded-md hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors opacity-60 group-hover:opacity-100"
                                    title="Copiar nota"
                                    data-note-id="{{ $nota->id }}"
                                    data-note-content="{{ base64_encode($nota->content) }}"
                                >
                                    <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                        @if($userNotes->count() >= 5)
                            <a 
                                href="{{ route('walee.notas') }}" 
                                onclick="closeMobileMenu()"
                                class="block px-4 py-2 text-xs text-center text-amber-600 dark:text-amber-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors border-t border-slate-200 dark:border-slate-700"
                            >
                                Ver todas las notas →
                            </a>
                        @endif
                    </div>
                @endif
            </div>
            
            <a 
                href="/admin" 
                onclick="closeMobileMenu()"
                class="flex items-center gap-3 px-4 py-3 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors border-b border-slate-200 dark:border-slate-700 last:border-b-0"
            >
                <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="font-medium">Dashboard</span>
            </a>
            
            <form method="POST" action="/logout" class="w-full">
                @csrf
                <button 
                    type="submit"
                    onclick="closeMobileMenu()"
                    class="w-full flex items-center gap-3 px-4 py-3 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span class="font-medium">Cerrar sesión</span>
                </button>
            </form>
        </div>
    </div>
</header>

<style>
    #mobileMenu {
        position: absolute !important;
    }
    
    @media (max-width: 640px) {
        #mobileMenu {
            width: calc(100vw - 2rem) !important;
            right: 0 !important;
            left: auto !important;
        }
    }
</style>

<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        const toggle = document.getElementById('mobileMenuToggle');
        const line1 = document.getElementById('menuLine1');
        const line2 = document.getElementById('menuLine2');
        const line3 = document.getElementById('menuLine3');
        
        if (menu.style.display === 'none' || !menu.style.display) {
            // Open menu
            menu.style.display = 'block';
            menu.classList.remove('hidden');
            // Ensure theme colors are applied
            if (document.documentElement.classList.contains('dark')) {
                menu.classList.add('dark:bg-slate-900');
                menu.classList.remove('bg-white');
            } else {
                menu.classList.add('bg-white');
                menu.classList.remove('dark:bg-slate-900');
            }
            toggle.setAttribute('aria-expanded', 'true');
            
            // Animate hamburger to X
            line1.style.transform = 'rotate(45deg) translate(5px, 5px)';
            line2.style.opacity = '0';
            line3.style.transform = 'rotate(-45deg) translate(7px, -6px)';
        } else {
            // Close menu
            closeMobileMenu();
        }
    }
    
    function closeMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        const toggle = document.getElementById('mobileMenuToggle');
        const line1 = document.getElementById('menuLine1');
        const line2 = document.getElementById('menuLine2');
        const line3 = document.getElementById('menuLine3');
        
        menu.style.display = 'none';
        menu.classList.add('hidden');
        toggle.setAttribute('aria-expanded', 'false');
        
        // Reset hamburger icon
        line1.style.transform = 'none';
        line2.style.opacity = '1';
        line3.style.transform = 'none';
    }
    
    // Close menu when clicking outside
    document.addEventListener('click', function(event) {
        const menu = document.getElementById('mobileMenu');
        const toggle = document.getElementById('mobileMenuToggle');
        
        if (menu && toggle && !menu.contains(event.target) && !toggle.contains(event.target)) {
            if (menu.style.display === 'block') {
                closeMobileMenu();
            }
        }
    });
    
    // Close menu on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeMobileMenu();
        }
    });
    
    function toggleNotesMenu() {
        const content = document.getElementById('notesMenuContent');
        const icon = document.getElementById('notesMenuIcon');
        
        if (content) {
            if (content.classList.contains('hidden')) {
                // Abrir menú
                content.classList.remove('hidden');
                // Forzar reflow para que la transición funcione
                content.offsetHeight;
                // Aplicar altura máxima para la animación
                content.style.maxHeight = content.scrollHeight + 'px';
                content.style.opacity = '1';
                
                if (icon) {
                    icon.style.transform = 'rotate(180deg)';
                }
            } else {
                // Cerrar menú
                content.style.maxHeight = '0px';
                content.style.opacity = '0';
                
                if (icon) {
                    icon.style.transform = 'rotate(0deg)';
                }
                
                // Esperar a que termine la animación antes de ocultar
                setTimeout(() => {
                    if (content.style.maxHeight === '0px') {
                        content.classList.add('hidden');
                    }
                }, 300);
            }
        }
    }
    
    function copyNoteFromMenu(event) {
        event.stopPropagation();
        event.preventDefault();
        
        const button = event.currentTarget;
        const noteContentEncoded = button.getAttribute('data-note-content');
        
        if (!noteContentEncoded) {
            console.error('No se encontró el contenido de la nota');
            showNotification('Error al copiar', 'error');
            return;
        }
        
        try {
            // Decodificar el contenido desde base64
            const noteContent = atob(noteContentEncoded);
            
            // Copiar al portapapeles
            navigator.clipboard.writeText(noteContent).then(() => {
                showNotification('Nota copiada', 'success');
            }).catch(err => {
                console.error('Error al copiar:', err);
                // Fallback: usar método alternativo
                const textArea = document.createElement('textarea');
                textArea.value = noteContent;
                textArea.style.position = 'fixed';
                textArea.style.left = '-999999px';
                textArea.style.top = '-999999px';
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                try {
                    document.execCommand('copy');
                    showNotification('Nota copiada', 'success');
                } catch (e) {
                    console.error('Error al copiar:', e);
                    showNotification('Error al copiar', 'error');
                }
                document.body.removeChild(textArea);
            });
        } catch (err) {
            console.error('Error al decodificar:', err);
            showNotification('Error al copiar', 'error');
        }
    }
    
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white px-4 py-2 rounded-lg shadow-lg z-50 animate-fade-in`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transition = 'opacity 0.3s';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 2000);
    }
    
    function toggleTasksMenu() {
        const content = document.getElementById('tasksMenuContent');
        const icon = document.getElementById('tasksMenuIcon');
        
        if (content) {
            if (content.classList.contains('hidden')) {
                // Abrir menú
                content.classList.remove('hidden');
                content.offsetHeight;
                content.style.maxHeight = content.scrollHeight + 'px';
                content.style.opacity = '1';
                
                if (icon) {
                    icon.style.transform = 'rotate(180deg)';
                }
            } else {
                // Cerrar menú
                content.style.maxHeight = '0px';
                content.style.opacity = '0';
                
                if (icon) {
                    icon.style.transform = 'rotate(0deg)';
                }
                
                setTimeout(() => {
                    if (content.style.maxHeight === '0px') {
                        content.classList.add('hidden');
                    }
                }, 300);
            }
        }
    }
    
    async function agregarQuickTask(event) {
        event.preventDefault();
        const input = document.getElementById('quickTaskInput');
        const texto = input.value.trim();
        
        if (!texto) return;
        
        try {
            const response = await fetch('/walee-quick-task', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ texto: texto })
            });
            
            const data = await response.json();
            
            if (data.success) {
                input.value = '';
                showNotification('Task agregada', 'success');
                // Recargar la página después de un breve delay
                setTimeout(() => {
                    location.reload();
                }, 500);
            } else {
                showNotification('Error al agregar task', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Error al agregar task', 'error');
        }
    }
    
    async function toggleTaskEstado(event, taskId) {
        event.stopPropagation();
        const checked = event.target.checked;
        const estado = checked ? 'completado' : 'pending';
        
        try {
            const response = await fetch(`/walee-quick-task/${taskId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ estado: estado })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Actualizar visualmente
                const taskItem = event.target.closest('.task-item');
                const taskText = taskItem.querySelector('p');
                if (checked) {
                    taskText.classList.add('line-through', 'opacity-60');
                } else {
                    taskText.classList.remove('line-through', 'opacity-60');
                }
            } else {
                // Revertir checkbox
                event.target.checked = !checked;
                showNotification('Error al actualizar task', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            event.target.checked = !checked;
            showNotification('Error al actualizar task', 'error');
        }
    }
    
    async function eliminarQuickTask(event, taskId) {
        event.stopPropagation();
        event.preventDefault();
        
        if (!confirm('¿Eliminar esta task?')) return;
        
        try {
            const response = await fetch(`/walee-quick-task/${taskId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                showNotification('Task eliminada', 'success');
                // Remover el elemento del DOM
                event.target.closest('.task-item').remove();
            } else {
                showNotification('Error al eliminar task', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Error al eliminar task', 'error');
        }
    }
</script>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Animaciones suaves para el menú de notas */
    .notes-menu-content {
        transition: max-height 0.3s ease-in-out, opacity 0.3s ease-in-out;
        opacity: 0;
    }
    
    .notes-menu-content:not(.hidden) {
        opacity: 1;
    }
    
    #notesMenuIcon {
        transition: transform 0.3s ease-in-out;
    }
    
    /* Animación de entrada para las notas individuales */
    .note-item {
        animation: slideIn 0.3s ease-out forwards;
        opacity: 0;
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Delay escalonado para cada nota */
    .note-item:nth-child(1) { animation-delay: 0.05s; }
    .note-item:nth-child(2) { animation-delay: 0.1s; }
    .note-item:nth-child(3) { animation-delay: 0.15s; }
    .note-item:nth-child(4) { animation-delay: 0.2s; }
    .note-item:nth-child(5) { animation-delay: 0.25s; }
    
    /* Animaciones suaves para el menú de tasks */
    .tasks-menu-content {
        transition: max-height 0.3s ease-in-out, opacity 0.3s ease-in-out;
        opacity: 0;
    }
    
    .tasks-menu-content:not(.hidden) {
        opacity: 1;
    }
    
    #tasksMenuIcon {
        transition: transform 0.3s ease-in-out;
    }
    
    /* Animación de entrada para las tasks individuales */
    .task-item {
        animation: slideIn 0.3s ease-out forwards;
        opacity: 0;
    }
    
    /* Delay escalonado para cada task */
    .task-item:nth-child(1) { animation-delay: 0.05s; }
    .task-item:nth-child(2) { animation-delay: 0.1s; }
    .task-item:nth-child(3) { animation-delay: 0.15s; }
    .task-item:nth-child(4) { animation-delay: 0.2s; }
    .task-item:nth-child(5) { animation-delay: 0.25s; }
</style>

