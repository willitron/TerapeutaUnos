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
        Schema::create('reschedulings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_original_appointment')->constrained('appointments')->onDelete('cascade');
            $table->foreignId('id_new_appointment')->constrained('appointments')->onDelete('cascade');
            $table->text('reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reschedulings');
    }
};
