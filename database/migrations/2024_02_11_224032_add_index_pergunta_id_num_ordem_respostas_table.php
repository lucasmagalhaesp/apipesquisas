<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexPerguntaIdNumOrdemRespostasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("respostas", function (Blueprint $table) {
            $table->unique(["pergunta_id", "num_ordem"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("respostas", function (Blueprint $table) {
            $table->dropUnique("respostas_pergunta_id_num_ordem_index");
        });
    }
}
