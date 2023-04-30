<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleOperacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('detalle_operacion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cuota_id');
            $table->foreign('cuota_id')->references('id')->on('cuotas');
            $table->unsignedBigInteger('operacion_id');
            $table->foreign('operacion_id')->references('id')->on('operacion');
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
        Schema::dropIfExists('detalle_operacion');
    }
}
