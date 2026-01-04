<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura - {{ $factura->numero_factura }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: #ffffff; padding: 30px;">
        <p style="color: #1f2937; font-size: 16px; margin: 0 0 15px 0;">
            Hola{{ $cliente && $cliente->nombre_empresa ? ' ' . $cliente->nombre_empresa : '' }},
        </p>
        <p style="color: #1f2937; font-size: 16px; margin: 0 0 15px 0;">
            Esperamos que se encuentre bien.
        </p>
        <p style="color: #1f2937; font-size: 16px; margin: 0 0 15px 0;">
            Le enviamos la factura adjunta a este correo.
        </p>
        <p style="color: #1f2937; font-size: 16px; margin: 20px 0 0 0;">
            Saludos cordiales,<br>
            <strong>Web Solutions</strong>
        </p>
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
            <p style="color: #6b7280; font-size: 14px; margin: 5px 0;">
                <strong>Email:</strong> websolutionscrnow@gmail.com
            </p>
            <p style="color: #6b7280; font-size: 14px; margin: 5px 0;">
                <strong>WhatsApp:</strong> +506 8806 1829
            </p>
            <p style="color: #6b7280; font-size: 14px; margin: 5px 0;">
                <strong>Web:</strong> websolutions.work
            </p>
        </div>
    </div>
</body>
</html>

