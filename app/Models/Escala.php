<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Soldado;

class Escala extends Model
{

    protected $fillable = [
        'data',
        'turno',
        'servico',
        'vagas_necessarias',
        'atividade_id', 
        'observacao'
    ];

    public function atividade()
    {
        return $this->belongsTo(Atividade::class);
    }

    public function soldados()
    {
        return $this->belongsToMany(Soldado::class, 'escala_soldado');
    }
}
