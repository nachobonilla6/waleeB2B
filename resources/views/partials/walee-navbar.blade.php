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
    
    <!-- Quick Access Buttons: Suppliers & Inventory -->
    <div class="hidden md:flex items-center gap-2 flex-shrink-0 mx-2">
        <a 
            href="{{ route('walee.proveedores.dashboard') }}" 
            class="flex items-center gap-2 px-3 py-2 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 hover:bg-emerald-100 dark:hover:bg-emerald-900/30 transition-colors group"
            title="Suppliers"
        >
            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <span class="text-sm font-medium text-emerald-700 dark:text-emerald-300">Suppliers</span>
        </a>
        
        <a 
            href="{{ route('walee.herramientas.inventory') }}" 
            class="flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors group"
            title="Inventory"
        >
            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <span class="text-sm font-medium text-blue-700 dark:text-blue-300">Inventory</span>
        </a>
    </div>
    
    <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0 relative">
        <!-- Deploy Button (Desktop only) -->
        <button 
            onclick="triggerDeploy()"
            class="hidden md:flex items-center gap-2 px-3 py-2 rounded-lg bg-gradient-to-r from-walee-500 to-walee-600 hover:from-walee-600 hover:to-walee-700 text-white text-sm font-medium transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 active:scale-95"
            title="Deploy: git pull && migrate && optimize:clear"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            <span>Deploy</span>
        </button>
        
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
                href="{{ route('walee.proveedores.dashboard') }}" 
                onclick="closeMobileMenu()"
                class="flex items-center gap-3 px-4 py-3 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors border-b border-slate-200 dark:border-slate-700 last:border-b-0"
            >
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="font-medium">Suppliers</span>
            </a>
            
            <a 
                href="{{ route('walee.herramientas.inventory') }}" 
                onclick="closeMobileMenu()"
                class="flex items-center gap-3 px-4 py-3 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors border-b border-slate-200 dark:border-slate-700 last:border-b-0"
            >
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span class="font-medium">Inventory</span>
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
    
    // Deploy function - triggers N8N webhook
    async function triggerDeploy() {
        const webhookUrl = 'https://n8n.srv1137974.hstgr.cloud/webhook-test/waleeb2b';
        const command = 'git pull origin main && php artisan migrate --force && php artisan optimize:clear';
        
        try {
            // Show loading state
            const deployButton = event.target.closest('button');
            const originalContent = deployButton.innerHTML;
            deployButton.disabled = true;
            deployButton.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg><span>Deploying...</span>';
            
            // Call webhook with command
            const response = await fetch(webhookUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    command: command
                })
            });
            
            // Restore button
            deployButton.disabled = false;
            deployButton.innerHTML = originalContent;
            
            if (response.ok) {
                showNotification('Deploy triggered successfully! Refreshing page...', 'success');
                // Refresh page after 2 seconds to allow webhook to process
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                throw new Error('Webhook returned error status: ' + response.status);
            }
        } catch (error) {
            console.error('Deploy error:', error);
            showNotification('Error triggering deploy: ' + error.message, 'error');
            // Restore button on error
            const deployButton = event.target.closest('button');
            if (deployButton) {
                deployButton.disabled = false;
                deployButton.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg><span>Deploy</span>';
            }
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

