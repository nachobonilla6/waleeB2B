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

// Ruta ejemplo1 - Página simplificada móvil-friendly
Route::get('/ejemplo1', function () {
    return view('ejemplo1-mobile');
})->name('ejemplo1');
