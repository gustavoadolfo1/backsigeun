<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUbcLibrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ubc.libros', function (Blueprint $table) {
            $table->bigIncrements('iLibroId');
            $table->integer('iCategoria_Id')->nullable();
            $table->string('cLibroTitulo');
            $table->string('cLibroAutor')->nullable();
            $table->text('cLibroDescripcion')->nullable();
            $table->string('cLibroCodigo')->nullable();
            $table->string('cLibroIsbn')->nullable();
            $table->string('cLibroEditorial')->nullable();
            $table->dateTime('dtLibroFechaPublicacion')->nullable();
            $table->dateTime('dtLibroFechaEntrada')->nullable();
            $table->string('cLibroEdicion')->nullable();
            $table->string('cLibroProcedencia')->nullable();
            $table->text('cLibroTablaContenido')->nullable();
            $table->string('cLibroPortada')->nullable();
            $table->integer('iLibroCantidadEjemplares')->nullable()->default(1);
            $table->integer('iLibroVisitas')->default(0)->nullable();
            $table->string('cLibroNumero')->nullable();
            $table->string('cLibroVolumen')->nullable();
            $table->string('cLibroTomo')->nullable();
            $table->string('cLibroUsuarioSis', 50)->nullable();
            $table->dateTime('dtLibroFechaSis')->nullable();
            $table->string('cLibroEquipoSis', 50)->nullable();
            $table->ipAddress('cLibroIpSis')->nullable();
            $table->char('cLibroOpenUsr', 1)->nullable();
            $table->macAddress('cLibroMacNicSis')->nullable();

            $table->foreign('iCategoria_Id')
              ->references('iCategoriaId')->on('ubc.categorias')
              ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ubc.libros');
    }
}
