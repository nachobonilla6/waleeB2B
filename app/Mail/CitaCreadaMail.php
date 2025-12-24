<?php

namespace App\Mail;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CitaCreadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $cita;
    public $googleEventUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Cita $cita, string $googleEventUrl = null)
    {
        $this->cita = $cita;
        $this->googleEventUrl = $googleEventUrl;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Cita Agendada: ' . $this->cita->titulo)
                    ->view('emails.cita-creada')
                    ->with([
                        'cita' => $this->cita,
                        'googleEventUrl' => $this->googleEventUrl,
                    ]);
    }
}

