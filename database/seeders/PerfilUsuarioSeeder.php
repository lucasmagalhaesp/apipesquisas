<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PerfilUsuario;

class PerfilUsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PerfilUsuario::insert([
            ["id" => 1, "nome" => "Admin"],
            ["id" => 2, "nome" => "Agente"],
            ["id" => 3, "nome" => "Entrevistado"]
        ]);
    }
}
