<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DataDeletionController extends Controller
{
    /**
     * Muestra el formulario de solicitud de eliminación de datos
     */
    public function show()
    {
        return view('data-deletion');
    }

    /**
     * Procesa la solicitud de eliminación de datos
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        try {
            // Aquí iría la lógica para eliminar los datos del usuario
            // Por ejemplo:
            // $user->tokens()->delete();
            // $user->tickets()->delete();
            // etc.

            // Opcional: Anonimizar los datos en lugar de eliminarlos
            $user->update([
                'name' => 'Usuario Eliminado',
                'email' => 'deleted_' . $user->id . '_' . Str::random(8) . '@deleted.com',
                'password' => Hash::make(Str::random(32)),
                'deleted_at' => now(),
            ]);

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('home')
                ->with('status', 'Todos tus datos han sido eliminados correctamente.');

        } catch (\Exception $e) {
            Log::error('Error al eliminar datos de usuario: ' . $e->getMessage());
            
            return back()->withErrors([
                'error' => 'Ocurrió un error al intentar eliminar tus datos. Por favor, inténtalo de nuevo más tarde.'
            ]);
        }
    }
}
