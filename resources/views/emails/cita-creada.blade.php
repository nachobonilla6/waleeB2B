<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cita Agendada</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 24px;">Cita Agendada</h1>
    </div>
    
    <div style="background: #ffffff; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 10px 10px;">
        <h2 style="color: #1f2937; margin-top: 0;">Hola {{ $cita->cliente->nombre_empresa ?? 'Cliente' }},</h2>
        
        <p style="color: #4b5563; font-size: 16px;">
            Se ha agendado una cita para ti. A continuación los detalles:
        </p>
        
        <div style="background: #f9fafb; border-left: 4px solid #10b981; padding: 20px; margin: 20px 0; border-radius: 5px;">
            <h3 style="color: #1f2937; margin-top: 0; font-size: 18px;">{{ $cita->titulo }}</h3>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; color: #6b7280; font-weight: bold; width: 120px;">Fecha y Hora:</td>
                    <td style="padding: 8px 0; color: #1f2937;">
                        {{ $cita->fecha_inicio->format('d/m/Y') }} a las {{ $cita->fecha_inicio->format('H:i') }}
                        @if($cita->fecha_fin)
                            - {{ $cita->fecha_fin->format('H:i') }}
                        @endif
                    </td>
                </tr>
                @if($cita->ubicacion)
                <tr>
                    <td style="padding: 8px 0; color: #6b7280; font-weight: bold;">Ubicación:</td>
                    <td style="padding: 8px 0; color: #1f2937;">{{ $cita->ubicacion }}</td>
                </tr>
                @endif
                @if($cita->descripcion)
                <tr>
                    <td style="padding: 8px 0; color: #6b7280; font-weight: bold; vertical-align: top;">Descripción:</td>
                    <td style="padding: 8px 0; color: #1f2937;">{{ $cita->descripcion }}</td>
                </tr>
                @endif
            </table>
        </div>
        
        @if($googleEventUrl)
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $googleEventUrl }}" 
               style="display: inline-block; background: #10b981; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px;">
                Agregar a Google Calendar
            </a>
        </div>
        @endif
        
        <p style="color: #6b7280; font-size: 14px; margin-top: 30px;">
            Si tienes alguna pregunta o necesitas modificar la cita, por favor contáctanos.
        </p>
        
        <p style="color: #6b7280; font-size: 14px; margin-top: 20px;">
            Saludos,<br>
            <strong>Equipo WebSolutions</strong>
        </p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #9ca3af; font-size: 12px;">
        <p>Este es un email automático, por favor no respondas a este mensaje.</p>
    </div>
</body>
</html>

