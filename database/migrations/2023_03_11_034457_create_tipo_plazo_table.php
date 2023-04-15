<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoPlazoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_plazo', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion');
            $table->integer('factor_divisor');
            $table->integer('dias_vencimiento');
            $table->decimal('interes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_plazo');
    }
}
