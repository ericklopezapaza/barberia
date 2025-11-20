<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Reserva;

class RecordatorioReserva extends Mailable
{
    use Queueable, SerializesModels;

    public $reserva;

    /**
     * Create a new message instance.
     */
    public function __construct(Reserva $reserva)
    {
        $this->reserva = $reserva;
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recordatorio de tu cita en Estilo Urbano Barbería',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.recordatorio_reserva', 
            with: [
                'reserva' => $this->reserva,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
