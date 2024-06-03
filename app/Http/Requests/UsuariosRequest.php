<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuariosRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "dados.perfil_usuario_id" => "required|numeric", 
            "dados.nome"              => "required|string",
            "dados.email"             => "required|unique:usuarios",
            "dados.senha"             => "required|min:8",
            "dados.confirmar_senha"   => "required|same:senha"
        ];
    }
}
