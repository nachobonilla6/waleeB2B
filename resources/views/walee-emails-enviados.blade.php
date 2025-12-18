<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Emails Enviados</title>
    <meta name="description" content="Walee - Historial de Emails Enviados">
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
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        .email-card {
            opacity: 0;
            animation: fadeInUp 0.5s ease-out forwards;
        }
        
        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(213, 159, 59, 0.3);
            border-radius: 3px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(213, 159, 59, 0.5);
        }
    </style>
</head>
<body class="bg-slate-950 text-white min-h-screen">
    @php
        $emails = \App\Models\PropuestaPersonalizada::with('cliente')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Contadores
        $totalEmails = \App\Models\PropuestaPersonalizada::count();
        $emailsEsteMes = \App\Models\PropuestaPersonalizada::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $emailsHoy = \App\Models\PropuestaPersonalizada::whereDate('created_at', today())->count();
    @endphp

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-walee-400/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-blue-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-4xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <!-- Header -->
            <header class="flex items-center justify-between mb-8 animate-fade-in-up">
                <div class="flex items-center gap-4">
                    <a href="{{ route('walee.emails') }}" class="w-10 h-10 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 flex items-center justify-center transition-all">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white flex items-center gap-2">
                            <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                            </svg>
                            Emails Enviados
                        </h1>
                        <p class="text-sm text-slate-400">{{ $emails->total() }} propuestas enviadas</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-2">
                    <a href="{{ route('walee.emails.crear') }}" class="px-4 py-2 bg-violet-600 hover:bg-violet-500 text-white font-medium rounded-xl transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span class="hidden sm:inline">Nuevo</span>
                    </a>
                </div>
            </header>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-3 gap-3 mb-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="bg-blue-500/10 border border-blue-500/20 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-blue-400">{{ $totalEmails }}</div>
                    <div class="text-xs text-blue-400/70">Total</div>
                </div>
                <div class="bg-violet-500/10 border border-violet-500/20 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-violet-400">{{ $emailsEsteMes }}</div>
                    <div class="text-xs text-violet-400/70">Este mes</div>
                </div>
                <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-emerald-400">{{ $emailsHoy }}</div>
                    <div class="text-xs text-emerald-400/70">Hoy</div>
                </div>
            </div>
            
            <!-- Email List -->
            <div class="space-y-4 animate-fade-in-up">
                @forelse($emails as $index => $email)
                    <div class="email-card bg-slate-800/50 border border-slate-700 rounded-2xl p-4 hover:border-blue-500/30 transition-all cursor-pointer" style="animation-delay: {{ $index * 0.05 }}s" onclick="showEmailDetail({{ $email->id }})">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex-shrink-0 flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <h3 class="font-semibold text-white truncate">{{ $email->subject }}</h3>
                                    <span class="text-xs text-slate-500 flex-shrink-0 ml-2">{{ $email->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-slate-400 mb-2">
                                    <span class="text-blue-400">{{ $email->email }}</span>
                                    @if($email->cliente_nombre)
                                        <span class="text-slate-500"> · {{ $email->cliente_nombre }}</span>
                                    @endif
                                </p>
                                <p class="text-sm text-slate-500 line-clamp-2">{{ Str::limit(strip_tags($email->body), 120) }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16">
                        <div class="w-16 h-16 mx-auto rounded-full bg-slate-800 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-white mb-2">No hay emails enviados</h3>
                        <p class="text-slate-500 mb-4">Aún no has enviado ninguna propuesta personalizada</p>
                        <a href="{{ route('walee.emails.crear') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-violet-600 hover:bg-violet-500 text-white font-medium rounded-xl transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Crear primer email
                        </a>
                    </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            @if($emails->hasPages())
                <div class="mt-8 flex justify-center gap-2">
                    @if($emails->onFirstPage())
                        <span class="px-4 py-2 bg-slate-800/50 text-slate-500 rounded-xl cursor-not-allowed">Anterior</span>
                    @else
                        <a href="{{ $emails->previousPageUrl() }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl transition-colors">Anterior</a>
                    @endif
                    
                    <span class="px-4 py-2 bg-slate-800/50 text-slate-400 rounded-xl">
                        Página {{ $emails->currentPage() }} de {{ $emails->lastPage() }}
                    </span>
                    
                    @if($emails->hasMorePages())
                        <a href="{{ $emails->nextPageUrl() }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl transition-colors">Siguiente</a>
                    @else
                        <span class="px-4 py-2 bg-slate-800/50 text-slate-500 rounded-xl cursor-not-allowed">Siguiente</span>
                    @endif
                </div>
            @endif
            
            <!-- Footer -->
            <footer class="text-center py-8 mt-8">
                <p class="text-sm text-slate-500">
                    <span class="text-walee-400 font-medium">Walee</span> · websolutions.work
                </p>
            </footer>
        </div>
    </div>
    
    <!-- Email Detail Modal -->
    <div id="emailModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-slate-900 rounded-2xl border border-slate-700 max-w-2xl w-full max-h-[90vh] overflow-hidden">
            <div class="flex items-center justify-between p-4 border-b border-slate-700">
                <h3 class="text-lg font-semibold text-white" id="modalSubject">Email</h3>
                <button onclick="closeModal()" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 flex items-center justify-center">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-4 overflow-y-auto max-h-[70vh]" id="modalContent">
                <!-- Modal content will be inserted here -->
            </div>
        </div>
    </div>
    
    <script>
        // Email data
        const emailsData = @json($emails->items());
        
        function showEmailDetail(emailId) {
            const email = emailsData.find(e => e.id === emailId);
            if (!email) return;
            
            document.getElementById('modalSubject').textContent = email.subject;
            document.getElementById('modalContent').innerHTML = `
                <div class="space-y-4">
                    <div class="bg-slate-800 rounded-xl p-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <h4 class="text-xs font-medium text-slate-400 mb-1">Para</h4>
                                <p class="text-white">${email.email}</p>
                            </div>
                            <div>
                                <h4 class="text-xs font-medium text-slate-400 mb-1">Cliente</h4>
                                <p class="text-white">${email.cliente_nombre || 'N/A'}</p>
                            </div>
                            <div class="col-span-2">
                                <h4 class="text-xs font-medium text-slate-400 mb-1">Enviado</h4>
                                <p class="text-white">${new Date(email.created_at).toLocaleString('es-ES', { 
                                    year: 'numeric', 
                                    month: 'long', 
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })}</p>
                            </div>
                        </div>
                    </div>
                    ${email.ai_prompt ? `
                        <div class="bg-violet-500/10 border border-violet-500/20 rounded-xl p-4">
                            <h4 class="text-xs font-medium text-violet-400 mb-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                                </svg>
                                Prompt de AI
                            </h4>
                            <p class="text-sm text-slate-300">${email.ai_prompt}</p>
                        </div>
                    ` : ''}
                    <div class="bg-slate-800 rounded-xl p-4">
                        <h4 class="text-xs font-medium text-slate-400 mb-2">Mensaje</h4>
                        <div class="text-white whitespace-pre-wrap text-sm">${email.body}</div>
                    </div>
                </div>
            `;
            document.getElementById('emailModal').classList.remove('hidden');
        }
        
        function closeModal() {
            document.getElementById('emailModal').classList.add('hidden');
        }
        
        // Close modal on escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
        
        // Close modal on backdrop click
        document.getElementById('emailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>

