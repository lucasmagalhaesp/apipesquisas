<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesquisa;
use App\Models\PesquisaRealizada;
use App\Models\Usuario;
use App\Models\Resposta;

class DashboardController extends Controller
{
    public function index()
    {
        $usuariosAtivos = Usuario::where("ativo", "S")->count();
        $usuariosInativos = Usuario::where("ativo", "N")->count();
        $pesquisasAtivas = Pesquisa::where("ativa", "S")->count();
        $pesquisasInativas = Pesquisa::where("ativa", "N")->count();
        $pesquisasRealizadas = PesquisaRealizada::count();

        return response()->json(["dados" => [
            "usuariosAtivos"        => $usuariosAtivos,
            "usuariosInativos"      => $usuariosInativos,
            "pesquisasAtivas"       => $pesquisasAtivas,
            "pesquisasInativas"     => $pesquisasInativas,
            "pesquisasRealizadas"   => $pesquisasRealizadas
        ]]);
    }

    public function getPesquisasRealizadas()
    {
        $pesquisasRealizadas = PesquisaRealizada::with("perguntasRespostas")->with("pesquisa")->get();
        $groups = collect($pesquisasRealizadas)->groupBy("pesquisa_id");

        return response()->json(array_slice($groups->toArray(), 0), 200);
    }

    public function getRespostas()
    {
        return response()->json(Resposta::select("id", "descricao")->get(), 200);
    }
}
