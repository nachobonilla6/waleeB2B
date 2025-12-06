<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura - {{ $numeroFactura }}</title>
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
        <h1>💰 Factura</h1>
        <p style="margin: 0;">{{ $numeroFactura }}</p>
    </div>
    
    <div class="content">
        <div class="info-box">
            <div class="info-row">
                <span class="label">Número de Factura:</span>
                <span class="value">{{ $numeroFactura }}</span>
            </div>
            <div class="info-row">
                <span class="label">Fecha de Emisión:</span>
                <span class="value">{{ $fechaEmision }}</span>
            </div>
            @if(!empty($fechaVencimiento))
            <div class="info-row">
                <span class="label">Fecha de Vencimiento:</span>
                <span class="value">{{ $fechaVencimiento }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="label">Estado:</span>
                <span class="value">
                    @if($estado === 'pendiente') 🟡 Pendiente
                    @elseif($estado === 'pagada') 🟢 Pagada
                    @elseif($estado === 'vencida') 🔴 Vencida
                    @elseif($estado === 'cancelada') ⚫ Cancelada
                    @else {{ $estado }}
                    @endif
                </span>
            </div>
        </div>

        <div class="info-box">
            <h3 style="margin-top: 0; color: #10b981;">Detalles</h3>
            <div class="info-row">
                <span class="label">Concepto:</span>
                <span class="value">
                    @if($concepto === 'diseno_web') 🌐 Diseño Web
                    @elseif($concepto === 'redes_sociales') 📱 Gestión Redes Sociales
                    @elseif($concepto === 'seo') 🔍 SEO / Posicionamiento
                    @elseif($concepto === 'publicidad') 📢 Publicidad Digital
                    @elseif($concepto === 'mantenimiento') 🔧 Mantenimiento Mensual
                    @elseif($concepto === 'hosting') ☁️ Hosting & Dominio
                    @else {{ $concepto }}
                    @endif
                </span>
            </div>
            <div class="info-row">
                <span class="label">Método de Pago:</span>
                <span class="value">
                    @if($metodoPago === 'transferencia') 🏦 Transferencia Bancaria
                    @elseif($metodoPago === 'sinpe') 📲 SINPE Móvil
                    @elseif($metodoPago === 'tarjeta') 💳 Tarjeta de Crédito
                    @elseif($metodoPago === 'efectivo') 💵 Efectivo
                    @elseif($metodoPago === 'paypal') 🅿️ PayPal
                    @else {{ $metodoPago }}
                    @endif
                </span>
            </div>
        </div>

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

        @if(!empty($notas))
        <div class="info-box">
            <h3 style="margin-top: 0; color: #10b981;">Notas</h3>
            <p style="white-space: pre-wrap;">{{ $notas }}</p>
        </div>
        @endif
    </div>

    <div class="total-box">
        Subtotal: ${{ number_format($subtotal, 2) }} USD<br>
        Total: ${{ number_format($total, 2) }} USD
    </div>

    <div class="footer">
        <p>Esta es una factura generada automáticamente por Web Solutions.</p>
    </div>
</body>
</html>

