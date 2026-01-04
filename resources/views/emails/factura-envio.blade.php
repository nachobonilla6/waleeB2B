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
        <p style="color: #1f2937; font-size: 16px; margin: 0;">
            Saludos cordiales,<br>
            <strong>Equipo Web Solutions</strong>
        </p>
    </div>
</body>
</html>

