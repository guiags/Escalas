<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Atividade extends Model
{
    protected $fillable = ['nome', 'sexo_restrito', 'quantidade_padrao', 'carga_horaria', 
    'observacao'];
    
    public function escalas()
    {
        return $this->hasMany(Escala::class);
    }
}
