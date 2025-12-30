<?php

namespace App\Models;
use App\Models\Escala;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Soldado extends Model
{
   protected $fillable = [
        'nome_completo', 'nome_guerra', 'matricula', 
        'numero_bone', 'sexo', 'turma', 'graduacao', 'disponivel', 'horas_iniciais'
    ];

    // Relacionamento com Escalas
    public function escalas(): BelongsToMany
    {
        return $this->belongsToMany(Escala::class, 'escala_soldado');
    }

    public function horasPorAtividade($atividadeId)
    {
        $horasNoSistema = $this->escalas()
            ->where('atividade_id', $atividadeId)
            ->join('atividades', 'escalas.atividade_id', '=', 'atividades.id')
            ->sum('atividades.carga_horaria');

        return $horasNoSistema + $this->horas_iniciais;
    }

    // Retorna o total geral (Histórico do sistema + Carga inicial lançada)
    public function getTotalGeralAttribute()
    {
        $horasSistema = $this->escalas()
            ->with('atividade')
            ->get()
            ->sum(fn($escala) => $escala->atividade->carga_horaria ?? 0);

        return $horasSistema + $this->horas_iniciais;
    }
}
