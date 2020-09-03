<?php

use Illuminate\Http\Request;


Route::group(['prefix' => 'ceid'], function () {

    Route::get('obtenerRitmosCiclos/{moduloid}', 'ceid\GeneralController@obtenerRitmosCiclos');

    Route::get('emailTest', 'ceid\GeneralController@testMail');
    Route::get('testViewEmail', function() {
       return view('ceid/mail') ;
    });
    Route::post('sendEmailPreinscrito', 'ceid\GeneralController@handleEmail');


    Route::group(['prefix' => 'horario'], function () {
        Route::get('preHorariosXFilialidMoProg', 'ceid\HorarioController@preHorariosXFilialidMoProg');
    });



    Route::group(['prefix' => 'preinscripcion'], function () {
        Route::post('crearPreInscripcion', 'ceid\PreinscripcionController@crearPreInscripcion');
    });

});

Route::group(['prefix' => 'ceid', 'middleware' => 'auth:api'], function () {
    Route::get('obtenerDiasDisponiblesXFilialIdModIdRItmoId', 'ceid\GeneralController@obtenerDiasDisponiblesXFilialIdModIdRItmoId');

    Route::group(['prefix' => 'horario' ], function() {
        Route::get('preHorariosXFilialModRitmoNivelCarrera', 'ceid\HorarioController@preHorariosXFilialModRitmoNivelCarrera');
    });
    Route::group(['prefix' => 'preinscripcion'], function() {
        Route::get('obtenerPreinsByDiasRitmoFilialModulo', 'ceid\PreinscripcionController@obtenerPreinsByDiasRitmoFilialModulo');
        Route::get('preinscritosXModProgHorasFilialRitmoFechas', 'ceid\PreinscripcionController@preinscritosXModProgHorasFilialRitmoFechas');
    });

});
