<?php

namespace App\Http\Controllers;

use App\Models\PesquisaRealizada;
use App\Models\Pesquisa;
use App\Models\Usuario;
use Illuminate\Http\Request;
use DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PesquisasRealizadasController extends Controller
{
    public function index()
    {
        $perfil = auth()->user()->perfil_usuario_id;
        $pesquisasRealizadas = PesquisaRealizada::where(function ($query) use ($perfil){
            if ($perfil != 1) $query->where("usuario_id", auth()->user()->id);
        })
        ->orderBy("id", "desc")
        ->with("perguntasRespostas", "pesquisa", "agente", "entrevistado")->get();
        
        return response()->json(["sucesso" => true, "dados" => $pesquisasRealizadas], 200);
    }

    public function show(PesquisaRealizada $pesquisasRealizada)
    {
        try {
            $dadosPesquisa = $pesquisasRealizada->with("perguntasRespostas")->with("pesquisa")->find($pesquisasRealizada->id);
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

        //caso seja uma pesquisa para gravar o entrevistado, verificar se os dados foram enviados
        if ($gravarEntrevistado){
            if (!isset($dados["nome"]) || is_null($dados["nome"]) || $dados["nome"] == "")
                return response()->json("Nome do entrevistado não informado", 402);
                
            if (!isset($dados["email"]) || is_null($dados["email"]) || $dados["email"] == "" || !filter_var($dados["email"], FILTER_VALIDATE_EMAIL))
                return response()->json("E-mail do entrevistado não informado", 402);
        }

        try {
            DB::beginTransaction();

            if ($gravarEntrevistado){
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

    private function getDados($pesquisa)
    {
        try {
            $dados = PesquisaRealizada::with("agente")->with("entrevistado")->with("perguntasRespostas")->with("pesquisa")->find($pesquisa);
        } catch (\Exception $e) {
            return response()->json(["sucesso" => false, "msg" => "Erro ao pegar os dados da pesquisa ({$e->getMessage()})"]);
        }

        $perguntasRespostas = $dados["perguntasRespostas"];
        $perguntas = $dados["pesquisa"]["perguntas"];
        $listaPerguntas = [];
        foreach($perguntas as $index => $pergunta){
            $idPergunta = $pergunta["id"];
            $resp = array_filter($perguntasRespostas->toArray(), function ($item) use ($idPergunta){
                return $item["pergunta_id"] == $idPergunta;
            });

            try{
                $resposta = array_filter($pergunta["respostas"]->toArray(), function ($item) use ($resp, $index){
                    return $item["id"] == $resp[$index]["resposta_id"];
                });
            }catch(\Exception $e){
                return $resp;
            }

            array_push($listaPerguntas, [
                "pergunta" => $pergunta["descricao"],
                "resposta" => array_slice($resposta, 0)[0]["descricao"]
            ]);
        }

        unset($dados["perguntasRespostas"]);
        //unset($dados["pesquisa"]);

        $dadosPesquisa = [
            "dados"             => $dados,
            "listaPerguntas"    => $listaPerguntas
        ];

        return $dadosPesquisa;
    }

    public function visualizar($pesquisa)
    {
        $dadosPesquisa = $this->getDados($pesquisa);

        return response()->json(["sucesso" => true, "pesquisa" => $dadosPesquisa], 200);
    }

    public function gerarRelatorio($pesquisa)
    {
        $dadosPesquisa = $this->getDados($pesquisa);
        $pdf = Pdf::loadView("relatorios.pesquisa-realizada", $dadosPesquisa);
        return $pdf->stream("teste.pdf");
    }
}
