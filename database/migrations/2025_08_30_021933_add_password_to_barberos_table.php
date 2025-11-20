<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barberos', function (Blueprint $table) {
            $table->string('password')->after('email'); // agrega la columna después de email
        });
    }

    public function down(): void
    {
        Schema::table('barberos', function (Blueprint $table) {
            $table->dropColumn('password'); // elimina la columna si haces rollback
        });
    }
};

