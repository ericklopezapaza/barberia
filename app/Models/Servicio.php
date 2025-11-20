<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table = 'servicios';

    protected $fillable = [
        'nombre',
        'duracion',   
        'descripcion',
        'precio',
    ];
    
    // Relación con reservas
    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'servicio_id');
    }
    
    public $timestamps = true; 
}
