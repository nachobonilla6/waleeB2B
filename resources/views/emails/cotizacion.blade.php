<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotización - {{ $numeroCotizacion }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #10b981;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
        }
        .info-box {
            background-color: white;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #10b981;
            border-radius: 4px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #6b7280;
        }
        .value {
            color: #111827;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-size: 12px;
        }
        .total-box {
            background-color: #10b981;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 5px 5px;
            font-size: 24px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📝 Cotización</h1>
        <p style="margin: 0;">{{ $numeroCotizacion }}</p>
    </div>
    
    <div class="content">
        <div class="info-box">
            <div class="info-row">
                <span class="label">Número de Cotización:</span>
                <span class="value">{{ $numeroCotizacion }}</span>
            </div>
            <div class="info-row">
                <span class="label">Fecha:</span>
                <span class="value">{{ $fecha }}</span>
            </div>
            <div class="info-row">
                <span class="label">Idioma:</span>
                <span class="value">
                    @if($idioma === 'es') 🇪🇸 Español
                    @elseif($idioma === 'en') 🇺🇸 English
                    @elseif($idioma === 'fr') 🇫🇷 Français
                    @else {{ $idioma }}
                    @endif
                </span>
            </div>
        </div>

        <div class="info-box">
            <h3 style="margin-top: 0; color: #10b981;">Servicios</h3>
            <div class="info-row">
                <span class="label">Tipo de Servicio:</span>
                <span class="value">
                    @if($tipoServicio === 'diseno_web') 🌐 Diseño Web
                    @elseif($tipoServicio === 'redes_sociales') 📱 Gestión Redes Sociales
                    @elseif($tipoServicio === 'seo') 🔍 SEO / Posicionamiento
                    @elseif($tipoServicio === 'publicidad') 📢 Publicidad Digital
                    @elseif($tipoServicio === 'mantenimiento') 🔧 Mantenimiento Web
                    @elseif($tipoServicio === 'hosting') ☁️ Hosting & Dominio
                    @elseif($tipoServicio === 'combo') 📦 Paquete Completo
                    @else {{ $tipoServicio }}
                    @endif
                </span>
            </div>
            <div class="info-row">
                <span class="label">Plan:</span>
                <span class="value">{{ $plan }}</span>
            </div>
            <div class="info-row">
                <span class="label">Vigencia:</span>
                <span class="value">{{ $vigencia }} días</span>
            </div>
        </div>

        @if(!empty($descripcion))
        <div class="info-box">
            <h3 style="margin-top: 0; color: #10b981;">Descripción / Servicios Incluidos</h3>
            <p style="white-space: pre-wrap;">{{ $descripcion }}</p>
        </div>
        @endif

        @if(!empty($clienteNombre))
        <div class="info-box">
            <h3 style="margin-top: 0; color: #10b981;">Cliente</h3>
            <div class="info-row">
                <span class="label">Nombre:</span>
                <span class="value">{{ $clienteNombre }}</span>
            </div>
            @if(!empty($clienteCorreo))
            <div class="info-row">
                <span class="label">Correo:</span>
                <span class="value">{{ $clienteCorreo }}</span>
            </div>
            @endif
        </div>
        @endif
    </div>

    <div class="total-box">
        Monto: ${{ number_format($monto, 2) }} USD
    </div>

    <div class="footer">
        <p>Esta es una cotización generada automáticamente por Web Solutions.</p>
        <p>Vigencia: {{ $vigencia }} días</p>
    </div>
</body>
</html>

