<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuotas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('solicitud_id');
            $table->foreign('solicitud_id')->references('id')->on('solicitud');
            $table->decimal('cuota');
            $table->decimal('saldo');
            $table->decimal('interes');
            $table->decimal('amortizacion');
            $table->decimal('mora');
            $table->decimal('capital');
            $table->integer('n_cuota');
            $table->unsignedBigInteger('estado');
            $table->foreign('estado')->references('id')->on('estado_cuota');
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
        Schema::dropIfExists('cuotas');
    }
}
