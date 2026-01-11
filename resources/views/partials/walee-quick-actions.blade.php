<!-- Quick Actions - Versión estándar para todas las páginas Walee -->
<section class="mb-4 sm:mb-6 md:mb-8 animate-fade-in-up">
    <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-300 mb-4 flex items-center gap-2">
        <svg class="w-5 h-5 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
        Acciones Rápidas
    </h2>
    
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <!-- Products -->
        <a href="{{ route('walee.productos.super') }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-violet-50 dark:bg-slate-900/50 border border-black dark:border-black hover:border-black dark:hover:border-black hover:bg-emerald-400/5 dark:hover:bg-emerald-400/5 transition-all duration-300">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Products</span>
        </a>
        
        <!-- Suppliers -->
        <a href="{{ route('walee.proveedores.dashboard') }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-violet-50 dark:bg-slate-900/50 border border-black dark:border-black hover:border-black dark:hover:border-black hover:bg-orange-400/5 dark:hover:bg-orange-400/5 transition-all duration-300">
            <div class="w-12 h-12 rounded-xl bg-orange-100 dark:bg-orange-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">Suppliers</span>
        </a>
        
        <!-- Calendar -->
        <a href="{{ route('walee.calendario') }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-violet-50 dark:bg-slate-900/50 border border-black dark:border-black hover:border-black dark:hover:border-black hover:bg-emerald-400/5 dark:hover:bg-emerald-400/5 transition-all duration-300">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Calendar</span>
        </a>
        
        <!-- Stock Management -->
        <a href="{{ route('walee.herramientas.stuck-dlc-dlv') }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-violet-50 dark:bg-slate-900/50 border border-black dark:border-black hover:border-black dark:hover:border-black hover:bg-orange-400/5 dark:hover:bg-orange-400/5 transition-all duration-300">
            <div class="w-12 h-12 rounded-xl bg-orange-100 dark:bg-orange-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">Stock Management</span>
        </a>
        
        <!-- Inventory -->
        <a href="{{ route('walee.herramientas.inventory') }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-violet-50 dark:bg-slate-900/50 border border-black dark:border-black hover:border-black dark:hover:border-black hover:bg-blue-400/5 dark:hover:bg-blue-400/5 transition-all duration-300">
            <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Inventory</span>
            <span class="text-xs text-slate-500 dark:text-slate-400 group-hover:text-blue-500 dark:group-hover:text-blue-400 transition-colors">DLC DLV</span>
        </a>
        
        <!-- WhatsApp (Bloqueado) -->
        <button onclick="window.openSubscribeModal()" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700 cursor-pointer opacity-60">
            <div class="w-12 h-12 rounded-xl bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
                <svg class="w-6 h-6 text-slate-500 dark:text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                </svg>
            </div>
            <span class="text-sm font-medium text-slate-500 dark:text-slate-400">WhatsApp</span>
        </button>
        
        <!-- Company (Bloqueado) -->
        <button onclick="window.openSubscribeModal()" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700 cursor-pointer opacity-60">
            <div class="w-12 h-12 rounded-xl bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
                <svg class="w-6 h-6 text-slate-500 dark:text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
            </div>
            <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Company</span>
        </button>
        
        <!-- Manager (Bloqueado) -->
        <button onclick="window.openSubscribeModal()" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700 cursor-pointer opacity-60">
            <div class="w-12 h-12 rounded-xl bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
                <svg class="w-6 h-6 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
            <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Manager</span>
        </button>
        
        <!-- Tools -->
        <a href="{{ route('walee.herramientas') }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-violet-50 dark:bg-slate-900/50 border border-black dark:border-black hover:border-black dark:hover:border-black hover:bg-walee-400/5 dark:hover:bg-walee-400/5 transition-all duration-300">
            <div class="w-12 h-12 rounded-xl bg-walee-100 dark:bg-walee-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-walee-600 dark:group-hover:text-walee-400 transition-colors">Tools</span>
        </a>
    </div>
</section>

<script>
    // Función para abrir modal de suscripción premium (disponible globalmente)
    // Definir inmediatamente para que esté disponible antes de que se cargue el DOM
    (function() {
        if (typeof window.openSubscribeModal === 'undefined') {
            window.openSubscribeModal = function() {
                const isDarkMode = document.documentElement.classList.contains('dark');
                
                // Verificar si SweetAlert2 está disponible
                if (typeof Swal === 'undefined') {
                    console.error('SweetAlert2 no está cargado');
                    alert('Esta característica está disponible solo para usuarios premium. Por favor, suscríbete para desbloquear.');
                    return;
                }
                
                Swal.fire({
                    title: '<div style="font-size: 1.5rem; font-weight: 700; color: ' + (isDarkMode ? '#e2e8f0' : '#1e293b') + ';">🔒 Características Premium</div>',
                    html: `
                        <div style="text-align: left; padding: 1rem 0; color: ${isDarkMode ? '#cbd5e1' : '#475569'};">
                            <p style="font-size: 0.875rem; margin-bottom: 1rem; color: ${isDarkMode ? '#e2e8f0' : '#334155'};">
                                Esta característica está disponible solo para usuarios premium. Suscríbete para desbloquear:
                            </p>
                            <ul style="font-size: 0.875rem; margin-bottom: 1.5rem; padding-left: 1.5rem; list-style-type: disc; color: ${isDarkMode ? '#cbd5e1' : '#64748b'};">
                                <li style="margin-bottom: 0.5rem;">WhatsApp</li>
                                <li style="margin-bottom: 0.5rem;">Company</li>
                                <li style="margin-bottom: 0.5rem;">Manager</li>
                            </ul>
                            <div style="text-align: center; margin-top: 1.5rem;">
                                <a href="{{ route('suscribe') }}" 
                                   style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background-color: #D59F3B; color: white; border-radius: 0.5rem; font-weight: 500; text-decoration: none; transition: all 0.2s; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);"
                                   onmouseover="this.style.backgroundColor='#C78F2E'; this.style.boxShadow='0 10px 15px -3px rgba(0, 0, 0, 0.1)';"
                                   onmouseout="this.style.backgroundColor='#D59F3B'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.1)';">
                                    <span>Suscribirse Ahora</span>
                                    <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    `,
                    width: '500px',
                    padding: '2rem',
                    background: isDarkMode ? '#1e293b' : '#ffffff',
                    color: isDarkMode ? '#e2e8f0' : '#1e293b',
                    showConfirmButton: false,
                    showCancelButton: true,
                    cancelButtonText: 'Cerrar',
                    cancelButtonColor: '#6b7280',
                    cancelButtonAriaLabel: 'Cerrar modal',
                    backdrop: true,
                    allowOutsideClick: true,
                    allowEscapeKey: true,
                    customClass: {
                        popup: isDarkMode ? 'dark-swal-popup' : 'light-swal-popup',
                        htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                        cancelButton: isDarkMode ? 'dark-swal-cancel' : 'light-swal-cancel'
                    },
                    didOpen: () => {
                        const popup = Swal.getPopup();
                        if (popup) {
                            popup.style.backgroundColor = isDarkMode ? '#1e293b' : '#ffffff';
                            popup.style.color = isDarkMode ? '#e2e8f0' : '#1e293b';
                            popup.style.borderRadius = '1rem';
                            popup.style.boxShadow = isDarkMode 
                                ? '0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 10px 10px -5px rgba(0, 0, 0, 0.2)'
                                : '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';
                        }
                    }
                });
            };
        }
    })();
</script>
