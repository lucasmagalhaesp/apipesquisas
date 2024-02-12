<?php

namespace App\Http\Controllers;

use App\Models\PesquisaRealizada;
use Illuminate\Http\Request;
use DB;

class PesquisasRealizadasController extends Controller
{
    public function index()
    {
        $pesquisasRealizadas = PesquisaRealizada::with("perguntasRespostas")->get();
        return response()->json(["sucesso" => true, "dados" => $pesquisasRealizadas], 200);
    }

    public function show(PesquisaRealizada $pesquisasRealizada)
    {
        try {
            $dadosPesquisa = $pesquisasRealizada->with("perguntasRespostas")->find($pesquisasRealizada->id);
        } catch (\Exception $e) {
            return response()->json(["sucesso" => false, "msg" => "Erro ao pegar os dados da pesquisa ({$e->getMessage()})"]);
        }

        return response()->json(["sucesso" => true, "dados" => $dadosPesquisa], 200);
    }

    public function store(Request $request)
    {
        $dados = $request->dados;
        if (is_null($dados)) return response()->json(["sucesso" => false, "msg" => "Dados da pesquisa não recebidos"], 400);

        $perguntasRespostas = $dados["perguntas_respostas"];
        unset($dados["perguntas_respostas"]);

        try {
            DB::beginTransaction();
            $pesquisa = PesquisaRealizada::create($dados);
            foreach($perguntasRespostas as $perguntaResp){
                $perguntaResp["pesquisa_realizada_id"] = $pesquisa->id;
                $pesquisa->perguntasRespostas()->create($perguntaResp);
            }

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(["sucesso" => false, "msg" => "Erro ao cadastrar dados coletados da pesquisa ({$e->getMessage()})"], 400);
        }

        return response()->json(["sucesso" => true], 200);
    }
}
