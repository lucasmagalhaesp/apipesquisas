<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pergunta;

class Resposta extends Model
{
    use HasFactory;

    protected $fillable = [
        "pergunta_id",
        "descricao",
        "num_ordem"
    ];

    public function pergunta()
    {
        return $this->belongsTo(Pergunta::class);
    }
}
