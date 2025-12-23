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
        Schema::create('escalas', function (Blueprint $table) {
            $table->id();
            $table->date('data');
            $table->string('turno'); // Ex: Manhã, Tarde, Noite
            $table->string('servico'); // Ex: Patrulhamento, Administrativo
            $table->integer('vagas_necessarias');
            $table->foreignId('atividade_id')->constrained('atividades'); // Liga à regra
            $table->text('observacao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('escalas');
    }
};
