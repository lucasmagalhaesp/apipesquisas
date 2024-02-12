<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $email = $request->dados["email"];
        $senha = $request->dados["senha"];

        $usuario = Usuario::where("email", $email)->first();
        if (is_null($usuario) || !Hash::check($senha, $usuario->senha)) return response()->json(["sucesso" => false, "msg" => "E-mail ou senha incorretos"], 400);

        $token = $usuario->createToken("app_pesquisa");

        return response()->json(["sucesso" => true, "token" => $token->plainTextToken], 200);
    }

    public function logout(Request $request)
    {
        $request->user->tokens()->delete();
        return response()->json(["sucesso" => true], 200);
    }
}
