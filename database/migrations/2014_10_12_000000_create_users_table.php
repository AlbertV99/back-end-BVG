<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_usuario')->unique();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('cedula');
            $table->string('password');
            $table->date('fecha_nacimiento');
            $table->string('email');
            $table->unsignedBigInteger('perfil_id');
            $table->boolean('restablecer_password');
            $table->softDeletes($column = 'activo', $precision = 0);
            $table->rememberToken();
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
        Schema::dropIfExists('usuario');
    }
}
