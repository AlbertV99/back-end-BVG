<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAperturasCajaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aperturas_caja', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('caja');
            $table->foreign('caja')->references('id')->on('caja');
            $table->unsignedBigInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('usuario');
            $table->integer('saldo_apertura');
            $table->integer('saldo_cierre')->nullable();
            $table->date('fecha_apertura');
            $table->date('fecha_cierre')->nullable();
            $table->integer('estado');
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
        Schema::dropIfExists('aperturas_caja');
    }
}
