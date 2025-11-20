<?php

use Illuminate\Database\Migrations\Migration; // Permite crear migraciones en Laravel
use Illuminate\Database\Schema\Blueprint;   // Nos ayuda a definir la estructura de la tabla
use Illuminate\Support\Facades\Schema;       // Nos permite interactuar con el esquema de la base de datos

return new class extends Migration
{
    /**
     * Aquí definimos qué hacer cuando ejecutamos la migración.
     */
    public function up(): void
    {
        // Creamos la tabla 'reservas'
        Schema::create('reservas', function (Blueprint $table) {

            $table->id(); 
            // Identificador único de cada reserva
            $table->foreignId('usuario_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade'); 
            // Crea un campo 'usuario_id' que se relaciona con 'id' de la tabla 'users'
            // onDelete('cascade') -> si el usuario se borra, sus reservas también se borran
            // onUpdate('cascade') -> si se cambia el id del usuario, se actualiza aquí también
            $table->string('nombre_completo')->nullable();
            $table->string('email')->nullable();
            $table->string('celular', 20)->nullable();
            $table->date('fecha_reserva');
            $table->time('hora_reserva');
            $table->integer('duracion');
            $table->string('tipo_servicio');
            $table->string('estado', 20)->default('pendiente');
            $table->string('codigo_reserva', 20);
            $table->unsignedBigInteger('barbero_id')->nullable();
            $table->timestamps();

        });
    }

    /*
     * Aquí definimos qué hacer si queremos deshacer la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
        // Elimina la tabla 'reservas' si existe
    }
};
