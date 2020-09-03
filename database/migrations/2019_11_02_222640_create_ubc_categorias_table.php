<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUbcCategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ubc.categorias', function (Blueprint $table) {
            $table->bigIncrements('iCategoriaId');
            $table->string('cCategoriaNombre', 60)->nullable();
            $table->integer('cCategoria_id')->nullable();
            $table->string('cCategoriaUsuarioSis', 50)->nullable();
            $table->dateTime('dtCategoriaFechaSis')->nullable();
            $table->string('cCategoriaEquipoSis', 50)->nullable();
            $table->ipAddress('cCategoriaIpSis')->nullable();
            $table->char('cCategoriaOpenUsr', 1)->nullable();
            $table->macAddress('cCategoriaMacNicSis')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ubc.categorias');
    }
}
