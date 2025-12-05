<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footwear etc. San Jose - Willow Glen | Tienda de Calzado</title>
    <meta name="description" content="Visita nuestra tienda en Willow Glen, San Jose. Calzado de calidad, servicio excepcional y 39 años de experiencia. Encuentra las mejores marcas como Hoka, Birkenstock, Brooks y más.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            scroll-behavior: smooth;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        
        .animate-fade-in {
            animation: fadeIn 1s ease-out forwards;
        }
        
        .animate-slide-in-left {
            animation: slideInLeft 0.8s ease-out forwards;
        }
        
        .animate-slide-in-right {
            animation: slideInRight 0.8s ease-out forwards;
        }
        
        .opacity-0 {
            opacity: 0;
        }
        
        .transition-all-smooth {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .hover-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .gradient-overlay {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.95) 0%, rgba(29, 78, 216, 0.95) 100%);
        }
        
        .backdrop-blur-smooth {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        .card-hover {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-hover:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .text-gradient {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .section-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(0,0,0,0.1), transparent);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-smooth shadow-md sticky top-0 z-50 transition-all-smooth animate-fade-in">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center opacity-0 animate-fade-in" style="animation-delay: 0.2s;">
                    <img src="https://footwearetc.com/cdn/shop/files/Footwear_etc._logo_440x.png?v=1760562269" alt="Footwear etc." class="h-10 transition-all-smooth hover:scale-110">
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="#" class="text-gray-700 hover:text-gray-900 transition-all-smooth relative group">
                        Productos
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all-smooth group-hover:w-full"></span>
                    </a>
                    <a href="#" class="text-gray-700 hover:text-gray-900 transition-all-smooth relative group">
                        Marcas
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all-smooth group-hover:w-full"></span>
                    </a>
                    <a href="#" class="text-gray-700 hover:text-gray-900 transition-all-smooth relative group">
                        Ofertas
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all-smooth group-hover:w-full"></span>
                    </a>
                    <a href="#" class="text-gray-700 hover:text-gray-900 transition-all-smooth relative group">
                        Contacto
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all-smooth group-hover:w-full"></span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="text-gray-700 hover:text-gray-900 transition-all-smooth hover:scale-110">
                        <i class="fas fa-search text-xl"></i>
                    </button>
                    <button class="text-gray-700 hover:text-gray-900 transition-all-smooth hover:scale-110">
                        <i class="fas fa-shopping-cart text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20 overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center opacity-20 transition-all-smooth animate-fade-in" style="background-image: url('https://cdn.shopify.com/s/files/1/0012/5332/3286/files/footwear-etc-store-exterior.jpg'); background-position: center; background-size: cover;"></div>
        <div class="absolute inset-0 gradient-overlay"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="opacity-0 animate-slide-in-left">
                    <h2 class="text-5xl font-bold mb-6 leading-tight">
                        San Jose Shoe Store<br>
                        <span class="text-blue-200">Footwear etc. San Jose</span><br>
                        <span class="text-blue-200 text-3xl">Downtown Willow Glen</span>
                    </h2>
                    <p class="text-xl mb-8 text-blue-100 leading-relaxed">
                        Más de 39 años ofreciendo calzado de calidad que combina estilo y comodidad. 
                        Tu destino definitivo para encontrar el par perfecto.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="#ubicacion" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition-all-smooth shadow-lg hover-lift group">
                            <i class="fas fa-map-marker-alt mr-2 group-hover:scale-110 transition-all-smooth"></i>Ver Ubicación
                        </a>
                        <a href="#horarios" class="bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-600 transition-all-smooth hover-lift group">
                            <i class="fas fa-clock mr-2 group-hover:scale-110 transition-all-smooth"></i>Horarios
                        </a>
                    </div>
                </div>
                <div class="hidden md:block opacity-0 animate-slide-in-right" style="animation-delay: 0.3s;">
                    <div class="bg-white/10 backdrop-blur-smooth rounded-2xl p-8 border border-white/20 transition-all-smooth hover:bg-white/15 card-hover">
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3 transition-all-smooth hover:translate-x-2">
                                <i class="fas fa-store text-3xl text-blue-200 transition-all-smooth"></i>
                                <div>
                                    <p class="font-semibold text-lg">Ubicación</p>
                                    <p class="text-blue-100">Willow Glen, San Jose</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3 transition-all-smooth hover:translate-x-2">
                                <i class="fas fa-clock text-3xl text-blue-200 transition-all-smooth"></i>
                                <div>
                                    <p class="font-semibold text-lg">Horarios Extendidos</p>
                                    <p class="text-blue-100">Abierto más tiempo que otras tiendas</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3 transition-all-smooth hover:translate-x-2">
                                <i class="fas fa-star text-3xl text-blue-200 transition-all-smooth"></i>
                                <div>
                                    <p class="font-semibold text-lg">39 Años de Experiencia</p>
                                    <p class="text-blue-100">Servicio de calidad comprobado</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Location Info -->
    <section id="ubicacion" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12">
                <!-- Address Card -->
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-8 shadow-lg opacity-0 animate-fade-in-up card-hover">
                    <h3 class="text-3xl font-bold text-gray-900 mb-6">
                        <i class="fas fa-map-marker-alt text-blue-600 mr-3 transition-all-smooth hover:scale-125"></i>
                        Nuestra Ubicación
                    </h3>
                    <div class="space-y-4 text-gray-700">
                        <p class="text-lg font-semibold">Footwear etc. Willow Glen</p>
                        <p class="flex items-start transition-all-smooth hover:translate-x-2">
                            <i class="fas fa-location-dot text-blue-600 mr-3 mt-1 transition-all-smooth"></i>
                            <span>Ubicado en el corazón de Willow Glen, San Jose</span>
                        </p>
                        <p class="flex items-start transition-all-smooth hover:translate-x-2">
                            <i class="fas fa-utensils text-blue-600 mr-3 mt-1 transition-all-smooth"></i>
                            <span>Cerca de Sushi Arashi y Round Table Pizza</span>
                        </p>
                        <p class="flex items-start transition-all-smooth hover:translate-x-2">
                            <i class="fas fa-tree text-blue-600 mr-3 mt-1 transition-all-smooth"></i>
                            <span>Calles arboladas y ambiente relajado de Willow Glen</span>
                        </p>
                    </div>
                </div>

                <!-- Google Maps -->
                <div class="bg-gray-200 rounded-2xl overflow-hidden shadow-lg opacity-0 animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="transition-all-smooth hover:scale-[1.02]">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3172.234567890123!2d-121.90234567890123!3d37.30456789012345!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzfCsDE4JzE2LjQiTiAxMjHCsDU0JzA4LjQiVw!5e0!3m2!1sen!2sus!4v1234567890123!5m2!1sen!2sus" 
                            width="100%" 
                            height="400" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            class="w-full h-full min-h-[400px] transition-all-smooth">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Store Features -->
    <section class="py-16 bg-gradient-to-b from-white to-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">En Esta Tienda Encontrarás</h2>
                <p class="text-xl text-gray-600">Todo lo que necesitas para el cuidado de tus pies</p>
            </div>
            
            <!-- Store Interior Photo -->
            <div class="mb-12 rounded-2xl overflow-hidden shadow-xl opacity-0 animate-fade-in-up group">
                <div class="overflow-hidden rounded-2xl">
                    <img 
                        src="https://cdn.shopify.com/s/files/1/0012/5332/3286/files/footwear-etc-store-interior.jpg" 
                        alt="Interior de Footwear etc. San Jose" 
                        class="w-full h-auto object-cover transition-all-smooth group-hover:scale-110"
                        onerror="this.src='https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=1200&h=600&fit=crop'; this.alt='Interior de tienda de calzado';">
                </div>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-all-smooth card-hover opacity-0 animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mb-4 group-hover:bg-blue-200 transition-all-smooth group-hover:scale-110">
                        <i class="fas fa-shoe-prints text-2xl text-blue-600 transition-all-smooth"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Sandalias</h3>
                    <p class="text-gray-600">Amplia selección de sandalias para hombre y mujer</p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-all-smooth card-hover opacity-0 animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mb-4 group-hover:bg-green-200 transition-all-smooth group-hover:scale-110">
                        <i class="fas fa-heartbeat text-2xl text-green-600 transition-all-smooth"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Calzado de Salud</h3>
                    <p class="text-gray-600">Calzado especializado para bienestar y salud</p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-all-smooth card-hover opacity-0 animate-fade-in-up" style="animation-delay: 0.3s;">
                    <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mb-4 group-hover:bg-purple-200 transition-all-smooth group-hover:scale-110">
                        <i class="fas fa-running text-2xl text-purple-600 transition-all-smooth"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Gran Selección Hoka</h3>
                    <p class="text-gray-600">Una de las mejores colecciones de zapatos Hoka</p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-all-smooth card-hover opacity-0 animate-fade-in-up" style="animation-delay: 0.4s;">
                    <div class="bg-orange-100 w-16 h-16 rounded-full flex items-center justify-center mb-4 group-hover:bg-orange-200 transition-all-smooth group-hover:scale-110">
                        <i class="fas fa-socks text-2xl text-orange-600 transition-all-smooth"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Accesorios</h3>
                    <p class="text-gray-600">Calcetines, bolsos y soportes para arco</p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-all-smooth card-hover opacity-0 animate-fade-in-up" style="animation-delay: 0.5s;">
                    <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mb-4 group-hover:bg-red-200 transition-all-smooth group-hover:scale-110">
                        <i class="fas fa-user-md text-2xl text-red-600 transition-all-smooth"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Personal Calificado</h3>
                    <p class="text-gray-600">Equipo experto y conocedor del producto</p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-all-smooth card-hover opacity-0 animate-fade-in-up" style="animation-delay: 0.6s;">
                    <div class="bg-yellow-100 w-16 h-16 rounded-full flex items-center justify-center mb-4 group-hover:bg-yellow-200 transition-all-smooth group-hover:scale-110">
                        <i class="fas fa-shoe-prints text-2xl text-yellow-600 transition-all-smooth"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Zapatos de Vestir</h3>
                    <p class="text-gray-600">Elegante colección para hombre y mujer</p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-all-smooth card-hover opacity-0 animate-fade-in-up" style="animation-delay: 0.7s;">
                    <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mb-4 group-hover:bg-indigo-200 transition-all-smooth group-hover:scale-110">
                        <i class="fas fa-stethoscope text-2xl text-indigo-600 transition-all-smooth"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Condiciones del Pie</h3>
                    <p class="text-gray-600">Productos para diversas condiciones podológicas</p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-all-smooth card-hover opacity-0 animate-fade-in-up" style="animation-delay: 0.8s;">
                    <div class="bg-teal-100 w-16 h-16 rounded-full flex items-center justify-center mb-4 group-hover:bg-teal-200 transition-all-smooth group-hover:scale-110">
                        <i class="fas fa-mountain text-2xl text-teal-600 transition-all-smooth"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Estilos Deportivos</h3>
                    <p class="text-gray-600">Calzado outdoor, atlético e impermeable</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Customer Reviews -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Lo Que Dicen Nuestros Clientes</h2>
                <p class="text-xl text-gray-600">Experiencias reales de clientes satisfechos</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Review 1 -->
                <div class="bg-gray-50 rounded-xl p-6 shadow-md hover:shadow-lg transition-all-smooth card-hover opacity-0 animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star transition-all-smooth hover:scale-125"></i>
                            <i class="fas fa-star transition-all-smooth hover:scale-125"></i>
                            <i class="fas fa-star transition-all-smooth hover:scale-125"></i>
                            <i class="fas fa-star transition-all-smooth hover:scale-125"></i>
                            <i class="fas fa-star transition-all-smooth hover:scale-125"></i>
                        </div>
                    </div>
                    <p class="text-gray-700 mb-4 italic leading-relaxed">
                        "Este es un gran ejemplo de por qué se necesitan tiendas físicas en el vecindario... 
                        tocar y sentir el producto, obtener respuestas en tiempo real y apoyar el negocio local."
                    </p>
                    <p class="font-semibold text-gray-900">- Darrin J.</p>
                </div>

                <!-- Review 2 -->
                <div class="bg-gray-50 rounded-xl p-6 shadow-md hover:shadow-lg transition-all-smooth card-hover opacity-0 animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star transition-all-smooth hover:scale-125"></i>
                            <i class="fas fa-star transition-all-smooth hover:scale-125"></i>
                            <i class="fas fa-star transition-all-smooth hover:scale-125"></i>
                            <i class="fas fa-star transition-all-smooth hover:scale-125"></i>
                            <i class="fas fa-star transition-all-smooth hover:scale-125"></i>
                        </div>
                    </div>
                    <p class="text-gray-700 mb-4 italic leading-relaxed">
                        "Estoy impresionado con cómo están manejando a los clientes durante la pandemia. 
                        Personal profesional, cortés y amigable. Me sentí cómodo comprando allí."
                    </p>
                    <p class="font-semibold text-gray-900">- Collie C.</p>
                </div>

                <!-- Review 3 -->
                <div class="bg-gray-50 rounded-xl p-6 shadow-md hover:shadow-lg transition-all-smooth card-hover opacity-0 animate-fade-in-up" style="animation-delay: 0.3s;">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star transition-all-smooth hover:scale-125"></i>
                            <i class="fas fa-star transition-all-smooth hover:scale-125"></i>
                            <i class="fas fa-star transition-all-smooth hover:scale-125"></i>
                            <i class="fas fa-star transition-all-smooth hover:scale-125"></i>
                            <i class="fas fa-star transition-all-smooth hover:scale-125"></i>
                        </div>
                    </div>
                    <p class="text-gray-700 mb-4 italic leading-relaxed">
                        "Mi tienda de zapatos favorita de propiedad local. Personal súper amigable y útil 
                        y una buena selección de zapatos. ¡Compra local!"
                    </p>
                    <p class="font-semibold text-gray-900">- Laylah M.</p>
                </div>

                <!-- Review 4 -->
                <div class="bg-gray-50 rounded-xl p-6 shadow-md hover:shadow-lg transition-all-smooth card-hover opacity-0 animate-fade-in-up" style="animation-delay: 0.4s;">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star transition-all-smooth hover:scale-125"></i>
                            <i class="fas fa-star transition-all-smooth hover:scale-125"></i>
                            <i class="fas fa-star transition-all-smooth hover:scale-125"></i>
                            <i class="fas fa-star transition-all-smooth hover:scale-125"></i>
                            <i class="fas fa-star transition-all-smooth hover:scale-125"></i>
                        </div>
                    </div>
                    <p class="text-gray-700 mb-4 italic leading-relaxed">
                        "Caesar fue extremadamente útil y conocedor. Tienen una máquina genial que toma 
                        una imagen del patrón de tu pie. ¡Caesar es un deleite para hablar!"
                    </p>
                    <p class="font-semibold text-gray-900">- Keith D.</p>
                </div>

                <!-- Review 5 -->
                <div class="bg-gray-50 rounded-xl p-6 shadow-md hover:shadow-lg transition-all-smooth card-hover opacity-0 animate-fade-in-up" style="animation-delay: 0.5s;">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star transition-all-smooth hover:scale-125"></i>
                            <i class="fas fa-star transition-all-smooth hover:scale-125"></i>
                            <i class="fas fa-star transition-all-smooth hover:scale-125"></i>
                            <i class="fas fa-star transition-all-smooth hover:scale-125"></i>
                            <i class="fas fa-star transition-all-smooth hover:scale-125"></i>
                        </div>
                    </div>
                    <p class="text-gray-700 mb-4 italic leading-relaxed">
                        "¡Gran servicio! El equipo de ventas trajo múltiples zapatos para que probara. 
                        Mis pies duelen cuando no uso los zapatos de su tienda. ¡Protege tus pies!"
                    </p>
                    <p class="font-semibold text-gray-900">- A. T.</p>
                </div>

                <!-- Review 6 -->
                <div class="bg-gray-50 rounded-xl p-6 shadow-md hover:shadow-lg transition-all-smooth card-hover opacity-0 animate-fade-in-up" style="animation-delay: 0.6s;">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-gray-700 mb-4 italic">
                        "¡Eso es de lo que estoy hablando! Servicio rápido y amigable que aún se toma 
                        el tiempo para asegurarse de que obtengas lo que necesitas. ¡Gracias Carlos!"
                    </p>
                    <p class="font-semibold text-gray-900">- Troy C.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-16 bg-gradient-to-br from-blue-600 to-blue-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold mb-4">Sobre Nosotros</h2>
                <div class="w-24 h-1 bg-white mx-auto"></div>
            </div>
            
            <div class="max-w-4xl mx-auto">
                <p class="text-xl text-blue-100 mb-6 leading-relaxed">
                    <strong class="text-white">¡Entra en estilo y comodidad con Footwear etc., tu destino definitivo para comprar zapatos!</strong>
                </p>
                <p class="text-lg text-blue-100 mb-6 leading-relaxed">
                    Durante 39 años, hemos sido un elemento querido de la comunidad, ofreciendo calzado de alta calidad 
                    que combina moda y comodidad. Desde zapatos atléticos de moda hasta opciones ortopédicas de apoyo, 
                    tenemos algo para todos.
                </p>
                <p class="text-lg text-blue-100 mb-6 leading-relaxed">
                    <strong class="text-white">El servicio excepcional al cliente está a solo un clic o llamada de distancia.</strong> 
                    Nuestro personal conocedor está listo para ayudar por correo electrónico o teléfono, proporcionando 
                    recomendaciones personalizadas. Priorizamos tu satisfacción para una experiencia de compra en línea perfecta.
                </p>
                <p class="text-lg text-blue-100 leading-relaxed">
                    <strong class="text-white">Visita nuestras ubicaciones en California o compra en línea</strong> para la combinación 
                    perfecta de estilo, comodidad y servicio. Ya seas un comprador orientado a la moda o busques apoyo y comodidad, 
                    Footwear etc. te tiene cubierto. <strong class="text-white">¡Tu viaje hacia un calzado fabuloso comienza aquí!</strong>
                </p>
            </div>
        </div>
    </section>

    <!-- Hours Section -->
    <section id="horarios" class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-8 shadow-lg">
                <h3 class="text-3xl font-bold text-gray-900 mb-6 text-center">
                    <i class="fas fa-clock text-blue-600 mr-3"></i>
                    Horarios de Atención
                </h3>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-lg p-6 shadow-md">
                        <h4 class="text-xl font-semibold text-gray-900 mb-4">Horarios Extendidos</h4>
                        <p class="text-gray-700">
                            Estamos abiertos más tiempo que la mayoría de las otras tiendas en el bloque, 
                            así que pasa hoy y experimenta zapatos de calidad y servicio amigable.
                        </p>
                    </div>
                    <div class="bg-white rounded-lg p-6 shadow-md">
                        <h4 class="text-xl font-semibold text-gray-900 mb-4">Servicio Personalizado</h4>
                        <p class="text-gray-700">
                            Nuestro personal está capacitado para medir tus pies y encontrar el zapato 
                            correcto para ti. Si no lo tenemos, lo pedimos por ti.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold mb-6">¿Listo para Encontrar tu Par Perfecto?</h2>
            <p class="text-xl text-gray-300 mb-8">
                Visítanos en Willow Glen o compra en línea desde la comodidad de tu hogar
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="#" class="bg-blue-600 text-white px-8 py-4 rounded-lg font-semibold hover:bg-blue-700 transition shadow-lg">
                    <i class="fas fa-store mr-2"></i>Visitar Tienda
                </a>
                <a href="#" class="bg-white text-gray-900 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition">
                    <i class="fas fa-shopping-bag mr-2"></i>Comprar en Línea
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <h4 class="text-white font-bold text-lg mb-4">Footwear etc.</h4>
                    <p class="text-sm">39 años de experiencia en calzado de calidad</p>
                </div>
                <div>
                    <h4 class="text-white font-bold text-lg mb-4">Enlaces</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">Productos</a></li>
                        <li><a href="#" class="hover:text-white transition">Marcas</a></li>
                        <li><a href="#" class="hover:text-white transition">Ofertas</a></li>
                        <li><a href="#" class="hover:text-white transition">Contacto</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold text-lg mb-4">Síguenos</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="hover:text-white transition"><i class="fab fa-facebook text-xl"></i></a>
                        <a href="#" class="hover:text-white transition"><i class="fab fa-instagram text-xl"></i></a>
                        <a href="#" class="hover:text-white transition"><i class="fab fa-twitter text-xl"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-bold text-lg mb-4">Contacto</h4>
                    <p class="text-sm">Willow Glen, San Jose, CA</p>
                    <p class="text-sm mt-2">1-800-720-0572</p>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm">
                <p>&copy; 2024 Footwear etc. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scroll reveal animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all animated elements
        document.addEventListener('DOMContentLoaded', () => {
            const animatedElements = document.querySelectorAll('.opacity-0');
            animatedElements.forEach(el => {
                observer.observe(el);
            });

            // Add smooth parallax effect to hero
            const hero = document.querySelector('section.relative.bg-gradient-to-r');
            if (hero) {
                window.addEventListener('scroll', () => {
                    const scrolled = window.pageYOffset;
                    const rate = scrolled * 0.5;
                    hero.style.transform = `translateY(${rate}px)`;
                });
            }
        });

        // Smooth hover effects for buttons
        document.querySelectorAll('a, button').forEach(element => {
            element.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.05)';
            });
            element.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>

