<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura - {{ $factura->numero_factura }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: #ffffff; padding: 30px;">
        @php
            $lang = $language ?? ($cliente->idioma ?? 'es');
            $name = $cliente && $cliente->nombre_empresa ? ' ' . $cliente->nombre_empresa : '';
            $issueDate = $factura->fecha_emision ? $factura->fecha_emision->format('Y-m-d') : null;
            $dueDate = $factura->fecha_vencimiento ? $factura->fecha_vencimiento->format('Y-m-d') : null;
            $total = number_format((float) $factura->total, 2, '.', ',');
            $vat = number_format((float) ($factura->subtotal * 0.13), 2, '.', ',');
            $clientCountry = $cliente->pais ?? 'Costa Rica';
            $clientCity = $cliente->ciudad ?? null;
            $clientPostal = $cliente->codigo_postal ?? null;
        @endphp

        @if($lang === 'es')
            <p style="color: #1f2937; font-size: 16px; margin: 0 0 15px 0; white-space: pre-line;">Hola{{ $name }},

Esperamos que se encuentre bien.

Le enviamos la factura adjunta a este correo.

Detalles fiscales:
- Número de factura: {{ $factura->numero_factura }}
@if($issueDate)
- Fecha de emisión: {{ $issueDate }}
@endif
@if($dueDate)
- Fecha de vencimiento: {{ $dueDate }}
@endif
- Importe total: ${{ $total }} USD
- IVA (13%): ${{ $vat }} USD
Moneda: USD (Dólar estadounidense).

Datos fiscales del cliente:
- Cliente: {{ $cliente->nombre_empresa ?? $factura->correo }}
@if($clientCity || $clientCountry)
- Dirección fiscal: {{ $clientCity ? $clientCity . ', ' : '' }}{{ $clientCountry }}
@endif
@if($clientPostal)
- Código postal: {{ $clientPostal }}
@endif

Saludos cordiales,
Web Solutions</p>
        @elseif($lang === 'fr')
            <p style="color: #1f2937; font-size: 16px; margin: 0 0 15px 0; white-space: pre-line;">Bonjour{{ $name }},

Nous espérons que vous allez bien.

Nous vous envoyons la facture en pièce jointe à cet e-mail.

Détails fiscaux :
- Numéro de facture : {{ $factura->numero_factura }}
@if($issueDate)
- Date d'émission : {{ $issueDate }}
@endif
@if($dueDate)
- Date d'échéance : {{ $dueDate }}
@endif
- Montant total : ${{ $total }} USD
- TVA (13 %) : ${{ $vat }} USD
Devise : USD (dollar américain).

Informations fiscales du client :
- Client : {{ $cliente->nombre_empresa ?? $factura->correo }}
@if($clientCity || $clientCountry)
- Adresse fiscale : {{ $clientCity ? $clientCity . ', ' : '' }}{{ $clientCountry }}
@endif
@if($clientPostal)
- Code postal : {{ $clientPostal }}
@endif

Cordialement,
Web Solutions</p>
        @else
            <p style="color: #1f2937; font-size: 16px; margin: 0 0 15px 0; white-space: pre-line;">Hello{{ $name }},

We hope you are doing well.

Please find your invoice attached to this email.

Fiscal details:
- Invoice number: {{ $factura->numero_factura }}
@if($issueDate)
- Issue date: {{ $issueDate }}
@endif
@if($dueDate)
- Due date: {{ $dueDate }}
@endif
- Total amount: ${{ $total }} USD
- VAT (13%): ${{ $vat }} USD
Currency: USD (US Dollar).

Customer fiscal information:
- Customer: {{ $cliente->nombre_empresa ?? $factura->correo }}
@if($clientCity || $clientCountry)
- Fiscal address: {{ $clientCity ? $clientCity . ', ' : '' }}{{ $clientCountry }}
@endif
@if($clientPostal)
- Postal code: {{ $clientPostal }}
@endif

Best regards,
Web Solutions</p>
        @endif
        
        <div style="margin-top: 20px;">
            <a href="https://wa.me/50688061829" target="_blank" style="display: inline-block; background-color: #25D366; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-size: 14px; font-weight: bold; margin: 5px 0 10px 0;">
                📱 WhatsApp
            </a>
            <p style="color: #6b7280; font-size: 14px; margin: 10px 0 5px 0;">
                <a href="https://websolutions.work" target="_blank" style="color: #2563eb; text-decoration: none;">websolutions.work</a>
            </p>
        </div>
    </div>
</body>
</html>

