<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidad - Web Solutions CR</title>
    <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/512/2115/2115955.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .float-animation { animation: float 4s ease-in-out infinite; }
        
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .gradient-animation {
            background: linear-gradient(-45deg, #1e40af, #1e3a8a, #1e1b4b, #1e1b4b);
            background-size: 300% 300%;
            animation: gradient 15s ease infinite;
        }
        
        .glass {
            background: rgba(17, 24, 39, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #030712;
            color: #f3f4f6;
        }
        
        .prose {
            color: #e5e7eb;
            max-width: 65ch;
            margin: 0 auto;
            line-height: 1.7;
        }
        
        .prose h1 {
            font-size: 2.25rem;
            line-height: 2.5rem;
            font-weight: 800;
            margin-top: 0;
            margin-bottom: 1.5rem;
        }
        
        .prose h2 {
            font-size: 1.5rem;
            line-height: 2rem;
            font-weight: 700;
            color: #e5e7eb;
            margin-top: 2.5rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .prose p {
            margin-bottom: 1.25rem;
            color: #d1d5db;
        }
        
        .prose ul, .prose ol {
            margin-bottom: 1.5rem;
            padding-left: 1.5rem;
        }
        
        .prose li {
            margin-bottom: 0.5rem;
            color: #d1d5db;
        }
        
        .prose a {
            color: #60a5fa;
            text-decoration: none;
            transition: color 0.2s;
            font-weight: 500;
        }
        
        .prose a:hover {
            color: #3b82f6;
            text-decoration: underline;
        }
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen flex flex-col">
    <!-- Header -->
    <header class="gradient-animation py-16 relative overflow-hidden">
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-20 left-10 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl animate-pulse"></div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-xl animate-pulse animation-delay-2000"></div>
            <div class="absolute bottom-0 left-1/2 w-80 h-80 bg-indigo-500 rounded-full mix-blend-multiply filter blur-xl animate-pulse animation-delay-4000"></div>
        </div>
        <div class="container mx-auto px-6 text-center relative z-10">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Política de Privacidad</h1>
            <p class="text-xl text-gray-300 max-w-3xl mx-auto">Cómo protegemos y manejamos tu información en nuestra plataforma de integración n8n con Facebook</p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow py-12 px-4">
        <div class="container mx-auto max-w-4xl">
            <div class="glass rounded-2xl p-8 md:p-12 backdrop-blur-sm">
                <div class="prose max-w-none">
                    <h1>Política de Privacidad para la Aplicación Web Solutions CR</h1>
                    <p class="text-gray-400 mb-8">Última actualización: {{ date('F d, Y') }}</p>
                    <p class="text-gray-400">Este documento detalla cómo manejamos la información en nuestra aplicación que se integra con Facebook a través de n8n.</p>

                    <h2>1. Información que Recopilamos</h2>
                    <p>Cuando utilizas nuestra aplicación con Facebook, podemos recopilar la siguiente información:</p>
                    <ul>
                        <li><strong>Credenciales de API de Facebook:</strong> Tokens de acceso necesarios para la integración con Facebook.</li>
                        <li><strong>Datos de perfil público:</strong> Información básica de tu perfil de Facebook que es pública.</li>
                        <li><strong>Datos de páginas administradas:</strong> Si nos otorgas permisos de administración de páginas.</li>
                        <li><strong>Interacciones:</strong> Datos de publicaciones, comentarios y reacciones según los permisos otorgados.</li>
                        <li><strong>Datos de análisis:</strong> Métricas de interacción y rendimiento de publicaciones.</li>
                    </ul>
                    
                    <h2>2. Uso de la Información</h2>
                    <p>Utilizamos la información recopilada para:</p>
                    <ul>
                        <li>Proporcionar y mantener nuestro servicio de integración.</li>
                        <li>Automatizar tareas entre Facebook y otras plataformas a través de n8n.</li>
                        <li>Mejorar y personalizar la experiencia del usuario.</li>
                        <li>Generar informes y análisis de rendimiento.</li>
                    </ul>

                    <h2>3. Compartición de Datos</h2>
                    <p>No vendemos ni compartimos tus datos personales con terceros, excepto en los siguientes casos:</p>
                    <ul>
                        <li>Con proveedores de servicios que nos ayudan a operar nuestra aplicación (como servicios de hosting).</li>
                        <li>Cuando sea requerido por ley o en respuesta a solicitudes legales válidas.</li>
                        <li>Para proteger los derechos, propiedad o seguridad de nuestra empresa, nuestros usuarios u otros.</li>
                    </ul>

                    <h2>4. Seguridad de los Datos</h2>
                    <p>Implementamos medidas de seguridad técnicas y organizativas para proteger tus datos:</p>
                    <ul>
                        <li>Encriptación de datos en tránsito usando protocolos seguros (HTTPS/TLS).</li>
                        <li>Almacenamiento seguro de credenciales con encriptación.</li>
                        <li>Acceso restringido solo a personal autorizado.</li>
                        <li>Monitoreo continuo de seguridad para prevenir accesos no autorizados.</li>
                    </ul>

                    <h2>5. Retención de Datos</h2>
                    <p>Mantenemos tus datos solo durante el tiempo necesario para los fines descritos en esta política, a menos que la ley requiera o permita un período de retención más largo. Los tokens de acceso de Facebook se actualizan automáticamente según sea necesario.</p>

                    <h2>6. Tus Derechos de Privacidad</h2>
                    <p>De acuerdo con las regulaciones de protección de datos, tienes derecho a:</p>
                    <ul>
                        <li>Solicitar acceso a los datos personales que tenemos sobre ti.</li>
                        <li>Solicitar la corrección de datos inexactos.</li>
                        <li>Solicitar la eliminación de tus datos personales.</li>
                        <li>Retirar tu consentimiento en cualquier momento.</li>
                        <li>Presentar una queja ante una autoridad de protección de datos.</li>
                    </ul>
                    
                    <p>Para ejercer estos derechos, contáctanos en info@websolutions.work.</p>

                    <h2>7. Transferencias Internacionales de Datos</h2>
                    <p>Tus datos pueden ser procesados en países fuera de tu país de residencia. Nos aseguramos de que estas transferencias cumplan con las leyes aplicables de protección de datos.</p>

                    <h2>8. Cambios en esta Política</h2>
                    <p>Podemos actualizar esta política periódicamente. Te notificaremos sobre cambios significativos publicando la nueva política en nuestro sitio web.</p>

                    <h2>9. Contacto</h2>
                    <p>Si tienes preguntas sobre esta política de privacidad o sobre cómo manejamos tus datos, contáctanos en:</p>
                    <p class="bg-gray-800 p-4 rounded-lg inline-block">
                        <strong>Email:</strong> info@websolutions.work<br>
                        <strong>Teléfono:</strong> +506 8806 1829<br>
                        <strong>Dirección:</strong> San José, Costa Rica
                    </p>

                    <p class="mt-8 text-sm text-gray-400">Última actualización: {{ date('F d, Y') }}</p>
                    
                    <div class="mt-10 text-center">
                        <a href="/" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Volver al Inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

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
                </p>
            </div>
        </div>
    </footer>

    <script>
        // Forzar tema oscuro para consistencia
        document.documentElement.classList.add('dark');
    </script>
</body>
</html>
