<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - {{ $cliente->name }}</title>
    <meta name="description" content="Supplier details">
    <meta name="theme-color" content="#D59F3B">
    @include('partials.walee-dark-mode-init')
    @include('partials.walee-violet-light-mode')
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // DEFINIR openEmailModal INMEDIATAMENTE al cargar la página
        // Esto asegura que esté disponible incluso si hay errores en otros scripts
        console.log('=== SCRIPT INLINE: Definiendo openEmailModal placeholder ===');
        window.openEmailModal = function() {
            console.log('=== PLACEHOLDER openEmailModal ejecutado ===');
            console.log('Esperando a que se defina la función real...');
            // Si la función real no está definida, mostrar mensaje
            if (typeof window._realOpenEmailModal === 'function') {
                console.log('Llamando a función real...');
                window._realOpenEmailModal();
            } else {
                console.error('ERROR: Función real no está disponible aún');
                alert('La función de email se está cargando. Por favor, espera un momento y vuelve a intentar.');
            }
        };
        console.log('Placeholder definido:', typeof window.openEmailModal);
    </script>
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
                            400: '#D59F3B',
                            500: '#C78F2E',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        * { font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif; }
        @keyframes fadeInUp {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .animate-fade-in-up { animation: fadeInUp 0.3s ease-out forwards; }
        
        /* Asegurar que el card de perfil use todo el ancho en mobile */
        @media (max-width: 640px) {
            /* Contenedor principal de la página - usar selector de atributo */
            div[class*="max-w-[90rem]"] {
                width: 100% !important;
                max-width: 100% !important;
            }
            
            /* Contenedor principal del header */
            .header-profesional-wrapper {
                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
            
            /* Card de perfil */
            .header-profesional-card {
                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;
                box-sizing: border-box !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
            
            /* Todos los contenedores dentro del card */
            .header-profesional-card > div {
                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;
            }
            
            /* Contenedor flex del layout mobile - usar selector más específico */
            .header-profesional-card > div.block {
                width: 100% !important;
                max-width: 100% !important;
                display: block !important;
            }
            
            .header-profesional-card > div.block > div {
                width: 100% !important;
                max-width: 100% !important;
            }
        }
        
        /* Mejorar scroll en mobile */
        @media (max-width: 640px) {
            body {
                overflow-y: auto !important;
                -webkit-overflow-scrolling: touch;
            }
        }
        
        /* Asegurar que el modal esté encima de todo */
        .swal2-container {
            z-index: 999999 !important;
        }
        .swal2-backdrop-show {
            z-index: 999998 !important;
            background-color: rgba(0, 0, 0, 0.75) !important;
        }
        .swal2-popup {
            z-index: 999999 !important;
        }
        .dark-swal {
            background: #1e293b !important;
            color: #e2e8f0 !important;
        }
        .light-swal {
            background: #ffffff !important;
            color: #1e293b !important;
        }
        .dark-swal-title {
            color: #f1f5f9 !important;
        }
        .light-swal-title {
            color: #0f172a !important;
        }
        .dark-swal-html {
            color: #cbd5e1 !important;
        }
        .light-swal-html {
            color: #334155 !important;
        }
        .swal2-actions {
            flex-direction: row !important;
            justify-content: flex-end !important;
        }
        .swal2-confirm {
            background-color: #8b5cf6 !important;
            border-color: #8b5cf6 !important;
            color: white !important;
            order: 2 !important;
            margin-left: 10px !important;
        }
        .swal2-cancel {
            order: 1 !important;
        }
        .swal2-confirm:hover {
            background-color: #7c3aed !important;
            border-color: #7c3aed !important;
        }
        
        /* Estilos para mobile - ancho completo con poco espacio */
        @media (max-width: 640px) {
            .swal2-popup {
                width: 95% !important;
                margin: 0.5rem !important;
                padding: 1.25rem !important;
                max-height: 90vh !important;
                display: flex !important;
                flex-direction: column !important;
            }
            .swal2-popup .swal2-html-container {
                flex: 1;
                overflow-y: auto;
                max-height: calc(90vh - 140px);
                padding: 0 !important;
                width: 100% !important;
            }
            .swal2-popup .swal2-html-container form {
                width: 100% !important;
                max-width: 100% !important;
            }
            .swal2-popup .swal2-html-container form > div {
                width: 100% !important;
                max-width: 100% !important;
            }
            .swal2-popup .swal2-html-container select,
            .swal2-popup .swal2-html-container input[type="text"],
            .swal2-popup .swal2-html-container input[type="email"],
            .swal2-popup .swal2-html-container textarea {
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
            }
            .swal2-popup .swal2-title {
                font-size: 1.25rem !important;
                margin-bottom: 1rem !important;
            }
            .swal2-html-container-mobile .grid {
                gap: 0.75rem !important;
            }
            .swal2-html-container-mobile > * {
                width: 100% !important;
            }
            /* Mejorar botones en mobile - siempre visibles */
            .swal2-actions {
                margin: 0 !important;
                padding: 0.75rem !important;
                gap: 0.75rem !important;
                border-top: 1px solid rgba(203, 213, 225, 0.3) !important;
                flex-shrink: 0 !important;
                background: inherit !important;
            }
            html.dark .swal2-actions {
                border-top-color: rgba(51, 65, 85, 0.5) !important;
            }
            .swal2-confirm,
            .swal2-cancel {
                flex: 1 !important;
                padding: 0.75rem 1rem !important;
                font-size: 0.875rem !important;
                font-weight: 600 !important;
                border-radius: 0.5rem !important;
                transition: all 0.2s !important;
                margin: 0 !important;
            }
            .swal2-confirm {
                background: #D59F3B !important;
                border: none !important;
                box-shadow: 0 2px 8px rgba(213, 159, 59, 0.3) !important;
                color: #ffffff !important;
            }
            .swal2-confirm:hover {
                background: #C78F2E !important;
                transform: translateY(-1px) !important;
                box-shadow: 0 4px 12px rgba(213, 159, 59, 0.4) !important;
            }
            .swal2-cancel {
                background: #f1f5f9 !important;
                color: #475569 !important;
                border: 1px solid #e2e8f0 !important;
            }
            .swal2-cancel:hover {
                background: #e2e8f0 !important;
            }
            html.dark .swal2-cancel {
                background: #1e293b !important;
                color: #cbd5e1 !important;
                border-color: #334155 !important;
            }
            html.dark .swal2-cancel:hover {
                background: #334155 !important;
            }
        }
        
        /* Estilos para desktop - modal más ancho y compacto */
        @media (min-width: 1024px) {
            .swal2-popup {
                max-height: 90vh !important;
                overflow-y: auto !important;
            }
            .swal2-html-container {
                max-height: calc(90vh - 120px) !important;
                overflow-y: auto !important;
            }
        }
        
        /* Asegurar que el modal respete el tema dark/light */
        html.dark .swal2-popup {
            background-color: #0f172a !important;
            color: #e2e8f0 !important;
            border: 1px solid rgba(213, 159, 59, 0.2) !important;
        }
        
        html.dark .swal2-title {
            color: #e2e8f0 !important;
        }
        
        html.dark .swal2-html-container {
            color: #e2e8f0 !important;
        }
        
        html.dark .swal2-html-container label {
            color: #cbd5e1 !important;
        }
        
        html.dark .swal2-html-container input,
        html.dark .swal2-html-container textarea,
        html.dark .swal2-html-container select {
            background-color: #1e293b !important;
            border-color: #475569 !important;
            color: #e2e8f0 !important;
        }
        
        html.dark .swal2-html-container input:focus,
        html.dark .swal2-html-container textarea:focus,
        html.dark .swal2-html-container select:focus {
            border-color: #D59F3B !important;
            outline-color: #D59F3B !important;
            ring-color: #D59F3B !important;
            background-color: #334155 !important;
        }
        
        /* Light mode - fondo blanco */
        html:not(.dark) .swal2-popup {
            background-color: #ffffff !important;
            color: #1e293b !important;
            border: 1px solid rgba(203, 213, 225, 0.5) !important;
        }
        
        html:not(.dark) .swal2-title {
            color: #1e293b !important;
        }
        
        html:not(.dark) .swal2-html-container {
            color: #1e293b !important;
        }
        
        html:not(.dark) .swal2-html-container label {
            color: #334155 !important;
        }
        
        html:not(.dark) .swal2-html-container input,
        html:not(.dark) .swal2-html-container textarea,
        html:not(.dark) .swal2-html-container select {
            background-color: #ffffff !important;
            border-color: #cbd5e1 !important;
            color: #1e293b !important;
        }
        
        html:not(.dark) .swal2-html-container input:focus,
        html:not(.dark) .swal2-html-container textarea:focus,
        html:not(.dark) .swal2-html-container select:focus {
            border-color: #D59F3B !important;
            outline-color: #D59F3B !important;
            background-color: #f8fafc !important;
        }
        
        /* Estilos para la sección de Template HTML */
        .template-content {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        .template-content img {
            max-width: 100% !important;
            height: auto !important;
            display: block;
            margin: 1rem 0;
        }
        
        .template-content table {
            width: 100% !important;
            max-width: 100% !important;
            display: block;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin: 1rem 0;
        }
        
        .template-content table td,
        .template-content table th {
            padding: 0.5rem;
            word-wrap: break-word;
        }
        
        .template-content iframe,
        .template-content video,
        .template-content embed {
            max-width: 100% !important;
            height: auto !important;
        }
        
        .template-content pre {
            overflow-x: auto;
            word-wrap: break-word;
            white-space: pre-wrap;
        }
        
        .template-content code {
            word-wrap: break-word;
            white-space: pre-wrap;
        }
        
        /* Responsive para mobile */
        @media (max-width: 640px) {
            .template-content {
                font-size: 0.875rem;
            }
            
            .template-content h1,
            .template-content h2,
            .template-content h3,
            .template-content h4,
            .template-content h5,
            .template-content h6 {
                font-size: 1.1em;
                margin-top: 0.75rem;
                margin-bottom: 0.5rem;
            }
            
            .template-content p {
                margin-bottom: 0.75rem;
            }
            
            .template-content ul,
            .template-content ol {
                padding-left: 1.25rem;
                margin-bottom: 0.75rem;
            }
        }
        
        /* Estilos para la sección de Website */
        .website-iframe {
            display: block;
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white min-h-screen transition-colors duration-200">
    @php
        use App\Models\PropuestaPersonalizada;
        
        // Obtener teléfono para WhatsApp (intentar múltiples campos)
        $phone = $cliente->telefono_1 ?: $cliente->telefono_2 ?: $cliente->phone;
        $cleanPhone = $phone ? preg_replace('/[^0-9]/', '', $phone) : null;
        // Si el teléfono no empieza con código de país, agregar código por defecto (ej: 52 para México, 1 para USA)
        if ($cleanPhone && strlen($cleanPhone) == 10 && !str_starts_with($cleanPhone, '1') && !str_starts_with($cleanPhone, '52')) {
            // Asumir código de país por defecto si es necesario
            // $cleanPhone = '52' . $cleanPhone; // Descomentar y ajustar según necesidad
        }
        $whatsappLink = $cleanPhone ? "https://wa.me/{$cleanPhone}" : null;
        
        // Variables de emails ya están disponibles desde la ruta
        $emailsColor = ($emailsEnviados ?? 0) >= 3 ? 'text-red-400' : (($emailsEnviados ?? 0) >= 1 ? 'text-amber-400' : 'text-slate-500');
        $emailsBg = ($emailsEnviados ?? 0) >= 3 ? 'bg-red-500/20' : (($emailsEnviados ?? 0) >= 1 ? 'bg-amber-500/20' : 'bg-slate-800/50');
        $emailsBorder = ($emailsEnviados ?? 0) >= 3 ? 'border-red-500/30' : (($emailsEnviados ?? 0) >= 1 ? 'border-amber-500/30' : 'border-slate-700');
        
        // Determinar zona horaria del cliente basada en ciudad y address
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
        ];
        
        $langTimezones = [
            'es' => 'Europe/Madrid', 'en' => 'America/New_York', 'fr' => 'Europe/Paris',
            'de' => 'Europe/Berlin', 'it' => 'Europe/Rome', 'pt' => 'Europe/Lisbon'
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
        
        // Determinar zona horaria: primero por ciudad (campo ciudad), luego por address/direccion, luego por país, luego por idioma
        $clientTimezone = null;
        $timezoneSource = null; // Para el tooltip
        
        // 1. Buscar por campo ciudad
        if ($cliente->ciudad) {
            $clientTimezone = $findCityInText($cliente->ciudad);
            if ($clientTimezone) {
                $timezoneSource = 'ciudad: ' . $cliente->ciudad;
            }
        }
        
        // 2. Si no se encontró, buscar en el campo address
        if (!$clientTimezone && $cliente->address) {
            $clientTimezone = $findCityInText($cliente->address);
            if ($clientTimezone) {
                $timezoneSource = 'address: ' . (strlen($cliente->address) > 30 ? substr($cliente->address, 0, 30) . '...' : $cliente->address);
            }
        }
        
        // 3. Si no se encontró, buscar en el campo direccion
        if (!$clientTimezone && $cliente->direccion) {
            $clientTimezone = $findCityInText($cliente->direccion);
            if ($clientTimezone) {
                $timezoneSource = 'direccion: ' . (strlen($cliente->direccion) > 30 ? substr($cliente->direccion, 0, 30) . '...' : $cliente->direccion);
            }
        }
        
        // 4. Si no se encontró ciudad, buscar país en address
        if (!$clientTimezone && $cliente->address) {
            $clientTimezone = $findCountryInText($cliente->address);
            if ($clientTimezone) {
                $timezoneSource = 'address (país)';
            }
        }
        
        // 5. Si no se encontró, buscar país en direccion
        if (!$clientTimezone && $cliente->direccion) {
            $clientTimezone = $findCountryInText($cliente->direccion);
            if ($clientTimezone) {
                $timezoneSource = 'direccion (país)';
            }
        }
        
        // 6. Si no se encontró, buscar país en campo ciudad
        if (!$clientTimezone && $cliente->ciudad) {
            $clientTimezone = $findCountryInText($cliente->ciudad);
            if ($clientTimezone) {
                $timezoneSource = 'ciudad (país)';
            }
        }
        
        // 7. Si no se encontró, usar idioma como fallback
        if (!$clientTimezone && $cliente->idioma && isset($langTimezones[$cliente->idioma])) {
            $clientTimezone = $langTimezones[$cliente->idioma];
            $timezoneSource = 'idioma: ' . $cliente->idioma;
        }
        
        // Obtener hora del sistema (Costa Rica)
        $systemTimezone = 'America/Costa_Rica';
        $systemTime = null;
        $systemDate = null;
        try {
            $systemNow = new \DateTime('now', new \DateTimeZone($systemTimezone));
            $systemTime = $systemNow->format('H:i');
            $systemDate = $systemNow->format('d/m');
        } catch (\Exception $e) {}
        
        // Obtener hora del cliente
        $clientTime = null;
        $clientDate = null;
        if ($clientTimezone) {
            try {
                $clientNow = new \DateTime('now', new \DateTimeZone($clientTimezone));
                $clientTime = $clientNow->format('H:i');
                $clientDate = $clientNow->format('d/m');
            } catch (\Exception $e) {}
        }
    @endphp

    <div class="min-h-screen relative flex flex-col">
        <!-- Background -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-400/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 -left-20 w-60 h-60 bg-walee-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Navbar -->
        <div class="relative max-w-[90rem] mx-auto px-4 py-6 sm:px-6 lg:px-8">
            @php $pageTitle = $cliente->name; @endphp
            @include('partials.walee-navbar')
        </div>
            
        <!-- Contenido principal -->
        <div class="relative max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8 pb-6 flex flex-col">
            @include('partials.walee-back-button')
            <!-- Botón del extractor fuera de la sección, esquina superior derecha (desactivado) -->
            <div class="absolute top-0 right-4 sm:right-6 z-30">
                <span class="inline-flex items-center justify-center gap-2 px-3 py-2 sm:px-4 sm:py-2.5 rounded-lg bg-gray-400/80 backdrop-blur-sm text-white border border-white/20 transition-all shadow-lg cursor-not-allowed opacity-60" title="Extractor desactivado">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                    <span class="text-sm sm:text-base font-medium">Extractor</span>
                </span>
            </div>
            
            <!-- Header Profesional -->
            <div class="mb-3 sm:mb-4 lg:mb-6 flex flex-col w-full header-profesional-wrapper">
                <div class="relative w-full bg-white dark:bg-slate-900/60 rounded-2xl lg:rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-none header-profesional-card">
                    @php
                        $fotoPath = $cliente->foto ?? null;
                        $fotoUrl = null;
                        
                        if ($fotoPath) {
                            if (\Illuminate\Support\Str::startsWith($fotoPath, ['http://', 'https://'])) {
                                $fotoUrl = $fotoPath;
                            } else {
                                $filename = basename($fotoPath);
                                $fotoUrl = route('storage.clientes', ['filename' => $filename]);
                            }
                        }
                    @endphp
                    
                    <!-- Botones de editar y configuraciones en esquina superior izquierda -->
                    <div class="absolute top-3 left-3 sm:top-4 sm:left-4 z-20 flex items-center gap-2">
                        <button onclick="openEditClientModal()" class="inline-flex items-center justify-center gap-1.5 px-2.5 py-1.5 sm:px-3 sm:py-2 rounded-lg bg-black/50 hover:bg-black/70 backdrop-blur-sm text-white border border-white/20 transition-all shadow-lg">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button onclick="openConfiguracionesModal()" class="inline-flex items-center justify-center gap-1.5 px-2.5 py-1.5 sm:px-3 sm:py-2 rounded-lg bg-violet-600/80 hover:bg-violet-600 backdrop-blur-sm text-white border border-white/20 transition-all shadow-lg" title="Configuraciones">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Mobile: Layout reorganizado -->
                    <div class="block sm:hidden w-full">
                        <div class="flex items-start gap-3 p-3 w-full">
                            <!-- Imagen a la izquierda -->
                            <div class="relative w-1/2 aspect-square flex-shrink-0">
                    @if($fotoUrl)
                                    <img src="{{ $fotoUrl }}" alt="{{ $cliente->name }}" class="w-full h-full object-cover rounded-xl">
                    @else
                                    <img src="https://images.icon-icons.com/1188/PNG/512/1490201150-client_82317.png" alt="{{ $cliente->name }}" class="w-full h-full object-cover rounded-xl opacity-80">
                    @endif
                            </div>
                            
                            <!-- Nombre y estado a la derecha -->
                    <div class="flex-1 min-w-0">
                                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white break-words mb-2">{{ $cliente->name }}</h1>
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Status:</span>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-full border border-emerald-600 dark:border-emerald-500/30 w-fit mb-1.5">
                                        <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                                        {{ $cliente->estado === 'accepted' ? 'Active' : ucfirst($cliente->estado) }}
                                    </span>
                            
                            <!-- Is Active Toggle Mobile -->
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="text-xs font-medium text-slate-600 dark:text-slate-400">Is Active:</span>
                                <button 
                                    onclick="toggleIsActive({{ $cliente->id }}, {{ ($cliente->is_active ?? false) ? 'true' : 'false' }})"
                                    class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 {{ (($cliente->is_active ?? false)) ? 'bg-emerald-500 focus:ring-emerald-500' : 'bg-slate-300 dark:bg-slate-600 focus:ring-slate-500' }}"
                                    title="{{ (($cliente->is_active ?? false)) ? 'Active' : 'Inactive' }}"
                                >
                                    <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition-transform {{ (($cliente->is_active ?? false)) ? 'translate-x-5' : 'translate-x-0.5' }}"></span>
                                </button>
                                    <span class="text-xs font-medium {{ (($cliente->is_active ?? false)) ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-500 dark:text-slate-400' }}">
                                    {{ (($cliente->is_active ?? false)) ? 'Yes' : 'No' }}
                                </span>
                            </div>
                            
                            @if($cliente->horario)
                                <div id="negocioEstadoMobile" class="mb-1.5">
                                    <!-- Se actualizará con JavaScript -->
                                </div>
                            @endif
                            @if($bandera || $pais)
                                <div class="flex items-center gap-1.5">
                                    @if($bandera)
                                        <span class="text-base">{{ $bandera }}</span>
                                    @endif
                                    @if($pais)
                                        <span class="text-xs text-slate-600 dark:text-slate-400">{{ $pais }}</span>
                                    @endif
                                </div>
                            @endif
                            @if($cliente->ciudad)
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($cliente->ciudad) }}&zoom=6" target="_blank" rel="noopener noreferrer" class="text-xs text-slate-600 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors cursor-pointer">{{ $cliente->ciudad }}</a>
                                </div>
                            @endif
                            @if($cliente->idioma)
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                                    </svg>
                                    <span class="text-xs text-slate-600 dark:text-slate-400">
                                        @php
                                            $idiomas = [
                                                'es' => 'Español',
                                                'en' => 'English',
                                                'fr' => 'Français',
                                                'de' => 'Deutsch',
                                                'it' => 'Italiano',
                                                'pt' => 'Português'
                                            ];
                                            echo $idiomas[$cliente->idioma] ?? strtoupper($cliente->idioma);
                                        @endphp
                                    </span>
                                </div>
                            @endif
                            @if($cliente->industria)
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <span class="text-xs text-slate-600 dark:text-slate-400">{{ $cliente->industria }}</span>
                                </div>
                            @endif
                            @if($cliente->horario)
                                <button onclick="showHorarioModal('{{ addslashes($cliente->horario) }}', '{{ $clientTimezone ?? '' }}', '{{ $systemTimezone }}')" id="horarioBtn" class="mt-1.5 inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800/50 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-400 text-xs transition-colors border border-slate-200 dark:border-slate-700">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Schedule</span>
                                    <span id="horarioEstado" class="ml-1"></span>
                                </button>
                            @endif
                            
                            <!-- Zona Horaria Widget Mobile -->
                            @if($clientTimezone)
                                <div class="mt-1.5 bg-violet-100 dark:bg-violet-500/20 border border-violet-200 dark:border-violet-500/30 rounded-lg px-2 py-1 flex items-center gap-1.5" title="Hora local del cliente{{ $cliente->ciudad ? ' - ' . $cliente->ciudad : '' }}">
                                    <svg class="w-3 h-3 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-xs font-bold text-violet-600 dark:text-violet-400 client-time-mobile">{{ $clientTime ?? '--:--' }}</span>
                                    <span class="text-[10px] text-violet-500 dark:text-violet-400 client-date-mobile">{{ $clientDate ?? '--' }}</span>
                                </div>
                            @endif
                        </div>
                </div>
            </div>
            
                        <!-- Contacto de la empresa Mobile -->
                        @if($cliente->contacto_empresa)
                            <div class="px-3 pb-2">
                                <div class="flex items-center gap-2 p-2.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                                    <svg class="w-4 h-4 text-slate-500 dark:text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <div class="flex-1 min-w-0">
                                        <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Company Contact:</span>
                                        <p class="text-sm font-semibold text-slate-900 dark:text-white truncate">{{ $cliente->contacto_empresa }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Acciones Rápidas Mobile -->
                        <div class="px-3 pb-3">
                            <div class="grid grid-cols-4 gap-1.5">
                                <!-- Note Button -->
                                <button onclick="openNotaModal()" class="flex items-center justify-center p-2 rounded-lg bg-violet-100 dark:bg-violet-500/20 hover:bg-violet-200 dark:hover:bg-violet-500/30 text-violet-600 dark:text-violet-400 border border-violet-600 dark:border-violet-500/30 transition-all group shadow-sm" title="Note">
                                    <svg class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </button>
            
                                <!-- Email Button -->
                                <button type="button" onclick="(function(){ console.log('=== DEBUG: Click en botón de email (mobile) ==='); console.log('window.openEmailModal:', typeof window.openEmailModal); console.log('window keys con Email:', Object.keys(window).filter(k => k.toLowerCase().includes('email'))); console.log('window keys con Modal:', Object.keys(window).filter(k => k.toLowerCase().includes('modal'))); console.log('window keys con Phase:', Object.keys(window).filter(k => k.toLowerCase().includes('phase'))); try { if(typeof window.openEmailModal === 'function') { console.log('✓ Llamando a window.openEmailModal()'); window.openEmailModal(); } else { console.error('✗ ERROR: window.openEmailModal no es una función'); console.error('Tipo:', typeof window.openEmailModal); console.error('Valor:', window.openEmailModal); console.error('Todas las keys de window:', Object.keys(window).slice(0, 50)); alert('Error: La función de email no está disponible.\n\nTipo: ' + typeof window.openEmailModal + '\n\nRevisa la consola para más detalles.'); } } catch(e) { console.error('✗ EXCEPCIÓN al ejecutar:', e); console.error('Stack:', e.stack); alert('Error: ' + e.message + '\n\nRevisa la consola para más detalles.'); } })();" class="flex items-center justify-center p-2 rounded-lg bg-amber-100 dark:bg-slate-800 hover:bg-amber-200 dark:hover:bg-slate-700 text-amber-600 dark:text-walee-600 border border-amber-600 dark:border-slate-700 transition-all group shadow-sm">
                                    <svg class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform text-amber-600 dark:text-walee-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                </button>
                                
                                <!-- Facebook Button -->
                                @if($cliente->facebook)
                                    <a href="{{ $cliente->facebook }}" target="_blank" class="flex items-center justify-center p-2 rounded-lg bg-violet-100 dark:bg-slate-800 hover:bg-violet-200 dark:hover:bg-slate-700 text-violet-600 dark:text-violet-600 border border-violet-600 dark:border-slate-700 transition-all group shadow-sm">
                                        <svg class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform text-violet-600 dark:text-violet-700" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                                    </a>
                                @else
                                    <div class="flex items-center justify-center p-2 rounded-lg bg-slate-100 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 opacity-50 cursor-not-allowed" title="Agregue un link de Facebook para activar">
                                        <svg class="w-5 h-5 flex-shrink-0 text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
            </div>
                                @endif
                
                                <!-- WhatsApp Button -->
                                <button onclick="openWhatsAppModal()" 
                                        class="flex items-center justify-center p-2 rounded-lg bg-emerald-100 dark:bg-slate-800 hover:bg-emerald-200 dark:hover:bg-slate-700 text-emerald-600 dark:text-emerald-600 border border-emerald-600 dark:border-slate-700 transition-all group shadow-sm {{ !$whatsappLink ? 'opacity-60 cursor-not-allowed' : '' }}"
                                        {{ !$whatsappLink ? 'disabled' : '' }}>
                                    <svg class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform text-emerald-600 dark:text-emerald-700" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                </button>
                            </div>
                            </div>
                        
                        <!-- Alertas/Información -->
                        <div class="px-3 pb-3 space-y-1.5">
                            @php
                                $totalPublicaciones = $publicacionesProgramadas + $publicacionesPublicadas;
                                $totalCitas = $citasPendientes->count() + $citasPasadas->count();
                            @endphp
                            
                            
                            <!-- Appointments -->
                            <a href="{{ route('walee.calendario', ['cliente_id' => $cliente->id]) }}" class="flex items-center justify-between p-2.5 rounded-lg bg-amber-100 dark:bg-walee-500/10 border border-amber-600 dark:border-walee-500/20 hover:bg-amber-200 dark:hover:bg-walee-500/20 transition-colors cursor-pointer">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-amber-600 dark:text-walee-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2zm-7 5h5v5h-5v-5z"/>
                            </svg>
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Appointments</span>
                        </div>
                                <span class="text-sm font-semibold text-amber-700 dark:text-walee-400">{{ $totalCitas }}</span>
                            </a>
                            
                            <!-- Invoices -->
                            <a href="{{ route('walee.facturas.crear') }}?cliente_id={{ $cliente->id }}" class="flex items-center justify-between p-2.5 rounded-lg bg-red-100 dark:bg-red-500/10 border border-red-600 dark:border-red-500/20 hover:bg-red-200 dark:hover:bg-red-500/20 transition-colors cursor-pointer">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Invoices</span>
                            </div>
                                <span class="text-sm font-semibold text-red-700 dark:text-red-400">{{ $facturas->count() }}</span>
                            </a>
                
                            <!-- Quotes -->
                            <a href="{{ route('walee.cotizaciones.crear') }}?cliente_id={{ $cliente->id }}" class="flex items-center justify-between p-2.5 rounded-lg bg-blue-100 dark:bg-blue-500/10 border border-blue-600 dark:border-blue-500/20 hover:bg-blue-200 dark:hover:bg-blue-500/20 transition-colors cursor-pointer">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Quotes</span>
                                </div>
                                <span class="text-sm font-semibold text-blue-700 dark:text-blue-400">{{ $cotizaciones->count() }}</span>
                            </a>
                
                            <!-- Contratos -->
                            <a href="{{ route('walee.contratos.cliente', $cliente->id) }}" class="flex items-center justify-between p-2.5 rounded-lg bg-walee-100 dark:bg-walee-500/10 border border-walee-600 dark:border-walee-500/20 hover:bg-walee-200 dark:hover:bg-walee-500/20 transition-colors cursor-pointer">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Contracts</span>
                            </div>
                                <span class="text-sm font-semibold text-walee-700 dark:text-walee-400">{{ $contratos->count() }}</span>
                            </a>
                            
                            <!-- Productos -->
                            <a href="{{ route('walee.productos.cliente', $cliente->id) }}" class="flex items-center justify-between p-2.5 rounded-lg bg-purple-100 dark:bg-purple-500/10 border border-purple-600 dark:border-purple-500/20 hover:bg-purple-200 dark:hover:bg-purple-500/20 transition-colors cursor-pointer">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Products</span>
                                </div>
                                <span class="text-sm font-semibold text-purple-700 dark:text-purple-400">{{ $productos->count() ?? 0 }}</span>
                            </a>
                            
                            <!-- Emails Enviados -->
                            <a href="{{ route('walee.emails.enviados') }}?cliente_id={{ $cliente->id }}" class="flex items-center justify-between p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 hover:bg-emerald-500/20 transition-colors cursor-pointer">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Emails Enviados</span>
                                </div>
                                <span class="text-sm font-semibold text-emerald-700 dark:text-emerald-400">{{ $emailsEnviados ?? 0 }}</span>
                            </a>
                        </div>
                    </div>
                
                    <!-- Desktop: Layout original -->
                    <div class="hidden sm:block p-4 sm:p-6 lg:p-8">
                        <div class="flex flex-col gap-4 lg:gap-6">
                            <!-- Header con imagen y nombre -->
                            <div class="flex items-start gap-3 sm:gap-4 lg:gap-6">
                                <!-- Imagen -->
                                @if($fotoUrl)
                                    <img src="{{ $fotoUrl }}" alt="{{ $cliente->name }}" class="w-20 h-20 lg:w-24 lg:h-24 rounded-xl lg:rounded-2xl object-cover border-3 border-emerald-500/30 flex-shrink-0 shadow-md">
                        @else
                                    <img src="https://images.icon-icons.com/1188/PNG/512/1490201150-client_82317.png" alt="{{ $cliente->name }}" class="w-20 h-20 lg:w-24 lg:h-24 rounded-xl lg:rounded-2xl object-cover border-3 border-emerald-500/30 flex-shrink-0 shadow-md opacity-80">
                @endif
                
                                <!-- Nombre y estado a la derecha -->
                        <div class="flex-1 min-w-0">
                                    <h1 class="text-2xl lg:text-4xl font-bold text-slate-900 dark:text-white mb-2 sm:mb-3 truncate">{{ $cliente->name }}</h1>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Status:</span>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-semibold bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-full border border-emerald-600 dark:border-emerald-500/30 w-fit mb-2">
                                            <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                                            {{ $cliente->estado === 'accepted' ? 'Active' : ucfirst($cliente->estado) }}
                                        </span>
                                    
                                    <!-- Is Active Toggle Desktop -->
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Is Active:</span>
                                        <button 
                                            onclick="toggleIsActive({{ $cliente->id }}, {{ ($cliente->is_active ?? false) ? 'true' : 'false' }})"
                                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 {{ (($cliente->is_active ?? false)) ? 'bg-emerald-500 focus:ring-emerald-500' : 'bg-slate-300 dark:bg-slate-600 focus:ring-slate-500' }}"
                                            title="{{ (($cliente->is_active ?? false)) ? 'Active' : 'Inactive' }}"
                                        >
                                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ (($cliente->is_active ?? false)) ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                        </button>
                                        <span class="text-sm font-medium {{ (($cliente->is_active ?? false)) ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-500 dark:text-slate-400' }}">
                                            {{ (($cliente->is_active ?? false)) ? 'Yes' : 'No' }}
                                        </span>
                                    </div>
                                    
                            @if($cliente->horario)
                                <div id="negocioEstadoDesktop" class="mb-2">
                                    <!-- Se actualizará con JavaScript -->
                                </div>
                            @endif
                            @if($bandera || $pais)
                                <div class="flex items-center gap-1.5">
                                    @if($bandera)
                                        <span class="text-lg">{{ $bandera }}</span>
                                    @endif
                                    @if($pais)
                                        <span class="text-sm text-slate-600 dark:text-slate-400">{{ $pais }}</span>
                                    @endif
                                </div>
                            @endif
                            @if($cliente->ciudad)
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($cliente->ciudad) }}&zoom=6" target="_blank" rel="noopener noreferrer" class="text-sm text-slate-600 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors cursor-pointer">{{ $cliente->ciudad }}</a>
                            </div>
                            @endif
                            @if($cliente->idioma)
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                                    </svg>
                                    <span class="text-sm text-slate-600 dark:text-slate-400">
                                        @php
                                            $idiomas = [
                                                'es' => 'Español',
                                                'en' => 'English',
                                                'fr' => 'Français',
                                                'de' => 'Deutsch',
                                                'it' => 'Italiano',
                                                'pt' => 'Português'
                                            ];
                                            echo $idiomas[$cliente->idioma] ?? strtoupper($cliente->idioma);
                                        @endphp
                                    </span>
                                </div>
                            @endif
                            @if($cliente->industria)
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <span class="text-sm text-slate-600 dark:text-slate-400">{{ $cliente->industria }}</span>
                                </div>
                            @endif
                            @if($cliente->horario)
                                <button onclick="showHorarioModal('{{ addslashes($cliente->horario) }}', '{{ $clientTimezone ?? '' }}', '{{ $systemTimezone }}')" id="horarioBtnDesktop" class="mt-2 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 dark:bg-slate-800/50 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-400 text-xs transition-colors border border-slate-200 dark:border-slate-700">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Schedule</span>
                                    <span id="horarioEstadoDesktop" class="ml-1"></span>
                                </button>
                            @endif
                            
                            <!-- Zona Horaria Widget Desktop -->
                            @if($clientTimezone)
                                <div class="mt-2 bg-violet-100 dark:bg-violet-500/20 border border-violet-200 dark:border-violet-500/30 rounded-lg px-2.5 py-1.5 flex items-center gap-1.5" title="Hora local del cliente{{ $cliente->ciudad ? ' - ' . $cliente->ciudad : '' }}">
                                    <svg class="w-3.5 h-3.5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-xs font-bold text-violet-600 dark:text-violet-400 client-time-desktop">{{ $clientTime ?? '--:--' }}</span>
                                    <span class="text-[10px] text-violet-500 dark:text-violet-400 client-date-desktop">{{ $clientDate ?? '--' }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                            <!-- Contacto de la empresa Desktop -->
                            @if($cliente->contacto_empresa)
                                <div class="mb-3">
                                    <div class="flex items-center gap-2.5 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                                        <svg class="w-5 h-5 text-slate-500 dark:text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <div class="flex-1 min-w-0">
                                            <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Company Contact:</span>
                                            <p class="text-sm font-semibold text-slate-900 dark:text-white truncate">{{ $cliente->contacto_empresa }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Acciones Rápidas Desktop -->
                            <div class="flex flex-wrap gap-2.5">
                                <!-- Note Button -->
                                <button onclick="openNotaModal()" class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-xl bg-gradient-to-br from-violet-50 to-violet-100/50 dark:from-violet-500/10 dark:to-violet-600/5 hover:from-violet-100 hover:to-violet-200/50 dark:hover:from-violet-500/20 dark:hover:to-violet-600/10 text-violet-700 dark:text-violet-400 border border-violet-200/50 dark:border-violet-500/20 hover:border-violet-300 dark:hover:border-violet-500/30 transition-all group shadow-sm hover:shadow-md active:scale-[0.98]" title="Note">
                                    <svg class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span class="text-sm font-semibold">Note</span>
                                </button>
            
                                <!-- Email Button -->
                                <button type="button" onclick="(function(){ console.log('=== DEBUG: Click en botón de email (desktop) ==='); console.log('window.openEmailModal:', typeof window.openEmailModal); console.log('window keys con Email:', Object.keys(window).filter(k => k.toLowerCase().includes('email'))); console.log('window keys con Modal:', Object.keys(window).filter(k => k.toLowerCase().includes('modal'))); console.log('window keys con Phase:', Object.keys(window).filter(k => k.toLowerCase().includes('phase'))); try { if(typeof window.openEmailModal === 'function') { console.log('✓ Llamando a window.openEmailModal()'); window.openEmailModal(); } else { console.error('✗ ERROR: window.openEmailModal no es una función'); console.error('Tipo:', typeof window.openEmailModal); console.error('Valor:', window.openEmailModal); console.error('Todas las keys de window:', Object.keys(window).slice(0, 50)); alert('Error: La función de email no está disponible.\n\nTipo: ' + typeof window.openEmailModal + '\n\nRevisa la consola para más detalles.'); } } catch(e) { console.error('✗ EXCEPCIÓN al ejecutar:', e); console.error('Stack:', e.stack); alert('Error: ' + e.message + '\n\nRevisa la consola para más detalles.'); } })();" class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-xl bg-gradient-to-br from-amber-50 to-amber-100/50 dark:from-amber-500/10 dark:to-amber-600/5 hover:from-amber-100 hover:to-amber-200/50 dark:hover:from-amber-500/20 dark:hover:to-amber-600/10 text-amber-700 dark:text-amber-400 border border-amber-200/50 dark:border-amber-500/20 hover:border-amber-300 dark:hover:border-amber-500/30 transition-all group shadow-sm hover:shadow-md active:scale-[0.98]">
                                    <svg class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-sm font-semibold">Email</span>
                                </button>
                                
                                <!-- Facebook Button -->
                                @if($cliente->facebook)
                                    <a href="{{ $cliente->facebook }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-xl bg-gradient-to-br from-violet-50 to-violet-100/50 dark:from-violet-500/10 dark:to-violet-600/5 hover:from-violet-100 hover:to-violet-200/50 dark:hover:from-violet-500/20 dark:hover:to-violet-600/10 text-violet-700 dark:text-violet-400 border border-violet-200/50 dark:border-violet-500/20 hover:border-violet-300 dark:hover:border-violet-500/30 transition-all group shadow-sm hover:shadow-md active:scale-[0.98]">
                                        <svg class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                        <span class="text-sm font-semibold">Facebook</span>
                                    </a>
                                @else
                                    <div class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/30 border border-slate-200 dark:border-slate-700/50 opacity-50 cursor-not-allowed" title="Agregue un link de Facebook para activar">
                                        <svg class="w-5 h-5 flex-shrink-0 text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                        <span class="text-sm font-semibold text-slate-400">Facebook</span>
                                    </div>
                                @endif
                    
                                <!-- WhatsApp Button -->
                                <button onclick="openWhatsAppModal()" 
                                        class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-xl bg-gradient-to-br from-emerald-50 to-emerald-100/50 dark:from-emerald-500/10 dark:to-emerald-600/5 hover:from-emerald-100 hover:to-emerald-200/50 dark:hover:from-emerald-500/20 dark:hover:to-emerald-600/10 text-emerald-700 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-500/20 hover:border-emerald-300 dark:hover:border-emerald-500/30 transition-all group shadow-sm hover:shadow-md active:scale-[0.98] {{ !$whatsappLink ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        {{ !$whatsappLink ? 'disabled' : '' }}>
                                    <svg class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                    </svg>
                                    <span class="text-sm font-semibold">WhatsApp</span>
                                </button>
                            </div>
            
                            <!-- Alertas Desktop -->
                            <div class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-7 gap-3">
                                @php
                                    $totalPublicaciones = $publicacionesProgramadas + $publicacionesPublicadas;
                                    $totalCitas = $citasPendientes->count() + $citasPasadas->count();
                                @endphp
                                
                                
                                <!-- Appointments -->
                                <a href="{{ route('walee.calendario', ['cliente_id' => $cliente->id]) }}" class="flex items-center justify-between p-3 rounded-xl bg-walee-500/10 border border-walee-500/20 hover:bg-walee-500/20 transition-colors cursor-pointer">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-amber-600 dark:text-walee-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2zm-7 5h5v5h-5v-5z"/>
                                                </svg>
                                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Appointments</span>
                                            </div>
                                    <span class="text-sm font-semibold text-amber-700 dark:text-walee-400">{{ $totalCitas }}</span>
                    </a>
                    
                                <!-- Invoices -->
                                <a href="{{ route('walee.facturas.crear') }}?cliente_id={{ $cliente->id }}" class="flex items-center justify-between p-3 rounded-xl bg-red-500/10 border border-red-500/20 hover:bg-red-500/20 transition-colors cursor-pointer">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Invoices</span>
                            </div>
                                    <span class="text-sm font-semibold text-red-700 dark:text-red-400">{{ $facturas->count() }}</span>
                    </a>
                    
                                <!-- Quotes -->
                                <a href="{{ route('walee.cotizaciones.crear') }}?cliente_id={{ $cliente->id }}" class="block w-full flex items-center justify-between p-3 rounded-xl bg-blue-500/10 border border-blue-500/20 hover:bg-blue-500/20 transition-colors cursor-pointer">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Quotes</span>
                                            </div>
                                    <span class="text-sm font-semibold text-blue-700 dark:text-blue-400">{{ $cotizaciones->count() }}</span>
                    </a>
                    
                                <!-- Contratos -->
                                <a href="{{ route('walee.contratos.cliente', $cliente->id) }}" class="flex items-center justify-between p-3 rounded-xl bg-walee-500/10 border border-walee-500/20 hover:bg-walee-500/20 transition-colors cursor-pointer">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Contracts</span>
                                            </div>
                                    <span class="text-sm font-semibold text-walee-700 dark:text-walee-400">{{ $contratos->count() }}</span>
                                            </a>
                                            
                                <!-- Productos -->
                                <a href="{{ route('walee.productos.cliente', $cliente->id) }}" class="flex items-center justify-between p-3 rounded-xl bg-purple-500/10 border border-purple-500/20 hover:bg-purple-500/20 transition-colors cursor-pointer">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Products</span>
                                    </div>
                                    <span class="text-sm font-semibold text-purple-700 dark:text-purple-400">{{ $productos->count() ?? 0 }}</span>
                                </a>
                                
                                <!-- Emails Enviados -->
                                <a href="{{ route('walee.emails.enviados') }}?cliente_id={{ $cliente->id }}" class="flex items-center justify-between p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 hover:bg-emerald-500/20 transition-colors cursor-pointer">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Emails Enviados</span>
                                    </div>
                                    <span class="text-sm font-semibold text-emerald-700 dark:text-emerald-400">{{ $emailsEnviados ?? 0 }}</span>
                                </a>
                                        </div>
                                        </div>
                                    </div>
                </div>
            </div>
            
            <!-- Google Maps -->
            @php
                // Priorizar dirección, si no hay usar ciudad
                $ubicacion = null;
                $tipoUbicacion = '';
                
                // Intentar obtener direccion de diferentes formas
                $direccion = $cliente->getAttribute('direccion') ?? $cliente->direccion ?? $cliente->getAttribute('address') ?? $cliente->address ?? null;
                
                if (!empty($direccion)) {
                    $ubicacion = trim($direccion);
                    $tipoUbicacion = 'Dirección';
                } elseif (!empty($cliente->ciudad)) {
                    $ubicacion = trim($cliente->ciudad);
                    $tipoUbicacion = 'Ciudad';
                }
            @endphp
            @if($ubicacion)
            <div class="mb-4 sm:mb-6 mt-4 sm:mt-6">
                <div class="bg-white dark:bg-slate-800/50 rounded-xl shadow-lg overflow-hidden border border-slate-200 dark:border-slate-700">
                    <div class="p-3 sm:p-4 border-b border-slate-200 dark:border-slate-700">
                        <h3 class="text-sm sm:text-base font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $tipoUbicacion }} - {{ $ubicacion }}
                        </h3>
                    </div>
                    <div class="w-full" style="height: 300px;">
                        <iframe
                            width="100%"
                            height="100%"
                            style="border:0"
                            loading="lazy"
                            allowfullscreen
                            referrerpolicy="no-referrer-when-downgrade"
                            src="https://www.google.com/maps?q={{ urlencode($ubicacion) }}&output=embed&zoom={{ !empty($cliente->direccion) ? '15' : '13' }}">
                        </iframe>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Template HTML Section -->
            @if($cliente->template && !empty(trim($cliente->template)))
            <div class="mb-4 sm:mb-6 mt-4 sm:mt-6">
                <div class="bg-white dark:bg-slate-800/50 rounded-xl shadow-lg overflow-hidden border border-slate-200 dark:border-slate-700">
                    <div class="p-3 sm:p-4 border-b border-slate-200 dark:border-slate-700">
                        <h3 class="text-sm sm:text-base font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Template HTML
                        </h3>
                    </div>
                    <div class="p-3 sm:p-4 sm:p-6">
                        <div class="template-content prose prose-sm sm:prose-base max-w-none dark:prose-invert prose-headings:text-slate-900 dark:prose-headings:text-white prose-p:text-slate-700 dark:prose-p:text-slate-300 prose-a:text-violet-600 dark:prose-a:text-violet-400 prose-strong:text-slate-900 dark:prose-strong:text-white prose-img:rounded-lg prose-img:shadow-md">
                            {!! $cliente->template !!}
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Website Section -->
            @if($cliente->website && !empty(trim($cliente->website)))
            @php
                $websiteUrl = trim($cliente->website);
                // Asegurar que tenga protocolo
                if (!str_starts_with($websiteUrl, 'http://') && !str_starts_with($websiteUrl, 'https://')) {
                    $websiteUrl = 'https://' . $websiteUrl;
                }
            @endphp
            <div class="mb-4 sm:mb-6 mt-4 sm:mt-6">
                <div class="bg-white dark:bg-slate-800/50 rounded-xl shadow-lg overflow-hidden border border-slate-200 dark:border-slate-700">
                    <div class="p-3 sm:p-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                        <h3 class="text-sm sm:text-base font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                            </svg>
                            Sitio Web
                        </h3>
                        <a 
                            href="{{ $websiteUrl }}" 
                            target="_blank" 
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs sm:text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 rounded-lg transition-colors border border-blue-200 dark:border-blue-800"
                        >
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            <span class="hidden sm:inline">Abrir en nueva pestaña</span>
                            <span class="sm:hidden">Abrir</span>
                        </a>
                    </div>
                    <div class="w-full h-[400px] sm:h-[500px] lg:h-[600px]">
                        <iframe
                            src="{{ $websiteUrl }}"
                            width="100%"
                            height="100%"
                            style="border:0"
                            loading="lazy"
                            allowfullscreen
                            referrerpolicy="no-referrer-when-downgrade"
                            class="website-iframe"
                        ></iframe>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- World Map with Clocks -->
            @include('partials.walee-world-map-clocks')
            
            <!-- Footer -->
            <footer class="text-center py-4 sm:py-6 mt-4 sm:mt-6">
                <p class="text-[10px] sm:text-sm text-slate-600 dark:text-slate-500">
                    <span class="text-walee-400 font-medium">Walee</span> · websolutions.work
                </p>
            </footer>
        </div>
    </div>
    @include('partials.walee-support-button')
    
    <script>
        // Pull to refresh functionality
        (function() {
            let startY = 0;
            let currentY = 0;
            let isPulling = false;
            let pullDistance = 0;
            const pullThreshold = 80; // Distancia mínima para activar refresh
            const maxPullDistance = 120;
            
            const refreshIndicator = document.createElement('div');
            refreshIndicator.id = 'pull-to-refresh-indicator';
            refreshIndicator.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                height: 60px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, #D59F3B 0%, #C78F2E 100%);
                color: white;
                font-weight: 600;
                font-size: 14px;
                z-index: 10000;
                transform: translateY(-100%);
                transition: transform 0.3s ease;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            `;
            refreshIndicator.innerHTML = `
                <svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <span id="refresh-text">Arrastra hacia abajo para actualizar</span>
            `;
            document.body.appendChild(refreshIndicator);
            
            const refreshText = document.getElementById('refresh-text');
            const refreshIcon = refreshIndicator.querySelector('svg');
            
            function handleTouchStart(e) {
                if (window.scrollY === 0) {
                    startY = e.touches[0].clientY;
                    isPulling = true;
                }
            }
            
            function handleTouchMove(e) {
                if (!isPulling) return;
                
                currentY = e.touches[0].clientY;
                pullDistance = currentY - startY;
                
                if (pullDistance > 0 && window.scrollY === 0) {
                    e.preventDefault();
                    const pullPercent = Math.min(pullDistance / maxPullDistance, 1);
                    const translateY = Math.min(pullDistance, maxPullDistance) - 60;
                    
                    refreshIndicator.style.transform = `translateY(${translateY}px)`;
                    
                    if (pullDistance >= pullThreshold) {
                        refreshText.textContent = 'Suelta para actualizar';
                        refreshIndicator.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
                    } else {
                        refreshText.textContent = 'Arrastra hacia abajo para actualizar';
                        refreshIndicator.style.background = 'linear-gradient(135deg, #D59F3B 0%, #C78F2E 100%)';
                    }
                } else {
                    resetPull();
                }
            }
            
            function handleTouchEnd(e) {
                if (!isPulling) return;
                
                if (pullDistance >= pullThreshold) {
                    refreshText.textContent = 'Actualizando...';
                    refreshIcon.style.display = 'block';
                    refreshIndicator.style.background = 'linear-gradient(135deg, #D59F3B 0%, #C78F2E 100%)';
                    
                    setTimeout(() => {
                        window.location.reload();
                    }, 300);
                } else {
                    resetPull();
                }
                
                isPulling = false;
                startY = 0;
                currentY = 0;
                pullDistance = 0;
            }
            
            function resetPull() {
                refreshIndicator.style.transform = 'translateY(-100%)';
                refreshText.textContent = 'Arrastra hacia abajo para actualizar';
                refreshIcon.style.display = 'none';
                refreshIndicator.style.background = 'linear-gradient(135deg, #D59F3B 0%, #C78F2E 100%)';
            }
            
            // También soportar scroll con mouse (para desktop)
            let lastScrollTop = 0;
            function handleScroll() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                if (scrollTop < lastScrollTop && scrollTop === 0 && !isPulling) {
                    // Scroll hacia arriba desde la parte superior
                    const pullPercent = Math.min(Math.abs(scrollTop - lastScrollTop) / 10, 1);
                    if (pullPercent > 0.5) {
                        refreshIndicator.style.transform = 'translateY(0)';
                        refreshText.textContent = 'Suelta para actualizar';
                        
                        setTimeout(() => {
                            if (window.scrollY === 0) {
                                refreshText.textContent = 'Actualizando...';
                                refreshIcon.style.display = 'block';
                                setTimeout(() => {
                                    window.location.reload();
                                }, 300);
                            }
                        }, 500);
                    }
                }
                
                lastScrollTop = scrollTop;
            }
            
            document.addEventListener('touchstart', handleTouchStart, { passive: false });
            document.addEventListener('touchmove', handleTouchMove, { passive: false });
            document.addEventListener('touchend', handleTouchEnd);
            window.addEventListener('scroll', handleScroll);
        })();
        
        function showWhatsAppError() {
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            Swal.fire({
                icon: 'info',
                title: 'Número no disponible',
                text: 'Este cliente no tiene un número de teléfono registrado. Por favor, edita el cliente para agregar un número de teléfono.',
                confirmButtonText: 'Editar Cliente',
                confirmButtonColor: '#10b981',
                cancelButtonText: 'Cerrar',
                showCancelButton: true,
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                    cancelButton: isDarkMode ? 'dark-swal-cancel' : 'light-swal-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    openEditClientModal();
                }
            });
        }
        
        function openWhatsAppModal() {
            @if(!$whatsappLink)
                showWhatsAppError();
                return;
            @endif
            
            const isMobile = window.innerWidth < 640;
            const isDesktop = window.innerWidth >= 1024;
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            const clienteName = @json($cliente->name ?? 'Cliente');
            const whatsappLink = @json($whatsappLink);
            
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
        
        function showCitasTab(tabName) {
            // Hide all citas tab contents
            document.querySelectorAll('.tab-content-citas').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active class from all citas tabs
            document.querySelectorAll('.tab-button-citas').forEach(button => {
                button.classList.remove('active', 'text-walee-400', 'border-walee-400');
                button.classList.add('text-slate-500', 'dark:text-slate-400', 'border-transparent');
            });
            
            // Show selected tab content
            document.getElementById('content-citas-' + tabName).classList.remove('hidden');
            
            // Add active class to selected tab
            const selectedTab = document.getElementById('tab-citas-' + tabName);
            if (selectedTab) {
                selectedTab.classList.add('active', 'text-walee-400', 'border-walee-400');
                selectedTab.classList.remove('text-slate-500', 'dark:text-slate-400', 'border-transparent');
            }
        }
        
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active', 'text-walee-400', 'border-walee-400');
                button.classList.add('text-slate-500', 'dark:text-slate-400', 'border-transparent');
            });
            
            // Show selected tab content
            document.getElementById('content-' + tabName).classList.remove('hidden');
            
            // Add active class to selected tab
            const activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.add('active', 'text-walee-400', 'border-walee-400');
            activeTab.classList.remove('text-slate-500', 'dark:text-slate-400', 'border-transparent');
        }
        
        // Modal para editar cliente
        function openEditClientModal() {
            const isMobile = window.innerWidth < 640;
            const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;
            const isDesktop = window.innerWidth >= 1024;
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            // Datos del cliente
            @php
                $fotoPath = $cliente->foto ?? null;
                $fotoUrl = null;
                if ($fotoPath) {
                    if (\Illuminate\Support\Str::startsWith($fotoPath, ['http://', 'https://'])) {
                        $fotoUrl = $fotoPath;
                    } else {
                        $filename = basename($fotoPath);
                        $fotoUrl = route('storage.clientes', ['filename' => $filename]);
                    }
                }
            @endphp
            
        // Función para determinar si está abierto o cerrado
        function getEstadoHorario(horario, clientTimezone) {
            try {
                let horarios = [];
                if (typeof horario === 'string') {
                    // Intentar parsear como JSON
                    try {
                        horarios = JSON.parse(horario);
                    } catch (e) {
                        // Si no es JSON válido, intentar parsear manualmente
                        horarios = horario.replace(/^\[|\]$/g, '').split(',').map(h => h.trim().replace(/^"|"$/g, ''));
                    }
                } else if (Array.isArray(horario)) {
                    horarios = horario;
                } else {
                    return { abierto: null, texto: 'N/A' };
                }
                
                // Obtener hora actual en la zona horaria del cliente
                let ahora;
                let diaActual;
                let horaActual;
                let minutoActual;
                
                if (clientTimezone) {
                    try {
                        // Usar Intl.DateTimeFormat para obtener hora y día en la zona horaria del cliente
                        const now = new Date();
                        const timeFormatter = new Intl.DateTimeFormat('en-US', {
                            timeZone: clientTimezone,
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: false,
                            weekday: 'long'
                        });
                        
                        const dateFormatter = new Intl.DateTimeFormat('en-US', {
                            timeZone: clientTimezone,
                            day: 'numeric',
                            month: 'numeric',
                            year: 'numeric'
                        });
                        
                        const timeParts = timeFormatter.formatToParts(now);
                        horaActual = parseInt(timeParts.find(part => part.type === 'hour').value);
                        minutoActual = parseInt(timeParts.find(part => part.type === 'minute').value);
                        
                        // Obtener día de la semana usando el formatter
                        const weekdayFormatter = new Intl.DateTimeFormat('es-ES', {
                            timeZone: clientTimezone,
                            weekday: 'long'
                        });
                        const diaNombreCompleto = weekdayFormatter.format(now).toLowerCase();
                        
                        // Mapeo de días en español a número
                        const diasMapReverse = {
                            'domingo': 0,
                            'lunes': 1,
                            'martes': 2,
                            'miércoles': 3,
                            'jueves': 4,
                            'viernes': 5,
                            'sábado': 6
                        };
                        diaActual = diasMapReverse[diaNombreCompleto] ?? new Date().getDay();
                        ahora = now;
                    } catch (e) {
                        console.error('Error obteniendo hora del cliente:', e);
                        // Fallback a hora del sistema
                        ahora = new Date();
                        diaActual = ahora.getDay();
                        horaActual = ahora.getHours();
                        minutoActual = ahora.getMinutes();
                    }
                } else {
                    // Si no hay zona horaria del cliente, usar hora del sistema
                    ahora = new Date();
                    diaActual = ahora.getDay();
                    horaActual = ahora.getHours();
                    minutoActual = ahora.getMinutes();
                }
                
                const horaActualDecimal = horaActual + (minutoActual / 60);
                
                // Mapeo de días
                const diasMap = {
                    0: 'domingo',
                    1: 'lunes',
                    2: 'martes',
                    3: 'miércoles',
                    4: 'jueves',
                    5: 'viernes',
                    6: 'sábado'
                };
                
                const diaNombre = diasMap[diaActual];
                const horarioDia = horarios.find(h => {
                    const hStr = String(h).toLowerCase();
                    return hStr.startsWith(diaNombre.toLowerCase());
                });
                
                if (!horarioDia) return { abierto: false, texto: 'Cerrado' };
                
                const partes = String(horarioDia).split(':');
                const horas = partes.slice(1).join(':').trim();
                
                // Parsear rangos de horas (ej: "19:00–0:30" o "12:00–16:00, 19:00–0:30")
                const rangos = horas.split(',').map(r => r.trim());
                
                for (const rango of rangos) {
                    const [inicio, fin] = rango.split('–').map(h => h.trim());
                    if (!inicio || !fin) continue;
                    
                    const [horaInicio, minutoInicio] = inicio.split(':').map(Number);
                    const [horaFin, minutoFin] = fin.split(':').map(Number);
                    
                    let horaInicioDecimal = horaInicio + (minutoInicio / 60);
                    let horaFinDecimal = horaFin + (minutoFin / 60);
                    
                    // Si la hora de fin es menor que la de inicio, significa que cruza medianoche
                    if (horaFinDecimal < horaInicioDecimal) {
                        horaFinDecimal += 24;
                        // Si la hora actual es antes de medianoche, ajustar
                        if (horaActualDecimal < horaInicioDecimal) {
                            horaActualDecimal += 24;
                        }
                    }
                    
                    if (horaActualDecimal >= horaInicioDecimal && horaActualDecimal <= horaFinDecimal) {
                        return { abierto: true, texto: 'Abierto' };
                    }
                }
                
                return { abierto: false, texto: 'Cerrado' };
            } catch (e) {
                console.error('Error al calcular estado del horario:', e);
                return { abierto: null, texto: 'N/A' };
            }
        }
        
        // Actualizar estado del horario al cargar
        @if($cliente->horario)
        document.addEventListener('DOMContentLoaded', function() {
            const horario = @json($cliente->horario);
            const clientTimezone = '{{ $clientTimezone ?? '' }}';
            const estado = getEstadoHorario(horario, clientTimezone);
            
            // Indicador principal del negocio (destacado)
            const negocioEstadoMobile = document.getElementById('negocioEstadoMobile');
            const negocioEstadoDesktop = document.getElementById('negocioEstadoDesktop');
            
            if (estado.abierto === true) {
                const badgePrincipal = '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-full border border-emerald-600 dark:border-emerald-500/30 w-fit"><div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>Negocio Abierto</span>';
                if (negocioEstadoMobile) negocioEstadoMobile.innerHTML = badgePrincipal;
                if (negocioEstadoDesktop) negocioEstadoDesktop.innerHTML = badgePrincipal;
            } else if (estado.abierto === false) {
                const badgePrincipal = '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400 rounded-full border border-red-600 dark:border-red-500/30 w-fit"><div class="w-2 h-2 rounded-full bg-red-400"></div>Negocio Cerrado</span>';
                if (negocioEstadoMobile) negocioEstadoMobile.innerHTML = badgePrincipal;
                if (negocioEstadoDesktop) negocioEstadoDesktop.innerHTML = badgePrincipal;
            }
            
            // Badge pequeño en el botón de horario
            const estadoMobile = document.getElementById('horarioEstado');
            const estadoDesktop = document.getElementById('horarioEstadoDesktop');
            const btnMobile = document.getElementById('horarioBtn');
            const btnDesktop = document.getElementById('horarioBtnDesktop');
            
            if (estado.abierto === true) {
                const badge = '<span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300">Abierto</span>';
                if (estadoMobile) estadoMobile.innerHTML = badge;
                if (estadoDesktop) estadoDesktop.innerHTML = badge;
                if (btnMobile) btnMobile.classList.add('border-emerald-300', 'dark:border-emerald-700');
                if (btnDesktop) btnDesktop.classList.add('border-emerald-300', 'dark:border-emerald-700');
            } else if (estado.abierto === false) {
                const badge = '<span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300">Cerrado</span>';
                if (estadoMobile) estadoMobile.innerHTML = badge;
                if (estadoDesktop) estadoDesktop.innerHTML = badge;
                if (btnMobile) btnMobile.classList.add('border-red-300', 'dark:border-red-700');
                if (btnDesktop) btnDesktop.classList.add('border-red-300', 'dark:border-red-700');
            }
            
            // Función para actualizar el estado del horario
            function updateEstadoHorario() {
                const nuevoEstado = getEstadoHorario(horario, clientTimezone);
                
                // Actualizar indicador principal
                if (nuevoEstado.abierto === true) {
                    const badgePrincipal = '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-full border border-emerald-600 dark:border-emerald-500/30 w-fit"><div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>Negocio Abierto</span>';
                    if (negocioEstadoMobile) negocioEstadoMobile.innerHTML = badgePrincipal;
                    if (negocioEstadoDesktop) negocioEstadoDesktop.innerHTML = badgePrincipal;
                } else if (nuevoEstado.abierto === false) {
                    const badgePrincipal = '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400 rounded-full border border-red-600 dark:border-red-500/30 w-fit"><div class="w-2 h-2 rounded-full bg-red-400"></div>Negocio Cerrado</span>';
                    if (negocioEstadoMobile) negocioEstadoMobile.innerHTML = badgePrincipal;
                    if (negocioEstadoDesktop) negocioEstadoDesktop.innerHTML = badgePrincipal;
                }
                
                // Actualizar badges en botones
                if (nuevoEstado.abierto === true) {
                    const badge = '<span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300">Abierto</span>';
                    if (estadoMobile) estadoMobile.innerHTML = badge;
                    if (estadoDesktop) estadoDesktop.innerHTML = badge;
                    if (btnMobile) {
                        btnMobile.classList.remove('border-red-300', 'dark:border-red-700');
                        btnMobile.classList.add('border-emerald-300', 'dark:border-emerald-700');
                    }
                    if (btnDesktop) {
                        btnDesktop.classList.remove('border-red-300', 'dark:border-red-700');
                        btnDesktop.classList.add('border-emerald-300', 'dark:border-emerald-700');
                    }
                } else if (nuevoEstado.abierto === false) {
                    const badge = '<span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300">Cerrado</span>';
                    if (estadoMobile) estadoMobile.innerHTML = badge;
                    if (estadoDesktop) estadoDesktop.innerHTML = badge;
                    if (btnMobile) {
                        btnMobile.classList.remove('border-emerald-300', 'dark:border-emerald-700');
                        btnMobile.classList.add('border-red-300', 'dark:border-red-700');
                    }
                    if (btnDesktop) {
                        btnDesktop.classList.remove('border-emerald-300', 'dark:border-emerald-700');
                        btnDesktop.classList.add('border-red-300', 'dark:border-red-700');
                    }
                }
            }
            
            // Actualizar estado del horario cada minuto
            setInterval(updateEstadoHorario, 60000);
        });
        @endif
            
            const clienteData = {
                fotoUrl: @json($fotoUrl),
                name: @json($cliente->name ?? ''),
                email: @json($cliente->email ?? ''),
                telefono_1: @json($cliente->telefono_1 ?? ''),
                website: @json($cliente->website ?? ''),
                facebook: @json($cliente->facebook ?? ''),
                estado: @json($cliente->estado ?? 'pending'),
                ciudad: @json($cliente->ciudad ?? ''),
                idioma: @json($cliente->idioma ?? ''),
                feedback: @json($cliente->feedback ?? ''),
                inicial: @json(strtoupper(substr($cliente->name, 0, 1)))
            };
            
            const html = `
                <form id="editClientForm" class="space-y-3 sm:space-y-2.5 text-left">
                    <!-- Foto -->
                    <div class="mb-3 sm:mb-3">
                        <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-2 sm:mb-2">Foto del Cliente</label>
                        <div class="flex items-start gap-3 sm:gap-3">
                            <div class="flex-shrink-0 relative">
                                <div id="fotoPreviewContainer" class="w-20 h-20 sm:w-20 sm:h-20 rounded-xl overflow-hidden border-2 border-emerald-500/30 shadow-sm">
                                    ${clienteData.fotoUrl ? 
                                        `<img src="${clienteData.fotoUrl}" alt="Foto" id="fotoPreview" class="w-full h-full object-cover">` :
                                        `<img src="https://images.icon-icons.com/1188/PNG/512/1490201150-client_82317.png" alt="Foto" id="fotoPreview" class="w-full h-full object-cover opacity-80">`
                                    }
                                </div>
                                ${clienteData.fotoUrl ? `
                                    <button type="button" onclick="deleteClientPhoto()" class="absolute -top-1 -right-1 w-6 h-6 sm:w-6 sm:h-6 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center transition-all shadow-lg z-10" title="Eliminar foto">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                ` : ''}
                            </div>
                            <div class="flex-1 flex flex-col gap-2">
                                <div class="flex gap-2">
                                    <label for="foto_file" class="cursor-pointer flex-1 inline-flex items-center justify-center ${isMobile ? 'gap-1.5' : 'gap-1.5 sm:gap-2'} ${isMobile ? 'px-3 py-2' : 'px-2.5 sm:px-3 py-1.5 sm:py-2'} rounded-lg bg-walee-400/20 hover:bg-walee-400/30 text-walee-400 border border-walee-400/30 transition-all text-xs sm:text-sm font-medium">
                                        <svg class="${isMobile ? 'w-4 h-4' : 'w-3.5 h-3.5 sm:w-4 sm:h-4'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-xs">Cambiar</span>
                                    </label>
                                    ${clienteData.fotoUrl ? `
                                        <button type="button" onclick="deleteClientPhoto()" class="flex-1 inline-flex items-center justify-center ${isMobile ? 'gap-1.5' : 'gap-1.5 sm:gap-2'} ${isMobile ? 'px-3 py-2' : 'px-2.5 sm:px-3 py-1.5 sm:py-2'} rounded-lg bg-red-500/20 hover:bg-red-500/30 text-red-400 border border-red-500/30 transition-all text-xs sm:text-sm font-medium">
                                            <svg class="${isMobile ? 'w-4 h-4' : 'w-3.5 h-3.5 sm:w-4 sm:h-4'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            <span class="text-xs">Eliminar</span>
                                        </button>
                                    ` : ''}
                                </div>
                                <input type="file" name="foto_file" id="foto_file" accept="image/*" class="hidden" onchange="previewClientImage(this)">
                                <input type="hidden" name="delete_foto" id="delete_foto" value="0">
                                <p class="text-[10px] text-slate-500 dark:text-slate-400">JPG, PNG o GIF. Máx 2MB</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 ${isDesktop ? 'lg:grid-cols-3' : 'sm:grid-cols-2'} gap-3 sm:gap-2.5">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1 sm:mb-1.5">Nombre *</label>
                            <input type="text" id="clientName" name="name" required value="${clienteData.name}"
                                   class="w-full px-2.5 sm:px-3 py-1.5 sm:py-2 text-xs sm:text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-400">
                        </div>
                        
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1 sm:mb-1.5">Email</label>
                            <input type="email" id="clientEmail" name="email" value="${clienteData.email}"
                                   class="w-full px-2.5 sm:px-3 py-1.5 sm:py-2 text-xs sm:text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-400">
                    </div>
                    
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1 sm:mb-1.5">Estado</label>
                            <select id="clientEstado" name="estado"
                                    class="w-full px-2.5 sm:px-3 py-1.5 sm:py-2 text-xs sm:text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-400">
                                <option value="pending" ${clienteData.estado === 'pending' ? 'selected' : ''}>Pendiente</option>
                                <option value="contactado" ${clienteData.estado === 'contactado' ? 'selected' : ''}>Contactado</option>
                                <option value="propuesta_enviada" ${clienteData.estado === 'propuesta_enviada' ? 'selected' : ''}>Propuesta Enviada</option>
                                <option value="accepted" ${clienteData.estado === 'accepted' ? 'selected' : ''}>Aceptado</option>
                                <option value="activo" ${clienteData.estado === 'activo' ? 'selected' : ''}>Activo</option>
                                <option value="rechazado" ${clienteData.estado === 'rechazado' ? 'selected' : ''}>Rechazado</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 ${isDesktop ? 'lg:grid-cols-3' : 'sm:grid-cols-2'} gap-3 sm:gap-2.5">
                    <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-1.5">Teléfono</label>
                            <input type="tel" id="clientTelefono1" name="telefono_1" value="${clienteData.telefono_1}"
                                   class="w-full px-3 sm:px-3 py-2 sm:py-2 text-sm sm:text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-400">
                    </div>
                    
                    <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-1.5">Sitio Web</label>
                            <input type="url" id="clientWebsite" name="website" value="${clienteData.website || ''}"
                                   class="w-full px-3 sm:px-3 py-2 sm:py-2 text-sm sm:text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-400">
                        </div>
                        
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-1.5">Facebook</label>
                            <input type="url" id="clientFacebook" name="facebook" value="${clienteData.facebook || ''}"
                                   class="w-full px-3 sm:px-3 py-2 sm:py-2 text-sm sm:text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-400">
                        </div>
                        
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-1.5">Ciudad</label>
                            <input type="text" id="clientCiudad" name="ciudad" value="${clienteData.ciudad || ''}"
                                   class="w-full px-3 sm:px-3 py-2 sm:py-2 text-sm sm:text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-400">
                        </div>
                        
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-1.5">Idioma</label>
                            <select id="clientIdioma" name="idioma"
                                    class="w-full px-3 sm:px-3 py-2 sm:py-2 text-sm sm:text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-400">
                                <option value="">Seleccionar idioma</option>
                                <option value="es" ${clienteData.idioma === 'es' ? 'selected' : ''}>Español</option>
                                <option value="en" ${clienteData.idioma === 'en' ? 'selected' : ''}>English</option>
                                <option value="fr" ${clienteData.idioma === 'fr' ? 'selected' : ''}>Français</option>
                                <option value="de" ${clienteData.idioma === 'de' ? 'selected' : ''}>Deutsch</option>
                                <option value="it" ${clienteData.idioma === 'it' ? 'selected' : ''}>Italiano</option>
                                <option value="pt" ${clienteData.idioma === 'pt' ? 'selected' : ''}>Português</option>
                            </select>
                        </div>
                    </div>
                </form>
            `;
            
            let modalWidth = '98%';
            if (isDesktop) {
                modalWidth = '750px';
            } else if (isTablet) {
                modalWidth = '600px';
            } else if (isMobile) {
                modalWidth = '98%';
            }
            
            Swal.fire({
                title: '',
                html: html,
                width: modalWidth,
                padding: isMobile ? '0.75rem' : (isDesktop ? '1.25rem' : '1.5rem'),
                titleText: '',
                customClass: {
                    container: isMobile ? 'swal2-container-mobile' : '',
                    popup: isMobile ? 'swal2-popup-mobile' : (isDarkMode ? 'dark-swal' : 'light-swal'),
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isMobile ? 'swal2-html-container-mobile' : (isDarkMode ? 'dark-swal-html' : 'light-swal-html'),
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                    cancelButton: isDarkMode ? 'dark-swal-cancel' : 'light-swal-cancel'
                },
                showCancelButton: true,
                confirmButtonText: 'Save',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#D59F3B',
                reverseButtons: true,
                didOpen: () => {
                    // Aplicar tema dark/light al modal
                    const isDark = document.documentElement.classList.contains('dark');
                    const popup = document.querySelector('.swal2-popup');
                    const container = document.querySelector('.swal2-html-container');
                    const title = document.querySelector('.swal2-title');
                    
                    if (isDark) {
                        if (popup) {
                            popup.style.backgroundColor = '#0f172a';
                            popup.style.color = '#e2e8f0';
                            popup.style.border = '1px solid rgba(213, 159, 59, 0.2)';
                        }
                        if (title) {
                            title.style.color = '#e2e8f0';
                        }
                        if (container) {
                            container.style.color = '#e2e8f0';
                        }
                        // Aplicar estilos a inputs, textareas y selects
                        const inputs = container?.querySelectorAll('input, textarea, select');
                        inputs?.forEach(el => {
                            el.style.backgroundColor = '#1e293b';
                            el.style.borderColor = '#475569';
                            el.style.color = '#e2e8f0';
                        });
                        // Aplicar estilos a labels
                        const labels = container?.querySelectorAll('label');
                        labels?.forEach(el => {
                            el.style.color = '#cbd5e1';
                        });
                    } else {
                        if (popup) {
                            popup.style.backgroundColor = '#ffffff';
                            popup.style.color = '#1e293b';
                            popup.style.border = '1px solid rgba(203, 213, 225, 0.5)';
                        }
                        if (title) {
                            title.style.color = '#1e293b';
                        }
                        if (container) {
                            container.style.color = '#1e293b';
                        }
                        // Aplicar estilos a inputs, textareas y selects
                        const inputs = container?.querySelectorAll('input, textarea, select');
                        inputs?.forEach(el => {
                            el.style.backgroundColor = '#ffffff';
                            el.style.borderColor = '#cbd5e1';
                            el.style.color = '#1e293b';
                        });
                        // Aplicar estilos a labels
                        const labels = container?.querySelectorAll('label');
                        labels?.forEach(el => {
                            el.style.color = '#334155';
                        });
                    }
                    
                    document.getElementById('clientName')?.focus();
                },
                preConfirm: () => {
                    const form = document.getElementById('editClientForm');
                    const formData = new FormData(form);
                    
                    // Validar nombre requerido
                    if (!formData.get('name') || formData.get('name').trim() === '') {
                        Swal.showValidationMessage('El nombre es requerido');
                        return false;
                    }
                    
                    return formData;
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    updateClient(result.value);
                }
            });
        }
        
        function previewClientImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const container = document.getElementById('fotoPreviewContainer');
                    container.innerHTML = `<img src="${e.target.result}" alt="Preview" id="fotoPreview" class="w-full h-full object-cover">`;
                    
                    // Resetear el flag de eliminación si se sube una nueva foto
                    const deleteFotoInput = document.getElementById('delete_foto');
                    if (deleteFotoInput) {
                        deleteFotoInput.value = '0';
                    }
                    
                    // Ocultar botones de eliminar si existen
                    const deleteButtons = container.parentElement.querySelectorAll('button[onclick="deleteClientPhoto()"]');
                    deleteButtons.forEach(btn => btn.style.display = 'none');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        function deleteClientPhoto() {
            const container = document.getElementById('fotoPreviewContainer');
            const deleteFotoInput = document.getElementById('delete_foto');
            const fotoFileInput = document.getElementById('foto_file');
            
            // Mostrar imagen genérica
            container.innerHTML = `<img src="https://images.icon-icons.com/1188/PNG/512/1490201150-client_82317.png" alt="Foto" id="fotoPreview" class="w-full h-full object-cover opacity-80">`;
            
            // Marcar para eliminar
            if (deleteFotoInput) {
                deleteFotoInput.value = '1';
            }
            
            // Limpiar input de archivo
            if (fotoFileInput) {
                fotoFileInput.value = '';
            }
            
            // Ocultar botones de eliminar
            const deleteButtons = container.parentElement.querySelectorAll('button[onclick="deleteClientPhoto()"]');
            deleteButtons.forEach(btn => btn.style.display = 'none');
        }
        
        async function updateClient(formData) {
            try {
                const response = await fetch('{{ route("walee.cliente.actualizar", $cliente->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Cliente actualizado!',
                        text: 'Los cambios se han guardado correctamente',
                        confirmButtonColor: '#10b981',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '{{ route("walee.supplier.detalle", $cliente->id) }}';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.message || 'Error al actualizar el cliente',
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión. Por favor, intenta de nuevo.',
                    confirmButtonColor: '#ef4444'
                });
            }
        }
        
        // Función para actualizar estado del cliente
        async function updateClientStatus(newStatus) {
            try {
                const response = await fetch('{{ route("walee.cliente.actualizar", $cliente->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        estado: newStatus
                    })
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Estado actualizado!',
                        text: 'El estado del cliente se ha actualizado correctamente',
                        confirmButtonColor: '#D59F3B',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.message || 'Error al actualizar el estado',
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión. Por favor, intenta de nuevo.',
                    confirmButtonColor: '#ef4444'
                });
            }
        }
        
        // Función para eliminar cliente
        function deleteClient() {
            const isDarkMode = document.documentElement.classList.contains('dark');
            const clienteName = @json($cliente->name ?? 'este cliente');
            
            Swal.fire({
                icon: 'warning',
                title: '¿Eliminar cliente?',
                html: `¿Estás seguro de que deseas eliminar <strong>${clienteName}</strong>?<br><br>Esta acción no se puede deshacer.`,
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    performDeleteClient();
                }
            });
        }
        
        async function performDeleteClient() {
            try {
                const response = await fetch('{{ route("walee.clientes.en-proceso.delete") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        client_ids: [{{ $cliente->id }}]
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Cliente eliminado!',
                        text: 'El cliente ha sido eliminado correctamente',
                        confirmButtonColor: '#D59F3B',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '{{ route("walee.clientes.dashboard") }}';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.message || 'Error al eliminar el cliente',
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión. Por favor, intenta de nuevo.',
                    confirmButtonColor: '#ef4444'
                });
            }
        }
        
        // Templates de email disponibles
        const emailTemplates = @json($templates ?? []);
        
        // Variables globales para el flujo de fases
        let emailModalData = {
            clienteId: {{ $cliente->id }},
            clienteEmail: '{{ $cliente->email ?? '' }}',
            clienteName: '{{ $cliente->name }}',
            clienteWebsite: '{{ $cliente->website ?? '' }}',
            email: '',
            aiPrompt: '',
            subject: '',
            body: '',
            attachments: null
        };
        
        // DEFINIR openEmailModal INMEDIATAMENTE después de emailModalData
        // Esto asegura que esté disponible desde el inicio
        console.log('=== DEBUG: Definiendo openEmailModal REAL ===');
        console.log('emailModalData:', typeof emailModalData, emailModalData);
        
        // Definir la función real y reemplazar el placeholder
        window.openEmailModal = function() {
            console.log('=== DEBUG: openEmailModal REAL EJECUTADO ===');
            console.log('emailModalData:', typeof emailModalData, emailModalData);
            console.log('showEmailPhase1:', typeof showEmailPhase1);
            console.log('Swal:', typeof Swal);
            
            try {
                // Verificar que emailModalData esté disponible
                if (typeof emailModalData === 'undefined') {
                    console.error('ERROR: emailModalData no está definido');
                    alert('Error: Datos del modal no están disponibles. Por favor, recarga la página.');
                    return;
                }
                
                // Verificar que showEmailPhase1 esté disponible
                if (typeof showEmailPhase1 === 'undefined') {
                    console.error('ERROR: showEmailPhase1 no está definido');
                    console.error('Funciones disponibles en window:', Object.keys(window).filter(k => k.includes('Email') || k.includes('Phase')));
                    alert('Error: La función showEmailPhase1 no está disponible. Por favor, recarga la página.');
                    return;
                }
                
                // Verificar que Swal esté disponible
                if (typeof Swal === 'undefined') {
                    console.error('ERROR: Swal (SweetAlert2) no está definido');
                    alert('Error: SweetAlert2 no está disponible. Por favor, recarga la página.');
                    return;
                }
                
                // Resetear datos para un nuevo email
                emailModalData.email = emailModalData.clienteEmail;
                emailModalData.aiPrompt = '';
                emailModalData.subject = '';
                emailModalData.body = '';
                emailModalData.attachments = null;
                
                console.log('DEBUG: Llamando a showEmailPhase1');
                // Abrir desde la fase 1
                showEmailPhase1();
            } catch (error) {
                console.error('=== ERROR EN openEmailModal ===');
                console.error('Error:', error);
                console.error('Stack:', error.stack);
                console.error('Message:', error.message);
                alert('Error al abrir el modal: ' + error.message + '\n\nRevisa la consola para más detalles.');
            }
        };
        
        console.log('=== DEBUG: Función real definida ===');
        console.log('window.openEmailModal:', typeof window.openEmailModal);
        
        // También definirla localmente para compatibilidad
        const openEmailModal = window.openEmailModal;
        
        // DEBUG: Verificar inmediatamente que esté disponible
        console.log('=== DEBUG: Después de definir openEmailModal ===');
        console.log('window.openEmailModal:', typeof window.openEmailModal);
        console.log('openEmailModal local:', typeof openEmailModal);
        console.log('Verificando que esté en window:', 'openEmailModal' in window);
        
        // DEBUG: Intentar llamar la función para verificar que funciona
        if (typeof window.openEmailModal === 'function') {
            console.log('✓ window.openEmailModal está definido correctamente');
        } else {
            console.error('✗ ERROR: window.openEmailModal NO está definido correctamente');
            console.error('Tipo actual:', typeof window.openEmailModal);
        }
        
        // Toggle is_active
        async function toggleIsActive(clientId, currentValue) {
            console.log('toggleIsActive llamado con clientId:', clientId, 'currentValue:', currentValue);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const newValue = !currentValue;
            
            try {
                const response = await fetch(`/walee-clientes-en-proceso/${clientId}/toggle-active`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        is_active: newValue
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Recargar la página para reflejar el cambio
                    location.reload();
                } else {
                    alert('Error al actualizar el estado: ' + (data.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error en toggleIsActive:', error);
                alert('Error de conexión: ' + error.message);
            }
        }
        
        // Asegurar que la función esté disponible globalmente
        window.toggleIsActive = toggleIsActive;
        
        function showEmailPhase1() {
            const isMobile = window.innerWidth < 640;
            const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;
            const isDesktop = window.innerWidth >= 1024;
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            let modalWidth = '95%';
            if (isDesktop) {
                modalWidth = '600px'; // Ancho más pequeño
            } else if (isTablet) {
                modalWidth = '500px';
            } else if (isMobile) {
                modalWidth = '95%';
            }
            
            // Generar opciones de templates - ORDENAR: PRIMERO LOS QUE TIENEN TIPO
            let templatesOptions = '<option value="">Seleccionar template (opcional)</option>';
            if (emailTemplates && emailTemplates.length > 0) {
                // Ordenar: primero los que tienen tipo, luego los que no tienen
                const sortedTemplates = [...emailTemplates].sort((a, b) => {
                    const aHasTipo = a.tipo && a.tipo.trim() !== '';
                    const bHasTipo = b.tipo && b.tipo.trim() !== '';
                    if (aHasTipo && !bHasTipo) return -1; // a primero
                    if (!aHasTipo && bHasTipo) return 1;  // b primero
                    return 0; // mantener orden original si ambos tienen o no tienen tipo
                });
                
                console.log('Templates ordenados:', sortedTemplates.map(t => ({ nombre: t.nombre, tipo: t.tipo })));
                
                sortedTemplates.forEach(template => {
                    // Mostrar el template siempre, con o sin tipo
                    // En el select no podemos usar colores, pero guardamos el tipo en data-attribute
                    templatesOptions += `<option value="${template.id}" data-tipo="${template.tipo || ''}">${template.nombre}${template.tipo ? ' [' + template.tipo.charAt(0).toUpperCase() + template.tipo.slice(1) + ']' : ''}</option>`;
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
                    
                    <div>
                        <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-2">Email destinatario <span class="text-red-500">*</span></label>
                        <input type="email" id="email_destinatario" value="${emailModalData.email}" required
                            class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-2">Instrucciones para AI (opcional)</label>
                        <textarea id="ai_prompt" rows="5" placeholder="Ej: Genera un email profesional..."
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
                modalWidth = '600px'; // Ancho más pequeño
            } else if (isTablet) {
                modalWidth = '500px';
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
                        <textarea id="email_body" rows="10" required placeholder="Escribe o genera el contenido del email..."
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
                modalWidth = '600px'; // Ancho más pequeño
            } else if (isTablet) {
                modalWidth = '500px';
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
                    formData.append('cliente_id', emailModalData.clienteId);
                    formData.append('email', emailModalData.email);
                    formData.append('subject', emailModalData.subject);
                    formData.append('body', emailModalData.body);
                    formData.append('ai_prompt', emailModalData.aiPrompt || '');
                    
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
                    // Mostrar mensaje y avanzar a fase 2
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
        
        function loadEmailTemplate(templateId) {
            const aiGenerateContainer = document.getElementById('ai_generate_container');
            const tipoDisplay = document.getElementById('template_tipo_display');
            
            if (!templateId || !emailTemplates) {
                // Si no hay template seleccionado, mostrar el botón de AI
                if (aiGenerateContainer) {
                    aiGenerateContainer.style.display = 'block';
                }
                // Ocultar el tipo
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
                // Ocultar el tipo
                if (tipoDisplay) {
                    tipoDisplay.style.display = 'none';
                }
                if (tipoBadgeInline) {
                    tipoBadgeInline.style.display = 'none';
                }
                return;
            }
            
            // Cargar el template en los datos del modal
            emailModalData.aiPrompt = template.ai_prompt || '';
            emailModalData.subject = template.asunto || '';
            emailModalData.body = template.contenido || '';
            
            // Actualizar los campos visibles si existen
            const aiPromptField = document.getElementById('ai_prompt');
            if (aiPromptField) {
                aiPromptField.value = emailModalData.aiPrompt;
            }
            
            // Si estamos en fase 2, actualizar también esos campos
            const subjectField = document.getElementById('email_subject');
            const bodyField = document.getElementById('email_body');
            if (subjectField) {
                subjectField.value = emailModalData.subject;
            }
            if (bodyField) {
                bodyField.value = emailModalData.body;
            }
            
            // Mostrar el tipo del template como badge (reutilizar tipoDisplay ya declarado arriba)
            // Esperar un momento para asegurar que el DOM esté listo
            setTimeout(() => {
                const tipoValue = document.getElementById('template_tipo_value');
                console.log('loadEmailTemplate - template:', template);
                console.log('loadEmailTemplate - tipoDisplay:', tipoDisplay);
                console.log('loadEmailTemplate - tipoValue:', tipoValue);
                
                // Función para obtener colores del badge según el tipo (con estilos inline como fallback)
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
                    console.log('Mostrando badge para tipo:', template.tipo);
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
                        console.log('Badge inline mostrado con className:', tipoBadgeValue.className);
                        console.log('Badge inline style:', tipoBadgeValue.style.cssText);
                        console.log('Badge inline text:', tipoBadgeValue.textContent);
                    } else {
                        console.error('tipoBadgeInline o tipoBadgeValue no encontrados');
                        console.error('tipoBadgeInline:', tipoBadgeInline);
                        console.error('tipoBadgeValue:', tipoBadgeValue);
                    }
                } else {
                    console.log('Template sin tipo, ocultando badge');
                    if (tipoDisplay) {
                        tipoDisplay.style.display = 'none';
                    }
                    if (tipoBadgeInline) {
                        tipoBadgeInline.style.display = 'none';
                    }
                }
            }, 100);
            
            // Ocultar el botón de generar con AI cuando hay template seleccionado
            if (aiGenerateContainer) {
                aiGenerateContainer.style.display = 'none';
            }
            
            // Mostrar el botón "Usar AI en su lugar"
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
            
            // Ocultar el botón "Usar AI en su lugar"
            const showAiBtn = document.getElementById('show_ai_btn');
            if (showAiBtn) {
                showAiBtn.style.display = 'none';
            }
            
            // Ocultar el tipo del template
            const tipoDisplay = document.getElementById('template_tipo_display');
            if (tipoDisplay) {
                tipoDisplay.style.display = 'none';
            }
            
            // Limpiar el template seleccionado
            const templateSelect = document.getElementById('email_template_select');
            if (templateSelect) {
                templateSelect.value = '';
            }
            
            // Limpiar los datos del template
            emailModalData.aiPrompt = '';
            emailModalData.subject = '';
            emailModalData.body = '';
            
            // Limpiar los campos
            const aiPromptField = document.getElementById('ai_prompt');
            if (aiPromptField) {
                aiPromptField.value = '';
            }
        }
        
        function showHorarioModal(horario, clientTimezone, systemTimezone) {
            const isDarkMode = document.documentElement.classList.contains('dark');
            const isMobile = window.innerWidth < 640;
            
            // Parsear el horario (puede venir como JSON string o array)
            let horarios = [];
            try {
                if (typeof horario === 'string') {
                    horarios = JSON.parse(horario);
                } else {
                    horarios = horario;
                }
            } catch (e) {
                // Si no es JSON válido, intentar parsear manualmente
                horarios = horario.split(',').map(h => h.trim().replace(/^\[|\]$/g, ''));
            }
            
            // Función para convertir hora entre zonas horarias
            const convertTime = function(timeStr, fromTZ, toTZ) {
                if (!fromTZ || !toTZ || fromTZ === toTZ) return timeStr;
                try {
                    // Parsear la hora (formato HH:MM o HH:MM-HH:MM)
                    const timeMatch = timeStr.match(/(\d{1,2}):(\d{2})(?:-(\d{1,2}):(\d{2}))?/);
                    if (!timeMatch) return timeStr;
                    
                    const startHour = parseInt(timeMatch[1]);
                    const startMin = parseInt(timeMatch[2]);
                    const endHour = timeMatch[3] ? parseInt(timeMatch[3]) : null;
                    const endMin = timeMatch[4] ? parseInt(timeMatch[4]) : null;
                    
                    // Obtener fecha de hoy en zona origen
                    const now = new Date();
                    const fromDateFormatter = new Intl.DateTimeFormat('en-US', {
                        timeZone: fromTZ,
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit'
                    });
                    const fromDateParts = fromDateFormatter.formatToParts(now);
                    const year = parseInt(fromDateParts.find(p => p.type === 'year').value);
                    const month = parseInt(fromDateParts.find(p => p.type === 'month').value) - 1;
                    const day = parseInt(fromDateParts.find(p => p.type === 'day').value);
                    
                    // Crear string de fecha ISO en zona origen y luego convertir
                    // Usar un método más simple: crear fecha local y ajustar
                    const localDate = new Date(year, month, day, startHour, startMin);
                    const localTimeStr = localDate.toLocaleString('en-US', { timeZone: fromTZ, hour: '2-digit', minute: '2-digit', hour12: false });
                    const localParts = localTimeStr.split(':');
                    const localHour = parseInt(localParts[0]);
                    const localMin = parseInt(localParts[1]);
                    
                    // Calcular offset necesario
                    const offsetMinutes = ((startHour - localHour) * 60) + (startMin - localMin);
                    const adjustedStartDate = new Date(localDate.getTime() - (offsetMinutes * 60000));
                    
                    // Formatear en zona destino
                    const toFormatter = new Intl.DateTimeFormat('en-US', {
                        timeZone: toTZ,
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    });
                    const startParts = toFormatter.formatToParts(adjustedStartDate);
                    const startHourAdj = startParts.find(p => p.type === 'hour').value;
                    const startMinAdj = startParts.find(p => p.type === 'minute').value;
                    
                    let result = `${startHourAdj}:${startMinAdj}`;
                    
                    if (endHour !== null && endMin !== null) {
                        const localEndDate = new Date(year, month, day, endHour, endMin);
                        const localEndTimeStr = localEndDate.toLocaleString('en-US', { timeZone: fromTZ, hour: '2-digit', minute: '2-digit', hour12: false });
                        const localEndParts = localEndTimeStr.split(':');
                        const localEndHour = parseInt(localEndParts[0]);
                        const localEndMin = parseInt(localEndParts[1]);
                        const endOffsetMinutes = ((endHour - localEndHour) * 60) + (endMin - localEndMin);
                        const adjustedEndDate = new Date(localEndDate.getTime() - (endOffsetMinutes * 60000));
                        const endParts = toFormatter.formatToParts(adjustedEndDate);
                        const endHourAdj = endParts.find(p => p.type === 'hour').value;
                        const endMinAdj = endParts.find(p => p.type === 'minute').value;
                        result += `-${endHourAdj}:${endMinAdj}`;
                    }
                    
                    return result;
                } catch (e) {
                    console.error('Error converting time:', e);
                    return timeStr;
                }
            };
            
            // Mapeo de días en español
            const diasOrden = ['lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo'];
            
            // Ordenar por día de la semana
            const horariosOrdenados = diasOrden.map(dia => {
                const horarioDia = horarios.find(h => h.toLowerCase().startsWith(dia.toLowerCase()));
                if (horarioDia) {
                    const partes = horarioDia.split(':');
                    const diaNombre = partes[0].trim();
                    const horas = partes.slice(1).join(':').trim();
                    
                    // Convertir horario a hora del sistema si hay zona horaria del cliente
                    let horasSistema = horas;
                    if (clientTimezone && systemTimezone && clientTimezone !== systemTimezone) {
                        horasSistema = convertTime(horas, clientTimezone, systemTimezone);
                    }
                    
                    return { dia: diaNombre, horas: horas, horasSistema: horasSistema };
                }
                return null;
            }).filter(h => h !== null);
            
            // Crear tabla HTML
            let tablaHTML = `
                <div class="overflow-x-auto">
                    <table class="w-full text-sm ${isDarkMode ? 'text-slate-200' : 'text-slate-800'}">
                        <thead>
                            <tr class="border-b ${isDarkMode ? 'border-slate-700' : 'border-slate-200'}">
                                <th class="text-left py-2 px-3 font-semibold ${isDarkMode ? 'text-slate-300' : 'text-slate-700'}">Día</th>
                                <th class="text-left py-2 px-3 font-semibold ${isDarkMode ? 'text-slate-300' : 'text-slate-700'}">Horario</th>
                            </tr>
                        </thead>
                        <tbody>
            `;
            
            horariosOrdenados.forEach((item, index) => {
                const esPar = index % 2 === 0;
                // Mostrar horario ajustado (zona horaria del sistema) primero y original entre paréntesis
                let horasDisplay;
                if (clientTimezone && systemTimezone && clientTimezone !== systemTimezone) {
                    // Mostrar horario ajustado primero y original entre paréntesis
                    horasDisplay = `${item.horasSistema} <span class="text-xs ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}">(${item.horas})</span>`;
                } else {
                    // Si no hay diferencia de zona horaria, mostrar solo el horario original
                    horasDisplay = item.horas;
                }
                
                tablaHTML += `
                    <tr class="${esPar ? (isDarkMode ? 'bg-slate-800/30' : 'bg-slate-50') : ''}">
                        <td class="py-2.5 px-3 font-medium">${item.dia.charAt(0).toUpperCase() + item.dia.slice(1)}</td>
                        <td class="py-2.5 px-3">${horasDisplay}</td>
                    </tr>
                `;
            });
            
            tablaHTML += `
                        </tbody>
                    </table>
                </div>
            `;
            
            Swal.fire({
                title: '<div class="flex items-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span>Horario</span></div>',
                html: tablaHTML,
                width: isMobile ? '90%' : '550px',
                padding: isMobile ? '1rem' : '1.5rem',
                showConfirmButton: true,
                confirmButtonText: 'Volver',
                confirmButtonColor: '#10b981',
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                }
            });
        }
        
        // Función para abrir modal de configuraciones
        function openConfiguracionesModal() {
            const isMobile = window.innerWidth < 640;
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            const clienteId = {{ $cliente->id }};
            const pageId = '{{ $cliente->page_id ?? "" }}';
            const token = '{{ $cliente->token ?? "" }}';
            const webhookDefault = 'https://n8n.srv1137974.hstgr.cloud/webhook/allaccounts';
            const webhookUrl = '{{ $cliente->webhook_url ?? "" }}' || webhookDefault;
            
            const html = `
                <form id="configuraciones-form" class="space-y-4 text-left">
                    <div>
                        <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-2">Page ID</label>
                        <input type="text" name="page_id" id="page_id" value="${pageId}" placeholder="Ingrese el Page ID de Facebook" class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-2">Token</label>
                        <input type="text" name="token" id="token" value="${token}" placeholder="Ingrese el Token de acceso" class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-2">Webhook URL</label>
                        <input type="url" name="webhook_url" id="webhook_url" value="${webhookUrl}" placeholder="${webhookDefault}" class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all">
                        <p class="text-xs ${isDarkMode ? 'text-slate-400' : 'text-slate-500'} mt-1">Por defecto: ${webhookDefault}</p>
                    </div>
                </form>
            `;
            
            Swal.fire({
                title: 'Configuraciones',
                html: html,
                width: isMobile ? '95%' : '500px',
                padding: isMobile ? '0.75rem' : '1.5rem',
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonText: 'Save',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#8b5cf6',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                reverseButtons: true,
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                customClass: {
                    popup: isMobile ? '!w-[95%] !max-w-[95%]' : '',
                    htmlContainer: isMobile ? '!w-full !max-w-full' : ''
                },
                didOpen: () => {
                    // Asegurar que los inputs usen todo el ancho en mobile
                    if (isMobile) {
                        const form = document.getElementById('configuraciones-form');
                        if (form) {
                            form.style.width = '100%';
                            form.style.maxWidth = '100%';
                        }
                    }
                },
                preConfirm: () => {
                    const pageId = document.getElementById('page_id').value;
                    const token = document.getElementById('token').value;
                    const webhookUrl = document.getElementById('webhook_url').value;
                    
                    return fetch(`/walee-cliente/${clienteId}/webhook`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            page_id: pageId,
                            token: token,
                            webhook_url: webhookUrl
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            throw new Error(data.message || 'Error saving');
                        }
                        return data;
                    })
                    .catch(error => {
                        Swal.showValidationMessage(error.message || 'Error saving configuration');
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Configuration saved!',
                        text: 'Changes have been saved successfully',
                        confirmButtonColor: '#8b5cf6',
                        background: isDarkMode ? '#1e293b' : '#ffffff',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Recargar la página para actualizar el estado del botón de Facebook
                        location.reload();
                    });
                }
            });
        }
        
        // Función para abrir modal de nota
        function openNotaModal() {
            const isMobile = window.innerWidth < 640;
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            const clienteId = {{ $cliente->id }};
            const notaActual = {!! json_encode($cliente->nota ?? '') !!};
            
            const html = `
                <form id="nota-form" class="space-y-4 text-left">
                    <div>
                        <textarea 
                            id="nota_content" 
                            name="nota" 
                            rows="10" 
                            placeholder="Write your note here..." 
                            class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all resize-none"
                            style="min-width: 100%; width: 100%; max-width: 100%;"
                        >${notaActual}</textarea>
                    </div>
                </form>
            `;
            
            Swal.fire({
                title: 'Note',
                html: html,
                width: isMobile ? '95%' : '700px',
                padding: isMobile ? '0.75rem' : '1.5rem',
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonText: 'Save',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#8b5cf6',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                reverseButtons: true,
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                customClass: {
                    popup: isMobile ? '!w-[95%] !max-w-[95%]' : '',
                    htmlContainer: isMobile ? '!w-full !max-w-full' : '',
                    confirmButton: '!bg-violet-500 hover:!bg-violet-600'
                },
                didOpen: () => {
                    // Asegurar que el textarea use todo el ancho
                    const textarea = document.getElementById('nota_content');
                    if (textarea) {
                        textarea.style.width = '100%';
                        textarea.style.minWidth = '100%';
                        textarea.style.maxWidth = '100%';
                    }
                    const form = document.getElementById('nota-form');
                    if (form) {
                        form.style.width = '100%';
                        form.style.maxWidth = '100%';
                    }
                },
                preConfirm: () => {
                    const nota = document.getElementById('nota_content').value;
                    
                    return fetch(`/walee-cliente/${clienteId}/nota`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            nota: nota
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            throw new Error(data.message || 'Error saving');
                        }
                        return data;
                    })
                    .catch(error => {
                        Swal.showValidationMessage(error.message || 'Error saving the note');
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Nota guardada!',
                        text: 'La nota se ha guardado correctamente',
                        confirmButtonColor: '#8b5cf6',
                        background: isDarkMode ? '#1e293b' : '#ffffff',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Recargar la página para actualizar la nota
                        location.reload();
                    });
                }
            });
        }
        
        // DEBUG: Verificar que todas las funciones estén disponibles después de cargar
        console.log('=== DEBUG: Verificando estado del DOM ===');
        console.log('document.readyState:', document.readyState);
        
        function verificarFunciones() {
            console.log('=== DEBUG: Verificación completa de funciones ===');
            console.log('window.openEmailModal:', typeof window.openEmailModal);
            console.log('showEmailPhase1:', typeof showEmailPhase1);
            console.log('emailModalData:', typeof emailModalData);
            console.log('Swal:', typeof Swal);
            console.log('emailTemplates:', typeof emailTemplates);
            
            // Verificar todas las propiedades de window relacionadas con email
            const emailKeys = Object.keys(window).filter(k => 
                k.toLowerCase().includes('email') || 
                k.toLowerCase().includes('modal') ||
                k.toLowerCase().includes('phase')
            );
            console.log('Propiedades relacionadas con email en window:', emailKeys);
            
            // Verificar si hay errores de sintaxis
            try {
                if (typeof window.openEmailModal === 'function') {
                    console.log('✓ window.openEmailModal está disponible y es una función');
                } else {
                    console.error('✗ ERROR: window.openEmailModal NO está disponible o no es una función');
                    console.error('Tipo:', typeof window.openEmailModal);
                    console.error('Valor:', window.openEmailModal);
                }
            } catch (e) {
                console.error('✗ ERROR al verificar window.openEmailModal:', e);
            }
        }
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                console.log('=== DEBUG: DOM cargado (DOMContentLoaded) ===');
                verificarFunciones();
            });
        } else {
            console.log('=== DEBUG: DOM ya cargado ===');
            verificarFunciones();
        }
        
        // También verificar después de un pequeño delay para asegurar que todo esté cargado
        setTimeout(function() {
            console.log('=== DEBUG: Verificación después de 500ms ===');
            verificarFunciones();
        }, 500);
        
        // Verificar cuando se hace clic en cualquier parte de la página
        document.addEventListener('click', function(e) {
            if (e.target.closest('button[onclick*="openEmailModal"]')) {
                console.log('=== DEBUG: Click detectado en botón de email ===');
                console.log('window.openEmailModal en momento del click:', typeof window.openEmailModal);
                verificarFunciones();
            }
        }, true);
        
        // Update client clocks
        @if($clientTimezone)
        function updateClientClocks() {
            const clientTimezone = '{{ $clientTimezone }}';
            
            // Update mobile clock
            const mobileTimeElement = document.querySelector('.client-time-mobile');
            const mobileDateElement = document.querySelector('.client-date-mobile');
            
            // Update desktop clock
            const desktopTimeElement = document.querySelector('.client-time-desktop');
            const desktopDateElement = document.querySelector('.client-date-desktop');
            
            if (clientTimezone) {
                try {
                    // Usar Intl.DateTimeFormat para obtener hora y fecha directamente en la zona horaria del cliente
                    const timeFormatter = new Intl.DateTimeFormat('en-US', {
                        timeZone: clientTimezone,
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    });
                    
                    const dateFormatter = new Intl.DateTimeFormat('en-US', {
                        timeZone: clientTimezone,
                        month: 'short',
                        day: 'numeric'
                    });
                    
                    const now = new Date();
                    const timeParts = timeFormatter.formatToParts(now);
                    const hours = timeParts.find(part => part.type === 'hour').value;
                    const minutes = timeParts.find(part => part.type === 'minute').value;
                    const date = dateFormatter.format(now);
                    
                    if (mobileTimeElement) mobileTimeElement.textContent = `${hours}:${minutes}`;
                    if (mobileDateElement) mobileDateElement.textContent = date;
                    if (desktopTimeElement) desktopTimeElement.textContent = `${hours}:${minutes}`;
                    if (desktopDateElement) desktopDateElement.textContent = date;
                } catch (error) {
                    console.error('Error updating client clock:', error);
                }
            }
        }
        
        // Update clocks every second
        updateClientClocks();
        setInterval(updateClientClocks, 1000);
        @endif
    </script>
</body>
</html>

