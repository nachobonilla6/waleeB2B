<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura - {{ $factura->numero_factura }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: #ffffff; padding: 30px;">
        <pre style="font-family: Arial, sans-serif; white-space: pre-wrap; color: #1f2937; font-size: 16px; margin: 0 0 20px 0;">Hola{{ $cliente && $cliente->nombre_empresa ? ' ' . $cliente->nombre_empresa : '' }},

Esperamos que se encuentre bien.

Le enviamos la factura adjunta a este correo.

Saludos cordiales,
Web Solutions</pre>
        
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
            <a href="https://wa.me/50688061829" target="_blank" style="display: inline-block; background-color: #25D366; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-size: 14px; font-weight: bold; margin: 5px 0;">
                💬 Contactar por WhatsApp
            </a>
            <p style="color: #6b7280; font-size: 14px; margin: 10px 0 5px 0;">
                <a href="https://websolutions.work" target="_blank" style="color: #2563eb; text-decoration: none;">websolutions.work</a>
            </p>
        </div>
    </div>
</body>
</html>

