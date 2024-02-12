<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerguntaResposta extends Model
{
    use HasFactory;

    protected $table = "perguntas_respostas";
    protected $fillable = [
        "pergunta_id",
        "resposta_id",
        "pesquisa_realizada_id"
    ];
}
