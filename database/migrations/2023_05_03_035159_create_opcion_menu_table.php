<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpcionMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opcion_menu', function (Blueprint $table) {

            $table->id();
            $table->string("descripcion");
            $table->string("observacion")->nullable();
            $table->string("direccion");
            $table->string("dir_imagen")->default('Imagenes/opcionMenu/estatico.png');
            $table->unsignedBigInteger('agrupador_id');
            $table->foreign('agrupador_id')->references('id')->on('agrupador');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('opcion_menu');
    }
}
