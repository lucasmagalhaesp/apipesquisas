<?php

namespace App\Http\Controllers;

use App\Models\Pergunta;
use Illuminate\Http\Request;

class PerguntasController extends Controller
{
    public function show(Pergunta $pergunta){
        try {
            $dadosPergunta = $pergunta->with("respostas")->find($pergunta->id);
        } catch (\Exception $e) {
            return response()->json(["sucesso" => false, "msg" => "Erro ao pegar os dados da pergunta ({$e->getMessage()})"]);
        }

        return response()->json(["sucesso" => true, "dados" => $dadosPergunta], 200);
    }
}