<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoEntrevistadoPesquisasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("pesquisas", function (Blueprint $table) {
            $table->enum("tipo_entrevistado", ["A", "C"])->comment("A - Anônimo, C - Cadastrado");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("pesquisas", function (Blueprint $table) {
            $table->dropColumn("tipo_entrevistado");
        });
    }
}
