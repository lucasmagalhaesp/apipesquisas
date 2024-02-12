<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PerguntaResposta;

class PesquisaRealizada extends Model
{
    use HasFactory;

    protected $table="pesquisas_realizadas";
    protected $fillable = [
        "pesquisa_id",
		"usuario_id",
		"entrevistado_id",
    ];

    public function perguntasRespostas()
    {
        return $this->hasMany(PerguntaResposta::class);
    }
}
