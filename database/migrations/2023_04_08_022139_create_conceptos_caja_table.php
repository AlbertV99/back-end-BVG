<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConceptosCajaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conceptos_caja', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
            $table->string('descripcion');
            $table->softDeletes($column = 'eliminado', $precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conceptos_caja');
    }
}
