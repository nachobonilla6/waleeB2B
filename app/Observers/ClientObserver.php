<?php

namespace App\Observers;

use App\Models\Client;
use Filament\Notifications\Notification;

class ClientObserver
{
    /**
     * Handle the Client "created" event.
     */
    public function created(Client $client): void
    {
        // Mostrar notificación cuando se crea un nuevo cliente
        try {
            $clientName = $client->name ?? 'Sin nombre';
            $clientWebsite = $client->website ?? 'Sin sitio web';
            
            // Limpiar el website para mostrar solo el dominio
            if ($clientWebsite && $clientWebsite !== 'Sin sitio web') {
                $clientWebsite = preg_replace('/^https?:\/\//', '', $clientWebsite);
                $clientWebsite = preg_replace('/^www\./', '', $clientWebsite);
                $clientWebsite = rtrim($clientWebsite, '/');
            }
            
            // Obtener todos los usuarios autenticados en Filament (o el usuario actual si hay uno)
            $users = [];
            
            // Si hay un usuario autenticado en el contexto actual, notificarle
            if (\Filament\Facades\Filament::auth()->check()) {
                $users[] = \Filament\Facades\Filament::auth()->user();
            } else {
                // Si no hay usuario en el contexto actual, notificar a todos los usuarios
                // Esto es útil cuando los clientes se crean desde webhooks o procesos en segundo plano
                $users = \App\Models\User::all();
            }
            
            // Enviar notificación a todos los usuarios
            foreach ($users as $user) {
                Notification::make()
                    ->title('Nuevo Cliente Extraído')
                    ->body("Cliente: {$clientName}" . ($clientWebsite !== 'Sin sitio web' ? " - {$clientWebsite}" : ''))
                    ->success()
                    ->icon('heroicon-o-user-plus')
                    ->sendToDatabase($user);
            }
            
            // Si estamos en un contexto web activo (no en consola), intentar mostrar notificación en tiempo real
            if (!app()->runningInConsole() && request()->hasHeader('X-Livewire')) {
                // Solo para requests de Livewire (Filament)
                try {
                    Notification::make()
                        ->title('Nuevo Cliente Extraído')
                        ->body("Cliente: {$clientName}" . ($clientWebsite !== 'Sin sitio web' ? " - {$clientWebsite}" : ''))
                        ->success()
                        ->icon('heroicon-o-user-plus')
                        ->send();
                } catch (\Exception $e) {
                    // Ignorar si no se puede enviar en tiempo real
                }
            }
        } catch (\Exception $e) {
            // Silenciar errores de notificación para no interrumpir el flujo
            \Log::warning('Error al enviar notificación de nuevo cliente: ' . $e->getMessage());
        }
    }

    /**
     * Handle the Client "updated" event.
     */
    public function updated(Client $client): void
    {
        //
    }

    /**
     * Handle the Client "deleted" event.
     */
    public function deleted(Client $client): void
    {
        //
    }

    /**
     * Handle the Client "restored" event.
     */
    public function restored(Client $client): void
    {
        //
    }

    /**
     * Handle the Client "force deleted" event.
     */
    public function forceDeleted(Client $client): void
    {
        //
    }
}
