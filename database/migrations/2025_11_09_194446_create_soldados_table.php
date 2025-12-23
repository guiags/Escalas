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
        Schema::create('soldados', function (Blueprint $table) {
        $table->id();
        $table->string('nome_completo');
        $table->string('nome_guerra');
        $table->string('matricula')->unique(); // Novo
        $table->string('numero_bone')->nullable(); // Novo
        $table->enum('sexo', ['M', 'F']); // Novo
        $table->string('turma'); // Novo (ex: "CFsd 2024", "Pelotão Alpha")
        $table->string('graduacao'); // Sd, Cb, Sgt...
        $table->boolean('disponivel')->default(true);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soldados');
    }
};
