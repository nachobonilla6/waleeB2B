<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <script>
        // Forzar tema oscuro
        document.documentElement.classList.add('dark');
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Solutions CR - Diseño y Desarrollo Web</title>
    <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/512/2115/2115955.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        .float-animation { animation: float 3s ease-in-out infinite; }
        
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .gradient-animation {
            background: linear-gradient(-45deg, #667eea, #764ba2, #f093fb, #4facfe);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }
        
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .project-card {
            transition: all 0.3s ease;
        }
        .project-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        /* Simple Music Button */
        .music-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(10px);
            border: none;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            z-index: 50;
        }
        
        .music-btn:hover {
            transform: scale(1.1);
            background: rgba(102, 126, 234, 0.9);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>
<body class="bg-gray-900 text-white">
    <!-- Hero Section -->
    <header class="gradient-animation min-h-screen flex items-center justify-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-20 left-10 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl animate-pulse"></div>
            <div class="absolute top-40 right-10 w-72 h-72 bg-blue-500 rounded-full mix-blend-multiply filter blur-xl animate-pulse" style="animation-delay: 2s;"></div>
            <div class="absolute -bottom-8 left-1/2 w-72 h-72 bg-pink-500 rounded-full mix-blend-multiply filter blur-xl animate-pulse" style="animation-delay: 4s;"></div>
        </div>
        
        <div class="container mx-auto px-6 text-center relative z-10">
            <h1 class="text-6xl md:text-8xl font-bold mb-4 float-animation">
                Web Solutions CR
            </h1>
            <p class="text-2xl md:text-3xl mb-2 text-white/90">
                Transformamos ideas en experiencias digitales
            </p>
            <p class="text-lg font-medium mb-8 bg-gradient-to-r from-purple-300 via-pink-300 to-blue-300 text-transparent bg-clip-text">
                Servicios profesionales en <span class="font-bold">Español</span>, <span class="font-bold">English</span> y <span class="font-bold">Français</span>
            </p>
            <div class="flex flex-wrap justify-center gap-4 mb-12">
                <span class="bg-gradient-to-r from-purple-500 to-blue-500 px-6 py-3 rounded-full text-lg text-white font-medium shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">Diseño Web</span>
                <span class="bg-gradient-to-r from-green-500 to-teal-500 px-6 py-3 rounded-full text-lg text-white font-medium shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">Desarrollo</span>
                <span class="bg-gradient-to-r from-pink-500 to-rose-500 px-6 py-3 rounded-full text-lg text-white font-medium shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">AI & Bots</span>
                <span class="bg-gradient-to-r from-amber-500 to-orange-500 px-6 py-3 rounded-full text-lg text-white font-medium shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">Mensajería</span>
                <span class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-3 rounded-full text-lg text-white font-medium shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">Tiendas en Línea</span>
            </div>
            <a href="#servicios" class="inline-block bg-white text-purple-600 px-8 py-4 rounded-full text-lg font-semibold hover:bg-purple-100 transition transform hover:scale-105">
                Explorar Servicios
            </a>
            <div class="mt-6">
                <a href="tel:+50688061829" 
                   class="text-4xl md:text-5xl font-black text-amber-500 hover:text-amber-400 transition-all duration-300">
                    <i class="fas fa-phone-alt mr-2"></i>+506 8806 1829
                </a>
            </div>
        </div>
    </header>

    <!-- Services Section -->
    <section id="servicios" class="py-20 bg-gray-800">
        <div class="container mx-auto px-6">
            <h2 class="text-5xl font-bold text-center mb-4 bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                Nuestros Servicios
            </h2>
            <p class="text-center text-white text-lg mb-16">
                Servicios profesionales en <span class="font-semibold">Español</span>, <span class="font-semibold">English</span> y <span class="font-semibold">Français</span>
            </p>
            
            <div class="grid sm:grid-cols-2 lg:grid-cols-6 gap-4">
    <div class="bg-gradient-to-br from-amber-500 to-pink-600 p-6 rounded-2xl shadow-2xl hover:shadow-amber-500/50 transition transform hover:scale-105">
        <div class="text-5xl mb-3">📱</div>
        <h3 class="text-xl font-bold mb-3">Marketing Digital</h3>
        <p class="text-amber-100 text-sm">Estrategias efectivas en redes sociales para impulsar tu negocio.</p>
    </div>
    
    <div class="bg-gradient-to-br from-purple-600 to-purple-800 p-6 rounded-2xl shadow-2xl hover:shadow-purple-500/50 transition transform hover:scale-105">
        <div class="text-5xl mb-3">🎨</div>
        <h3 class="text-xl font-bold mb-3">Diseño Web</h3>
        <p class="text-purple-100 text-sm">Interfaces modernas y atractivas que cautivan a tus usuarios.</p>
    </div>
    
    <div class="bg-gradient-to-br from-blue-600 to-blue-800 p-6 rounded-2xl shadow-2xl hover:shadow-blue-500/50 transition transform hover:scale-105">
        <div class="text-5xl mb-3">💻</div>
        <h3 class="text-xl font-bold mb-3">Desarrollo Web</h3>
        <p class="text-blue-100 text-sm">Sitios web robustos y escalables con tecnología de punta.</p>
    </div>
    
    <div class="bg-gradient-to-br from-pink-600 to-pink-800 p-6 rounded-2xl shadow-2xl hover:shadow-pink-500/50 transition transform hover:scale-105">
        <div class="text-5xl mb-3">🤖</div>
        <h3 class="text-xl font-bold mb-3">Bots con AI</h3>
        <p class="text-pink-100 text-sm">Automatización inteligente para tus clientes 24/7.</p>
    </div>
    
    <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 p-6 rounded-2xl shadow-2xl hover:shadow-indigo-500/50 transition transform hover:scale-105">
        <div class="text-5xl mb-3">💬</div>
        <h3 class="text-xl font-bold mb-3">Mensajería AI</h3>
        <p class="text-indigo-100 text-sm">Comunicación efectiva con tus clientes.</p>
    </div>

    <div class="bg-gradient-to-br from-green-600 to-emerald-800 p-6 rounded-2xl shadow-2xl hover:shadow-green-500/50 transition transform hover:scale-105">
    <div class="text-5xl mb-3">🛒</div>
    <h3 class="text-xl font-bold mb-3">Tienda en Línea</h3>
    <p class="text-green-100 text-sm">Creación de tiendas en Shopify y otras plataformas para vender en internet fácilmente.</p>
</div>
</div>
            </div>
        </div>
    </section>

    <!-- Portfolio Gallery -->
    <section id="galeria" class="py-20 bg-gray-900">
        <div class="container mx-auto px-6">
            <h2 class="text-5xl font-bold text-center mb-16 bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
                Proyectos Destacados
            </h2>
            
            @if(isset($sitios) && $sitios->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($sitios as $sitio)
                        <div class="project-card bg-gray-800 rounded-2xl overflow-hidden shadow-xl flex flex-col h-full">
                            <div class="h-48 flex items-center justify-center p-6 relative group bg-gray-900">
                                @if($sitio->imagen)
                                    <img src="{{ asset('storage/' . $sitio->imagen) }}" 
                                         alt="{{ $sitio->nombre }}" 
                                         class="max-h-full max-w-full object-contain transition-transform duration-500 group-hover:scale-150 group-hover:z-50 group-hover:shadow-2xl group-hover:shadow-black/50">
                                @else
                                    @php
                                        $words = explode(' ', $sitio->nombre);
                                        $initials = '';
                                        $maxInitials = 2;
                                        foreach ($words as $i => $word) {
                                            if ($i >= $maxInitials) break;
                                            $initials .= strtoupper(substr($word, 0, 1));
                                        }
                                        $colors = ['from-blue-600 to-blue-800', 'from-purple-600 to-purple-800', 'from-pink-600 to-pink-800', 'from-green-600 to-green-800'];
                                        $colorIndex = abs(crc32($sitio->nombre)) % count($colors);
                                        $colorClass = $colors[$colorIndex];
                                    @endphp
                                    <div class="flex items-center justify-center w-40 h-40 rounded-full text-6xl font-bold bg-gradient-to-br {{ $colorClass }} text-white">
                                        {{ $initials }}
                                    </div>
                                @endif
                            </div>
                            <div class="p-6">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="w-3 h-3 rounded-full {{ $sitio->en_linea ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                    <span class="text-sm {{ $sitio->en_linea ? 'text-green-400' : 'text-red-400' }}">
                                        {{ $sitio->en_linea ? 'En línea' : 'Fuera de línea' }}
                                    </span>
                                </div>
                                <h3 class="text-2xl font-bold mb-2">{{ $sitio->nombre }}</h3>
                                <div class="text-gray-400 mb-4">{{ strip_tags($sitio->descripcion) ?: 'Sitio web profesional' }}</div>
                                
                                @if($sitio->tags->count() > 0)
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        @foreach($sitio->tags->take(3) as $tag)
                                            @php
                                                // Si el tag contiene "sold", usar color rojo
                                                $isSold = stripos($tag->nombre, 'sold') !== false;
                                                if ($isSold) {
                                                    $tagColor = 'bg-red-900/50 text-red-300';
                                                } else {
                                                    $tagColors = ['bg-blue-900/50 text-blue-300', 'bg-purple-900/50 text-purple-300', 'bg-pink-900/50 text-pink-300', 'bg-green-900/50 text-green-300'];
                                                    $tagColor = $tagColors[array_rand($tagColors)];
                                                }
                                            @endphp
                                            <span class="px-3 py-1 text-xs rounded-full {{ $tagColor }}">
                                                {{ $tag->nombre }}
                                            </span>
                                        @endforeach
                                        @if($sitio->tags->count() > 3)
                                            <span class="px-3 py-1 bg-gray-700/50 text-gray-300 text-xs rounded-full">
                                                +{{ $sitio->tags->count() - 3 }} más
                                            </span>
                                        @endif
                                    </div>
                                @endif
                                
                                @if($sitio->url)
                                    <a href="{{ $sitio->url }}" target="_blank" rel="noopener noreferrer" 
                                       class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-semibold px-6 py-2 rounded-full transition-all duration-300 transform hover:scale-105">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        Visitar Sitio
                                    </a>
                                @else
                                    <span class="inline-flex items-center gap-2 bg-gray-600 text-white font-semibold px-6 py-2 rounded-full cursor-not-allowed">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        Solicitar acceso
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-400 text-lg">No hay proyectos para mostrar en este momento.</p>
                </div>
            @endif
            
            <div class="text-center mt-12 w-full">
                <a href="{{ route('filament.admin.resources.sitios.index') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-semibold px-8 py-3 rounded-full transition-all duration-300 transform hover:scale-105">
                    <span>Ver todos los proyectos</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
    </section>
 


    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-purple-600 via-pink-600 to-blue-600">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-5xl font-bold mb-6">¿Listo para transformar tu negocio?</h2>
            <p class="text-2xl mb-8 text-white/90">Contáctanos y llevemos tu proyecto al siguiente nivel</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="mailto:info@websolutions.work" class="bg-white text-purple-600 px-8 py-4 rounded-full text-lg font-semibold hover:bg-gray-100 transition transform hover:scale-105">
                    Solicitar Cotización
                </a>
                <a href="tel:+50689681451" class="bg-transparent border-2 border-white px-8 py-4 rounded-full text-lg font-semibold hover:bg-white hover:text-purple-600 transition transform hover:scale-105 flex items-center gap-2">
                    <span>Contáctanos</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Theme Toggle -->
    <div class="fixed bottom-6 right-6 z-50">
        <button onclick="toggleTheme()" class="p-3 rounded-full bg-white dark:bg-gray-800 shadow-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </button>
    </div>

    <!-- Modal de Contacto -->
    <style>
        .modal-overlay {
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
            pointer-events: none;
        }
        .modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }
        .modal-content {
            transform: translateY(20px);
            transition: transform 0.3s ease-out, opacity 0.3s ease-out;
            opacity: 0;
        }
        .modal-overlay.active .modal-content {
            transform: translateY(0);
            opacity: 1;
        }
    </style>
    <div id="contactModal" class="fixed inset-0 bg-black bg-opacity-0 flex items-center justify-center z-50 modal-overlay transition-all duration-300">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-8 max-w-md w-full mx-4 relative modal-content transition-all duration-300">
            <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Solicitar Información</h3>
            <form id="contactForm" class="space-y-4">
                <input type="hidden" id="packageName" name="packageName" value="">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre</label>
                    <input type="text" id="name" name="name" required
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Correo electrónico</label>
                    <input type="email" id="email" name="email" required
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Teléfono</label>
                    <input type="tel" id="phone" name="phone" required
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label for="business" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre del negocio</label>
                    <input type="text" id="business" name="business" required
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mensaje</label>
                    <textarea id="message" name="message" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:text-white"></textarea>
                </div>
                <div class="mt-6">
                    <a href="#contacto" class="inline-block bg-white text-purple-600 px-8 py-4 rounded-full text-lg font-semibold hover:bg-purple-100 transition transform hover:scale-105">
                        Comenzar Ahora
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Paquetes de Desarrollo Web -->
    <section class="py-20 bg-gradient-to-br from-gray-900 to-green-950 relative overflow-hidden">
        <div class="absolute inset-0 opacity-30">
            <div class="absolute top-20 left-10 w-72 h-72 bg-green-500/70 rounded-full mix-blend-multiply filter blur-xl animate-pulse"></div>
            <div class="absolute top-40 right-10 w-72 h-72 bg-red-500/70 rounded-full mix-blend-multiply filter blur-xl" style="animation-delay: 2s;"></div>
            <div class="absolute -bottom-8 left-1/2 w-72 h-72 bg-orange-500/70 rounded-full mix-blend-multiply filter blur-xl" style="animation-delay: 4s;"></div>
        </div>
        <div class="container mx-auto px-6 relative z-10">
            <h2 class="text-5xl font-bold text-center mb-4 bg-gradient-to-r from-green-400 via-orange-400 to-red-400 bg-clip-text text-transparent">Nuestros Paquetes de Desarrollo Web</h2>
            <p class="text-xl text-white/90 text-center mb-16 max-w-3xl mx-auto">Elige el paquete que mejor se adapte a las necesidades de tu negocio</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-7xl mx-auto items-start">
                <!-- Pack Boost -->
                <div class="bg-gradient-to-br from-emerald-900/80 to-emerald-800/50 backdrop-blur-sm rounded-xl border border-emerald-500/20 overflow-hidden flex flex-col hover:border-emerald-400/30 transition-all duration-300">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-white text-center mb-2">
                            <span class="bg-gradient-to-r from-emerald-300 to-teal-300 text-transparent bg-clip-text">Pack Boost</span>
                        </h3>
                        <div class="text-center mb-6">
                            <div class="text-5xl font-bold bg-gradient-to-r from-emerald-300 to-teal-300 bg-clip-text text-transparent">$50+</div>
                            <div class="text-xl text-emerald-100/80">ó ₡25,000+</div>
                            <div class="text-sm text-emerald-200/70 mt-1">Precio por proyecto</div>
                            <div class="text-sm text-emerald-200/70">Desde $50 (₡25,000) por 2 páginas</div>
                        </div>

                        <ul class="space-y-3 text-left mb-8">
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-emerald-400 mr-2 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Edición de 2 páginas web existentes (optimización de sitio ya en línea)</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-emerald-400 mr-2 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Actualización de contenido</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-emerald-400 mr-2 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Optimización de imágenes</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-emerald-400 mr-2 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Mejoras de diseño</span>
                            </li>
                       
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-emerald-400 mr-2 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Optimización para móviles</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-emerald-400 mr-2 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Revisión de rendimiento</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-emerald-400 mr-2 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-white">Página suplementaria: <span class="text-emerald-300">$10</span> <span class="text-emerald-200">(₡5,000 c/u)</span></span>
                            </li>

                            
                            <li class="text-sm text-emerald-100/60 mt-4">
                                * Precio base para 2 páginas. Cotización personalizada según requerimientos adicionales.
                            </li>
                        </ul>
                    </div>
                    <div class="mt-auto p-6 pt-0">
                        <a href="https://docs.google.com/forms/d/e/1FAIpQLSfD8awNX4xwDA7t-JnoH1bFvNVweH9rWzJNkF92I5O6aZq5rg/viewform?usp=publish-editor" target="_blank" rel="noopener noreferrer" class="relative overflow-hidden group block w-full bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-center font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg hover:shadow-emerald-500/30">
                            <span class="relative z-10 flex items-center justify-center">
                                <span>🛠️</span> Solicitar Cotización <span>🛠️</span>
                            </span>
                            <span class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-teal-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg"></span>
                            <span class="absolute -inset-1 bg-gradient-to-r from-emerald-400 to-teal-400 rounded-lg blur opacity-0 group-hover:opacity-30 transition-all duration-300"></span>
                        </a>
                    </div>
                </div>

                <!-- Web Start -->
                <div class="bg-gradient-to-br from-blue-900/80 to-blue-800/50 backdrop-blur-sm rounded-xl border border-blue-500/20 overflow-hidden flex flex-col hover:border-blue-400/30 transition-all duration-300">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-white text-center mb-2">
                            <span class="bg-gradient-to-r from-blue-300 to-cyan-300 text-transparent bg-clip-text">Web Start</span>
                        </h3>
                        <p class="text-blue-100/80 text-center mb-4">Perfecto para dar tus primeros pasos en línea</p>
                        <div class="text-center mb-6">
                            <div class="text-5xl font-bold bg-gradient-to-r from-blue-300 to-cyan-300 bg-clip-text text-transparent">$100</div>
                            <div class="text-xl text-blue-100/80">ó ₡50,000</div>
                            <div class="text-sm text-blue-200/70 mt-1">Anual</div>
                            <div class="text-sm text-blue-200/70">o $14/mes (₡7,000)</div>
                        </div>

                        <ul class="space-y-3 text-left mb-8">
                            <li class="flex items-center text-green-100/90">
                                <svg class="h-5 w-5 text-emerald-400 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                Página informativa básica
                            </li>
                            <li class="flex items-center">
                                <svg class="h-6 w-6 text-blue-400 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                Catálogo de productos
                            </li>
                      
                            <li class="flex items-center">
                                <svg class="h-6 w-6 text-blue-400 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                Mantenimiento incluido por 3 meses
                            </li>
                            <li class="flex items-center">
                                <svg class="h-6 w-6 text-blue-400 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                Una edición mensual incluida
                            </li>
                            <li class="flex items-center">
                                <svg class="h-6 w-6 text-blue-400 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                Enlace directo a WhatsApp para atención al cliente
                            </li>
                            <li class="flex items-center">
                                <svg class="h-6 w-6 text-blue-400 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                Gestión de dominio por 1 año
                            </li>
                            <li class="flex items-center">
                                <svg class="h-6 w-6 text-blue-400 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                Diseño 100% responsivo
                            </li>
                       
                            <li class="flex items-start mt-2">
                                <svg class="h-6 w-6 text-blue-400 mr-2 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-white">Página suplementaria: <span class="text-blue-300">$15</span> <span class="text-blue-200">(₡7,500 c/u)</span></span>
                            </li>
                            <li class="text-sm text-green-100/60 mt-2">
                                * El precio no incluye el costo del dominio
                            </li>
                        </ul>
                    </div>
                    <div class="mt-auto p-6 pt-0">
                        <a href="https://docs.google.com/forms/d/e/1FAIpQLSf72gJGIq00oZG_kxOKhlpXsMp45JsnYVQ67KHFfK14fXY44g/viewform?usp=publish-editor" target="_blank" rel="noopener noreferrer" class="relative overflow-hidden group block w-full bg-gradient-to-r from-blue-600 to-cyan-600 text-white text-center font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg hover:shadow-blue-500/30">
                            <span class="relative z-10 flex items-center justify-center">
                                <span>🚀</span> Comenzar Ahora <span>🚀</span>
                            </span>
                            <span class="absolute inset-0 bg-gradient-to-r from-blue-500 to-cyan-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg"></span>
                            <span class="absolute -inset-1 bg-gradient-to-r from-blue-400 to-cyan-400 rounded-lg blur opacity-0 group-hover:opacity-30 transition-all duration-300"></span>
                        </a>
                    </div>
                </div>
                
                <!-- Web Sol -->
                <div class="bg-gradient-to-br from-purple-900/80 to-purple-800/50 backdrop-blur-sm rounded-xl border border-purple-500/20 overflow-hidden flex flex-col hover:border-purple-400/30 transition-all duration-300 relative">
                    <div class="absolute top-4 right-4 bg-gradient-to-r from-purple-500 to-fuchsia-500 text-white text-xs font-semibold px-4 py-1.5 rounded-full shadow-lg z-10">
                        MÁS POPULAR
                    </div>
                    <div class="p-6 pt-16">
                        <h3 class="text-xl font-bold text-white text-center mb-2">
                            <span class="bg-gradient-to-r from-purple-300 to-fuchsia-300 text-transparent bg-clip-text">Web Sol</span>
                        </h3>
                        <p class="text-purple-100 text-center mb-4">Solución completa para negocios en crecimiento</p>
                        <div class="text-center mb-6">
                            <div class="text-5xl font-bold bg-gradient-to-r from-purple-300 to-fuchsia-300 bg-clip-text text-transparent">$150</div>
                            <div class="text-xl text-purple-100/80">ó ₡75,000</div>
                            <div class="text-sm text-purple-200/70 mt-1">Anual</div>
                            <div class="text-sm text-purple-200/70">o $20/mes (₡10,000)</div>
                        </div>

                        <ul class="space-y-4 text-left mb-8 text-white">
                      
                            <li class="flex items-center">
                                <svg class="h-6 w-6 text-blue-400 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                Mantenimiento incluido por 3 meses
                            </li>
                            <li class="flex items-center">
                                <svg class="h-6 w-6 text-blue-400 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                Sitio web de 5 páginas
                            </li>
                            <li class="flex items-center">
                                <svg class="h-6 w-6 text-blue-400 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                Página de productos con catálogo
                            </li>
                            <li class="flex items-center">
                                <svg class="h-6 w-6 text-blue-400 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                Carrito de compras integrado
                            </li>
                            <li class="flex items-center">
                                <svg class="h-6 w-6 text-blue-400 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                Email corporativo (2 buzones)
                            </li>
                            <li class="flex items-center">
                                <svg class="h-6 w-6 text-blue-400 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                Integración con redes sociales y WhatsApp
                            </li>
                            <li class="flex items-center">
                                <svg class="h-6 w-6 text-blue-400 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                Chatbot avanzado para preguntas frecuentes y gestión de ítems
                            </li>
                            <li class="flex items-center">
                                <svg class="h-6 w-6 text-blue-400 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                Diseño 100% responsivo
                            </li>

                            <li class="flex items-center">
    <svg class="h-6 w-6 text-amber-400 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
    </svg>
    Gestión de dominio por 1 año
</li>

                        
                            <li class="flex items-start mt-2">
                                <svg class="h-6 w-6 text-purple-400 mr-2 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-white">Página suplementaria: <span class="text-purple-300">$15</span> <span class="text-purple-200">(₡7,500 c/u)</span></span>
                            </li>
                            <li class="text-sm text-green-100/60 mt-2">
                                * El precio no incluye el costo del dominio
                            </li>
                        </ul>
                    </div>
                    <div class="mt-auto p-6 pt-0">
                        <a href="https://docs.google.com/forms/d/e/1FAIpQLSf72gJGIq00oZG_kxOKhlpXsMp45JsnYVQ67KHFfK14fXY44g/viewform?usp=publish-editor" target="_blank" rel="noopener noreferrer" class="relative overflow-hidden group block w-full bg-gradient-to-r from-purple-600 to-fuchsia-600 text-white text-center font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg hover:shadow-purple-500/30">
                            <span class="relative z-10 flex items-center justify-center">
                                <span>💎</span> Comenzar Ahora <span>💎</span>
                            </span>
                            <span class="absolute inset-0 bg-gradient-to-r from-purple-500 to-fuchsia-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg"></span>
                            <span class="absolute -inset-1 bg-gradient-to-r from-purple-400 to-fuchsia-400 rounded-lg blur opacity-0 group-hover:opacity-30 transition-all duration-300"></span>
                        </a>
                    </div>
                </div>

                <!-- Web Premium Completo -->
                <div class="bg-gradient-to-br from-amber-900/80 to-amber-800/50 backdrop-blur-sm rounded-xl border border-amber-500/20 overflow-hidden flex flex-col hover:border-amber-400/30 transition-all duration-300">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-white text-center mb-2">
                            <span class="bg-gradient-to-r from-amber-300 to-orange-300 text-transparent bg-clip-text">Web Premium</span>
                        </h3>
                        <p class="text-amber-100/80 text-center mb-4">Solución completa para negocios establecidos</p>
                        <div class="text-center mb-6">
                            <div class="text-4xl font-bold bg-gradient-to-r from-amber-300 to-orange-300 bg-clip-text text-transparent">A partir de $300</div>
                            <div class="text-lg text-amber-100/80">ó desde ₡150,000</div>
                            <div class="text-sm text-amber-200/70 mt-1">Precio varía según alcance</div>
                            <div class="text-sm text-amber-200/70">o $38/mes (₡19,000)</div>
                        </div>

                        <ul class="space-y-3 text-left mb-8 text-white">
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-amber-400 mr-2 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Mantenimiento incluido por 3 meses</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-amber-400 mr-2 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Sitio web de 6 páginas</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-amber-400 mr-2 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Página de productos con catálogo</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-amber-400 mr-2 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Carrito de compras integrado</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-amber-400 mr-2 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Email corporativo (2 buzones)</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-amber-400 mr-2 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Integración con redes sociales y WhatsApp</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-amber-400 mr-2 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Chatbot avanzado con IA para toma de pedidos</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-amber-400 mr-2 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Diseño 100% responsivo</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-amber-400 mr-2 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Panel de control administrativo</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-amber-400 mr-2 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Panel de edición y creación de productos</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-amber-400 mr-2 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Reproductor de música y video integrado</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-amber-400 mr-2 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Asistencia prioritaria 24/7</span>
                            </li>

                            <li class="flex items-center">
    <svg class="h-6 w-6 text-amber-400 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
    </svg>
    Gestión de dominio por 1 año
</li>

                       
                            <li class="flex items-start mt-2">
                                <svg class="h-6 w-6 text-amber-400 mr-2 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-white">Página suplementaria: <span class="text-amber-300">$20</span> <span class="text-amber-200">(₡10,000 c/u)</span></span>
                            </li>
                            <li class="text-sm text-amber-100/60 mt-2 pl-2">
                                * El precio no incluye el costo del dominio
                            </li>
                            <li class="mt-4 pt-4 border-t border-amber-500/20">
                                <div class="flex items-start">
                            
                                    <div>
                                   
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="mt-auto p-6 pt-0">
                        <a href="https://docs.google.com/forms/d/e/1FAIpQLSf72gJGIq00oZG_kxOKhlpXsMp45JsnYVQ67KHFfK14fXY44g/viewform?usp=publish-editor" target="_blank" rel="noopener noreferrer" class="relative overflow-hidden group block w-full bg-gradient-to-r from-amber-600 to-orange-600 text-white text-center font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg hover:shadow-amber-500/30">
                            <span class="relative z-10 flex items-center justify-center">
                                <span>🏆</span> Comenzar Ahora <span>🏆</span>
                            </span>
                        </a>
                    </div>
                </div>
            
            
        </div>
        <div class="text-center mt-12">
                <p class="text-gray-600 dark:text-gray-300">¿Necesitas algo personalizado? <a href="mailto:info@websolutions.work" class="text-purple-600 dark:text-purple-400 font-semibold hover:underline">Contáctanos</a> para un presupuesto a tu medida.</p>
                <div class="mt-6 flex justify-center space-x-4">
                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                        <svg class="h-5 w-5 text-green-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Pago único sin sorpresas
                    </div>
                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                        <svg class="h-5 w-5 text-green-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Soporte 24/7
                    </div>
                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                        <svg class="h-5 w-5 text-green-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Garantía de satisfacción
                    </div>
                </div>
            </div>
            
    </section>

    <!-- Hero Banner Section -->
    <section class="relative py-20 overflow-hidden" style="background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 50%, #A855F7 100%);">
        <div class="absolute inset-0 opacity-20">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1516321497487-e288fb19713f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80')] bg-cover bg-center"></div>
        </div>
        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Impulsa tu Negocio en Línea Hoy Mismo</h2>
                <p class="text-xl text-white/90 mb-10 max-w-3xl mx-auto">Obtén una presencia web profesional que convierta visitantes en clientes. Nuestros paquetes incluyen todo lo que necesitas para destacar en internet.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 max-w-6xl mx-auto">
                    <div class="bg-white/10 backdrop-blur-sm p-4 rounded-lg border border-white/20 hover:bg-white/20 transition-colors">
                        <div class="text-center mb-2">
                            <i class="fas fa-ad text-2xl text-purple-300 mb-2"></i>
                            <h3 class="font-bold text-white">Publicidad Diaria</h3>
                        </div>
                        <p class="text-white/80 text-sm text-center">Campañas efectivas que llegan a tu audiencia todos los días</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/20 hover:bg-white/20 transition-all duration-300 transform hover:scale-105 group">
                        <div class="text-center mb-3">
                            <div class="relative inline-block">
                                <i class="fas fa-cubes text-2xl text-purple-300 group-hover:scale-110 transition-transform duration-300"></i>
                            </div>
                            <h3 class="font-bold text-white mt-2">Diseño de Marca</h3>
                        </div>
                        <p class="text-white/80 text-sm text-center">Diseño de logo profesional y paquetes personalizados que reflejen la identidad de tu marca</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/20 hover:bg-white/20 transition-all duration-300 transform hover:scale-105 group">
                        <div class="text-center mb-2">
                            <i class="fas fa-bullhorn text-2xl text-purple-300 mb-2"></i>
                            <h3 class="font-bold text-white">Flyers</h3>
                        </div>
                        <p class="text-white/80 text-sm text-center">Diseño de flyers profesionales para promociones y eventos</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/20 hover:bg-white/20 transition-all duration-300 transform hover:scale-105 group">
                        <div class="text-center mb-2">
                            <i class="fas fa-chart-line text-2xl text-purple-300 mb-2"></i>
                            <h3 class="font-bold text-white">Análisis de Mercado</h3>
                        </div>
                        <p class="text-white/80 text-sm text-center">Estudios de mercado para optimizar tus estrategias de marketing</p>
                    </div>
                </div>
                         
                            </ul>
                            </div>
               
                        </div>


                    </div>
                </div>

                <!-- Marketing Packages Section -->
                <div class="mt-16 py-12 bg-gradient-to-br from-gray-900 to-green-950 relative overflow-hidden">
                    <div class="absolute inset-0 opacity-30">
                        <div class="absolute top-20 left-10 w-72 h-72 bg-green-500/70 rounded-full mix-blend-multiply filter blur-xl animate-pulse"></div>
                        <div class="absolute top-40 right-10 w-72 h-72 bg-red-500/70 rounded-full mix-blend-multiply filter blur-xl" style="animation-delay: 2s;"></div>
                        <div class="absolute -bottom-8 left-1/2 w-72 h-72 bg-orange-500/70 rounded-full mix-blend-multiply filter blur-xl" style="animation-delay: 4s;"></div>
                    </div>
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                        <div class="text-center mb-12">
                            <h2 class="text-4xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-green-400 via-orange-400 to-red-400 sm:text-5xl">Paquetes de Marketing</h2>
                            <p class="mt-4 text-lg text-white/90 max-w-3xl mx-auto">Soluciones a medida para potenciar tu presencia digital</p>
                        </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                        <!-- Pack Mini -->
                        <div class="bg-gradient-to-br from-green-900/80 to-green-800/50 backdrop-blur-sm rounded-xl border border-green-500/20 overflow-hidden flex flex-col h-full hover:border-green-400/30 transition-all duration-300">
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-white text-center mb-2">
                                    <span class="bg-gradient-to-r from-green-300 to-emerald-300 text-transparent bg-clip-text">Start Pub</span>
                                </h3>
                                <div class="text-center mb-6">
                                    <div class="text-5xl font-bold bg-gradient-to-r from-green-300 to-emerald-300 bg-clip-text text-transparent">$50</div>
                                    <div class="text-xl text-green-100/80">ó ₡25,000</div>
                                    <div class="text-sm text-green-200/70 mt-1">Mensual</div>
                                    <div class="text-sm text-green-200/70">ó $12.50 (₡6,250) una vez por semana</div>
                                </div>
                                <ul class="space-y-3 text-gray-300 text-base">
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-green-400 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>Creación o mejora de 2 redes sociales</span>
                                    </li>
                                    

                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-green-400 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>Informe mensual básico</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="mt-auto p-6 pt-0">
                                <a href="https://docs.google.com/forms/d/e/1FAIpQLSdsWk9lIfWzssTZ_x8YPbGMU3-2Zhm6TVrAplkdVccBQ4SyoA/viewform?usp=publish-editor" target="_blank" rel="noopener noreferrer" class="relative overflow-hidden group block w-full bg-gradient-to-r from-pink-600 via-orange-500 to-pink-600 text-white text-center font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg hover:shadow-pink-500/30">
                                    <span class="relative z-10 flex items-center justify-center">
                                        <span class="mr-2">🔥</span> Contratar Ahora <span class="ml-2">🔥</span>
                                    </span>
                                    <span class="absolute inset-0 bg-gradient-to-r from-pink-500 to-orange-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg"></span>
                                    <span class="absolute -inset-1 bg-gradient-to-r from-pink-400 to-orange-400 rounded-lg blur opacity-0 group-hover:opacity-50 transition-all duration-300"></span>
                                </a>
                            </div>
                        </div>

                        <!-- Pop Pub -->
                        <div class="bg-gradient-to-br from-orange-900/80 to-amber-800/50 backdrop-blur-sm rounded-xl border border-orange-500/30 overflow-hidden flex flex-col h-full transform hover:scale-[1.02] transition-all duration-300 relative">
                            <div class="absolute top-4 right-4 bg-gradient-to-r from-orange-500 to-amber-500 text-white text-xs font-semibold px-4 py-1.5 rounded-full shadow-lg z-10">
                                MÁS POPULAR
                            </div>
                            <div class="p-6 pt-16">
                                <h3 class="text-xl font-bold text-white text-center mb-2">
                                    <span class="bg-gradient-to-r from-orange-300 to-amber-300 text-transparent bg-clip-text">Pop Pub</span>
                                </h3>
                                <div class="text-center mb-6">
                                    <div class="text-5xl font-bold bg-gradient-to-r from-orange-300 to-amber-300 bg-clip-text text-transparent">$150</div>
                                    <div class="text-xl text-orange-100/80">ó ₡75,000</div>
                                    <div class="text-sm text-orange-200/70 mt-1">Mensual</div>
                                    <div class="text-sm text-orange-200/70">ó $37.50 (₡18,750) una vez por semana</div>
                                </div>
                                <ul class="space-y-3 text-gray-300 text-base">
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-green-400 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>Gestión de perfiles en 3 redes sociales de tu preferencia</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-green-400 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>Briefing de marca (identidad, tono, colores)</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-green-400 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>Minicalendario mensual (plan de 2 semanas a 1 mes)</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-green-400 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>8 a 10 publicaciones al mes</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-green-400 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>8 a 10 historias breves</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-green-400 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>Redacción publicitaria</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-green-400 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>Planificación de contenido</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-green-400 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>Reporte cada fin de mes</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-green-400 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>Reporte cada fin de semana*</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="mt-auto p-6 pt-0">
                                <a href="https://docs.google.com/forms/d/e/1FAIpQLSdsWk9lIfWzssTZ_x8YPbGMU3-2Zhm6TVrAplkdVccBQ4SyoA/viewform?usp=publish-editor" target="_blank" rel="noopener noreferrer" class="relative overflow-hidden group block w-full bg-gradient-to-r from-pink-600 via-orange-500 to-pink-600 text-white text-center font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg hover:shadow-pink-500/30">
                                    <span class="relative z-10 flex items-center justify-center">
                                        <span class="mr-2">🔥</span> Contratar Ahora <span class="ml-2">🔥</span>
                                    </span>
                                    <span class="absolute inset-0 bg-gradient-to-r from-pink-500 to-orange-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg"></span>
                                    <span class="absolute -inset-1 bg-gradient-to-r from-pink-400 to-orange-400 rounded-lg blur opacity-0 group-hover:opacity-50 transition-all duration-300"></span>
                                </a>
                            </div>
                        </div>

                        <!-- Full Pub -->
                        <div class="bg-gradient-to-br from-red-900/80 to-red-800/50 backdrop-blur-sm rounded-xl border border-red-500/20 overflow-hidden flex flex-col h-full hover:border-red-400/30 transition-all duration-300">
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-white text-center mb-2">
                                    <span class="bg-gradient-to-r from-red-300 to-pink-300 text-transparent bg-clip-text">Full Pub</span>
                                </h3>
                                <div class="text-center mb-6">
                                    <div class="text-4xl font-bold bg-gradient-to-r from-red-300 to-pink-300 bg-clip-text text-transparent">A partir de $250</div>
                                    <div class="text-lg text-red-100/80">ó desde ₡125,000</div>
                                    <div class="text-sm text-red-200/70 mt-1">Precio mensual, varía según alcance</div>
                                </div>
                                <ul class="space-y-3 text-gray-300 text-base">
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-green-400 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>Calendario editorial mensual completo</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-green-400 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>Programación estratégica de contenido</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-green-400 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>Redacción de textos persuasivos</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-green-400 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>30 Historias mensuales</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-green-400 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>6 Publicaciones en el feed mensuales</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-green-400 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>2 Reels profesionales con edición de video</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-green-400 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>Informes semanales y mensuales detallados</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-green-400 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>Gestión de 3 redes sociales a elección</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-green-400 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>Consultoría estratégica personalizada con informe mensual</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="mt-auto p-6 pt-0">
                                <a href="https://docs.google.com/forms/d/e/1FAIpQLSdsWk9lIfWzssTZ_x8YPbGMU3-2Zhm6TVrAplkdVccBQ4SyoA/viewform?usp=publish-editor" target="_blank" rel="noopener noreferrer" class="relative overflow-hidden group block w-full bg-gradient-to-r from-pink-600 via-orange-500 to-pink-600 text-white text-center font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg hover:shadow-pink-500/30">
                                    <span class="relative z-10 flex items-center justify-center">
                                        <span class="mr-2">🔥</span> Contratar Ahora <span class="ml-2">🔥</span>
                                    </span>
                                    <span class="absolute inset-0 bg-gradient-to-r from-pink-500 to-orange-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg"></span>
                                    <span class="absolute -inset-1 bg-gradient-to-r from-pink-400 to-orange-400 rounded-lg blur opacity-0 group-hover:opacity-50 transition-all duration-300"></span>
                                </a>
                            </div>
                        </div>


                    </div>
                </div>
                </div>

                <div class="mt-12 flex flex-col sm:flex-row justify-center gap-4">
                    <a href="mailto:info@websolutions.work" class="mx-auto sm:mx-0 bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-400 hover:to-purple-500 text-white font-semibold py-2 sm:py-3 px-6 sm:px-8 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-pink-500/30 text-sm sm:text-base">
                        Contáctanos Ahora
                    </a>
                </div>
            </div>
            
            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mt-16 max-w-5xl mx-auto">
                <div class="text-center">
                    <div class="text-4xl font-bold text-white mb-2">+100</div>
                    <div class="text-white/80">Clientes Satisfechos</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-white mb-2">24/7</div>
                    <div class="text-white/80">Soporte Técnico</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-white mb-2">99.9%</div>
                    <div class="text-white/80">Tiempo Activo</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-white mb-2">+50</div>
                    <div class="text-white/80">Campañas de Marketing</div>
                </div>
            </div>
        </div>
        
        <!-- Wave Divider -->
        <div class="absolute bottom-0 left-0 w-full overflow-hidden">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="fill-current text-white"></path>
            </svg>
        </div>
    </section>

    <!-- Partners Section -->
    <section class="py-16 bg-gray-900">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-4 bg-gradient-to-r from-purple-400 to-blue-400 bg-clip-text text-transparent">
                Nuestros Partners
            </h2>
            <p class="text-center text-gray-400 mb-12 max-w-2xl mx-auto">
                Colaboramos con las mejores tecnologías y plataformas para ofrecerte soluciones de vanguardia.
            </p>
            
            <div class="flex flex-nowrap justify-start sm:justify-center items-center gap-2 sm:gap-4 md:gap-6 lg:gap-8 py-4 overflow-x-auto w-full px-2 sm:px-0">
                <!-- WordPress -->
                <div class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 lg:w-28 lg:h-28 bg-white rounded-xl shadow-lg transform transition duration-300 hover:scale-105 flex items-center justify-center p-2 sm:p-3">
                    <img src="https://www.kindpng.com/picc/m/137-1372425_wordpress-blue-logo-png-transparent-png.png" alt="WordPress" class="w-full h-full object-contain transition duration-300">
                </div>
                
                <!-- Hostinger -->
                <div class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 lg:w-28 lg:h-28 bg-white rounded-xl shadow-lg transform transition duration-300 hover:scale-105 flex items-center justify-center p-2 sm:p-3">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/e/e5/Hostinger_Logotype.png" alt="Hostinger" class="w-full h-full object-contain transition duration-300">
                </div>
                
<!-- Laravel -->
                                <div class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 lg:w-28 lg:h-28 bg-amber-500 rounded-xl shadow-lg shadow-amber-500/30 transform transition duration-300 hover:scale-105 hover:shadow-amber-500/50 flex items-center justify-center p-2 sm:p-3">
                                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTPNom4M_g0NKR31DzLdISnPUw0k-IxxsDjFkv-BoDE6u2hOjzBBZb827sxbB0YNKFwQP8&usqp=CAU" alt="Laravel" class="w-full h-full object-contain transition duration-300 brightness-0 invert">
                                </div>
                
                <!-- Filament -->
                <div class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 lg:w-28 lg:h-28 bg-white rounded-xl shadow-lg transform transition duration-300 hover:scale-105 flex items-center justify-center p-2 sm:p-3">
                    <img src="https://shop.filamentphp.com/cdn/shop/products/kiss-cut-stickers-5.5x5.5-default-6388cb9b4f472.jpg?v=1669909410&width=1445" alt="Filament" class="w-full h-full object-contain transition duration-300">
                </div>
                
                <!-- Shopify -->
                <div class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 lg:w-28 lg:h-28 bg-white rounded-xl shadow-lg transform transition duration-300 hover:scale-105 flex items-center justify-center p-2 sm:p-3">
                    <img src="https://pluspng.com/img-png/shopify-logo-png-shopify-logo-1-300.png" alt="Shopify" class="w-full h-full object-contain transition duration-300">
                </div>
                
                <!-- VS Code -->
                <div class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 lg:w-28 lg:h-28 bg-white rounded-xl shadow-lg transform transition duration-300 hover:scale-105 flex items-center justify-center p-2 sm:p-3">
                    <img src="https://w7.pngwing.com/pngs/284/106/png-transparent-visual-studio-code-logo.png" alt="VS Code" class="w-full h-full object-contain transition duration-300">
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-4 text-center">
            <div class="flex flex-col md:flex-row justify-between items-center max-w-6xl mx-auto">
                <div class="mb-6 md:mb-0 text-center md:text-left">
                    <div class="flex items-center justify-center md:justify-start">
                        <img src="https://cdn-icons-png.flaticon.com/512/2115/2115955.png" alt="Logo" class="h-8 w-8 mr-2">
                        <span class="text-xl font-bold bg-gradient-to-r from-blue-400 to-purple-500 bg-clip-text text-transparent">Web Solutions CR</span>
                    </div>
                    <p class="mt-2 text-gray-400 text-sm">Soluciones tecnológicas a tu medida</p>
                </div>
                
                <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-8">
                    <div class="text-center md:text-left">
                        <h3 class="text-sm font-semibold text-gray-300 tracking-wider uppercase">Contacto</h3>
                        <div class="mt-4 space-y-1">
                            <p class="text-gray-400 text-sm">info@websolutions.work</p>
                            <p class="text-gray-400 text-sm">+506 8806 1829</p>
                        </div>
                    </div>
                    
                    <div class="text-center md:text-left">
                        <h3 class="text-sm font-semibold text-gray-300 tracking-wider uppercase">Legal</h3>
                        <div class="mt-4 space-y-1">
                            <a href="{{ route('privacy') }}" class="text-gray-400 hover:text-white text-sm block">Política de Privacidad</a>
                            <a href="{{ route('terms') }}" class="text-gray-400 hover:text-white text-sm block">Términos y Condiciones</a>
                            <a href="{{ route('data-deletion.show') }}" class="text-gray-400 hover:text-white text-sm block">Eliminar mis datos</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-12 pt-8 border-t border-gray-800">
                <p class="text-gray-400 text-sm">
                    &copy; {{ date('Y') }} Web Solutions CR. Todos los derechos reservados.
                </p>
                <p class="mt-2 text-gray-500 text-xs">
                    Desarrollado con <i class="fas fa-heart text-red-500"></i> en Costa Rica
                </p>
            </div>
        </div>
    </footer>

    <!-- Botones flotantes -->
    <div class="fixed right-6 bottom-6 z-50 flex flex-col gap-4">
        <!-- Botón de Música -->
        <button class="music-btn" id="playPauseBtn" title="Reproducir música" aria-label="Reproducir música de fondo">
            <i class="fas fa-headphones" id="playIcon"></i>
        </button>
        
        <!-- Botón de WhatsApp -->
        <a href="https://wa.me/50688061829" target="_blank" class="w-14 h-14 bg-green-500 hover:bg-green-600 text-white rounded-full flex items-center justify-center shadow-lg transition-all transform hover:scale-110" aria-label="Chatear por WhatsApp">
            <i class="fab fa-whatsapp text-2xl"></i>
        </a>
        
        <!-- Botón de Chat -->
        <button id="chatbotButton" class="w-14 h-14 bg-blue-500 hover:bg-blue-600 text-white rounded-full flex items-center justify-center shadow-lg transition-all transform hover:scale-110" aria-label="Abrir chat">
            <i class="fas fa-comment-dots text-2xl"></i>
        </button>
    </div>

    <!-- Ventana de Chat (inicialmente oculta) -->
    <div id="chatWindow" class="fixed right-6 bottom-24 w-80 bg-white dark:bg-gray-800 rounded-t-xl shadow-xl z-40 hidden flex-col border border-gray-200 dark:border-gray-700">
        <!-- Encabezado del Chat -->
        <div class="bg-blue-500 text-white px-4 py-3 rounded-t-xl flex justify-between items-center">
            <div class="flex items-center">
                <i class="fas fa-robot mr-2"></i>
                <span class="font-semibold">Asistente Virtual</span>
            </div>
            <button id="closeChat" class="text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <!-- Cuerpo del Chat -->
        <div class="flex-1 p-4 overflow-y-auto h-80">
            <div class="mb-4">
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-3 max-w-[80%] inline-block">
                    <p class="text-sm text-gray-800 dark:text-gray-200">¡Hola! Soy tu asistente virtual. ¿En qué puedo ayudarte hoy?</p>
                </div>
            </div>
        </div>
        
        <!-- Entrada de Mensaje -->
        <div class="p-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-750">
            <div class="flex items-center">
                <input type="text" placeholder="Escribe tu mensaje..." class="flex-1 border border-gray-300 dark:border-gray-600 rounded-l-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-r-lg">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 text-center">Presiona Enter para enviar</p>
        </div>
    </div>

    <!-- Script para el tema oscuro y funcionalidad del chat -->
    <script>
        // Funciones para el modal de contacto
        function openModal(packageName) {
            const modal = document.getElementById('contactModal');
            document.getElementById('packageName').value = packageName;
            
            // Mostrar el modal antes de la animación
            modal.style.display = 'flex';
            
            // Forzar reflow para asegurar que el navegador registre el cambio de display
            void modal.offsetWidth;
            
            // Iniciar animación
            setTimeout(() => {
                modal.classList.add('active');
                // Bloquear el scroll del body
                document.body.style.overflow = 'hidden';
            }, 10);
            
            // Enfocar el primer campo del formulario
            setTimeout(() => {
                document.getElementById('name')?.focus();
            }, 350);
        }

        function closeModal() {
            const modal = document.getElementById('contactModal');
            modal.classList.remove('active');
            
            // Esperar a que termine la animación antes de ocultar
            setTimeout(() => {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }, 300);
        }

        // Cerrar modal al hacer clic fuera del contenido
        window.onclick = function(event) {
            const modal = document.getElementById('contactModal');
            if (event.target === modal) {
                closeModal();
            }
        }

        // Manejar el envío del formulario
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Aquí puedes agregar la lógica para enviar el formulario
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            console.log('Datos del formulario:', data);
            
            // Cerrar el modal después de enviar
            closeModal();
            
            // Mostrar mensaje de éxito
            alert('¡Gracias por tu interés! Nos pondremos en contacto contigo pronto.');
        });

        // Tema oscuro/claro
        function toggleTheme() {
            const html = document.documentElement;
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
        }

        // Aplicar tema guardado al cargar
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        // Funcionalidad del chat
        document.addEventListener('DOMContentLoaded', function() {
            const chatButton = document.getElementById('chatbotButton');
            const chatWindow = document.getElementById('chatWindow');
            const closeChat = document.getElementById('closeChat');
            
            // Función para mostrar el chat
            function showChat() {
                chatWindow.classList.remove('hidden');
                chatWindow.classList.add('flex');
                
                // No volver a mostrar el mensaje automático si ya se cerró manualmente
                localStorage.setItem('chatShown', 'true');
            }
            
            // Mostrar automáticamente después de 5 segundos si es la primera vez
            if (!localStorage.getItem('chatShown')) {
                setTimeout(showChat, 5000);
            }
            
            // Alternar visibilidad del chat al hacer clic en el botón
            chatButton.addEventListener('click', function() {
                chatWindow.classList.toggle('hidden');
                chatWindow.classList.toggle('flex');
                
                // Marcar como visto si el usuario interactúa manualmente
                if (!chatWindow.classList.contains('hidden')) {
                    localStorage.setItem('chatShown', 'true');
                }
            });
            
            // Cerrar el chat
            closeChat.addEventListener('click', function() {
                chatWindow.classList.add('hidden');
                chatWindow.classList.remove('flex');
            });
        });

        const musicButton = document.getElementById('musicButton');
        const musicIcon = document.getElementById('musicIcon');
        const bgMusic = document.getElementById('bgMusic');
        let isPlaying = false;

        musicButton.addEventListener('click', () => {
            if (isPlaying) {
                bgMusic.pause();
                musicIcon.classList.remove('fa-volume-up');
                musicIcon.classList.add('fa-music');
                musicButton.classList.remove('bg-green-500', 'hover:bg-green-600');
                musicButton.classList.add('bg-purple-600', 'hover:bg-purple-700');
            } else {
                bgMusic.play().catch(e => console.log("La reproducción automática fue prevenida:", e));
                musicIcon.classList.remove('fa-music');
                musicIcon.classList.add('fa-volume-up');
                musicButton.classList.remove('bg-purple-600', 'hover:bg-purple-700');
                musicButton.classList.add('bg-green-500', 'hover:bg-green-600');
            }
            isPlaying = !isPlaying;
        });
    </script>
    <!-- Background Music -->
    <audio id="bgMusic" loop>
        <source src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3" type="audio/mpeg">
        Tu navegador no soporta el elemento de audio.
    </audio>

    <script>
        // Simple Music Player Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const audio = document.getElementById('bgMusic');
            const playPauseBtn = document.getElementById('playPauseBtn');
            const playIcon = document.getElementById('playIcon');
            
            // Set initial volume
            audio.volume = 0.5;
            
            // Play/Pause functionality
            playPauseBtn.addEventListener('click', function() {
                if (audio.paused) {
                    audio.play();
                    playIcon.classList.remove('fa-headphones');
                    playIcon.classList.add('fa-pause');
                    playPauseBtn.style.background = 'rgba(102, 126, 234, 0.9)';
                } else {
                    audio.pause();
                    playIcon.classList.remove('fa-pause');
                    playIcon.classList.add('fa-headphones');
                    playPauseBtn.style.background = 'rgba(0, 0, 0, 0.7)';
                }
            });
            
            // Add visual feedback on hover
            playPauseBtn.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.1)';
            });
            
            playPauseBtn.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>
