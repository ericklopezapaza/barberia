<?php

namespace App\Jobs;

use App\Mail\ReservaConfirmada;
use App\Mail\RecordatorioReserva;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EnviarRecordatorioReserva implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    public $reserva;

    public function __construct($reserva)
    {
        $this->reserva = $reserva;
    }

    public function handle()
    {
        $reserva = $this->reserva->load('servicio', 'barbero');
        Mail::to($this->reserva->usuario->email)->send(new RecordatorioReserva($this->reserva));
    }
}
