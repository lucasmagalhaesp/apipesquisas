<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerguntasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("perguntas", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("pesquisa_id")->unsigned();
            $table->foreign("pesquisa_id")->references("id")->on("pesquisas")->onDelete("cascade");
            $table->string("descricao");
            $table->integer("num_ordem");
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
        Schema::dropIfExists("perguntas");
    }
}
