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
                    /* Fondo de tecnología/IA para toda la página de login */
                    body[data-page="login"] {
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #4facfe 75%, #00f2fe 100%);
                        background-size: 400% 400%;
                        animation: gradientShift 15s ease infinite;
                        background-attachment: fixed;
                    }
                    body[data-page="login"]::before {
                        content: "";
                        position: fixed;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        background-image: 
                            radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.4) 0%, transparent 50%),
                            radial-gradient(circle at 80% 80%, rgba(255, 119, 198, 0.4) 0%, transparent 50%),
                            radial-gradient(circle at 40% 20%, rgba(120, 219, 255, 0.4) 0%, transparent 50%),
                            url("https://images.unsplash.com/photo-1635070041078-e363dbe005cb?w=1920&q=80");
                        background-size: cover, cover, cover, cover;
                        background-position: center;
                        opacity: 0.3;
                        z-index: 0;
                        pointer-events: none;
                    }
                    @keyframes gradientShift {
                        0% { background-position: 0% 50%; }
                        50% { background-position: 100% 50%; }
                        100% { background-position: 0% 50%; }
                    }
                    /* Asegurar que el contenido esté sobre el fondo */
                    body[data-page="login"] > * {
                        position: relative;
                        z-index: 1;
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
