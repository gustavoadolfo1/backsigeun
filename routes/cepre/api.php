<?php

Route::get('criteriosCepre', 'Cepre\GeneralController@getCriterios');
Route::get('getProvin/{dep}', 'Cepre\GeneralController@getProvin');
Route::get('getAcadColegios/{depId}/{provId}', 'Cepre\GeneralController@getAcadColegios');
Route::get('getDistritosAcad/{departamento}/{provincia}', 'Cepre\GeneralController@getDistritos');

Route::post('guardarPreinscrip', 'Cepre\GeneralController@saveData');
Route::post('VerificarInscripcion', 'Cepre\GeneralController@VerificarInscripcion');

Route::get('loadFicha/{ficha}', 'Cepre\CarneController@loadFicha');

Route::group([ 'prefix' => 'cepre' ], function ($router) {

    Route::get('getCicloControl/{fil}', 'Cepre\GeneralController@getCicloControl');
    Route::get('getAllCicloControl/{fil}', 'Cepre\GeneralController@getAllCicloControl');
    Route::post('selControl','Cepre\GeneralController@selControl');
    Route::get('getPreInscripciones/{ciclo}/{fil}', 'Cepre\GeneralController@getPreInscripciones');
    Route::get('getAulasCepre', 'Cepre\GeneralController@getAulasCepre');
    Route::get('getSedesCepre/{dni}', 'Cepre\GeneralController@getSedesCepre');
    Route::post('saveAula','Cepre\GeneralController@saveAula');
    Route::post('editInscripcion','Cepre\GeneralController@editIns');
    Route::get('getTurnos/{fil}','Cepre\GeneralController@getTurnos');
    Route::post('saveTurnos','Cepre\GeneralController@saveTurnos');
    Route::get('getConceptosTurnos','Cepre\GeneralController@getConceptosTurnos');
    Route::post('confirmarIns','Cepre\GeneralController@confirmarIns');

    Route::post('generarCodigo','Cepre\GeneralController@generarCodigo');
    Route::post('addFoto','Cepre\GeneralController@addFoto');
    Route::post('uploadFile','Cepre\GeneralController@uploadFile');
    Route::get('getEstudiantesXaulaxturno/{iGrupoId}/{iTurno}/{iAulaId}','Cepre\GeneralController@getEstudiantesXaulaxturno');
    Route::get('getCriteriosEstudiantes/{fil}','Cepre\GeneralController@getCriteriosEstudiantes');
    Route::post('puchangeExpediente','Cepre\GeneralController@puchangeExpediente');

});



