<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Config Bot Alpha</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="min-h-screen bg-slate-50 dark:bg-slate-900">
    <div class="max-w-4xl mx-auto px-4 py-6 sm:py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-bold text-slate-900 dark:text-white">Config Bot Alpha</h1>
                <p class="text-sm text-slate-600 dark:text-slate-400">Toggle de Extracción de Clientes</p>
            </div>
            <a href="{{ route('walee.bot.alpha') }}" class="inline-flex items-center gap-2 px-3 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white rounded-lg text-sm font-semibold transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Volver
            </a>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-900 dark:text-white">Extracción de Clientes</p>
                    <p class="text-xs text-slate-600 dark:text-slate-400">Activa o desactiva el bot de extracción</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="toggleExtraccion" class="sr-only peer" {{ ($ordenExtraccion->activo ?? false) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-500 dark:peer-focus:ring-blue-400 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-5 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:border-slate-300 after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-blue-500"></div>
                </label>
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const toggleExtraccion = document.getElementById('toggleExtraccion');

        async function guardarEstadoExtraccion(activo) {
            const isDark = document.documentElement.classList.contains('dark');
            try {
                const response = await fetch('/api/ordenes-programadas', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        tipo: 'extraccion_clientes',
                        activo: activo,
                        recurrencia_horas: null,
                        configuracion: null,
                    })
                });
                const data = await response.json();
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Guardado',
                        text: 'Estado de extracción actualizado',
                        timer: 1800,
                        showConfirmButton: false,
                        background: isDark ? '#1e293b' : '#ffffff',
                        color: isDark ? '#e2e8f0' : '#1e293b'
                    });
                } else {
                    throw new Error(data.message || 'Error al guardar');
                }
            } catch (error) {
                console.error('Error al guardar estado:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message,
                    background: isDark ? '#1e293b' : '#ffffff',
                    color: isDark ? '#e2e8f0' : '#1e293b'
                });
                // revert toggle
                toggleExtraccion.checked = !activo;
            }
        }

        toggleExtraccion?.addEventListener('change', (e) => {
            guardarEstadoExtraccion(e.target.checked);
        });
    </script>
</body>
</html>

