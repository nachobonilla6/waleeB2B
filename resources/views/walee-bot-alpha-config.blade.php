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

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-5 space-y-4 mb-4">
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

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2 border-t border-slate-200 dark:border-slate-700">
                <div>
                    <label for="recurrenciaExtraccion" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        Recurrencia de extracción
                    </label>
                    <select
                        id="recurrenciaExtraccion"
                        class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="">Sin recurrencia</option>
                        <option value="0.5">Cada media hora</option>
                        <option value="1">Cada una hora</option>
                        <option value="2">Cada 2 horas</option>
                        <option value="4">Cada 4 horas</option>
                        <option value="6">Cada 6 horas</option>
                        <option value="8">Cada 8 horas</option>
                        <option value="12">Cada 12 horas</option>
                        <option value="24">Cada 24 horas</option>
                        <option value="48">Cada 48 horas</option>
                        <option value="76">Cada 76 horas</option>
                    </select>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                        Define cada cuánto tiempo se ejecutará automáticamente la extracción de clientes.
                    </p>
                </div>
                <div>
                    <label for="idiomaExtraccion" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        Idioma para extracción
                    </label>
                    <select
                        id="idiomaExtraccion"
                        class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 mb-1.5"
                    >
                        <option value="">Todos</option>
                        <option value="es">🇪🇸 Español</option>
                        <option value="en">🇬🇧 English</option>
                        <option value="fr">🇫🇷 Français</option>
                        <option value="de">🇩🇪 Deutsch</option>
                        <option value="it">🇮🇹 Italiano</option>
                        <option value="pt">🇵🇹 Português</option>
                    </select>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                        Filtra la extracción según el idioma principal del contenido.
                    </p>
                </div>
                <div>
                    <label for="industriaExtraccion" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        Industria para extracción
                    </label>
                    <select
                        id="industriaExtraccion"
                        class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 mb-1.5"
                    >
                        <option value="">Todas</option>
                        <option value="Turismo">Turismo</option>
                        <option value="Gastronomía">Gastronomía</option>
                        <option value="Retail">Retail</option>
                        <option value="Salud">Salud</option>
                        <option value="Educación">Educación</option>
                        <option value="Tecnología">Tecnología</option>
                        <option value="Servicios">Servicios</option>
                        <option value="Comercio">Comercio</option>
                        <option value="Manufactura">Manufactura</option>
                        <option value="Inmobiliaria">Inmobiliaria</option>
                        <option value="Automotriz">Automotriz</option>
                        <option value="Belleza y Estética">Belleza y Estética</option>
                        <option value="Fitness y Deportes">Fitness y Deportes</option>
                        <option value="Arte y Cultura">Arte y Cultura</option>
                        <option value="Legal">Legal</option>
                        <option value="Finanzas">Finanzas</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Construcción">Construcción</option>
                        <option value="Agricultura">Agricultura</option>
                        <option value="Otro">Otro / Personalizada</option>
                    </select>
                    <input
                        type="text"
                        id="industriaExtraccionPersonalizada"
                        class="hidden w-full px-2.5 py-1.5 rounded-lg border border-dashed border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Escribe la industria personalizada"
                    />
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                        Elige una industria o escribe una personalizada si no aparece en la lista.
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-5 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-900 dark:text-white">Emails Automáticos</p>
                    <p class="text-xs text-slate-600 dark:text-slate-400">Activa o desactiva el envío automático de emails</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="toggleEmails" class="sr-only peer" {{ ($ordenEmails->activo ?? false) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-emerald-500 dark:peer-focus:ring-emerald-400 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-5 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:border-slate-300 after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-emerald-500"></div>
                </label>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-200 dark:border-slate-700">
                <div>
                    <label for="recurrenciaEmails" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        Recurrencia de emails
                    </label>
                    <select
                        id="recurrenciaEmails"
                        class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    >
                        <option value="">Sin recurrencia</option>
                        <option value="0.5">Cada media hora</option>
                        <option value="1">Cada 1 hora</option>
                        <option value="2">Cada 2 horas</option>
                        <option value="3">Cada 3 horas</option>
                        <option value="4">Cada 4 horas</option>
                        <option value="5">Cada 5 horas</option>
                        <option value="6">Cada 6 horas</option>
                        <option value="8">Cada 8 horas</option>
                        <option value="12">Cada 12 horas</option>
                        <option value="48">Cada 48 horas</option>
                        <option value="72">Cada 72 horas</option>
                    </select>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                        Define cada cuánto tiempo se enviarán automáticamente los emails.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const toggleExtraccion = document.getElementById('toggleExtraccion');
        const recurrenciaSelect = document.getElementById('recurrenciaExtraccion');
        const industriaExtraccionSelect = document.getElementById('industriaExtraccion');
        const industriaExtraccionInput = document.getElementById('industriaExtraccionPersonalizada');
        const idiomaExtraccionSelect = document.getElementById('idiomaExtraccion');
        const toggleEmails = document.getElementById('toggleEmails');
        const recurrenciaEmailsSelect = document.getElementById('recurrenciaEmails');

        // Configuración inicial de extracción desde la BD
        const configuracionExtraccion = @json($ordenExtraccion->configuracion ?? []);

        // Recurrencia inicial de extracción desde la BD (normalizada a número para que coincida con los <option>)
        let recurrenciaActual = @json($ordenExtraccion->recurrencia_horas ?? null);
        // Industria inicial de extracción
        let industriaExtraccionActual = configuracionExtraccion?.industria || '';
        // Idioma inicial de extracción
        let idiomaExtraccionActual = configuracionExtraccion?.idioma || '';

        if (recurrenciaSelect && recurrenciaActual !== null && recurrenciaActual !== undefined) {
            const normalizada = parseFloat(recurrenciaActual);
            if (!isNaN(normalizada)) {
                recurrenciaActual = normalizada;
                recurrenciaSelect.value = String(normalizada);
            } else {
                recurrenciaSelect.value = '';
                recurrenciaActual = null;
            }
        }

        if (industriaExtraccionSelect) {
            const industriasPredefinidas = [
                '',
                'Turismo',
                'Gastronomía',
                'Retail',
                'Salud',
                'Educación',
                'Tecnología',
                'Servicios',
                'Comercio',
                'Manufactura',
                'Inmobiliaria',
                'Automotriz',
                'Belleza y Estética',
                'Fitness y Deportes',
                'Arte y Cultura',
                'Legal',
                'Finanzas',
                'Marketing',
                'Construcción',
                'Agricultura',
            ];

            if (industriaExtraccionActual && industriasPredefinidas.includes(industriaExtraccionActual)) {
                industriaExtraccionSelect.value = industriaExtraccionActual;
                if (industriaExtraccionInput) {
                    industriaExtraccionInput.classList.add('hidden');
                }
            } else if (industriaExtraccionActual) {
                industriaExtraccionSelect.value = 'Otro';
                if (industriaExtraccionInput) {
                    industriaExtraccionInput.classList.remove('hidden');
                    industriaExtraccionInput.value = industriaExtraccionActual;
                }
            } else {
                industriaExtraccionSelect.value = '';
                if (industriaExtraccionInput) {
                    industriaExtraccionInput.classList.add('hidden');
                    industriaExtraccionInput.value = '';
                }
            }
        }

        if (idiomaExtraccionSelect) {
            idiomaExtraccionSelect.value = idiomaExtraccionActual || '';
        }

        // Recurrencia inicial de emails desde la BD (normalizada)
        let recurrenciaEmailsActual = @json($ordenEmails->recurrencia_horas ?? null);

        if (recurrenciaEmailsSelect && recurrenciaEmailsActual !== null && recurrenciaEmailsActual !== undefined) {
            const normalizadaEmails = parseFloat(recurrenciaEmailsActual);
            if (!isNaN(normalizadaEmails)) {
                recurrenciaEmailsActual = normalizadaEmails;
                recurrenciaEmailsSelect.value = String(normalizadaEmails);
            } else {
                recurrenciaEmailsSelect.value = '';
                recurrenciaEmailsActual = null;
            }
        }

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
                        recurrencia_horas: recurrenciaActual ?? null,
                        configuracion: {
                            industria: industriaExtraccionActual || null,
                            idioma: idiomaExtraccionActual || null,
                        },
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

        async function guardarRecurrenciaExtraccion(nuevaRecurrencia) {
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
                        activo: toggleExtraccion?.checked ?? false,
                        recurrencia_horas: nuevaRecurrencia,
                        configuracion: {
                            industria: industriaExtraccionActual || null,
                            idioma: idiomaExtraccionActual || null,
                        },
                    })
                });
                const data = await response.json();
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Guardado',
                        text: 'Recurrencia de extracción actualizada',
                        timer: 1800,
                        showConfirmButton: false,
                        background: isDark ? '#1e293b' : '#ffffff',
                        color: isDark ? '#e2e8f0' : '#1e293b'
                    });
                } else {
                    throw new Error(data.message || 'Error al guardar');
                }
            } catch (error) {
                console.error('Error al guardar recurrencia:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message,
                    background: isDark ? '#1e293b' : '#ffffff',
                    color: isDark ? '#e2e8f0' : '#1e293b'
                });
                // Revertir select al valor anterior
                if (recurrenciaSelect) {
                    recurrenciaSelect.value = recurrenciaActual ? String(recurrenciaActual) : '';
                }
            }
        }

        async function guardarEstadoEmails(activo) {
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
                        tipo: 'emails_automaticos',
                        activo: activo,
                        recurrencia_horas: recurrenciaEmailsActual ?? null,
                        configuracion: null,
                    })
                });
                const data = await response.json();
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Guardado',
                        text: 'Estado de emails automáticos actualizado',
                        timer: 1800,
                        showConfirmButton: false,
                        background: isDark ? '#1e293b' : '#ffffff',
                        color: isDark ? '#e2e8f0' : '#1e293b'
                    });
                } else {
                    throw new Error(data.message || 'Error al guardar');
                }
            } catch (error) {
                console.error('Error al guardar estado de emails:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message,
                    background: isDark ? '#1e293b' : '#ffffff',
                    color: isDark ? '#e2e8f0' : '#1e293b'
                });
                // revert toggle
                if (toggleEmails) {
                    toggleEmails.checked = !activo;
                }
            }
        }

        async function guardarRecurrenciaEmails(nuevaRecurrencia) {
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
                        tipo: 'emails_automaticos',
                        activo: toggleEmails?.checked ?? false,
                        recurrencia_horas: nuevaRecurrencia,
                        configuracion: null,
                    })
                });
                const data = await response.json();
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Guardado',
                        text: 'Recurrencia de emails actualizada',
                        timer: 1800,
                        showConfirmButton: false,
                        background: isDark ? '#1e293b' : '#ffffff',
                        color: isDark ? '#e2e8f0' : '#1e293b'
                    });
                } else {
                    throw new Error(data.message || 'Error al guardar');
                }
            } catch (error) {
                console.error('Error al guardar recurrencia de emails:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message,
                    background: isDark ? '#1e293b' : '#ffffff',
                    color: isDark ? '#e2e8f0' : '#1e293b'
                });
                // Revertir select al valor anterior
                if (recurrenciaEmailsSelect) {
                    recurrenciaEmailsSelect.value = recurrenciaEmailsActual ? String(recurrenciaEmailsActual) : '';
                }
            }
        }

        toggleExtraccion?.addEventListener('change', (e) => {
            guardarEstadoExtraccion(e.target.checked);
        });

        recurrenciaSelect?.addEventListener('change', (e) => {
            const value = e.target.value;
            recurrenciaActual = value ? parseFloat(value) : null;
            guardarRecurrenciaExtraccion(recurrenciaActual);
        });

        industriaExtraccionSelect?.addEventListener('change', (e) => {
            const value = e.target.value;
            if (value === 'Otro') {
                if (industriaExtraccionInput) {
                    industriaExtraccionInput.classList.remove('hidden');
                    industriaExtraccionInput.focus();
                }
                // No pisamos todavía industriaExtraccionActual hasta que escriba
            } else {
                if (industriaExtraccionInput) {
                    industriaExtraccionInput.classList.add('hidden');
                }
                industriaExtraccionActual = value || '';
            }
            // Guardar cambios de configuración (industria predefinida) inmediatamente
            guardarEstadoExtraccion(toggleExtraccion?.checked ?? false);
        });

        industriaExtraccionInput?.addEventListener('input', (e) => {
            industriaExtraccionActual = e.target.value || '';
            // Guardar cambios de configuración (industria personalizada) inmediatamente
            guardarEstadoExtraccion(toggleExtraccion?.checked ?? false);
        });

        idiomaExtraccionSelect?.addEventListener('change', (e) => {
            idiomaExtraccionActual = e.target.value || '';
            // Guardar cambios de configuración (idioma) inmediatamente
            guardarEstadoExtraccion(toggleExtraccion?.checked ?? false);
        });

        toggleEmails?.addEventListener('change', (e) => {
            guardarEstadoEmails(e.target.checked);
        });

        recurrenciaEmailsSelect?.addEventListener('change', (e) => {
            const value = e.target.value;
            recurrenciaEmailsActual = value ? parseFloat(value) : null;
            guardarRecurrenciaEmails(recurrenciaEmailsActual);
        });
    </script>
</body>
</html>

