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
            $table->double('cuota',$precision = 15, $scale = 2);
            $table->double('saldo',$precision = 15, $scale = 2);
            $table->double('interes',$precision = 15, $scale = 2);
            $table->double('amortizacion',$precision = 15, $scale = 2);
            $table->double('mora',$precision = 15, $scale = 2);
            $table->double('capital',$precision = 15, $scale = 2);
            $table->integer('n_cuota');
            $table->date('fec_vencimiento');
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
