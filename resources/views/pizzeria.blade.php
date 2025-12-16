<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Pizzeria - Web Solutions CR</title>
    <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/512/2115/2115955.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        .image-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .image-hover:hover {
            transform: scale(1.05);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        .thumbnail-item.active {
            border-color: rgb(239 68 68) !important;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }
        .thumbnail-item:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <div class="min-h-screen">
        <!-- Header -->
        <div class="bg-gradient-to-r from-red-600 to-orange-600 text-white py-16 relative">
            <!-- Botón Back -->
            <div class="container mx-auto px-4 mb-4">
                <a href="/" class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm px-4 py-2 rounded-lg transition-all duration-300 text-white font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver al inicio
                </a>
            </div>
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-5xl font-bold mb-4">🍕 Sistema de Pizzeria</h1>
                <p class="text-xl opacity-90 max-w-3xl mx-auto">
                    Plataforma completa para gestionar pedidos online, carrito de compras, confirmaciones por email y administración de clientes. Ideal para pizzerías que buscan digitalizar su negocio.
                </p>
            </div>
        </div>

        <!-- Galería de Imágenes -->
        <div class="container mx-auto px-4 py-12">
            <div class="max-w-6xl mx-auto mb-12">
                <h2 class="text-4xl font-bold text-center mb-4 text-gray-900 dark:text-white">Galería del Sistema</h2>
                <p class="text-center text-gray-600 dark:text-gray-400 mb-8 max-w-2xl mx-auto">
                    Descubre las características y funcionalidades de nuestro sistema diseñado especialmente para pizzerías
                </p>
                
                <!-- Imagen Principal Grande -->
                <div class="mb-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl overflow-hidden">
                        <div class="bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden" style="min-height: 500px;">
                            <img id="mainImage" 
                                 src="https://i.postimg.cc/s2FSt9bC/screenshot-2025-12-15-21-04-26.png" 
                                 alt="Sistema de Pizzeria" 
                                 class="w-full h-full object-contain transition-opacity duration-300">
                        </div>
                        <div class="p-6">
                            <h3 id="mainTitle" class="text-2xl font-bold mb-2 text-gray-900 dark:text-white">Sistema de Pizzeria</h3>
                            <p id="mainDescription" class="text-gray-600 dark:text-gray-300">Plataforma completa para gestión de pedidos y clientes</p>
                        </div>
                    </div>
                </div>

                <!-- Thumbnails -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <!-- Thumbnail 1 -->
                    <div class="cursor-pointer thumbnail-item bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border-2 border-red-500 transition-all duration-300 hover:shadow-lg active" 
                         onclick="changeImage('https://i.postimg.cc/s2FSt9bC/screenshot-2025-12-15-21-04-26.png', 'Sistema de Pizzeria', 'Plataforma completa para gestión de pedidos y clientes', this)">
                        <div class="aspect-video bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                            <img src="https://i.postimg.cc/s2FSt9bC/screenshot-2025-12-15-21-04-26.png" 
                                 alt="Sistema de Pizzeria" 
                                 class="w-full h-full object-contain">
                        </div>
                        <div class="p-3">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white text-center">Sistema de Pizzeria</p>
                        </div>
                    </div>

                    <!-- Thumbnail 2 -->
                    <div class="cursor-pointer thumbnail-item bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border-2 border-transparent transition-all duration-300 hover:shadow-lg hover:border-red-300" 
                         onclick="changeImage('https://i.postimg.cc/BvWDdcR9/screenshot-2025-12-15-21-04-42.png', 'Carrito de Compras', 'Sistema de carrito de compras intuitivo para tus clientes', this)">
                        <div class="aspect-video bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                            <img src="https://i.postimg.cc/BvWDdcR9/screenshot-2025-12-15-21-04-42.png" 
                                 alt="Carrito de Compras" 
                                 class="w-full h-full object-contain">
                        </div>
                        <div class="p-3">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white text-center">Carrito de Compras</p>
                        </div>
                    </div>

                    <!-- Thumbnail 3 -->
                    <div class="cursor-pointer thumbnail-item bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border-2 border-transparent transition-all duration-300 hover:shadow-lg hover:border-red-300" 
                         onclick="changeImage('https://i.postimg.cc/6QsRDLFs/screenshot-2025-12-15-21-04-50.png', 'Gestión de Pedidos', 'Panel de administración para gestionar todos los pedidos', this)">
                        <div class="aspect-video bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                            <img src="https://i.postimg.cc/6QsRDLFs/screenshot-2025-12-15-21-04-50.png" 
                                 alt="Gestión de Pedidos" 
                                 class="w-full h-full object-contain">
                        </div>
                        <div class="p-3">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white text-center">Gestión de Pedidos</p>
                        </div>
                    </div>

                    <!-- Thumbnail 4 -->
                    <div class="cursor-pointer thumbnail-item bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border-2 border-transparent transition-all duration-300 hover:shadow-lg hover:border-red-300" 
                         onclick="changeImage('https://i.postimg.cc/jjVNGQmB/screenshot-2025-12-15-21-05-07.png', 'Administración de Clientes', 'Sistema completo para gestionar información de clientes', this)">
                        <div class="aspect-video bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                            <img src="https://i.postimg.cc/jjVNGQmB/screenshot-2025-12-15-21-05-07.png" 
                                 alt="Administración de Clientes" 
                                 class="w-full h-full object-contain">
                        </div>
                        <div class="p-3">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white text-center">Administración de Clientes</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Módulos Principales -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                
                <!-- Módulo de Carrito de Compras -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border-l-4 border-red-500">
                    <div class="flex items-center mb-4">
                        <div class="bg-red-100 dark:bg-red-900 p-3 rounded-lg mr-4">
                            <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Carrito de Compras</h2>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">
                        Sistema intuitivo de carrito de compras para que tus clientes puedan seleccionar y personalizar sus pedidos fácilmente.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                        <li class="flex items-start">
                            <span class="text-green-500 mr-2">✓</span>
                            <div>
                                <strong>Interfaz Intuitiva:</strong> Diseño fácil de usar para que los clientes agreguen productos rápidamente
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-500 mr-2">✓</span>
                            <div>
                                <strong>Personalización:</strong> Los clientes pueden personalizar sus pizzas con ingredientes adicionales
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-500 mr-2">✓</span>
                            <div>
                                <strong>Cálculo Automático:</strong> El sistema calcula automáticamente el total del pedido
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-500 mr-2">✓</span>
                            <div>
                                <strong>Persistencia:</strong> El carrito se guarda durante la sesión del cliente
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Módulo de Pedidos por Email -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border-l-4 border-orange-500">
                    <div class="flex items-center mb-4">
                        <div class="bg-orange-100 dark:bg-orange-900 p-3 rounded-lg mr-4">
                            <svg class="w-8 h-8 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Recepción de Pedidos</h2>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">
                        Todos los pedidos se reciben automáticamente por email para que nunca pierdas una orden.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                        <li class="flex items-start">
                            <span class="text-green-500 mr-2">✓</span>
                            <div>
                                <strong>Notificaciones Instantáneas:</strong> Recibe un email cada vez que un cliente realiza un pedido
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-500 mr-2">✓</span>
                            <div>
                                <strong>Detalles Completos:</strong> El email incluye toda la información del pedido y del cliente
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-500 mr-2">✓</span>
                            <div>
                                <strong>Múltiples Destinatarios:</strong> Puedes configurar varios emails para recibir notificaciones
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-500 mr-2">✓</span>
                            <div>
                                <strong>Formato Profesional:</strong> Emails bien estructurados y fáciles de leer
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Módulo de Confirmación por Email -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 dark:bg-green-900 p-3 rounded-lg mr-4">
                            <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Confirmación al Cliente</h2>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">
                        Los clientes reciben automáticamente un email de confirmación con los detalles de su pedido.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                        <li class="flex items-start">
                            <span class="text-green-500 mr-2">✓</span>
                            <div>
                                <strong>Confirmación Automática:</strong> Email enviado automáticamente al completar el pedido
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-500 mr-2">✓</span>
                            <div>
                                <strong>Resumen del Pedido:</strong> El cliente recibe un resumen completo de lo que ordenó
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-500 mr-2">✓</span>
                            <div>
                                <strong>Información de Contacto:</strong> Incluye datos de contacto y tiempo estimado de entrega
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-500 mr-2">✓</span>
                            <div>
                                <strong>Diseño Profesional:</strong> Emails con diseño atractivo que refleja tu marca
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Módulo de Administración -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-lg mr-4">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Panel de Administración</h2>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">
                        Panel completo para gestionar pedidos y clientes de forma eficiente.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                        <li class="flex items-start">
                            <span class="text-green-500 mr-2">✓</span>
                            <div>
                                <strong>Gestión de Pedidos:</strong> Visualiza, edita y gestiona todos los pedidos en un solo lugar
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-500 mr-2">✓</span>
                            <div>
                                <strong>Administración de Clientes:</strong> Base de datos completa con información de todos los clientes
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-500 mr-2">✓</span>
                            <div>
                                <strong>Estados de Pedidos:</strong> Cambia el estado de los pedidos (pendiente, en preparación, listo, entregado)
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-500 mr-2">✓</span>
                            <div>
                                <strong>Reportes y Estadísticas:</strong> Visualiza estadísticas de ventas y pedidos más populares
                            </div>
                        </li>
                    </ul>
                </div>

            </div>

            <!-- Funcionalidades Destacadas -->
            <div class="bg-gradient-to-r from-red-50 to-orange-50 dark:from-gray-800 dark:to-gray-900 rounded-lg p-8 mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6 text-center">✨ Funcionalidades Destacadas</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-md">
                        <div class="text-4xl mb-4">🛒</div>
                        <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">Carrito Inteligente</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">
                            Sistema de carrito de compras con persistencia de sesión y cálculo automático de totales.
                        </p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-md">
                        <div class="text-4xl mb-4">📧</div>
                        <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">Notificaciones por Email</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">
                            Recibe pedidos por email y envía confirmaciones automáticas a tus clientes.
                        </p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-md">
                        <div class="text-4xl mb-4">👥</div>
                        <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">Gestión de Clientes</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">
                            Administra información de clientes, historial de pedidos y preferencias.
                        </p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-md">
                        <div class="text-4xl mb-4">📊</div>
                        <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">Panel de Control</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">
                            Dashboard completo para gestionar pedidos, ver estadísticas y administrar tu negocio.
                        </p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-md">
                        <div class="text-4xl mb-4">⚡</div>
                        <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">Procesamiento Rápido</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">
                            Sistema optimizado para procesar pedidos rápidamente y mantener tu negocio funcionando sin interrupciones.
                        </p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-md">
                        <div class="text-4xl mb-4">📱</div>
                        <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">Diseño Responsive</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">
                            Interfaz adaptada para funcionar perfectamente en dispositivos móviles, tablets y escritorio.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Beneficios para el Negocio -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6 text-center">💼 Beneficios para tu Pizzeria</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="bg-red-100 dark:bg-red-900 p-3 rounded-full">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Ahorro de Tiempo</h3>
                            <p class="text-gray-600 dark:text-gray-300">
                                Automatiza el proceso de pedidos, desde la recepción hasta la confirmación al cliente, ahorrando tiempo valioso.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="bg-orange-100 dark:bg-orange-900 p-3 rounded-full">
                                <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Mejor Organización</h3>
                            <p class="text-gray-600 dark:text-gray-300">
                                Centraliza todos los pedidos y clientes en un solo lugar, facilitando la gestión diaria de tu pizzeria.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Aumento de Ventas</h3>
                            <p class="text-gray-600 dark:text-gray-300">
                                Facilita que los clientes realicen pedidos online, aumentando las ventas y mejorando la experiencia del cliente.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Profesionalismo</h3>
                            <p class="text-gray-600 dark:text-gray-300">
                                Presenta una imagen profesional con confirmaciones automáticas y un sistema bien organizado que inspira confianza.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA -->
            <div class="bg-gradient-to-r from-red-600 to-orange-600 rounded-lg p-8 text-white text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">¿Listo para digitalizar tu pizzeria?</h2>
                <p class="text-lg mb-6 opacity-90">
                    Este sistema está diseñado especialmente para pizzerías que buscan modernizar su negocio, mejorar la experiencia del cliente y aumentar sus ventas mediante pedidos online automatizados.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg px-6 py-3">
                        <div class="text-2xl font-bold">24/7</div>
                        <div class="text-sm opacity-90">Disponible</div>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg px-6 py-3">
                        <div class="text-2xl font-bold">📧</div>
                        <div class="text-sm opacity-90">Notificaciones</div>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg px-6 py-3">
                        <div class="text-2xl font-bold">⚡</div>
                        <div class="text-sm opacity-90">Rápido</div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center py-8 text-gray-600 dark:text-gray-400">
                <p>&copy; {{ date('Y') }} Web Solutions CR. Todos los derechos reservados.</p>
            </div>
        </div>
    </div>

    <script>
        function changeImage(imageSrc, title, description, thumbnailElement) {
            // Cambiar la imagen principal con efecto fade
            const mainImage = document.getElementById('mainImage');
            const mainTitle = document.getElementById('mainTitle');
            const mainDescription = document.getElementById('mainDescription');
            
            // Fade out
            mainImage.style.opacity = '0';
            
            setTimeout(() => {
                mainImage.src = imageSrc;
                mainImage.alt = title;
                mainTitle.textContent = title;
                mainDescription.textContent = description;
                
                // Fade in
                mainImage.style.opacity = '1';
            }, 150);
            
            // Actualizar estado activo de thumbnails
            document.querySelectorAll('.thumbnail-item').forEach(item => {
                item.classList.remove('active', 'border-red-500');
                item.classList.add('border-transparent');
            });
            
            thumbnailElement.classList.add('active', 'border-red-500');
            thumbnailElement.classList.remove('border-transparent');
        }
    </script>
</body>
</html>
