<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'grl'], function () {
    Route::any('configuracion', 'ConfiguracionesGeneralesController@conf');

    Route::any('dataexistenteAnonimo/{tipo}', 'Generales\RespuestasApiController@anonimo');

    Route::any('guardarArchivo', 'Generales\ArchivosController@cargarArchivos');

    Route::get('getIdentificacionesTipos', 'Generales\GrlPersonasController@getIdentificacionesTipos');
    Route::get('buscarPersona/{dni}/{tipoDoc}', 'Generales\GrlPersonasController@buscarPersona');


    Route::group(['middleware' => 'auth:api', 'namespace' => 'Generales'], function () {
        Route::any('dataexistente/{tipo}', 'RespuestasApiController@getData');
        Route::any('guardar/{tipo}', 'RespuestasApiController@setData');

        Route::group(['prefix' => 'archivos'], function () {
            Route::any('cargar', 'ArchivosController@cargarArchivos');
            Route::any('eliminar', 'ArchivosController@eliminarTemporales');
        });
    });
});
