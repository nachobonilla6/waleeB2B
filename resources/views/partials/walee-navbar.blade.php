<!-- Walee Navbar -->
<header class="flex items-center justify-between mb-6 sm:mb-8 animate-fade-in-up relative">
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
        
        <!-- Mobile Menu Button -->
        <button 
            id="mobileMenuToggle"
            onclick="toggleMobileMenu()"
            class="sm:hidden w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center justify-center transition-colors border border-slate-300 dark:border-slate-700"
            aria-label="Toggle menu"
        >
            <svg id="menuIcon" class="w-6 h-6 text-slate-700 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <svg id="closeIcon" class="w-6 h-6 text-slate-700 dark:text-slate-300 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
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
    
    <!-- Mobile Menu -->
    <div id="mobileMenu" class="sm:hidden fixed inset-x-0 top-0 mt-[88px] bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700 shadow-lg z-40 transform -translate-y-full transition-transform duration-300 ease-in-out">
        <div class="px-4 py-3 space-y-2">
            <a href="{{ route('walee') }}" onclick="closeMobileMenu()" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-walee-50 dark:bg-walee-400/10 hover:bg-walee-100 dark:hover:bg-walee-400/20 text-walee-600 dark:text-walee-400 transition-all duration-300 border border-walee-200 dark:border-walee-400/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <span class="font-medium">Chat</span>
            </a>
            
            <a href="{{ route('walee.tickets') }}" onclick="closeMobileMenu()" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-50 dark:bg-blue-500/10 hover:bg-blue-100 dark:hover:bg-blue-500/20 text-blue-600 dark:text-blue-400 transition-all duration-300 border border-blue-200 dark:border-blue-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="font-medium">Tickets</span>
            </a>
            
            <a href="{{ route('walee.tareas') }}" onclick="closeMobileMenu()" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-violet-50 dark:bg-violet-500/10 hover:bg-violet-100 dark:hover:bg-violet-500/20 text-violet-600 dark:text-violet-400 transition-all duration-300 border border-violet-200 dark:border-violet-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <span class="font-medium">Tareas</span>
            </a>
            
            <a href="{{ route('walee.calendario') }}" onclick="closeMobileMenu()" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 transition-all duration-300 border border-emerald-200 dark:border-emerald-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="font-medium">Calendario</span>
            </a>
        </div>
    </div>
    
    <!-- Mobile Menu Overlay -->
    <div id="mobileMenuOverlay" class="sm:hidden fixed inset-0 bg-black/60 dark:bg-black/80 backdrop-blur-sm z-30 opacity-0 pointer-events-none transition-opacity duration-300" onclick="closeMobileMenu()"></div>
</header>

<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        const overlay = document.getElementById('mobileMenuOverlay');
        const menuIcon = document.getElementById('menuIcon');
        const closeIcon = document.getElementById('closeIcon');
        
        if (menu.classList.contains('-translate-y-full')) {
            // Open menu
            menu.classList.remove('-translate-y-full');
            overlay.classList.remove('opacity-0', 'pointer-events-none');
            overlay.classList.add('opacity-100');
            menuIcon.classList.add('hidden');
            closeIcon.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } else {
            // Close menu
            closeMobileMenu();
        }
    }
    
    function closeMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        const overlay = document.getElementById('mobileMenuOverlay');
        const menuIcon = document.getElementById('menuIcon');
        const closeIcon = document.getElementById('closeIcon');
        
        menu.classList.add('-translate-y-full');
        overlay.classList.remove('opacity-100');
        overlay.classList.add('opacity-0', 'pointer-events-none');
        menuIcon.classList.remove('hidden');
        closeIcon.classList.add('hidden');
        document.body.style.overflow = '';
    }
    
    // Close menu on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeMobileMenu();
        }
    });
</script>

