<!DOCTYPE html>
<html lang="en" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Supplier Profile - {{ $supplier->name ?? 'Supplier' }}</title>
    <meta name="description" content="Supplier Profile - Edit your information">
    <meta name="theme-color" content="#D59F3B">
    @include('partials.walee-dark-mode-init')
    @include('partials.walee-violet-light-mode')
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        walee: {
                            400: '#D59F3B',
                            500: '#C78F2E',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        * {
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white min-h-screen transition-colors duration-200">
    @php
        // Obtener teléfono para WhatsApp
        $phone = $supplier->telefono_1 ?: $supplier->telefono_2 ?: $supplier->phone;
        $cleanPhone = $phone ? preg_replace('/[^0-9+]/', '', $phone) : null;
        $whatsappLink = $cleanPhone;
        
        // Obtener foto
        $fotoPath = $supplier->foto ?? null;
        $fotoUrl = null;
        if ($fotoPath) {
            if (\Illuminate\Support\Str::startsWith($fotoPath, ['http://', 'https://'])) {
                $fotoUrl = $fotoPath;
            } else {
                $filename = basename($fotoPath);
                $fotoUrl = route('storage.clientes', ['filename' => $filename]);
            }
        }
        
        // Obtener bandera del país
        $getCountryFlag = function($countryName) {
            if (empty($countryName)) return null;
            $countryFlags = [
                'costa rica' => '🇨🇷', 'mexico' => '🇲🇽', 'méxico' => '🇲🇽', 'usa' => '🇺🇸',
                'united states' => '🇺🇸', 'estados unidos' => '🇺🇸', 'spain' => '🇪🇸', 'españa' => '🇪🇸',
                'colombia' => '🇨🇴', 'argentina' => '🇦🇷', 'chile' => '🇨🇱', 'peru' => '🇵🇪', 'perú' => '🇵🇪',
                'ecuador' => '🇪🇨', 'venezuela' => '🇻🇪', 'guatemala' => '🇬🇹', 'panama' => '🇵🇦', 'panamá' => '🇵🇦',
                'nicaragua' => '🇳🇮', 'honduras' => '🇭🇳', 'el salvador' => '🇸🇻', 'belize' => '🇧🇿',
                'brazil' => '🇧🇷', 'brasil' => '🇧🇷', 'canada' => '🇨🇦', 'canadá' => '🇨🇦',
                'france' => '🇫🇷', 'francia' => '🇫🇷', 'germany' => '🇩🇪', 'alemania' => '🇩🇪',
                'italy' => '🇮🇹', 'italia' => '🇮🇹', 'portugal' => '🇵🇹',
                'united kingdom' => '🇬🇧', 'reino unido' => '🇬🇧', 'uk' => '🇬🇧',
            ];
            $countryLower = strtolower(trim($countryName));
            return $countryFlags[$countryLower] ?? null;
        };
        $pais = $supplier->fiscal_country ?? null;
        $bandera = $pais ? $getCountryFlag($pais) : null;
        
        // Obtener productos del supplier con URLs de imágenes
        $productos = \App\Models\ProductoSuper::where('cliente_id', $supplier->id)->orderBy('created_at', 'desc')->get();
        $productos = $productos->map(function($producto) {
            $producto->imagen_url = $producto->imagen ? asset('storage/' . $producto->imagen) : null;
            return $producto;
        });
    @endphp

    <div class="min-h-screen relative flex flex-col">
        <!-- Background -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-400/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 -left-20 w-60 h-60 bg-walee-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-4xl mx-auto px-4 py-6 sm:px-6 lg:px-8 w-full">
            @if(!session('supplier_public_authenticated_' . $supplier->id))
                <!-- Access Code Form -->
                <div class="max-w-md mx-auto mt-20">
                    <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-8 shadow-lg dark:shadow-none">
                        <div class="text-center mb-8">
                            <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">Supplier Profile</h1>
                            <p class="text-slate-600 dark:text-slate-400">Enter access code to edit your profile</p>
                        </div>
                        
                        <form id="accessCodeForm" class="space-y-6">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Access Code</label>
                                <input type="password" id="accessCode" name="code" required
                                       class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-500 focus:border-transparent text-center text-2xl tracking-widest"
                                       placeholder="••••" maxlength="4" autocomplete="off">
                            </div>
                            
                            <button type="submit" 
                                    class="w-full px-4 py-3 bg-gradient-to-r from-walee-500 to-walee-600 hover:from-walee-600 hover:to-walee-700 text-white font-semibold rounded-xl shadow-lg shadow-walee-500/30 hover:shadow-xl hover:shadow-walee-500/40 transition-all duration-300 transform hover:scale-105 active:scale-95">
                                Access Profile
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <!-- Supplier Profile -->
                <div class="bg-white dark:bg-slate-900/60 rounded-2xl lg:rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-none p-4 sm:p-5">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 mb-4">
                        <div class="flex items-center gap-4 flex-1">
                            <!-- Foto -->
                            <div class="relative w-20 h-20 sm:w-24 sm:h-24 rounded-xl overflow-hidden border-2 border-emerald-500/30 shadow-sm flex-shrink-0">
                                @if($fotoUrl)
                                    <img src="{{ $fotoUrl }}" alt="{{ $supplier->name }}" class="w-full h-full object-cover">
                                @else
                                    <img src="https://images.icon-icons.com/1188/PNG/512/1490201150-client_82317.png" alt="{{ $supplier->name }}" class="w-full h-full object-cover opacity-80">
                                @endif
                            </div>
                            
                            <!-- Nombre y Info -->
                            <div class="flex-1 min-w-0">
                                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white mb-2">{{ $supplier->name }}</h1>
                                
                                @if($bandera || $pais)
                                    <div class="flex items-center gap-1.5 mb-1">
                                        @if($bandera)
                                            <span class="text-lg">{{ $bandera }}</span>
                                        @endif
                                        @if($pais)
                                            <span class="text-sm text-slate-600 dark:text-slate-400">{{ $pais }}</span>
                                        @endif
                                    </div>
                                @endif
                                
                                @if($supplier->ciudad)
                                    <div class="flex items-center gap-1.5 text-sm text-slate-600 dark:text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span>{{ $supplier->ciudad }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
                        <!-- Note Button -->
                        <button onclick="openNotaModal()" class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl bg-violet-100 dark:bg-violet-500/20 hover:bg-violet-200 dark:hover:bg-violet-500/30 text-violet-600 dark:text-violet-400 border border-violet-600 dark:border-violet-500/30 transition-all group shadow-sm">
                            <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="text-sm font-semibold">Note</span>
                        </button>
        
                        <!-- Email Button -->
                        <button onclick="openEmailModal()" class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl bg-amber-100 dark:bg-amber-500/20 hover:bg-amber-200 dark:hover:bg-amber-500/30 text-amber-600 dark:text-amber-400 border border-amber-600 dark:border-amber-500/30 transition-all group shadow-sm">
                            <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-sm font-semibold">Email</span>
                        </button>
                        
                        <!-- Facebook Button -->
                        @if($supplier->facebook)
                            <a href="{{ $supplier->facebook }}" target="_blank" rel="noopener noreferrer" class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl bg-violet-100 dark:bg-violet-500/20 hover:bg-violet-200 dark:hover:bg-violet-500/30 text-violet-600 dark:text-violet-400 border border-violet-600 dark:border-violet-500/30 transition-all group shadow-sm">
                                <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                <span class="text-sm font-semibold">Facebook</span>
                            </a>
                        @else
                            <div class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl bg-slate-100 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 opacity-50 cursor-not-allowed">
                                <svg class="w-6 h-6 text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                <span class="text-sm font-semibold text-slate-400">Facebook</span>
                            </div>
                        @endif
            
                        <!-- WhatsApp Button -->
                        <button onclick="openWhatsAppModal()" 
                                class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 hover:bg-emerald-200 dark:hover:bg-emerald-500/30 text-emerald-600 dark:text-emerald-400 border border-emerald-600 dark:border-emerald-500/30 transition-all group shadow-sm {{ !$whatsappLink ? 'opacity-60 cursor-not-allowed' : '' }}"
                                {{ !$whatsappLink ? 'disabled' : '' }}>
                            <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            <span class="text-sm font-semibold">WhatsApp</span>
                        </button>
                    </div>
                    
                    <!-- Subscribe Section - Subtle -->
                    <div class="pt-2 pb-1">
                        <div class="text-center">
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">
                                Subscribe for more features
                            </p>
                            <a href="https://websolutions.work/suscribe" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 text-xs text-walee-500 hover:text-walee-600 dark:text-walee-400 dark:hover:text-walee-300 transition-colors underline decoration-dotted underline-offset-2">
                                <span>websolutions.work/suscribe</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Edit Profile Form -->
                    <div class="border-t border-slate-200 dark:border-slate-700 pt-4">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-xl font-bold text-slate-900 dark:text-white">Edit Profile</h2>
                            <div class="flex items-center gap-2">
                                <button onclick="openListOfProductsModal()" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white rounded-lg font-medium transition-colors">
                                    List of Products
                                </button>
                                <button onclick="openAddProductModal()" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg font-medium transition-colors">
                                    Add Product
                                </button>
                                <button onclick="openEditProfileModal()" class="px-4 py-2 bg-walee-500 hover:bg-walee-600 text-white rounded-lg font-medium transition-colors">
                                    Edit
                                </button>
                            </div>
                        </div>
                        
                        <!-- Profile Info Display -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Name</label>
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $supplier->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Email</label>
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $supplier->email ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Phone 1</label>
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $supplier->telefono_1 ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Phone 2</label>
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $supplier->telefono_2 ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Address</label>
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $supplier->address ?? $supplier->direccion ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Company Contact</label>
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $supplier->contacto_empresa ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <script>
        // Access Code Form
        document.getElementById('accessCodeForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            const code = document.getElementById('accessCode').value;
            
            try {
                const response = await fetch('{{ route("walee.supplier.public.authenticate", $supplier->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ code: code })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    window.location.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Code',
                        text: data.message || 'The access code is incorrect',
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred. Please try again.',
                    confirmButtonColor: '#ef4444'
                });
            }
        });
        
        // Note Modal
        function openNotaModal() {
            const nota = @json($supplier->nota ?? '');
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            Swal.fire({
                title: 'Note',
                html: `
                    <textarea id="notaText" rows="8" class="w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-500" placeholder="Add a note...">${nota}</textarea>
                `,
                showCancelButton: true,
                confirmButtonText: 'Save',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#8b5cf6',
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html'
                },
                didOpen: () => {
                    const popup = Swal.getPopup();
                    if (isDarkMode) {
                        popup.style.backgroundColor = '#0f172a';
                        popup.style.color = '#e2e8f0';
                    }
                },
                preConfirm: () => {
                    return document.getElementById('notaText').value;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    saveNota(result.value);
                }
            });
        }
        
        // Save Note
        async function saveNota(nota) {
            try {
                const response = await fetch('{{ route("walee.supplier.public.update", $supplier->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ nota: nota })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Note saved successfully',
                        confirmButtonColor: '#8b5cf6',
                        timer: 2000
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to save note',
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred. Please try again.',
                    confirmButtonColor: '#ef4444'
                });
            }
        }
        
        // Email Modal
        function openEmailModal() {
            const email = @json($supplier->email ?? '');
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            Swal.fire({
                title: 'Send Email',
                html: `
                    <div class="space-y-4 text-left">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">To</label>
                            <input type="email" id="emailTo" value="${email}" class="w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Subject</label>
                            <input type="text" id="emailSubject" class="w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Message</label>
                            <textarea id="emailMessage" rows="6" class="w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500"></textarea>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Send',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#f59e0b',
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html'
                },
                didOpen: () => {
                    const popup = Swal.getPopup();
                    if (isDarkMode) {
                        popup.style.backgroundColor = '#0f172a';
                        popup.style.color = '#e2e8f0';
                    }
                },
                preConfirm: () => {
                    const subject = document.getElementById('emailSubject').value;
                    const message = document.getElementById('emailMessage').value;
                    if (!subject || !message) {
                        Swal.showValidationMessage('Subject and message are required');
                        return false;
                    }
                    return { subject, message };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `mailto:${email}?subject=${encodeURIComponent(result.value.subject)}&body=${encodeURIComponent(result.value.message)}`;
                }
            });
        }
        
        // List of Products Modal
        function openListOfProductsModal() {
            const productos = @json($productos);
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            if (!productos || productos.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'No Products',
                    text: 'No products found for this supplier',
                    confirmButtonColor: '#D59F3B'
                });
                return;
            }
            
            let productsHtml = '<div class="max-h-96 overflow-y-auto space-y-2 text-left">';
            productos.forEach((producto, index) => {
                const imagenUrl = producto.imagen_url || 'https://via.placeholder.com/80x80?text=No+Image';
                productsHtml += `
                    <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-2 ${isDarkMode ? 'bg-slate-800/50' : 'bg-slate-50'}">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0">
                                <img src="${imagenUrl}" alt="${producto.nombre || 'Product'}" 
                                     class="w-16 h-16 object-cover rounded-lg border border-slate-300 dark:border-slate-600">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-sm text-slate-900 dark:text-white mb-0.5 truncate">${producto.nombre || 'Unnamed Product'}</h3>
                                <div class="flex flex-wrap gap-2 text-xs text-slate-600 dark:text-slate-400">
                                    ${producto.categoria ? `<span>${producto.categoria}</span>` : ''}
                                    ${producto.precio !== undefined ? `<span>$${parseFloat(producto.precio).toFixed(2)}</span>` : ''}
                                    ${producto.stock !== undefined ? `<span>Stock: ${producto.stock}</span>` : ''}
                                    ${producto.fecha_expiracion ? `<span>Exp: ${new Date(producto.fecha_expiracion).toLocaleDateString()}</span>` : ''}
                                </div>
                                ${producto.activo !== undefined ? `<span class="inline-block mt-1 px-1.5 py-0.5 text-xs rounded ${producto.activo ? 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300'}">${producto.activo ? 'Active' : 'Inactive'}</span>` : ''}
                            </div>
                            <div class="flex-shrink-0 flex gap-1">
                                <button onclick="editProductFromModal(${producto.id})" 
                                        class="p-1.5 text-walee-500 hover:bg-walee-500/10 rounded transition-colors" 
                                        title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button onclick="deleteProductFromModal(${producto.id})" 
                                        class="p-1.5 text-red-500 hover:bg-red-500/10 rounded transition-colors" 
                                        title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
            productsHtml += '</div>';
            
            Swal.fire({
                title: `Products (${productos.length})`,
                html: productsHtml,
                width: '700px',
                padding: '1rem',
                showConfirmButton: true,
                confirmButtonText: 'Close',
                confirmButtonColor: '#D59F3B',
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html'
                },
                didOpen: () => {
                    const popup = Swal.getPopup();
                    if (isDarkMode) {
                        popup.style.backgroundColor = '#0f172a';
                        popup.style.color = '#e2e8f0';
                    }
                }
            });
        }
        
        // Edit Product from Modal
        function editProductFromModal(productId) {
            Swal.close();
            const productos = @json($productos);
            const producto = productos.find(p => p.id === productId);
            
            if (!producto) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Product not found',
                    confirmButtonColor: '#ef4444'
                });
                return;
            }
            
            openEditProductModal(producto);
        }
        
        // Delete Product from Modal
        async function deleteProductFromModal(productId) {
            const result = await Swal.fire({
                title: 'Delete Product?',
                text: 'This action cannot be undone',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                reverseButtons: true
            });
            
            if (!result.isConfirmed) return;
            
            try {
                const response = await fetch(`{{ route("walee.supplier.public.delete-product", $supplier->id) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ product_id: productId })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted',
                        text: 'Product deleted successfully',
                        confirmButtonColor: '#10b981',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to delete product',
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred. Please try again.',
                    confirmButtonColor: '#ef4444'
                });
            }
        }
        
        // Edit Product Modal
        function openEditProductModal(producto) {
            const isDarkMode = document.documentElement.classList.contains('dark');
            const supplierId = @json($supplier->id);
            const imagenUrl = producto.imagen_url || '';
            
            Swal.fire({
                title: 'Edit Product',
                html: `
                    <form id="editProductForm" class="space-y-2.5 text-left">
                        <div class="grid grid-cols-2 gap-2.5">
                            <div>
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-0.5">Product Name *</label>
                                <input type="text" id="editProductName" name="nombre" required
                                       class="w-full px-2 py-1.5 text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                       value="${producto.nombre || ''}">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-0.5">Type</label>
                                <input type="text" id="editProductType" name="tipo"
                                       class="w-full px-2 py-1.5 text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                       value="${producto.categoria || ''}">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2.5">
                            <div>
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-0.5">Price *</label>
                                <div class="relative">
                                    <span class="absolute left-2 top-1/2 transform -translate-y-1/2 text-slate-500 dark:text-slate-400 text-sm">$</span>
                                    <input type="number" id="editProductPrice" name="precio" min="0" step="0.01" value="${producto.precio || 0}" required
                                           class="w-full pl-6 pr-2 py-1.5 text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-0.5">Stock Quantity</label>
                                <input type="number" id="editProductStock" name="stock" min="0" value="${producto.stock || 0}"
                                       class="w-full px-2 py-1.5 text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2.5">
                            <div>
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-0.5">Status</label>
                                <select id="editProductStatus" name="estado"
                                        class="w-full px-2 py-1.5 text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    <option value="activo" ${producto.activo ? 'selected' : ''}>Active</option>
                                    <option value="inactivo" ${!producto.activo ? 'selected' : ''}>Inactive</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-0.5">Expiration Date</label>
                                <input type="date" id="editProductExpirationDate" name="fecha_expiracion"
                                       class="w-full px-2 py-1.5 text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                       value="${producto.fecha_expiracion || ''}">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-0.5">Description</label>
                            <textarea id="editProductDescription" name="descripcion" rows="2"
                                      class="w-full px-2 py-1.5 text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">${producto.descripcion || ''}</textarea>
                        </div>
                        ${imagenUrl ? `
                        <div class="flex items-center gap-2">
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-0.5">Current:</label>
                            <img src="${imagenUrl}" alt="Current" class="w-12 h-12 object-cover rounded border border-slate-300 dark:border-slate-600">
                        </div>
                        ` : ''}
                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-0.5">Image</label>
                            <input type="file" id="editProductImage" name="imagen" accept="image/*"
                                   class="w-full px-2 py-1.5 text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                    </form>
                `,
                width: '500px',
                padding: '1rem',
                maxHeight: '500px',
                showCancelButton: true,
                confirmButtonText: 'Save',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html'
                },
                didOpen: () => {
                    const popup = Swal.getPopup();
                    if (isDarkMode) {
                        popup.style.backgroundColor = '#0f172a';
                        popup.style.color = '#e2e8f0';
                    }
                },
                preConfirm: () => {
                    const nombre = document.getElementById('editProductName').value.trim();
                    const tipo = document.getElementById('editProductType').value.trim();
                    const descripcion = document.getElementById('editProductDescription').value.trim();
                    const estado = document.getElementById('editProductStatus').value;
                    const precio = parseFloat(document.getElementById('editProductPrice').value) || 0;
                    const stock = parseInt(document.getElementById('editProductStock').value) || 0;
                    const imagen = document.getElementById('editProductImage').files[0];
                    
                    if (!nombre) {
                        Swal.showValidationMessage('Product name is required');
                        return false;
                    }
                    
                    if (precio < 0) {
                        Swal.showValidationMessage('Price must be greater than or equal to 0');
                        return false;
                    }
                    
                    return { nombre, tipo, descripcion, estado, precio, stock, cliente_id: supplierId, imagen: imagen, product_id: producto.id };
                }
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const formData = new FormData();
                        formData.append('nombre', result.value.nombre);
                        formData.append('tipo', result.value.tipo);
                        formData.append('descripcion', result.value.descripcion);
                        formData.append('estado', result.value.estado);
                        formData.append('precio', result.value.precio);
                        formData.append('stock', result.value.stock);
                        formData.append('cliente_id', result.value.cliente_id);
                        formData.append('product_id', result.value.product_id);
                        
                        if (result.value.imagen) {
                            formData.append('imagen', result.value.imagen);
                        }
                        
                        const response = await fetch('{{ route("walee.supplier.public.update-product", $supplier->id) }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: formData
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Product updated successfully',
                                confirmButtonColor: '#10b981',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Failed to update product',
                                confirmButtonColor: '#ef4444'
                            });
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred. Please try again.',
                            confirmButtonColor: '#ef4444'
                        });
                    }
                }
            });
        }
        
        // Add Product Modal
        function openAddProductModal() {
            const isDarkMode = document.documentElement.classList.contains('dark');
            const supplierId = @json($supplier->id);
            
            Swal.fire({
                title: 'Add New Product',
                html: `
                    <form id="addProductForm" class="space-y-2.5 text-left">
                        <div class="grid grid-cols-2 gap-2.5">
                            <div>
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-0.5">Product Name *</label>
                                <input type="text" id="productName" name="nombre" required
                                       class="w-full px-2 py-1.5 text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                       placeholder="Product name">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-0.5">Type</label>
                                <input type="text" id="productType" name="tipo"
                                       class="w-full px-2 py-1.5 text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                       placeholder="Product type">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2.5">
                            <div>
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-0.5">Price *</label>
                                <div class="relative">
                                    <span class="absolute left-2 top-1/2 transform -translate-y-1/2 text-slate-500 dark:text-slate-400 text-sm">$</span>
                                    <input type="number" id="productPrice" name="precio" min="0" step="0.01" value="0" required
                                           class="w-full pl-6 pr-2 py-1.5 text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                           placeholder="0.00">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-0.5">Stock Quantity</label>
                                <input type="number" id="productStock" name="stock" min="0" value="0"
                                       class="w-full px-2 py-1.5 text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                       placeholder="0">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2.5">
                            <div>
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-0.5">Status</label>
                                <select id="productStatus" name="estado"
                                        class="w-full px-2 py-1.5 text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    <option value="activo">Active</option>
                                    <option value="inactivo">Inactive</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-0.5">Expiration Date</label>
                                <input type="date" id="productExpirationDate" name="fecha_expiracion"
                                       class="w-full px-2 py-1.5 text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-0.5">Description</label>
                            <textarea id="productDescription" name="descripcion" rows="2"
                                      class="w-full px-2 py-1.5 text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                      placeholder="Product description"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-0.5">Image</label>
                            <input type="file" id="productImage" name="imagen" accept="image/*"
                                   class="w-full px-2 py-1.5 text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Optional: Upload product image</p>
                        </div>
                    </form>
                `,
                width: '500px',
                padding: '1rem',
                maxHeight: '600px',
                showCancelButton: true,
                confirmButtonText: 'Add Product',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html'
                },
                didOpen: () => {
                    const popup = Swal.getPopup();
                    if (isDarkMode) {
                        popup.style.backgroundColor = '#0f172a';
                        popup.style.color = '#e2e8f0';
                    }
                },
                preConfirm: () => {
                    const nombre = document.getElementById('productName').value.trim();
                    const tipo = document.getElementById('productType').value.trim();
                    const descripcion = document.getElementById('productDescription').value.trim();
                    const estado = document.getElementById('productStatus').value;
                    const precio = parseFloat(document.getElementById('productPrice').value) || 0;
                    const stock = parseInt(document.getElementById('productStock').value) || 0;
                    const imagen = document.getElementById('productImage').files[0];
                    
                    if (!nombre) {
                        Swal.showValidationMessage('Product name is required');
                        return false;
                    }
                    
                    if (precio < 0) {
                        Swal.showValidationMessage('Price must be greater than or equal to 0');
                        return false;
                    }
                    
                    return { nombre, tipo, descripcion, estado, precio, stock, cliente_id: supplierId, imagen: imagen };
                }
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const formData = new FormData();
                        formData.append('nombre', result.value.nombre);
                        formData.append('tipo', result.value.tipo);
                        formData.append('descripcion', result.value.descripcion);
                        formData.append('estado', result.value.estado);
                        formData.append('precio', result.value.precio);
                        formData.append('stock', result.value.stock);
                        formData.append('cliente_id', result.value.cliente_id);
                        
                        if (result.value.imagen) {
                            formData.append('imagen', result.value.imagen);
                        }
                        
                        const response = await fetch('{{ route("walee.supplier.public.add-product", $supplier->id) }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: formData
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Product added successfully',
                                confirmButtonColor: '#10b981',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Failed to add product',
                                confirmButtonColor: '#ef4444'
                            });
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred. Please try again.',
                            confirmButtonColor: '#ef4444'
                        });
                    }
                }
            });
        }
        
        // WhatsApp Modal
        function openWhatsAppModal() {
            @if(!$whatsappLink)
                Swal.fire({
                    icon: 'error',
                    title: 'No Phone Number',
                    text: 'Phone number is not available',
                    confirmButtonColor: '#ef4444'
                });
                return;
            @endif
            
            const whatsappLink = @json($whatsappLink);
            const supplierName = @json($supplier->name ?? 'Supplier');
            const productos = @json($productos);
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            // Filter only active products
            const activeProducts = productos.filter(p => p.activo);
            
            let productsHtml = '';
            if (activeProducts.length > 0) {
                productsHtml = `
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Include Products List:</label>
                        <div class="max-h-32 overflow-y-auto border border-slate-300 dark:border-slate-600 rounded-lg p-2 space-y-1.5">
                            ${activeProducts.map((producto, index) => `
                                <label class="flex items-center gap-2 p-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 rounded cursor-pointer">
                                    <input type="checkbox" class="product-checkbox" data-product-id="${producto.id}" 
                                           data-product-name="${producto.nombre || 'Unnamed'}" 
                                           data-product-stock="${producto.stock || 0}" 
                                           data-product-price="${producto.precio || 0}"
                                           class="rounded border-slate-300 dark:border-slate-600 text-emerald-500 focus:ring-emerald-500">
                                    <div class="flex-1 text-xs">
                                        <span class="font-medium text-slate-900 dark:text-white">${producto.nombre || 'Unnamed Product'}</span>
                                        <span class="text-slate-600 dark:text-slate-400 ml-2">Stock: ${producto.stock || 0}</span>
                                        ${producto.precio ? `<span class="text-slate-600 dark:text-slate-400 ml-2">$${parseFloat(producto.precio).toFixed(2)}</span>` : ''}
                                    </div>
                                </label>
                            `).join('')}
                        </div>
                    </div>
                `;
            }
            
            Swal.fire({
                title: 'Send WhatsApp Message',
                html: `
                    <div class="space-y-3 text-left">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Message to ${supplierName}</label>
                            <textarea id="whatsappMessage" rows="4" class="w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Type your message..."></textarea>
                        </div>
                        ${productsHtml}
                    </div>
                `,
                width: '500px',
                padding: '1rem',
                showCancelButton: true,
                confirmButtonText: 'Send',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html'
                },
                didOpen: () => {
                    const popup = Swal.getPopup();
                    if (isDarkMode) {
                        popup.style.backgroundColor = '#0f172a';
                        popup.style.color = '#e2e8f0';
                    }
                },
                preConfirm: () => {
                    const message = document.getElementById('whatsappMessage').value.trim();
                    const selectedProducts = [];
                    
                    // Get selected products
                    document.querySelectorAll('.product-checkbox:checked').forEach(checkbox => {
                        selectedProducts.push({
                            name: checkbox.getAttribute('data-product-name'),
                            stock: checkbox.getAttribute('data-product-stock'),
                            price: checkbox.getAttribute('data-product-price')
                        });
                    });
                    
                    // Validate that there's either a message or selected products
                    if (!message && selectedProducts.length === 0) {
                        Swal.showValidationMessage('Please enter a message or select at least one product');
                        return false;
                    }
                    
                    // Build final message
                    let finalMessage = message;
                    
                    if (selectedProducts.length > 0) {
                        if (message) {
                            finalMessage += '\n\n';
                        }
                        finalMessage += 'Available Products:\n';
                        selectedProducts.forEach((product, index) => {
                            finalMessage += `${index + 1}. ${product.name}`;
                            if (product.stock && parseInt(product.stock) > 0) {
                                finalMessage += ` (Stock: ${product.stock})`;
                            }
                            if (product.price && parseFloat(product.price) > 0) {
                                finalMessage += ` - $${parseFloat(product.price).toFixed(2)}`;
                            }
                            finalMessage += '\n';
                        });
                    }
                    
                    return finalMessage.trim();
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    const message = result.value;
                    
                    // Limpiar número de teléfono (remover espacios, guiones, etc.)
                    const telefonoLimpio = whatsappLink.replace(/[\s\-\(\)]/g, '');
                    
                    // Asegurar que tenga el código de país si no lo tiene
                    let numeroFinal = telefonoLimpio;
                    if (!numeroFinal.startsWith('+')) {
                        // Si no tiene código de país, asumir que es local
                        if (numeroFinal.length <= 8) {
                            numeroFinal = '+506' + numeroFinal; // Ajusta el código de país según necesites
                        } else {
                            numeroFinal = '+' + numeroFinal;
                        }
                    }
                    
                    // Codificar el mensaje para URL
                    const mensajeCodificado = encodeURIComponent(message);
                    
                    // Detectar si es móvil o desktop
                    const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
                    
                    let whatsappUrl;
                    if (isMobile) {
                        // Para móviles usar wa.me
                        whatsappUrl = `https://wa.me/${numeroFinal}?text=${mensajeCodificado}`;
                    } else {
                        // Para desktop usar web.whatsapp.com
                        whatsappUrl = `https://web.whatsapp.com/send?phone=${numeroFinal}&text=${mensajeCodificado}`;
                    }
                    
                    // Abrir WhatsApp
                    window.open(whatsappUrl, '_blank');
                }
            });
        }
        
        // Edit Profile Modal
        function openEditProfileModal() {
            const supplier = {
                name: @json($supplier->name ?? ''),
                email: @json($supplier->email ?? ''),
                telefono_1: @json($supplier->telefono_1 ?? ''),
                telefono_2: @json($supplier->telefono_2 ?? ''),
                address: @json($supplier->address ?? $supplier->direccion ?? ''),
                contacto_empresa: @json($supplier->contacto_empresa ?? '')
            };
            
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            Swal.fire({
                title: 'Edit Profile',
                width: '700px',
                html: `
                    <form id="editProfileForm" class="text-left">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Name *</label>
                                <input type="text" id="profileName" name="name" required value="${supplier.name}" class="w-full px-2 py-1.5 text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Email</label>
                                <input type="email" id="profileEmail" name="email" value="${supplier.email}" class="w-full px-2 py-1.5 text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Phone 1</label>
                                <input type="tel" id="profileTelefono1" name="telefono_1" value="${supplier.telefono_1}" class="w-full px-2 py-1.5 text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Phone 2</label>
                                <input type="tel" id="profileTelefono2" name="telefono_2" value="${supplier.telefono_2}" class="w-full px-2 py-1.5 text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Address</label>
                                <input type="text" id="profileAddress" name="address" value="${supplier.address}" class="w-full px-2 py-1.5 text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Company Contact</label>
                                <input type="text" id="profileContactoEmpresa" name="contacto_empresa" value="${supplier.contacto_empresa}" class="w-full px-2 py-1.5 text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-500">
                            </div>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Save',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#D59F3B',
                reverseButtons: true,
                padding: '1rem',
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html'
                },
                didOpen: () => {
                    const popup = Swal.getPopup();
                    if (isDarkMode) {
                        popup.style.backgroundColor = '#0f172a';
                        popup.style.color = '#e2e8f0';
                    }
                    // Asegurar que el modal no sea muy alto
                    popup.style.maxHeight = '500px';
                    popup.style.overflowY = 'auto';
                },
                preConfirm: () => {
                    const name = document.getElementById('profileName').value;
                    if (!name || name.trim() === '') {
                        Swal.showValidationMessage('Name is required');
                        return false;
                    }
                    return {
                        name: name,
                        email: document.getElementById('profileEmail').value,
                        telefono_1: document.getElementById('profileTelefono1').value,
                        telefono_2: document.getElementById('profileTelefono2').value,
                        address: document.getElementById('profileAddress').value,
                        contacto_empresa: document.getElementById('profileContactoEmpresa').value
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    updateProfile(result.value);
                }
            });
        }
        
        // Update Profile
        async function updateProfile(data) {
            try {
                const response = await fetch('{{ route("walee.supplier.public.update", $supplier->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Profile updated successfully',
                        confirmButtonColor: '#D59F3B',
                        timer: 2000
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.message || 'Failed to update profile',
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred. Please try again.',
                    confirmButtonColor: '#ef4444'
                });
            }
        }
    </script>
</body>
</html>

