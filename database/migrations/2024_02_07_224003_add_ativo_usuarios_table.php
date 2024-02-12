<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAtivoUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("usuarios", function (Blueprint $table) {
            $table->enum("ativo", ["S","N"])->default("S");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("usuarios", function (Blueprint $table) {
            $table->dropColumn("ativo");
        });
    }
}
