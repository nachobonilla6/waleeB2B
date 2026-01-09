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
        $cleanPhone = $phone ? preg_replace('/[^0-9]/', '', $phone) : null;
        $whatsappLink = $cleanPhone ? "https://wa.me/{$cleanPhone}" : null;
        
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
    @endphp

    <div class="min-h-screen relative flex flex-col">
        <!-- Background -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-400/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 -left-20 w-60 h-60 bg-walee-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-4xl mx-auto px-4 py-8 sm:px-6 lg:px-8 w-full">
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
                <div class="bg-white dark:bg-slate-900/60 rounded-2xl lg:rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-none p-6 sm:p-8">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 mb-6">
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
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
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
                    
                    <!-- Edit Profile Form -->
                    <div class="border-t border-slate-200 dark:border-slate-700 pt-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-bold text-slate-900 dark:text-white">Edit Profile</h2>
                            <button onclick="openEditProfileModal()" class="px-4 py-2 bg-walee-500 hover:bg-walee-600 text-white rounded-lg font-medium transition-colors">
                                Edit
                            </button>
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
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            Swal.fire({
                title: 'Send WhatsApp Message',
                html: `
                    <div class="space-y-4 text-left">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Message to ${supplierName}</label>
                            <textarea id="whatsappMessage" rows="6" class="w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Type your message..."></textarea>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Send',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#10b981',
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
                    return document.getElementById('whatsappMessage').value;
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    const message = encodeURIComponent(result.value);
                    const separator = whatsappLink.includes('?') ? '&' : '?';
                    window.open(`${whatsappLink}${separator}text=${message}`, '_blank');
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

