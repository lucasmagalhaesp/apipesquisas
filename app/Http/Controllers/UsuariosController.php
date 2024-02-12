<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Validator;

class UsuariosController extends Controller
{
    private $request;
    private $mdUsuario;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        try {
            $dados = Usuario::all();
        } catch (\Exception $e) {
            return response()->json(["sucesso" => false, "msg" => "Erro ao buscar dados dos usuários"], 400);
        }

        return response()->json(["sucesso" => true, "dados" => $dados], 200);
    }

    public function store()
    {
        $dados = $this->request->dados;
        $dadosValidacao = $this->validarUsuario();
        $validacaoUsuario = Validator::make($dados, $dadosValidacao["regras"], $dadosValidacao["mensagens"]);

        $erros = $validacaoUsuario->errors();
        if (count($erros->all()) > 0) return response()->json(["sucesso" => false, "errosValidacao" => $erros], 402);

        unset($dados["confirmar_senha"]);
        $dados["senha"] = bcrypt($dados["senha"]);
        try {
            Usuario::create($dados);
        } catch (\Exception $e) {
            return response()->json(["sucesso" => false, "msg" => "Erro ao cadastrar usuário"], 400);
        }

        return response()->json(["sucesso" => true], 200);
    }

    public function delete()
    {
        $idUsuario = $this->request->id;

        //não exclui o usuário, apenas inativa
        try {
            Usuario::find($idUsuario)->update(["ativo" => "N"]);
        } catch (\Exception $e) {
            return response()->json(["sucesso" => false, "msg" => "Erro ao cadastrar usuário"], 400);
        }

        return response()->json(["sucesso" => true], 200);
    }

    private function validarUsuario()
    {
        $regras =  [
            "perfil_usuario_id" => "required|numeric", 
            "nome"              => "required|string",
            "email"             => "required|unique:usuarios",
            "senha"             => "required|min:8",
            "confirmar_senha"   => "required|same:senha"
        ];

        $mensagens = [
            "perfil_usuario_id.required"    => "Perfil de usuário não informado",
            "perfil_usuario_id.numeric"     => "Informe o id do perfil de usuário",
            "nome.required"                 => "Nome do usuário não informado",
            "email.required"                => "E-mail do usuário não informado",
            "email.unique"                  => "E-mail já cadastrado. Informe outro e-mail ou atualize o seu cadastro",
            "senha.required"                => "Senha não informada",
            "senha.min"                     => "A senha precisa ter pelo menos 8 caracteres",
            "confirmar_senha.required"      => "Confirme a senha informada",
            "confirmar_senha.same"          => "As senhas não coincidem"           
        ];
       
        return [
            "regras"    => $regras,
            "mensagens" => $mensagens
        ];
    }
}
