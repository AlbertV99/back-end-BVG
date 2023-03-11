<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferenciaComercialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referencia_comercial', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('solicitud_id');
            // $table->foreign('solicitud_id')->references('id')->on('estado_civil'); referenciar a la solicitud
            $table->string('entidad');
            $table->string('estado');
            $table->integer('monto_cuota');
            $table->integer('cuotas_pendientes');
            $table->integer('cuotas_totales');
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
        Schema::dropIfExists('referencia_comercial');
    }
}
