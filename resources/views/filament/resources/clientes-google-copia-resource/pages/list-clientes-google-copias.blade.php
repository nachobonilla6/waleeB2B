<x-filament-panels::page>
    <div class="flex flex-col gap-y-6">
        <x-filament-panels::resources.tabs />

        {{-- Tarjetas arriba --}}
        <x-filament-widgets::widgets :widgets="[
            \\App\\Filament\\Resources\\ClienteEnProcesoResource\\Widgets\\ClientesEnProcesoCards::class,
        ]" />

        {{-- Formulario abajo --}}
        <x-filament-widgets::widgets :widgets="[
            \\App\\Filament\\Resources\\ClientesGoogleCopiaResource\\Widgets\\SiteScraperFormWidget::class,
        ]" />
    </div>
</x-filament-panels::page>
