<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotización - {{ $cotizacion->numero_cotizacion }}</title>
    <style>
        @page {
            margin: 20mm;
        }
        * {
            font-family: 'DejaVu Sans', Arial, sans-serif;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11pt;
            color: #333;
            line-height: 1.4;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 15px;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14pt;
        }
        .header-right {
            text-align: right;
        }
        .cotizacion-title {
            font-size: 24pt;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 5px;
        }
        .cotizacion-number {
            font-size: 16pt;
            font-weight: bold;
            color: #333;
        }
        .estado {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 10pt;
            font-weight: bold;
            margin-top: 5px;
        }
        .estado-aceptada {
            background-color: #10b981;
            color: white;
        }
        .estado-pendiente {
            background-color: #f59e0b;
            color: white;
        }
        .estado-rechazada {
            background-color: #ef4444;
            color: white;
        }
        .datos-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        .datos-box {
            background-color: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        .datos-title {
            font-weight: bold;
            font-size: 12pt;
            color: #2563eb;
            margin-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
        .datos-item {
            margin-bottom: 8px;
            font-size: 10pt;
        }
        .datos-label {
            color: #6b7280;
            font-weight: 500;
        }
        .datos-value {
            color: #111827;
            font-weight: 600;
        }
        .info-section {
            background-color: #eff6ff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #2563eb;
        }
        .info-title {
            font-weight: bold;
            font-size: 13pt;
            color: #2563eb;
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #dbeafe;
        }
        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .info-label {
            color: #6b7280;
            font-size: 10pt;
        }
        .info-value {
            color: #111827;
            font-weight: 600;
            font-size: 10pt;
        }
        .monto-total {
            background-color: #2563eb;
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-top: 30px;
        }
        .monto-label {
            font-size: 12pt;
            margin-bottom: 5px;
        }
        .monto-value {
            font-size: 28pt;
            font-weight: bold;
        }
        .descripcion-section {
            margin-top: 30px;
            padding: 20px;
            background-color: #f9fafb;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        .descripcion-title {
            font-weight: bold;
            font-size: 12pt;
            color: #2563eb;
            margin-bottom: 10px;
        }
        .descripcion-text {
            color: #374151;
            font-size: 10pt;
            line-height: 1.6;
            white-space: pre-line;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 9pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table thead {
            background-color: #2563eb;
            color: white;
        }
        table th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 10pt;
            border: 1px solid #1e40af;
        }
        table th.text-center {
            text-align: center;
        }
        table th.text-right {
            text-align: right;
        }
        table tbody tr {
            border-bottom: 1px solid #e5e7eb;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        table td {
            padding: 10px 12px;
            font-size: 10pt;
            color: #374151;
        }
        table td.text-center {
            text-align: center;
        }
        table td.text-right {
            text-align: right;
        }
        .item-descripcion {
            font-weight: 600;
            color: #111827;
            margin-bottom: 3px;
        }
        .item-detalle {
            font-size: 9pt;
            color: #6b7280;
            font-style: italic;
        }
        .totals-section {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
        }
        .totals-table {
            width: 300px;
            background-color: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10pt;
        }
        .totals-row:last-child {
            border-bottom: none;
        }
        .totals-row.total {
            font-weight: bold;
            font-size: 12pt;
            padding-top: 10px;
            margin-top: 5px;
            border-top: 2px solid #2563eb;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <div class="logo" style="width: 50px; height: 50px; font-size: 12pt;">WS</div>
            <div>
                <div style="font-weight: bold; font-size: 12pt; color: #2563eb;">Web Solutions CR</div>
                <div style="font-size: 9pt; color: #666;">WebSolutions.Work</div>
            </div>
        </div>
        <div class="header-right">
            <div class="cotizacion-title" style="font-size: 20pt; margin-bottom: 3px;">Cotización</div>
            <div class="cotizacion-number" style="font-size: 14pt;">{{ $cotizacion->numero_cotizacion }}</div>
            <div class="estado estado-{{ $cotizacion->estado }}" style="margin-top: 5px;">
                {{ ucfirst($cotizacion->estado) }}
            </div>
        </div>
    </div>
    
    <!-- Datos del Emisor y Cliente -->
    <div class="datos-section">
        <div class="datos-box">
            <div class="datos-title">Emisor</div>
            <div class="datos-item">
                <span class="datos-label">Empresa:</span>
                <span class="datos-value">Web Solutions CR</span>
            </div>
            <div class="datos-item">
                <span class="datos-label">Email:</span>
                <span class="datos-value">websolutionscrnow@gmail.com</span>
            </div>
            <div class="datos-item">
                <span class="datos-label">WhatsApp:</span>
                <span class="datos-value">+506 8806 1829</span>
            </div>
            <div class="datos-item">
                <span class="datos-label">Web:</span>
                <span class="datos-value">websolutions.work</span>
            </div>
        </div>
        
        <div class="datos-box">
            <div class="datos-title">Cliente</div>
            <div class="datos-item">
                <span class="datos-label">Nombre:</span>
                <span class="datos-value">{{ $cliente ? $cliente->nombre_empresa : 'N/A' }}</span>
            </div>
            <div class="datos-item">
                <span class="datos-label">Email:</span>
                <span class="datos-value">{{ $cotizacion->correo }}</span>
            </div>
            @if($cliente && $cliente->telefono)
            <div class="datos-item">
                <span class="datos-label">Teléfono:</span>
                <span class="datos-value">{{ $cliente->telefono }}</span>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Información de la Cotización -->
    <div class="info-section">
        <div class="info-title">Información General</div>
        <div class="info-row">
            <span class="info-label">Fecha:</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($cotizacion->fecha)->format('d/m/Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Idioma:</span>
            <span class="info-value">
                @if($cotizacion->idioma === 'es') Español
                @elseif($cotizacion->idioma === 'en') English
                @elseif($cotizacion->idioma === 'fr') Français
                @else {{ $cotizacion->idioma }}
                @endif
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Vigencia:</span>
            <span class="info-value">{{ $cotizacion->vigencia }} días</span>
        </div>
    </div>
    
    <!-- Tabla de Servicios -->
    <table>
        <thead>
            <tr>
                <th>Servicio</th>
                <th>Plan</th>
                <th class="text-right">Precio Unitario</th>
                <th class="text-right">Impuestos (13%)</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="item-descripcion">{{ $cotizacion->tipo_servicio }}</div>
                    @if($cotizacion->descripcion)
                        <div class="item-detalle">{{ $cotizacion->descripcion }}</div>
                    @endif
                </td>
                <td>{{ $cotizacion->plan }}</td>
                <td class="text-right">&#8353;{{ number_format($cotizacion->monto / 1.13, 2, ',', ' ') }}</td>
                <td class="text-right">&#8353;{{ number_format($cotizacion->monto * 0.13 / 1.13, 2, ',', ' ') }}</td>
                <td class="text-right">
                    <strong>&#8353;{{ number_format($cotizacion->monto, 2, ',', ' ') }}</strong>
                </td>
            </tr>
        </tbody>
    </table>
    
    <!-- Totales -->
    <div class="totals-section">
        <div class="totals-table">
            <div class="totals-row">
                <span>Subtotal:</span>
                <span><strong>&#8353;{{ number_format($cotizacion->monto / 1.13, 2, ',', ' ') }}</strong></span>
            </div>
            <div class="totals-row">
                <span>IVA (13%):</span>
                <span><strong>&#8353;{{ number_format($cotizacion->monto * 0.13 / 1.13, 2, ',', ' ') }}</strong></span>
            </div>
            <div class="totals-row total">
                <span>Total:</span>
                <span>&#8353;{{ number_format($cotizacion->monto, 2, ',', ' ') }}</span>
            </div>
        </div>
    </div>
    
    
    <!-- Footer -->
    <div class="footer">
        <p>Esta cotización tiene una vigencia de {{ $cotizacion->vigencia }} días a partir de la fecha de emisión.</p>
        <p style="margin-top: 10px;">Web Solutions CR · websolutions.work · websolutionscrnow@gmail.com</p>
    </div>
</body>
</html>

