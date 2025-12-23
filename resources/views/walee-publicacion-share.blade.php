<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $publicacion->title }}</title>
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $publicacion->title }}">
    <meta property="og:description" content="{{ Str::limit($publicacion->content, 200) }}">
    @if($publicacion->image_url)
    <meta property="og:image" content="{{ $publicacion->image_url }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    @endif
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $publicacion->title }}">
    <meta name="twitter:description" content="{{ Str::limit($publicacion->content, 200) }}">
    @if($publicacion->image_url)
    <meta name="twitter:image" content="{{ $publicacion->image_url }}">
    @endif
    
    <!-- LinkedIn -->
    <meta property="linkedin:title" content="{{ $publicacion->title }}">
    <meta property="linkedin:description" content="{{ Str::limit($publicacion->content, 200) }}">
    @if($publicacion->image_url)
    <meta property="linkedin:image" content="{{ $publicacion->image_url }}">
    @endif
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .image-container {
            width: 100%;
            height: 400px;
            overflow: hidden;
            background: #f0f0f0;
        }
        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .content {
            padding: 30px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 15px;
            line-height: 1.4;
        }
        .text {
            font-size: 16px;
            color: #4a4a4a;
            line-height: 1.6;
            white-space: pre-wrap;
        }
        .meta {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            font-size: 14px;
            color: #888;
        }
        .no-image {
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 48px;
        }
    </style>
</head>
<body>
    <div class="container">
        @if($publicacion->image_url)
        <div class="image-container">
            <img src="{{ $publicacion->image_url }}" alt="{{ $publicacion->title }}">
        </div>
        @else
        <div class="no-image">
            📱
        </div>
        @endif
        <div class="content">
            <h1 class="title">{{ $publicacion->title }}</h1>
            <div class="text">{{ $publicacion->content }}</div>
            <div class="meta">
                Publicado el {{ $publicacion->created_at->format('d/m/Y H:i') }}
            </div>
        </div>
    </div>
</body>
</html>

