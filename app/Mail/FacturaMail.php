<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FacturaMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $facturaData;

    /**
     * Create a new message instance.
     */
    public function __construct(array $facturaData)
    {
        $this->facturaData = $facturaData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $numeroFactura = $this->facturaData['numero_factura'] ?? 'N/A';
        return new Envelope(
            subject: "Factura {$numeroFactura} - Web Solutions",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.factura',
            with: [
                'numeroFactura' => $this->facturaData['numero_factura'] ?? '',
                'fechaEmision' => $this->facturaData['fecha_emision'] ?? '',
                'concepto' => $this->facturaData['concepto'] ?? '',
                'subtotal' => $this->facturaData['subtotal'] ?? '0',
                'total' => $this->facturaData['total'] ?? '0',
                'metodoPago' => $this->facturaData['metodo_pago'] ?? '',
                'estado' => $this->facturaData['estado'] ?? '',
                'fechaVencimiento' => $this->facturaData['fecha_vencimiento'] ?? '',
                'notas' => $this->facturaData['notas'] ?? '',
                'clienteNombre' => $this->facturaData['cliente_nombre'] ?? '',
                'clienteCorreo' => $this->facturaData['cliente_correo'] ?? '',
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
