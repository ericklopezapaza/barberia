<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Los atributos que se pueden llenar masivamente.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'apellido_paterno',
        'apellido_materno',
        'role',
        'imagen_perfil',
    ];

    /**
     * Atributos ocultos para serialización.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Atributos que se deben convertir automáticamente.
     */
    protected $casts = [
        // 'email_verified_at' => 'datetime', // ← Lo quitamos porque no necesitamos verificación
    ];

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'usuario_id');
    }
}


