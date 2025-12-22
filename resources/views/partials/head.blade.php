<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? config('app.name', 'Soporte') }}</title>

<!-- Favicons -->
<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Livewire Styles -->
@livewireStyles

<style>
    body {
        font-family: 'Inter', sans-serif;
    }
    
    /* Estilos globales para violeta claro en light mode */
    html:not(.dark) body {
        background-color: rgb(245, 243, 255) !important;
    }
    
    html:not(.dark) .bg-white {
        background-color: rgb(245, 243, 255) !important;
    }
    
    html:not(.dark) .bg-slate-50 {
        background-color: rgb(245, 243, 255) !important;
    }
    
    html:not(.dark) .bg-slate-100 {
        background-color: rgb(237, 233, 254) !important;
    }
    
    html:not(.dark) .bg-slate-200 {
        background-color: rgb(221, 214, 254) !important;
    }
    
    html:not(.dark) .bg-slate-900\/50,
    html:not(.dark) .bg-slate-900\/80 {
        background-color: rgb(245, 243, 255) !important;
    }
    
    html:not(.dark) .bg-slate-800 {
        background-color: rgb(237, 233, 254) !important;
    }
    
    html:not(.dark) .min-h-screen {
        background-color: rgb(245, 243, 255) !important;
    }
    
    /* Cambiar gradientes comunes a violeta claro */
    html:not(.dark) .bg-gradient-to-br:not([class*="dark:"]):not([class*="bg-violet"]):not([class*="bg-emerald"]):not([class*="bg-blue"]):not([class*="bg-walee"]):not([class*="bg-cyan"]):not([class*="bg-amber"]):not([class*="bg-red"]):not([class*="bg-green"]):not([class*="bg-yellow"]) {
        background: linear-gradient(to bottom right, rgb(245, 243, 255), rgb(237, 233, 254)) !important;
    }
</style>
