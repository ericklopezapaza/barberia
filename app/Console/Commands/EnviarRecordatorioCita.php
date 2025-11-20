<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reserva;
use App\Mail\ReservaConfirmada;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class EnviarRecordatorioCita extends Command
{
    protected $signature = 'citas:recordatorio';
    protected $description = 'Enviar recordatorios de cita 12 horas antes';

    public function handle()
    {
        $ahora = Carbon::now();
        $limiteInferior = $ahora->copy()->addHours(12);
        $limiteSuperior = $ahora->copy()->addHours(13);

        $reservas = Reserva::whereBetween('fecha_hora', [$limiteInferior, $limiteSuperior])->get();

        foreach ($reservas as $reserva) {
            Mail::to($reserva->email)->send(new ReservaConfirmada($reserva));
        }

        $this->info('Recordatorios enviados.');
    }
}

