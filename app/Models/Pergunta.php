<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pesquisa;
use App\Models\Resposta;

class Pergunta extends Model
{
    use HasFactory;

    protected $fillable = [
        "pesquisa_id",
        "descricao",
        "num_ordem"
    ];

    public function pesquisa()
    {
        return $this->belongsTo(Pesquisa::class);
    }

    public function respostas()
    {
        return $this->hasMany(Resposta::class);
    }

}
