<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Usuario::create([
            "perfil_usuario_id" => 1,
            "nome"              => "Admin",
            "email"             => "admin@pesquisa.sem",
            "senha"             => bcrypt("123456")
        ]);
    }
}
