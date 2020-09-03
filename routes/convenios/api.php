<?php

use Illuminate\Http\Request;

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

Route::group(['prefix' => 'conv'], function () {

    Route::any('dataexistenteAnonimo', 'Convenios\ConveniosController@leerDataAnonimo');

});


Route::group(['prefix' => 'conv', 'middleware' => 'auth:api'], function () {

    Route::any('dataexistente', 'Convenios\ConveniosController@leerData');
    Route::any('guardar', 'Convenios\ConveniosController@guardarData');
    Route::any('guardarArchivo', 'Convenios\ConveniosController@guardarDataArchivo');


    /*
        Route::group(['prefix' => 'dasa', 'middleware' => 'auth:api'], function () {
            Route::any('dataexistente', 'Convenios\DasaController@leerData');
            Route::any('guardar', 'Tram\DasaController@guardarData');
        });*/
});

Route::group(['prefix' => 'conv/gestion/descargas'], function ($router) {

    Route::get('pptTotalProyXanyoEstPropt/{a}/{b}/{c}', 'conv\ReportePdfController@descargaPptTotalProyXanyoEstProptPdf');
    Route::get('pptxRubroXanyoEstPropt/{a}/{b}/{c}', 'conv\ReportePdfController@descargaPptxRubroXanyoEstProptPdf');
    Route::get('gtxRubroXanyoEstPropt/{a}/{b}/{c}', 'conv\ReportePdfController@descargaGtxRubroXanyoEstProptPdf');

    Route::get('infAvTec/{a}/{b}/{c}', 'conv\ReportePdfController@descargaInfAvTecPdf');

    Route::get('unionback/{a}', 'Convenios\ReportePdfController@funcionconvenio');

    // reporte por proyecto
    Route::get('repPptGtXrubroResumen/{a}', 'conv\ReportePdfController@descargaRepPptGastoXrubroResumenPdf');
    Route::get('repPptGtXrubroDetallado/{a}', 'conv\ReportePdfController@descargaRepPptGastoXrubroDetalladoPdf');




});

Route::get('pdfcertificados/{id}', 'Tram\ReportePdfController@Pdf_certificados')->name('Pdf_certificados');

