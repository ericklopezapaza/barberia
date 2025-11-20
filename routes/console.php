<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Ejecutar el comando cada día a las 08:00 am
Schedule::command('enviar:recordatorio-cita')->dailyAt('08:00');
