<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('therapist_id')->constrained('users')->onDelete('cascade');
            $table->enum('day', ['lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado']);
            $table->enum('shift', ['manana', 'tarde', 'noche']);
            $table->time('time');
            $table->enum('status', ['pendiente', 'confirmada', 'cancelada', 'realizada'])->default('pendiente');
            $table->date('appointment_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
