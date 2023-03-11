<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReglasEstadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reglas_estados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estado_regla');
            $table->foreign('estado_regla')->references('id')->on('estado_solicitud');
            $table->unsignedBigInteger('estado_posible');
            $table->foreign('estado_posible')->references('id')->on('estado_solicitud');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reglas_estados');
    }
}
