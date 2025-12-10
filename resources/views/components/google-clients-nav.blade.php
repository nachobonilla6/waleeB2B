@php
    $currentUrl = url()->current();
    $clientesGoogleUrl = \App\Filament\Resources\ClienteEnProcesoResource::getUrl('index');
    $listosUrl = \App\Filament\Resources\ClienteEnProcesoResource::getUrl('listos');
    $propuestasEnviadasUrl = 'https://websolutions.work/admin/clientes-google-enviadas';
    $extraerClientesUrl = \App\Filament\Resources\ClientesGoogleCopiaResource::getUrl('index');

    $isClientesGoogle = str_contains($currentUrl, 'cliente-en-procesos') && !str_contains($currentUrl, 'listos-para-enviar');
    $isListos = str_contains($currentUrl, 'listos-para-enviar');
    $isPropuestasEnviadas = str_contains($currentUrl, 'clientes-google-enviadas');
    $isExtraerClientes = str_contains($currentUrl, 'clientes-google-copias');
@endphp
<div class="w-full max-w-full space-y-4" style="width: 100% !important; max-width: 100% !important; margin-left: -1.5rem !important; margin-right: -1.5rem !important; padding-left: 1.5rem !important; padding-right: 1.5rem !important;">
    <div class="flex flex-wrap gap-4 w-full" style="width: 100% !important;">
        <a href="{{ $clientesGoogleUrl }}" class="px-6 py-3 rounded-lg transition-colors font-medium flex items-center gap-2 {{ $isClientesGoogle ? 'bg-gray-700 dark:bg-gray-600 text-white border-2 border-green-400' : 'bg-gray-900 dark:bg-gray-800 text-white hover:bg-gray-800 dark:hover:bg-gray-700' }}">
            Clientes Google
            @if ($isClientesGoogle)
                <span class="ml-2 w-3 h-3 rounded-full bg-green-400 border-2 border-white inline-block"></span>
                <span class="text-green-400 font-bold">(Estás aquí)</span>
            @endif
        </a>
        <a href="{{ $listosUrl }}" class="px-6 py-3 rounded-lg transition-colors font-medium flex items-center gap-2 {{ $isListos ? 'bg-gray-700 dark:bg-gray-600 text-white border-2 border-green-400' : 'bg-gray-900 dark:bg-gray-800 text-white hover:bg-gray-800 dark:hover:bg-gray-700' }}">
            Listos para Enviar
            @if ($isListos)
                <span class="ml-2 w-3 h-3 rounded-full bg-green-400 border-2 border-white inline-block"></span>
                <span class="text-green-400 font-bold">(Estás aquí)</span>
            @endif
        </a>
        <a href="{{ $propuestasEnviadasUrl }}" class="px-6 py-3 rounded-lg transition-colors font-medium flex items-center gap-2 {{ $isPropuestasEnviadas ? 'bg-gray-700 dark:bg-gray-600 text-white border-2 border-green-400' : 'bg-gray-900 dark:bg-gray-800 text-white hover:bg-gray-800 dark:hover:bg-gray-700' }}">
            Propuestas Enviadas
            @if ($isPropuestasEnviadas)
                <span class="ml-2 w-3 h-3 rounded-full bg-green-400 border-2 border-white inline-block"></span>
                <span class="text-green-400 font-bold">(Estás aquí)</span>
            @endif
        </a>
        <a href="{{ $extraerClientesUrl }}" class="px-6 py-3 rounded-lg transition-colors font-medium flex items-center gap-2 {{ $isExtraerClientes ? 'bg-gray-700 dark:bg-gray-600 text-white border-2 border-green-400' : 'bg-gray-900 dark:bg-gray-800 text-white hover:bg-gray-800 dark:hover:bg-gray-700' }}">
            Extraer Nuevos Clientes
            @if ($isExtraerClientes)
                <span class="ml-2 w-3 h-3 rounded-full bg-green-400 border-2 border-white inline-block"></span>
                <span class="text-green-400 font-bold">(Estás aquí)</span>
            @endif
        </a>
    </div>
</div>
