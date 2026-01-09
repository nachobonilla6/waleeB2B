<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscribe - Walee B2B</title>
    <meta name="description" content="Subscribe to unlock premium features">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'walee': {
                            400: '#D59F3B',
                            500: '#D59F3B',
                            600: '#B8860B',
                        }
                    }
                }
            }
        }
        
        // Detectar y aplicar modo dark/light
        (function() {
            // Cargar preferencia guardada o usar la del sistema
            const darkMode = localStorage.getItem('darkMode') === 'true' || 
                (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches);
            
            if (darkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            
            // Escuchar cambios en la preferencia del sistema
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                if (!localStorage.getItem('darkMode')) {
                    if (e.matches) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
            });
        })();
    </script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white min-h-screen transition-colors duration-200">
    <!-- Background -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-400/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 -left-20 w-60 h-60 bg-walee-400/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative min-h-screen flex flex-col items-center justify-center px-4 py-12">
        <!-- Dark Mode Toggle Button -->
        <button onclick="toggleDarkMode()" class="fixed top-4 right-4 z-50 p-3 rounded-full bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 transition-colors shadow-lg" title="Toggle dark mode">
            <svg id="darkModeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
            <svg id="lightModeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </button>
        
        <div class="w-full max-w-4xl">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl sm:text-5xl font-bold text-slate-900 dark:text-white mb-4">
                    Subscribe for Premium Features
                </h1>
                <p class="text-lg text-slate-600 dark:text-slate-400">
                    Unlock access to all advanced features and tools
                </p>
            </div>

            <!-- Pricing Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <!-- Basic Plan -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 p-8 hover:shadow-xl transition-shadow">
                    <div class="text-center">
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Basic</h3>
                        <div class="mb-6">
                            <span class="text-4xl font-bold text-slate-900 dark:text-white">$29</span>
                            <span class="text-slate-600 dark:text-slate-400">/month</span>
                        </div>
                        <ul class="text-left space-y-3 mb-8">
                            <li class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Up to 50 suppliers</span>
                            </li>
                            <li class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Basic product management</span>
                            </li>
                            <li class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Email support</span>
                            </li>
                        </ul>
                        <button class="w-full px-6 py-3 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white rounded-lg font-medium transition-colors">
                            Get Started
                        </button>
                    </div>
                </div>

                <!-- Pro Plan (Featured) -->
                <div class="bg-gradient-to-br from-walee-500 to-walee-600 rounded-2xl shadow-xl border-2 border-walee-400 p-8 hover:shadow-2xl transition-shadow transform scale-105">
                    <div class="text-center">
                        <div class="inline-block px-3 py-1 bg-white/20 rounded-full text-white text-sm font-semibold mb-4">
                            Most Popular
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-2">Pro</h3>
                        <div class="mb-6">
                            <span class="text-4xl font-bold text-white">$79</span>
                            <span class="text-white/80">/month</span>
                        </div>
                        <ul class="text-left space-y-3 mb-8">
                            <li class="flex items-center gap-2 text-white">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Unlimited suppliers</span>
                            </li>
                            <li class="flex items-center gap-2 text-white">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Advanced product management</span>
                            </li>
                            <li class="flex items-center gap-2 text-white">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Appointments & Invoices</span>
                            </li>
                            <li class="flex items-center gap-2 text-white">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Quotes & Contracts</span>
                            </li>
                            <li class="flex items-center gap-2 text-white">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Priority support</span>
                            </li>
                        </ul>
                        <button class="w-full px-6 py-3 bg-white hover:bg-slate-100 text-walee-600 rounded-lg font-medium transition-colors shadow-lg">
                            Get Started
                        </button>
                    </div>
                </div>

                <!-- Enterprise Plan -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 p-8 hover:shadow-xl transition-shadow">
                    <div class="text-center">
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Enterprise</h3>
                        <div class="mb-6">
                            <span class="text-4xl font-bold text-slate-900 dark:text-white">Custom</span>
                        </div>
                        <ul class="text-left space-y-3 mb-8">
                            <li class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Everything in Pro</span>
                            </li>
                            <li class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Custom integrations</span>
                            </li>
                            <li class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Dedicated account manager</span>
                            </li>
                            <li class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>24/7 phone support</span>
                            </li>
                        </ul>
                        <button class="w-full px-6 py-3 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white rounded-lg font-medium transition-colors">
                            Contact Sales
                        </button>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 p-8 mb-12">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-6 text-center">All Plans Include</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 dark:bg-emerald-500/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900 dark:text-white mb-1">Secure Data Storage</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Your data is encrypted and securely stored</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 dark:bg-emerald-500/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900 dark:text-white mb-1">Regular Updates</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Get access to new features and improvements</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 dark:bg-emerald-500/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900 dark:text-white mb-1">Mobile Responsive</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Access your account from any device</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 dark:bg-emerald-500/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900 dark:text-white mb-1">Easy Cancellation</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Cancel anytime with no hidden fees</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 p-8">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-6 text-center">Frequently Asked Questions</h2>
                <div class="space-y-4">
                    <div class="border-b border-slate-200 dark:border-slate-700 pb-4">
                        <h3 class="font-semibold text-slate-900 dark:text-white mb-2">Can I change my plan later?</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Yes, you can upgrade or downgrade your plan at any time from your account settings.</p>
                    </div>
                    <div class="border-b border-slate-200 dark:border-slate-700 pb-4">
                        <h3 class="font-semibold text-slate-900 dark:text-white mb-2">What payment methods do you accept?</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400">We accept all major credit cards and PayPal.</p>
                    </div>
                    <div class="border-b border-slate-200 dark:border-slate-700 pb-4">
                        <h3 class="font-semibold text-slate-900 dark:text-white mb-2">Is there a free trial?</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Yes, all plans come with a 14-day free trial. No credit card required.</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-900 dark:text-white mb-2">How do I cancel my subscription?</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400">You can cancel your subscription at any time from your account settings. Your access will continue until the end of your billing period.</p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-12">
                <p class="text-slate-600 dark:text-slate-400 mb-4">
                    Need help? <a href="mailto:support@websolutions.work" class="text-walee-500 hover:text-walee-600 dark:text-walee-400 dark:hover:text-walee-300 underline">Contact our support team</a>
                </p>
                <a href="/" class="inline-flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Back to Home</span>
                </a>
            </div>
        </div>
    </div>
    
    <script>
        // Función para cambiar entre modo dark y light
        function toggleDarkMode() {
            const isDark = document.documentElement.classList.contains('dark');
            
            if (isDark) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('darkMode', 'false');
                updateDarkModeIcons(false);
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('darkMode', 'true');
                updateDarkModeIcons(true);
            }
        }
        
        // Actualizar iconos según el modo actual
        function updateDarkModeIcons(isDark) {
            const darkIcon = document.getElementById('darkModeIcon');
            const lightIcon = document.getElementById('lightModeIcon');
            
            if (isDark) {
                darkIcon.style.display = 'block';
                lightIcon.style.display = 'none';
            } else {
                darkIcon.style.display = 'none';
                lightIcon.style.display = 'block';
            }
        }
        
        // Inicializar iconos al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            const isDark = document.documentElement.classList.contains('dark');
            updateDarkModeIcons(isDark);
        });
    </script>
</body>
</html>

