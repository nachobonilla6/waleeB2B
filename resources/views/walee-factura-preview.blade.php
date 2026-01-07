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
            border-bottom: 2px solid #8b5cf6;
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
        .factura-number-top {
            font-size: 16pt;
            font-weight: bold;
            color: #8b5cf6;
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
            border-left: 3px solid #8b5cf6;
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }
        .datos-title {
            font-weight: bold;
            color: #8b5cf6;
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
        .signature-section {
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
            gap: 40px;
        }
        .signature-box {
            font-size: 8pt;
            color: #555;
            text-align: center;
            width: 45%;
        }
        .signature-line {
            margin-top: 30px;
            border-top: 1px solid #cbd5e1;
            padding-top: 4px;
        }
        .signature-date-line {
            display: inline-block;
            min-width: 90px;
            border-bottom: 1px solid #cbd5e1;
            margin-left: 4px;
        }
    </style>
</head>
<body>
    @php
        $cliente = \App\Models\Cliente::find($data['cliente_id'] ?? null);
        $lang = $language ?? ($data['language'] ?? 'en');
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

        $translations = [
            'en' => [
                'invoice' => 'Invoice',
                'invoice_no' => 'Invoice No.',
                'order_no' => 'Order No.',
                'issue_date' => 'Issue Date',
                'due_date' => 'Due Date',
                'payment_terms' => 'Payment Terms',
                'payment_terms_value' => 'Net 30 days',
                'currency' => 'Currency',
                'tax_rate' => 'Tax Rate',
                'payment_method' => 'Payment Method',
                'bill_to' => 'Bill To',
                'invoice_details' => 'Invoice Details',
                'description' => 'Description',
                'qty' => 'Qty',
                'unit_price' => 'Unit Price',
                'tax' => 'Tax (13%)',
                'amount' => 'Amount',
                'no_items' => 'There are no items on this invoice',
                'discount_before' => 'Discount before tax',
                'subtotal' => 'Subtotal (excl. tax)',
                'vat' => 'VAT (13%)',
                'discount_after' => 'Discount after tax',
                'total_usd' => 'Total (USD)',
                'amount_paid' => 'Amount paid',
                'amount_due' => 'Amount due (USD)',
                'payments' => 'Payments',
                'payments_desc' => 'Description',
                'payments_date' => 'Date',
                'payments_amount' => 'Amount',
                'terms' => 'Terms and Conditions',
                'legal' => 'Legal Notice',
                'currency_note' => 'USD (US Dollar)',
                'page' => 'Page',
                'of' => 'of',
                'seller_signature' => 'Seller signature',
                'client_signature' => 'Client signature',
                'signature_date' => 'Date',
            ],
            'es' => [
                'invoice' => 'Factura',
                'invoice_no' => 'Factura Nº',
                'order_no' => 'N° Orden',
                'issue_date' => 'Fecha de emisión',
                'due_date' => 'Fecha de vencimiento',
                'payment_terms' => 'Términos de pago',
                'payment_terms_value' => 'Pago a 30 días',
                'currency' => 'Moneda',
                'tax_rate' => 'Tasa de impuesto',
                'payment_method' => 'Método de pago',
                'bill_to' => 'Datos del cliente',
                'invoice_details' => 'Detalles de la factura',
                'description' => 'Descripción',
                'qty' => 'Cant.',
                'unit_price' => 'Precio unitario',
                'tax' => 'Impuestos (13%)',
                'amount' => 'Importe',
                'no_items' => 'No hay items en esta factura',
                'discount_before' => 'Descuento antes de impuestos',
                'subtotal' => 'Subtotal (sin impuestos)',
                'vat' => 'IVA (13%)',
                'discount_after' => 'Descuento después de impuestos',
                'total_usd' => 'Total (USD)',
                'amount_paid' => 'Monto pagado',
                'amount_due' => 'Monto pendiente (USD)',
                'payments' => 'Pagos',
                'payments_desc' => 'Descripción',
                'payments_date' => 'Fecha',
                'payments_amount' => 'Importe',
                'terms' => 'Términos y Condiciones',
                'legal' => 'Aviso Legal',
                'currency_note' => 'USD (Dólar estadounidense)',
                'page' => 'Página',
                'of' => 'de',
                'seller_signature' => 'Firma del vendedor',
                'client_signature' => 'Firma del cliente',
                'signature_date' => 'Fecha',
            ],
            'fr' => [
                'invoice' => 'Facture',
                'invoice_no' => 'N° de facture',
                'order_no' => 'N° de commande',
                'issue_date' => 'Date d’émission',
                'due_date' => 'Date d’échéance',
                'payment_terms' => 'Conditions de paiement',
                'payment_terms_value' => 'Net 30 jours',
                'currency' => 'Devise',
                'tax_rate' => 'Taux de taxe',
                'payment_method' => 'Mode de paiement',
                'bill_to' => 'Coordonnées du client',
                'invoice_details' => 'Détails de la facture',
                'description' => 'Description',
                'qty' => 'Qté',
                'unit_price' => 'Prix unitaire',
                'tax' => 'Taxe (13%)',
                'amount' => 'Montant',
                'no_items' => 'Aucun article dans cette facture',
                'discount_before' => 'Remise avant taxes',
                'subtotal' => 'Sous-total (HT)',
                'vat' => 'TVA (13%)',
                'discount_after' => 'Remise après taxes',
                'total_usd' => 'Total (USD)',
                'amount_paid' => 'Montant payé',
                'amount_due' => 'Montant dû (USD)',
                'payments' => 'Paiements',
                'payments_desc' => 'Description',
                'payments_date' => 'Date',
                'payments_amount' => 'Montant',
                'terms' => 'Termes et conditions',
                'legal' => 'Mentions légales',
                'currency_note' => 'USD (Dollar US)',
                'page' => 'Page',
                'of' => 'sur',
                'seller_signature' => 'Signature du vendeur',
                'client_signature' => 'Signature du client',
                'signature_date' => 'Date',
            ],
        ];

        $t = $translations[$lang] ?? $translations['en'];
    @endphp
    
    <!-- Invoice number at the top -->
    <div class="factura-number-top">
        {{ $t['invoice'] ?? 'Invoice' }} #{{ $data['numero_factura'] ?? 'N/A' }}
    </div>
    
    <div class="header">
        <div class="header-left">
            <div class="logo">WS</div>
            <div>
                <div style="font-weight: bold; font-size: 11pt; color: #8b5cf6;">WebSolutions.Work</div>
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
            <div class="datos-title">{{ $t['bill_to'] ?? 'Bill To' }}</div>
            <div class="datos-content">
                @if($cliente)
                    <div><strong>{{ $cliente->nombre_empresa }}</strong></div>
                    @if($cliente->direccion)
                        <div>{{ $cliente->direccion }}</div>
                    @endif
                    @if($cliente->ciudad || $cliente->estado || $cliente->pais)
                        <div>
                            {{ $cliente->ciudad ?? '' }}
                            @if($cliente->ciudad && ($cliente->estado || $cliente->pais)), @endif
                            {{ $cliente->estado ?? '' }}
                            @if(($cliente->ciudad || $cliente->estado) && $cliente->pais), @endif
                            {{ $cliente->pais ?? 'Costa Rica' }}
                        </div>
                    @else
                        <div>Costa Rica</div>
                    @endif
                    @if($cliente->codigo_postal)
                        <div>Postal Code: {{ $cliente->codigo_postal }}</div>
                    @endif
                    @if($cliente->telefono)
                        <div>Tel: {{ $cliente->telefono }}</div>
                    @endif
                    @if($cliente->correo)
                        <div>Email: {{ $cliente->correo }}</div>
                    @endif
                @else
                    <div><strong>{{ $data['correo'] ?? 'N/A' }}</strong></div>
                    <div>Unregistered customer</div>
                @endif
            </div>
        </div>
        
        <div class="datos-box">
            <div class="datos-title">{{ $t['invoice_details'] ?? 'Invoice Details' }}</div>
            <div class="datos-content">
                <div><strong>{{ $t['invoice_no'] ?? 'Invoice No.' }}:</strong> {{ $data['serie'] ?? 'A' }}-{{ str_pad($data['numero_factura'] ?? 'N/A', 4, '0', STR_PAD_LEFT) }}</div>
                <div><strong>{{ $t['order_no'] ?? 'Order No.' }}:</strong> {{ $data['numero_orden'] ?? ($data['numero_factura'] ?? 'N/A') }}</div>
                <div><strong>{{ $t['issue_date'] ?? 'Issue Date' }}:</strong> {{ isset($data['fecha_emision']) ? \Carbon\Carbon::parse($data['fecha_emision'])->format('d/m/Y') : date('d/m/Y') }}</div>
                @if(!empty($data['fecha_vencimiento']))
                    <div><strong>{{ $t['due_date'] ?? 'Due Date' }}:</strong> {{ \Carbon\Carbon::parse($data['fecha_vencimiento'])->format('d/m/Y') }}</div>
                @else
                    <div><strong>{{ $t['payment_terms'] ?? 'Payment Terms' }}:</strong> {{ $t['payment_terms_value'] ?? 'Net 30 days' }}</div>
                @endif
                <div><strong>{{ $t['currency'] ?? 'Currency' }}:</strong> {{ $t['currency_note'] ?? 'USD' }}</div>
                <div><strong>{{ $t['tax_rate'] ?? 'Tax Rate' }}:</strong> 13% VAT</div>
                @if(!empty($data['metodo_pago']) && $data['metodo_pago'] !== 'sin_especificar')
                    <div><strong>{{ $t['payment_method'] ?? 'Payment Method' }}:</strong> {{ ucfirst(str_replace('_', ' ', $data['metodo_pago'])) }}</div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Items table -->
    <table>
        <thead>
            <tr>
                <th>{{ $t['description'] ?? 'Description' }}</th>
                <th class="text-center">{{ $t['qty'] ?? 'Qty' }}</th>
                <th class="text-right">{{ $t['unit_price'] ?? 'Unit Price' }}</th>
                <th class="text-right">{{ $t['tax'] ?? 'Tax' }}</th>
                <th class="text-right">{{ $t['amount'] ?? 'Amount' }}</th>
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
                    <td colspan="5" class="text-center" style="padding: 20px; color: #999;">{{ $t['no_items'] ?? 'There are no items on this invoice' }}</td>
                </tr>
            @endif
        </tbody>
    </table>
    
    <!-- Totals -->
    <div class="totals-section">
        <div class="totals-table">
            @if($descuentoAntes > 0)
            <div class="totals-row">
                <span>{{ $t['discount_before'] ?? 'Discount before tax' }}:</span>
                <span style="color: #dc2626;">-${{ number_format($descuentoAntes, 2, '.', ',') }}</span>
            </div>
            @endif
            
            <div class="totals-row">
                <span>{{ $t['subtotal'] ?? 'Subtotal' }}:</span>
                <span><strong>${{ number_format($subtotalConDescuento, 2, '.', ',') }}</strong></span>
            </div>
            
            <div class="totals-row">
                <span>{{ $t['vat'] ?? 'VAT' }}:</span>
                <span><strong>${{ number_format($iva, 2, '.', ',') }}</strong></span>
            </div>
            
            @if($descuentoDespues > 0)
            <div class="totals-row">
                <span>{{ $t['discount_after'] ?? 'Discount after tax' }}:</span>
                <span style="color: #dc2626;">-${{ number_format($descuentoDespues, 2, '.', ',') }}</span>
            </div>
            @endif
            
            <div class="totals-row total">
                <span>{{ $t['total_usd'] ?? 'Total' }}:</span>
                <span>${{ number_format($total, 2, '.', ',') }}</span>
            </div>
            
            <div class="totals-row">
                <span>{{ $t['amount_paid'] ?? 'Amount paid' }}:</span>
                <span><strong>${{ number_format($montoPagado, 2, '.', ',') }}</strong></span>
            </div>
            <div class="totals-row">
                @php
                    $amountDue = max($total - $montoPagado, 0);
                @endphp
                <span><strong>{{ $t['amount_due'] ?? 'Amount due' }}:</strong></span>
                <span><strong>${{ number_format($amountDue, 2, '.', ',') }}</strong></span>
            </div>
        </div>
    </div>
    
    <!-- Payments -->
    @if(is_array($pagos) && count($pagos) > 0)
    <div class="pagos-section">
        <div class="pagos-title">{{ $t['payments'] ?? 'Payments' }}</div>
        <table>
            <thead>
                <tr>
                    <th>{{ $t['payments_desc'] ?? 'Description' }}</th>
                    <th class="text-center">{{ $t['payments_date'] ?? 'Date' }}</th>
                    <th class="text-right">{{ $t['payments_amount'] ?? 'Amount' }}</th>
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
        <strong>{{ $t['terms'] ?? 'Terms and Conditions' }}</strong><br>
        {{ $data['notas'] }}
    </div>
    @endif
    
    <!-- Legal information -->
    <div class="legal-info">
        <strong>{{ $t['legal'] ?? 'Legal Notice' }}</strong><br>
        @if($lang === 'es')
            Esta factura ha sido generada electrónicamente y es válida sin firma ni sello. Todos los montos están expresados en dólares estadounidenses (USD) e incluyen los impuestos detallados arriba. Cualquier disputa sobre esta factura debe notificarse por escrito dentro de los 30 días posteriores a la fecha de emisión.
        @elseif($lang === 'fr')
            Cette facture a été générée électroniquement et est valable sans signature ni cachet. Tous les montants sont exprimés en dollars américains (USD) et incluent les taxes détaillées ci-dessus. Toute contestation relative à cette facture doit être notifiée par écrit dans un délai de 30 jours à compter de la date d’émission.
        @else
            This invoice has been generated electronically and is valid without signature or stamp. All amounts are expressed in US Dollars (USD) and include taxes as detailed above. Any disputes regarding this invoice must be notified in writing within 30 days from the issue date.
        @endif
    </div>
    
    <!-- Signatures -->
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div>{{ $t['seller_signature'] ?? 'Seller signature' }}</div>
        </div>
        <div class="signature-box">
            <div style="display: flex; justify-content: space-between; gap: 24px; align-items: flex-end;">
                <!-- Columna firma cliente -->
                <div style="flex: 1; text-align: center;">
                    <div class="signature-line"></div>
                    <div>{{ $t['client_signature'] ?? 'Client signature' }}</div>
                </div>
                <!-- Columna fecha -->
                <div style="flex: 1; text-align: right;">
                    <div style="margin-top: 30px; font-size: 7pt; color: #6b7280;">
                        {{ $t['signature_date'] ?? 'Date' }}:
                        <span class="signature-date-line">&nbsp;</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="page-number">
        {{ $t['page'] ?? 'Page' }} 1 {{ $t['of'] ?? 'of' }} 1
    </div>
    
</body>
</html>