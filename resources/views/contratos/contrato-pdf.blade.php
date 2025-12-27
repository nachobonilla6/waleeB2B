<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrato de Servicios - {{ $cliente->nombre_empresa }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 40px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 3px solid #D59F3B;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #D59F3B;
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #666;
            font-size: 11px;
        }
        
        .contract-info {
            margin-bottom: 30px;
        }
        
        .contract-info p {
            margin-bottom: 8px;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #D59F3B;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        
        .section-content {
            font-size: 11px;
            text-align: justify;
            margin-bottom: 15px;
        }
        
        .parties {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .party {
            width: 48%;
        }
        
        .party h3 {
            font-size: 13px;
            color: #D59F3B;
            margin-bottom: 10px;
        }
        
        .party p {
            font-size: 11px;
            margin-bottom: 5px;
        }
        
        .service-details {
            background-color: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #D59F3B;
            margin-bottom: 20px;
        }
        
        .service-details p {
            margin-bottom: 8px;
        }
        
        .service-details strong {
            color: #D59F3B;
        }
        
        .price {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            color: #D59F3B;
            margin: 20px 0;
        }
        
        .terms {
            margin-top: 30px;
        }
        
        .terms ol {
            margin-left: 20px;
        }
        
        .terms li {
            margin-bottom: 10px;
            font-size: 11px;
        }
        
        .signatures {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }
        
        .signature-box {
            width: 45%;
            text-align: center;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 50px;
            padding-top: 5px;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        
        @page {
            margin: 1cm;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>CONTRATO DE PRESTACIÓN DE SERVICIOS</h1>
        <p>Web Solutions - WALEÉ</p>
    </div>
    
    <div class="contract-info">
        <p><strong>Fecha:</strong> {{ now()->format('d/m/Y') }}</p>
        <p><strong>Número de Contrato:</strong> CONT-{{ now()->format('Ymd') }}-{{ str_pad($cliente->id, 4, '0', STR_PAD_LEFT) }}</p>
    </div>
    
    <div class="parties">
        <div class="party">
            <h3>PRESTADOR DE SERVICIOS</h3>
            <p><strong>Web Solutions - WALEÉ</strong></p>
            <p>Email: websolutionscrnow@gmail.com</p>
            <p>Costa Rica</p>
        </div>
        
        <div class="party">
            <h3>CLIENTE</h3>
            <p><strong>{{ $cliente->nombre_empresa }}</strong></p>
            @if($cliente->correo)
            <p>Email: {{ $cliente->correo }}</p>
            @endif
            @if($cliente->telefono)
            <p>Teléfono: {{ $cliente->telefono }}</p>
            @endif
            @if($cliente->direccion)
            <p>Dirección: {{ $cliente->direccion }}</p>
            @endif
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">1. OBJETO DEL CONTRATO</div>
        <div class="section-content">
            El presente contrato tiene por objeto la prestación de servicios de {{ $servicioNombre }} por parte de Web Solutions - WALEÉ a favor de {{ $cliente->nombre_empresa }}, de acuerdo con las condiciones establecidas en el presente documento.
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">2. SERVICIOS CONTRATADOS</div>
        <div class="service-details">
            <p><strong>Tipo de Servicio:</strong> {{ $servicioNombre }}</p>
            <p><strong>Descripción:</strong> {{ $servicioDescripcion }}</p>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">3. PRECIO Y FORMA DE PAGO</div>
        <div class="price">
            Precio Total: ₡{{ number_format($precio, 2, ',', '.') }}
        </div>
        <div class="section-content">
            El cliente se compromete a realizar el pago según las condiciones acordadas entre las partes. El pago podrá realizarse mediante transferencia bancaria, SINPE móvil, tarjeta de crédito o efectivo, según lo acordado.
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">4. OBLIGACIONES DEL PRESTADOR</div>
        <div class="section-content">
            Web Solutions - WALEÉ se compromete a:
            <ol>
                <li>Prestar los servicios contratados con la máxima calidad y profesionalismo.</li>
                <li>Cumplir con los plazos acordados para la entrega de los servicios.</li>
                <li>Mantener la confidencialidad de la información proporcionada por el cliente.</li>
                <li>Proporcionar soporte técnico según lo acordado en el servicio contratado.</li>
            </ol>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">5. OBLIGACIONES DEL CLIENTE</div>
        <div class="section-content">
            {{ $cliente->nombre_empresa }} se compromete a:
            <ol>
                <li>Proporcionar toda la información necesaria para la prestación del servicio.</li>
                <li>Realizar los pagos en los plazos acordados.</li>
                <li>Colaborar activamente en el desarrollo del proyecto.</li>
                <li>Respetar los términos y condiciones establecidos en el presente contrato.</li>
            </ol>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">6. DURACIÓN Y TERMINACIÓN</div>
        <div class="section-content">
            El presente contrato tendrá vigencia desde la fecha de firma hasta la finalización de los servicios contratados. Cualquiera de las partes podrá terminar el contrato mediante notificación escrita con 30 días de anticipación.
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">7. PROPIEDAD INTELECTUAL</div>
        <div class="section-content">
            Todos los derechos de propiedad intelectual sobre los trabajos realizados serán transferidos al cliente una vez que se haya completado el pago total del servicio. Hasta ese momento, Web Solutions - WALEÉ conservará los derechos sobre los materiales desarrollados.
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">8. CONFIDENCIALIDAD</div>
        <div class="section-content">
            Ambas partes se comprometen a mantener la confidencialidad de toda la información intercambiada durante la ejecución del presente contrato.
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">9. LEGISLACIÓN APLICABLE</div>
        <div class="section-content">
            El presente contrato se rige por la legislación de la República de Costa Rica. Cualquier controversia será resuelta mediante arbitraje o en los tribunales competentes de Costa Rica.
        </div>
    </div>
    
    <div class="signatures">
        <div class="signature-box">
            <p><strong>Web Solutions - WALEÉ</strong></p>
            <div class="signature-line">
                <p>Firma y Sello</p>
            </div>
        </div>
        <div class="signature-box">
            <p><strong>{{ $cliente->nombre_empresa }}</strong></p>
            <div class="signature-line">
                <p>Firma</p>
            </div>
        </div>
    </div>
    
    <div class="footer">
        <p>Este documento ha sido generado electrónicamente por Web Solutions - WALEÉ</p>
        <p>Para consultas: websolutionscrnow@gmail.com</p>
    </div>
</body>
</html>

