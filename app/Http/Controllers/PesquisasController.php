<?php

namespace App\Http\Controllers;

use App\Models\Pergunta;
use App\Models\Pesquisa;
use Illuminate\Http\Request;
use DB;

class PesquisasController extends Controller
{
    public function index()
    {
        $pesquisas = Pesquisa::with("perguntas")->get();
        return response()->json(["sucesso" => true, "dados" => $pesquisas], 200);
    }

    public function store(Request $request)
    {
        $dados = $request->dados;
        if (is_null($dados)) return response()->json(["sucesso" => false, "msg" => "Dados não enviados"], 400);

        $perguntas = $dados["perguntas"];
        unset($dados["perguntas"]);

        try{
            DB::beginTransaction();
            $pesquisa = Pesquisa::create($dados);
            foreach($perguntas as $pergunta){
                $respostas = $pergunta["respostas"];
                unset($pergunta["respostas"]);
                $perg = $pesquisa->perguntas()->create($pergunta);
    
                foreach($respostas as $resposta){
                    $perg->respostas()->create($resposta);
                }
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(["sucesso" => false, "msg" => "Erro ao cadastrar pesquisa ({$e->getMessage()})"], 400);
        }

        return response()->json(["sucesso" => true], 200);
    }

    public function destroy(Pesquisa $pesquisa)
    {
        if (is_null($pesquisa)) return response()->json(["sucesso" => false, "msg" => "Dados da pesquisa não recebidos para a exclusão"]);

        try {
            $pesquisa->delete();
        } catch (\Exception $e) {
            return response()->json(["sucesso" => false, "msg" => "Erro ao excluir pesquisa ({$e->getMessage()})"]);
        }

        return response()->json(["sucesso" => true], 200);
    }

    public function getIDsPerguntas(Pesquisa $pesquisa)
    {
        try {
            $dadosPesquisa = $pesquisa->with("perguntas")->first();
            $idPerguntas = [];
            foreach($dadosPesquisa->perguntas as $pergunta){
                $idPerguntas[] = $pergunta->id;
            }
        } catch (\Exception $e) {
            return response()->json(["sucesso" => false, "msg" => "Erro ao pegar os ids das perguntas dessa pesquisa ({$e->getMessage()})"]);
        }

        return response()->json(["sucesso" => true, "dados" => $idPerguntas], 200);
    }


}
