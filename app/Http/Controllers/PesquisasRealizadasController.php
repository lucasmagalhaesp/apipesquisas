<?php

namespace App\Http\Controllers;

use App\Models\PesquisaRealizada;
use App\Models\Pesquisa;
use App\Models\Usuario;
use Illuminate\Http\Request;
use DB;

class PesquisasRealizadasController extends Controller
{
    public function index()
    {
        $perfil = auth()->user()->perfil_usuario_id;
        $pesquisasRealizadas = PesquisaRealizada::where(function ($query) use ($perfil){
            if ($perfil != 1) $query->where("usuario_id", auth()->user()->id);
        })
        ->orderBy("id", "desc")
        ->with("perguntasRespostas", "pesquisa")->get();
        
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

        foreach($perguntasRespostas as $perRes){
            if (is_null($perRes["resposta_id"]) || $perRes["resposta_id"] === 0)
                return response()->json("Todas as perguntas precisam ser respondidas para concluir a pesquisa", 402);
        }

        unset($dados["perguntas_respostas"]);

        $dados["usuario_id"] = auth()->user()->id ?? null;

        $gravarEntrevistado = false;
        $dadosPesquisa = Pesquisa::find($dados["pesquisa_id"]);
        if (!is_null($dadosPesquisa)){
            $gravarEntrevistado = $dadosPesquisa->tipo_entrevistado == "C";
        }

        try {
            DB::beginTransaction();

            if ($gravarEntrevistado) {
                $idEntrevistado = $this->gravarEntrevistado($dados);
                if ($idEntrevistado) $dados["entrevistado_id"] = $idEntrevistado;
            }

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

    private function gravarEntrevistado($dados)
    {
        $usuario = new Usuario;
        $usuarioExistente = $usuario->where("email", $dados["email"])->first();
        if (!is_null($usuarioExistente)) return $usuarioExistente->id;

        try{
            $usuario->perfil_usuario_id = 3;
            $usuario->nome = $dados["nome"];
            $usuario->email = $dados["email"];
            $usuario->senha = null;
            $usuario->save();
        }catch(\Exception $e){
            return false;
        }

        return $usuario->id;
    }
}
