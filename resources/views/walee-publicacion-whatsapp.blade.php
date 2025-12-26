<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $publicacion->title }}</title>
    
    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $publicacion->title }}">
    @php
        // Limpiar contenido removiendo el botón de WhatsApp si existe
        $cleanContent = preg_replace('/\n.*[Ww]hats[Aa]pp.*\n?/', '', $publicacion->content);
        $cleanContent = trim($cleanContent);
        
        // Remover el link del sitio web del contenido para el meta description
        $metaContent = preg_replace('/\n.*velasportfishingandtours\.com.*\n?/', '', $cleanContent);
        $metaContent = trim($metaContent);
    @endphp
    <meta property="og:description" content="{{ Str::limit($metaContent, 200) }}">
    @if($publicacion->image_url)
    @php
        // Asegurar que la URL de la imagen sea absoluta
        $imageUrl = $publicacion->image_url;
        if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            $imageUrl = url($imageUrl);
        }
    @endphp
    <meta property="og:image" content="{{ $imageUrl }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:type" content="image/jpeg">
    @endif
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $publicacion->title }}">
    <meta name="twitter:description" content="{{ Str::limit($metaContent, 200) }}">
    @if($publicacion->image_url)
    @php
        // Asegurar que la URL de la imagen sea absoluta
        $imageUrl = $publicacion->image_url;
        if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            $imageUrl = url($imageUrl);
        }
    @endphp
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
            
            <button 
                onclick="shareToWhatsApp()" 
                class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                </svg>
                Compartir por WhatsApp
            </button>
        </div>
    </div>
    
    <script>
        function shareToWhatsApp() {
            @php
                // Limpiar contenido removiendo el botón de WhatsApp si existe
                $cleanContent = preg_replace('/\n.*[Ww]hats[Aa]pp.*\n?/', '', $publicacion->content);
                $cleanContent = trim($cleanContent);
                
                // Remover el link del sitio web del contenido (ya estará en el link de la página)
                $cleanContent = preg_replace('/\n.*velasportfishingandtours\.com.*\n?/', '', $cleanContent);
                $cleanContent = trim($cleanContent);
                
                // Obtener la URL completa de esta página
                $pageUrl = url()->current();
                
                // Crear mensaje solo con el texto y el link de la página (sin título)
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

