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
            <div class="mb-6 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-slate-800 dark:to-slate-900 rounded-xl p-4 overflow-hidden relative" style="height: 400px; position: relative;">
                <div id="worldMapContainer" style="width: 100%; height: 100%; position: relative; overflow: hidden; border-radius: 8px;">
                    <!-- Imagen de fondo del mapa -->
                    <img src="https://upload.wikimedia.org/wikipedia/commons/8/83/Equirectangular_projection_SW.jpg" alt="World Map" style="width: 100%; height: 100%; object-fit: fill; position: absolute; top: 0; left: 0; opacity: 0.8; filter: brightness(0.95);" class="world-map-bg">
                    <!-- Mapa mundial con marcadores mejorados - coordenadas ajustadas para alinearse con la imagen -->
                    <svg viewBox="0 0 1000 500" style="width: 100%; height: 100%; position: absolute; top: 0; left: 0; z-index: 10;" class="world-map-svg" preserveAspectRatio="none">
                        <!-- Fondo transparente para que se vea la imagen de fondo -->
                        <rect width="1000" height="500" fill="transparent"/>
                        
                        <!-- Marcadores de ciudades - coordenadas ajustadas manualmente para alinearse con la imagen -->
                        <!-- New York: Ajustado para alinearse con la imagen -->
                        <g class="city-marker" data-city="ny" data-timezone="America/New_York">
                            <circle cx="280" cy="180" r="12" fill="#ef4444" stroke="#fff" stroke-width="3" class="city-dot" opacity="0.9"/>
                            <circle cx="280" cy="180" r="8" fill="#fff" class="city-pulse"/>
                            <text x="280" y="165" text-anchor="middle" fill="#fff" font-size="11" font-weight="bold" style="text-shadow: 0 1px 2px rgba(0,0,0,0.5);">NY</text>
                            <rect x="250" y="200" width="60" height="25" rx="4" fill="rgba(255,255,255,0.95)" stroke="#ef4444" stroke-width="1.5" opacity="0.95"/>
                            <text x="280" y="217" text-anchor="middle" fill="#1e293b" font-size="11" font-weight="bold" id="map-clock-ny">--:--</text>
                        </g>
                        
                        <!-- London: Ajustado para alinearse con la imagen -->
                        <g class="city-marker" data-city="london" data-timezone="Europe/London">
                            <circle cx="500" cy="150" r="12" fill="#3b82f6" stroke="#fff" stroke-width="3" class="city-dot" opacity="0.9"/>
                            <circle cx="500" cy="150" r="8" fill="#fff" class="city-pulse"/>
                            <text x="500" y="135" text-anchor="middle" fill="#fff" font-size="11" font-weight="bold" style="text-shadow: 0 1px 2px rgba(0,0,0,0.5);">LDN</text>
                            <rect x="470" y="170" width="60" height="25" rx="4" fill="rgba(255,255,255,0.95)" stroke="#3b82f6" stroke-width="1.5" opacity="0.95"/>
                            <text x="500" y="187" text-anchor="middle" fill="#1e293b" font-size="11" font-weight="bold" id="map-clock-london">--:--</text>
                        </g>
                        
                        <!-- Tokyo: Ajustado para alinearse con la imagen -->
                        <g class="city-marker" data-city="tokyo" data-timezone="Asia/Tokyo">
                            <circle cx="870" cy="200" r="12" fill="#10b981" stroke="#fff" stroke-width="3" class="city-dot" opacity="0.9"/>
                            <circle cx="870" cy="200" r="8" fill="#fff" class="city-pulse"/>
                            <text x="870" y="185" text-anchor="middle" fill="#fff" font-size="11" font-weight="bold" style="text-shadow: 0 1px 2px rgba(0,0,0,0.5);">TKY</text>
                            <rect x="840" y="220" width="60" height="25" rx="4" fill="rgba(255,255,255,0.95)" stroke="#10b981" stroke-width="1.5" opacity="0.95"/>
                            <text x="870" y="237" text-anchor="middle" fill="#1e293b" font-size="11" font-weight="bold" id="map-clock-tokyo">--:--</text>
                        </g>
                        
                        <!-- Sydney: Ajustado para alinearse con la imagen -->
                        <g class="city-marker" data-city="sydney" data-timezone="Australia/Sydney">
                            <circle cx="900" cy="380" r="12" fill="#f59e0b" stroke="#fff" stroke-width="3" class="city-dot" opacity="0.9"/>
                            <circle cx="900" cy="380" r="8" fill="#fff" class="city-pulse"/>
                            <text x="900" y="365" text-anchor="middle" fill="#fff" font-size="11" font-weight="bold" style="text-shadow: 0 1px 2px rgba(0,0,0,0.5);">SYD</text>
                            <rect x="870" y="400" width="60" height="25" rx="4" fill="rgba(255,255,255,0.95)" stroke="#f59e0b" stroke-width="1.5" opacity="0.95"/>
                            <text x="900" y="417" text-anchor="middle" fill="#1e293b" font-size="11" font-weight="bold" id="map-clock-sydney">--:--</text>
                        </g>
                        
                        <!-- Dubai: Ajustado para alinearse con la imagen -->
                        <g class="city-marker" data-city="dubai" data-timezone="Asia/Dubai">
                            <circle cx="640" cy="220" r="12" fill="#8b5cf6" stroke="#fff" stroke-width="3" class="city-dot" opacity="0.9"/>
                            <circle cx="640" cy="220" r="8" fill="#fff" class="city-pulse"/>
                            <text x="640" y="205" text-anchor="middle" fill="#fff" font-size="11" font-weight="bold" style="text-shadow: 0 1px 2px rgba(0,0,0,0.5);">DXB</text>
                            <rect x="610" y="240" width="60" height="25" rx="4" fill="rgba(255,255,255,0.95)" stroke="#8b5cf6" stroke-width="1.5" opacity="0.95"/>
                            <text x="640" y="257" text-anchor="middle" fill="#1e293b" font-size="11" font-weight="bold" id="map-clock-dubai">--:--</text>
                        </g>
                        
                        <!-- São Paulo: Ajustado para alinearse con la imagen -->
                        <g class="city-marker" data-city="saopaulo" data-timezone="America/Sao_Paulo">
                            <circle cx="380" cy="340" r="12" fill="#ec4899" stroke="#fff" stroke-width="3" class="city-dot" opacity="0.9"/>
                            <circle cx="380" cy="340" r="8" fill="#fff" class="city-pulse"/>
                            <text x="380" y="325" text-anchor="middle" fill="#fff" font-size="11" font-weight="bold" style="text-shadow: 0 1px 2px rgba(0,0,0,0.5);">SP</text>
                            <rect x="350" y="360" width="60" height="25" rx="4" fill="rgba(255,255,255,0.95)" stroke="#ec4899" stroke-width="1.5" opacity="0.95"/>
                            <text x="380" y="377" text-anchor="middle" fill="#1e293b" font-size="11" font-weight="bold" id="map-clock-saopaulo">--:--</text>
                        </g>
                        
                        <!-- Los Angeles: Ajustado para alinearse con la imagen -->
                        <g class="city-marker" data-city="la" data-timezone="America/Los_Angeles">
                            <circle cx="200" cy="200" r="12" fill="#06b6d4" stroke="#fff" stroke-width="3" class="city-dot" opacity="0.9"/>
                            <circle cx="200" cy="200" r="8" fill="#fff" class="city-pulse"/>
                            <text x="200" y="185" text-anchor="middle" fill="#fff" font-size="11" font-weight="bold" style="text-shadow: 0 1px 2px rgba(0,0,0,0.5);">LA</text>
                            <rect x="170" y="220" width="60" height="25" rx="4" fill="rgba(255,255,255,0.95)" stroke="#06b6d4" stroke-width="1.5" opacity="0.95"/>
                            <text x="200" y="237" text-anchor="middle" fill="#1e293b" font-size="11" font-weight="bold" id="map-clock-la">--:--</text>
                        </g>
                        
                        <!-- Madrid: Ajustado para alinearse con la imagen -->
                        <g class="city-marker" data-city="madrid" data-timezone="Europe/Madrid">
                            <circle cx="490" cy="180" r="12" fill="#14b8a6" stroke="#fff" stroke-width="3" class="city-dot" opacity="0.9"/>
                            <circle cx="490" cy="180" r="8" fill="#fff" class="city-pulse"/>
                            <text x="490" y="165" text-anchor="middle" fill="#fff" font-size="11" font-weight="bold" style="text-shadow: 0 1px 2px rgba(0,0,0,0.5);">MAD</text>
                            <rect x="460" y="200" width="60" height="25" rx="4" fill="rgba(255,255,255,0.95)" stroke="#14b8a6" stroke-width="1.5" opacity="0.95"/>
                            <text x="490" y="217" text-anchor="middle" fill="#1e293b" font-size="11" font-weight="bold" id="map-clock-madrid">--:--</text>
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
                'madrid': 'Europe/Madrid'
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
        filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
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
            transform: scale(1.3);
        }
    }
    
    .dark .world-map-svg {
        filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.3));
    }
</style>

