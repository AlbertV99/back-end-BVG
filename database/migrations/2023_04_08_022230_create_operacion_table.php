<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operacion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('caja');
            $table->foreign('caja')->references('id')->on('caja');
            $table->unsignedBigInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('usuario');
            $table->unsignedBigInteger('concepto');
            $table->foreign('concepto')->references('id')->on('conceptos_caja');
            $table->integer('saldo_anterior');
            $table->integer('monto');
            $table->integer('saldo_posterior');
            $table->date('fecha_operacion');
            $table->unsignedBigInteger('solicitud_id')->nullable();


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
        Schema::dropIfExists('operacion');
    }
}
