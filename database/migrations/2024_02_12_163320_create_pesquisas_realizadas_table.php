<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePesquisasRealizadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("pesquisas_realizadas", function (Blueprint $table) {
            $table->id();
            $table->integer("pesquisa_id")->unsigned();
            $table->foreign("pesquisa_id")->references("id")->on("pesquisas")->onDelete("cascade");
            $table->integer("usuario_id")->unsigned();
            $table->foreign("usuario_id")->references("id")->on("usuarios")->onDelete("cascade");
            $table->integer("entrevistado_id")->nullable();
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
        Schema::dropIfExists("pesquisas_realizadas");
    }
}
