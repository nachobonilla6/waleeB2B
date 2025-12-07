<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura {{ $numeroFactura }} - WALEÉ</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .invoice-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .invoice-header h1 {
            font-size: 32px;
            margin-bottom: 10px;
            font-weight: 700;
        }
        .invoice-header p {
            font-size: 18px;
            opacity: 0.9;
        }
        .invoice-body {
            padding: 40px;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }
        .company-info, .client-info {
            flex: 1;
            min-width: 250px;
            margin-bottom: 20px;
        }
        .company-info h3, .client-info h3 {
            color: #10b981;
            font-size: 18px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #10b981;
        }
        .info-item {
            margin-bottom: 8px;
            font-size: 14px;
        }
        .info-label {
            font-weight: 600;
            color: #6b7280;
            display: inline-block;
            width: 120px;
        }
        .info-value {
            color: #111827;
        }
        .invoice-details {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 30px;
        }
        .invoice-details h3 {
            color: #10b981;
            font-size: 18px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #6b7280;
        }
        .detail-value {
            color: #111827;
            text-align: right;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table thead {
            background-color: #10b981;
            color: white;
        }
        .items-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }
        .items-table td {
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
        }
        .items-table tbody tr:hover {
            background-color: #f9fafb;
        }
        .items-table tbody tr:last-child td {
            border-bottom: none;
        }
        .text-right {
            text-align: right;
        }
        .totals-section {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 30px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            font-size: 16px;
        }
        .total-row.subtotal {
            border-bottom: 2px solid #e5e7eb;
            margin-bottom: 10px;
        }
        .total-row.grand-total {
            font-size: 24px;
            font-weight: 700;
            color: #10b981;
            margin-top: 10px;
            padding-top: 15px;
            border-top: 3px solid #10b981;
        }
        .total-label {
            font-weight: 600;
        }
        .notes-section {
            background-color: #fff7ed;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            border-radius: 4px;
            margin-bottom: 30px;
        }
        .notes-section h3 {
            color: #f59e0b;
            margin-bottom: 10px;
        }
        .invoice-footer {
            background-color: #1f2937;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .invoice-footer p {
            margin: 5px 0;
            font-size: 14px;
            opacity: 0.8;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-pendiente {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-pagada {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-vencida {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .status-cancelada {
            background-color: #f3f4f6;
            color: #374151;
        }
        @media (max-width: 600px) {
            .invoice-info {
                flex-direction: column;
            }
            .invoice-body {
                padding: 20px;
            }
            .items-table {
                font-size: 12px;
            }
            .items-table th,
            .items-table td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <h1>FACTURA</h1>
            <p>Nº {{ $numeroFactura }}</p>
        </div>

        <!-- Body -->
        <div class="invoice-body">
            <!-- Company and Client Info -->
            <div class="invoice-info">
                <div class="company-info">
                    <h3>WALEÉ</h3>
                    <div class="info-item">
                        <span class="info-label">Email:</span>
                        <span class="info-value">nachobonilla6@gmail.com</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Fecha Emisión:</span>
                        <span class="info-value">{{ $fechaEmision ?? 'N/A' }}</span>
                    </div>
                    @if(!empty($fechaVencimiento))
                    <div class="info-item">
                        <span class="info-label">Fecha Vencimiento:</span>
                        <span class="info-value">{{ $fechaVencimiento }}</span>
                    </div>
                    @endif
                </div>

                <div class="client-info">
                    <h3>Cliente</h3>
                    @if(!empty($clienteNombre))
                    <div class="info-item">
                        <span class="info-label">Nombre:</span>
                        <span class="info-value">{{ $clienteNombre }}</span>
                    </div>
                    @endif
                    @if(!empty($clienteCorreo))
                    <div class="info-item">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ $clienteCorreo }}</span>
                    </div>
                    @endif
                    <div class="info-item">
                        <span class="info-label">Estado:</span>
                        <span class="info-value">
                            @if($estado === 'pendiente')
                                <span class="status-badge status-pendiente">Pendiente</span>
                            @elseif($estado === 'pagada')
                                <span class="status-badge status-pagada">Pagada</span>
                            @elseif($estado === 'vencida')
                                <span class="status-badge status-vencida">Vencida</span>
                            @elseif($estado === 'cancelada')
                                <span class="status-badge status-cancelada">Cancelada</span>
                            @else
                                {{ $estado }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Invoice Details -->
            <div class="invoice-details">
                <h3>Detalles de la Factura</h3>
                <div class="detail-row">
                    <span class="detail-label">Concepto:</span>
                    <span class="detail-value">
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
                <div class="detail-row">
                    <span class="detail-label">Método de Pago:</span>
                    <span class="detail-value">
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

            <!-- Items Table -->
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th class="text-right">Subtotal</th>
                        <th class="text-right">IVA (13%)</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            @if($concepto === 'diseno_web') Diseño Web
                            @elseif($concepto === 'redes_sociales') Gestión Redes Sociales
                            @elseif($concepto === 'seo') SEO / Posicionamiento
                            @elseif($concepto === 'publicidad') Publicidad Digital
                            @elseif($concepto === 'mantenimiento') Mantenimiento Mensual
                            @elseif($concepto === 'hosting') Hosting & Dominio
                            @else {{ $concepto }}
                            @endif
                        </td>
                        <td class="text-right">${{ number_format($subtotal ?? 0, 2) }}</td>
                        <td class="text-right">${{ number_format(($total ?? 0) - ($subtotal ?? 0), 2) }}</td>
                        <td class="text-right"><strong>${{ number_format($total ?? 0, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>

            <!-- Totals -->
            <div class="totals-section">
                <div class="total-row subtotal">
                    <span class="total-label">Subtotal:</span>
                    <span>${{ number_format($subtotal ?? 0, 2) }} USD</span>
                </div>
                <div class="total-row">
                    <span class="total-label">IVA (13%):</span>
                    <span>${{ number_format(($total ?? 0) - ($subtotal ?? 0), 2) }} USD</span>
                </div>
                <div class="total-row grand-total">
                    <span class="total-label">TOTAL:</span>
                    <span>${{ number_format($total ?? 0, 2) }} USD</span>
                </div>
            </div>

            <!-- Notes -->
            @if(!empty($notas))
            <div class="notes-section">
                <h3>Notas Adicionales</h3>
                <p style="white-space: pre-wrap; margin: 0;">{{ $notas }}</p>
            </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="invoice-footer">
            <p><strong>WALEÉ</strong></p>
            <p>Esta es una factura generada automáticamente.</p>
            <p>Para consultas, contacta a: nachobonilla6@gmail.com</p>
        </div>
    </div>
</body>
</html>
