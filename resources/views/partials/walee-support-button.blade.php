<!-- Support Button (Floating) -->
<button 
    onclick="openSupportModal()" 
    class="fixed bottom-36 right-6 w-12 h-12 bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-300 dark:border-slate-700 rounded-full shadow-lg flex items-center justify-center transition-all hover:scale-110 group z-40"
    title="Soporte"
>
    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400 group-hover:text-walee-400 dark:group-hover:text-walee-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
</button>

<!-- Support Modal -->
<div id="supportModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-end sm:items-center justify-center p-4">
    <div class="bg-slate-900 rounded-2xl border border-slate-700 w-full max-w-md overflow-hidden transform transition-all">
        <!-- Header -->
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-700/50">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-walee-400/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white">Soporte</h3>
                    <p class="text-xs text-slate-400">¿Necesitas ayuda?</p>
                </div>
            </div>
            <button onclick="closeSupportModal()" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 flex items-center justify-center transition-colors">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <!-- Form -->
        <form id="supportForm" class="p-5 space-y-4">
            <!-- Subject -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Asunto</label>
                <input 
                    type="text" 
                    name="subject" 
                    placeholder="¿En qué podemos ayudarte?"
                    class="w-full px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all text-sm"
                >
            </div>
            
            <!-- Message -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Mensaje</label>
                <textarea 
                    name="message" 
                    rows="4" 
                    placeholder="Describe tu problema o pregunta..."
                    class="w-full px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all resize-none text-sm"
                ></textarea>
            </div>
            
            <!-- File Upload -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Captura de pantalla (opcional)</label>
                <div class="relative">
                    <input 
                        type="file" 
                        name="screenshot" 
                        id="supportFile"
                        accept="image/*"
                        class="hidden"
                        onchange="updateFileName(this)"
                    >
                    <label 
                        for="supportFile" 
                        class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-slate-800 border border-dashed border-slate-600 rounded-xl text-slate-400 hover:border-walee-500/50 hover:text-walee-400 cursor-pointer transition-all"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span id="fileLabel" class="text-sm">Subir imagen</span>
                    </label>
                </div>
            </div>
            
            <!-- Submit -->
            <button 
                type="submit" 
                class="w-full px-4 py-3 bg-walee-500 hover:bg-walee-400 text-white font-medium rounded-xl transition-all flex items-center justify-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                Enviar mensaje
            </button>
        </form>
        
        <!-- Footer -->
        <div class="px-5 py-3 bg-slate-800/50 border-t border-slate-700/50">
            <p class="text-xs text-slate-500 text-center">
                También puedes escribirnos a <span class="text-walee-400">websolutionscrnow@gmail.com</span>
            </p>
        </div>
    </div>
</div>

<script>
    // Support modal functions - only initialize if not already defined
    if (typeof openSupportModal === 'undefined') {
        const csrfTokenSupport = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        function openSupportModal() {
            const modal = document.getElementById('supportModal');
            if (modal) {
                modal.classList.remove('hidden');
            }
        }
        
        function closeSupportModal() {
            const modal = document.getElementById('supportModal');
            const form = document.getElementById('supportForm');
            const fileLabel = document.getElementById('fileLabel');
            
            if (modal) {
                modal.classList.add('hidden');
            }
            if (form) {
                form.reset();
            }
            if (fileLabel) {
                fileLabel.textContent = 'Subir imagen';
            }
        }
        
        function updateFileName(input) {
            const label = document.getElementById('fileLabel');
            if (label && input.files && input.files[0]) {
                label.textContent = input.files[0].name;
            }
        }
        
        function showSupportNotification(title, message, type) {
            const modal = document.getElementById('supportModal');
            if (!modal) return;
            
            const existing = modal.querySelector('.support-notification');
            if (existing) existing.remove();
            
            const bgColor = type === 'success' ? 'bg-emerald-500' : 'bg-red-500';
            const notification = document.createElement('div');
            notification.className = `support-notification ${bgColor} text-white px-4 py-3 rounded-xl mb-4 text-sm`;
            notification.innerHTML = `<strong>${title}</strong><br>${message}`;
            
            const form = document.getElementById('supportForm');
            if (form) {
                form.insertBefore(notification, form.firstChild);
                setTimeout(() => notification.remove(), 5000);
            }
        }
        
        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('supportModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) closeSupportModal();
                });
            }
            
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const modal = document.getElementById('supportModal');
                    if (modal && !modal.classList.contains('hidden')) {
                        closeSupportModal();
                    }
                }
            });
            
            const form = document.getElementById('supportForm');
            if (form) {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const asunto = this.querySelector('[name="subject"]').value.trim();
                    const mensaje = this.querySelector('[name="message"]').value.trim();
                    
                    if (!asunto || !mensaje) {
                        showSupportNotification('Error', 'Por favor completa el asunto y mensaje', 'error');
                        return;
                    }
                    
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `
                        <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Enviando...
                    `;
                    
                    try {
                        const formData = new FormData();
                        formData.append('name', 'Web Solutions');
                        formData.append('email', 'websolutionscrnow@gmail.com');
                        formData.append('website', 'https://websolutions.work/walee-dashboard');
                        formData.append('asunto', asunto);
                        formData.append('mensaje', mensaje);
                        
                        const fileInput = document.getElementById('supportFile');
                        if (fileInput && fileInput.files && fileInput.files[0]) {
                            formData.append('imagen', fileInput.files[0]);
                        }
                        
                        const response = await fetch('{{ route("walee.tickets.store") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfTokenSupport,
                            },
                            body: formData,
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            showSupportNotification('¡Enviado!', 'Tu mensaje ha sido recibido. Te responderemos pronto.', 'success');
                            this.reset();
                            const fileLabel = document.getElementById('fileLabel');
                            if (fileLabel) fileLabel.textContent = 'Subir imagen';
                            
                            setTimeout(() => closeSupportModal(), 2000);
                        } else {
                            showSupportNotification('Error', data.message || 'No se pudo enviar el mensaje', 'error');
                        }
                    } catch (error) {
                        showSupportNotification('Error', 'Error de conexión: ' + error.message, 'error');
                    } finally {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                });
            }
        });
    }
</script>

