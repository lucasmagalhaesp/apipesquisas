<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("usuarios", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("perfil_usuario_id")->unsigned();
            $table->foreign("perfil_usuario_id")->references("id")->on("perfis_usuarios");
            $table->string("nome", 100);
            $table->string("email")->unique();
            $table->string("senha", 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("usuarios");
    }
}
