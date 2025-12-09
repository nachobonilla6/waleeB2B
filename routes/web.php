<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Controllers\SupportCaseController;
use App\Models\Sitio;

// Página de inicio
Route::get('/', function () {
    $sitios = Sitio::with('tags')
        ->where('en_linea', true)
        ->orderBy('created_at', 'desc')
        ->take(6) // Show only 6 most recent active sites
        ->get();
    
    return view('welcome', compact('sitios'));
})->name('home');

// Dashboard
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Formulario para crear un nuevo ticket
Route::get('/tickets/crear', function () {
    return view('test-form');
})->name('tickets.create');

// Ruta para la plataforma educativa
Route::get('/educacion', [App\Http\Controllers\EducationController::class, 'index'])->name('education.index');

// Ruta para el salón de belleza
Route::get('/salon', [App\Http\Controllers\SalonController::class, 'index'])->name('salon.index');

// Rutas para eliminación de datos
Route::get('/data-deletion', [App\Http\Controllers\DataDeletionController::class, 'show'])
    ->name('data-deletion.show');

Route::delete('/data-deletion', [App\Http\Controllers\DataDeletionController::class, 'destroy'])
    ->middleware('auth')
    ->name('user.data.delete');

// Rutas para páginas legales
Route::get('/privacidad', function () {
    return view('privacy');
})->name('privacy');

Route::get('/terminos', function () {
    return view('terms');
})->name('terms');

// Webhook para recibir datos del formulario
Route::post('/webhook/tickets', function (\Illuminate\Http\Request $request) {
    // Validar los datos recibidos
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'image' => 'nullable|image|max:2048', // 2MB Max
    ]);

    // Procesar la imagen si se subió
    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('case-images', 'public');
    }

    // Datos para el webhook
    $webhookData = [
        'name' => $validated['name'],
        'email' => $validated['email'],
        'title' => $validated['title'],
        'description' => $validated['description'],
        'status' => 'open',
        'image' => $imagePath,
        'created_at' => now()->toDateTimeString(),
    ];

    // Aquí iría el código para enviar los datos a tu webhook externo
    // Por ejemplo, usando Guzzle o similar
    // $response = Http::post('URL_DEL_WEBHOOK', $webhookData);

    // Por ahora, solo retornamos los datos como JSON
    return response()->json([
        'success' => true,
        'message' => 'Ticket recibido correctamente',
        'data' => $webhookData
    ]);
})->name('webhook.tickets');

// Rutas para los casos de soporte
Route::get('/cases', [SupportCaseController::class, 'index'])->name('cases.index');
Route::post('/support-cases/{case}/close', [SupportCaseController::class, 'close'])
    ->name('support-cases.close');

// Site Scraper Routes
// Rutas protegidas para el scraper de sitios web
Route::middleware(['auth'])->group(function () {
    Route::get('/site-scraper', [\App\Http\Controllers\SiteScraperController::class, 'index'])->name('site-scraper');
    Route::post('/site-scraper/search', [\App\Http\Controllers\SiteScraperController::class, 'search'])->name('site-scraper.search');
    
    // Ruta para el chat
    Route::get('/chat', function () {
        return view('chat.index');
    })->name('chat');
    
    // Callback de Google Calendar OAuth2
    Route::get('/google-calendar/callback', function (\Illuminate\Http\Request $request) {
        $code = $request->get('code');
        
        if (!$code) {
            \Filament\Notifications\Notification::make()
                ->title('Error de autorización')
                ->body('No se recibió el código de autorización de Google.')
                ->danger()
                ->send();
            
            return redirect()->route('filament.admin.pages.google-calendar-auth');
        }
        
        $service = new \App\Services\GoogleCalendarService();
        $success = $service->handleCallback($code);
        
        if ($success) {
            \Filament\Notifications\Notification::make()
                ->title('Autorización exitosa')
                ->body('Google Calendar ha sido autorizado correctamente.')
                ->success()
                ->send();
        } else {
            \Filament\Notifications\Notification::make()
                ->title('Error de autorización')
                ->body('No se pudo completar la autorización. Intenta nuevamente.')
                ->danger()
                ->send();
        }
        
        return redirect()->route('filament.admin.pages.google-calendar-auth');
    })->name('google-calendar.callback');
});

// Rutas de autenticación y configuración
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    if (Features::canManageTwoFactorAuthentication()) {
        Volt::route('settings/two-factor', 'settings.two-factor')
            ->middleware(Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword') 
                ? ['auth', 'password.confirm'] 
                : ['auth']
            )
            ->name('two-factor.show');
    }
});



Route::get('/test-form', function () {
    return view('test-form');
});

// Ruta para venta de camas/colchones
Route::get('/camas', function () {
    return view('camas');
})->name('camas');

// Ruta para clientes con propuesta enviada
Route::get('/clientes/propuesta-enviada', [App\Http\Controllers\ClientPropuestaEnviadaController::class, 'index'])
    ->middleware('auth')
    ->name('clients.propuesta-enviada');

// Rutas para listas personalizadas móviles
Route::prefix('ejemplo1/lista')->group(function () {
    Route::get('/clientes', function () {
        $clientes = \App\Models\Cliente::orderBy('created_at', 'desc')->paginate(20);
        return view('ejemplo1-listas.clientes', compact('clientes'));
    })->name('ejemplo1.lista.clientes');
    
    Route::get('/clientes/{id}', function ($id) {
        $cliente = \App\Models\Cliente::findOrFail($id);
        return view('ejemplo1-listas.show.cliente', compact('cliente'));
    })->name('ejemplo1.show.cliente');
    
    Route::get('/citas', function () {
        $citas = \App\Models\Cita::with('cliente')->orderBy('fecha_inicio', 'desc')->paginate(20);
        return view('ejemplo1-listas.citas', compact('citas'));
    })->name('ejemplo1.lista.citas');
    
    Route::get('/citas/{id}', function ($id) {
        $cita = \App\Models\Cita::with('cliente')->findOrFail($id);
        return view('ejemplo1-listas.show.cita', compact('cita'));
    })->name('ejemplo1.show.cita');
    
    Route::get('/usuarios', function () {
        $usuarios = \App\Models\User::orderBy('created_at', 'desc')->paginate(20);
        return view('ejemplo1-listas.usuarios', compact('usuarios'));
    })->name('ejemplo1.lista.usuarios');
    
    Route::get('/usuarios/{id}', function ($id) {
        $usuario = \App\Models\User::findOrFail($id);
        return view('ejemplo1-listas.show.usuario', compact('usuario'));
    })->name('ejemplo1.show.usuario');
    
    Route::get('/propuestas-enviadas', function () {
        $propuestas = \App\Models\Client::where('propuesta_enviada', true)->orderBy('created_at', 'desc')->paginate(20);
        return view('ejemplo1-listas.propuestas-enviadas', compact('propuestas'));
    })->name('ejemplo1.lista.propuestas-enviadas');
    
    Route::get('/propuestas-enviadas/{id}', function ($id) {
        $propuesta = \App\Models\Client::findOrFail($id);
        return view('ejemplo1-listas.show.propuesta-enviada', compact('propuesta'));
    })->name('ejemplo1.show.propuesta-enviada');
    
    Route::get('/facturas', function () {
        $facturas = \App\Models\Factura::with('cliente')->orderBy('created_at', 'desc')->paginate(20);
        return view('ejemplo1-listas.facturas', compact('facturas'));
    })->name('ejemplo1.lista.facturas');
    
    Route::get('/facturas/{id}', function ($id) {
        $factura = \App\Models\Factura::with('cliente')->findOrFail($id);
        return view('ejemplo1-listas.show.factura', compact('factura'));
    })->name('ejemplo1.show.factura');
    
    Route::get('/sitios', function () {
        $sitios = \App\Models\Sitio::with('tags')->orderBy('created_at', 'desc')->paginate(20);
        return view('ejemplo1-listas.sitios', compact('sitios'));
    })->name('ejemplo1.lista.sitios');
    
    Route::get('/sitios/{id}', function ($id) {
        $sitio = \App\Models\Sitio::with('tags')->findOrFail($id);
        return view('ejemplo1-listas.show.sitio', compact('sitio'));
    })->name('ejemplo1.show.sitio');
    
    Route::get('/support-cases', function () {
        $cases = \App\Models\SupportCase::orderBy('created_at', 'desc')->paginate(20);
        return view('ejemplo1-listas.support-cases', compact('cases'));
    })->name('ejemplo1.lista.support-cases');
    
    Route::get('/support-cases/{id}', function ($id) {
        $case = \App\Models\SupportCase::findOrFail($id);
        return view('ejemplo1-listas.show.support-case', compact('case'));
    })->name('ejemplo1.show.support-case');
});

// Ruta ejemplo1 - Información de Filament optimizada para móviles
Route::get('/ejemplo1', function () {
    try {
        $panel = \Filament\Facades\Filament::getPanel('admin');
    } catch (\Exception $e) {
        $panel = null;
    }
    
    // Estadísticas
    $clientes = \App\Models\Cliente::count();
    $clientesActivos = \App\Models\Cliente::where('estado_cuenta', 'activo')->count();
    $citasHoy = \App\Models\Cita::whereDate('fecha_inicio', today())->where('estado', '!=', 'cancelada')->count();
    $citasProximas = \App\Models\Cita::where('fecha_inicio', '>=', now())
        ->where('estado', '!=', 'cancelada')
        ->orderBy('fecha_inicio', 'asc')
        ->limit(5)
        ->with('cliente')
        ->get();
    $citasTotal = \App\Models\Cita::where('estado', '!=', 'cancelada')->count();
    $usuarios = \App\Models\User::count();
    
    // Mapeo de recursos de Filament a rutas personalizadas
    $resourceRouteMap = [
        'App\\Filament\\Resources\\ClienteResource' => 'ejemplo1.lista.clientes',
        'App\\Filament\\Resources\\ClientPropuestaEnviadaResource' => 'ejemplo1.lista.propuestas-enviadas',
        'App\\Filament\\Resources\\FacturaResource' => 'ejemplo1.lista.facturas',
        'App\\Filament\\Resources\\SitioResource' => 'ejemplo1.lista.sitios',
        'App\\Filament\\Resources\\SupportCaseResource' => 'ejemplo1.lista.support-cases',
        'App\\Filament\\Resources\\UserResource' => 'ejemplo1.lista.usuarios',
    ];
    
    // Mapeo de páginas de Filament a rutas personalizadas
    $pageRouteMap = [
        'App\\Filament\\Pages\\GoogleCalendar' => 'ejemplo1.lista.citas',
    ];
    
    // Obtener todos los recursos y páginas
    $groupedItems = [];
    
    if ($panel) {
        // Obtener todos los recursos
        $resources = [];
        foreach ($panel->getResources() as $resourceClass) {
            try {
                if (method_exists($resourceClass, 'shouldRegisterNavigation') && !$resourceClass::shouldRegisterNavigation()) {
                    continue;
                }
                
                $resourceClassFull = is_string($resourceClass) ? $resourceClass : get_class($resourceClass);
                $customRoute = $resourceRouteMap[$resourceClassFull] ?? null;
                
                $resources[] = [
                    'name' => $resourceClass::getNavigationLabel() ?? class_basename($resourceClass),
                    'url' => $customRoute ? route($customRoute) : ($resourceClass::getUrl('index') ?? '#'),
                    'icon' => $resourceClass::getNavigationIcon(),
                    'group' => $resourceClass::getNavigationGroup() ?? 'Otros',
                    'badge' => method_exists($resourceClass, 'getNavigationBadge') ? $resourceClass::getNavigationBadge() : null,
                ];
            } catch (\Exception $e) {
                // Ignorar recursos que no se pueden cargar
                continue;
            }
        }
        
        // Obtener todas las páginas personalizadas
        $pages = [];
        foreach ($panel->getPages() as $pageClass) {
            try {
                if (method_exists($pageClass, 'shouldRegisterNavigation') && !$pageClass::shouldRegisterNavigation()) {
                    continue;
                }
                
                $pageClassFull = is_string($pageClass) ? $pageClass : get_class($pageClass);
                $customRoute = $pageRouteMap[$pageClassFull] ?? null;
                
                $pages[] = [
                    'name' => $pageClass::getNavigationLabel() ?? class_basename($pageClass),
                    'url' => $customRoute ? route($customRoute) : ($pageClass::getUrl() ?? '#'),
                    'icon' => $pageClass::getNavigationIcon(),
                    'group' => $pageClass::getNavigationGroup() ?? 'Otros',
                ];
            } catch (\Exception $e) {
                // Ignorar páginas que no se pueden cargar
                continue;
            }
        }
        
        // Agrupar recursos y páginas por grupo
        foreach ($resources as $item) {
            $group = $item['group'] ?? 'Otros';
            if (!isset($groupedItems[$group])) {
                $groupedItems[$group] = [];
            }
            $groupedItems[$group][] = $item;
        }
        foreach ($pages as $item) {
            $group = $item['group'] ?? 'Otros';
            if (!isset($groupedItems[$group])) {
                $groupedItems[$group] = [];
            }
            $groupedItems[$group][] = $item;
        }
    }
    
    return view('ejemplo1-mobile', compact(
        'clientes',
        'clientesActivos',
        'citasHoy',
        'citasProximas',
        'citasTotal',
        'usuarios',
        'groupedItems'
    ));
})->name('ejemplo1');
