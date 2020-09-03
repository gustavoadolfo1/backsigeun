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

Route::group(['prefix' => 'inv'], function () {
    Route::any('dataexistenteAnonimo', 'Inv\InvestigacionController@leerDataAnonimo');
    Route::any('guardarAnonimo', 'Inv\InvestigacionController@guardarDataAnonimo');
    Route::any('pdf', 'Inv\InvestigacionController@generarPDF');
});

Route::post('investigaciones/postulante/registrarNuevoPostulante', 'Inv\PostulanteController@registrarNuevoPostulante');





Route::group(['prefix' => 'inv', 'middleware' => 'auth:api'], function (){
//Route::group(['prefix' => 'inv'], function (){
    Route::any('dataexistente', 'Inv\InvestigacionController@leerData');
    Route::any('guardar', 'Inv\InvestigacionController@guardarData');
    Route::any('guardarArchivo', 'Inv\InvestigacionController@guardarDataArchivo');

    Route::get('actualizarDatosReniec/{FN}', 'Inv\InvestigacionController@actualizarDatosReniec');
    /*
        Route::group(['prefix' => 'dasa', 'middleware' => 'auth:api'], function () {
            Route::any('dataexistente', 'Inv\DasaController@leerData');
            Route::any('guardar', 'Tram\DasaController@guardarData');
        });*/
});






Route::group(['prefix' => 'inv/gestion/descargas'], function($router){
    //reporte en general
    Route::get('pptTotalProyXanyoEstPropt/{a}/{b}/{c}', 'inv\ReportePdfController@descargaPptTotalProyXanyoEstProptPdf');
    Route::get('pptxRubroXanyoEstPropt/{a}/{b}/{c}', 'inv\ReportePdfController@descargaPptxRubroXanyoEstProptPdf');
    Route::get('gtxRubroXanyoEstPropt/{a}/{b}/{c}', 'inv\ReportePdfController@descargaGtxRubroXanyoEstProptPdf');

    Route::get('infAvTec/{a}/{b}/{c}', 'inv\ReportePdfController@descargaInfAvTecPdf');

    // reporte por proyecto en submodulo
    Route::get('repPptGtXrubroResumen/{a}', 'inv\ReportePdfController@descargaRepPptGastoXrubroResumenPdf');
    Route::get('repPptGtXrubroDetallado/{a}', 'inv\ReportePdfController@descargaRepPptGastoXrubroDetalladoPdf');

//reporte presupuesto
    Route::get('repPptPtXrubroResumen/{a}', 'inv\ReportePdfController@descargaRepPptPresupuestoXrubroResumenPdf');
    Route::get('repPptPtXrubroDetallado/{a}', 'inv\ReportePdfController@descargaRepPptPresupuestoXrubroDetalladoPdf');
    //reporte proyectos por escuela docentes
    Route::get('reporte_proyecto_docentesXescuela/{a}', 'inv\ReportePdfController@descargaProyectosXescuela_docentesPdf');
    Route::get('reporte_webapi/{a}/{b}/{c}', 'inv\ReportePdfController@reporte_web_funcion');
    Route::get('reporte_trimestralapi/{a}/{b}/{c}', 'inv\ReportePdfController@reporte_detalle_trimestral');
    Route::get('reporte_miembrosapi/{a}/{b}/{c}', 'inv\ReportePdfController@reporte_miembros');
    Route::get('reporte_consolidadoapi/{a}/{b}', 'inv\ReportePdfController@reporte_consolidado');
    Route::get('reporte_revisionitemsapi/{a}/{b}', 'inv\ReportePdfController@reporte_revisionitems');
//excel reportes

    Route::get('excel_web/{a}/{b}', 'inv\ReporteExcelController@web_funcion');
 ////lo tocaRE un ratito   Route::get('excel_trimestral/{a}/{b}', 'inv\ReporteExcelController@trimestral_funcion');

    Route::get('excel_miembros/{a}/{b}', 'inv\ReporteExcelController@miembros_funcion');
    Route::get('excel_consolidado/{a}', 'inv\ReporteExcelController@consolidado_funcion');
    Route::get('excel_consolidado_saldos/{a}', 'inv\ReporteExcelController@consolidado_funcion_saldos');
    Route::get('excel_items/{a}', 'inv\ReporteExcelController@items_funcion');
//excel dificil trimestral
   Route::get('excel_trimestral/{tipo}/{anyo}', 'inv\ExportReporteController@reporteExceltrimestral');

    Route::any('excel_pdftrimestral/{iFilId}/{iCarreraId}/{iSemestre}/{opcion}', 'Inv\ReporteController@getRecojoInfoMINEDU');
    Route::get('excel_trimestral1/{a}/{b}', 'inv\ReporteExcelController@exportExcel');


    Route::get('descargaFichaEvaluacion/', 'DBU\ComedorUniversitarioController@ImprimirFichaE');
    Route::get('descargaHCPdf/', 'DBU\SaludController@descargaHCPdf');
    Route::get('descargaPdfFecha/{a}/{b}', 'DBU\SaludController@descargaPdfFecha');


});

Route::get('pdfcertificados/{id}', 'Tram\ReportePdfController@Pdf_certificados')->name('Pdf_certificados');

