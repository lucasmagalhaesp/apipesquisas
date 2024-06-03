<?php

namespace App\Http\Controllers;

use App\Models\Pergunta;
use App\Models\Resposta;
use App\Models\Pesquisa;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;

class PesquisasController extends Controller
{
    public function index()
    {
        $pesquisas = Pesquisa::with("perguntas")->get();
        return response()->json(["sucesso" => true, "dados" => $pesquisas], 200);
    }

    public function show($id)
    {
        if (is_null($id)) return response()->json(["sucesso" => false, "msg" => "Código da pesquisa não informado"], 400);

        try {
            $dadosPesquisa = Pesquisa::with("perguntas")->find($id);
        } catch (\Exception $e) {
            return response()->json(["sucesso" => false, "msg" => "Erro ao buscar dados da pesquisa ({$e->getMessage()})"], 400);
        }

        return response()->json(["sucesso" => true, "dados" => $dadosPesquisa], 200);
    }

    public function store(Request $request)
    {
        $dados = $request->dados;
        if (is_null($dados)) return response()->json(["sucesso" => false, "msg" => "Dados não enviados"], 400);

        $validacao = Validator::make($dados, [
            "titulo"    => "required|min:5",
            "descricao" => "required|min:10"
        ], [
            "titulo.required"       => "Título da pesquisa não informado",
            "titulo.min"            => "O título da pesquisa precisa ter pelo menos 5 caracteres",
            "descricao.required"    => "Descrição da pesquisa não informada",
            "descricao.min"         => "A descrição da pesquisa precisa ter pelo menos 10 caracteres"
        ]);

        $erros = $validacao->errors();
        if (count($erros->all()) > 0) return response()->json($erros, 402);

        if (count($dados["perguntas"]) == 0)
            return response()->json("Nenhuma pergunta cadastrada", 402);

        $perguntas = $dados["perguntas"];
        unset($dados["perguntas"]);

        try{
            DB::beginTransaction();
            $idPesquisa = $dados["id"];
            unset($dados["id"]);
            $pesquisa = Pesquisa::updateOrCreate(["id" => $idPesquisa], $dados);
            
            //remover as perguntas que foram excluídas na edição
            Pergunta::where("pesquisa_id", $idPesquisa)->whereNotIn("id", array_map(function ($perg){
                return $perg["id"];
            }, $perguntas))->delete();
            
            foreach($perguntas as $pergunta){
                $respostas = $pergunta["respostas"];
                unset($pergunta["respostas"]);
                $idPergunta = $pergunta["id"];
                unset($pergunta["id"]);
                $perg = $pesquisa->perguntas()->updateOrCreate(["id" => $idPergunta], $pergunta);
                
                //remover as respostas que foram excluídas na edição
                Resposta::where("pergunta_id", $perg->id)->whereNotIn("id", array_map(function ($resp){
                    return $resp["id"];
                }, $respostas))->delete();

                foreach($respostas as $resposta){
                    $idResposta = $resposta["id"];
                    unset($resposta["id"]);
                    $resp = $perg->respostas()->updateOrCreate(["id" => $idResposta], $resposta);
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
            $pesquisa->ativa = "N";
            $pesquisa->save();
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
