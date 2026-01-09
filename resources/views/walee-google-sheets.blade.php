<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Google Sheets</title>
    <meta name="description" content="Walee - Ver Google Sheets">
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
        
        .table-container {
            overflow-x: auto;
        }
        
        table {
            min-width: 100%;
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">
    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-green-400/20 dark:bg-green-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-violet-400/10 dark:bg-violet-400/5 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-4 py-6 sm:px-6 lg:px-8">
            @php $pageTitle = 'Google Sheets'; @endphp
            @include('partials.walee-navbar')
            
            <!-- Header -->
            <header class="flex items-center justify-between mb-8 animate-fade-in-up">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">
                        Google Sheets
                    </h1>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Visualiza datos de Google Sheets</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('walee.herramientas') }}" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white font-medium rounded-xl transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span class="hidden sm:inline">Back</span>
                    </a>
                    @include('partials.walee-dark-mode-toggle')
                </div>
            </header>
            
            <!-- Form -->
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 shadow-sm dark:shadow-none animate-fade-in-up mb-8">
                <form method="GET" action="{{ route('walee.google-sheets') }}" class="space-y-4">
                    <div>
                        <label for="spreadsheet_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Google Sheets ID
                        </label>
                        <div class="flex gap-3">
                            <input 
                                type="text" 
                                id="spreadsheet_id" 
                                name="spreadsheet_id" 
                                value="{{ request('spreadsheet_id') }}"
                                placeholder="Ej: 1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms"
                                class="flex-1 px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                required
                            >
                            <input 
                                type="text" 
                                id="range" 
                                name="range" 
                                value="{{ request('range', 'A1:Z1000') }}"
                                placeholder="Rango (opcional): A1:Z1000"
                                class="w-48 px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            >
                            <button 
                                type="submit" 
                                class="px-6 py-2.5 bg-green-500 hover:bg-green-600 text-white font-medium rounded-xl transition-all flex items-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <span>Buscar</span>
                            </button>
                        </div>
                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                            El ID del spreadsheet se encuentra en la URL: <code class="bg-slate-100 dark:bg-slate-700 px-1 py-0.5 rounded">https://docs.google.com/spreadsheets/d/<strong>SPREADSHEET_ID</strong>/edit</code>
                        </p>
                    </div>
                </form>
            </div>
            
            @if(request('spreadsheet_id'))
                @php
                    $sheetsService = new \App\Services\GoogleSheetsService();
                    $spreadsheetId = request('spreadsheet_id');
                    $range = request('range', 'A1:Z1000');
                    $data = $sheetsService->getSheetData($spreadsheetId, $range);
                    $info = $sheetsService->getSpreadsheetInfo($spreadsheetId);
                @endphp
                
                @if($data === null)
                    <!-- Link to Dashboard -->
                    <div class="mb-4">
                        <a href="{{ route('walee.sheets.dashboard', ['spreadsheet_id' => request('spreadsheet_id'), 'range' => request('range', 'A1:Z1000')]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-medium rounded-xl transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <span>Ir a Dashboard de Control</span>
                        </a>
                    </div>
                    <!-- Error -->
                    <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-2xl p-6 animate-fade-in-up">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <h3 class="text-lg font-semibold text-red-900 dark:text-red-300">Error al obtener datos</h3>
                                <p class="text-sm text-red-700 dark:text-red-400">
                                    No se pudo acceder al Google Sheet. Verifica que:
                                </p>
                                <ul class="mt-2 text-sm text-red-700 dark:text-red-400 list-disc list-inside">
                                    <li>El ID del spreadsheet sea correcto</li>
                                    <li>El spreadsheet sea público o tengas acceso</li>
                                    <li>Las credenciales de Google estén configuradas</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @elseif(empty($data))
                    <!-- Sin datos -->
                    <div class="bg-yellow-50 dark:bg-yellow-500/10 border border-yellow-200 dark:border-yellow-500/20 rounded-2xl p-6 animate-fade-in-up">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <div>
                                <h3 class="text-lg font-semibold text-yellow-900 dark:text-yellow-300">Sin datos</h3>
                                <p class="text-sm text-yellow-700 dark:text-yellow-400">
                                    El spreadsheet no contiene datos en el rango especificado.
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Info del Spreadsheet -->
                    @if($info)
                        <div class="bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 rounded-2xl p-4 mb-6 animate-fade-in-up">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-blue-900 dark:text-blue-300">{{ $info['title'] }}</p>
                                    @if(!empty($info['sheets']))
                                        <p class="text-sm text-blue-700 dark:text-blue-400">
                                            Hojas: {{ implode(', ', array_column($info['sheets'], 'title')) }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Table -->
                    <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm dark:shadow-none animate-fade-in-up overflow-hidden">
                        <div class="table-container max-h-[600px] overflow-y-auto">
                            <table class="w-full border-collapse">
                                <thead class="bg-slate-100 dark:bg-slate-700 sticky top-0">
                                    @if(!empty($data))
                                        <tr>
                                            @foreach($data[0] as $index => $header)
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider border-b border-slate-200 dark:border-slate-600">
                                                    {{ $header ?: 'Columna ' . ($index + 1) }}
                                                </th>
                                            @endforeach
                                        </tr>
                                    @endif
                                </thead>
                                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                                    @foreach(array_slice($data, 1) as $row)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                            @foreach($row as $cell)
                                                <td class="px-4 py-3 text-sm text-slate-900 dark:text-slate-200 whitespace-nowrap">
                                                    {{ $cell ?: '-' }}
                                                </td>
                                            @endforeach
                                            @if(count($row) < count($data[0]))
                                                @for($i = count($row); $i < count($data[0]); $i++)
                                                    <td class="px-4 py-3 text-sm text-slate-400 dark:text-slate-500">-</td>
                                                @endfor
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-700/50 border-t border-slate-200 dark:border-slate-600">
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                Mostrando {{ count($data) - 1 }} fila(s) de datos
                            </p>
                        </div>
                    </div>
                @endif
            @else
                <!-- Instrucciones -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 shadow-sm dark:shadow-none animate-fade-in-up">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Cómo usar</h2>
                    <ol class="space-y-3 text-sm text-slate-700 dark:text-slate-300">
                        <li class="flex items-start gap-3">
                            <span class="flex-shrink-0 w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center text-xs font-bold">1</span>
                            <span>Abre tu Google Sheet y copia el ID de la URL. El ID está entre <code class="bg-slate-100 dark:bg-slate-700 px-1 py-0.5 rounded">/d/</code> y <code class="bg-slate-100 dark:bg-slate-700 px-1 py-0.5 rounded">/edit</code></span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="flex-shrink-0 w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center text-xs font-bold">2</span>
                            <span>Pega el ID en el campo de arriba y opcionalmente especifica un rango (por defecto: A1:Z1000)</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="flex-shrink-0 w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center text-xs font-bold">3</span>
                            <span>Haz clic en "Buscar" para ver los datos del spreadsheet</span>
                        </li>
                    </ol>
                </div>
            @endif
        </div>
    </div>
    @include('partials.walee-support-button')
</body>
</html>

