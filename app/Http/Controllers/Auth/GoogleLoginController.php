<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleLoginController extends Controller
{
    /**
     * Redirigir al usuario a Google para autenticación
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    /**
     * Manejar el callback de Google
     */
    public function handleGoogleCallback(Request $request)
    {
        $code = $request->get('code');
        $state = $request->get('state');
        
        // Si hay un código pero no viene de Socialite (es decir, es un callback de OAuth directo)
        // Verificar si es un callback de Google Calendar
        if ($code && !$request->has('scope')) {
            // Intentar manejar como callback de Google Calendar
            try {
                $googleService = new \App\Services\GoogleCalendarService();
                $success = $googleService->handleCallback($code);
                
                if ($success) {
                    if ($state === 'aplicaciones') {
                        return redirect()->route('walee.calendario.aplicaciones')
                            ->with('success', 'Google Calendar ha sido autorizado correctamente.');
                    }
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Autorización exitosa')
                        ->body('Google Calendar ha sido autorizado correctamente.')
                        ->success()
                        ->send();
                    
                    return redirect()->route('filament.admin.pages.google-calendar-auth');
                } else {
                    if ($state === 'aplicaciones') {
                        return redirect()->route('walee.calendario.aplicaciones')
                            ->with('error', 'No se pudo completar la autorización. Intenta nuevamente.');
                    }
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Error de autorización')
                        ->body('No se pudo completar la autorización. Intenta nuevamente.')
                        ->danger()
                        ->send();
                    
                    return redirect()->route('filament.admin.pages.google-calendar-auth');
                }
            } catch (\Exception $e) {
                \Log::error('Error en callback de Google Calendar: ' . $e->getMessage());
                // Continuar con el flujo normal de autenticación si falla
            }
        }
        
        // Flujo normal de autenticación con Socialite
        try {
            $googleUser = Socialite::driver('google')->user();

            // Buscar o crear usuario
            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'password' => bcrypt(Str::random(16)), // Contraseña aleatoria
                    'email_verified_at' => now(),
                ]
            );

            // Actualizar información si el usuario ya existe
            if ($user->wasRecentlyCreated === false) {
                $user->update([
                    'name' => $googleUser->getName(),
                ]);
            }

            // Autenticar al usuario
            Auth::login($user, true);

            // Redirigir a walee-dashboard
            return redirect()->intended(route('walee.dashboard'));
        } catch (\Exception $e) {
            \Log::error('Error en Google login: ' . $e->getMessage());
            return redirect()->route('filament.admin.auth.login')
                ->with('error', 'No se pudo autenticar con Google. Por favor, intenta de nuevo.');
        }
    }
}

