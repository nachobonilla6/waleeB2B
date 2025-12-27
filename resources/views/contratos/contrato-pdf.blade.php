<!DOCTYPE html>
<html lang="es" dir="ltr">
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
        }
        
        .section-number {
            font-weight: bold;
        }
        
        .subsection {
            margin-left: 15px;
            margin-top: 8px;
        }
        
        .subsection-title {
            font-weight: bold;
            margin-bottom: 5px;
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
        <div class="subtitle">🌍 Bilingual Version EN / ESP</div>
        <h1>SOFTWARE CREATION & DIGITAL SERVICES CONTRACT</h1>
    </div>
    
    <div class="parties">
        <div class="party-section">
            <div class="party-label">Between the undersigned:</div>
        </div>
        
        <div class="party-section">
            <div class="party-label">Service Provider:</div>
            <div class="party-name">Web Solutions CR – WHENSOL</div>
            <div class="party-details">Company location: Jacó, Puntarenas, Costa Rica</div>
            <div class="party-details">(hereinafter referred to as "the Service Provider")</div>
        </div>
        
        <div class="party-section">
            <div class="party-label">Client:</div>
            <div class="party-name">{{ $cliente->nombre_empresa }}@if(isset($cliente->contacto_nombre)) – {{ $cliente->contacto_nombre }}@endif</div>
            <div class="party-details">(hereinafter referred to as "the Client")</div>
            @if($cliente->direccion || $cliente->ciudad)
            <div class="party-details">Client's business location:</div>
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
        <p><strong>It has been agreed as follows:</strong></p>
    </div>
    
    <div class="section">
        <div class="section-title"><span class="section-number">1.</span> Purpose of the Contract</div>
        <p>The purpose of this contract is the provision of digital services intended for a {{ strtolower($servicioNombre) }} business, including specifically:</p>
        
        <div class="subsection">
            <div class="subsection-title">1.1 Website & Digital Presence</div>
            <p>The following services:</p>
            <ul>
                <li>Creation of a professional website;</li>
                <li>Upgrade of two (2) social media accounts: Facebook and Instagram;</li>
                <li>Setup and optimization of Google presence.</li>
            </ul>
            <p>It is specified that on {{ now()->format('F d, Y') }}, the Client subscribed to this initial package.</p>
            <p>The initial price was calculated based on the service requirements.</p>
            <p class="price-highlight">Final applied price: <span class="amount">₡{{ number_format($precio, 2, ',', '.') }} ({{ number_format($precio / 520, 2, '.', ',') }} USD)</span></p>
            <p class="note">This price is based on the specific services contracted by the Client.</p>
        </div>
        
        <div class="subsection">
            <div class="subsection-title">1.2 Management Software</div>
            <p>This contract also includes the creation, development, customization, and deployment of a management software intended for the internal organization of the Client's business.</p>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title"><span class="section-number">2.</span> Software Description</div>
        <p>The Software is a dedicated business management software, including in particular:</p>
        <ul>
            <li>Management of activities and operations;</li>
            <li>Internal organization;</li>
            <li>Monitoring and improvement tools;</li>
            <li>Scalability according to the Client's future needs.</li>
        </ul>
        <p>Features may be specified and adjusted by mutual agreement between the parties.</p>
    </div>
    
    <div class="section">
        <div class="section-title"><span class="section-number">3.</span> Price and Payment Terms – Software Creation</div>
        
        <div class="subsection">
            <div class="subsection-title">3.1 Creation Price</div>
            <p>The total price for the creation of the Software and digital services is set at:</p>
            <p class="price-highlight"><span class="amount">₡{{ number_format($precio, 2, ',', '.') }} ({{ number_format($precio / 520, 2, '.', ',') }} USD)</span></p>
        </div>
        
        <div class="subsection">
            <div class="subsection-title">3.2 Payment Terms</div>
            <p>Payment terms will be agreed upon between the parties. Any adjustment or postponement of payment must be agreed upon in writing by both parties.</p>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title"><span class="section-number">4.</span> Support, Maintenance, and Improvements</div>
        <p>Starting from the contract date, the Client may subscribe to the following package:</p>
        <p class="price-highlight">Support, Maintenance, Improvements & Website Package</p>
        <p>This package includes:</p>
        <ul>
            <li>Technical support;</li>
            <li>Software maintenance;</li>
            <li>Continuous improvements;</li>
            <li>Website-related support.</li>
        </ul>
        <p>Specific terms and pricing for this package will be agreed upon separately.</p>
    </div>
    
    <div class="section">
        <div class="section-title"><span class="section-number">5.</span> Social Media Marketing</div>
        <p>The Client may benefit from Social Media Marketing services (Facebook & Instagram).</p>
        <p>Specific rates and duration will be agreed upon separately between the parties.</p>
    </div>
    
    <div class="section">
        <div class="section-title"><span class="section-number">6.</span> Monthly Amount Summary</div>
        <p>Any recurring monthly amounts will be specified in separate agreements and will be communicated to the Client in writing.</p>
    </div>
    
    <div class="section">
        <div class="section-title"><span class="section-number">7.</span> Intellectual Property</div>
        <p>The developed Software remains the exclusive intellectual property of the Service Provider until full payment of all amounts due.</p>
        <p>After full payment, the Client is granted a non-exclusive right of use, strictly limited to its professional activity.</p>
        <p>Any resale, transfer, duplication, modification, or provision of the Software without written authorization from the Service Provider is strictly prohibited.</p>
    </div>
    
    <div class="section">
        <div class="section-title"><span class="section-number">8.</span> Late Payment and Non-Payment</div>
        <p>In case of late payment, the Service Provider reserves the right to suspend access to the Software and related services, without compensation.</p>
        <p>In case of non-payment, the contract may be terminated automatically, without prejudice to the amounts still due.</p>
        
        <div class="subsection">
            <div class="subsection-title">8.1 Limitation of Liability</div>
            <p>The Service Provider is subject to an obligation of means, not of results.</p>
            <p>Its liability cannot be engaged in case of:</p>
            <ul>
                <li>Malfunctions related to hosting or third-party services (Google, social networks, external platforms, etc.);</li>
                <li>Data loss due to misuse or external failure;</li>
                <li>Indirect damages (loss of revenue, loss of customers, loss of profit).</li>
            </ul>
            <p>In any case, the financial liability of the Service Provider is strictly limited to the total amount actually paid by the Client under this contract.</p>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title"><span class="section-number">9.</span> Evolutions and Additional Requests</div>
        <p>Any request for modification, additional option, or specific development will be subject to a written agreement and additional invoicing, depending on working time and technical complexity.</p>
    </div>
    
    <div class="section">
        <div class="section-title"><span class="section-number">10.</span> Duration – Termination – Applicable Law</div>
        <p>This contract takes effect on the date of signature.</p>
        <p>Any termination must be notified in writing.</p>
        <p>The contract is governed by the applicable law agreed upon between the parties.</p>
        <p>This contract is provided in a bilingual version (ES / EN), each version having the same legal value.</p>
        <p><strong>Done in two copies.</strong></p>
    </div>
    
    <div class="signature-section">
        <div class="signature-box">
            <div class="date-line">At ________________________, on {{ now()->format('d / m / Y') }}</div>
            <div class="signature-line">Signature of the Service Provider</div>
        </div>
        <div class="signature-box">
            <div class="date-line"></div>
            <div class="signature-line">Signature of the Client</div>
        </div>
    </div>
    
    <div class="footer">
        <p>Web Solutions CR – WHENSOL | Jacó, Puntarenas, Costa Rica</p>
        <p>Contract generated on {{ now()->format('F d, Y') }}</p>
    </div>
</body>
</html>
