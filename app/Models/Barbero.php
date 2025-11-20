<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barbero extends Model
{
    use HasFactory;

    protected $table = 'barberos'; 

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'telefono',
        'password',
        'estado', // 'activo' o 'permiso'
        'fecha_inicio_permiso',
        'fecha_fin_permiso',
        'imagen_perfil', // opcional, si usas avatar
    ];

    // Relación con reservas
    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'barbero_id'); 
    }
}
