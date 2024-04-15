<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PerguntaResposta;
use App\Models\Pesquisa;

class PesquisaRealizada extends Model
{
    use HasFactory;

    protected $table="pesquisas_realizadas";
    protected $fillable = [
        "pesquisa_id",
		"usuario_id",
		"entrevistado_id",
    ];

    protected $dates  = ["created_at", "updated_at"];

    public function getCreatedAtAttribute($date)
    {
        return date("d/m/Y", strtotime($date));
    }

    public function perguntasRespostas()
    {
        return $this->hasMany(PerguntaResposta::class);
    }

    public function pesquisa()
    {
        return $this->belongsTo(Pesquisa::class);
    }
}
