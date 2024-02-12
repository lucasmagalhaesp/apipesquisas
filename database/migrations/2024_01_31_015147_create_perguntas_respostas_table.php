<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerguntasRespostasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("perguntas_respostas", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("pergunta_id")->unsigned();
            $table->foreign("pergunta_id")->references("id")->on("perguntas");
            $table->integer("resposta_id")->unsigned();
            $table->foreign("resposta_id")->references("id")->on("respostas");
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
        Schema::dropIfExists("perguntas_respostas");
    }
}
