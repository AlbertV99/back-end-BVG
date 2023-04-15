<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParametrizacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parametrizacion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parametro_id');
            $table->foreign('parametro_id')->references('id')->on('parametros_perfil');
            $table->integer('rango_inf');
            $table->integer('rango_sup');
            $table->integer('punto');
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
        Schema::dropIfExists('parametrizacion');
    }
}
