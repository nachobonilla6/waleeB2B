<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footwear etc. San Jose - Willow Glen | Tienda de Calzado</title>
    <meta name="description" content="Visita nuestra tienda en Willow Glen, San Jose. Calzado de calidad, servicio excepcional y 39 años de experiencia.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            scroll-behavior: smooth;
        }
        
        .shopify-nav {
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .shopify-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .shopify-btn {
            background: #000;
            color: #fff;
            padding: 12px 24px;
            border-radius: 4px;
            font-weight: 500;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }
        
        .shopify-btn:hover {
            background: #333;
        }
        
        .shopify-btn-secondary {
            background: transparent;
            color: #000;
            border: 1px solid #000;
        }
        
        .shopify-btn-secondary:hover {
            background: #000;
            color: #fff;
        }
        
        .shopify-section {
            padding: 60px 0;
        }
        
        .shopify-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }
        
        .shopify-card {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .shopify-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .shopify-heading {
            font-size: 32px;
            font-weight: 600;
            line-height: 1.2;
            margin-bottom: 16px;
        }
        
        .shopify-text {
            font-size: 16px;
            line-height: 1.6;
            color: #4b5563;
        }
        
        .shopify-link {
            color: #000;
            text-decoration: underline;
            text-underline-offset: 4px;
            transition: opacity 0.2s;
        }
        
        .shopify-link:hover {
            opacity: 0.7;
        }
        
        .shopify-input {
            border: 1px solid #d1d5db;
            border-radius: 4px;
            padding: 10px 14px;
            font-size: 14px;
            width: 100%;
        }
        
        .shopify-input:focus {
            outline: none;
            border-color: #000;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }
    </style>
</head>
<body class="bg-white">
    <!-- Shopify-style Navigation -->
    <nav class="shopify-nav sticky top-0 z-50">
        <div class="shopify-container">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-8">
                    <a href="/" class="flex items-center">
                        <img src="https://footwearetc.com/cdn/shop/files/Footwear_etc._logo_440x.png?v=1760562269" alt="Footwear etc." class="h-8">
                    </a>
                    <div class="hidden md:flex items-center space-x-6">
                        <a href="#" class="text-sm font-medium text-gray-700 hover:text-gray-900">Productos</a>
                        <a href="#" class="text-sm font-medium text-gray-700 hover:text-gray-900">Marcas</a>
                        <a href="#" class="text-sm font-medium text-gray-700 hover:text-gray-900">Ofertas</a>
                        <a href="#" class="text-sm font-medium text-gray-700 hover:text-gray-900">Acerca de</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="text-gray-700 hover:text-gray-900">
                        <i class="fas fa-search text-lg"></i>
                    </button>
                    <button class="text-gray-700 hover:text-gray-900 relative">
                        <i class="fas fa-shopping-cart text-lg"></i>
                        <span class="absolute -top-2 -right-2 bg-black text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section - Shopify Style -->
    <section class="bg-gray-50 shopify-section">
        <div class="shopify-container">
            <div class="grid md:grid-cols-2 gap-12 items-center fade-in">
                <div>
                    <h1 class="shopify-heading text-4xl md:text-5xl mb-6">
                        San Jose Shoe Store<br>
                        <span class="text-gray-600">Footwear etc. San Jose</span><br>
                        <span class="text-xl text-gray-500">Downtown Willow Glen</span>
                    </h1>
                    <p class="shopify-text text-lg mb-8">
                        Más de 39 años ofreciendo calzado de calidad que combina estilo y comodidad. 
                        Tu destino definitivo para encontrar el par perfecto.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="#ubicacion" class="shopify-btn">Ver Ubicación</a>
                        <a href="#horarios" class="shopify-btn shopify-btn-secondary">Horarios</a>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="bg-white rounded-lg p-8 shadow-sm border border-gray-200">
                        <div class="space-y-6">
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-map-marker-alt text-gray-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-1">Ubicación</h3>
                                    <p class="text-sm text-gray-600">Willow Glen, San Jose</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-clock text-gray-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-1">Horarios Extendidos</h3>
                                    <p class="text-sm text-gray-600">Abierto más tiempo que otras tiendas</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-star text-gray-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-1">39 Años de Experiencia</h3>
                                    <p class="text-sm text-gray-600">Servicio de calidad comprobado</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Location Section -->
    <section id="ubicacion" class="shopify-section bg-white">
        <div class="shopify-container">
            <div class="grid md:grid-cols-2 gap-12">
                <div class="fade-in">
                    <h2 class="shopify-heading text-3xl mb-6">
                        Nuestra Ubicación
                    </h2>
                    <div class="space-y-4">
                        <p class="text-lg font-semibold text-gray-900">Footwear etc. Willow Glen</p>
                        <div class="space-y-3">
                            <p class="flex items-start text-gray-700">
                                <i class="fas fa-location-dot text-gray-400 mr-3 mt-1"></i>
                                <span>Ubicado en el corazón de Willow Glen, San Jose</span>
                            </p>
                            <p class="flex items-start text-gray-700">
                                <i class="fas fa-utensils text-gray-400 mr-3 mt-1"></i>
                                <span>Cerca de Sushi Arashi y Round Table Pizza</span>
                            </p>
                            <p class="flex items-start text-gray-700">
                                <i class="fas fa-tree text-gray-400 mr-3 mt-1"></i>
                                <span>Calles arboladas y ambiente relajado de Willow Glen</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="bg-gray-100 rounded-lg overflow-hidden" style="padding-bottom: 75%; position: relative;">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3172.234567890123!2d-121.90234567890123!3d37.30456789012345!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzfCsDE4JzE2LjQiTiAxMjHCsDU0JzA4LjQiVw!5e0!3m2!1sen!2sus!4v1234567890123!5m2!1sen!2sus" 
                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;"
                            allowfullscreen="" 
                            loading="lazy">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Store Features - Shopify Grid -->
    <section class="shopify-section bg-gray-50">
        <div class="shopify-container">
            <div class="text-center mb-12 fade-in">
                <h2 class="shopify-heading text-3xl mb-4">En Esta Tienda Encontrarás</h2>
                <p class="shopify-text text-lg">Todo lo que necesitas para el cuidado de tus pies</p>
            </div>
            
            <!-- Store Interior Photo -->
            <div class="mb-12 fade-in">
                <div class="bg-gray-100 rounded-lg overflow-hidden">
                    <img 
                        src="https://cdn.shopify.com/s/files/1/0012/5332/3286/files/footwear-etc-store-interior.jpg" 
                        alt="Interior de Footwear etc. San Jose" 
                        class="w-full h-auto"
                        onerror="this.src='https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=1200&h=600&fit=crop';">
                </div>
            </div>
            
            <div class="shopify-grid">
                <div class="shopify-card fade-in">
                    <div class="p-6">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-shoe-prints text-gray-600"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Sandalias</h3>
                        <p class="text-sm text-gray-600">Amplia selección de sandalias para hombre y mujer</p>
                    </div>
                </div>

                <div class="shopify-card fade-in">
                    <div class="p-6">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-heartbeat text-gray-600"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Calzado de Salud</h3>
                        <p class="text-sm text-gray-600">Calzado especializado para bienestar y salud</p>
                    </div>
                </div>

                <div class="shopify-card fade-in">
                    <div class="p-6">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-running text-gray-600"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Gran Selección Hoka</h3>
                        <p class="text-sm text-gray-600">Una de las mejores colecciones de zapatos Hoka</p>
                    </div>
                </div>

                <div class="shopify-card fade-in">
                    <div class="p-6">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-socks text-gray-600"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Accesorios</h3>
                        <p class="text-sm text-gray-600">Calcetines, bolsos y soportes para arco</p>
                    </div>
                </div>

                <div class="shopify-card fade-in">
                    <div class="p-6">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-user-md text-gray-600"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Personal Calificado</h3>
                        <p class="text-sm text-gray-600">Equipo experto y conocedor del producto</p>
                    </div>
                </div>

                <div class="shopify-card fade-in">
                    <div class="p-6">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-shoe-prints text-gray-600"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Zapatos de Vestir</h3>
                        <p class="text-sm text-gray-600">Elegante colección para hombre y mujer</p>
                    </div>
                </div>

                <div class="shopify-card fade-in">
                    <div class="p-6">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-stethoscope text-gray-600"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Condiciones del Pie</h3>
                        <p class="text-sm text-gray-600">Productos para diversas condiciones podológicas</p>
                    </div>
                </div>

                <div class="shopify-card fade-in">
                    <div class="p-6">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-mountain text-gray-600"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Estilos Deportivos</h3>
                        <p class="text-sm text-gray-600">Calzado outdoor, atlético e impermeable</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Customer Reviews - Shopify Style -->
    <section class="shopify-section bg-white">
        <div class="shopify-container">
            <div class="text-center mb-12 fade-in">
                <h2 class="shopify-heading text-3xl mb-4">Lo Que Dicen Nuestros Clientes</h2>
                <p class="shopify-text text-lg">Experiencias reales de clientes satisfechos</p>
            </div>

            <div class="shopify-grid">
                <div class="shopify-card fade-in">
                    <div class="p-6">
                        <div class="flex text-yellow-400 mb-4">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="text-gray-700 mb-4 italic text-sm leading-relaxed">
                            "Este es un gran ejemplo de por qué se necesitan tiendas físicas en el vecindario... 
                            tocar y sentir el producto, obtener respuestas en tiempo real y apoyar el negocio local."
                        </p>
                        <p class="font-semibold text-gray-900 text-sm">- Darrin J.</p>
                    </div>
                </div>

                <div class="shopify-card fade-in">
                    <div class="p-6">
                        <div class="flex text-yellow-400 mb-4">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="text-gray-700 mb-4 italic text-sm leading-relaxed">
                            "Estoy impresionado con cómo están manejando a los clientes durante la pandemia. 
                            Personal profesional, cortés y amigable. Me sentí cómodo comprando allí."
                        </p>
                        <p class="font-semibold text-gray-900 text-sm">- Collie C.</p>
                    </div>
                </div>

                <div class="shopify-card fade-in">
                    <div class="p-6">
                        <div class="flex text-yellow-400 mb-4">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="text-gray-700 mb-4 italic text-sm leading-relaxed">
                            "Mi tienda de zapatos favorita de propiedad local. Personal súper amigable y útil 
                            y una buena selección de zapatos. ¡Compra local!"
                        </p>
                        <p class="font-semibold text-gray-900 text-sm">- Laylah M.</p>
                    </div>
                </div>

                <div class="shopify-card fade-in">
                    <div class="p-6">
                        <div class="flex text-yellow-400 mb-4">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="text-gray-700 mb-4 italic text-sm leading-relaxed">
                            "Caesar fue extremadamente útil y conocedor. Tienen una máquina genial que toma 
                            una imagen del patrón de tu pie. ¡Caesar es un deleite para hablar!"
                        </p>
                        <p class="font-semibold text-gray-900 text-sm">- Keith D.</p>
                    </div>
                </div>

                <div class="shopify-card fade-in">
                    <div class="p-6">
                        <div class="flex text-yellow-400 mb-4">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="text-gray-700 mb-4 italic text-sm leading-relaxed">
                            "¡Gran servicio! El equipo de ventas trajo múltiples zapatos para que probara. 
                            Mis pies duelen cuando no uso los zapatos de su tienda. ¡Protege tus pies!"
                        </p>
                        <p class="font-semibold text-gray-900 text-sm">- A. T.</p>
                    </div>
                </div>

                <div class="shopify-card fade-in">
                    <div class="p-6">
                        <div class="flex text-yellow-400 mb-4">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="text-gray-700 mb-4 italic text-sm leading-relaxed">
                            "¡Eso es de lo que estoy hablando! Servicio rápido y amigable que aún se toma 
                            el tiempo para asegurarse de que obtengas lo que necesitas. ¡Gracias Carlos!"
                        </p>
                        <p class="font-semibold text-gray-900 text-sm">- Troy C.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section - Shopify Style -->
    <section class="shopify-section bg-gray-50">
        <div class="shopify-container">
            <div class="max-w-3xl mx-auto text-center fade-in">
                <h2 class="shopify-heading text-3xl mb-6">Sobre Nosotros</h2>
                <div class="space-y-6 text-left">
                    <p class="shopify-text">
                        <strong class="text-gray-900">¡Entra en estilo y comodidad con Footwear etc., tu destino definitivo para comprar zapatos!</strong>
                    </p>
                    <p class="shopify-text">
                        Durante 39 años, hemos sido un elemento querido de la comunidad, ofreciendo calzado de alta calidad 
                        que combina moda y comodidad. Desde zapatos atléticos de moda hasta opciones ortopédicas de apoyo, 
                        tenemos algo para todos.
                    </p>
                    <p class="shopify-text">
                        <strong class="text-gray-900">El servicio excepcional al cliente está a solo un clic o llamada de distancia.</strong> 
                        Nuestro personal conocedor está listo para ayudar por correo electrónico o teléfono, proporcionando 
                        recomendaciones personalizadas. Priorizamos tu satisfacción para una experiencia de compra en línea perfecta.
                    </p>
                    <p class="shopify-text">
                        <strong class="text-gray-900">Visita nuestras ubicaciones en California o compra en línea</strong> para la combinación 
                        perfecta de estilo, comodidad y servicio. Ya seas un comprador orientado a la moda o busques apoyo y comodidad, 
                        Footwear etc. te tiene cubierto. <strong class="text-gray-900">¡Tu viaje hacia un calzado fabuloso comienza aquí!</strong>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Hours Section -->
    <section id="horarios" class="shopify-section bg-white">
        <div class="shopify-container">
            <div class="max-w-2xl mx-auto fade-in">
                <div class="bg-gray-50 rounded-lg p-8 border border-gray-200">
                    <h3 class="shopify-heading text-2xl mb-6 text-center">
                        Horarios de Atención
                    </h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">Horarios Extendidos</h4>
                            <p class="text-sm text-gray-600">
                                Estamos abiertos más tiempo que la mayoría de las otras tiendas en el bloque, 
                                así que pasa hoy y experimenta zapatos de calidad y servicio amigable.
                            </p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">Servicio Personalizado</h4>
                            <p class="text-sm text-gray-600">
                                Nuestro personal está capacitado para medir tus pies y encontrar el zapato 
                                correcto para ti. Si no lo tenemos, lo pedimos por ti.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section - Shopify Style -->
    <section class="shopify-section bg-black text-white">
        <div class="shopify-container text-center">
            <h2 class="shopify-heading text-3xl mb-4 text-white">¿Listo para Encontrar tu Par Perfecto?</h2>
            <p class="shopify-text text-lg mb-8 text-gray-300">
                Visítanos en Willow Glen o compra en línea desde la comodidad de tu hogar
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="#" class="shopify-btn bg-white text-black hover:bg-gray-100">Visitar Tienda</a>
                <a href="#" class="shopify-btn shopify-btn-secondary border-white text-white hover:bg-white hover:text-black">Comprar en Línea</a>
            </div>
        </div>
    </section>

    <!-- Footer - Shopify Style -->
    <footer class="bg-white border-t border-gray-200 py-12">
        <div class="shopify-container">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-4">Footwear etc.</h4>
                    <p class="text-sm text-gray-600">39 años de experiencia en calzado de calidad</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-4">Enlaces</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="shopify-link text-gray-600">Productos</a></li>
                        <li><a href="#" class="shopify-link text-gray-600">Marcas</a></li>
                        <li><a href="#" class="shopify-link text-gray-600">Ofertas</a></li>
                        <li><a href="#" class="shopify-link text-gray-600">Contacto</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-4">Síguenos</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-600 hover:text-gray-900"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-gray-600 hover:text-gray-900"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-600 hover:text-gray-900"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-4">Contacto</h4>
                    <p class="text-sm text-gray-600">Willow Glen, San Jose, CA</p>
                    <p class="text-sm text-gray-600 mt-2">1-800-720-0572</p>
                </div>
            </div>
            <div class="border-t border-gray-200 pt-8 text-center text-sm text-gray-600">
                <p>&copy; 2024 Footwear etc. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html>
