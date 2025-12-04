<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plataforma Educativa | Web Solutions CR</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            @apply bg-gradient-to-br from-gray-50 to-indigo-50 text-gray-800;
        }
        .hero-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .progress-bar {
            height: 8px;
            @apply bg-gray-200 rounded-full overflow-hidden;
        }
        .progress-fill {
            height: 100%;
            @apply bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full;
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Navbar -->
    <nav class="bg-white/80 backdrop-blur-md shadow-sm fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="#" class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                        <i class="fas fa-graduation-cap mr-2"></i>EduTech
                    </a>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#inicio" class="text-gray-700 hover:text-indigo-600 px-3 py-2 font-medium">Inicio</a>
                    <a href="#cursos" class="text-gray-700 hover:text-indigo-600 px-3 py-2 font-medium">Cursos</a>
                    <a href="#progreso" class="text-gray-700 hover:text-indigo-600 px-3 py-2 font-medium">Mi Progreso</a>
                    <a href="#" class="bg-indigo-600 text-white px-6 py-2 rounded-full hover:bg-indigo-700 transition-all">
                        Iniciar Sesión
                    </a>
                </div>
                <div class="md:hidden flex items-center">
                    <button class="text-gray-700">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="inicio" class="pt-32 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                    Aprende <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600">desarrollo web</span> 
                    <br>de manera interactiva
                </h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-10">
                    Domina las habilidades más demandadas en la industria tecnológica con nuestros cursos prácticos y proyectos del mundo real.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="#cursos" class="bg-indigo-600 text-white px-8 py-4 rounded-full text-lg font-semibold hover:bg-indigo-700 transition-all transform hover:-translate-y-1">
                        Explorar Cursos
                    </a>
                    <a href="#" class="border-2 border-indigo-600 text-indigo-600 px-8 py-4 rounded-full text-lg font-semibold hover:bg-indigo-50 transition-all">
                        Ver Demo
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Cursos Destacados -->
    <section id="cursos" class="py-20 bg-white/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Nuestros Cursos Populares</h2>
                <div class="w-20 h-1 bg-indigo-600 mx-auto"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Curso 1 -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden card-hover">
                    <div class="h-48 bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center">
                        <i class="fab fa-html5 text-white text-7xl"></i>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-3">
                            <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded">Principiante</span>
                            <div class="flex items-center text-yellow-400">
                                <i class="fas fa-star"></i>
                                <span class="ml-1 text-gray-600">4.9 (128)</span>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">HTML5 y CSS3 Moderno</h3>
                        <p class="text-gray-600 mb-4">Aprende a crear sitios web modernos y responsivos con las últimas características de HTML5 y CSS3.</p>
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <i class="far fa-clock text-gray-500 mr-1"></i>
                                <span class="text-sm text-gray-500">12 horas</span>
                            </div>
                            <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">Ver más →</a>
                        </div>
                    </div>
                </div>

                <!-- Curso 2 -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden card-hover">
                    <div class="h-48 bg-gradient-to-r from-yellow-500 to-orange-500 flex items-center justify-center">
                        <i class="fab fa-js text-white text-7xl"></i>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-3">
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Intermedio</span>
                            <div class="flex items-center text-yellow-400">
                                <i class="fas fa-star"></i>
                                <span class="ml-1 text-gray-600">4.8 (95)</span>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">JavaScript Moderno ES6+</h3>
                        <p class="text-gray-600 mb-4">Domina JavaScript moderno con ES6+ y lleva tus habilidades de desarrollo al siguiente nivel.</p>
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <i class="far fa-clock text-gray-500 mr-1"></i>
                                <span class="text-sm text-gray-500">18 horas</span>
                            </div>
                            <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">Ver más →</a>
                        </div>
                    </div>
                </div>

                <!-- Curso 3 -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden card-hover">
                    <div class="h-48 bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center">
                        <i class="fab fa-react text-white text-7xl"></i>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-3">
                            <span class="bg-pink-100 text-pink-800 text-xs font-medium px-2.5 py-0.5 rounded">Avanzado</span>
                            <div class="flex items-center text-yellow-400">
                                <i class="fas fa-star"></i>
                                <span class="ml-1 text-gray-600">4.9 (142)</span>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">React.js Profesional</h3>
                        <p class="text-gray-600 mb-4">Construye aplicaciones web modernas con React, Redux y las mejores prácticas de la industria.</p>
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <i class="far fa-clock text-gray-500 mr-1"></i>
                                <span class="text-sm text-gray-500">24 horas</span>
                            </div>
                            <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">Ver más →</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-12">
                <a href="#" class="inline-block border-2 border-indigo-600 text-indigo-600 px-8 py-3 rounded-full text-lg font-semibold hover:bg-indigo-50 transition-all">
                    Ver todos los cursos
                </a>
            </div>
        </div>
    </section>

    <!-- Progreso del Estudiante -->
    <section id="progreso" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Mi Progreso de Aprendizaje</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Sigue tu avance y completa los cursos para obtener certificados de finalización.</p>
            </div>

            <div class="bg-white rounded-xl shadow-md p-8 mb-12">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Tus Cursos en Progreso</h3>
                
                <!-- Curso en Progreso 1 -->
                <div class="border-b border-gray-200 pb-6 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-4">
                        <div class="mb-4 md:mb-0">
                            <h4 class="font-semibold text-lg">JavaScript Moderno ES6+</h4>
                            <p class="text-gray-600 text-sm">Completado al 65%</p>
                        </div>
                        <div class="w-full md:w-1/2">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 65%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500 mt-1">
                                <span>11 de 17 lecciones</span>
                                <span>65%</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                            Continuar Aprendiendo
                        </button>
                    </div>
                </div>

                <!-- Curso en Progreso 2 -->
                <div class="border-b border-gray-200 pb-6 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-4">
                        <div class="mb-4 md:mb-0">
                            <h4 class="font-semibold text-lg">React.js Profesional</h4>
                            <p class="text-gray-600 text-sm">Completado al 30%</p>
                        </div>
                        <div class="w-full md:w-1/2">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 30%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500 mt-1">
                                <span>6 de 20 lecciones</span>
                                <span>30%</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                            Continuar Aprendiendo
                        </button>
                    </div>
                </div>
            </div>

            <!-- Lecciones Recientes -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white rounded-xl shadow-md p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Próximas Lecciones</h3>
                    <div class="space-y-4">
                        <div class="flex items-center p-4 rounded-lg border-2 border-blue-500 bg-blue-50">
                            <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 mr-4">
                                <i class="fas fa-play-circle text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">Módulo 4: Eventos en JavaScript</h4>
                                <p class="text-sm text-gray-500">JavaScript Moderno ES6+</p>
                            </div>
                        </div>
                        <div class="flex items-center p-4 rounded-lg border-2 border-gray-200 bg-gray-50">
                            <div class="flex-shrink-0 w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mr-4">
                                <i class="fas fa-lock text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-500">Módulo 3: Hooks Avanzados</h4>
                                <p class="text-sm text-gray-400">React.js Profesional</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Logros Recientes</h3>
                    <div class="space-y-4">
                        <div class="flex items-center p-4 rounded-lg bg-green-50 border border-green-200">
                            <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600 mr-4">
                                <i class="fas fa-trophy text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">¡Módulo Completado!</h4>
                                <p class="text-sm text-gray-600">Has completado "Fundamentos de JavaScript"</p>
                            </div>
                        </div>
                        <div class="flex items-center p-4 rounded-lg bg-purple-50 border border-purple-200">
                            <div class="flex-shrink-0 w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 mr-4">
                                <i class="fas fa-bolt text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">Racha de 7 días</h4>
                                <p class="text-sm text-gray-600">¡Sigue así para mantener tu racha!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">¿Listo para impulsar tu carrera?</h2>
            <p class="text-xl text-indigo-100 max-w-3xl mx-auto mb-10">
                Únete a miles de estudiantes que ya están aprendiendo y creciendo con nuestros cursos.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#" class="bg-white text-indigo-600 px-8 py-4 rounded-full text-lg font-semibold hover:bg-gray-100 transition-all transform hover:-translate-y-1">
                    Comenzar Ahora
                </a>
                <a href="#" class="border-2 border-white text-white px-8 py-4 rounded-full text-lg font-semibold hover:bg-white/10 transition-all">
                    Ver Planes
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-2xl font-bold text-white mb-4">EduTech</h3>
                    <p class="mb-4">Plataforma de aprendizaje en línea para desarrolladores web.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-github"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Cursos</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white">Desarrollo Web</a></li>
                        <li><a href="#" class="hover:text-white">JavaScript</a></li>
                        <li><a href="#" class="hover:text-white">React.js</a></li>
                        <li><a href="#" class="hover:text-white">Node.js</a></li>
                        <li><a href="#" class="hover:text-white">Bases de Datos</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Compañía</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white">Sobre Nosotros</a></li>
                        <li><a href="#" class="hover:text-white">Carreras</a></li>
                        <li><a href="#" class="hover:text-white">Blog</a></li>
                        <li><a href="#" class="hover:text-white">Afiliados</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Soporte</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white">Centro de Ayuda</a></li>
                        <li><a href="#" class="hover:text-white">Términos de Servicio</a></li>
                        <li><a href="#" class="hover:text-white">Política de Privacidad</a></li>
                        <li><a href="#" class="hover:text-white">Contacto</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-500 text-sm">
                <p>© 2025 EduTech. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>