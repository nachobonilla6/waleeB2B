<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Emails</title>
    <meta name="description" content="Walee - Gestión de Emails">
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
        
        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(213, 159, 59, 0.3);
            }
            50% {
                box-shadow: 0 0 40px rgba(213, 159, 59, 0.5);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        .option-card {
            opacity: 0;
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        .option-card:nth-child(1) { animation-delay: 0.1s; }
        .option-card:nth-child(2) { animation-delay: 0.2s; }
        
        .walee-gradient {
            background: linear-gradient(135deg, #D59F3B 0%, #E0C684 50%, #C78F2E 100%);
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
    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-400/20 dark:bg-blue-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-violet-400/10 dark:bg-violet-400/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-blue-400/20 dark:bg-blue-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-4 py-6 sm:px-6 lg:px-8">
            @php $pageTitle = 'Emails'; @endphp
            @include('partials.walee-navbar')
            <!-- Header -->
            <header class="flex items-center justify-between mb-10 animate-fade-in-up">
                <div class="flex items-center gap-4">
                    <a href="{{ route('walee.dashboard') }}" class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 flex items-center justify-center transition-all shadow-sm dark:shadow-none">
                        <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">
                            Emails
                        </h1>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Gestión de correos electrónicos</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    @include('partials.walee-dark-mode-toggle')
                    <div class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center shadow-sm dark:shadow-none">
                        <span class="text-sm font-medium text-walee-600 dark:text-walee-400">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                </div>
            </header>
            
            @php
                // Obtener correos de clientes con estado pending (normalizados a minúsculas y sin espacios)
                $clientesEmails = \App\Models\Client::where('estado', 'pending')
                    ->whereNotNull('email')
                    ->where('email', '!=', '')
                    ->pluck('email')
                    ->map(function($email) {
                        return strtolower(trim($email));
                    })
                    ->filter()
                    ->unique()
                    ->values()
                    ->toArray();
                
                // Contar solo emails de clientes con estado pending
                if (!empty($clientesEmails) && count($clientesEmails) > 0) {
                    $emailsQuery = \App\Models\EmailRecibido::where(function($query) use ($clientesEmails) {
                        foreach ($clientesEmails as $clienteEmail) {
                            $query->orWhereRaw('
                                CASE 
                                    WHEN from_email LIKE "%<%" THEN 
                                        LOWER(TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(from_email, "<", -1), ">", 1))) = ?
                                    ELSE 
                                        LOWER(TRIM(from_email)) = ?
                                END
                            ', [$clienteEmail, $clienteEmail]);
                        }
                    });
                    $totalRecibidos = $emailsQuery->count();
                } else {
                    $totalRecibidos = 0;
                }
                
                $totalEnviados = \App\Models\PropuestaPersonalizada::count();
                $totalTemplates = \App\Models\EmailTemplate::where('user_id', auth()->id())->count();
            @endphp
            
            <!-- Options Grid -->
            <div class="grid grid-cols-1 gap-6">
                <!-- Crear con AI -->
                <a href="{{ route('walee.emails.crear') }}" class="option-card group relative overflow-hidden rounded-3xl bg-gradient-to-br from-violet-50 to-violet-100/50 dark:from-violet-500/10 dark:to-violet-600/5 border border-violet-200 dark:border-violet-500/20 p-8 hover:border-violet-400 dark:hover:border-violet-400/50 hover:from-violet-100 dark:hover:from-violet-500/15 hover:to-violet-200/50 dark:hover:to-violet-600/10 transition-all duration-500 shadow-sm dark:shadow-none">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-violet-500/20 dark:bg-violet-500/10 rounded-full blur-3xl transform translate-x-20 -translate-y-20 group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative flex items-center gap-6">
                        <div class="w-20 h-20 rounded-2xl bg-violet-100 dark:bg-violet-500/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-10 h-10 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2 group-hover:text-violet-700 dark:group-hover:text-violet-300 transition-colors">Crear con AI</h2>
                            <p class="text-slate-600 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors">Genera emails personalizados usando inteligencia artificial</p>
                        </div>
                        <div class="hidden sm:flex w-12 h-12 rounded-xl bg-violet-100 dark:bg-violet-500/10 items-center justify-center group-hover:bg-violet-200 dark:group-hover:bg-violet-500/20 transition-colors">
                            <svg class="w-6 h-6 text-violet-600 dark:text-violet-400 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
                
                <!-- Emails Recibidos -->
                <a href="{{ route('walee.emails.recibidos') }}" class="option-card group relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-50 to-emerald-100/50 dark:from-emerald-500/10 dark:to-emerald-600/5 border border-emerald-200 dark:border-emerald-500/20 p-8 hover:border-emerald-400 dark:hover:border-emerald-400/50 hover:from-emerald-100 dark:hover:from-emerald-500/15 hover:to-emerald-200/50 dark:hover:to-emerald-600/10 transition-all duration-500 shadow-sm dark:shadow-none">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/20 dark:bg-emerald-500/10 rounded-full blur-3xl transform translate-x-20 -translate-y-20 group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative flex items-center gap-6">
                        <div class="w-20 h-20 rounded-2xl bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-10 h-10 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 13.5h3.86a2.25 2.25 0 012.012 1.244l.256.512a2.25 2.25 0 002.013 1.244h3.218a2.25 2.25 0 002.013-1.244l.256-.512a2.25 2.25 0 012.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 00-2.15-1.588H6.911a2.25 2.25 0 00-2.15 1.588L2.35 13.177a2.25 2.25 0 00-.1.661z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2 group-hover:text-emerald-700 dark:group-hover:text-emerald-300 transition-colors">
                                Emails Recibidos
                                <span class="ml-2 px-2.5 py-1 text-sm font-semibold bg-emerald-500 text-white rounded-full">{{ $totalRecibidos }}</span>
                            </h2>
                            <p class="text-slate-600 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors">Ver bandeja de entrada y emails recibidos</p>
                        </div>
                        <div class="hidden sm:flex w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-500/10 items-center justify-center group-hover:bg-emerald-200 dark:group-hover:bg-emerald-500/20 transition-colors">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
                
                <!-- Ver Emails Enviados -->
                <a href="{{ route('walee.emails.enviados') }}" class="option-card group relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-50 to-blue-100/50 dark:from-blue-500/10 dark:to-blue-600/5 border border-blue-200 dark:border-blue-500/20 p-8 hover:border-blue-400 dark:hover:border-blue-400/50 hover:from-blue-100 dark:hover:from-blue-500/15 hover:to-blue-200/50 dark:hover:to-blue-600/10 transition-all duration-500 shadow-sm dark:shadow-none">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/20 dark:bg-blue-500/10 rounded-full blur-3xl transform translate-x-20 -translate-y-20 group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative flex items-center gap-6">
                        <div class="w-20 h-20 rounded-2xl bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-10 h-10 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2 group-hover:text-blue-700 dark:group-hover:text-blue-300 transition-colors">
                                Emails Enviados
                                <span class="ml-2 px-2.5 py-1 text-sm font-semibold bg-blue-500 text-white rounded-full">{{ $totalEnviados }}</span>
                            </h2>
                            <p class="text-slate-600 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors">Ver historial de propuestas y emails enviados</p>
                        </div>
                        <div class="hidden sm:flex w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-500/10 items-center justify-center group-hover:bg-blue-200 dark:group-hover:bg-blue-500/20 transition-colors">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
                
                <!-- Templates -->
                <a href="{{ route('walee.emails.templates') }}" class="option-card group relative overflow-hidden rounded-3xl bg-gradient-to-br from-walee-50 to-walee-100/50 dark:from-walee-500/10 dark:to-walee-600/5 border border-walee-200 dark:border-walee-500/20 p-8 hover:border-walee-400 dark:hover:border-walee-400/50 hover:from-walee-100 dark:hover:from-walee-500/15 hover:to-walee-200/50 dark:hover:to-walee-600/10 transition-all duration-500 shadow-sm dark:shadow-none">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-walee-500/20 dark:bg-walee-500/10 rounded-full blur-3xl transform translate-x-20 -translate-y-20 group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative flex items-center gap-6">
                        <div class="w-20 h-20 rounded-2xl bg-walee-100 dark:bg-walee-500/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-10 h-10 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2 group-hover:text-walee-700 dark:group-hover:text-walee-300 transition-colors">
                                Templates
                                <span class="ml-2 px-2.5 py-1 text-sm font-semibold bg-walee-500 text-white rounded-full">{{ $totalTemplates }}</span>
                            </h2>
                            <p class="text-slate-600 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors">Crea, guarda y envía templates de emails con AI</p>
                        </div>
                        <div class="hidden sm:flex w-12 h-12 rounded-xl bg-walee-100 dark:bg-walee-500/10 items-center justify-center group-hover:bg-walee-200 dark:group-hover:bg-walee-500/20 transition-colors">
                            <svg class="w-6 h-6 text-walee-600 dark:text-walee-400 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
            </div>
            
            <!-- Footer -->
            <footer class="text-center py-8 mt-8">
                <p class="text-sm text-slate-600 dark:text-slate-500">
                    <span class="text-walee-600 dark:text-walee-400 font-medium">Walee</span> · websolutions.work
                </p>
            </footer>
        </div>
    </div>
    @include('partials.walee-support-button')
</body>
</html>

