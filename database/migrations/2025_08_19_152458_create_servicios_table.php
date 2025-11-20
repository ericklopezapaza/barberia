<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Ej: Corte de cabello, Afeitado, Tinte, etc.
            $table->text('descripcion')->nullable(); // Opcional, para más detalle
            $table->integer('duracion')->default(50); // duración en minutos (ejemplo: 50 min)
            $table->decimal('precio', 8, 2); // precio del servicio
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
