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

Route::group(['prefix' => 'dasa'], function () {
    Route::any('obtenerReporteHorarios/{tipo}', 'Ura\UraHorarioController@obtenerReporteHorarios');
    Route::any('ReporteRelacionDocentesSilaboPdf/{iFilId}/{iCarreraId}/{iSemestre}/{opcion}', 'DASA\ReporteController@ReporteRelacionDocentesSilabo');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::any('dataexistente/{tipo}', 'DASA\DasaController@getData');
        Route::any('guardar/{tipo}', 'DASA\DasaController@setData');
    });
});
