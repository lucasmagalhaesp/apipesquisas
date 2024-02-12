<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPesquisaRealizadaIdPerguntasRespostasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("perguntas_respostas", function (Blueprint $table) {
            //$table->integer("pesquisa_realizada_id")->unsigned();
            $table->foreignId("pesquisa_realizada_id")->constrained("pesquisas_realizadas")->onDelete("cascade");
            //$table->foreign("pesquisa_realizada_id")->references("id")->on("pesquisas_realizadas")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("perguntas_respostas", function (Blueprint $table) {
            $table->dropColumn("pesquisa_realizada_id");
        });
    }
}
