<!-- World Map with Clocks -->
<section class="mb-8">
    <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-300 mb-4 flex items-center gap-2">
        <svg class="w-5 h-5 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        Bienvenido{{ auth()->check() && auth()->user()->name ? ', ' . auth()->user()->name : '' }}
    </h2>
    
    <div class="bg-white dark:bg-slate-900/50 rounded-2xl shadow-lg border border-black dark:border-black overflow-hidden">
        <div class="p-4 sm:p-6">
            @php
                use App\Models\UserWorldClock;

                // Opciones disponibles para los relojes (tarjetas) – con banderas
                $worldClockOptions = [
                    'montreal'     => '🇨🇦 Montreal',
                    'london'       => '🇬🇧 London',
                    'tokyo'        => '🇯🇵 Tokyo',
                    'sydney'       => '🇦🇺 Sydney',
                    'dubai'        => '🇦🇪 Dubai',
                    'saopaulo'     => '🇧🇷 São Paulo',
                    'la'           => '🇺🇸 Los Angeles',
                    'madrid'       => '🇪🇸 Madrid',
                    'sanjosecr'    => '🇨🇷 San José, Costa Rica',
                    'cameroon'     => '🇨🇲 Cameroon',
                    'hongkong'     => '🇭🇰 Hong Kong',
                    'southafrica'  => '🇿🇦 South Africa',
                    // Más opciones extra que también se verán en el mapa
                    'newyork'      => '🇺🇸 New York',
                    'mexico'       => '🇲🇽 Ciudad de México',
                    'buenosaires'  => '🇦🇷 Buenos Aires',
                    'paris'        => '🇫🇷 Paris',
                    'berlin'       => '🇩🇪 Berlin',
                    'rome'         => '🇮🇹 Rome',
                    'lisbon'       => '🇵🇹 Lisbon',
                    'singapore'    => '🇸🇬 Singapore',
                    'bangkok'      => '🇹🇭 Bangkok',
                    'moscow'       => '🇷🇺 Moscow',
                    'beijing'      => '🇨🇳 Beijing',
                    'delhi'        => '🇮🇳 New Delhi',
                    'auckland'     => '🇳🇿 Auckland',
                ];

                // Ciudades por defecto para cada tarjeta (se pueden cambiar desde el selector)
                $defaultOrder = [
                    'montreal',
                    'london',
                    'tokyo',
                    'sydney',
                    'dubai',
                    'saopaulo',
                    'la',
                    'madrid',
                    'sanjosecr',
                    'cameroon',
                    'hongkong',
                    'southafrica',
                ];

                $worldClockDefaults = [];

                // Si hay usuario autenticado, intentamos cargar sus preferencias desde DB
                if (auth()->check()) {
                    $prefs = UserWorldClock::where('user_id', auth()->id())
                        ->orderBy('slot')
                        ->get()
                        ->keyBy('slot');

                    for ($i = 0; $i < 12; $i++) {
                        if (isset($prefs[$i]) && array_key_exists($prefs[$i]->city_key, $worldClockOptions)) {
                            $worldClockDefaults[$i] = $prefs[$i]->city_key;
                        } else {
                            // Fallback al orden por defecto si existe, si no, usar la primera opción disponible
                            $worldClockDefaults[$i] = $defaultOrder[$i] ?? array_key_first($worldClockOptions);
                        }
                    }
                } else {
                    // Sin usuario autenticado, usar solo el orden por defecto
                    for ($i = 0; $i < 12; $i++) {
                        $worldClockDefaults[$i] = $defaultOrder[$i] ?? array_key_first($worldClockOptions);
                    }
                }
            @endphp

            <!-- World Map Widget (arriba de los relojes) -->
            <h3 class="text-base font-semibold text-slate-800 dark:text-slate-200 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Usos Horarios y Mapa Mundial
            </h3>
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-slate-800 dark:to-slate-900 rounded-xl p-4 overflow-hidden relative mb-6" style="height: 450px; position: relative;">
                <div id="worldMapContainer" style="width: 100%; height: 100%; position: relative; overflow: hidden; border-radius: 8px;">
                    <!-- Mapa mundial con imagen de fondo integrada en SVG para mantener alineación perfecta -->
                    <svg id="worldMapSvg" viewBox="0 0 1000 500" style="width: 100%; height: 100%; position: absolute; top: 0; left: 0; z-index: 10;" class="world-map-svg" preserveAspectRatio="none">
                        <!-- Imagen de fondo del mapa integrada en SVG para mantener alineación -->
                        <image href="https://upload.wikimedia.org/wikipedia/commons/8/83/Equirectangular_projection_SW.jpg" x="0" y="0" width="1000" height="500" preserveAspectRatio="none" opacity="0.8" style="filter: brightness(0.95);"/>
                        
                        <!-- Marcadores de ciudades fijos (los existentes) -->
                        <!-- Montreal: Coordenadas aproximadas (-73°W, 45°N) -->
                        <g class="city-marker" data-city="montreal" data-timezone="America/Toronto">
                            <circle cx="294" cy="130" r="4" fill="#ef4444" stroke="#fff" stroke-width="1.5" class="city-dot" opacity="0.9"/>
                            <circle cx="294" cy="130" r="3" fill="#fff" class="city-pulse"/>
                            <text x="284" y="118" text-anchor="middle" fill="#fff" font-size="8" font-weight="bold" style="text-shadow: 0 1px 2px rgba(0,0,0,0.5);">MTL</text>
                            <rect x="302" y="140" width="50" height="18" rx="4" fill="rgba(255,255,255,0.95)" stroke="#ef4444" stroke-width="1.5" opacity="0.95"/>
                            <text x="327" y="154" text-anchor="middle" fill="#1e293b" font-size="10" font-weight="bold" id="map-clock-montreal">--:--</text>
                        </g>

                        <!-- San José, Costa Rica: Coordenadas ajustadas (-84°W, 9°N) -->
                        <g class="city-marker" data-city="sanjosecr" data-timezone="America/Costa_Rica">
                            <!-- Equirectangular: x = (lon+180)/360*1000, y = (90-lat)/180*500 ≈ (267, 225) -->
                            <circle cx="267" cy="225" r="4" fill="#a855f7" stroke="#fff" stroke-width="1.5" class="city-dot" opacity="0.9"/>
                            <circle cx="267" cy="225" r="3" fill="#fff" class="city-pulse"/>
                            <text x="257" y="213" text-anchor="middle" fill="#fff" font-size="8" font-weight="bold" style="text-shadow: 0 1px 2px rgba(0,0,0,0.5);">SJO</text>
                            <rect x="275" y="219" width="50" height="18" rx="4" fill="rgba(255,255,255,0.95)" stroke="#a855f7" stroke-width="1.5" opacity="0.95"/>
                            <text x="300" y="233" text-anchor="middle" fill="#1e293b" font-size="10" font-weight="bold" id="map-clock-sanjosecr">--:--</text>
                        </g>
                        
                        <!-- London: Coordenadas ajustadas (0°W, 51°N) -->
                        <g class="city-marker" data-city="london" data-timezone="Europe/London">
                            <circle cx="500" cy="108" r="4" fill="#3b82f6" stroke="#fff" stroke-width="1.5" class="city-dot" opacity="0.9"/>
                            <circle cx="500" cy="108" r="3" fill="#fff" class="city-pulse"/>
                            <text x="488" y="96" text-anchor="middle" fill="#fff" font-size="8" font-weight="bold" style="text-shadow: 0 1px 2px rgba(0,0,0,0.5);">LDN</text>
                            <rect x="508" y="120" width="50" height="18" rx="4" fill="rgba(255,255,255,0.95)" stroke="#3b82f6" stroke-width="1.5" opacity="0.95"/>
                            <text x="533" y="134" text-anchor="middle" fill="#1e293b" font-size="10" font-weight="bold" id="map-clock-london">--:--</text>
                        </g>
                        
                        <!-- Tokyo: Coordenadas ajustadas (139°E, 35°N) -->
                        <g class="city-marker" data-city="tokyo" data-timezone="Asia/Tokyo">
                            <circle cx="886" cy="152" r="4" fill="#10b981" stroke="#fff" stroke-width="1.5" class="city-dot" opacity="0.9"/>
                            <circle cx="886" cy="152" r="3" fill="#fff" class="city-pulse"/>
                            <text x="874" y="140" text-anchor="middle" fill="#fff" font-size="8" font-weight="bold" style="text-shadow: 0 1px 2px rgba(0,0,0,0.5);">TKY</text>
                            <rect x="894" y="164" width="50" height="18" rx="4" fill="rgba(255,255,255,0.95)" stroke="#10b981" stroke-width="1.5" opacity="0.95"/>
                            <text x="919" y="178" text-anchor="middle" fill="#1e293b" font-size="10" font-weight="bold" id="map-clock-tokyo">--:--</text>
                        </g>
                        
                        <!-- Sydney: Coordenadas ajustadas (151°E, 33°S) -->
                        <g class="city-marker" data-city="sydney" data-timezone="Australia/Sydney">
                            <circle cx="919" cy="342" r="4" fill="#f59e0b" stroke="#fff" stroke-width="1.5" class="city-dot" opacity="0.9"/>
                            <circle cx="919" cy="342" r="3" fill="#fff" class="city-pulse"/>
                            <text x="907" y="330" text-anchor="middle" fill="#fff" font-size="8" font-weight="bold" style="text-shadow: 0 1px 2px rgba(0,0,0,0.5);">SYD</text>
                            <rect x="927" y="354" width="50" height="18" rx="4" fill="rgba(255,255,255,0.95)" stroke="#f59e0b" stroke-width="1.5" opacity="0.95"/>
                            <text x="952" y="368" text-anchor="middle" fill="#1e293b" font-size="10" font-weight="bold" id="map-clock-sydney">--:--</text>
                        </g>
                        
                        <!-- Dubai: Coordenadas ajustadas (55°E, 25°N) -->
                        <g class="city-marker" data-city="dubai" data-timezone="Asia/Dubai">
                            <circle cx="653" cy="181" r="4" fill="#8b5cf6" stroke="#fff" stroke-width="1.5" class="city-dot" opacity="0.9"/>
                            <circle cx="653" cy="181" r="3" fill="#fff" class="city-pulse"/>
                            <text x="641" y="169" text-anchor="middle" fill="#fff" font-size="8" font-weight="bold" style="text-shadow: 0 1px 2px rgba(0,0,0,0.5);">DXB</text>
                            <rect x="661" y="193" width="50" height="18" rx="4" fill="rgba(255,255,255,0.95)" stroke="#8b5cf6" stroke-width="1.5" opacity="0.95"/>
                            <text x="686" y="207" text-anchor="middle" fill="#1e293b" font-size="10" font-weight="bold" id="map-clock-dubai">--:--</text>
                        </g>

                        <!-- Cameroon (Douala): Coordenadas aproximadas (10°E, 4°N) -->
                        <g class="city-marker" data-city="cameroon" data-timezone="Africa/Douala">
                            <circle cx="530" cy="235" r="4" fill="#22c55e" stroke="#fff" stroke-width="1.5" class="city-dot" opacity="0.9"/>
                            <circle cx="530" cy="235" r="3" fill="#fff" class="city-pulse"/>
                            <text x="520" y="223" text-anchor="middle" fill="#fff" font-size="8" font-weight="bold" style="text-shadow: 0 1px 2px rgba(0,0,0,0.5);">CMR</text>
                            <rect x="538" y="229" width="50" height="18" rx="4" fill="rgba(255,255,255,0.95)" stroke="#22c55e" stroke-width="1.5" opacity="0.95"/>
                            <text x="563" y="243" text-anchor="middle" fill="#1e293b" font-size="10" font-weight="bold" id="map-clock-cameroon">--:--</text>
                        </g>

                        <!-- Hong Kong: Coordenadas aproximadas (114°E, 22°N) -->
                        <g class="city-marker" data-city="hongkong" data-timezone="Asia/Hong_Kong">
                            <circle cx="820" cy="190" r="4" fill="#0ea5e9" stroke="#fff" stroke-width="1.5" class="city-dot" opacity="0.9"/>
                            <circle cx="820" cy="190" r="3" fill="#fff" class="city-pulse"/>
                            <text x="810" y="178" text-anchor="middle" fill="#fff" font-size="8" font-weight="bold" style="text-shadow: 0 1px 2px rgba(0,0,0,0.5);">HKG</text>
                            <rect x="828" y="184" width="50" height="18" rx="4" fill="rgba(255,255,255,0.95)" stroke="#0ea5e9" stroke-width="1.5" opacity="0.95"/>
                            <text x="853" y="198" text-anchor="middle" fill="#1e293b" font-size="10" font-weight="bold" id="map-clock-hongkong">--:--</text>
                        </g>

                        <!-- South Africa (Johannesburg): Coordenadas aproximadas (28°E, 26°S) -->
                        <g class="city-marker" data-city="southafrica" data-timezone="Africa/Johannesburg">
                            <circle cx="580" cy="325" r="4" fill="#f97316" stroke="#fff" stroke-width="1.5" class="city-dot" opacity="0.9"/>
                            <circle cx="580" cy="325" r="3" fill="#fff" class="city-pulse"/>
                            <text x="570" y="313" text-anchor="middle" fill="#fff" font-size="8" font-weight="bold" style="text-shadow: 0 1px 2px rgba(0,0,0,0.5);">SA</text>
                            <rect x="588" y="319" width="50" height="18" rx="4" fill="rgba(255,255,255,0.95)" stroke="#f97316" stroke-width="1.5" opacity="0.95"/>
                            <text x="613" y="333" text-anchor="middle" fill="#1e293b" font-size="10" font-weight="bold" id="map-clock-southafrica">--:--</text>
                        </g>
                        
                        <!-- São Paulo: Coordenadas ajustadas (46°W, 23°S) -->
                        <g class="city-marker" data-city="saopaulo" data-timezone="America/Sao_Paulo">
                            <circle cx="372" cy="314" r="4" fill="#ec4899" stroke="#fff" stroke-width="1.5" class="city-dot" opacity="0.9"/>
                            <circle cx="372" cy="314" r="3" fill="#fff" class="city-pulse"/>
                            <text x="360" y="302" text-anchor="middle" fill="#fff" font-size="8" font-weight="bold" style="text-shadow: 0 1px 2px rgba(0,0,0,0.5);">SP</text>
                            <rect x="380" y="326" width="50" height="18" rx="4" fill="rgba(255,255,255,0.95)" stroke="#ec4899" stroke-width="1.5" opacity="0.95"/>
                            <text x="405" y="340" text-anchor="middle" fill="#1e293b" font-size="10" font-weight="bold" id="map-clock-saopaulo">--:--</text>
                        </g>
                        
                        <!-- Los Angeles: Coordenadas ajustadas (118°W, 34°N) -->
                        <g class="city-marker" data-city="la" data-timezone="America/Los_Angeles">
                            <circle cx="172" cy="156" r="4" fill="#06b6d4" stroke="#fff" stroke-width="1.5" class="city-dot" opacity="0.9"/>
                            <circle cx="172" cy="156" r="3" fill="#fff" class="city-pulse"/>
                            <text x="160" y="144" text-anchor="middle" fill="#fff" font-size="8" font-weight="bold" style="text-shadow: 0 1px 2px rgba(0,0,0,0.5);">LA</text>
                            <rect x="180" y="168" width="50" height="18" rx="4" fill="rgba(255,255,255,0.95)" stroke="#06b6d4" stroke-width="1.5" opacity="0.95"/>
                            <text x="205" y="182" text-anchor="middle" fill="#1e293b" font-size="10" font-weight="bold" id="map-clock-la">--:--</text>
                        </g>
                        
                        <!-- Madrid: Coordenadas ajustadas (3°W, 40°N) -->
                        <g class="city-marker" data-city="madrid" data-timezone="Europe/Madrid">
                            <circle cx="491" cy="139" r="4" fill="#14b8a6" stroke="#fff" stroke-width="1.5" class="city-dot" opacity="0.9"/>
                            <circle cx="491" cy="139" r="3" fill="#fff" class="city-pulse"/>
                            <text x="479" y="127" text-anchor="middle" fill="#fff" font-size="8" font-weight="bold" style="text-shadow: 0 1px 2px rgba(0,0,0,0.5);">MAD</text>
                            <rect x="499" y="151" width="50" height="18" rx="4" fill="rgba(255,255,255,0.95)" stroke="#14b8a6" stroke-width="1.5" opacity="0.95"/>
                            <text x="524" y="165" text-anchor="middle" fill="#1e293b" font-size="10" font-weight="bold" id="map-clock-madrid">--:--</text>
                        </g>
                    </svg>
                </div>
            </div>
            
            <!-- World Clocks Grid (cada tarjeta permite cambiar la ciudad) -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 mt-6">
                @foreach($worldClockDefaults as $slotIndex => $defaultCity)
                    <div class="bg-violet-50 dark:bg-slate-800/50 rounded-xl p-3 border border-slate-200 dark:border-slate-700 world-clock-card" data-card-index="{{ $slotIndex }}">
                    <div class="text-center">
                            <div class="flex items-center justify-start gap-1 mb-1">
                                <span class="inline-block w-2 h-2 rounded-full bg-violet-500 dark:bg-violet-400"></span>
                                <select class="world-clock-select text-[11px] font-semibold text-slate-700 dark:text-slate-200 border border-transparent rounded-md pr-4 pl-1.5 py-0.5 focus:outline-none focus:ring-1 focus:ring-violet-500 focus:border-violet-500 bg-transparent">
                                @foreach($worldClockOptions as $cityKey => $cityLabel)
                                    <option value="{{ $cityKey }}" {{ $cityKey === $defaultCity ? 'selected' : '' }}>
                                        {{ $cityLabel }}
                                    </option>
                                @endforeach
                            </select>
                    </div>
                            <div class="text-xl font-bold text-slate-900 dark:text-white mb-0.5 world-clock-time">--:--</div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-500 world-clock-date">--</div>
                </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<script>
    // World Clocks - Solo inicializar si no existe ya
    if (typeof updateWorldClocks === 'undefined') {
        const timezones = {
            montreal:    { label: 'Montreal',             tz: 'America/Toronto',               lat: 45.5017,  lon: -73.5673,  code: 'MTL', color: '#ef4444' },
            london:      { label: 'London',               tz: 'Europe/London',                 lat: 51.5074,  lon: -0.1278,   code: 'LDN', color: '#3b82f6' },
            tokyo:       { label: 'Tokyo',                tz: 'Asia/Tokyo',                    lat: 35.6762,  lon: 139.6503,  code: 'TKY', color: '#10b981' },
            sydney:      { label: 'Sydney',               tz: 'Australia/Sydney',              lat: -33.8688, lon: 151.2093,  code: 'SYD', color: '#f59e0b' },
            dubai:       { label: 'Dubai',                tz: 'Asia/Dubai',                    lat: 25.2048,  lon: 55.2708,   code: 'DXB', color: '#8b5cf6' },
            saopaulo:    { label: 'São Paulo',            tz: 'America/Sao_Paulo',             lat: -23.5558, lon: -46.6396,  code: 'SP',  color: '#ec4899' },
            la:          { label: 'Los Angeles',          tz: 'America/Los_Angeles',           lat: 34.0522,  lon: -118.2437, code: 'LA',  color: '#06b6d4' },
            madrid:      { label: 'Madrid',               tz: 'Europe/Madrid',                 lat: 40.4168,  lon: -3.7038,   code: 'MAD', color: '#14b8a6' },
            sanjosecr:   { label: 'San José, Costa Rica', tz: 'America/Costa_Rica',            lat: 9.9281,   lon: -84.0907,  code: 'SJO', color: '#a855f7' },
            cameroon:    { label: 'Cameroon',             tz: 'Africa/Douala',                 lat: 4.0511,   lon: 9.7679,    code: 'CMR', color: '#22c55e' },
            hongkong:    { label: 'Hong Kong',            tz: 'Asia/Hong_Kong',                lat: 22.3193,  lon: 114.1694,  code: 'HKG', color: '#0ea5e9' },
            southafrica: { label: 'South Africa',         tz: 'Africa/Johannesburg',           lat: -26.2041, lon: 28.0473,   code: 'SA',  color: '#f97316' },
            // Extra cities que también tendrán punto dinámico en el mapa
            newyork:     { label: 'New York',             tz: 'America/New_York',              lat: 40.7128,  lon: -74.0060,  code: 'NYC', color: '#16a34a' },
            mexico:      { label: 'Ciudad de México',     tz: 'America/Mexico_City',           lat: 19.4326,  lon: -99.1332,  code: 'CDMX',color: '#22c55e' },
            buenosaires: { label: 'Buenos Aires',         tz: 'America/Argentina/Buenos_Aires',lat: -34.6037, lon: -58.3816,  code: 'BA',  color: '#e11d48' },
            paris:       { label: 'Paris',                tz: 'Europe/Paris',                  lat: 48.8566,  lon: 2.3522,    code: 'PAR', color: '#6366f1' },
            berlin:      { label: 'Berlin',               tz: 'Europe/Berlin',                 lat: 52.5200,  lon: 13.4050,   code: 'BER', color: '#0891b2' },
            rome:        { label: 'Rome',                 tz: 'Europe/Rome',                   lat: 41.9028,  lon: 12.4964,   code: 'ROM', color: '#d97706' },
            lisbon:      { label: 'Lisbon',               tz: 'Europe/Lisbon',                 lat: 38.7223,  lon: -9.1393,   code: 'LIS', color: '#22c55e' },
            singapore:   { label: 'Singapore',            tz: 'Asia/Singapore',                lat: 1.3521,   lon: 103.8198,  code: 'SGP', color: '#db2777' },
            bangkok:     { label: 'Bangkok',              tz: 'Asia/Bangkok',                  lat: 13.7563,  lon: 100.5018,  code: 'BKK', color: '#10b981' },
            moscow:      { label: 'Moscow',               tz: 'Europe/Moscow',                 lat: 55.7558,  lon: 37.6173,   code: 'MOW', color: '#3b82f6' },
            beijing:     { label: 'Beijing',              tz: 'Asia/Shanghai',                 lat: 39.9042,  lon: 116.4074,  code: 'BJ',  color: '#ef4444' },
            delhi:       { label: 'New Delhi',            tz: 'Asia/Kolkata',                  lat: 28.6139,  lon: 77.2090,   code: 'DEL', color: '#f97316' },
            auckland:    { label: 'Auckland',             tz: 'Pacific/Auckland',              lat: -36.8485, lon: 174.7633,  code: 'AKL', color: '#22d3ee' },
        };

        function updateWorldClocks() {
            // Ciudades activas según las tarjetas (lugares "guardados")
            const activeCities = new Set();

            // Actualizar las tarjetas según la ciudad seleccionada en cada una
            document.querySelectorAll('.world-clock-card').forEach(card => {
                const select = card.querySelector('.world-clock-select');
                if (!select) return;

                const cityKey = select.value;
                const config = timezones[cityKey];
                if (!config) return;

                activeCities.add(cityKey);

                try {
                    const now = new Date(new Date().toLocaleString('en-US', { timeZone: config.tz }));
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    
                    const options = { month: 'short', day: 'numeric' };
                    const dateStr = now.toLocaleDateString('en-US', options);

                    const clockEl = card.querySelector('.world-clock-time');
                    const dateEl = card.querySelector('.world-clock-date');
                    
                    if (clockEl) {
                        clockEl.textContent = `${hours}:${minutes}`;
                    }
                    if (dateEl) {
                        dateEl.textContent = dateStr;
                    }
                } catch (error) {
                    console.error(`Error updating card clock for ${cityKey}:`, error);
                }
            });

            // Luego, actualizar los relojes del mapa y mostrar solo los puntos de las ciudades activas
            Object.keys(timezones).forEach(city => {
                try {
                    const config = timezones[city];
                    const tz = config.tz;
                    const now = new Date(new Date().toLocaleString('en-US', { timeZone: tz }));
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    const seconds = String(now.getSeconds()).padStart(2, '0');

                    const mapClockElement = document.getElementById(`map-clock-${city}`);
                    if (mapClockElement) {
                        mapClockElement.textContent = `${hours}:${minutes}:${seconds}`;
                    }

                    let markerGroup = document.querySelector(`.city-marker[data-city="${city}"]`);

                    // Si no existe marcador y tenemos lat/lon, crearlo dinámicamente
                    if (!markerGroup && typeof config.lat === 'number' && typeof config.lon === 'number') {
                        const svg = document.getElementById('worldMapSvg');
                        if (svg) {
                            const xmlns = 'http://www.w3.org/2000/svg';
                            markerGroup = document.createElementNS(xmlns, 'g');
                            markerGroup.classList.add('city-marker');
                            markerGroup.setAttribute('data-city', city);

                            const x = ((config.lon + 180) / 360) * 1000;
                            const y = ((90 - config.lat) / 180) * 500;

                            const circleOuter = document.createElementNS(xmlns, 'circle');
                            circleOuter.setAttribute('cx', x);
                            circleOuter.setAttribute('cy', y);
                            circleOuter.setAttribute('r', 4);
                            circleOuter.setAttribute('fill', config.color || '#8b5cf6');
                            circleOuter.setAttribute('stroke', '#fff');
                            circleOuter.setAttribute('stroke-width', '1.5');
                            circleOuter.setAttribute('class', 'city-dot');
                            circleOuter.setAttribute('opacity', '0.9');
                            markerGroup.appendChild(circleOuter);

                            const circleInner = document.createElementNS(xmlns, 'circle');
                            circleInner.setAttribute('cx', x);
                            circleInner.setAttribute('cy', y);
                            circleInner.setAttribute('r', 3);
                            circleInner.setAttribute('fill', '#fff');
                            circleInner.setAttribute('class', 'city-pulse');
                            markerGroup.appendChild(circleInner);

                            const label = document.createElementNS(xmlns, 'text');
                            label.setAttribute('x', x - 10);
                            label.setAttribute('y', y - 12);
                            label.setAttribute('text-anchor', 'middle');
                            label.setAttribute('fill', '#fff');
                            label.setAttribute('font-size', '8');
                            label.setAttribute('font-weight', 'bold');
                            label.setAttribute('style', 'text-shadow: 0 1px 2px rgba(0,0,0,0.5);');
                            label.textContent = config.code || (config.label || city).substring(0, 3).toUpperCase();
                            markerGroup.appendChild(label);

                            const rect = document.createElementNS(xmlns, 'rect');
                            rect.setAttribute('x', x + 8);
                            rect.setAttribute('y', y + 10);
                            rect.setAttribute('width', 60);
                            rect.setAttribute('height', 18);
                            rect.setAttribute('rx', 4);
                            rect.setAttribute('fill', 'rgba(255,255,255,0.95)');
                            rect.setAttribute('stroke', config.color || '#8b5cf6');
                            rect.setAttribute('stroke-width', '1.5');
                            rect.setAttribute('opacity', '0.95');
                            markerGroup.appendChild(rect);

                            const text = document.createElementNS(xmlns, 'text');
                            text.setAttribute('x', x + 38);
                            text.setAttribute('y', y + 24);
                            text.setAttribute('text-anchor', 'middle');
                            text.setAttribute('fill', '#1e293b');
                            text.setAttribute('font-size', '10');
                            text.setAttribute('font-weight', 'bold');
                            text.setAttribute('id', `map-clock-${city}`);
                            text.textContent = '--:--';
                            markerGroup.appendChild(text);

                            svg.appendChild(markerGroup);
                        }
                    }

                    if (markerGroup) {
                        if (activeCities.has(city)) {
                            markerGroup.style.display = 'block';
                        } else {
                            markerGroup.style.display = 'none';
                        }
                    }
                } catch (error) {
                    console.error(`Error updating map clock for ${city}:`, error);
                }
            });
        }
        
        // Actualizar relojes al cambiar una ciudad en cualquier tarjeta
        document.addEventListener('change', function (event) {
            if (event.target.classList.contains('world-clock-select')) {
                const card = event.target.closest('.world-clock-card');
                if (card) {
                    const index = card.getAttribute('data-card-index');
                    if (index !== null) {
                        // Guardar selección en el backend para el usuario actual
                        try {
                            fetch('/walee/world-clocks/save', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content
                                },
                                body: JSON.stringify({
                                    slot: parseInt(index, 10),
                                    city_key: event.target.value
                                })
                            }).catch((e) => {
                                console.warn('No se pudo guardar la selección de ciudad en el servidor', e);
                            });
                        } catch (e) {
                            console.warn('No se pudo guardar la selección de ciudad en el servidor', e);
                        }
                    }
                }
                updateWorldClocks();
            }
        });

        // Actualizar relojes cada segundo
        updateWorldClocks();
        setInterval(updateWorldClocks, 1000);
    }
</script>

<style>
    /* World Map Styles */
    .world-map-svg {
        /* Sin filtro global para evitar afectar la imagen de fondo */
    }
    
    .city-marker {
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
    }
    
    .city-dot {
        cursor: pointer;
        transition: transform 0.2s ease;
    }
    
    .city-dot:hover {
        transform: scale(1.2);
    }
    
    .city-pulse {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 0.4;
            transform: scale(1);
        }
        50% {
            opacity: 0.8;
            transform: scale(1.2);
        }
    }
    
    .dark .city-marker {
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.4));
    }

    /* Dropdown de ciudades compatible con light/dark */
    .world-clock-select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: linear-gradient(45deg, transparent 50%, #6b21a8 50%), linear-gradient(135deg, #6b21a8 50%, transparent 50%);
        background-position: right 6px top 50%, right 2px top 50%;
        background-size: 6px 6px, 6px 6px;
        background-repeat: no-repeat;
        cursor: pointer;
    }

    html:not(.dark) .world-clock-select {
        background-color: #ffffff;
        color: #0f172a;
    }

    .dark .world-clock-select {
        background-color: rgba(15, 23, 42, 0.9);
        color: #e5e7eb;
        background-image: linear-gradient(45deg, transparent 50%, #a855f7 50%), linear-gradient(135deg, #a855f7 50%, transparent 50%);
    }

    .world-clock-select option {
        background-color: #ffffff;
        color: #0f172a;
    }

    .dark .world-clock-select option {
        background-color: #020617;
        color: #e5e7eb;
    }
</style>

