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
                </style>'
            )
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
                fn (): string => Blade::render('
                    <div class="mt-6">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white dark:bg-gray-900 text-gray-500 dark:text-gray-400">O continúa con</span>
                            </div>
                        </div>
                        <div class="mt-6">
                            <a href="' . route('auth.google') . '" class="w-full flex items-center justify-center gap-3 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors font-medium">
                                <svg class="w-5 h-5" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                                Continuar con Google
                            </a>
                        </div>
                    </div>
                ')
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
