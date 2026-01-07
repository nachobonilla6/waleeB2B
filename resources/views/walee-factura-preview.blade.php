<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura - {{ $data['numero_factura'] ?? 'N/A' }}</title>
    <style>
        @page {
            margin: 15mm;
        }
        * {
            font-family: 'DejaVu Sans', Arial, sans-serif;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10pt;
            color: #333;
            line-height: 1.3;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
            border-bottom: 2px solid #8b5cf6;
            padding-bottom: 8px;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
        }
        .logo {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 11pt;
            flex-shrink: 0;
        }
        .header-left > div:last-child {
            flex: 1;
        }
        .header-right {
            text-align: right;
            flex-shrink: 0;
        }
        .factura-title {
            font-size: 18pt;
            font-weight: bold;
            color: #8b5cf6;
            margin-bottom: 2px;
            line-height: 1.2;
        }
        .factura-number {
            font-size: 13pt;
            font-weight: bold;
            color: #333;
            line-height: 1.3;
        }
        .estado {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 8pt;
            font-weight: bold;
            margin-top: 4px;
            line-height: 1.2;
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
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 10px;
        }
        .datos-box {
            background: #f9fafb;
            padding: 10px;
            border-radius: 6px;
            border-left: 3px solid #8b5cf6;
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .datos-title {
            font-weight: bold;
            color: #8b5cf6;
            margin-bottom: 6px;
            font-size: 10pt;
            line-height: 1.2;
            margin-top: 0;
            padding-top: 0;
            width: 100%;
        }
        .datos-content {
            font-size: 9pt;
            color: #555;
            line-height: 1.5;
            margin-top: 0;
            width: 100%;
        }
        .datos-content > div {
            margin-bottom: 3px;
        }
        .datos-content > div:last-child {
            margin-bottom: 0;
        }
        .datos-box.cliente-box {
            align-items: flex-end;
        }
        .datos-box.cliente-box .datos-title {
            text-align: right;
            margin-top: 0;
            padding-top: 0;
        }
        .datos-box.cliente-box .datos-content {
            text-align: right;
            margin-top: 0;
        }
        .datos-box.cliente-box .datos-content > div {
            text-align: right;
        }
        .info-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 8px;
            font-size: 9pt;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
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
            margin-bottom: 12px;
        }
        thead {
            background-color: #8b5cf6;
            color: white;
        }
        th {
            padding: 6px 6px;
            text-align: left;
            font-weight: bold;
            font-size: 9pt;
            vertical-align: top;
        }
        th.text-center {
            text-align: center;
        }
        th.text-right {
            text-align: right;
        }
        td {
            padding: 5px 6px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 9pt;
            vertical-align: top;
        }
        td.text-center {
            text-align: center;
            vertical-align: middle;
        }
        td.text-right {
            text-align: right;
            vertical-align: middle;
        }
        .item-descripcion {
            font-weight: 500;
            color: #333;
            font-size: 9pt;
        }
        .item-detalle {
            font-size: 8pt;
            color: #666;
            margin-top: 2px;
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
            margin-top: 12px;
            margin-bottom: 15px;
        }
        .totals-table {
            width: 100%;
            max-width: 400px;
            margin-left: auto;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            padding: 4px 0;
            border-bottom: 1px solid #e5e7eb;
            font-size: 9pt;
        }
        .totals-row.total {
            background-color: #8b5cf6;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12pt;
            margin-top: 6px;
        }
        .pagos-section {
            margin-top: 15px;
            page-break-inside: avoid;
        }
        .pagos-title {
            font-weight: bold;
            font-size: 10pt;
            color: #8b5cf6;
            margin-bottom: 6px;
        }
        .terminos {
            margin-top: 15px;
            padding: 10px;
            background: #f9fafb;
            border-left: 3px solid #8b5cf6;
            border-radius: 4px;
            font-size: 8pt;
            color: #555;
            line-height: 1.4;
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
        
        // Procesar items - asegurar que siempre sea un array
        $items = [];
        if (isset($data['items_json']) && !empty($data['items_json'])) {
            $decoded = json_decode($data['items_json'], true);
            $items = is_array($decoded) ? $decoded : [];
        } elseif (isset($data['items']) && is_array($data['items'])) {
            $items = $data['items'];
        }
        
        // Procesar pagos - asegurar que siempre sea un array
        $pagos = [];
        if (isset($data['pagos'])) {
            if (is_string($data['pagos']) && !empty($data['pagos'])) {
                $decoded = json_decode($data['pagos'], true);
                $pagos = is_array($decoded) ? $decoded : [];
            } elseif (is_array($data['pagos'])) {
                $pagos = $data['pagos'];
            }
        }
        
        // Calcular subtotal desde items
        $subtotal = 0;
        if (is_array($items)) {
            foreach ($items as $item) {
                if (is_array($item)) {
                    $subtotal += floatval($item['subtotal'] ?? ($item['precio_unitario'] ?? 0) * ($item['cantidad'] ?? 1));
                }
            }
        }
        
        $descuentoAntes = floatval($data['descuento_antes_impuestos'] ?? 0);
        $subtotalConDescuento = $subtotal - $descuentoAntes;
        $iva = $subtotalConDescuento * 0.13;
        $descuentoDespues = floatval($data['descuento_despues_impuestos'] ?? 0);
        $total = $subtotalConDescuento + $iva - $descuentoDespues;
        
        $estado = $data['estado'] ?? 'pendiente';
        $montoPagado = floatval($data['monto_pagado'] ?? 0);
        // Sumar pagos recibidos al monto pagado
        if (is_array($pagos)) {
            foreach ($pagos as $pago) {
                if (is_array($pago)) {
                    $montoPagado += floatval($pago['importe'] ?? 0);
                }
            }
        }
        if ($montoPagado >= $total && $total > 0) {
            $estado = 'pagada';
        }
    @endphp
    
    <div class="header">
        <div class="header-left">
            <div class="logo">WS</div>
            <div>
                <div style="font-weight: bold; font-size: 11pt; color: #8b5cf6;">Web Solutions CR</div>
                <div style="font-size: 8pt; color: #666;">WebSolutions.Work</div>
            </div>
        </div>
        <div class="header-right">
            <div class="factura-title">Factura</div>
            <div class="factura-number">{{ $data['numero_factura'] ?? 'N/A' }}</div>
            <div class="estado estado-{{ $estado }}">
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
        
        <div class="datos-box cliente-box">
            <div class="datos-title">Datos del cliente</div>
            <div class="datos-content">
                @if($cliente)
                    <div><strong>{{ $cliente->nombre_empresa }}</strong></div>
                    @if($cliente->direccion)
                        <div>{{ $cliente->direccion }}</div>
                    @endif
                    @if($cliente->telefono)
                        <div>{{ $cliente->telefono }}</div>
                    @endif
                    @if($cliente->correo)
                        <div>{{ $cliente->correo }}</div>
                    @endif
                    <div>Costa Rica</div>
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
            <span class="info-label">Fecha de emisión:</span>
            <span class="info-value">{{ isset($data['fecha_emision']) ? \Carbon\Carbon::parse($data['fecha_emision'])->format('d/m/Y') : date('d/m/Y') }}</span>
        </div>
    </div>
    
    @if(!empty($data['fecha_vencimiento']))
    <div class="info-row">
        <div class="info-item">
            <span class="info-label">Fecha de vencimiento:</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($data['fecha_vencimiento'])->format('d/m/Y') }}</span>
        </div>
        <div class="info-item">
            <!-- Espacio vacío para mantener alineación -->
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
            @if(is_array($items) && count($items) > 0)
                @foreach($items as $item)
                    @if(is_array($item))
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
                            <td class="text-right">${{ number_format($precioUnitario, 2, '.', ',') }}</td>
                            <td class="text-right">${{ number_format($ivaItem, 2, '.', ',') }}</td>
                            <td class="text-right">
                                <strong>${{ number_format($totalItem, 2, '.', ',') }}</strong>
                            </td>
                        </tr>
                    @endif
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px; color: #999;">No hay items en esta factura</td>
                </tr>
            @endif
        </tbody>
    </table>
    
    <!-- Totales -->
    <div class="totals-section">
        <div class="totals-table">
            @if($descuentoAntes > 0)
            <div class="totals-row">
                <span>Descuento antes de impuestos:</span>
                <span style="color: #dc2626;">-${{ number_format($descuentoAntes, 2, '.', ',') }}</span>
            </div>
            @endif
            
            <div class="totals-row">
                <span>Subtotal:</span>
                <span><strong>${{ number_format($subtotalConDescuento, 2, '.', ',') }}</strong></span>
            </div>
            
            <div class="totals-row">
                <span>IVA (13%):</span>
                <span><strong>${{ number_format($iva, 2, '.', ',') }}</strong></span>
            </div>
            
            @if($descuentoDespues > 0)
            <div class="totals-row">
                <span>Descuento después de impuestos:</span>
                <span style="color: #dc2626;">-${{ number_format($descuentoDespues, 2, '.', ',') }}</span>
            </div>
            @endif
            
            <div class="totals-row total">
                <span>Total:</span>
                <span>${{ number_format($total, 2, '.', ',') }} USD</span>
            </div>
        </div>
    </div>
    
    <!-- Pagos Recibidos -->
    @if(is_array($pagos) && count($pagos) > 0)
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
                    @if(is_array($pago))
                        <tr>
                            <td>{{ $pago['descripcion'] ?? '' }}</td>
                            <td class="text-center">{{ isset($pago['fecha']) ? \Carbon\Carbon::parse($pago['fecha'])->format('d/m/Y') : '' }}</td>
                            <td class="text-right"><strong>${{ number_format(floatval($pago['importe'] ?? 0), 2, '.', ',') }}</strong></td>
                        </tr>
                    @endif
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
