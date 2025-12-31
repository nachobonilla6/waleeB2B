<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura - {{ $data['numero_factura'] ?? 'N/A' }}</title>
    <style>
        @page {
            margin: 20mm;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            color: #333;
            line-height: 1.4;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            border-bottom: 2px solid #8b5cf6;
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
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
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
        .factura-title {
            font-size: 24pt;
            font-weight: bold;
            color: #8b5cf6;
            margin-bottom: 5px;
        }
        .factura-number {
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
        .estado-pagada {
            background-color: #10b981;
            color: white;
        }
        .estado-pendiente {
            background-color: #f59e0b;
            color: white;
        }
        .datos-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 25px;
        }
        .datos-box {
            background: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #8b5cf6;
        }
        .datos-title {
            font-weight: bold;
            color: #8b5cf6;
            margin-bottom: 10px;
            font-size: 12pt;
        }
        .datos-content {
            font-size: 10pt;
            color: #555;
            line-height: 1.6;
        }
        .info-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
            font-size: 10pt;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
        }
        .info-label {
            font-weight: bold;
            color: #666;
        }
        .info-value {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        thead {
            background-color: #8b5cf6;
            color: white;
        }
        th {
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 10pt;
        }
        th.text-center {
            text-align: center;
        }
        th.text-right {
            text-align: right;
        }
        td {
            padding: 10px 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10pt;
        }
        td.text-center {
            text-align: center;
        }
        td.text-right {
            text-align: right;
        }
        .item-descripcion {
            font-weight: 500;
            color: #333;
        }
        .item-detalle {
            font-size: 9pt;
            color: #666;
            margin-top: 3px;
            font-style: italic;
        }
        .descuento-badge {
            display: inline-block;
            background: #fef3c7;
            color: #92400e;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8pt;
            margin-left: 5px;
        }
        .totals-section {
            margin-top: 20px;
            margin-bottom: 30px;
        }
        .totals-table {
            width: 100%;
            max-width: 400px;
            margin-left: auto;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .totals-row.total {
            background-color: #8b5cf6;
            color: white;
            padding: 12px 15px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14pt;
            margin-top: 10px;
        }
        .pagos-section {
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .pagos-title {
            font-weight: bold;
            font-size: 12pt;
            color: #8b5cf6;
            margin-bottom: 10px;
        }
        .terminos {
            margin-top: 30px;
            padding: 15px;
            background: #f9fafb;
            border-left: 4px solid #8b5cf6;
            border-radius: 6px;
            font-size: 9pt;
            color: #555;
            line-height: 1.6;
        }
        .page-number {
            text-align: right;
            color: #999;
            font-size: 9pt;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    @php
        $cliente = \App\Models\Cliente::find($data['cliente_id'] ?? null);
        $items = isset($data['items_json']) ? json_decode($data['items_json'], true) : (isset($data['items']) ? $data['items'] : []);
        $pagos = isset($data['pagos']) ? (is_string($data['pagos']) ? json_decode($data['pagos'], true) : $data['pagos']) : [];
        
        // Calcular subtotal desde items
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += floatval($item['subtotal'] ?? ($item['precio_unitario'] ?? 0) * ($item['cantidad'] ?? 1));
        }
        
        $descuentoAntes = floatval($data['descuento_antes_impuestos'] ?? 0);
        $subtotalConDescuento = $subtotal - $descuentoAntes;
        $iva = $subtotalConDescuento * 0.13;
        $descuentoDespues = floatval($data['descuento_despues_impuestos'] ?? 0);
        $total = $subtotalConDescuento + $iva - $descuentoDespues;
        
        $estado = $data['estado'] ?? 'pendiente';
        $montoPagado = floatval($data['monto_pagado'] ?? 0);
        // Sumar pagos recibidos al monto pagado
        foreach ($pagos as $pago) {
            $montoPagado += floatval($pago['importe'] ?? 0);
        }
        if ($montoPagado >= $total) {
            $estado = 'pagada';
        }
    @endphp
    
    <div class="header">
        <div class="header-left">
            <div class="logo" style="width: 50px; height: 50px; font-size: 12pt;">WS</div>
            <div>
                <div style="font-weight: bold; font-size: 12pt; color: #8b5cf6;">Web Solutions CR</div>
                <div style="font-size: 9pt; color: #666;">WebSolutions.Work</div>
            </div>
        </div>
        <div class="header-right">
            <div class="factura-title" style="font-size: 20pt; margin-bottom: 3px;">Factura</div>
            <div class="factura-number" style="font-size: 14pt;">{{ $data['numero_factura'] ?? 'N/A' }}</div>
            <div class="estado estado-{{ $estado }}" style="margin-top: 5px;">
                {{ ucfirst($estado) }}
            </div>
        </div>
    </div>
    
    <!-- Datos del Emisor y Cliente -->
    <div class="datos-section">
        <div class="datos-box">
            <div class="datos-title">Datos del emisor</div>
            <div class="datos-content">
                <div><strong>WebSolutions.Work</strong></div>
                <div>Jaco, Puntarenas</div>
                <div>506 8806 1829</div>
                <div>websolutionscrnow@gmail.com</div>
                <div>Costa Rica</div>
            </div>
        </div>
        
        <div class="datos-box">
            <div class="datos-title">Datos del cliente</div>
            <div class="datos-content">
                @if($cliente)
                    <div><strong>{{ $cliente->nombre_empresa }}</strong></div>
                    @if($cliente->direccion)
                        <div>{{ $cliente->direccion }}</div>
                    @endif
                    <div>Costa Rica</div>
                    @if($cliente->telefono)
                        <div>{{ $cliente->telefono }}</div>
                    @endif
                    @if($cliente->correo)
                        <div>{{ $cliente->correo }}</div>
                    @endif
                @else
                    <div><strong>{{ $data['correo'] ?? 'N/A' }}</strong></div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Información de Orden y Fechas -->
    <div class="info-row">
        <div class="info-item">
            <span class="info-label">N° de orden:</span>
            <span class="info-value">{{ $data['numero_orden'] ?? ($data['numero_factura'] ?? 'N/A') }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Fecha:</span>
            <span class="info-value">{{ isset($data['fecha_emision']) ? \Carbon\Carbon::parse($data['fecha_emision'])->format('d/m/Y') : date('d/m/Y') }}</span>
        </div>
    </div>
    
    @if(!empty($data['fecha_vencimiento']))
    <div class="info-row">
        <div class="info-item">
            <span class="info-label">Fecha de vencimiento:</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($data['fecha_vencimiento'])->format('d/m/Y') }}</span>
        </div>
    </div>
    @endif
    
    <!-- Tabla de Items -->
    <table>
        <thead>
            <tr>
                <th>Artículo</th>
                <th class="text-center">Uds.</th>
                <th class="text-right">Precio</th>
                <th class="text-right">Impuestos</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                @php
                    $precioUnitario = floatval($item['precio_unitario'] ?? 0);
                    $cantidad = intval($item['cantidad'] ?? 1);
                    $subtotalItem = floatval($item['subtotal'] ?? ($precioUnitario * $cantidad));
                    // El IVA se calcula sobre el subtotal del item
                    $ivaItem = $subtotalItem * 0.13;
                    $totalItem = $subtotalItem + $ivaItem;
                @endphp
                <tr>
                    <td>
                        <div class="item-descripcion">{{ $item['descripcion'] ?? '' }}</div>
                        @if(!empty($item['notas']))
                            <div class="item-detalle">{{ $item['notas'] }}</div>
                        @endif
                    </td>
                    <td class="text-center">{{ $cantidad }}</td>
                    <td class="text-right">₡{{ number_format($precioUnitario, 2, ',', ' ') }}</td>
                    <td class="text-right">₡{{ number_format($ivaItem, 2, ',', ' ') }}</td>
                    <td class="text-right">
                        <strong>₡{{ number_format($totalItem, 2, ',', ' ') }}</strong>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- Totales -->
    <div class="totals-section">
        <div class="totals-table">
            @if($descuentoAntes > 0)
            <div class="totals-row">
                <span>Descuento antes de impuestos:</span>
                <span style="color: #dc2626;">-₡{{ number_format($descuentoAntes, 2, ',', ' ') }}</span>
            </div>
            @endif
            
            <div class="totals-row">
                <span>Subtotal:</span>
                <span><strong>₡{{ number_format($subtotalConDescuento, 2, ',', ' ') }}</strong></span>
            </div>
            
            @if($descuentoDespues > 0)
            <div class="totals-row">
                <span>Descuento después de impuestos:</span>
                <span style="color: #dc2626;">-₡{{ number_format($descuentoDespues, 2, ',', ' ') }}</span>
            </div>
            @endif
            
            <div class="totals-row total">
                <span>Total:</span>
                <span>₡{{ number_format($total, 2, ',', ' ') }}</span>
            </div>
        </div>
    </div>
    
    <!-- Pagos Recibidos -->
    @if(count($pagos) > 0)
    <div class="pagos-section">
        <div class="pagos-title">Pagos recibidos</div>
        <table>
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th class="text-center">Fecha</th>
                    <th class="text-right">Importe</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pagos as $pago)
                    <tr>
                        <td>{{ $pago['descripcion'] ?? '' }}</td>
                        <td class="text-center">{{ isset($pago['fecha']) ? \Carbon\Carbon::parse($pago['fecha'])->format('d/m/Y') : '' }}</td>
                        <td class="text-right"><strong>₡{{ number_format(floatval($pago['importe'] ?? 0), 2, ',', ' ') }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    
    <!-- Términos y Condiciones -->
    @if(!empty($data['notas']))
    <div class="terminos">
        <strong>Términos y condiciones:</strong><br>
        {{ $data['notas'] }}
    </div>
    @endif
    
    <div class="page-number">
        Página 1 de 1
    </div>
</body>
</html>
