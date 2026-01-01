<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\Cita;
use App\Models\User;
use App\Observers\ClientObserver;
use App\Observers\CitaObserver;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        
        // Configurar zona horaria de Costa Rica para Carbon
        \Carbon\Carbon::setTimezone(config('app.timezone'));
        
        // Registrar Observer para el modelo Client
        Client::observe(ClientObserver::class);
        
        // Registrar Observer para el modelo Cita
        Cita::observe(CitaObserver::class);
    }

    /**
     * Register the application's policies.
     */
    protected function registerPolicies(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
