<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUbcPrestamosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ubc.prestamos', function (Blueprint $table) {
            $table->bigIncrements('iPrestamoId');
            $table->integer('iLibro_Id');
            $table->string('cPrestamoNombres')->nullable();
            $table->string('cPrestamoApellidos')->nullable();
            $table->datetime('dtPrestamoInicia');
            $table->datetime('dtPrestamoFinaliza')->nullable();
            $table->datetime('dtPrestamoDevolucion')->nullable();
            $table->string('cPrestamoEstado');
            $table->integer('user_id_solicitante')->nullable();
            $table->integer('user_id_atiende')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ubc.prestamos');
    }
}
