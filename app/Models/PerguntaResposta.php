<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pergunta;
use App\Models\Resposta;

class PerguntaResposta extends Model
{
    use HasFactory;

    protected $table = "perguntas_respostas";
    protected $fillable = [
        "pergunta_id",
        "resposta_id",
        "pesquisa_realizada_id"
    ];

    public function pergunta()
    {
        return $this->hasMany(Pergunta::class);
    }

    public function resposta()
    {
        return $this->hasMany(Resposta::class);
    }
}
