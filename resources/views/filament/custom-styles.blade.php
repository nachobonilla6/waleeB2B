<style>
    /* Light Mode - Blanco y Negro */
    html:not(.dark) {
        --primary-50: 249 250 251 !important;
        --primary-100: 243 244 246 !important;
        --primary-200: 229 231 235 !important;
        --primary-300: 209 213 219 !important;
        --primary-400: 156 163 175 !important;
        --primary-500: 107 114 128 !important;
        --primary-600: 75 85 99 !important;
        --primary-700: 55 65 81 !important;
        --primary-800: 31 41 55 !important;
        --primary-900: 17 24 39 !important;
        --primary-950: 3 7 18 !important;
    }

    /* Light Mode - Fondo blanco, texto negro */
    html:not(.dark) body,
    html:not(.dark) .fi-body,
    html:not(.dark) .fi-main-ctn,
    html:not(.dark) .fi-main,
    html:not(.dark) .fi-page,
    html:not(.dark) .fi-screen {
        background: #ffffff !important;
        color: #000000 !important;
    }

    html:not(.dark) .fi-sidebar {
        background: #ffffff !important;
        border-right: 1px solid #e5e7eb !important;
    }

    html:not(.dark) .fi-topbar {
        background: #ffffff !important;
        border-bottom: 1px solid #e5e7eb !important;
    }

    html:not(.dark) .fi-card,
    html:not(.dark) .fi-section {
        background: #ffffff !important;
        color: #000000 !important;
    }

    /* Dark Mode - Negro y Blanco (al revés) */
    .dark,
    html.dark,
    [data-theme="dark"] {
        --primary-50: 3 7 18 !important;
        --primary-100: 17 24 39 !important;
        --primary-200: 31 41 55 !important;
        --primary-300: 55 65 81 !important;
        --primary-400: 75 85 99 !important;
        --primary-500: 107 114 128 !important;
        --primary-600: 156 163 175 !important;
        --primary-700: 209 213 219 !important;
        --primary-800: 229 231 235 !important;
        --primary-900: 243 244 246 !important;
        --primary-950: 249 250 251 !important;
    }

    /* Dark Mode - Fondo negro, texto blanco */
    .dark body,
    html.dark body,
    .dark .fi-body,
    html.dark .fi-body,
    .dark .fi-main-ctn,
    html.dark .fi-main-ctn,
    .dark .fi-main,
    html.dark .fi-main,
    .dark .fi-page,
    html.dark .fi-page,
    .dark .fi-screen,
    html.dark .fi-screen {
        background: #000000 !important;
        color: #ffffff !important;
    }

    .dark .fi-sidebar,
    html.dark .fi-sidebar {
        background: #000000 !important;
        border-right: 1px solid #1f2937 !important;
    }

    .dark .fi-topbar,
    html.dark .fi-topbar {
        background: #000000 !important;
        border-bottom: 1px solid #1f2937 !important;
    }

    .dark .fi-card,
    html.dark .fi-card,
    .dark .fi-section,
    html.dark .fi-section {
        background: #000000 !important;
        color: #ffffff !important;
    }
</style>
