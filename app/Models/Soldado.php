<?php

namespace App\Models;
use App\Models\Escala;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Soldado extends Model
{
   protected $fillable = [
        'nome_completo', 'nome_guerra', 'matricula', 
        'numero_bone', 'sexo', 'turma', 'graduacao', 'disponivel'
    ];

    // Relacionamento com Escalas
    public function escalas(): BelongsToMany
    {
        return $this->belongsToMany(Escala::class, 'escala_soldado');
    }

    // Acessor para calcular horas totais automaticamente
    // Uso no blade: $soldado->total_horas
    public function getTotalHorasAttribute()
    {
        // Carrega as escalas e soma a carga horária da atividade vinculada
        return $this->escalas()
            ->with('atividade')
            ->get()
            ->sum(fn($escala) => $escala->atividade->carga_horaria);
    }
}
