<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePesquisasUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("pesquisas_usuarios", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("pesquisa_id")->unsigned();
            $table->foreign("pesquisa_id")->references("id")->on("pesquisas");
            $table->integer("usuario_id")->unsigned();
            $table->foreign("usuario_id")->references("id")->on("usuarios");
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
        Schema::dropIfExists("pesquisas_usuarios");
    }
}
