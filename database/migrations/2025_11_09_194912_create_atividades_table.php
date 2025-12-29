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
        Schema::create('atividades', function (Blueprint $table) {
        $table->id();
        $table->string('nome'); // Ex: "Limpeza Vestiário Feminino", "Plantão Claviculário"
        $table->enum('sexo_restrito', ['M', 'F'])->nullable(); // Null = Ambos permitidos
        $table->integer('quantidade_padrao')->default(1); // Quantos soldados precisa?
        $table->integer('carga_horaria')->default(3); // Quantas horas conta para o banco de horas?
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atividades');
    }
};
