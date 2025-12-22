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
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 w-full max-w-md overflow-hidden transform transition-all">
        <!-- Header -->
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200 dark:border-slate-700/50">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-walee-400/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-black dark:text-white">Soporte</h3>
                    <p class="text-xs text-slate-600 dark:text-slate-400">¿Necesitas ayuda?</p>
                </div>
            </div>
            <button onclick="closeSupportModal()" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center justify-center transition-colors">
                <svg class="w-5 h-5 text-black dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <!-- Form -->
        <form id="supportForm" class="p-5 space-y-4">
            <!-- Subject -->
            <div>
                <label class="block text-sm font-medium text-black dark:text-slate-300 mb-2">Asunto</label>
                <input 
                    type="text" 
                    name="subject" 
                    placeholder="¿En qué podemos ayudarte?"
                    class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-black dark:text-white placeholder-slate-500 dark:placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all text-sm"
                >
            </div>
            
            <!-- Message -->
            <div>
                <label class="block text-sm font-medium text-black dark:text-slate-300 mb-2">Mensaje</label>
                <textarea 
                    name="message" 
                    rows="4" 
                    placeholder="Describe tu problema o pregunta..."
                    class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-black dark:text-white placeholder-slate-500 dark:placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all resize-none text-sm"
                ></textarea>
            </div>
            
            <!-- File Upload -->
            <div>
                <label class="block text-sm font-medium text-black dark:text-slate-300 mb-2">Archivos adjuntos (opcional)</label>
                <div class="relative">
                    <input 
                        type="file" 
                        name="screenshots" 
                        id="supportFile"
                        accept="image/*,application/pdf"
                        multiple
                        class="hidden"
                        onchange="handleSupportFiles(this)"
                    >
                    <label 
                        for="supportFile" 
                        class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-white dark:bg-slate-800 border border-dashed border-slate-300 dark:border-slate-600 rounded-xl text-slate-600 dark:text-slate-400 hover:border-walee-500/50 hover:text-walee-400 cursor-pointer transition-all"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span id="fileLabel" class="text-sm">Subir archivos (imágenes o PDF)</span>
                    </label>
                </div>
                <!-- Selected Files List -->
                <div id="supportFilesList" class="mt-3 space-y-2 hidden"></div>
            </div>
            
            <!-- Urgente Checkbox -->
            <div>
                <label class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                    <input 
                        type="checkbox" 
                        name="urgente" 
                        id="supportUrgente"
                        class="w-5 h-5 text-yellow-500 border-slate-300 dark:border-slate-600 rounded focus:ring-yellow-500 focus:ring-2"
                    >
                    <div class="flex items-center gap-2 flex-1">
                        <svg class="w-5 h-5 text-yellow-500 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        <span class="text-sm font-medium text-black dark:text-white">Marcar como urgente</span>
                    </div>
                </label>
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
        <div class="px-5 py-3 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-700/50">
            <p class="text-xs text-slate-600 dark:text-slate-500 text-center">
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
            const filesList = document.getElementById('supportFilesList');
            
            if (modal) {
                modal.classList.add('hidden');
            }
            if (form) {
                form.reset();
            }
            if (fileLabel) {
                fileLabel.textContent = 'Subir archivos (imágenes o PDF)';
            }
            if (filesList) {
                filesList.classList.add('hidden');
                filesList.innerHTML = '';
            }
            // Reset urgente checkbox
            const urgenteCheckbox = document.getElementById('supportUrgente');
            if (urgenteCheckbox) {
                urgenteCheckbox.checked = false;
            }
        }
        
        function handleSupportFiles(input) {
            const filesList = document.getElementById('supportFilesList');
            const fileLabel = document.getElementById('fileLabel');
            
            if (!filesList || !input.files || input.files.length === 0) {
                if (filesList) filesList.classList.add('hidden');
                if (fileLabel) fileLabel.textContent = 'Subir archivos (imágenes o PDF)';
                return;
            }
            
            filesList.innerHTML = '';
            filesList.classList.remove('hidden');
            
            Array.from(input.files).forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'flex items-center justify-between p-2 bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700';
                fileItem.innerHTML = `
                    <div class="flex items-center gap-2 flex-1 min-w-0">
                        <svg class="w-4 h-4 text-slate-500 dark:text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="text-sm text-black dark:text-white truncate">${file.name}</span>
                        <span class="text-xs text-slate-500 dark:text-slate-400 flex-shrink-0">(${formatSupportFileSize(file.size)})</span>
                    </div>
                    <button 
                        type="button" 
                        onclick="removeSupportFile(${index})" 
                        class="ml-2 p-1 text-red-500 hover:text-red-600 dark:hover:text-red-400 transition-colors"
                        title="Eliminar archivo"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                `;
                filesList.appendChild(fileItem);
            });
            
            const fileCount = input.files.length;
            if (fileLabel) {
                fileLabel.textContent = fileCount === 1 
                    ? input.files[0].name 
                    : `${fileCount} archivos seleccionados`;
            }
        }
        
        function removeSupportFile(index) {
            const input = document.getElementById('supportFile');
            if (!input || !input.files) return;
            
            const dt = new DataTransfer();
            Array.from(input.files).forEach((file, i) => {
                if (i !== index) {
                    dt.items.add(file);
                }
            });
            
            input.files = dt.files;
            handleSupportFiles(input);
        }
        
        function formatSupportFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
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
                        
                        // Agregar campo urgente
                        const urgenteCheckbox = document.getElementById('supportUrgente');
                        if (urgenteCheckbox && urgenteCheckbox.checked) {
                            formData.append('urgente', '1');
                        }
                        
                        const fileInput = document.getElementById('supportFile');
                        if (fileInput && fileInput.files && fileInput.files.length > 0) {
                            Array.from(fileInput.files).forEach((file, index) => {
                                formData.append(`archivos[${index}]`, file);
                            });
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
                            const isUrgente = urgenteCheckbox && urgenteCheckbox.checked;
                            const message = isUrgente 
                                ? 'Tu mensaje urgente ha sido recibido. Te responderemos lo antes posible.' 
                                : 'Tu mensaje ha sido recibido. Te responderemos pronto.';
                            showSupportNotification('¡Enviado!', message, 'success');
                            this.reset();
                            const fileLabel = document.getElementById('fileLabel');
                            const filesList = document.getElementById('supportFilesList');
                            if (fileLabel) fileLabel.textContent = 'Subir archivos (imágenes o PDF)';
                            if (filesList) {
                                filesList.classList.add('hidden');
                                filesList.innerHTML = '';
                            }
                            if (urgenteCheckbox) {
                                urgenteCheckbox.checked = false;
                            }
                            
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

