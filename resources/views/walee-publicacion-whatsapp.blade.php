<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $publicacion->title }}</title>
    
    @php
        // Limpiar contenido removiendo el botón de WhatsApp si existe
        $cleanContent = preg_replace('/\n.*[Ww]hats[Aa]pp.*\n?/', '', $publicacion->content);
        $cleanContent = trim($cleanContent);
        
        // Remover el link del sitio web del contenido para el meta description
        $metaContent = preg_replace('/\n.*velasportfishingandtours\.com.*\n?/', '', $cleanContent);
        $metaContent = trim($metaContent);
        
        // Limpiar HTML y caracteres especiales para meta description
        $metaDescription = strip_tags($metaContent);
        $metaDescription = preg_replace('/\s+/', ' ', $metaDescription);
        $metaDescription = trim($metaDescription);
        
        if (empty($metaDescription)) {
            $metaDescription = $publicacion->title;
        }
        
        // Procesar URL de imagen
        $imageUrl = null;
        if ($publicacion->image_url) {
            $imageUrl = $publicacion->image_url;
            
            // Si ya es una URL completa (empieza con http:// o https://), validarla
            if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                // Ya es una URL válida, solo asegurar HTTPS
                $imageUrl = str_replace('http://', 'https://', $imageUrl);
            } else {
                // Es una ruta relativa, convertir a URL completa
                // Si empieza con /storage/, usar asset() directamente
                if (strpos($imageUrl, '/storage/') === 0) {
                    $imageUrl = asset($imageUrl);
                } elseif (strpos($imageUrl, 'storage/') === 0) {
                    // Si no tiene / inicial, agregarlo
                    $imageUrl = asset('/' . $imageUrl);
                } elseif (strpos($imageUrl, '/') === 0) {
                    // Cualquier otra ruta absoluta relativa
                    $imageUrl = url($imageUrl);
                } else {
                    // Ruta relativa sin / inicial
                    $imageUrl = asset('/' . $imageUrl);
                }
                
                // Asegurar HTTPS
                $imageUrl = str_replace('http://', 'https://', $imageUrl);
            }
        }
    @endphp
    
    <!-- Open Graph / Facebook / WhatsApp - Orden importante para WhatsApp -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ e($publicacion->title) }}">
    <meta property="og:description" content="{{ e(Str::limit($metaDescription, 300)) }}">
    <meta property="og:site_name" content="Vela SportFishing & Tours">
    <meta property="og:locale" content="es_ES">
    
    @if($imageUrl)
    <!-- Open Graph Image - WhatsApp requiere estos meta tags en este orden -->
    <meta property="og:image" content="{{ $imageUrl }}">
    <meta property="og:image:url" content="{{ $imageUrl }}">
    <meta property="og:image:secure_url" content="{{ $imageUrl }}">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ e($publicacion->title) }}">
    <!-- Meta adicional para compatibilidad con WhatsApp -->
    <meta name="og:image" content="{{ $imageUrl }}">
    <meta name="twitter:image" content="{{ $imageUrl }}">
    <link rel="image_src" href="{{ $imageUrl }}">
    @endif
    
    <meta name="description" content="{{ e(Str::limit($metaDescription, 300)) }}">
    
    <!-- Twitter -->
    @if($imageUrl)
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ e($publicacion->title) }}">
    <meta name="twitter:description" content="{{ e(Str::limit($metaDescription, 200)) }}">
    <meta name="twitter:image" content="{{ $imageUrl }}">
    @endif
    
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full overflow-hidden">
        @if($publicacion->image_url)
        <div class="w-full">
            <img src="{{ $publicacion->image_url }}" alt="{{ $publicacion->title }}" class="w-full h-auto object-cover">
        </div>
        @endif
        
        <div class="p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-3">{{ $publicacion->title }}</h2>
            
            <div class="text-gray-700 whitespace-pre-wrap mb-6">
                @php
                    // Limpiar contenido removiendo el botón de WhatsApp si existe
                    $cleanContent = preg_replace('/\n.*[Ww]hats[Aa]pp.*\n?/', '', $publicacion->content);
                    $cleanContent = trim($cleanContent);
                    
                    // Asegurar que tenga el link del sitio web
                    if (strpos($cleanContent, 'velasportfishingandtours.com') === false) {
                        $cleanContent .= "\n\n🌐 Más información: https://www.velasportfishingandtours.com/";
                    }
                    
                    echo nl2br(e($cleanContent));
                @endphp
            </div>
            
            <div class="flex gap-3">
                <button 
                    onclick="shareToWhatsApp()" 
                    class="flex-1 bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    Compartir
                </button>
                
                @php
                    // Obtener número de WhatsApp del cliente
                    $whatsappNumber = $cliente->telefono_1 ?? $cliente->telefono_2 ?? null;
                    $whatsappUrl = null;
                    
                    if ($whatsappNumber) {
                        // Limpiar número (eliminar espacios, guiones, paréntesis, etc.)
                        $cleanNumber = preg_replace('/[^0-9]/', '', $whatsappNumber);
                        
                        // Si no empieza con código de país, asumir Costa Rica (506)
                        if (strlen($cleanNumber) <= 8) {
                            $cleanNumber = '506' . $cleanNumber;
                        }
                        
                        $whatsappUrl = "https://wa.me/{$cleanNumber}";
                    }
                @endphp
                
                @if($whatsappUrl)
                <a 
                    href="{{ $whatsappUrl }}" 
                    target="_blank"
                    class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    Contact
                </a>
                @endif
            </div>
        </div>
    </div>
    
    <script>
        function shareToWhatsApp() {
            @php
                // Limpiar contenido removiendo el botón de WhatsApp si existe
                $cleanContent = preg_replace('/\n.*[Ww]hats[Aa]pp.*\n?/', '', $publicacion->content);
                $cleanContent = trim($cleanContent);
                
                // Remover el link del sitio web del contenido
                $cleanContent = preg_replace('/\n.*velasportfishingandtours\.com.*\n?/', '', $cleanContent);
                $cleanContent = trim($cleanContent);
                
                // Obtener la URL completa de esta página para que WhatsApp muestre la previsualización con la imagen
                $pageUrl = url()->current();
                
                // Crear mensaje con el texto y el link de la página (el link permite que WhatsApp muestre la previsualización con la imagen)
                $message = $cleanContent . "\n\n" . $pageUrl;
            @endphp
            
            const text = @json($message);
            
            // Detectar si es móvil o desktop
            const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
            let url;
            
            if (isMobile) {
                // Para móviles usar whatsapp://
                url = 'whatsapp://send?text=' + encodeURIComponent(text);
            } else {
                // Para desktop usar web.whatsapp.com
                url = 'https://web.whatsapp.com/send?text=' + encodeURIComponent(text);
            }
            
            // Abrir WhatsApp
            window.location.href = url;
        }
    </script>
</body>
</html>

