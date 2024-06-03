<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePesquisasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("pesquisas", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("categoria_id")->unsigned();
            $table->foreign("categoria_id")->references("id")->on("categorias");
            $table->string("titulo", 100);
            $table->string("descricao");
            $table->enum("ativa", ["S","N"])->default("S");
           //$table->integer("num_perguntas");
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
        Schema::dropIfExists("pesquisas");
    }
}
