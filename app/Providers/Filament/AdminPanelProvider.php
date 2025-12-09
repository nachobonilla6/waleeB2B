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
                    /* Layout de dos columnas: imagen izquierda, formulario derecha */
                    body[data-page="login"] .fi-simple-layout {
                        display: grid;
                        grid-template-columns: 1fr 1fr;
                        min-height: 100vh;
                        position: relative;
                    }
                    /* Columna izquierda con imagen de tecnología/IA */
                    body[data-page="login"] .fi-simple-layout::before {
                        content: "";
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 50%;
                        height: 100vh;
                        background-image: url("https://images.unsplash.com/photo-1635070041078-e363dbe005cb?w=1920&q=80");
                        background-size: cover;
                        background-position: center;
                        background-repeat: no-repeat;
                        z-index: 0;
                    }
                    /* Overlay sutil en la imagen */
                    body[data-page="login"] .fi-simple-layout::after {
                        content: "";
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 50%;
                        height: 100vh;
                        background: linear-gradient(135deg, rgba(102, 126, 234, 0.7) 0%, rgba(118, 75, 162, 0.7) 100%);
                        z-index: 1;
                    }
                    body[data-page="login"] .fi-simple-main-ctn {
                        grid-column: 2;
                        background: white;
                        padding: 2rem;
                        position: relative;
                        z-index: 2;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }
                    .dark body[data-page="login"] .fi-simple-main-ctn {
                        background: rgb(17, 24, 39);
                    }
                    /* Ajustar el formulario */
                    body[data-page="login"] .fi-simple-main {
                        max-width: 100% !important;
                        margin: 0 !important;
                        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
                        background: rgba(255, 255, 255, 0.98) !important;
                        backdrop-filter: blur(10px);
                    }
                    .dark body[data-page="login"] .fi-simple-main {
                        background: rgba(17, 24, 39, 0.98) !important;
                    }
                    @media (max-width: 768px) {
                        body[data-page="login"] .fi-simple-layout {
                            grid-template-columns: 1fr;
                        }
                        body[data-page="login"] .fi-simple-layout::before,
                        body[data-page="login"] .fi-simple-layout::after {
                            width: 100%;
                        }
                        body[data-page="login"] .fi-simple-main-ctn {
                            grid-column: 1;
                            background: rgba(255, 255, 255, 0.95);
                        }
                        .dark body[data-page="login"] .fi-simple-main-ctn {
                            background: rgba(17, 24, 39, 0.95);
                        }
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
