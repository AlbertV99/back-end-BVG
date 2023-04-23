<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitud', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('cliente');
            $table->integer('ingresos_actuales');
            $table->integer('monto_credito');
            $table->integer('gastos_administrativos');
            $table->decimal('interes');
            $table->decimal('interes_moratorio');
            $table->unsignedBigInteger('tipo_plazo');
            $table->foreign('tipo_plazo')->references('id')->on('tipo_plazo');
            $table->string('observacion')->nullable();
            $table->unsignedBigInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('usuario');
            $table->date('fecha_retiro')->nullable();
            $table->date('vencimiento_retiro')->nullable();
            $table->integer('estado')->nullable();
            $table->integer('cant_cuotas');
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
        Schema::dropIfExists('solicitud');
    }
}
