<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use App\Filament\Resources\UserResource;
use App\Filament\Widgets\ReportesStatsWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\Facades\Blade;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        config(['tables.pagination.default_records_per_page' => 5]);
        
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandName('WALEÉ')
            ->login()
            ->sidebarCollapsibleOnDesktop()
            ->darkMode(true)
            ->colors([
                'primary' => Color::Green,
            ])
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->renderHook(
                PanelsRenderHook::GLOBAL_SEARCH_AFTER,
                fn (): string => Blade::render('@livewire(\'deploy-button\')')
            )
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => '<style>
                    /* Searchbar más ancho */
                    .fi-global-search-field { 
                        width: 600px !important; 
                        min-width: 400px !important;
                        max-width: 800px !important;
                    }
                    .fi-global-search-field input { 
                        width: 100% !important;
                        font-size: 1rem !important; 
                    }
                    /* Resultados del search - solo el dropdown de búsqueda */
                    .fi-global-search-field ~ div[x-float-aware],
                    .fi-global-search-results-ctn {
                        width: 600px !important;
                        min-width: 400px !important;
                        max-width: 800px !important;
                    }
                    /* Modales más cuadrados */
                    .fi-modal {
                        border-radius: 4px !important;
                    }
                    .fi-modal-content {
                        border-radius: 4px !important;
                    }
                    .fi-modal-header {
                        border-radius: 4px 4px 0 0 !important;
                    }
                    .fi-modal-footer {
                        border-radius: 0 0 4px 4px !important;
                    }
                    /* Fondo de tecnología/IA para la página de login */
                    .fi-simple-page {
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #4facfe 75%, #00f2fe 100%);
                        background-size: 400% 400%;
                        animation: gradientShift 15s ease infinite;
                        position: relative;
                        min-height: 100vh;
                    }
                    .fi-simple-page::before {
                        content: "";
                        position: absolute;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        background-image: 
                            radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                            radial-gradient(circle at 80% 80%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                            radial-gradient(circle at 40% 20%, rgba(120, 219, 255, 0.3) 0%, transparent 50%),
                            url("data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
                        background-size: cover, cover, cover, 60px 60px;
                        opacity: 0.6;
                        z-index: 0;
                    }
                    .fi-simple-page > * {
                        position: relative;
                        z-index: 1;
                    }
                    @keyframes gradientShift {
                        0% { background-position: 0% 50%; }
                        50% { background-position: 100% 50%; }
                        100% { background-position: 0% 50%; }
                    }
                    /* Asegurar que el formulario tenga fondo semi-transparente */
                    .fi-simple-page .fi-section {
                        background: rgba(255, 255, 255, 0.95) !important;
                        backdrop-filter: blur(10px);
                        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
                    }
                    .dark .fi-simple-page .fi-section {
                        background: rgba(17, 24, 39, 0.95) !important;
                    }
                </style>'
            )
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): string => Blade::render('@livewire(\'chat-widget\')')
            )
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Administración')
                    ->icon('heroicon-o-banknotes'),
                NavigationGroup::make()
                    ->label('Herramientas')
                    ->icon('heroicon-o-wrench-screwdriver'),
                NavigationGroup::make()
                    ->label('Soporte')
                    ->icon('heroicon-o-lifebuoy'),
                NavigationGroup::make()
                    ->label('Configuración')
                    ->icon('heroicon-o-cog-6-tooth'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->resources([
                UserResource::class,
            ])
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                ReportesStatsWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
