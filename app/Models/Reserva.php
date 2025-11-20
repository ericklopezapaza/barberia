<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Barbero;

class Reserva extends Model
{
    protected $table = 'reservas';

    protected $fillable = [
        'usuario_id',
        'barbero_id',
        'nombre_completo',
        'email',
        'servicio_id',
        'fecha_reserva',
        'hora_reserva',
        'hora_fin',
        'duracion',
        'precio',
        'estado',
        'codigo_reserva',
        'celular'
    ];

    // Relación con el barbero
    public function barbero()
    {
        return $this->belongsTo(Barbero::class, 'barbero_id'); 
    }


    // Relación con el usuario que hace la reserva
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
    
    // Relación con servicio
    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }
}
