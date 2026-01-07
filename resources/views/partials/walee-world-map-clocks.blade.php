<!-- World Map with Clocks -->
<section class="mb-8">
    <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-300 mb-4 flex items-center gap-2">
        <svg class="w-5 h-5 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Mapa Mundial y Zonas Horarias
    </h2>
    
    <div class="bg-white dark:bg-slate-900/50 rounded-2xl shadow-lg border border-black dark:border-black overflow-hidden">
        <div class="p-4 sm:p-6">
            <!-- World Map Widget -->
            <div class="mb-6 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-slate-800 dark:to-slate-900 rounded-xl p-4 overflow-hidden relative" style="height: 450px; position: relative;">
                <div id="worldMapContainer" style="width: 100%; height: 100%; position: relative; overflow: hidden; border-radius: 8px;">
                    <!-- Mapa mundial con imagen de fondo integrada en SVG para mantener alineación perfecta -->
                    <svg viewBox="0 0 1000 500" style="width: 100%; height: 100%; position: absolute; top: 0; left: 0; z-index: 10;" class="world-map-svg" preserveAspectRatio="none">
                        <!-- Imagen de fondo del mapa integrada en SVG para mantener alineación -->
                        <image href="https://upload.wikimedia.org/wikipedia/commons/8/83/Equirectangular_projection_SW.jpg" x="0" y="0" width="1000" height="500" preserveAspectRatio="none" opacity="0.8" style="filter: brightness(0.95);"/>
                        
                        <!-- Marcadores de ciudades - coordenadas ajustadas para alinearse perfectamente con las ciudades del mapa -->
                        <!-- New York: Coordenadas ajustadas (-74°W, 40°N) -->
                        <g class="city-marker" data-city="ny" data-timezone="America/New_York">
                            <circle cx="294" cy="139" r="4" fill="#ef4444" stroke="#fff" stroke-width="1.5" class="city-dot" opacity="0.9"/>
                            <circle cx="294" cy="139" r="3" fill="#fff" class="city-pulse"/>
                            <text x="282" y="126" text-anchor="middle" fill="#fff" font-size="8" font-weight="bold" style="text-shadow: 0 1px 2px rgba(0,0,0,0.5);">NY</text>
                            <rect x="302" y="148" width="50" height="18" rx="4" fill="rgba(255,255,255,0.95)" stroke="#ef4444" stroke-width="1.5" opacity="0.95"/>
                            <text x="327" y="162" text-anchor="middle" fill="#1e293b" font-size="10" font-weight="bold" id="map-clock-ny">--:--</text>
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
            
            <!-- World Clocks Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                <!-- New York -->
                <div class="bg-violet-50 dark:bg-slate-800/50 rounded-xl p-4 border border-slate-200 dark:border-slate-700">
                    <div class="text-center">
                        <div class="text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2">New York</div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white mb-1" id="clock-ny">--:--</div>
                        <div class="text-xs text-slate-500 dark:text-slate-500" id="date-ny">--</div>
                    </div>
                </div>
                
                <!-- London -->
                <div class="bg-violet-50 dark:bg-slate-800/50 rounded-xl p-4 border border-slate-200 dark:border-slate-700">
                    <div class="text-center">
                        <div class="text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2">London</div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white mb-1" id="clock-london">--:--</div>
                        <div class="text-xs text-slate-500 dark:text-slate-500" id="date-london">--</div>
                    </div>
                </div>
                
                <!-- Tokyo -->
                <div class="bg-violet-50 dark:bg-slate-800/50 rounded-xl p-4 border border-slate-200 dark:border-slate-700">
                    <div class="text-center">
                        <div class="text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2">Tokyo</div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white mb-1" id="clock-tokyo">--:--</div>
                        <div class="text-xs text-slate-500 dark:text-slate-500" id="date-tokyo">--</div>
                    </div>
                </div>
                
                <!-- Sydney -->
                <div class="bg-violet-50 dark:bg-slate-800/50 rounded-xl p-4 border border-slate-200 dark:border-slate-700">
                    <div class="text-center">
                        <div class="text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2">Sydney</div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white mb-1" id="clock-sydney">--:--</div>
                        <div class="text-xs text-slate-500 dark:text-slate-500" id="date-sydney">--</div>
                    </div>
                </div>
                
                <!-- Dubai -->
                <div class="bg-violet-50 dark:bg-slate-800/50 rounded-xl p-4 border border-slate-200 dark:border-slate-700">
                    <div class="text-center">
                        <div class="text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2">Dubai</div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white mb-1" id="clock-dubai">--:--</div>
                        <div class="text-xs text-slate-500 dark:text-slate-500" id="date-dubai">--</div>
                    </div>
                </div>
                
                <!-- São Paulo -->
                <div class="bg-violet-50 dark:bg-slate-800/50 rounded-xl p-4 border border-slate-200 dark:border-slate-700">
                    <div class="text-center">
                        <div class="text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2">São Paulo</div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white mb-1" id="clock-saopaulo">--:--</div>
                        <div class="text-xs text-slate-500 dark:text-slate-500" id="date-saopaulo">--</div>
                    </div>
                </div>
                
                <!-- Los Angeles -->
                <div class="bg-violet-50 dark:bg-slate-800/50 rounded-xl p-4 border border-slate-200 dark:border-slate-700">
                    <div class="text-center">
                        <div class="text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2">Los Angeles</div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white mb-1" id="clock-la">--:--</div>
                        <div class="text-xs text-slate-500 dark:text-slate-500" id="date-la">--</div>
                    </div>
                </div>
                
                <!-- Madrid -->
                <div class="bg-violet-50 dark:bg-slate-800/50 rounded-xl p-4 border border-slate-200 dark:border-slate-700">
                    <div class="text-center">
                        <div class="text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2">Madrid</div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white mb-1" id="clock-madrid">--:--</div>
                        <div class="text-xs text-slate-500 dark:text-slate-500" id="date-madrid">--</div>
                    </div>
                </div>

                <!-- Cameroon -->
                <div class="bg-violet-50 dark:bg-slate-800/50 rounded-xl p-4 border border-slate-200 dark:border-slate-700">
                    <div class="text-center">
                        <div class="text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2">Cameroon</div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white mb-1" id="clock-cameroon">--:--</div>
                        <div class="text-xs text-slate-500 dark:text-slate-500" id="date-cameroon">--</div>
                    </div>
                </div>

                <!-- Hong Kong -->
                <div class="bg-violet-50 dark:bg-slate-800/50 rounded-xl p-4 border border-slate-200 dark:border-slate-700">
                    <div class="text-center">
                        <div class="text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2">Hong Kong</div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white mb-1" id="clock-hongkong">--:--</div>
                        <div class="text-xs text-slate-500 dark:text-slate-500" id="date-hongkong">--</div>
                    </div>
                </div>

                <!-- South Africa -->
                <div class="bg-violet-50 dark:bg-slate-800/50 rounded-xl p-4 border border-slate-200 dark:border-slate-700">
                    <div class="text-center">
                        <div class="text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2">South Africa</div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white mb-1" id="clock-southafrica">--:--</div>
                        <div class="text-xs text-slate-500 dark:text-slate-500" id="date-southafrica">--</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // World Clocks - Solo inicializar si no existe ya
    if (typeof updateWorldClocks === 'undefined') {
        function updateWorldClocks() {
            const timezones = {
                'ny': 'America/New_York',
                'london': 'Europe/London',
                'tokyo': 'Asia/Tokyo',
                'sydney': 'Australia/Sydney',
                'dubai': 'Asia/Dubai',
                'saopaulo': 'America/Sao_Paulo',
                'la': 'America/Los_Angeles',
                'madrid': 'Europe/Madrid',
                'cameroon': 'Africa/Douala',
                'hongkong': 'Asia/Hong_Kong',
                'southafrica': 'Africa/Johannesburg'
            };
            
            Object.keys(timezones).forEach(city => {
                try {
                    const now = new Date(new Date().toLocaleString('en-US', { timeZone: timezones[city] }));
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    
                    // Actualizar relojes en las tarjetas
                    const clockElement = document.getElementById(`clock-${city}`);
                    const dateElement = document.getElementById(`date-${city}`);
                    
                    if (clockElement) {
                        clockElement.textContent = `${hours}:${minutes}`;
                    }
                    
                    if (dateElement) {
                        const options = { month: 'short', day: 'numeric' };
                        dateElement.textContent = now.toLocaleDateString('en-US', options);
                    }
                    
                    // Actualizar relojes en el mapa
                    const mapClockElement = document.getElementById(`map-clock-${city}`);
                    if (mapClockElement) {
                        mapClockElement.textContent = `${hours}:${minutes}`;
                    }
                } catch (error) {
                    console.error(`Error updating clock for ${city}:`, error);
                }
            });
        }
        
        // Update clocks every second
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
</style>

