<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cliente', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barrio');
            $table->foreign('barrio')->references('id')->on('barrio');
            $table->string("documento")->unique();
            $table->unsignedBigInteger('tipo_documento');
            $table->string('nombre');
            $table->string('apellido');
            $table->date('f_nacimiento');
            $table->string('correo');
            $table->string('direccion');
            $table->string('sexo');
            $table->string('observaciones')->nullable();
            $table->unsignedBigInteger('estado_civil');
            $table->foreign('estado_civil')->references('id')->on('estado_civil');
            $table->timestamps();
            $table->softDeletes($column = 'eliminado', $precision = 0);
            // $table->foreign('barrio')->references('id')->on('barrio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cliente');
    }
}
