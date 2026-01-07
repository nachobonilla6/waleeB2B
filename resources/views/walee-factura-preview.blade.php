<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $data['numero_factura'] ?? 'N/A' }}</title>
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
            border-bottom: 2px solid #D59F3B;
            padding-bottom: 8px;
        }
        .header-left {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            flex: 1;
        }
        .logo {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #D59F3B 0%, #C78F2E 100%);
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
        .factura-number-top {
            font-size: 16pt;
            font-weight: bold;
            color: #D59F3B;
            text-align: right;
            margin-bottom: 8px;
            padding-bottom: 6px;
            border-bottom: 1px solid #e5e7eb;
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
            display: grid;
            grid-template-columns: 1fr 1fr;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 10px;
        }
        .datos-box {
            background: #f9fafb;
            padding: 10px;
            border-radius: 6px;
            border-left: 3px solid #D59F3B;
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }
        .datos-title {
            font-weight: bold;
            color: #D59F3B;
            margin-bottom: 6px;
            font-size: 10pt;
            line-height: 1.2;
            margin-top: 0;
            padding-top: 0;
        }
        .datos-content {
            font-size: 9pt;
            color: #555;
            line-height: 1.5;
            margin-top: 0;
        }
        .datos-content > div {
            margin-bottom: 3px;
        }
        .datos-content > div:last-child {
            margin-bottom: 0;
        }
        .datos-box.cliente-box {
            text-align: left;
            align-items: flex-start;
        }
        .datos-box.cliente-box .datos-title {
            text-align: left;
            margin-top: 0;
            padding-top: 0;
        }
        .datos-box.cliente-box .datos-content {
            text-align: left;
            margin-top: 0;
            align-items: flex-start;
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
            background-color: #D59F3B;
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
            background-color: #D59F3B;
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
            color: #D59F3B;
            margin-bottom: 6px;
        }
        .terminos {
            margin-top: 15px;
            padding: 10px;
            background: #f9fafb;
            border-left: 3px solid #D59F3B;
            border-radius: 4px;
            font-size: 8pt;
            color: #555;
            line-height: 1.4;
        }
        .legal-info {
            margin-top: 15px;
            padding: 12px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            font-size: 7pt;
            color: #666;
            line-height: 1.4;
        }
        .legal-info strong {
            color: #333;
            font-size: 7.5pt;
        }
        .tax-info {
            margin-top: 8px;
            padding: 8px;
            background: #fef3c7;
            border-left: 3px solid #D59F3B;
            border-radius: 4px;
            font-size: 8pt;
            color: #92400e;
        }
        .fiscal-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 8px;
            font-size: 8pt;
        }
        .fiscal-item {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            border-bottom: 1px dotted #e5e7eb;
        }
        .fiscal-label {
            font-weight: bold;
            color: #666;
        }
        .fiscal-value {
            color: #333;
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
    
    <!-- Invoice number at the top -->
    <div class="factura-number-top">
        Invoice #{{ $data['numero_factura'] ?? 'N/A' }}
    </div>
    
    <div class="header">
        <div class="header-left">
            <div class="logo">WS</div>
            <div>
                <div style="font-weight: bold; font-size: 11pt; color: #D59F3B;">WebSolutions.Work</div>
                <div style="font-size: 8pt; color: #666;">
                    Jaco, Puntarenas, Costa Rica · Tel: +506 8806 1829
                </div>
                <div style="font-size: 8pt; color: #666;">
                    Email: websolutionscrnow@gmail.com · Web: websolutions.work
                </div>
                <div style="font-size: 8pt; color: #666; margin-top: 2px;">
                    Tax ID: CR-3-101-XXXXXX
                </div>
            </div>
        </div>
        <div class="header-right">
            <div class="estado estado-{{ $estado }}">
                {{ ucfirst($estado) }}
            </div>
        </div>
    </div>
    
    <!-- Customer and invoice details -->
    <div class="datos-section">
        <div class="datos-box cliente-box">
            <div class="datos-title">Bill To</div>
            <div class="datos-content">
                @if($cliente)
                    <div><strong>{{ $cliente->nombre_empresa }}</strong></div>
                    @if($cliente->direccion)
                        <div>{{ $cliente->direccion }}</div>
                    @endif
                    @if($cliente->ciudad || $cliente->pais)
                        <div>{{ $cliente->ciudad ?? '' }}{{ $cliente->ciudad && $cliente->pais ? ', ' : '' }}{{ $cliente->pais ?? 'Costa Rica' }}</div>
                    @else
                        <div>Costa Rica</div>
                    @endif
                    @if($cliente->telefono)
                        <div>Tel: {{ $cliente->telefono }}</div>
                    @endif
                    @if($cliente->correo)
                        <div>Email: {{ $cliente->correo }}</div>
                    @endif
                    @if($cliente->codigo_postal)
                        <div>Postal Code: {{ $cliente->codigo_postal }}</div>
                    @endif
                @else
                    <div><strong>{{ $data['correo'] ?? 'N/A' }}</strong></div>
                    <div>Unregistered customer</div>
                @endif
            </div>
        </div>
        
        <div class="datos-box">
            <div class="datos-title">Invoice Details</div>
            <div class="datos-content">
                <div><strong>Invoice No.:</strong> {{ $data['serie'] ?? 'A' }}-{{ str_pad($data['numero_factura'] ?? 'N/A', 4, '0', STR_PAD_LEFT) }}</div>
                <div><strong>Order No.:</strong> {{ $data['numero_orden'] ?? ($data['numero_factura'] ?? 'N/A') }}</div>
                <div><strong>Issue Date:</strong> {{ isset($data['fecha_emision']) ? \Carbon\Carbon::parse($data['fecha_emision'])->format('d/m/Y') : date('d/m/Y') }}</div>
                @if(!empty($data['fecha_vencimiento']))
                    <div><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($data['fecha_vencimiento'])->format('d/m/Y') }}</div>
                @else
                    <div><strong>Payment Terms:</strong> Net 30 days</div>
                @endif
                <div><strong>Currency:</strong> USD (US Dollar)</div>
                <div><strong>Tax Rate:</strong> 13% VAT</div>
                @if(!empty($data['metodo_pago']) && $data['metodo_pago'] !== 'sin_especificar')
                    <div><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $data['metodo_pago'])) }}</div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Items table -->
    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Tax (13%)</th>
                <th class="text-right">Amount</th>
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
                    <td colspan="5" class="text-center" style="padding: 20px; color: #999;">There are no items on this invoice</td>
                </tr>
            @endif
        </tbody>
    </table>
    
    <!-- Totals -->
    <div class="totals-section">
        <div class="totals-table">
            @if($descuentoAntes > 0)
            <div class="totals-row">
                <span>Discount before tax:</span>
                <span style="color: #dc2626;">-${{ number_format($descuentoAntes, 2, '.', ',') }}</span>
            </div>
            @endif
            
            <div class="totals-row">
                <span>Subtotal (excl. tax):</span>
                <span><strong>${{ number_format($subtotalConDescuento, 2, '.', ',') }}</strong></span>
            </div>
            
            <div class="totals-row">
                <span>VAT (13%):</span>
                <span><strong>${{ number_format($iva, 2, '.', ',') }}</strong></span>
            </div>
            
            @if($descuentoDespues > 0)
            <div class="totals-row">
                <span>Discount after tax:</span>
                <span style="color: #dc2626;">-${{ number_format($descuentoDespues, 2, '.', ',') }}</span>
            </div>
            @endif
            
            <div class="totals-row total">
                <span>Total (USD):</span>
                <span>${{ number_format($total, 2, '.', ',') }}</span>
            </div>
            
            <div class="totals-row">
                <span>Amount paid:</span>
                <span><strong>${{ number_format($montoPagado, 2, '.', ',') }}</strong></span>
            </div>
            <div class="totals-row">
                @php
                    $amountDue = max($total - $montoPagado, 0);
                @endphp
                <span><strong>Amount due (USD):</strong></span>
                <span><strong>${{ number_format($amountDue, 2, '.', ',') }}</strong></span>
            </div>
        </div>
    </div>
    
    <!-- Payments -->
    @if(is_array($pagos) && count($pagos) > 0)
    <div class="pagos-section">
        <div class="pagos-title">Payments</div>
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-center">Date</th>
                    <th class="text-right">Amount</th>
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
    
    <!-- Terms and conditions -->
    @if(!empty($data['notas']))
    <div class="terminos">
        <strong>Terms and Conditions</strong><br>
        {{ $data['notas'] }}
    </div>
    @endif
    
    <!-- Legal information -->
    <div class="legal-info">
        <strong>Legal Notice</strong><br>
        This invoice has been generated electronically and is valid without signature or stamp. All amounts are expressed in US Dollars (USD) and include taxes as detailed above. Any disputes regarding this invoice must be notified in writing within 30 days from the issue date.
    </div>
    
    <div class="page-number">
        Page 1 of 1
    </div>
    
</body>
</html>