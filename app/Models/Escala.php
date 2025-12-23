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
    ];

    public function soldados()
    {
        return $this->belongsToMany(Soldado::class, 'escala_soldado');
    }
}
