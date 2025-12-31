<?php

namespace App\Mail;

use App\Models\Tarea;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TareaNotificacionMail extends Mailable
{
    use Queueable, SerializesModels;

    public Tarea $tarea;
    public string $fechaTarea;

    /**
     * Create a new message instance.
     */
    public function __construct(Tarea $tarea)
    {
        $this->tarea = $tarea;
        $this->fechaTarea = \Carbon\Carbon::parse($tarea->fecha_hora)->format('d/m/Y H:i');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📋 Recordatorio de Tarea - ' . $this->tarea->texto,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.tarea-notificacion',
            with: [
                'tarea' => $this->tarea,
                'fechaTarea' => $this->fechaTarea,
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
