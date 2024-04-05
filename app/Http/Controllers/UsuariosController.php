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
            $dados = Usuario::with("perfilUsuario")->get();
        } catch (\Exception $e) {
            return response()->json(["sucesso" => false, "msg" => "Erro ao buscar dados dos usuários. ({$e->getMessage()})"], 400);
        }

        return response()->json(["sucesso" => true, "dados" => $dados], 200);
    }

    public function show($id)
    {
        try {
            $dados = Usuario::find($id);
        } catch (\Exception $e) {
            return response()->json(["sucesso" => false, "msg" => "Erro ao buscar dados do usuário. ({$e->getMessage()})"], 400);
        }

        return response()->json(["sucesso" => true, "dados" => $dados], 200);
    }

    public function store()
    {
        $dados = $this->request->dados;
        $dadosValidacao = $this->validarUsuario($dados);
        $validacaoUsuario = Validator::make($dados, $dadosValidacao["regras"], $dadosValidacao["mensagens"]);

        $erros = $validacaoUsuario->errors();
        if (count($erros->all()) > 0) return response()->json(["sucesso" => false, "errosValidacao" => $erros], 402);

        //verificar se já existe algum usuário com o mesmo e-mail
        $emailExistente = Usuario::where("email", $dados["email"])->where("id", "!=", $dados["id"])->get();
        if (count($emailExistente)) return response()->json(["sucesso" => false, "msg" => "E-mail já cadastrado. Informe outro e-mail ou atualize o seu cadastro"], 400); 

        unset($dados["confirmar_senha"]);
        $dados["senha"] = bcrypt($dados["senha"]);
        try {
            Usuario::updateOrCreate(["id" => $dados["id"]], $dados);
        } catch (\Exception $e) {
            return response()->json(["sucesso" => false, "msg" => "Erro ao cadastrar usuário"], 400);
        }

        return response()->json(["sucesso" => true], 200);
    }

    public function destroy($id)
    {
        //não exclui o usuário, apenas inativa
        try {
            Usuario::find($id)->update(["ativo" => "N"]);
        } catch (\Exception $e) {
            return response()->json(["sucesso" => false, "msg" => "Erro ao cadastrar usuário"], 400);
        }

        return response()->json(["sucesso" => true], 200);
    }

    private function validarUsuario($dados)
    {
        $edicao = !is_null($dados["id"]);

        $regras =  [
            "perfil_usuario_id" => "required|numeric",
            "nome"              => "required|string",
            "email"             => "required"
            // "email"             => "required|unique:usuarios"
        ];

        $mensagens = [
            "perfil_usuario_id.required"    => "Perfil de usuário não informado",
            "perfil_usuario_id.numeric"     => "Informe o id do perfil de usuário",
            "nome.required"                 => "Nome do usuário não informado",
            "email.required"                => "E-mail do usuário não informado",
            //"email.unique"                  => "E-mail já cadastrado. Informe outro e-mail ou atualize o seu cadastro"
        ];

        if (!$edicao || strlen($dados["senha"]) > 0){
            $regras = array_merge($regras, [
                "senha"             => "required|min:8",
                "confirmar_senha"   => "required|same:senha"
            ]);

            $mensagens = array_merge($mensagens, [
                "senha.required"                => "Senha não informada",
                "senha.min"                     => "A senha precisa ter pelo menos 8 caracteres",
                "confirmar_senha.required"      => "Confirme a senha informada",
                "confirmar_senha.same"          => "As senhas não coincidem"           
            ]);
        }
       
        return [
            "regras"    => $regras,
            "mensagens" => $mensagens
        ];
    }
}
