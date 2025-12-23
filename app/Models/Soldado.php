<?php

namespace App\Models;
use App\Models\Escala;

use Illuminate\Database\Eloquent\Model;

class Soldado extends Model
{
    protected $fillable = [
        'numero_policia',
        'numero_curso',
        'nome_guerra',
        'nome_completo',
    ];

    public function escalas()
    {
        return $this->belongsToMany(Escala::class, 'escala_soldado');
    }
}
