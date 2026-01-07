<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Clientes Extraídos</title>
    <meta name="description" content="Walee - Clientes Extraídos">
    <meta name="theme-color" content="#D59F3B">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    @include('partials.walee-dark-mode-init')
    @include('partials.walee-violet-light-mode')
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }
        
        .client-card {
            opacity: 0;
            animation: fadeInUp 0.4s ease-out forwards;
        }
        
        .client-card:nth-child(1) { animation-delay: 0.05s; }
        .client-card:nth-child(2) { animation-delay: 0.1s; }
        .client-card:nth-child(3) { animation-delay: 0.15s; }
        .client-card:nth-child(4) { animation-delay: 0.2s; }
        .client-card:nth-child(5) { animation-delay: 0.25s; }
        .client-card:nth-child(6) { animation-delay: 0.3s; }
        .client-card:nth-child(7) { animation-delay: 0.35s; }
        .client-card:nth-child(8) { animation-delay: 0.4s; }
        .client-card:nth-child(9) { animation-delay: 0.45s; }
        .client-card:nth-child(10) { animation-delay: 0.5s; }
        
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(213, 159, 59, 0.3); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(213, 159, 59, 0.5); }
        
        /* Mejorar botones de acción en mobile */
        @media (max-width: 640px) {
            /* Botones de acción en las tarjetas de clientes */
            .client-card .flex-shrink-0 button,
            .client-card .flex-shrink-0 div[title] {
                min-width: 44px !important;
                min-height: 44px !important;
                padding: 0.625rem !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
            
            .client-card .flex-shrink-0 button svg,
            .client-card .flex-shrink-0 div[title] svg {
                width: 1.25rem !important;
                height: 1.25rem !important;
            }
            
            /* Botones del header */
            header button {
                min-height: 44px !important;
                padding: 0.625rem 1rem !important;
                font-size: 0.875rem !important;
            }
            
            header button svg {
                width: 1.125rem !important;
                height: 1.125rem !important;
            }
            
            /* Permitir que el contenido del cliente se expanda completamente */
            .client-card > div {
                min-height: auto !important;
                height: auto !important;
            }
            
            .client-card a {
                overflow: visible !important;
                text-overflow: unset !important;
                white-space: normal !important;
            }
            
            .client-card p {
                overflow: visible !important;
                text-overflow: unset !important;
                white-space: normal !important;
                word-wrap: break-word !important;
                word-break: break-word !important;
            }
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">
    @php
        use App\Models\Client;
        use App\Models\PropuestaPersonalizada;
        use App\Models\EmailTemplate;
        
        $idiomaFilter = request()->get('idioma', '');
        
        $query = Client::whereIn('estado', ['pending', 'received']);
        
        // Aplicar filtro por idioma si existe
        if ($idiomaFilter) {
            $query->where('idioma', $idiomaFilter);
        }
        
        $clientes = $query->withCount('emails')
            ->orderBy('updated_at', 'desc')
            ->paginate(5)
            ->appends(request()->query());
        
        // Obtener conteo de propuestas por cliente
        $propuestasPorCliente = PropuestaPersonalizada::selectRaw('cliente_id, COUNT(*) as total')
            ->whereNotNull('cliente_id')
            ->groupBy('cliente_id')
            ->pluck('total', 'cliente_id')
            ->toArray();
        
        // Obtener templates de email del usuario
        $templates = EmailTemplate::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Obtener clientes en proceso con email
        $clientesEnProceso = Client::whereNotNull('email')
            ->where('email', '!=', '')
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'email']);
    @endphp

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-400/20 dark:bg-blue-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-walee-400/10 dark:bg-walee-400/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-blue-400/20 dark:bg-blue-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-3 py-4 sm:px-4 sm:py-6 lg:px-8">
            @php $pageTitle = 'Clientes Extraídos'; @endphp
            @include('partials.walee-navbar')
            
            <!-- Header -->
            <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-0 mb-3 sm:mb-4">
                <div>
                    <h1 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white">
                        Clientes Extraídos
                    </h1>
                </div>
                <div class="flex items-center gap-2">
                    <button 
                        id="actionsMenuBtn"
                        onclick="toggleActionsMenu()"
                        class="px-3 py-1.5 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white font-medium rounded-lg transition-all flex items-center gap-1.5 text-xs"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                        </svg>
                        <span>Acciones</span>
                    </button>
                    <button 
                        id="deleteSelectedBtn"
                        onclick="deleteSelectedClients()"
                        class="hidden px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-all flex items-center gap-1.5 text-xs shadow-sm"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <span id="deleteCount">Borrar (0)</span>
                    </button>
                </div>
            </header>
            
            <!-- Actions Menu (hidden by default) -->
            <div id="actionsMenu" class="hidden mb-3 bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-3 shadow-sm">
                <div class="flex items-center gap-2">
                    <label class="flex items-center gap-1.5 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input 
                            type="checkbox" 
                            id="selectAll"
                            onchange="toggleSelectAll(this.checked)"
                            class="w-3.5 h-3.5 rounded border-slate-300 dark:border-slate-600 text-emerald-500 focus:ring-emerald-500 focus:ring-offset-0"
                        >
                        <span>Seleccionar todos</span>
                    </label>
                </div>
            </div>
            
            <!-- Search Bar -->
            <div class="mb-3">
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-3 shadow-sm">
                    <div class="flex gap-2 items-center">
                        <form method="GET" action="{{ route('walee.clientes.proceso') }}" class="flex gap-2 flex-1">
                            <div class="relative flex-1">
                                <input 
                                    type="text" 
                                    id="searchInput"
                                    name="search"
                                    value="{{ request()->get('search', '') }}"
                                    placeholder="Buscar por nombre, email o teléfono..."
                                    class="w-full px-3 py-2 pl-9 rounded-lg bg-slate-100 dark:bg-slate-900/80 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500/50 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm"
                                >
                                <svg class="w-4 h-4 text-slate-400 dark:text-slate-500 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <select name="idioma" onchange="this.form.submit()" class="px-3 py-2 rounded-lg bg-slate-100 dark:bg-slate-900/80 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-white focus:outline-none focus:border-blue-500/50 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm">
                                <option value="">Todos los idiomas</option>
                                <option value="es" {{ $idiomaFilter == 'es' ? 'selected' : '' }}>🇪🇸 Español</option>
                                <option value="en" {{ $idiomaFilter == 'en' ? 'selected' : '' }}>🇬🇧 English</option>
                                <option value="fr" {{ $idiomaFilter == 'fr' ? 'selected' : '' }}>🇫🇷 Français</option>
                                <option value="de" {{ $idiomaFilter == 'de' ? 'selected' : '' }}>🇩🇪 Deutsch</option>
                                <option value="it" {{ $idiomaFilter == 'it' ? 'selected' : '' }}>🇮🇹 Italiano</option>
                                <option value="pt" {{ $idiomaFilter == 'pt' ? 'selected' : '' }}>🇵🇹 Português</option>
                            </select>
                            @if(request()->get('search') || $idiomaFilter)
                                <a href="{{ route('walee.clientes.proceso') }}" class="px-3 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white font-medium rounded-lg transition-all flex items-center gap-1.5 text-xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <span>Limpiar</span>
                                </a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Clientes List -->
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-3 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">
                        Clientes Extraídos
                        <span class="text-xs font-normal text-slate-500 dark:text-slate-400">
                            ({{ $clientes->total() }})
                        </span>
                    </h2>
                </div>
                
                <div class="space-y-2" id="clientsList">
                @forelse($clientes as $cliente)
                    @php
                        $phone = $cliente->telefono_1 ?: $cliente->telefono_2;
                        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                        $whatsappLink = $cleanPhone ? "https://wa.me/{$cleanPhone}" : null;
                        
                        // Bandera del idioma
                        $banderas = [
                            'es' => '🇪🇸',
                            'en' => '🇬🇧',
                            'fr' => '🇫🇷',
                            'de' => '🇩🇪',
                            'it' => '🇮🇹',
                            'pt' => '🇵🇹'
                        ];
                        $bandera = ($cliente->idioma) ? ($banderas[$cliente->idioma] ?? '') : '';
                        
                        // Mapeo de ciudades a zonas horarias (ciudades comunes)
                        $cityTimezones = [
                            // España
                            'madrid' => 'Europe/Madrid',
                            'barcelona' => 'Europe/Madrid',
                            'valencia' => 'Europe/Madrid',
                            'sevilla' => 'Europe/Madrid',
                            'bilbao' => 'Europe/Madrid',
                            'zaragoza' => 'Europe/Madrid',
                            'málaga' => 'Europe/Madrid',
                            'murcia' => 'Europe/Madrid',
                            'córdoba' => 'Europe/Madrid',
                            'palma' => 'Europe/Madrid',
                            
                            // Estados Unidos
                            'new york' => 'America/New_York',
                            'los angeles' => 'America/Los_Angeles',
                            'chicago' => 'America/Chicago',
                            'houston' => 'America/Chicago',
                            'phoenix' => 'America/Phoenix',
                            'philadelphia' => 'America/New_York',
                            'san antonio' => 'America/Chicago',
                            'san diego' => 'America/Los_Angeles',
                            'dallas' => 'America/Chicago',
                            'san jose' => 'America/Los_Angeles',
                            'miami' => 'America/New_York',
                            'atlanta' => 'America/New_York',
                            'boston' => 'America/New_York',
                            'seattle' => 'America/Los_Angeles',
                            'denver' => 'America/Denver',
                            
                            // Reino Unido
                            'london' => 'Europe/London',
                            'manchester' => 'Europe/London',
                            'birmingham' => 'Europe/London',
                            'glasgow' => 'Europe/London',
                            'liverpool' => 'Europe/London',
                            'edinburgh' => 'Europe/London',
                            
                            // Francia
                            'paris' => 'Europe/Paris',
                            'lyon' => 'Europe/Paris',
                            'marseille' => 'Europe/Paris',
                            'toulouse' => 'Europe/Paris',
                            'nice' => 'Europe/Paris',
                            
                            // Alemania
                            'berlin' => 'Europe/Berlin',
                            'munich' => 'Europe/Berlin',
                            'hamburg' => 'Europe/Berlin',
                            'frankfurt' => 'Europe/Berlin',
                            'cologne' => 'Europe/Berlin',
                            'stuttgart' => 'Europe/Berlin',
                            
                            // Italia
                            'rome' => 'Europe/Rome',
                            'milano' => 'Europe/Rome',
                            'milan' => 'Europe/Rome',
                            'naples' => 'Europe/Rome',
                            'napoli' => 'Europe/Rome',
                            'turin' => 'Europe/Rome',
                            'palermo' => 'Europe/Rome',
                            
                            // Portugal
                            'lisbon' => 'Europe/Lisbon',
                            'lisboa' => 'Europe/Lisbon',
                            'porto' => 'Europe/Lisbon',
                            
                            // México
                            'méxico' => 'America/Mexico_City',
                            'mexico' => 'America/Mexico_City',
                            'mexico city' => 'America/Mexico_City',
                            'guadalajara' => 'America/Mexico_City',
                            'monterrey' => 'America/Monterrey',
                            
                            // Argentina
                            'buenos aires' => 'America/Argentina/Buenos_Aires',
                            'córdoba' => 'America/Argentina/Cordoba',
                            
                            // Colombia
                            'bogotá' => 'America/Bogota',
                            'bogota' => 'America/Bogota',
                            'medellín' => 'America/Bogota',
                            'medellin' => 'America/Bogota',
                            
                            // Chile
                            'santiago' => 'America/Santiago',
                            
                            // Perú
                            'lima' => 'America/Lima',
                            
                            // Brasil
                            'são paulo' => 'America/Sao_Paulo',
                            'sao paulo' => 'America/Sao_Paulo',
                            'rio de janeiro' => 'America/Sao_Paulo',
                            'brasilia' => 'America/Sao_Paulo',
                            
                            // Canadá
                            'toronto' => 'America/Toronto',
                            'montreal' => 'America/Toronto',
                            'vancouver' => 'America/Vancouver',
                            
                            // Asia
                            'tokyo' => 'Asia/Tokyo',
                            'beijing' => 'Asia/Shanghai',
                            'shanghai' => 'Asia/Shanghai',
                            'hong kong' => 'Asia/Hong_Kong',
                            'singapore' => 'Asia/Singapore',
                            'dubai' => 'Asia/Dubai',
                            'mumbai' => 'Asia/Kolkata',
                            'delhi' => 'Asia/Kolkata',
                            
                            // Australia
                            'sydney' => 'Australia/Sydney',
                            'melbourne' => 'Australia/Melbourne',
                            'brisbane' => 'Australia/Brisbane',
                        ];
                        
                        // Mapeo de países a zonas horarias (para usar cuando solo tenemos país)
                        $countryTimezones = [
                            'spain' => 'Europe/Madrid',
                            'españa' => 'Europe/Madrid',
                            'united states' => 'America/New_York',
                            'usa' => 'America/New_York',
                            'estados unidos' => 'America/New_York',
                            'united kingdom' => 'Europe/London',
                            'uk' => 'Europe/London',
                            'reino unido' => 'Europe/London',
                            'france' => 'Europe/Paris',
                            'francia' => 'Europe/Paris',
                            'germany' => 'Europe/Berlin',
                            'alemania' => 'Europe/Berlin',
                            'italy' => 'Europe/Rome',
                            'italia' => 'Europe/Rome',
                            'portugal' => 'Europe/Lisbon',
                            'mexico' => 'America/Mexico_City',
                            'méxico' => 'America/Mexico_City',
                            'argentina' => 'America/Argentina/Buenos_Aires',
                            'colombia' => 'America/Bogota',
                            'chile' => 'America/Santiago',
                            'peru' => 'America/Lima',
                            'perú' => 'America/Lima',
                            'brazil' => 'America/Sao_Paulo',
                            'brasil' => 'America/Sao_Paulo',
                            'canada' => 'America/Toronto',
                            'canadá' => 'America/Toronto',
                            'costa rica' => 'America/Costa_Rica',
                            'japan' => 'Asia/Tokyo',
                            'japón' => 'Asia/Tokyo',
                            'china' => 'Asia/Shanghai',
                            'australia' => 'Australia/Sydney',
                            'australia' => 'Australia/Sydney',
                        ];
                        
                        // Mapeo de idiomas a zonas horarias (fallback)
                        $langTimezones = [
                            'es' => 'Europe/Madrid',
                            'en' => 'America/New_York',
                            'fr' => 'Europe/Paris',
                            'de' => 'Europe/Berlin',
                            'it' => 'Europe/Rome',
                            'pt' => 'Europe/Lisbon'
                        ];
                        
                        // Función para buscar ciudad en un texto
                        $findCityInText = function($text) use ($cityTimezones) {
                            if (empty($text)) return null;
                            $textLower = strtolower(trim($text));
                            // Buscar ciudades en orden de longitud (más específicas primero)
                            $sortedCities = array_keys($cityTimezones);
                            usort($sortedCities, function($a, $b) {
                                return strlen($b) - strlen($a);
                            });
                            foreach ($sortedCities as $city) {
                                if (strpos($textLower, $city) !== false) {
                                    return $cityTimezones[$city];
                                }
                            }
                            return null;
                        };
                        
                        // Función para buscar país en un texto
                        $findCountryInText = function($text) use ($countryTimezones) {
                            if (empty($text)) return null;
                            $textLower = strtolower(trim($text));
                            foreach ($countryTimezones as $country => $tz) {
                                if (strpos($textLower, $country) !== false) {
                                    return $tz;
                                }
                            }
                            return null;
                        };
                        
                        // Determinar zona horaria: primero por ciudad (campo ciudad), luego por address, luego por país en address, luego por idioma
                        $timezone = null;
                        $timezoneSource = null; // Para el tooltip
                        
                        // 1. Buscar por campo ciudad
                        if ($cliente->ciudad) {
                            $timezone = $findCityInText($cliente->ciudad);
                            if ($timezone) {
                                $timezoneSource = 'ciudad: ' . $cliente->ciudad;
                            }
                        }
                        
                        // 2. Si no se encontró, buscar en el campo address
                        if (!$timezone && $cliente->address) {
                            $timezone = $findCityInText($cliente->address);
                            if ($timezone) {
                                $timezoneSource = 'address: ' . (strlen($cliente->address) > 30 ? substr($cliente->address, 0, 30) . '...' : $cliente->address);
                            }
                        }
                        
                        // 3. Si no se encontró ciudad, buscar país en address
                        if (!$timezone && $cliente->address) {
                            $timezone = $findCountryInText($cliente->address);
                            if ($timezone) {
                                $timezoneSource = 'address (país)';
                            }
                        }
                        
                        // 4. Si no se encontró, buscar país en campo ciudad
                        if (!$timezone && $cliente->ciudad) {
                            $timezone = $findCountryInText($cliente->ciudad);
                            if ($timezone) {
                                $timezoneSource = 'ciudad (país)';
                            }
                        }
                        
                        // 5. Si no se encontró, usar idioma como fallback
                        if (!$timezone && $cliente->idioma && isset($langTimezones[$cliente->idioma])) {
                            $timezone = $langTimezones[$cliente->idioma];
                            $timezoneSource = 'idioma: ' . $cliente->idioma;
                        }
                        
                        // Obtener hora actual en la zona horaria del cliente
                        $clientTime = null;
                        $clientDate = null;
                        if ($timezone) {
                            try {
                                $now = new \DateTime('now', new \DateTimeZone($timezone));
                                $clientTime = $now->format('H:i');
                                $clientDate = $now->format('d/m');
                            } catch (\Exception $e) {
                                // Si hay error, no mostrar hora
                            }
                        }
                    @endphp
                    <div class="client-card group" data-search="{{ strtolower($cliente->name . ' ' . ($phone ?? '') . ' ' . ($cliente->email ?? '')) }}" data-client-id="{{ $cliente->id }}">
                        <div class="flex items-start gap-2.5 p-2.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:border-blue-400 dark:hover:border-blue-500/30 hover:bg-blue-50/50 dark:hover:bg-blue-500/10 transition-all">
                            <!-- Checkbox -->
                            <input 
                                type="checkbox" 
                                class="client-checkbox w-4 h-4 rounded border-slate-300 dark:border-slate-600 text-emerald-500 focus:ring-emerald-500 focus:ring-offset-0 cursor-pointer hidden"
                                data-client-id="{{ $cliente->id }}"
                                data-client-name="{{ $cliente->name }}"
                                onchange="updateDeleteButton()"
                            >
                            
                            <!-- Icon -->
                            <div class="w-9 h-9 rounded-lg bg-blue-100 dark:bg-blue-500/20 flex-shrink-0 flex items-center justify-center border border-blue-500/30">
                                @if($bandera)
                                    <span class="text-lg">{{ $bandera }}</span>
                                @else
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                @endif
                            </div>
                            
                            <!-- Content -->
                            <a href="{{ route('walee.cliente.detalle', $cliente->id) }}" class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-0.5">
                                    <p class="font-medium text-sm text-slate-900 dark:text-white break-words group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ $cliente->name ?: 'Sin nombre' }}</p>
                                </div>
                                @if($cliente->industria)
                                    <p class="text-xs text-slate-500 dark:text-slate-400 break-words mb-0.5">{{ $cliente->industria }}</p>
                                @endif
                                @if($cliente->email)
                                    <p class="text-xs text-slate-600 dark:text-slate-400 break-words">{{ $cliente->email }}</p>
                                @endif
                                @if($phone)
                                    <p class="text-xs text-slate-500 dark:text-slate-500 break-words">{{ $phone }}</p>
                                @endif
                                @if($cliente->website)
                                    <p class="text-xs text-slate-500 dark:text-slate-500 break-words">{{ $cliente->website }}</p>
                                @endif
                                <p class="text-xs text-slate-500 dark:text-slate-500 mt-0.5">{{ $cliente->created_at->diffForHumans() }}</p>
                            </a>
                            
                            <!-- Email Counter and Timezone -->
                            <div class="flex-shrink-0 flex items-start gap-1.5 self-start">
                                @if($clientTime && $timezone)
                                    <div class="bg-violet-100 dark:bg-violet-500/20 border border-violet-200 dark:border-violet-500/30 rounded-lg px-2 py-1 flex flex-col items-center gap-0.5" title="Hora local del cliente{{ $timezoneSource ? ' (basada en ' . $timezoneSource . ')' : '' }}" data-client-timezone="{{ $timezone }}" data-client-ciudad="{{ $cliente->ciudad ?? '' }}">
                                        <div class="flex items-center gap-1">
                                            <svg class="w-3 h-3 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span class="text-xs font-bold text-violet-600 dark:text-violet-400 client-time-{{ $cliente->id }}">{{ $clientTime }}</span>
                                        </div>
                                        <span class="text-[10px] text-violet-500 dark:text-violet-400 client-date-{{ $cliente->id }}">{{ $clientDate }}</span>
                                    </div>
                                @endif
                                <div class="bg-blue-100 dark:bg-blue-500/20 border border-blue-200 dark:border-blue-500/30 rounded-lg px-2 py-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-xs font-semibold text-blue-600 dark:text-blue-400">{{ $cliente->emails_count ?? 0 }}</span>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex-shrink-0 flex items-start gap-1.5 sm:gap-1.5 self-start">
                                <!-- Email with AI Button -->
                                <button onclick="openEmailModalForCliente({{ $cliente->id }}, '{{ $cliente->email ?? '' }}', '{{ addslashes($cliente->name ?? '') }}', '{{ $cliente->website ?? '' }}')" class="p-1.5 sm:p-1.5 rounded-md bg-walee-500/20 hover:bg-walee-500/30 text-walee-600 dark:text-walee-400 border border-walee-500/30 hover:border-walee-400/50 transition-all flex items-center justify-center" title="Email AI">
                                    <svg class="w-4 h-4 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </button>
                                
                                <!-- WhatsApp Button -->
                                @if($whatsappLink)
                                    <button onclick="openWhatsAppModalForCliente('{{ addslashes($cliente->name ?? 'Cliente') }}', '{{ $whatsappLink }}')" class="p-1.5 sm:p-1.5 rounded-md bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30 hover:border-emerald-400/50 transition-all flex items-center justify-center" title="WhatsApp">
                                        <svg class="w-4 h-4 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                        </svg>
                                    </button>
                                @else
                                    <div class="p-1.5 sm:p-1.5 rounded-md bg-slate-800/50 text-slate-500 border border-slate-700 flex items-center justify-center" title="Sin teléfono">
                                        <svg class="w-4 h-4 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Delete Button -->
                                <button onclick="deleteCliente({{ $cliente->id }}, '{{ addslashes($cliente->name ?? 'Cliente') }}')" class="p-1.5 sm:p-1.5 rounded-md bg-red-500/20 hover:bg-red-500/30 text-red-600 dark:text-red-400 border border-red-500/30 hover:border-red-400/50 transition-all flex items-center justify-center" title="Eliminar">
                                    <svg class="w-4 h-4 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-sm text-slate-500 dark:text-slate-400">No se encontraron clientes en proceso</p>
                    </div>
                @endforelse
                </div>
                
                <!-- Pagination -->
                @if($clientes->hasPages())
                    <div class="mt-4 flex justify-center gap-2">
                        @if($clientes->onFirstPage())
                            <span class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 rounded-md cursor-not-allowed text-xs">Anterior</span>
                        @else
                            <a href="{{ $clientes->previousPageUrl() }}" class="px-3 py-1.5 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-900 dark:text-white rounded-md transition-colors border border-slate-200 dark:border-slate-700 text-xs">Anterior</a>
                        @endif
                        
                        <span class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800/50 text-slate-600 dark:text-slate-400 rounded-md border border-slate-200 dark:border-slate-700 text-xs">
                            Página {{ $clientes->currentPage() }} de {{ $clientes->lastPage() }}
                        </span>
                        
                        @if($clientes->hasMorePages())
                            <a href="{{ $clientes->nextPageUrl() }}" class="px-3 py-1.5 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-900 dark:text-white rounded-md transition-colors border border-slate-200 dark:border-slate-700 text-xs">Siguiente</a>
                        @else
                            <span class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 rounded-md cursor-not-allowed text-xs">Siguiente</span>
                        @endif
                    </div>
                @endif
            </div>
            
            <!-- Footer -->
            <footer class="text-center py-4 sm:py-6 md:py-8 mt-4 sm:mt-6 md:mt-8">
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-500">
                    <span class="text-walee-600 dark:text-walee-400 font-medium">Walee</span> · websolutions.work
                </p>
            </footer>
        </div>
    </div>

    <script>
        // Templates de email disponibles
        const emailTemplates = @json($templates ?? []);
        
        // Clientes en proceso disponibles
        const clientesEnProceso = @json($clientesEnProceso ?? []);
        
        // Variables globales para el flujo de fases del modal de email
        let emailModalData = {
            clienteId: null,
            clienteEmail: '',
            clienteName: '',
            clienteWebsite: '',
            email: '',
            aiPrompt: '',
            subject: '',
            body: '',
            attachments: null
        };
        
        function openEmailModalForCliente(clienteId, email, clienteName, clienteWebsite) {
            // Configurar datos del cliente
            emailModalData.clienteId = clienteId || null;
            emailModalData.clienteEmail = email || '';
            emailModalData.clienteName = clienteName || '';
            emailModalData.clienteWebsite = clienteWebsite || '';
            emailModalData.email = email || '';
            emailModalData.aiPrompt = '';
            emailModalData.subject = '';
            emailModalData.body = '';
            emailModalData.attachments = null;
            
            // Abrir desde la fase 1
            showEmailPhase1();
        }
        
        function loadEmailTemplate(templateId) {
            const aiGenerateContainer = document.getElementById('ai_generate_container');
            const tipoDisplay = document.getElementById('template_tipo_display');
            const tipoBadgeInline = document.getElementById('template_tipo_badge_inline');
            const tipoBadgeValue = document.getElementById('template_tipo_badge_value');
            
            if (!templateId || !emailTemplates) {
                if (aiGenerateContainer) {
                    aiGenerateContainer.style.display = 'block';
                }
                if (tipoDisplay) {
                    tipoDisplay.style.display = 'none';
                }
                if (tipoBadgeInline) {
                    tipoBadgeInline.style.display = 'none';
                }
                return;
            }
            
            const template = emailTemplates.find(t => t.id == templateId);
            if (!template) {
                if (aiGenerateContainer) {
                    aiGenerateContainer.style.display = 'block';
                }
                if (tipoDisplay) {
                    tipoDisplay.style.display = 'none';
                }
                if (tipoBadgeInline) {
                    tipoBadgeInline.style.display = 'none';
                }
                return;
            }
            
            emailModalData.aiPrompt = template.ai_prompt || '';
            emailModalData.subject = template.asunto || '';
            emailModalData.body = template.contenido || '';
            
            const aiPromptField = document.getElementById('ai_prompt');
            if (aiPromptField) {
                aiPromptField.value = emailModalData.aiPrompt;
            }
            
            const subjectField = document.getElementById('email_subject');
            const bodyField = document.getElementById('email_body');
            if (subjectField) {
                subjectField.value = emailModalData.subject;
            }
            if (bodyField) {
                bodyField.value = emailModalData.body;
            }
            
            // Mostrar el tipo del template como badge
            setTimeout(() => {
                const tipoValue = document.getElementById('template_tipo_value');
                
                // Función para obtener colores del badge según el tipo
                const getTipoColors = (tipo) => {
                    const tipoColors = {
                        'business': {
                            class: 'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300 border border-blue-300 dark:border-blue-500/30',
                            style: 'background-color: rgb(219 234 254); color: rgb(29 78 216); border: 1px solid rgb(147 197 253);'
                        },
                        'agricultura': {
                            class: 'bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-300 border border-green-300 dark:border-green-500/30',
                            style: 'background-color: rgb(220 252 231); color: rgb(21 128 61); border: 1px solid rgb(134 239 172);'
                        },
                        'b2b': {
                            class: 'bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-300 border border-purple-300 dark:border-purple-500/30',
                            style: 'background-color: rgb(243 232 255); color: rgb(126 34 206); border: 1px solid rgb(196 181 253);'
                        },
                        'b2c': {
                            class: 'bg-orange-100 dark:bg-orange-500/20 text-orange-700 dark:text-orange-300 border border-orange-300 dark:border-orange-500/30',
                            style: 'background-color: rgb(255 237 213); color: rgb(194 65 12); border: 1px solid rgb(254 215 170);'
                        }
                    };
                    const defaultColors = {
                        class: 'bg-violet-100 dark:bg-violet-500/20 text-violet-700 dark:text-violet-300 border border-violet-300 dark:border-violet-500/30',
                        style: 'background-color: rgb(237 233 254); color: rgb(109 40 217); border: 1px solid rgb(196 181 253);'
                    };
                    return tipoColors[tipo] || defaultColors;
                };
                
                if (template.tipo) {
                    const tipoText = template.tipo.charAt(0).toUpperCase() + template.tipo.slice(1);
                    const tipoColors = getTipoColors(template.tipo);
                    
                    // Badge debajo del select
                    if (tipoDisplay && tipoValue) {
                        tipoValue.textContent = tipoText;
                        tipoValue.className = 'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold ' + tipoColors.class;
                        tipoValue.style.cssText = tipoColors.style + ' display: inline-flex; align-items: center; padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;';
                        tipoDisplay.style.display = 'block';
                        tipoDisplay.style.visibility = 'visible';
                    }
                    
                    // Badge inline en el select
                    if (tipoBadgeInline && tipoBadgeValue) {
                        tipoBadgeValue.textContent = tipoText;
                        tipoBadgeValue.className = 'inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold ' + tipoColors.class;
                        tipoBadgeValue.style.cssText = tipoColors.style + ' display: inline-flex; align-items: center; padding: 0.125rem 0.5rem; border-radius: 9999px; font-size: 0.625rem; font-weight: 600;';
                        tipoBadgeInline.style.display = 'block';
                        tipoBadgeInline.style.visibility = 'visible';
                    }
                } else {
                    if (tipoDisplay) {
                        tipoDisplay.style.display = 'none';
                    }
                    if (tipoBadgeInline) {
                        tipoBadgeInline.style.display = 'none';
                    }
                }
            }, 100);
            
            if (aiGenerateContainer) {
                aiGenerateContainer.style.display = 'none';
            }
            
            const showAiBtn = document.getElementById('show_ai_btn');
            if (showAiBtn) {
                showAiBtn.style.display = 'block';
            }
        }
        
        function showAIGenerateButton() {
            const aiGenerateContainer = document.getElementById('ai_generate_container');
            if (aiGenerateContainer) {
                aiGenerateContainer.style.display = 'block';
            }
            
            const showAiBtn = document.getElementById('show_ai_btn');
            if (showAiBtn) {
                showAiBtn.style.display = 'none';
            }
            
            const templateSelect = document.getElementById('email_template_select');
            if (templateSelect) {
                templateSelect.value = '';
            }
            
            // Ocultar badges de tipo
            const tipoDisplay = document.getElementById('template_tipo_display');
            const tipoBadgeInline = document.getElementById('template_tipo_badge_inline');
            if (tipoDisplay) {
                tipoDisplay.style.display = 'none';
            }
            if (tipoBadgeInline) {
                tipoBadgeInline.style.display = 'none';
            }
            
            emailModalData.aiPrompt = '';
            emailModalData.subject = '';
            emailModalData.body = '';
            
            const aiPromptField = document.getElementById('ai_prompt');
            if (aiPromptField) {
                aiPromptField.value = '';
            }
        }
        
        function selectClienteProceso(clienteId) {
            if (!clienteId) {
                return;
            }
            
            const select = document.getElementById('cliente_proceso_select');
            const selectedOption = select.options[select.selectedIndex];
            
            if (selectedOption) {
                const email = selectedOption.getAttribute('data-email') || '';
                const name = selectedOption.getAttribute('data-name') || '';
                
                // Actualizar datos del modal
                emailModalData.clienteId = clienteId;
                emailModalData.clienteEmail = email;
                emailModalData.clienteName = name;
                emailModalData.email = email;
                
                // Actualizar el campo de email
                const emailField = document.getElementById('email_destinatario');
                if (emailField) {
                    emailField.value = email;
                }
            }
        }
        
        function showEmailPhase1() {
            const isMobile = window.innerWidth < 640;
            const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;
            const isDesktop = window.innerWidth >= 1024;
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            let modalWidth = '95%';
            if (isDesktop) {
                modalWidth = '900px';
            } else if (isTablet) {
                modalWidth = '700px';
            } else if (isMobile) {
                modalWidth = '95%';
            }
            
            // Generar opciones de templates
            let templatesOptions = '<option value="">Seleccionar template (opcional)</option>';
            if (emailTemplates && emailTemplates.length > 0) {
                const sortedTemplates = [...emailTemplates].sort((a, b) => {
                    const aHasTipo = a.tipo && a.tipo.trim() !== '';
                    const bHasTipo = b.tipo && b.tipo.trim() !== '';
                    if (aHasTipo && !bHasTipo) return -1;
                    if (!aHasTipo && bHasTipo) return 1;
                    return 0;
                });
                
                sortedTemplates.forEach(template => {
                    const tipoLabel = template.tipo ? ` [${template.tipo.charAt(0).toUpperCase() + template.tipo.slice(1)}]` : '';
                    templatesOptions += `<option value="${template.id}" data-tipo="${template.tipo || ''}">${template.nombre}${tipoLabel}</option>`;
                });
            }
            
            const html = `
                <div class="space-y-3 text-left">
                    <div class="flex items-center justify-center gap-1 mb-3">
                        <div class="w-2 h-2 rounded-full bg-violet-600"></div>
                        <div class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-600"></div>
                        <div class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-600"></div>
                    </div>
                    
                    ${emailTemplates && emailTemplates.length > 0 ? `
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'}">Template guardado (opcional)</label>
                            <button type="button" onclick="showAIGenerateButton()" id="show_ai_btn" style="display: none;"
                                class="text-xs text-violet-600 dark:text-violet-400 hover:text-violet-700 dark:hover:text-violet-300 underline">
                                Usar AI en su lugar
                            </button>
                        </div>
                        <div class="relative">
                            <select id="email_template_select" onchange="loadEmailTemplate(this.value)"
                                class="w-full px-3 py-2 pr-20 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none appearance-none">
                                ${templatesOptions}
                            </select>
                            <div id="template_tipo_badge_inline" class="absolute right-8 top-1/2 -translate-y-1/2 pointer-events-none z-10" style="display: none;">
                                <span id="template_tipo_badge_value" class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold"></span>
                            </div>
                        </div>
                        <div id="template_tipo_display" class="mt-2" style="display: none;">
                            <span id="template_tipo_value" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold"></span>
                        </div>
                    </div>
                    ` : ''}
                    
                    ${clientesEnProceso && clientesEnProceso.length > 0 ? `
                    <div>
                        <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-2">Seleccionar cliente en proceso (opcional)</label>
                        <select id="cliente_proceso_select" onchange="selectClienteProceso(this.value)"
                            class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none">
                            <option value="">Seleccionar cliente...</option>
                            ${clientesEnProceso.map(cliente => 
                                `<option value="${cliente.id}" data-email="${cliente.email || ''}" data-name="${cliente.name || ''}">${cliente.name || 'Sin nombre'} - ${cliente.email || 'Sin email'}</option>`
                            ).join('')}
                        </select>
                    </div>
                    ` : ''}
                    
                    <div>
                        <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-2">Email destinatario <span class="text-red-500">*</span></label>
                        <input type="email" id="email_destinatario" value="${emailModalData.email}" required
                            class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-2">Instrucciones para AI (opcional)</label>
                        <textarea id="ai_prompt" rows="3" placeholder="Ej: Genera un email profesional..."
                            class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none resize-none">${emailModalData.aiPrompt}</textarea>
                        <div id="ai_generate_container">
                            <button type="button" onclick="generateEmailWithAI()" id="generateEmailBtn"
                                class="mt-2 w-full px-3 py-2 bg-violet-600 hover:bg-violet-500 text-white font-semibold rounded-lg transition-all flex items-center justify-center gap-2 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                                </svg>
                                <span>Generar con AI</span>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: '<div class="flex items-center gap-2"><svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M24 5.457v13.909c0 .904-.732 1.636-1.636 1.636h-3.819V11.73L12 16.64l-6.545-4.91v9.273H1.636A1.636 1.636 0 0 1 0 21.366V5.457c0-2.023 2.309-3.178 3.927-1.964L5.455 4.64 12 9.548l6.545-4.91 1.528-1.145C21.69 2.28 24 3.434 24 5.457z"/></svg><span>Crear Email - Paso 1</span></div>',
                html: html,
                width: modalWidth,
                padding: isMobile ? '1rem' : '1.5rem',
                showCancelButton: true,
                confirmButtonText: 'Siguiente',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#8b5cf6',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                reverseButtons: true,
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                    cancelButton: isDarkMode ? 'dark-swal-cancel' : 'light-swal-cancel'
                },
                preConfirm: () => {
                    const email = document.getElementById('email_destinatario').value;
                    if (!email) {
                        Swal.showValidationMessage('El email destinatario es requerido');
                        return false;
                    }
                    emailModalData.email = email;
                    const aiPromptField = document.getElementById('ai_prompt');
                    if (aiPromptField) {
                        emailModalData.aiPrompt = aiPromptField.value;
                    }
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    showEmailPhase2();
                }
            });
        }
        
        function showEmailPhase2() {
            const isMobile = window.innerWidth < 640;
            const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;
            const isDesktop = window.innerWidth >= 1024;
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            let modalWidth = '95%';
            if (isDesktop) {
                modalWidth = '900px';
            } else if (isTablet) {
                modalWidth = '700px';
            } else if (isMobile) {
                modalWidth = '95%';
            }
            
            const html = `
                <div class="space-y-3 text-left">
                    <div class="flex items-center justify-center gap-1 mb-3">
                        <div class="w-2 h-2 rounded-full bg-violet-600"></div>
                        <div class="w-2 h-2 rounded-full bg-violet-600"></div>
                        <div class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-600"></div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-2">Asunto <span class="text-red-500">*</span></label>
                        <input type="text" id="email_subject" value="${emailModalData.subject}" required placeholder="Asunto del email"
                            class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-2">Mensaje <span class="text-red-500">*</span></label>
                        <textarea id="email_body" rows="6" required placeholder="Escribe o genera el contenido del email..."
                            class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none resize-none">${emailModalData.body}</textarea>
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: '<div class="flex items-center gap-2"><svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M24 5.457v13.909c0 .904-.732 1.636-1.636 1.636h-3.819V11.73L12 16.64l-6.545-4.91v9.273H1.636A1.636 1.636 0 0 1 0 21.366V5.457c0-2.023 2.309-3.178 3.927-1.964L5.455 4.64 12 9.548l6.545-4.91 1.528-1.145C21.69 2.28 24 3.434 24 5.457z"/></svg><span>Crear Email - Paso 2</span></div>',
                html: html,
                width: modalWidth,
                padding: isMobile ? '1rem' : '1.5rem',
                showCancelButton: true,
                confirmButtonText: 'Siguiente',
                cancelButtonText: 'Anterior',
                confirmButtonColor: '#8b5cf6',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                reverseButtons: true,
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                    cancelButton: isDarkMode ? 'dark-swal-cancel' : 'light-swal-cancel'
                },
                preConfirm: () => {
                    const subject = document.getElementById('email_subject').value;
                    const body = document.getElementById('email_body').value;
                    if (!subject || !body) {
                        Swal.showValidationMessage('Por favor, completa el asunto y el mensaje');
                        return false;
                    }
                    emailModalData.subject = subject;
                    emailModalData.body = body;
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    showEmailPhase3();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    showEmailPhase1();
                }
            });
        }
        
        function showEmailPhase3() {
            const isMobile = window.innerWidth < 640;
            const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;
            const isDesktop = window.innerWidth >= 1024;
            const isDarkMode = document.documentElement.classList.contains('dark');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            let modalWidth = '95%';
            if (isDesktop) {
                modalWidth = '900px';
            } else if (isTablet) {
                modalWidth = '700px';
            } else if (isMobile) {
                modalWidth = '95%';
            }
            
            const html = `
                <div class="space-y-3 text-left">
                    <div class="flex items-center justify-center gap-1 mb-3">
                        <div class="w-2 h-2 rounded-full bg-violet-600"></div>
                        <div class="w-2 h-2 rounded-full bg-violet-600"></div>
                        <div class="w-2 h-2 rounded-full bg-violet-600"></div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-2">Adjuntar archivos (opcional)</label>
                        <input type="file" id="email_attachments" multiple accept=".pdf,.jpg,.jpeg,.png,.gif,.webp"
                            class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none">
                        <p class="text-xs ${isDarkMode ? 'text-slate-400' : 'text-slate-500'} mt-1">PDF o imágenes (máx. 10MB por archivo)</p>
                        <div id="email_files_list" class="mt-2 space-y-1.5"></div>
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: '<div class="flex items-center gap-2"><svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M24 5.457v13.909c0 .904-.732 1.636-1.636 1.636h-3.819V11.73L12 16.64l-6.545-4.91v9.273H1.636A1.636 1.636 0 0 1 0 21.366V5.457c0-2.023 2.309-3.178 3.927-1.964L5.455 4.64 12 9.548l6.545-4.91 1.528-1.145C21.69 2.28 24 3.434 24 5.457z"/></svg><span>Crear Email - Paso 3</span></div>',
                html: html,
                width: modalWidth,
                padding: isMobile ? '1rem' : '1.5rem',
                showCancelButton: true,
                confirmButtonText: 'Enviar Email',
                cancelButtonText: 'Anterior',
                confirmButtonColor: '#8b5cf6',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                reverseButtons: true,
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                    cancelButton: isDarkMode ? 'dark-swal-cancel' : 'light-swal-cancel'
                },
                didOpen: () => {
                    const fileInput = document.getElementById('email_attachments');
                    const filesList = document.getElementById('email_files_list');
                    if (fileInput) {
                        fileInput.addEventListener('change', function(e) {
                            if (filesList) {
                                filesList.innerHTML = '';
                                Array.from(e.target.files).forEach((file, index) => {
                                    const fileItem = document.createElement('div');
                                    fileItem.className = `flex items-center justify-between p-1.5 rounded ${isDarkMode ? 'bg-slate-700' : 'bg-slate-100'}`;
                                    fileItem.innerHTML = `
                                        <span class="text-xs ${isDarkMode ? 'text-slate-300' : 'text-slate-700'}">${file.name}</span>
                                        <span class="text-xs ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
                                    `;
                                    filesList.appendChild(fileItem);
                                });
                            }
                        });
                    }
                },
                preConfirm: async () => {
                    const attachments = document.getElementById('email_attachments');
                    emailModalData.attachments = attachments && attachments.files && attachments.files.length > 0 ? attachments.files : null;
                    
                    const formData = new FormData();
                    formData.append('cliente_id', emailModalData.clienteId || '');
                    formData.append('email', emailModalData.email);
                    formData.append('subject', emailModalData.subject);
                    formData.append('body', emailModalData.body);
                    formData.append('ai_prompt', emailModalData.aiPrompt || '');
                    formData.append('from_bot_alpha', 'true');
                    
                    if (emailModalData.attachments) {
                        Array.from(emailModalData.attachments).forEach((file, index) => {
                            formData.append(`archivos[${index}]`, file);
                        });
                    }
                    
                    try {
                        Swal.fire({
                            title: 'Enviando...',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                            background: isDarkMode ? '#1e293b' : '#ffffff',
                            color: isDarkMode ? '#e2e8f0' : '#1e293b'
                        });
                        
                        const response = await fetch('{{ route("walee.emails.enviar") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            body: formData
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Email enviado!',
                                text: data.message || 'El email se ha enviado correctamente',
                                confirmButtonColor: '#8b5cf6',
                                background: isDarkMode ? '#1e293b' : '#ffffff',
                                color: isDarkMode ? '#e2e8f0' : '#1e293b'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Error al enviar el email',
                                confirmButtonColor: '#ef4444',
                                background: isDarkMode ? '#1e293b' : '#ffffff',
                                color: isDarkMode ? '#e2e8f0' : '#1e293b'
                            });
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de conexión',
                            text: error.message,
                            confirmButtonColor: '#ef4444',
                            background: isDarkMode ? '#1e293b' : '#ffffff',
                            color: isDarkMode ? '#e2e8f0' : '#1e293b'
                        });
                    }
                    
                    return false;
                }
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.cancel) {
                    showEmailPhase2();
                }
            });
        }
        
        async function generateEmailWithAI() {
            const generateBtn = document.getElementById('generateEmailBtn');
            const aiPrompt = document.getElementById('ai_prompt').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const clienteId = emailModalData.clienteId;
            const clienteName = emailModalData.clienteName;
            const clienteWebsite = emailModalData.clienteWebsite;
            
            generateBtn.disabled = true;
            generateBtn.innerHTML = `
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Generando...</span>
            `;
            
            try {
                const response = await fetch('{{ route("walee.emails.generar") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        cliente_id: clienteId,
                        ai_prompt: aiPrompt,
                        client_name: clienteName,
                        client_website: clienteWebsite,
                    }),
                });
                
                const data = await response.json();
                
                if (data.success) {
                    emailModalData.subject = data.subject;
                    emailModalData.body = data.body;
                    Swal.fire({
                        icon: 'success',
                        title: 'Email generado',
                        text: 'El contenido ha sido generado con AI',
                        confirmButtonColor: '#8b5cf6',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        Swal.close();
                        showEmailPhase2();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al generar email',
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: error.message,
                    confirmButtonColor: '#ef4444'
                });
            } finally {
                generateBtn.disabled = false;
                generateBtn.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                    </svg>
                    <span>Generar con AI</span>
                `;
            }
        }
        
        function openWhatsAppModalForCliente(clienteName, whatsappLink) {
            if (!whatsappLink) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Sin teléfono',
                    text: 'Este cliente no tiene un número de teléfono registrado.',
                    confirmButtonColor: '#D59F3B'
                });
                return;
            }
            
            const isMobile = window.innerWidth < 640;
            const isDesktop = window.innerWidth >= 1024;
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            let modalWidth = '600px';
            if (isMobile) {
                modalWidth = '98%';
            }
            
            const html = `
                <form id="whatsappForm" class="space-y-4 text-left">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Redactar mensaje para ${clienteName}</label>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">Prompt</label>
                        <textarea id="whatsappPrompt" rows="4" placeholder="Describe el mensaje que quieres enviar (ej: saludar y preguntar sobre disponibilidad para una reunión)"
                                  class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                    </div>
                    <div>
                        <button type="button" onclick="generateWhatsAppMessage()" 
                                class="w-full px-4 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-semibold rounded-lg transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Generar con AI
                        </button>
                    </div>
                    <div id="generatedMessageContainer" class="hidden">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Mensaje generado:</label>
                        <textarea id="generatedMessage" rows="4" readonly
                                  class="w-full px-3 py-2 text-sm rounded-lg border border-emerald-300 dark:border-emerald-600 bg-emerald-50 dark:bg-emerald-900/20 text-slate-900 dark:text-white"></textarea>
                    </div>
                </form>
            `;
            
            Swal.fire({
                title: '',
                html: html,
                width: modalWidth,
                padding: isMobile ? '0.75rem' : (isDesktop ? '1.5rem' : '1.5rem'),
                heightAuto: true,
                customClass: {
                    container: isMobile ? 'swal2-container-mobile' : '',
                    popup: isMobile ? 'swal2-popup-mobile' : (isDarkMode ? 'dark-swal' : 'light-swal'),
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isMobile ? 'swal2-html-container-mobile' : (isDarkMode ? 'dark-swal-html' : 'light-swal-html'),
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                    cancelButton: isDarkMode ? 'dark-swal-cancel' : 'light-swal-cancel'
                },
                showCancelButton: true,
                confirmButtonText: 'Abrir WhatsApp',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#25D366',
                reverseButtons: true,
                didOpen: () => {
                    // Aplicar tema dark/light al modal
                    const popup = Swal.getPopup();
                    if (isDarkMode) {
                        popup.style.backgroundColor = '#0f172a';
                        popup.style.color = '#e2e8f0';
                        popup.style.borderColor = 'rgba(213, 159, 59, 0.2)';
                    } else {
                        popup.style.backgroundColor = '#ffffff';
                        popup.style.color = '#1e293b';
                        popup.style.borderColor = 'rgba(203, 213, 225, 0.5)';
                    }
                    // Hacer el modal más alto
                    popup.style.minHeight = isMobile ? 'auto' : '500px';
                    popup.style.maxHeight = isMobile ? '90vh' : '80vh';
                },
                preConfirm: () => {
                    const generatedMessage = document.getElementById('generatedMessage')?.value;
                    if (!generatedMessage || generatedMessage.trim() === '') {
                        Swal.showValidationMessage('Primero debes generar un mensaje con AI');
                        return false;
                    }
                    return generatedMessage;
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    const message = encodeURIComponent(result.value);
                    // Construir URL correctamente: usar ? si no tiene parámetros, & si ya tiene
                    const separator = whatsappLink.includes('?') ? '&' : '?';
                    const whatsappUrl = `${whatsappLink}${separator}text=${message}`;
                    window.open(whatsappUrl, '_blank');
                }
            });
        }
        
        async function generateWhatsAppMessage() {
            const prompt = document.getElementById('whatsappPrompt')?.value;
            if (!prompt || prompt.trim() === '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campo vacío',
                    text: 'Por favor, describe el mensaje que quieres enviar.',
                    confirmButtonColor: '#D59F3B'
                });
                return;
            }
            
            // Deshabilitar botón y mostrar loading
            const generateButton = event.target;
            const originalText = generateButton.innerHTML;
            generateButton.disabled = true;
            generateButton.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Generando...';
            
            try {
                const response = await fetch('{{ route("walee.whatsapp.generar-mensaje") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ prompt: prompt })
                });
                
                const result = await response.json();
                
                // Restaurar botón
                generateButton.disabled = false;
                generateButton.innerHTML = originalText;
                
                if (result.success) {
                    // Mostrar mensaje generado
                    const container = document.getElementById('generatedMessageContainer');
                    const textarea = document.getElementById('generatedMessage');
                    if (container && textarea) {
                        container.classList.remove('hidden');
                        textarea.value = result.message;
                        // Scroll al mensaje generado
                        container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.message || 'Error al generar el mensaje',
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                // Restaurar botón
                generateButton.disabled = false;
                generateButton.innerHTML = originalText;
                
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión. Por favor, intenta nuevamente.',
                    confirmButtonColor: '#ef4444'
                });
            }
        }
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const searchInput = document.getElementById('searchInput');
        const clientsList = document.getElementById('clientsList');
        
        // La búsqueda ahora se hace mediante el formulario, pero mantenemos la funcionalidad de búsqueda en tiempo real si no hay filtro de idioma
        const idiomaFilter = '{{ $idiomaFilter }}';
        if (searchInput && clientsList && !idiomaFilter) {
            const cards = clientsList.querySelectorAll('.client-card');
            
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                
                cards.forEach(card => {
                    const searchText = card.dataset.search || '';
                    const matches = searchText.includes(query);
                    card.style.display = matches ? 'block' : 'none';
                });
            });
        }
        
        // Toggle actions menu
        function toggleActionsMenu() {
            const menu = document.getElementById('actionsMenu');
            const checkboxes = document.querySelectorAll('.client-checkbox');
            
            if (menu) {
                const isHidden = menu.classList.contains('hidden');
                menu.classList.toggle('hidden');
                
                // Show/hide checkboxes based on menu state
                checkboxes.forEach(checkbox => {
                    if (isHidden) {
                        // Opening menu - show checkboxes
                        checkbox.classList.remove('hidden');
                    } else {
                        // Closing menu - hide checkboxes and uncheck them
                        checkbox.classList.add('hidden');
                        checkbox.checked = false;
                    }
                });
                
                // Update delete button and select all checkbox
                updateDeleteButton();
                const selectAllCheckbox = document.getElementById('selectAll');
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = false;
                }
            }
        }
        
        // Close actions menu when clicking outside
        document.addEventListener('click', function(e) {
            const menuBtn = document.getElementById('actionsMenuBtn');
            const menu = document.getElementById('actionsMenu');
            
            if (menu && menuBtn && !menu.contains(e.target) && !menuBtn.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });
        
        // Toggle select all
        function toggleSelectAll(checked) {
            const checkboxes = document.querySelectorAll('.client-checkbox');
            checkboxes.forEach(checkbox => {
                // Only check visible checkboxes
                const card = checkbox.closest('.client-card');
                if (card && card.style.display !== 'none') {
                    checkbox.checked = checked;
                }
            });
            updateDeleteButton();
        }
        
        // Update delete button visibility and count
        function updateDeleteButton() {
            const checkedBoxes = document.querySelectorAll('.client-checkbox:checked');
            const deleteBtn = document.getElementById('deleteSelectedBtn');
            const deleteCount = document.getElementById('deleteCount');
            
            if (!deleteBtn || !deleteCount) {
                return;
            }
            
            if (checkedBoxes.length > 0) {
                deleteBtn.classList.remove('hidden');
                deleteCount.textContent = `Borrar (${checkedBoxes.length})`;
            } else {
                deleteBtn.classList.add('hidden');
            }
        }
        
        // Delete selected clients
        async function deleteSelectedClients() {
            const checkedBoxes = document.querySelectorAll('.client-checkbox:checked');
            
            if (checkedBoxes.length === 0) {
                return;
            }
            
            const clientIds = Array.from(checkedBoxes).map(cb => cb.dataset.clientId);
            const clientNames = Array.from(checkedBoxes).map(cb => cb.dataset.clientName);
            
            const confirmMessage = clientIds.length === 1 
                ? `¿Estás seguro de que deseas borrar a ${clientNames[0]}?`
                : `¿Estás seguro de que deseas borrar ${clientIds.length} clientes?`;
            
            if (!confirm(confirmMessage)) {
                return;
            }
            
            const deleteBtn = document.getElementById('deleteSelectedBtn');
            deleteBtn.disabled = true;
            deleteBtn.innerHTML = `
                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Borrando...</span>
            `;
            
            try {
                const response = await fetch('{{ route("walee.clientes.en-proceso.delete") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        client_ids: clientIds
                    }),
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Remove deleted clients from DOM
                    checkedBoxes.forEach(checkbox => {
                        const card = checkbox.closest('.client-card');
                        if (card) {
                            card.style.transition = 'opacity 0.3s, transform 0.3s';
                            card.style.opacity = '0';
                            card.style.transform = 'translateX(-20px)';
                            setTimeout(() => card.remove(), 300);
                        }
                    });
                    
                    // Reset select all checkbox
                    const selectAllCheckbox = document.getElementById('selectAll');
                    if (selectAllCheckbox) {
                        selectAllCheckbox.checked = false;
                    }
                    updateDeleteButton();
                    
                    // Show success message
                    showNotification('Clientes borrados', `${clientIds.length} ${clientIds.length === 1 ? 'cliente ha sido' : 'clientes han sido'} borrado${clientIds.length === 1 ? '' : 's'} exitosamente`, 'success');
                } else {
                    showNotification('Error', data.message || 'Error al borrar clientes', 'error');
                }
            } catch (error) {
                showNotification('Error', 'Error de conexión: ' + error.message, 'error');
            } finally {
                deleteBtn.disabled = false;
                deleteBtn.innerHTML = `
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span id="deleteCount">Borrar (0)</span>
                `;
            }
        }
        
        // Show notification
        function showNotification(title, message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-2xl shadow-2xl transform transition-all ${
                type === 'success' ? 'bg-emerald-600' : 
                type === 'error' ? 'bg-red-600' : 
                'bg-blue-600'
            } text-white max-w-md`;
            notification.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="flex-1">
                        <p class="font-semibold">${title}</p>
                        <p class="text-sm opacity-90 mt-1">${message}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-white/70 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        }
        
        // Delete individual client
        async function deleteCliente(clienteId, clienteName) {
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            const result = await Swal.fire({
                title: '¿Eliminar cliente?',
                text: `¿Estás seguro de que deseas eliminar a ${clienteName}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
            });
            
            if (!result.isConfirmed) {
                return;
            }
            
            try {
                const response = await fetch('{{ route("walee.clientes.en-proceso.delete") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        client_ids: [clienteId]
                    }),
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Remove client from DOM
                    const card = document.querySelector(`[data-client-id="${clienteId}"]`);
                    if (card) {
                        card.style.transition = 'opacity 0.3s, transform 0.3s';
                        card.style.opacity = '0';
                        card.style.transform = 'translateX(-20px)';
                        setTimeout(() => card.remove(), 300);
                    }
                    
                    Swal.fire({
                        icon: 'success',
                        title: '¡Cliente eliminado!',
                        text: 'El cliente ha sido eliminado correctamente',
                        confirmButtonColor: '#10b981',
                        timer: 2000,
                        showConfirmButton: false,
                        background: isDarkMode ? '#1e293b' : '#ffffff',
                        color: isDarkMode ? '#e2e8f0' : '#1e293b',
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al eliminar el cliente',
                        confirmButtonColor: '#ef4444',
                        background: isDarkMode ? '#1e293b' : '#ffffff',
                        color: isDarkMode ? '#e2e8f0' : '#1e293b',
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión: ' + error.message,
                    confirmButtonColor: '#ef4444',
                    background: isDarkMode ? '#1e293b' : '#ffffff',
                    color: isDarkMode ? '#e2e8f0' : '#1e293b',
                });
            }
        }
        
        // Update client timezones
        function updateClientClocks() {
            // Obtener todos los contenedores de reloj con data-client-timezone
            document.querySelectorAll('[data-client-timezone]').forEach(container => {
                const timezone = container.getAttribute('data-client-timezone');
                if (!timezone) return;
                
                const timeElement = container.querySelector('[class*="client-time-"]');
                const dateElement = container.querySelector('[class*="client-date-"]');
                
                if (!timeElement) return;
                
                try {
                    const now = new Date(new Date().toLocaleString('en-US', { timeZone: timezone }));
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    
                    timeElement.textContent = `${hours}:${minutes}`;
                    
                    if (dateElement) {
                        const options = { month: 'short', day: 'numeric' };
                        dateElement.textContent = now.toLocaleDateString('en-US', options);
                    }
                } catch (error) {
                    console.error(`Error updating clock for timezone ${timezone}:`, error);
                }
            });
        }
        
        // Update clocks every second
        updateClientClocks();
        setInterval(updateClientClocks, 1000);
    </script>
    @include('partials.walee-support-button')
</body>
</html>

