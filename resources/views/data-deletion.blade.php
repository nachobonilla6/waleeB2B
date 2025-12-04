<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminación de Datos - Web Solutions CR</title>
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
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 1.5rem;
        }
        
        .prose h2 {
            font-size: 1.5rem;
            line-height: 2rem;
            font-weight: 600;
            color: #e5e7eb;
            margin-top: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .prose p, .prose ul, .prose ol {
            margin-bottom: 1.25rem;
        }
        
        .prose a {
            color: #60a5fa;
            text-decoration: none;
            transition: color 0.2s;
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
            <div class="absolute top-40 right-20 w-72 h-72 bg-blue-500 rounded-full mix-blend-multiply filter blur-xl animate-pulse animation-delay-2000"></div>
            <div class="absolute bottom-20 left-1/2 w-72 h-72 bg-indigo-500 rounded-full mix-blend-multiply filter blur-xl animate-pulse animation-delay-4000"></div>
        </div>
        <div class="container mx-auto px-4 text-center relative z-10">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Eliminación de Datos</h1>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto">Solicita la eliminación de tus datos personales de nuestra plataforma</p>
        </div>
    </header>

    <main class="flex-grow py-12 px-4">
        <div class="container mx-auto max-w-4xl">
            <div class="glass rounded-2xl p-8 md:p-12 backdrop-blur-sm">
                <div class="prose max-w-none">
                    <h1>Solicitud de Eliminación de Datos</h1>
                    <p class="text-gray-400 mb-8">Última actualización: {{ date('F d, Y') }}</p>

                    <div class="bg-blue-900/20 border-l-4 border-blue-500 p-4 rounded mb-8">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h2a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-100">
                                    Para solicitar la eliminación de tus datos personales, por favor envíanos un correo electrónico a <strong>privacy@websolutions.work</strong> desde la dirección de correo asociada a tu cuenta.
                                </p>
                            </div>
                        </div>
                    </div>

                    <h2>¿Qué necesitas incluir en tu solicitud?</h2>
                    <p>Para procesar tu solicitud de manera eficiente, por favor incluye la siguiente información en tu correo:</p>
                    
                    <ul>
                        <li>Asunto: <strong>Solicitud de Eliminación de Datos</strong></li>
                        <li>Nombre completo</li>
                        <li>Dirección de correo electrónico asociada a tu cuenta</li>
                        <li>Breve descripción de los datos que deseas eliminar</li>
                    </ul>

                    <h2>¿Qué sucede después?</h2>
                    <p>Una vez que recibamos tu solicitud:</p>
                    <ol>
                        <li>Te enviaremos un correo de confirmación de recepción en un plazo de 24-48 horas.</li>
                        <li>Verificaremos tu identidad para proteger tu información.</li>
                        <li>Procesaremos tu solicitud en un plazo máximo de 30 días.</li>
                        <li>Recibirás una notificación cuando se haya completado la eliminación.</li>
                    </ol>

                    <div class="mt-8 bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 p-4 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h2a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700 dark:text-blue-300">
                                    <strong>Importante:</strong> Si tienes una cuenta con nosotros, te recomendamos iniciar sesión para realizar la solicitud de manera más segura.
                                </p>
                            </div>
                        </div>
                    </div>

                    <h2>¿Qué datos se eliminarán?</h2>
                    <p>Al solicitar la eliminación de tus datos, se eliminará permanentemente la siguiente información asociada a tu cuenta:</p>
                    
                    <ul>
                        <li>Información de perfil (nombre, correo electrónico, foto, etc.)</li>
                        <li>Historial de actividad en la plataforma</li>
                        <li>Preferencias y configuraciones de usuario</li>
                        <li>Cualquier otro dato personal que hayas proporcionado</li>
                    </ul>

                    <div class="bg-yellow-900/20 border-l-4 border-yellow-500 p-4 rounded my-8">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-200">Importante</h3>
                                <div class="mt-2 text-sm text-yellow-100">
                                    <p>La eliminación de datos es un proceso irreversible. Una vez completado, no podremos recuperar tu información.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h2>¿Tienes preguntas?</h2>
                    <p>Si tienes alguna pregunta sobre cómo manejamos tus datos o sobre el proceso de eliminación, no dudes en contactarnos a <a href="mailto:privacy@websolutions.work" class="text-blue-400 hover:underline">privacy@websolutions.work</a>.</p>
                    </div>

                    <div class="mt-12 border-t border-gray-700 pt-8">
                        <h2>Preguntas frecuentes</h2>
                        
                        <div class="mt-6 space-y-6">
                            <div>
                                <h3 class="text-lg font-medium">¿Cuánto tiempo tarda en procesarse mi solicitud?</h3>
                                <p class="text-gray-400 mt-2">Procesamos las solicitudes de eliminación dentro de los 30 días siguientes a la recepción. Recibirás una confirmación por correo electrónico una vez completado el proceso.</p>
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-medium">¿Se eliminarán todos mis datos de inmediato?</h3>
                                <p class="text-gray-400 mt-2">La mayoría de los datos se eliminarán de inmediato, pero algunos datos pueden permanecer en nuestras copias de seguridad durante un período de hasta 90 días antes de ser eliminados permanentemente, según lo permitido por la ley.</p>
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-medium">¿Qué información debo incluir en mi correo?</h3>
                                <p class="text-gray-400 mt-2">Por favor, incluye tu nombre completo, dirección de correo electrónico asociada a la cuenta y una breve descripción de los datos que deseas eliminar. Esto nos ayudará a procesar tu solicitud de manera más eficiente.</p>
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-medium">¿Cómo sé que mi solicitud ha sido recibida?</h3>
                                <p class="text-gray-400 mt-2">Te enviaremos un correo de confirmación dentro de las 24-48 horas posteriores a la recepción de tu solicitud. Si no recibes esta confirmación, por favor revisa tu carpeta de correo no deseado o contáctanos.</p>
                            </div>
                        </div>
                    </div>
                </div>
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
            </div>
        </div>
    </footer>

    <script>
        // Toggle FAQ items
        document.querySelectorAll('button[aria-controls^="faq-"]').forEach(button => {
            button.addEventListener('click', () => {
                const isExpanded = button.getAttribute('aria-expanded') === 'true';
                const targetId = button.getAttribute('aria-controls');
                const target = document.getElementById(targetId);
                
                // Toggle visibility
                button.setAttribute('aria-expanded', !isExpanded);
                target.classList.toggle('hidden');
                
                // Toggle icon
                const icon = button.querySelector('svg');
                if (isExpanded) {
                    icon.classList.remove('rotate-45');
                } else {
                    icon.classList.add('rotate-45');
                }
            });
        });

        // Form submission confirmation
        document.getElementById('deleteForm')?.addEventListener('submit', function(e) {
            if (!confirm('¿Estás seguro de que deseas eliminar permanentemente tu cuenta y todos tus datos? Esta acción no se puede deshacer.')) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>