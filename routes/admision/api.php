<?php

Route::get('getCriteriosAdmision', 'Admision\GeneralController@getCriteriosAdmision');
Route::post('verificarInscripcionAdmision', 'Admision\GeneralController@verificarInscripcion');


Route::get('tk', 'Admision\GeneralController@print');


Route::post('guardarInscripcionAdmision', 'Admision\GeneralController@guardarInscripcionAdmision');

Route::get('admision/buscarInscripcion/{dni}/{grupoControl}', 'Admision\InscripcionController@buscarInscripcion');

Route::group([ 'prefix' => 'admision', 'middleware' => 'auth:api'], function ($router) {

    Route::get('getdataInscripcionesDashboard/{filId}', 'Admision\GeneralController@getdataInscripcionesDashboard');

    // Route::post('getAsistenciaPuerta', 'Admision\GeneralController@getAsistenciaPuerta');
    Route::post('cambioCarrera', 'Admision\GeneralController@cambioCarrera');
    
    Route::post('dataProcesada', 'Admision\ImportarController@dataProcesada');
    

    Route::get('getPreInscripciones/{fil}/{filtro}/{modalidad}/{lugar}/{carrera}/{sexo}/{extraordinario?}', 'Admision\GeneralController@getPreInscripciones');
    Route::get('getCicloControl/{fil}', 'Admision\GeneralController@getCicloControl');
    Route::get('getAllCicloControl/{fil}', 'Admision\GeneralController@getAllCicloControl');
    Route::post('selControl','Admision\GeneralController@selControl');
    Route::get('getSedesCepre/{dni}', 'Admision\GeneralController@getSedesCepre');
    Route::post('addFoto','Admision\GeneralController@addFoto');
    Route::post('uploadFile','Admision\GeneralController@uploadFile');

    Route::get('getPrograma','Admision\GeneralController@getPrograma');
    Route::get('modalidadRequisitos/{mod}','Admision\GeneralController@modalidadRequisitos');
    Route::post('upImg','Admision\GeneralController@upImg');
    Route::post('changeEstadoRequisito/{id}/{estado}','Admision\GeneralController@changeEstadoRequisito');
    Route::get('validarDataInscripcion/{id}/{filial}','Admision\GeneralController@validarDataInscripcion');
    Route::get('traerModalidadxRequisitos','Admision\GeneralController@traerModalidadxRequisitos');

    Route::get('getFiltrosInscripciopnes','Admision\GeneralController@getFiltrosInscripciopnes');

    Route::post('editInscripcion','Admision\InscripcionController@editInscripcion');
    Route::post('editInsAdm','Admision\GeneralController@editInsAdm');

    Route::delete('eliminarInscripcion/{idInscripcion}','Admision\InscripcionController@eliminarInscripcion');
    /*Mantenimiento de Aulas*/
    Route::get('getAulas/{id}','Admision\GeneralController@getAulas');
    Route::get('getTipoAulas','Admision\GeneralController@getTipoAulas');
    Route::post('saveUpdAulas','Admision\GeneralController@saveUpdAulas');
    Route::get('deleteAulas/{id}','Admision\GeneralController@deleteAulas');
    
    Route::get('getDistribuciones/{idFil}','Admision\GeneralController@getDistribuciones');
    Route::get('getDistribucionesAulas/{iProcesoDistribId}','Admision\GeneralController@getDistribucionesAulas');

    Route::get('getnProcesados/{idGrupo}','Admision\GeneralController@getnProcesados');
    
    /*End mantenimientno*/ 
    Route::post('procesarDistribucion','Admision\GeneralController@procesarDistribucion');

    Route::post('cargarFotos','Admision\GeneralController@cargarFotos');
     
    Route::group([ 'prefix' => 'reportes'], function ($router) {
        Route::post('getInscritos/{fil}/{filtro}/{modalidad}/{lugar}/{carrera}/{sexo}/{modaCod?}/{tipo}', 'Admision\ReporteController@getInscritos');

        Route::get('getRecaudacionModalidadReporte/{proceso}/{tipoReturn}/{tipo}', 'Admision\ReporteController@getRecaudacionModalidadReporte');

        Route::post('getRecaudacionModalidadReporteDet/{tipoReturn}/{tipo}', 'Admision\ReporteController@getRecaudacionModalidadReporteDet');

        Route::get('getRecaudacionEscuelaReporte/{proceso}/{tipoReturn}/{tipo}', 'Admision\ReporteController@getRecaudacionEscuelaReporte');

        Route::get('getAsistenciaReporte/{proceso}/{modalidad}/{filial}/{tipoReturn}/{tipo}', 'Admision\ReporteController@getAsistenciaReporte');
    });

    Route::group([ 'prefix' => 'carnets'], function ($router) {
        Route::get('getCriterios', 'Admision\CarnetController@getCriterios');
        Route::get('getListado/{modalidad}/{carrera}/{filial}', 'Admision\CarnetController@getListado');
        Route::get('toggleEntrega/{inscripId}/{bEntrega}', 'Admision\CarnetController@toggleEntrga');

        Route::get('updCheckImpresionMasivo/{modalidad}/{carrera}/{filial}', 'Admision\CarnetController@updCheckImpresionMasivo');
        Route::get('updCheckImpresionUnitario/{inscripcionId}', 'Admision\CarnetController@updCheckImpresionUnitario');
        Route::get('updCheckImpresionSelects/{ids}', 'Admision\CarnetController@updCheckImpresionSelects');
    });

    Route::group([ 'prefix' => 'proceso'], function ($router) {
        Route::post('store','Admision\ProcesoController@store');
        Route::get('activar/{idProceso}','Admision\ProcesoController@activar');
    });

    Route::group([ 'prefix' => 'ingresante'], function ($router) {
        Route::get('enviarExpedienteDASA/{ingresanteId}','Admision\GeneralController@enviarExpedienteDASA');
    });

    Route::get('descargarTXT/{tipoExportacion}/{procesoAdm}','Admision\GeneralController@descargarTXT');

    Route::get('getProcesos','Admision\importarController@getProcesos');
    Route::get('getProcesosCertificados/{idProceso}','Admision\importarController@getProcesosCertificados');
    
    Route::get('getTipoModalidades','Admision\GeneralController@getTipoModalidades');

    Route::get('getProcesosGruposFiliales','Admision\GeneralController@getProcesosGruposFiliales');

    /**
     * Constancias de ingreso
     * 
     */
    Route::get('constaciaIngreso/{id}','Admision\importarController@constaciaIngreso');
    Route::get('toggleEntregaConstancia/{id}/{bEntrega}', 'Admision\importarController@toggleEntregaConstancia');
    Route::get('updImpresionConstancia/{id}', 'Admision\importarController@updImpresionConstancia');
    
});

Route::get('getDistribucionesAulasPersonas/{iProcesoDistribId}/{aula}','Admision\GeneralController@getDistribucionesAulasPersonas');
Route::get('getDistribucionesAulasPersonas2/{iProcesoDistribId}/{aula}','Admision\GeneralController@getDistribucionesAulasPersonas2');
Route::get('padron-publico/{iProcesoDistribId}/{aula}','Admision\GeneralController@getDistribucionesAulasPersonas3');

Route::get('admision/imprimirPreInscripcion/{idInscripcion}/{isHashed?}', 'Admision\InscripcionController@imprimirPreInscripcion');


Route::get('admision/imprimirConstanciaInscripcion/{idInscripcion}/{isHashed?}', 'Admision\InscripcionController@imprimirConstanciaInscripcion');

Route::get('admision/imprimirCarnets/{modalidad}/{carrera}/{filial}', 'Admision\CarnetController@generarCarnets');
Route::get('admision/carnets/imprimirUnCarnet/{inscripcionId}', 'Admision\CarnetController@imprimirUnCarnet');
Route::get('admision/carnets/imprimirCarnetsSeleccionados/{ids}', 'Admision\CarnetController@imprimirCarnetsSeleccionados');

Route::get('admision/descargarTXTRuta','Admision\GeneralController@descargarTXTRuta');

Route::post('admision/getAsistenciaPuerta', 'Admision\GeneralController@getAsistenciaPuerta');
Route::get('admision/getAsistenciaPuerta/{dni}', 'Admision\GeneralController@getAsistenciaPuerta');

