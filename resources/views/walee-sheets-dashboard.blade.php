<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Dashboard Control de Contenido</title>
    <meta name="description" content="Walee - Dashboard Control de Contenido">
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
            @php $pageTitle = 'Control de Contenido'; @endphp
            @include('partials.walee-navbar')
            
            <!-- Header -->
            <header class="flex items-center justify-between mb-8 animate-fade-in-up">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">
                        Dashboard Control de Contenido
                    </h1>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Gestiona publicaciones automáticas desde Google Sheets</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('walee.google-sheets') }}" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white font-medium rounded-xl transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span class="hidden sm:inline">Volver</span>
                    </a>
                    @include('partials.walee-dark-mode-toggle')
                </div>
            </header>
            
            @if(request('spreadsheet_id'))
                @php
                    $sheetsService = new \App\Services\GoogleSheetsService();
                    $spreadsheetId = request('spreadsheet_id');
                    $range = request('range', 'A1:Z1000');
                    $data = $sheetsService->getSheetData($spreadsheetId, $range);
                    $info = $sheetsService->getSpreadsheetInfo($spreadsheetId);
                    $isAuthenticated = $sheetsService->isAuthenticated();
                    $tokenPath = storage_path('app/google-sheets-token.json');
                    $hasToken = file_exists($tokenPath);
                    $credentialsPath = config('services.google.credentials_path');
                    $hasCredentials = $credentialsPath && file_exists($credentialsPath);
                @endphp
                
                <!-- Estado de Conexión -->
                <div class="mb-6 animate-fade-in-up">
                    <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center gap-2">
                                    @if($hasCredentials)
                                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                        <span class="text-sm text-slate-700 dark:text-slate-300">Credenciales configuradas</span>
                                    @else
                                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                        <span class="text-sm text-slate-700 dark:text-slate-300">Credenciales no encontradas</span>
                                    @endif
                                </div>
                                <span class="text-slate-400">•</span>
                                <div class="flex items-center gap-2">
                                    @if($hasToken)
                                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                        <span class="text-sm text-slate-700 dark:text-slate-300">Token de acceso</span>
                                    @else
                                        <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                        <span class="text-sm text-slate-700 dark:text-slate-300">Token no encontrado</span>
                                    @endif
                                </div>
                                <span class="text-slate-400">•</span>
                                <div class="flex items-center gap-2">
                                    @if($isAuthenticated)
                                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                        <span class="text-sm text-slate-700 dark:text-slate-300">Conectado</span>
                                    @else
                                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                        <span class="text-sm text-slate-700 dark:text-slate-300">No conectado</span>
                                    @endif
                                </div>
                            </div>
                            @if(!$hasToken || !$isAuthenticated)
                                <a href="{{ route('walee.google-sheets') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                    Configurar conexión
                                </a>
                            @endif
                        </div>
                        @if(!$hasToken)
                            <div class="mt-3 p-3 bg-yellow-50 dark:bg-yellow-500/10 border border-yellow-200 dark:border-yellow-500/20 rounded-lg">
                                <p class="text-sm text-yellow-800 dark:text-yellow-300">
                                    <strong>⚠️ Acción requerida:</strong> Para editar el sheet, necesitas autenticarte. 
                                    Ejecuta: <code class="bg-yellow-100 dark:bg-yellow-500/20 px-2 py-1 rounded">php artisan-auth-google-sheets.php</code>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
                
                @if($data === null || empty($data))
                    <!-- Error -->
                    <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-2xl p-6 animate-fade-in-up">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <h3 class="text-lg font-semibold text-red-900 dark:text-red-300">Error al obtener datos</h3>
                                <p class="text-sm text-red-700 dark:text-red-400">
                                    No se pudo acceder al Google Sheet. Verifica que el ID sea correcto y el sheet sea público.
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Info del Spreadsheet -->
                    @if($info)
                        <div class="bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 rounded-2xl p-4 mb-6 animate-fade-in-up">
                            <div class="flex items-center justify-between">
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
                                <input type="hidden" id="spreadsheet_id_hidden" value="{{ $spreadsheetId }}">
                            </div>
                        </div>
                    @endif
                    
                    <!-- Table -->
                    <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm dark:shadow-none animate-fade-in-up overflow-hidden">
                        <div class="overflow-x-auto max-h-[600px] overflow-y-auto">
                            <table class="w-full border-collapse">
                                <thead class="bg-slate-100 dark:bg-slate-700 sticky top-0">
                                    @if(!empty($data))
                                        <tr>
                                            @foreach($data[0] as $index => $header)
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider border-b border-slate-200 dark:border-slate-600">
                                                    {{ $header ?: 'Columna ' . ($index + 1) }}
                                                </th>
                                            @endforeach
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider border-b border-slate-200 dark:border-slate-600">
                                                Acciones
                                            </th>
                                        </tr>
                                    @endif
                                </thead>
                                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                                    @foreach(array_slice($data, 1) as $rowIndex => $row)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors" data-row="{{ $rowIndex + 2 }}">
                                            @foreach($row as $cellIndex => $cell)
                                                <td class="px-4 py-3 text-sm text-slate-900 dark:text-slate-200">
                                                    <span class="editable-cell focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-blue-50 dark:focus:bg-blue-500/10 px-2 py-1 rounded min-w-[100px] block" 
                                                          data-row="{{ $rowIndex + 2 }}" 
                                                          data-col="{{ $cellIndex + 1 }}" 
                                                          contenteditable="{{ $isAuthenticated ? 'true' : 'false' }}"
                                                          @if(!$isAuthenticated) title="Autentica para editar" @endif>
                                                        {{ $cell ?: '-' }}
                                                    </span>
                                                </td>
                                            @endforeach
                                            @if(count($row) < count($data[0]))
                                                @for($i = count($row); $i < count($data[0]); $i++)
                                                    <td class="px-4 py-3 text-sm text-slate-400 dark:text-slate-500">
                                                        <span class="editable-cell focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-blue-50 dark:focus:bg-blue-500/10 px-2 py-1 rounded min-w-[100px] block" 
                                                              data-row="{{ $rowIndex + 2 }}" 
                                                              data-col="{{ $i + 1 }}" 
                                                              contenteditable="{{ $isAuthenticated ? 'true' : 'false' }}"
                                                              @if(!$isAuthenticated) title="Autentica para editar" @endif>-</span>
                                                    </td>
                                                @endfor
                                            @endif
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-2">
                                                    <label class="cursor-pointer" title="Subir imagen">
                                                        <input type="file" 
                                                               class="hidden image-upload" 
                                                               accept="image/*"
                                                               data-row="{{ $rowIndex + 2 }}"
                                                               data-col="{{ count($data[0]) }}">
                                                        <svg class="w-5 h-5 text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                    </label>
                                                    <button onclick="saveRow({{ $rowIndex + 2 }})" 
                                                            class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 disabled:opacity-50 disabled:cursor-not-allowed"
                                                            @if(!$isAuthenticated) disabled title="Autentica para guardar" @endif>
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @else
                <!-- Form para ingresar ID -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 shadow-sm dark:shadow-none animate-fade-in-up">
                    <form method="GET" action="{{ route('walee.sheets.dashboard') }}" class="space-y-4">
                        <div>
                            <label for="spreadsheet_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Google Sheets ID
                            </label>
                            <div class="flex gap-3">
                                <input 
                                    type="text" 
                                    id="spreadsheet_id" 
                                    name="spreadsheet_id" 
                                    placeholder="Ej: 1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms"
                                    class="flex-1 px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    required
                                >
                                <input 
                                    type="text" 
                                    id="range" 
                                    name="range" 
                                    value="A1:Z1000"
                                    placeholder="Rango: A1:Z1000"
                                    class="w-48 px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                >
                                <button 
                                    type="submit" 
                                    class="px-6 py-2.5 bg-green-500 hover:bg-green-600 text-white font-medium rounded-xl transition-all flex items-center gap-2"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <span>Cargar</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
    
    <script>
        const spreadsheetId = document.getElementById('spreadsheet_id_hidden')?.value || '{{ request('spreadsheet_id') }}';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const isAuthenticated = {{ $isAuthenticated ?? 'false' ? 'true' : 'false' }};
        
        // Manejar subida de imágenes
        document.querySelectorAll('.image-upload').forEach(input => {
            input.addEventListener('change', async function(e) {
                const file = e.target.files[0];
                if (!file) return;
                
                const row = this.dataset.row;
                const col = parseInt(this.dataset.col) || 1;
                
                // Mostrar loading
                const rowElement = document.querySelector(`tr[data-row="${row}"]`);
                const uploadIcon = this.closest('label').querySelector('svg');
                uploadIcon.classList.add('animate-spin');
                
                const formData = new FormData();
                formData.append('image', file);
                formData.append('spreadsheet_id', spreadsheetId);
                formData.append('row', row);
                formData.append('col', col);
                
                try {
                    const response = await fetch('{{ route("walee.sheets.upload-image") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    uploadIcon.classList.remove('animate-spin');
                    
                    if (data.success) {
                        // Buscar la columna de imagen (última columna editable)
                        const cells = rowElement.querySelectorAll('.editable-cell');
                        if (cells.length > 0) {
                            const lastCell = cells[cells.length - 1];
                            lastCell.textContent = data.image_url;
                            lastCell.classList.add('text-green-600', 'dark:text-green-400');
                            
                            // Guardar automáticamente
                            await saveRow(row);
                        }
                        
                        // Mostrar notificación
                        showNotification('Imagen subida y guardada correctamente', 'success');
                    } else {
                        showNotification('Error: ' + data.message, 'error');
                    }
                } catch (error) {
                    uploadIcon.classList.remove('animate-spin');
                    console.error('Error:', error);
                    showNotification('Error al subir la imagen', 'error');
                }
            });
        });
        
        // Función para mostrar notificaciones
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-6 py-3 rounded-xl shadow-lg z-50 animate-fade-in-up ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
        
        // Guardar fila
        async function saveRow(row) {
            const rowElement = document.querySelector(`tr[data-row="${row}"]`);
            const cells = rowElement.querySelectorAll('.editable-cell');
            const values = Array.from(cells).map(cell => cell.textContent.trim() || '');
            
            // Determinar el rango (asumiendo que empezamos en la columna A)
            const startCol = 'A';
            const endCol = String.fromCharCode(64 + cells.length); // Convertir número a letra
            const range = `${startCol}${row}:${endCol}${row}`;
            
            // Mostrar loading
            const saveBtn = rowElement.querySelector('button');
            if (saveBtn) {
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>';
            }
            
            try {
                const response = await fetch('{{ route("walee.sheets.update-row") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        spreadsheet_id: spreadsheetId,
                        range: range,
                        values: values
                    })
                });
                
                const data = await response.json();
                
                if (saveBtn) {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
                }
                
                if (data.success) {
                    // Mostrar feedback visual
                    if (saveBtn) {
                        const originalColor = saveBtn.classList.contains('text-blue-600') ? 'text-blue-600' : 'text-blue-400';
                        saveBtn.classList.remove(originalColor);
                        saveBtn.classList.add('text-green-600');
                        setTimeout(() => {
                            saveBtn.classList.remove('text-green-600');
                            saveBtn.classList.add(originalColor);
                        }, 2000);
                    }
                    showNotification('Fila guardada correctamente', 'success');
                } else {
                    showNotification('Error: ' + data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                if (saveBtn) {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
                }
                showNotification('Error al guardar: ' + error.message, 'error');
            }
        }
        
        // Mejorar la experiencia de edición
        document.querySelectorAll('.editable-cell').forEach(cell => {
            // Guardar valor original
            let originalValue = cell.textContent;
            
            // Al hacer focus, seleccionar todo el texto
            cell.addEventListener('focus', function() {
                originalValue = this.textContent;
                if (window.getSelection && document.createRange) {
                    const range = document.createRange();
                    range.selectNodeContents(this);
                    range.collapse(false);
                    const sel = window.getSelection();
                    sel.removeAllRanges();
                    sel.addRange(range);
                }
            });
            
            // Al presionar Enter, guardar y salir
            cell.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    this.blur();
                    const row = this.dataset.row;
                    saveRow(row);
                }
                if (e.key === 'Escape') {
                    this.textContent = originalValue;
                    this.blur();
                }
            });
        });
    </script>
    @include('partials.walee-support-button')
</body>
</html>

