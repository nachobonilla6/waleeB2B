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

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    protected static string $view = 'filament.pages.google-calendar-auth';

    public function mount(): void
    {
        // Verificar si ya está autorizado
    }

    public function authorizeGoogleCalendar()
    {
        try {
            $service = new GoogleCalendarService();
            $credentialsPath = $service->findCredentialsFile();
            
            // Verificar si el archivo existe
            if (!$credentialsPath) {
                $possiblePaths = [
                    config('services.google.credentials_path'),
                    storage_path('app/google-credentials.json'),
                    base_path('storage/app/google-credentials.json'),
                ];
                $pathsList = implode(', ', array_filter($possiblePaths));
                
                Notification::make()
                    ->title('Error de configuración')
                    ->body('El archivo de credenciales no se encuentra en ninguna de estas ubicaciones: ' . $pathsList . '. Por favor, sube el archivo google-credentials.json a una de esas ubicaciones en el servidor.')
                    ->danger()
                    ->persistent()
                    ->send();
                return;
            }
            
            $authUrl = $service->getAuthUrl('filament');
            
            if (!$authUrl) {
                Notification::make()
                    ->title('Error')
                    ->body('No se pudo generar la URL de autorización. Verifica los logs para más detalles.')
                    ->danger()
                    ->send();
                return;
            }
            
            // Redirigir a Google para autorizar
            return redirect($authUrl);
        } catch (\Exception $e) {
            Log::error('Error en autorización: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
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

    public function disconnectGoogleCalendar()
    {
        try {
            $service = new GoogleCalendarService();
            $success = $service->disconnect();
            
            if ($success) {
                Notification::make()
                    ->title('Desconectado')
                    ->body('Google Calendar ha sido desconectado. Puedes conectarlo nuevamente con otra cuenta.')
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Error')
                    ->body('No se pudo desconectar Google Calendar.')
                    ->danger()
                    ->send();
            }
        } catch (\Exception $e) {
            Log::error('Error desconectando Google Calendar: ' . $e->getMessage());
            Notification::make()
                ->title('Error')
                ->body('Error al desconectar: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function getAuthorizedEmail(): ?string
    {
        try {
            $service = new GoogleCalendarService();
            return $service->getAuthorizedEmail();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getCalendarId(): string
    {
        try {
            $service = new GoogleCalendarService();
            return $service->getCalendarId();
        } catch (\Exception $e) {
            return 'primary';
        }
    }
}
