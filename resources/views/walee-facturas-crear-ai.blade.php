<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Crear Factura con AI</title>
    <meta name="description" content="Crear Factura con Inteligencia Artificial">
    <meta name="theme-color" content="#D59F3B">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
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
        ::-webkit-scrollbar-thumb { background: rgba(213, 159, 59, 0.3); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(213, 159, 59, 0.5); }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">
    @php
        $clientes = \App\Models\Cliente::orderBy('nombre_empresa')->get();
        $ultimaFactura = \App\Models\Factura::orderBy('id', 'desc')->first();
        $siguienteNumero = $ultimaFactura ? intval($ultimaFactura->numero_factura) + 1 : 1;
    @endphp

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-400/20 dark:bg-purple-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-walee-400/10 dark:bg-walee-400/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-purple-400/20 dark:bg-purple-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-4xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <!-- Header -->
            <header class="flex items-center justify-between mb-8 animate-fade-in-up">
                <div class="flex items-center gap-4">
                    <a href="{{ route('walee.facturas') }}" class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 flex items-center justify-center transition-all shadow-sm dark:shadow-none">
                        <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <svg class="w-7 h-7 text-purple-500 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                            </svg>
                            Crear Factura con AI
                        </h1>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Selecciona el cliente y la AI genera la factura</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    @include('partials.walee-dark-mode-toggle')
                    <div class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center shadow-sm dark:shadow-none">
                        <span class="text-sm font-medium text-walee-600 dark:text-walee-400">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                </div>
            </header>
            
            <!-- Notifications -->
            <div id="notifications" class="fixed top-4 right-4 z-50 space-y-2"></div>
            
            <!-- Step 1: Select Client -->
            <div id="step1" class="animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="bg-gradient-to-br from-purple-500/10 to-purple-600/5 border border-purple-500/20 rounded-2xl p-6 mb-6">
                    <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-purple-500/20 flex items-center justify-center text-purple-400 font-bold">1</span>
                        Seleccionar Cliente
                    </h2>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-300 mb-2">Cliente <span class="text-red-400">*</span></label>
                        <select id="cliente_id" class="w-full px-4 py-3 bg-slate-900/50 border border-slate-600 rounded-xl text-white focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 focus:outline-none transition-all">
                            <option value="">Seleccionar cliente...</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" 
                                        data-nombre="{{ $cliente->nombre_empresa }}"
                                        data-email="{{ $cliente->correo }}"
                                        data-industria="{{ $cliente->industria }}"
                                        data-descripcion="{{ $cliente->descripcion }}">
                                    {{ $cliente->nombre_empresa }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Selected Client Info -->
                    <div id="clienteInfo" class="hidden mb-4 p-4 bg-slate-800/50 rounded-xl border border-purple-500/20">
                        <div class="flex items-center gap-4">
                            <div id="clienteAvatar" class="w-14 h-14 rounded-xl bg-purple-500/20 flex items-center justify-center">
                                <span class="text-xl font-bold text-purple-400">?</span>
                            </div>
                            <div class="flex-1">
                                <p id="clienteNombre" class="font-semibold text-white">Cliente</p>
                                <p id="clienteEmail" class="text-sm text-slate-400">email@ejemplo.com</p>
                                <p id="clienteIndustria" class="text-xs text-purple-400 mt-1"></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-300 mb-2">Instrucciones adicionales (opcional)</label>
                        <textarea id="instrucciones" rows="2" placeholder="Ej: Factura por diseño de logo, incluir mantenimiento mensual..." class="w-full px-4 py-3 bg-slate-900/50 border border-slate-600 rounded-xl text-white placeholder-slate-500 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 focus:outline-none transition-all resize-none"></textarea>
                    </div>
                    
                    <button type="button" id="generateBtn" onclick="generateWithAI()" class="w-full px-6 py-4 bg-purple-600 hover:bg-purple-500 text-white font-semibold rounded-xl transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                        </svg>
                        <span>Generar Factura con AI</span>
                    </button>
                </div>
            </div>
            
            <!-- Step 2: Review & Edit -->
            <div id="step2" class="hidden animate-fade-in-up">
                <form id="facturaForm" class="space-y-6">
                    @csrf
                    <input type="hidden" id="form_cliente_id" name="cliente_id">
                    
                    <!-- Generated Invoice Preview -->
                    <div class="bg-gradient-to-br from-emerald-500/10 to-emerald-600/5 border border-emerald-500/20 rounded-2xl p-6">
                        <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-400 font-bold">2</span>
                            Revisar y Editar
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Correo</label>
                                <input type="email" id="correo" name="correo" class="w-full px-4 py-3 bg-slate-900/50 border border-slate-600 rounded-xl text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Número de Factura</label>
                                <input type="text" id="numero_factura" name="numero_factura" value="{{ str_pad($siguienteNumero, 4, '0', STR_PAD_LEFT) }}" class="w-full px-4 py-3 bg-slate-900/50 border border-slate-600 rounded-xl text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-300 mb-2">Concepto <span class="text-emerald-400">(Generado por AI)</span></label>
                            <textarea id="concepto" name="concepto" rows="4" class="w-full px-4 py-3 bg-slate-900/50 border border-emerald-500/30 rounded-xl text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all resize-none"></textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Total <span class="text-emerald-400">(Sugerido)</span></label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">₡</span>
                                    <input type="number" step="0.01" id="total" name="total" class="w-full pl-8 pr-4 py-3 bg-slate-900/50 border border-emerald-500/30 rounded-xl text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Fecha Emisión</label>
                                <input type="date" id="fecha_emision" name="fecha_emision" value="{{ date('Y-m-d') }}" class="w-full px-4 py-3 bg-slate-900/50 border border-slate-600 rounded-xl text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Fecha Vencimiento</label>
                                <input type="date" id="fecha_vencimiento" name="fecha_vencimiento" value="{{ date('Y-m-d', strtotime('+30 days')) }}" class="w-full px-4 py-3 bg-slate-900/50 border border-slate-600 rounded-xl text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-300 mb-2">Notas <span class="text-emerald-400">(Generado por AI)</span></label>
                            <textarea id="notas" name="notas" rows="2" class="w-full px-4 py-3 bg-slate-900/50 border border-emerald-500/30 rounded-xl text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all resize-none"></textarea>
                        </div>
                        
                        <!-- Hidden fields -->
                        <input type="hidden" name="subtotal" id="subtotal">
                        <input type="hidden" name="serie" value="A">
                        <input type="hidden" name="estado" value="pendiente">
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex gap-4">
                        <button type="button" onclick="resetForm()" class="px-6 py-4 bg-slate-800 hover:bg-slate-700 text-white font-medium rounded-xl transition-all flex items-center justify-center gap-2 border border-slate-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span>Regenerar</span>
                        </button>
                        
                        <button type="submit" id="submitBtn" class="flex-1 px-6 py-4 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Crear Factura</span>
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Footer -->
            <footer class="text-center py-8 mt-8">
                <p class="text-sm text-slate-500">
                    <span class="text-walee-400 font-medium">Walee</span> · websolutions.work
                </p>
            </footer>
        </div>
    </div>
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let selectedCliente = null;
        
        // Client selection
        document.getElementById('cliente_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            if (this.value) {
                selectedCliente = {
                    id: this.value,
                    nombre: selectedOption.dataset.nombre,
                    email: selectedOption.dataset.email,
                    industria: selectedOption.dataset.industria,
                    descripcion: selectedOption.dataset.descripcion,
                };
                
                document.getElementById('clienteAvatar').innerHTML = `<span class="text-xl font-bold text-purple-400">${selectedCliente.nombre.charAt(0).toUpperCase()}</span>`;
                document.getElementById('clienteNombre').textContent = selectedCliente.nombre;
                document.getElementById('clienteEmail').textContent = selectedCliente.email || 'Sin email';
                document.getElementById('clienteIndustria').textContent = selectedCliente.industria || '';
                document.getElementById('clienteInfo').classList.remove('hidden');
            } else {
                selectedCliente = null;
                document.getElementById('clienteInfo').classList.add('hidden');
            }
        });
        
        // Generate with AI
        async function generateWithAI() {
            if (!selectedCliente) {
                showNotification('Error', 'Selecciona un cliente primero', 'error');
                return;
            }
            
            const generateBtn = document.getElementById('generateBtn');
            const instrucciones = document.getElementById('instrucciones').value;
            
            generateBtn.disabled = true;
            generateBtn.innerHTML = `
                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Generando con AI...</span>
            `;
            
            try {
                const response = await fetch('{{ route("walee.facturas.generar-ai") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        cliente_nombre: selectedCliente.nombre,
                        cliente_industria: selectedCliente.industria,
                        cliente_descripcion: selectedCliente.descripcion,
                        instrucciones: instrucciones,
                    }),
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Fill the form
                    document.getElementById('form_cliente_id').value = selectedCliente.id;
                    document.getElementById('correo').value = selectedCliente.email || '';
                    document.getElementById('concepto').value = data.concepto;
                    document.getElementById('total').value = data.total;
                    document.getElementById('subtotal').value = data.total;
                    document.getElementById('notas').value = data.notas || '';
                    
                    // Show step 2
                    document.getElementById('step1').classList.add('hidden');
                    document.getElementById('step2').classList.remove('hidden');
                    
                    showNotification('Factura generada', 'Revisa y ajusta los datos antes de crear', 'success');
                } else {
                    showNotification('Error', data.message || 'Error al generar factura', 'error');
                }
            } catch (error) {
                showNotification('Error', 'Error de conexión: ' + error.message, 'error');
            } finally {
                generateBtn.disabled = false;
                generateBtn.innerHTML = `
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                    </svg>
                    <span>Generar Factura con AI</span>
                `;
            }
        }
        
        // Reset form
        function resetForm() {
            document.getElementById('step2').classList.add('hidden');
            document.getElementById('step1').classList.remove('hidden');
        }
        
        // Form submission
        document.getElementById('facturaForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const formData = new FormData(this);
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Creando...</span>
            `;
            
            try {
                const response = await fetch('{{ route("walee.facturas.guardar") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: formData,
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification('Factura creada', 'La factura ha sido creada correctamente', 'success');
                    setTimeout(() => {
                        window.location.href = '{{ route("walee.facturas") }}';
                    }, 1500);
                } else {
                    showNotification('Error', data.message || 'Error al crear factura', 'error');
                }
            } catch (error) {
                showNotification('Error', 'Error de conexión: ' + error.message, 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = `
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Crear Factura</span>
                `;
            }
        });
        
        function showNotification(title, body, type = 'info') {
            const container = document.getElementById('notifications');
            const id = 'notif-' + Date.now();
            
            const bgClass = {
                'success': 'bg-emerald-600',
                'error': 'bg-red-600',
                'info': 'bg-blue-600',
            }[type] || 'bg-slate-600';
            
            const notification = document.createElement('div');
            notification.id = id;
            notification.className = `${bgClass} text-white px-4 py-3 rounded-xl shadow-lg transform translate-x-full transition-transform duration-300`;
            notification.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="flex-1">
                        <p class="font-medium text-sm">${title}</p>
                        <p class="text-xs opacity-90 mt-0.5">${body}</p>
                    </div>
                    <button onclick="document.getElementById('${id}').remove()" class="text-white/70 hover:text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;
            
            container.appendChild(notification);
            setTimeout(() => notification.classList.remove('translate-x-full'), 10);
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        }
    </script>
    @include('partials.walee-support-button')
</body>
</html>
