<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexPesquisaIdNumOrdemPerguntasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("perguntas", function (Blueprint $table) {
            $table->unique(["pesquisa_id", "num_ordem"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("perguntas", function (Blueprint $table) {
            $table->dropUnique("perguntas_pesquisa_id_num_ordem_index");
        });
    }
}
