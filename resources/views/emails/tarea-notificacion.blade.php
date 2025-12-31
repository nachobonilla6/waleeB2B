<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recordatorio de Tarea</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 24px;">📋 Recordatorio de Tarea</h1>
    </div>
    
    <div style="background: #ffffff; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 10px 10px;">
        <h2 style="color: #1f2937; margin-top: 0;">Hola,</h2>
        
        <p style="color: #4b5563; font-size: 16px;">
            Tienes una tarea programada que se acerca. Aquí están los detalles:
        </p>
        
        <div style="background: #f9fafb; border-left: 4px solid #8b5cf6; padding: 20px; margin: 20px 0; border-radius: 5px;">
            <h3 style="color: #1f2937; margin-top: 0; font-size: 18px;">{{ $tarea->texto }}</h3>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; color: #6b7280; font-weight: bold; width: 120px;">Fecha y Hora:</td>
                    <td style="padding: 8px 0; color: #1f2937;">
                        {{ $fechaTarea }}
                    </td>
                </tr>
                @if($tarea->lista)
                <tr>
                    <td style="padding: 8px 0; color: #6b7280; font-weight: bold;">Lista:</td>
                    <td style="padding: 8px 0; color: #1f2937;">{{ $tarea->lista->nombre }}</td>
                </tr>
                @endif
                @if($tarea->tipo)
                <tr>
                    <td style="padding: 8px 0; color: #6b7280; font-weight: bold;">Tipo:</td>
                    <td style="padding: 8px 0; color: #1f2937;">{{ $tarea->tipo }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding: 8px 0; color: #6b7280; font-weight: bold;">Estado:</td>
                    <td style="padding: 8px 0; color: #1f2937;">
                        <span style="background: {{ $tarea->estado === 'completado' ? '#10b981' : '#f59e0b' }}; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: bold;">
                            {{ $tarea->estado === 'completado' ? 'Completado' : 'Pendiente' }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>
        
        <p style="color: #6b7280; font-size: 14px; margin-top: 30px;">
            No olvides completar esta tarea a tiempo.
        </p>
        
        <p style="color: #6b7280; font-size: 14px; margin-top: 20px;">
            Saludos,<br>
            <strong>Equipo Walee</strong>
        </p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #9ca3af; font-size: 12px;">
        <p>Este es un email automático, por favor no respondas a este mensaje.</p>
    </div>
</body>
</html>

