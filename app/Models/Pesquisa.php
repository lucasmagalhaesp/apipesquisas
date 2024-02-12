<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pergunta;
use App\Models\Categoria;

class Pesquisa extends Model
{
    use HasFactory;

    protected $fillable = [
        "categoria_id",
        "titulo",
        "descricao"
    ];

    public function perguntas()
    {
        return $this->hasMany(Pergunta::class)->with("respostas");
    }

    public function categoria(){
        return $this->belongsTo(Categoria::class);
    }
}
