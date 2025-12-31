<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura - {{ $factura->numero_factura }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 24px;">Factura - Web Solutions</h1>
    </div>

    <div style="background: #ffffff; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 10px 10px;">
        <h2 style="color: #1f2937; margin-top: 0;">Hola{{ $cliente ? ' ' . $cliente->nombre_empresa : '' }},</h2>

        <p style="color: #4b5563; font-size: 16px;">
            Adjuntamos la factura <strong>{{ $factura->numero_factura }}</strong> por un monto de <strong>₡{{ number_format($factura->total, 2) }}</strong>.
        </p>

        <div style="background: #f9fafb; border-left: 4px solid #8b5cf6; padding: 20px; margin: 20px 0; border-radius: 5px;">
            <h3 style="color: #1f2937; margin-top: 0; font-size: 18px;">Detalles de la Factura</h3>

            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; color: #6b7280; font-weight: bold; width: 120px;">Número:</td>
                    <td style="padding: 8px 0; color: #1f2937;">{{ $factura->numero_factura }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #6b7280; font-weight: bold;">Fecha:</td>
                    <td style="padding: 8px 0; color: #1f2937;">{{ $factura->fecha_emision->format('d/m/Y') }}</td>
                </tr>
                @if($factura->fecha_vencimiento)
                <tr>
                    <td style="padding: 8px 0; color: #6b7280; font-weight: bold;">Vencimiento:</td>
                    <td style="padding: 8px 0; color: #1f2937;">{{ $factura->fecha_vencimiento->format('d/m/Y') }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding: 8px 0; color: #6b7280; font-weight: bold;">Total:</td>
                    <td style="padding: 8px 0; color: #1f2937; font-size: 18px; font-weight: bold;">₡{{ number_format($factura->total, 2) }}</td>
                </tr>
            </table>
        </div>

        <p style="color: #6b7280; font-size: 14px; margin-top: 30px;">
            Puede encontrar el PDF de la factura adjunto a este correo.
        </p>

        @if($factura->notas)
        <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0; border-radius: 5px;">
            <p style="color: #92400e; font-size: 14px; margin: 0;"><strong>Nota:</strong> {{ $factura->notas }}</p>
        </div>
        @endif

        <p style="color: #6b7280; font-size: 14px; margin-top: 20px;">
            Saludos,<br>
            <strong>Equipo Web Solutions</strong>
        </p>
    </div>

    <div style="text-align: center; margin-top: 20px; color: #9ca3af; font-size: 12px;">
        <p>Este es un email automático, por favor no respondas a este mensaje.</p>
    </div>
</body>
</html>

