<x-filament-panels::page
    :max-content-width="$this->getMaxContentWidth()"
    :heading="$this->getHeading()"
    :subheading="$this->getSubheading()"
>
    <div class="flex flex-col gap-y-6">
        <x-filament-panels::resources.tabs />

        {{-- Tarjetas arriba --}}
        <x-filament-widgets::widgets :widgets="$this->getHeaderWidgets()" />

        {{-- Formulario abajo --}}
        <x-filament-widgets::widgets :widgets="$this->getFooterWidgets()" />
    </div>
</x-filament-panels::page>
