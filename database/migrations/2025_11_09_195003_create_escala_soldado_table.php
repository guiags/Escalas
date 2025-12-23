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
        Schema::create('escala_soldado', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escala_id')->constrained()->onDelete('cascade');
            $table->foreignId('soldado_id')->constrained()->onDelete('cascade');
            //$table->unique(['escala_id', 'soldado_id']); // Garante que o mesmo soldado não seja escalado duas vezes no mesmo serviço
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('escala_soldado');
    }
};
