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
        "descricao",
        "tipo_entrevistado",
        "ativa"
    ];

    protected $dates  = ["created_at", "updated_at"];

    public function getCreatedAtAttribute($date)
    {
        return date("d/m/Y", strtotime($date));
    }

    public function perguntas()
    {
        return $this->hasMany(Pergunta::class)->select("id", "pesquisa_id", "descricao", "num_ordem")->orderBy("num_ordem")->with("respostas");
    }

    public function categoria(){
        return $this->belongsTo(Categoria::class);
    }
}
