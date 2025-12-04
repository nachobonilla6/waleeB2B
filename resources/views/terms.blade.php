<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Términos y Condiciones - Web Solutions CR</title>
    <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/512/2115/2115955.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            <div class="absolute top-40 right-10 w-72 h-72 bg-blue-500 rounded-full mix-blend-multiply filter blur-xl" style="animation-delay: 2s;"></div>
            <div class="absolute -bottom-8 left-1/2 w-72 h-72 bg-pink-500 rounded-full mix-blend-multiply filter blur-xl" style="animation-delay: 4s;"></div>
        </div>
        <div class="container mx-auto px-6 relative z-10 text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 bg-gradient-to-r from-white to-blue-100 bg-clip-text text-transparent">
                Términos y Condiciones
            </h1>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                Conoce los términos que rigen el uso de nuestros servicios
            </p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow py-12 px-4">
        <div class="container mx-auto max-w-4xl">
            <div class="glass rounded-2xl p-8 md:p-12 backdrop-blur-sm">
                <div class="prose max-w-none">
                    <h1>Términos y Condiciones de Uso</h1>
                    <p class="text-gray-400 mb-8">Última actualización: {{ date('F d, Y') }}</p>
                    <p class="text-gray-400">Al utilizar nuestra aplicación de integración con Facebook, aceptas estos términos en su totalidad.</p>

                    <h2>1. Definiciones</h2>
                    <ul class="space-y-2">
                        <li><strong>"Aplicación"</strong> se refiere al servicio de integración entre n8n y Facebook proporcionado por Web Solutions CR.</li>
                        <li><strong>"Usuario"</strong> es cualquier persona que acceda o utilice la Aplicación.</li>
                        <li><strong>"Datos"</strong> se refiere a cualquier información procesada a través de la Aplicación.</li>
                        <li><strong>"Facebook"</strong> se refiere a Facebook, Inc. y sus afiliados, incluyendo Instagram.</li>
                        <li><strong>"Contenido"</strong> se refiere a cualquier texto, gráficos, imágenes y otro material disponible a través de la Aplicación.</li>
                    </ul>

                    <h2>2. Uso Aceptable</h2>
                    <p>Al utilizar nuestra Aplicación, aceptas:</p>
                    <ul>
                        <li>Utilizar la Aplicación solo para fines legítimos y de acuerdo con estos Términos y las leyes aplicables.</li>
                        <li>No utilizar la Aplicación para actividades ilegales, engañosas, maliciosas o que infrinjan derechos de terceros.</li>
                        <li>No interferir ni interrumpir la seguridad o funcionalidad de la Aplicación o servidores y redes conectadas a la misma.</li>
                        <li>Cumplir con todas las políticas de Facebook, incluyendo sus Términos de Servicio y Directrices para Desarrolladores.</li>
                        <li>No realizar ingeniería inversa, descompilar o desensamblar cualquier parte de la Aplicación.</li>
                    </ul>

                    <h2>3. Cuentas y Seguridad</h2>
                    <p>Para utilizar nuestra Aplicación, debes:</p>
                    <ul>
                        <li>Proporcionar información precisa y completa durante el registro.</li>
                        <li>Mantener la confidencialidad de tus credenciales de acceso.</li>
                        <li>Notificarnos inmediatamente de cualquier violación de seguridad o uso no autorizado de tu cuenta.</li>
                        <li>Ser mayor de 18 años o tener la edad legal requerida en tu jurisdicción.</li>
                        <li>No crear cuentas de forma automatizada ni utilizar cuentas de otros usuarios sin autorización.</li>
                    </ul>

                    <h2>4. Propiedad Intelectual</h2>
                    <p>Todos los derechos de propiedad intelectual relacionados con la Aplicación son propiedad exclusiva de Web Solutions CR. Concedemos un acceso limitado, no exclusivo, intransferible y revocable a la Aplicación, sujeto a estos Términos. No se otorgan licencias implícitas.</p>
                    
                    <p>El contenido de la Aplicación está protegido por derechos de autor, marcas comerciales y otras leyes de propiedad intelectual. No puedes modificar, copiar, distribuir, transmitir, mostrar, realizar, reproducir, publicar, licenciar, crear trabajos derivados, transferir o vender cualquier información, software, productos o servicios obtenidos de la Aplicación sin nuestro permiso por escrito.</p>

                    <h2>5. Limitación de Responsabilidad</h2>
                    <p>En la máxima medida permitida por la ley, Web Solutions CR no será responsable por:</p>
                    <ul>
                        <li>Daños directos, indirectos, incidentales, especiales, consecuentes o ejemplares.</li>
                        <li>Pérdida de beneficios, datos, uso, fondo de comercio u otras pérdidas intangibles.</li>
                        <li>Cualquier interrupción o retraso en el acceso o uso de la Aplicación.</li>
                        <li>La exactitud, integridad o actualidad de cualquier información proporcionada a través de la Aplicación.</li>
                        <li>Daños resultantes de la interrupción, suspensión o terminación de la Aplicación.</li>
                    </ul>

                    <h2>6. Exclusión de Garantías</h2>
                    <p>La Aplicación se proporciona "tal cual" y "según disponibilidad", sin garantías de ningún tipo, ya sean expresas o implícitas, incluidas, entre otras, las garantías implícitas de comerciabilidad, idoneidad para un propósito particular y no infracción.</p>

                    <h2>7. Indemnización</h2>
                    <p>Aceptas indemnizar y eximir de responsabilidad a Web Solutions CR, sus afiliados, funcionarios, empleados, agentes y licenciantes de y contra cualquier reclamo, daño, obligación, pérdida, responsabilidad, costo o deuda, y gastos que surjan de:</p>
                    <ul>
                        <li>Tu uso y acceso a la Aplicación.</li>
                        <li>Tu violación de estos Términos.</li>
                        <li>Tu violación de los derechos de terceros, incluyendo derechos de propiedad intelectual.</li>
                        <li>Cualquier reclamación de que tu Contenido causó daño a un tercero.</li>
                    </ul>

                    <h2>8. Terminación</h2>
                    <p>Podemos suspender o terminar tu acceso a la Aplicación en cualquier momento, con o sin causa, con o sin previo aviso, lo que puede resultar en la pérdida de información asociada con tu cuenta. Todas las disposiciones de estos Términos que por su naturaleza deban sobrevivir a la terminación sobrevivirán, incluidas, entre otras, las disposiciones de propiedad, exenciones de garantía, indemnización y limitaciones de responsabilidad.</p>

                    <h2>9. Ley Aplicable</h2>
                    <p>Estos Términos se regirán e interpretarán de acuerdo con las leyes de Costa Rica, sin tener en cuenta sus disposiciones sobre conflictos de leyes. Cualquier disputa relacionada con estos Términos estará sujeta a la jurisdicción exclusiva de los tribunales de Costa Rica.</p>

                    <h2>10. Cumplimiento de Políticas de Facebook</h2>
                    <p>Al utilizar nuestra Aplicación con Facebook, aceptas cumplir con todas las políticas aplicables de Facebook, incluyendo pero no limitado a:</p>
                    <ul>
                        <li>Políticas de la Plataforma Facebook</li>
                        <li>Políticas de Publicidad de Facebook</li>
                        <li>Políticas de Datos de Facebook</li>
                        <li>Términos del Servicio de Facebook</li>
                    </ul>
                    <p>Nos reservamos el derecho de modificar o descontinuar el acceso a la API de Facebook en cualquier momento para garantizar el cumplimiento de las políticas de Facebook.</p>

                    <h2>11. Cambios en los Términos</h2>
                    <p>Nos reservamos el derecho de modificar estos Términos en cualquier momento. Publicaremos los cambios en esta página y actualizaremos la fecha de "Última actualización". El uso continuado de la Aplicación después de dichos cambios constituye tu aceptación de los nuevos Términos.</p>

                    <h2>12. Contacto</h2>
                    <p>Si tienes alguna pregunta sobre estos Términos, contáctanos en:</p>
                    <p class="bg-gray-800 p-4 rounded-lg inline-block">
                        <strong>Email:</strong> info@websolutions.work<br>
                        <strong>Teléfono:</strong> +506 8806 1829<br>
                        <strong>Dirección:</strong> San José, Costa Rica
                    </p>

                    <p class="mt-8 text-sm text-gray-400">Al utilizar nuestra Aplicación, confirmas que has leído, comprendido y aceptado estos Términos y nuestra Política de Privacidad.</p>
                    
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
            </div>
        </div>
    </footer>

    <script>
        // Forzar tema oscuro para consistencia
        document.documentElement.classList.add('dark');
    </script>
</body>
</html>
