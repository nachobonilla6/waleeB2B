<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Emails Recibidos</title>
    <meta name="description" content="Walee - Bandeja de Entrada">
    <meta name="theme-color" content="#D59F3B">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    @include('partials.walee-dark-mode-init')
    @include('partials.walee-violet-light-mode')
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
        
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        .animate-spin {
            animation: spin 1s linear infinite;
        }
        
        .email-card {
            opacity: 0;
            animation: fadeInUp 0.5s ease-out forwards;
        }
        
        .email-html-content {
            width: 100%;
            overflow-x: auto;
        }
        
        .email-html-content img {
            max-width: 100%;
            height: auto;
        }
        
        .email-html-content table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .email-html-content table td,
        .email-html-content table th {
            padding: 8px;
            border: 1px solid #e2e8f0;
        }
        
        /* Ajustes para mobile - hacer contenido más ancho */
        @media (max-width: 640px) {
            #emailModal {
                padding: 0 !important;
            }
            
            #emailModal > div {
                border-radius: 0 !important;
                height: 100vh !important;
                max-height: 100vh !important;
            }
            
            #modalContent {
                padding: 0.5rem !important;
                max-height: calc(100vh - 80px) !important;
            }
            
            #modalContent > div > div {
                padding: 0.5rem !important;
            }
            
            .email-html-content {
                padding: 0 !important;
                margin: 0 !important;
            }
            
            .email-html-content * {
                max-width: 100% !important;
            }
            
            .email-html-content img {
                width: 100% !important;
                height: auto !important;
            }
            
            .email-html-content p,
            .email-html-content div,
            .email-html-content span {
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
            }
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
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">
    @php
        try {
            // Total de emails
            $totalEmails = \App\Models\EmailRecibido::count();
            
            // Contar emails no leídos
            $noLeidos = \App\Models\EmailRecibido::where('is_read', false)->count();
            
            // Obtener emails con paginación de 5, ordenados por más recientes primero
            $emails = \App\Models\EmailRecibido::orderByRaw('COALESCE(received_at, created_at, updated_at) DESC')
                ->paginate(5);
        } catch (\Exception $e) {
            // En caso de error, mostrar emails con paginación
            $emails = \App\Models\EmailRecibido::orderByRaw('COALESCE(received_at, created_at, updated_at) DESC')
                ->paginate(5);
            $totalEmails = \App\Models\EmailRecibido::count();
            $noLeidos = \App\Models\EmailRecibido::where('is_read', false)->count();
        }
    @endphp

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-400/20 dark:bg-emerald-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-walee-400/10 dark:bg-walee-400/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-emerald-400/20 dark:bg-emerald-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-4 py-6 sm:px-6 lg:px-8">
            @php 
                $pageTitle = 'Emails Recibidos';
                if($noLeidos > 0) {
                    $pageTitle .= ' (' . $noLeidos . ' sin leer)';
                }
            @endphp
            @include('partials.walee-navbar')
            
            <!-- Header -->
            <header class="flex items-center justify-between mb-8 animate-fade-in-up">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">
                        Emails Recibidos
                        @if($noLeidos > 0)
                            <span class="ml-2 px-2.5 py-1 text-sm font-semibold bg-emerald-500 text-white rounded-full">{{ $noLeidos }} sin leer</span>
                        @endif
                    </h1>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Bandeja de entrada</p>
                </div>
                <button onclick="window.open('https://mail.google.com', '_blank')" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-medium rounded-xl transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    <span class="hidden sm:inline">Ir a Gmail</span>
                </button>
            </header>
            
            <!-- Notifications -->
            <div id="notifications" class="fixed top-4 right-4 z-50 space-y-2"></div>
            
            <!-- Pagination -->
            @if($emails->hasPages())
                <div class="mb-6 flex justify-center gap-2">
                    @if($emails->onFirstPage())
                        <span class="px-4 py-2 bg-slate-100 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 rounded-xl cursor-not-allowed">Anterior</span>
                    @else
                        <a href="{{ $emails->previousPageUrl() }}" class="px-4 py-2 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-900 dark:text-white rounded-xl transition-colors border border-slate-200 dark:border-slate-700 shadow-sm dark:shadow-none">Anterior</a>
                    @endif
                    
                    <span class="px-4 py-2 bg-slate-100 dark:bg-slate-800/50 text-slate-600 dark:text-slate-400 rounded-xl border border-slate-200 dark:border-slate-700">
                        Página {{ $emails->currentPage() }} de {{ $emails->lastPage() }}
                    </span>
                    
                    @if($emails->hasMorePages())
                        <a href="{{ $emails->nextPageUrl() }}" class="px-4 py-2 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-900 dark:text-white rounded-xl transition-colors border border-slate-200 dark:border-slate-700 shadow-sm dark:shadow-none">Siguiente</a>
                    @else
                        <span class="px-4 py-2 bg-slate-100 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 rounded-xl cursor-not-allowed">Siguiente</span>
                    @endif
                </div>
            @endif
            
            <!-- Email List -->
            <div class="space-y-3 animate-fade-in-up">
                @forelse($emails as $index => $email)
                    <div 
                        class="email-card group bg-white dark:bg-slate-800/50 border {{ $email->is_read ? 'border-slate-200 dark:border-slate-700' : 'border-emerald-300 dark:border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/5' }} rounded-2xl p-4 hover:border-emerald-400 dark:hover:border-emerald-500/50 transition-all cursor-pointer shadow-sm dark:shadow-none" 
                        style="animation-delay: {{ $index * 0.05 }}s" 
                        onclick="showEmailDetail({{ $email->id }})"
                    >
                        <div class="flex items-start gap-4">
                            <!-- Unread indicator -->
                            <div class="flex-shrink-0 mt-1">
                                @if(!$email->is_read)
                                    <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                                @else
                                    <div class="w-3 h-3 rounded-full bg-slate-400 dark:bg-slate-600"></div>
                                @endif
                            </div>
                            
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 flex-shrink-0 flex items-center justify-center">
                                <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">{{ strtoupper(substr($email->from_name ?? $email->from_email, 0, 1)) }}</span>
                            </div>
                            
                            <div class="flex-1 min-w-0 overflow-hidden">
                                <div class="flex items-start justify-between gap-2 mb-1">
                                    <h3 class="font-semibold {{ $email->is_read ? 'text-slate-700 dark:text-slate-300' : 'text-slate-900 dark:text-white' }} truncate flex-1 min-w-0">
                                        {{ $email->from_name ?? $email->from_email }}
                                    </h3>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        @if($email->is_starred)
                                            <svg class="w-4 h-4 text-walee-600 dark:text-walee-400 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                            </svg>
                                        @endif
                                        <span class="text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap flex-shrink-0">
                                            @php
                                                $date = $email->received_at ?? $email->created_at ?? $email->updated_at ?? now();
                                                $dateObj = $date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::parse($date);
                                            @endphp
                                            {{ $dateObj->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                                <p class="text-sm {{ $email->is_read ? 'text-slate-600 dark:text-slate-400' : 'text-slate-900 dark:text-white font-medium' }} truncate mb-1">
                                    {{ $email->subject }}
                                </p>
                                <p class="text-sm text-slate-600 dark:text-slate-500 line-clamp-2 overflow-hidden">{{ Str::limit(strip_tags($email->body ?? ''), 120) }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16">
                        <div class="w-16 h-16 mx-auto rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 13.5h3.86a2.25 2.25 0 012.012 1.244l.256.512a2.25 2.25 0 002.013 1.244h3.218a2.25 2.25 0 002.013-1.244l.256-.512a2.25 2.25 0 012.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 00-2.15-1.588H6.911a2.25 2.25 0 00-2.15 1.588L2.35 13.177a2.25 2.25 0 00-.1.661z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Bandeja de entrada vacía</h3>
                        <p class="text-slate-600 dark:text-slate-500">No hay emails recibidos todavía</p>
                    </div>
                @endforelse
            </div>
            
            
            <!-- Footer -->
            <footer class="text-center py-8 mt-8">
                <p class="text-sm text-slate-600 dark:text-slate-500">
                    <span class="text-walee-600 dark:text-walee-400 font-medium">Walee</span> · websolutions.work
                </p>
            </footer>
        </div>
    </div>
    
    <!-- Email Detail Modal -->
    <div id="emailModal" class="fixed inset-0 bg-black/60 dark:bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-0 sm:p-4">
        <div class="bg-white dark:bg-slate-900 rounded-none sm:rounded-2xl border-0 sm:border border-slate-200 dark:border-slate-700 max-w-5xl w-full h-full sm:h-auto sm:max-h-[90vh] overflow-hidden shadow-xl">
            <div class="flex items-center justify-between p-3 sm:p-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-base sm:text-lg font-semibold text-slate-900 dark:text-white truncate pr-2 sm:pr-4" id="modalSubject">Email</h3>
                <div class="flex items-center gap-2">
                    <button onclick="openGmail()" class="px-3 py-1.5 rounded-lg bg-blue-100 dark:bg-blue-500/20 hover:bg-blue-200 dark:hover:bg-blue-500/30 text-blue-600 dark:text-blue-400 text-sm font-medium transition-colors flex items-center gap-1.5" title="Ir a Gmail">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        <span class="hidden sm:inline">Gmail</span>
                    </button>
                    <button onclick="replyInGmail()" class="px-3 py-1.5 rounded-lg bg-emerald-100 dark:bg-emerald-500/20 hover:bg-emerald-200 dark:hover:bg-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-sm font-medium transition-colors flex items-center gap-1.5" title="Responder en Gmail">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                        <span class="hidden sm:inline">Responder</span>
                    </button>
                    <button onclick="toggleStar()" id="starBtn" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" id="starIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </button>
                    <button onclick="closeModal()" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="p-2 sm:p-4 overflow-y-auto max-h-[calc(100vh-80px)] sm:max-h-[70vh]" id="modalContent">
                <!-- Modal content will be inserted here -->
            </div>
        </div>
    </div>
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let currentEmailId = null;
        
        // Email data
        const emailsData = @json($emails->items());
        
        function showEmailDetail(emailId) {
            currentEmailId = emailId;
            const email = emailsData.find(e => e.id === emailId);
            if (!email) return;
            
            // Mark as read
            if (!email.is_read) {
                markAsRead(emailId);
                email.is_read = true;
            }
            
            document.getElementById('modalSubject').textContent = email.subject;
            
            // Update star icon
            const starIcon = document.getElementById('starIcon');
            if (email.is_starred) {
                starIcon.setAttribute('fill', 'currentColor');
                starIcon.classList.remove('text-slate-600', 'dark:text-slate-400');
                starIcon.classList.add('text-walee-600', 'dark:text-walee-400');
            } else {
                starIcon.setAttribute('fill', 'none');
                starIcon.classList.remove('text-walee-600', 'dark:text-walee-400');
                starIcon.classList.add('text-slate-600', 'dark:text-slate-400');
            }
            
            const receivedDate = email.received_at ? new Date(email.received_at) : new Date(email.created_at);
            
            document.getElementById('modalContent').innerHTML = `
                <div class="space-y-3 sm:space-y-4">
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-3 sm:p-4 border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center">
                                <span class="text-lg font-bold text-emerald-600 dark:text-emerald-400">${(email.from_name || email.from_email).charAt(0).toUpperCase()}</span>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-slate-900 dark:text-white">${email.from_name || 'Sin nombre'}</h4>
                                <p class="text-sm text-emerald-600 dark:text-emerald-400">${email.from_email}</p>
                            </div>
                        </div>
                        <div class="text-xs text-slate-600 dark:text-slate-500">
                            ${receivedDate.toLocaleString('es-ES', { 
                                weekday: 'long',
                                year: 'numeric', 
                                month: 'long', 
                                day: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            })}
                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-1 sm:p-4 border border-slate-200 dark:border-slate-700">
                        <div class="prose prose-slate dark:prose-invert prose-sm max-w-none email-html-content" style="padding: 0 !important; margin: 0 !important;">
                            ${email.body_html || email.body.replace(/\n/g, '<br>')}
                        </div>
                    </div>
                    ${email.attachments && email.attachments.length > 0 ? `
                        <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-4 border border-slate-200 dark:border-slate-700">
                            <h4 class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-3">Archivos adjuntos</h4>
                            <div class="space-y-2">
                                ${email.attachments.map(att => `
                                    <div class="flex items-center gap-3 p-2 bg-slate-100 dark:bg-slate-700/50 rounded-lg">
                                        <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                        </svg>
                                        <span class="text-sm text-slate-900 dark:text-white">${att.name || att}</span>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    ` : ''}
                </div>
            `;
            document.getElementById('emailModal').classList.remove('hidden');
        }
        
        async function markAsRead(emailId) {
            try {
                await fetch(`{{ url('/walee-emails/recibidos') }}/${emailId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });
            } catch (error) {
                console.error('Error marking as read:', error);
            }
        }
        
        async function toggleStar() {
            if (!currentEmailId) return;
            
            try {
                const response = await fetch(`{{ url('/walee-emails/recibidos') }}/${currentEmailId}/star`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });
                
                const data = await response.json();
                
                const email = emailsData.find(e => e.id === currentEmailId);
                if (email) {
                    email.is_starred = data.is_starred;
                }
                
                const starIcon = document.getElementById('starIcon');
                if (data.is_starred) {
                    starIcon.setAttribute('fill', 'currentColor');
                    starIcon.classList.remove('text-slate-600', 'dark:text-slate-400');
                    starIcon.classList.add('text-walee-600', 'dark:text-walee-400');
                } else {
                    starIcon.setAttribute('fill', 'none');
                    starIcon.classList.remove('text-walee-600', 'dark:text-walee-400');
                    starIcon.classList.add('text-slate-600', 'dark:text-slate-400');
                }
            } catch (error) {
                console.error('Error toggling star:', error);
            }
        }
        
        async function syncEmails() {
            const syncBtn = document.getElementById('syncBtn');
            
            syncBtn.disabled = true;
            syncBtn.innerHTML = `
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="hidden sm:inline">Sincronizando...</span>
            `;
            
            try {
                const response = await fetch('{{ route("walee.emails.recibidos.sync") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification('Sincronización completa', data.message || 'Emails sincronizados correctamente', 'success');
                    // Reload page to show new emails
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showNotification('Error', data.message || 'Error al sincronizar', 'error');
                }
            } catch (error) {
                showNotification('Error', 'Error de conexión: ' + error.message, 'error');
            } finally {
                syncBtn.disabled = false;
                syncBtn.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span class="hidden sm:inline">Sincronizar</span>
                `;
            }
        }
        
        function closeModal() {
            document.getElementById('emailModal').classList.add('hidden');
            currentEmailId = null;
        }
        
        function openGmail() {
            window.open('https://mail.google.com', '_blank');
        }
        
        function replyInGmail() {
            const email = emailsData.find(e => e.id === currentEmailId);
            if (!email) return;
            
            // Construir URL de Gmail para responder
            const to = encodeURIComponent(email.from_email || '');
            const subject = encodeURIComponent('Re: ' + (email.subject || ''));
            
            // Usar el HTML del email si está disponible, sino usar el texto plano
            let originalBody = '';
            if (email.body_html) {
                // Usar el HTML directamente
                originalBody = email.body_html;
            } else if (email.body) {
                // Si no hay HTML, usar el texto plano y convertirlo a HTML básico
                originalBody = email.body.replace(/\n/g, '<br>');
            }
            
            // Preparar el cuerpo con el mensaje original en HTML
            const bodyContent = originalBody ? `\n\n--- Mensaje original ---\n${originalBody}` : '';
            const body = encodeURIComponent(bodyContent);
            
            // URL de Gmail compose con parámetros
            // Gmail soporta HTML en el body usando el parámetro view=cm
            const gmailUrl = `https://mail.google.com/mail/?view=cm&to=${to}&su=${subject}&body=${body}`;
            
            window.open(gmailUrl, '_blank');
        }
        
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
            
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 10);
            
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 5000);
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
    @include('partials.walee-support-button')
</body>
</html>

