<?php

namespace App\Filament\Pages;

use App\Services\GoogleCalendarService;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class GoogleCalendarAuth extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?string $navigationLabel = 'Autorizar Google Calendar';
    protected static ?string $title = 'Autorizar Google Calendar';
    protected static ?string $navigationGroup = 'Configuración';
    protected static ?int $navigationSort = 100;

    protected static string $view = 'filament.pages.google-calendar-auth';

    public function mount(): void
    {
        // Verificar si ya está autorizado
    }

    public function authorizeGoogleCalendar()
    {
        try {
            $service = new GoogleCalendarService();
            $authUrl = $service->getAuthUrl();
            
            if (!$authUrl) {
                Notification::make()
                    ->title('Error')
                    ->body('No se pudieron cargar las credenciales de Google Calendar. Verifica la configuración.')
                    ->danger()
                    ->send();
                return;
            }
            
            // Redirigir a Google para autorizar
            return redirect($authUrl);
        } catch (\Exception $e) {
            Log::error('Error en autorización: ' . $e->getMessage());
            Notification::make()
                ->title('Error')
                ->body('Error al iniciar la autorización: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function checkAuthStatus(): bool
    {
        $service = new GoogleCalendarService();
        return $service->isAuthorized();
    }
}
