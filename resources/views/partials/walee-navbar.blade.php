<!-- Walee Navbar -->
<header class="flex items-center justify-between mb-6 sm:mb-8 animate-fade-in-up relative" style="z-index: 9999;">
    <div class="flex items-center gap-2 sm:gap-4 flex-1 min-w-0">
        <a href="{{ route('walee.dashboard') }}" class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl walee-gradient flex items-center justify-center shadow-lg flex-shrink-0" style="animation: pulse-glow 3s infinite;">
            <img src="https://i.postimg.cc/RVw3wk3Y/wa-(Edited).jpg" alt="Walee" class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl object-cover">
        </a>
        <div class="min-w-0 flex-1">
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold bg-gradient-to-r from-walee-300 via-walee-400 to-walee-500 bg-clip-text text-transparent truncate">
                Walee B2B
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
                <span class="font-medium text-slate-900 dark:text-white">Back</span>
            </a>
            
            <a 
                href="{{ route('walee.calendario.aplicaciones') }}" 
                onclick="closeMobileMenu()"
                class="flex items-center gap-3 px-4 py-3 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors border-b border-slate-200 dark:border-slate-700 last:border-b-0"
            >
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="font-medium">Calendar</span>
            </a>
            
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
                    <span class="font-medium">Sign out</span>
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
            console.error('Note content not found');
            showNotification('Error al copiar', 'error');
            return;
        }
        
        try {
            // Decodificar el contenido desde base64
            const noteContent = atob(noteContentEncoded);
            
            // Copiar al portapapeles
            navigator.clipboard.writeText(noteContent).then(() => {
                showNotification('Note copied', 'success');
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
                    showNotification('Note copied', 'success');
                } catch (e) {
                    console.error('Error al copiar:', e);
                    showNotification('Error copying', 'error');
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
                showNotification('Task added', 'success');
                // Recargar la página después de un breve delay
                setTimeout(() => {
                    location.reload();
                }, 500);
            } else {
                showNotification('Error adding task', 'error');
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
                showNotification('Error updating task', 'error');
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
        
        if (!confirm('Delete this task?')) return;
        
        try {
            const response = await fetch(`/walee-quick-task/${taskId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                showNotification('Task deleted', 'success');
                // Remover el elemento del DOM
                event.target.closest('.task-item').remove();
            } else {
                showNotification('Error deleting task', 'error');
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
    
</style>

