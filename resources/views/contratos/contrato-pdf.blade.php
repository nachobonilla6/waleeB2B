<!DOCTYPE html>
<html lang="{{ $idioma }}" dir="{{ $idioma == 'zh' ? 'ltr' : ($idioma == 'ar' ? 'rtl' : 'ltr') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Software Creation & Digital Services Contract - {{ $cliente->nombre_empresa }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #000;
            padding: 20mm;
            background: #fff;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        
        .header h1 {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        
        .header .subtitle {
            font-size: 10pt;
            font-style: italic;
        }
        
        .contract-title {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            margin: 20px 0;
            text-transform: uppercase;
        }
        
        .parties {
            margin: 20px 0;
        }
        
        .party-section {
            margin-bottom: 15px;
        }
        
        .party-label {
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 5px;
        }
        
        .party-name {
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        .party-details {
            font-size: 10pt;
            margin-left: 10px;
        }
        
        .section {
            margin: 15px 0;
            text-align: justify;
        }
        
        .section-title {
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 8px;
            text-decoration: underline;
        }
        
        .section-number {
            font-weight: bold;
            text-decoration: underline;
        }
        
        .subsection {
            margin-left: 15px;
            margin-top: 8px;
        }
        
        .subsection-title {
            font-weight: bold;
            margin-bottom: 5px;
            text-decoration: underline;
        }
        
        .price-highlight {
            font-weight: bold;
            font-size: 11pt;
            margin: 5px 0;
        }
        
        .amount {
            font-weight: bold;
            text-decoration: underline;
        }
        
        .note {
            font-style: italic;
            font-size: 10pt;
            margin-top: 5px;
        }
        
        .signature-section {
            margin-top: 40px;
            page-break-inside: avoid;
        }
        
        .signature-box {
            display: inline-block;
            width: 48%;
            vertical-align: top;
            margin-top: 60px;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 50px;
            padding-top: 5px;
            text-align: center;
            font-size: 10pt;
        }
        
        .date-line {
            border-top: 1px solid #000;
            margin-top: 20px;
            padding-top: 5px;
            text-align: center;
            font-size: 10pt;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9pt;
            font-style: italic;
        }
        
        ul {
            margin-left: 20px;
            margin-top: 5px;
        }
        
        li {
            margin-bottom: 5px;
        }
        
        @page {
            margin: 20mm;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="subtitle">{{ $t['header_subtitle'] }}</div>
        <h1>{{ $t['contract_title'] }}</h1>
    </div>
    
    <div class="parties">
        <div class="party-section">
            <div class="party-label">{{ $t['between'] }}</div>
        </div>
        
        <div class="party-section">
            <div class="party-label">{{ $t['service_provider'] }}</div>
            <div class="party-name">{{ $t['service_provider_name'] }}</div>
            <div class="party-details">{{ $t['service_provider_location'] }}</div>
            <div class="party-details">{{ $t['service_provider_referred'] }}</div>
        </div>
        
        <div class="party-section">
            <div class="party-label">{{ $t['client'] }}</div>
            <div class="party-name">{{ $cliente->nombre_empresa }}@if(isset($cliente->contacto_nombre)) – {{ $cliente->contacto_nombre }}@endif</div>
            <div class="party-details">{{ $t['client_referred'] }}</div>
            @if($cliente->direccion || $cliente->ciudad)
            <div class="party-details">{{ $t['client_business_location'] }}</div>
            <div class="party-details">
                @if($cliente->direccion){{ $cliente->direccion }}, @endif
                @if($cliente->ciudad){{ $cliente->ciudad }}, @endif
                @if($cliente->estado){{ $cliente->estado }}, @endif
                Costa Rica
            </div>
            @endif
        </div>
    </div>
    
    <div class="section">
        <p><strong>{{ $t['agreed'] }}</strong></p>
    </div>
    
    <div class="section">
        <div class="section-title"><span class="section-number">1.</span> {{ $t['section_1'] }}</div>
        <p>{{ $t['section_1_text'] }} {{ strtolower($servicioNombre) }}, including specifically:</p>
        
        <div class="subsection">
            <div class="subsection-title">1.1 {{ $t['section_1_1'] }}</div>
            <p>{{ $t['section_1_1_services'] }}</p>
            <ul>
                <li>{{ $t['section_1_1_list_1'] }}</li>
                <li>{{ $t['section_1_1_list_2'] }}</li>
                <li>{{ $t['section_1_1_list_3'] }}</li>
            </ul>
            <p>{{ $t['section_1_1_specified'] }} {{ now()->format('F d, Y') }}, {{ $t['section_1_1_subscribed'] }}</p>
            <p>{{ $t['section_1_1_price'] }}</p>
            <p class="price-highlight">{{ $t['section_1_1_final_price'] }} <span class="amount">{{ number_format($precio, 2, ',', '.') }} CRC ({{ number_format($precio / 520, 2, '.', ',') }} USD)</span></p>
            <p class="note">{{ $t['section_1_1_note'] }}</p>
        </div>
        
        <div class="subsection">
            <div class="subsection-title">1.2 {{ $t['section_1_2'] }}</div>
            <p>{{ $t['section_1_2_text'] }}</p>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title"><span class="section-number">2.</span> {{ $t['section_2'] }}</div>
        <p>{{ $t['section_2_text'] }}</p>
        <ul>
            <li>{{ $t['section_2_list_1'] }}</li>
            <li>{{ $t['section_2_list_2'] }}</li>
            <li>{{ $t['section_2_list_3'] }}</li>
            <li>{{ $t['section_2_list_4'] }}</li>
        </ul>
        <p>{{ $t['section_2_features'] }}</p>
    </div>
    
    <div class="section">
        <div class="section-title"><span class="section-number">3.</span> {{ $t['section_3'] }}</div>
        
        <div class="subsection">
            <div class="subsection-title">3.1 {{ $t['section_3_1'] }}</div>
            <p>{{ $t['section_3_1_text'] }}</p>
            <p class="price-highlight"><span class="amount">{{ number_format($precio, 2, ',', '.') }} CRC ({{ number_format($precio / 520, 2, '.', ',') }} USD)</span></p>
        </div>
        
        <div class="subsection">
            <div class="subsection-title">3.2 {{ $t['section_3_2'] }}</div>
            <p>{{ $t['section_3_2_text'] }}</p>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title"><span class="section-number">4.</span> {{ $t['section_4'] }}</div>
        <p>{{ $t['section_4_text'] }}</p>
        <p class="price-highlight">{{ $t['section_4_package'] }}</p>
        <p>{{ $t['section_4_includes'] }}</p>
        <ul>
            <li>{{ $t['section_4_list_1'] }}</li>
            <li>{{ $t['section_4_list_2'] }}</li>
            <li>{{ $t['section_4_list_3'] }}</li>
            <li>{{ $t['section_4_list_4'] }}</li>
        </ul>
        <p>{{ $t['section_4_terms'] }}</p>
    </div>
    
    <div class="section">
        <div class="section-title"><span class="section-number">5.</span> {{ $t['section_5'] }}</div>
        <p>{{ $t['section_5_text'] }}</p>
        <p>{{ $t['section_5_rates'] }}</p>
    </div>
    
    <div class="section">
        <div class="section-title"><span class="section-number">6.</span> {{ $t['section_6'] }}</div>
        <p>{{ $t['section_6_text'] }}</p>
    </div>
    
    <div class="section">
        <div class="section-title"><span class="section-number">7.</span> {{ $t['section_7'] }}</div>
        <p>{{ $t['section_7_text_1'] }}</p>
        <p>{{ $t['section_7_text_2'] }}</p>
        <p>{{ $t['section_7_text_3'] }}</p>
    </div>
    
    <div class="section">
        <div class="section-title"><span class="section-number">8.</span> {{ $t['section_8'] }}</div>
        <p>{{ $t['section_8_text_1'] }}</p>
        <p>{{ $t['section_8_text_2'] }}</p>
        
        <div class="subsection">
            <div class="subsection-title">8.1 {{ $t['section_8_1'] }}</div>
            <p>{{ $t['section_8_1_text_1'] }}</p>
            <p>{{ $t['section_8_1_text_2'] }}</p>
            <ul>
                <li>{{ $t['section_8_1_list_1'] }}</li>
                <li>{{ $t['section_8_1_list_2'] }}</li>
                <li>{{ $t['section_8_1_list_3'] }}</li>
            </ul>
            <p>{{ $t['section_8_1_text_3'] }}</p>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title"><span class="section-number">9.</span> {{ $t['section_9'] }}</div>
        <p>{{ $t['section_9_text'] }}</p>
    </div>
    
    <div class="section">
        <div class="section-title"><span class="section-number">10.</span> {{ $t['section_10'] }}</div>
        <p>{{ $t['section_10_text_1'] }}</p>
        <p>{{ $t['section_10_text_2'] }}</p>
        <p>{{ $t['section_10_text_3'] }}</p>
        <p>{{ $t['section_10_text_4'] }}</p>
        <p><strong>{{ $t['section_10_done'] }}</strong></p>
    </div>
    
    <div class="signature-section">
        <div class="signature-box">
            <div class="date-line">{{ $t['signature_at'] }} ________________________, {{ $t['signature_on'] }} {{ now()->format('d / m / Y') }}</div>
            <div class="signature-line">{{ $t['signature_provider'] }}</div>
        </div>
        <div class="signature-box">
            <div class="date-line"></div>
            <div class="signature-line">{{ $t['signature_client'] }}</div>
        </div>
    </div>
    
    <div class="footer">
        <p>{{ $t['footer_company'] }}</p>
        <p>{{ $t['footer_generated'] }} {{ now()->format('F d, Y') }}</p>
    </div>
</body>
</html>
