<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use App\Filament\Resources\UserResource;
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
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Contabilidad')
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
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Los widgets se registran automáticamente pero el Dashboard los filtra explícitamente
            ])
            ->widgets([
                // No registrar widgets globalmente, cada página los define explícitamente
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
