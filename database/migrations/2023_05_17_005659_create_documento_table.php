<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentoTable extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documento', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->date('fecha_vencimiento')->nullable();
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->unsignedBigInteger('solicitud_id_contrato')->nullable();
            $table->unsignedBigInteger('solicitud_id_pagare')->nullable();
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
        Schema::dropIfExists('documento');
    }
}
