<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model  //modelo para la tabla admin
{
    protected $table = 'admin'; //$

    protected $fillable = [ //campos de mi tabla de base de datos
        'nombre', 
        'apellido',
        'email',
        'password', 
    ];    
}
