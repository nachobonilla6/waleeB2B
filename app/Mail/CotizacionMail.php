<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CotizacionMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $cotizacionData;

    /**
     * Create a new message instance.
     */
    public function __construct(array $cotizacionData)
    {
        $this->cotizacionData = $cotizacionData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $numeroCotizacion = $this->cotizacionData['numero_cotizacion'] ?? 'N/A';
        return new Envelope(
            from: 'nachobonilla6@gmail.com',
            subject: "Cotización {$numeroCotizacion} - WALEÉ",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.cotizacion',
            with: [
                'numeroCotizacion' => $this->cotizacionData['numero_cotizacion'] ?? '',
                'fecha' => $this->cotizacionData['fecha'] ?? '',
                'idioma' => $this->cotizacionData['idioma'] ?? 'es',
                'tipoServicio' => $this->cotizacionData['tipo_servicio'] ?? '',
                'plan' => $this->cotizacionData['plan'] ?? '',
                'monto' => $this->cotizacionData['monto'] ?? '0',
                'vigencia' => $this->cotizacionData['vigencia'] ?? '15',
                'descripcion' => $this->cotizacionData['descripcion'] ?? '',
                'clienteNombre' => $this->cotizacionData['cliente_nombre'] ?? '',
                'clienteCorreo' => $this->cotizacionData['cliente_correo'] ?? '',
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
