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
                @if(isset($pageTitle))
                    {{ $pageTitle }}
                @else
                    Dashboard · {{ now()->format('d M, Y') }}
                @endif
            </p>
        </div>
    </div>
    
    <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
        @include('partials.walee-dark-mode-toggle')
        
        <!-- Mobile Menu Toggle Button (Bootstrap Style) -->
        <button 
            id="mobileMenuToggle"
            onclick="toggleMobileMenu()"
            class="sm:hidden w-10 h-10 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 flex flex-col items-center justify-center gap-1.5 transition-colors"
            aria-label="Toggle menu"
            aria-expanded="false"
            aria-controls="mobileMenu"
        >
            <span class="block w-5 h-0.5 bg-slate-700 dark:bg-slate-300 rounded transition-all duration-300" id="menuLine1"></span>
            <span class="block w-5 h-0.5 bg-slate-700 dark:bg-slate-300 rounded transition-all duration-300" id="menuLine2"></span>
            <span class="block w-5 h-0.5 bg-slate-700 dark:bg-slate-300 rounded transition-all duration-300" id="menuLine3"></span>
        </button>
        
        <!-- Desktop Links -->
        <a href="{{ route('walee') }}" class="hidden sm:flex items-center gap-2 px-3 sm:px-4 py-2 rounded-xl bg-walee-50 dark:bg-walee-400/10 hover:bg-walee-100 dark:hover:bg-walee-400/20 text-walee-600 dark:text-walee-400 transition-all duration-300 border border-walee-200 dark:border-walee-400/20 shadow-sm dark:shadow-none">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <span class="text-xs sm:text-sm font-medium hidden lg:inline">Chat</span>
        </a>
        
        <a href="{{ route('walee.tickets') }}" class="hidden sm:flex items-center gap-2 px-3 sm:px-4 py-2 rounded-xl bg-blue-50 dark:bg-blue-500/10 hover:bg-blue-100 dark:hover:bg-blue-500/20 text-blue-600 dark:text-blue-400 transition-all duration-300 border border-blue-200 dark:border-blue-500/20 shadow-sm dark:shadow-none">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="text-xs sm:text-sm font-medium hidden lg:inline">Tickets</span>
        </a>
        
        <a href="{{ route('walee.tareas') }}" class="hidden sm:flex items-center gap-2 px-3 sm:px-4 py-2 rounded-xl bg-violet-50 dark:bg-violet-500/10 hover:bg-violet-100 dark:hover:bg-violet-500/20 text-violet-600 dark:text-violet-400 transition-all duration-300 border border-violet-200 dark:border-violet-500/20 shadow-sm dark:shadow-none">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            <span class="text-xs sm:text-sm font-medium hidden lg:inline">Tareas</span>
        </a>
        
        <a href="{{ route('walee.calendario') }}" class="hidden sm:flex items-center gap-2 px-3 sm:px-4 py-2 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 transition-all duration-300 border border-emerald-200 dark:border-emerald-500/20 shadow-sm dark:shadow-none">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="text-xs sm:text-sm font-medium hidden lg:inline">Calendario</span>
        </a>
    </div>
    
    <!-- Mobile Menu Dropdown (Bootstrap Style) -->
    <div 
        id="mobileMenu" 
        class="sm:hidden w-full mt-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg shadow-2xl overflow-hidden hidden"
        style="display: none;"
    >
        <div class="py-2">
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
                href="{{ route('walee.tickets') }}" 
                onclick="closeMobileMenu()"
                class="flex items-center gap-3 px-4 py-3 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors border-b border-slate-200 dark:border-slate-700 last:border-b-0"
            >
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="font-medium">Tickets</span>
            </a>
            
            <a 
                href="{{ route('walee.tareas') }}" 
                onclick="closeMobileMenu()"
                class="flex items-center gap-3 px-4 py-3 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors border-b border-slate-200 dark:border-slate-700 last:border-b-0"
            >
                <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <span class="font-medium">Tareas</span>
            </a>
            
            <a 
                href="{{ route('walee.calendario') }}" 
                onclick="closeMobileMenu()"
                class="flex items-center gap-3 px-4 py-3 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
            >
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="font-medium">Calendario</span>
            </a>
        </div>
    </div>
</header>

<style>
    #mobileMenu {
        position: relative !important;
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
</script>

