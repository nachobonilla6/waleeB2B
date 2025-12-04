<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-gray-100">
        <div class="min-h-screen flex flex-col items-center justify-center p-6">
            <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
                <div class="flex flex-col items-center mb-8">
                    <a href="/" class="text-2xl font-bold text-gray-900">
                        {{ config('app.name', 'Soporte') }}
                    </a>
                    <p class="mt-2 text-sm text-gray-600">
                        {{ $title ?? 'Bienvenido' }}
                    </p>
                </div>
                
                {{ $slot }}
                
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        @if (Route::has('login'))
                            ¿Ya tienes una cuenta? 
                            <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                                Inicia sesión
                            </a>
                        @endif
                        
                        @if (Route::has('register') && str_contains(url()->current(), 'login'))
                            <span class="mx-2">•</span>
                            <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                                Regístrate
                            </a>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        
        @livewireScripts
    </body>
</html>
