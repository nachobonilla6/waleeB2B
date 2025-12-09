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
    public function handleGoogleCallback()
    {
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

            // Redirigir al panel de Filament
            return redirect()->intended(\Filament\Facades\Filament::getUrl());
        } catch (\Exception $e) {
            \Log::error('Error en Google login: ' . $e->getMessage());
            return redirect()->route('filament.admin.auth.login')
                ->with('error', 'No se pudo autenticar con Google. Por favor, intenta de nuevo.');
        }
    }
}

