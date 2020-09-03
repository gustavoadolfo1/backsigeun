<?php

use Illuminate\Http\Request;

Route::group(['middleware' => 'auth:api', 'prefix' => 'usegresado/egresado'], function ($router) {

    Route::group(['prefix' => 'control'], function ($router) {

        Route::get('obternerTipoEncuesta', 'Usegresado\SeguimientoEncuestaController@obternerTipoEncuesta');
        Route::get('obternerListaConvenios', 'Usegresado\SeguimientoEncuestaController@obternerListaConvenios');
        Route::get('obternerTipoCursos', 'Usegresado\SeguimientoEncuestaController@obternerTipoCursos');
        Route::get('obternerFilial', 'Usegresado\SeguimientoEncuestaController@obternerFilial');
        Route::get('obternerTipoTrabajo', 'Usegresado\SeguimientoEncuestaController@obternerTipoTrabajo');
        Route::get('obternerTipoPuesto', 'Usegresado\SeguimientoEncuestaController@obternerTipoPuesto');
        Route::get('obternerAreaTrabajo', 'Usegresado\SeguimientoEncuestaController@obternerAreaTrabajo');
        Route::get('obternerSector', 'Usegresado\SeguimientoEncuestaController@obternerSector');
        Route::get('obternerPais', 'Usegresado\SeguimientoEncuestaController@obternerPais');
        Route::get('obternerEntidad', 'Usegresado\SeguimientoEncuestaController@obternerEntidad');
        Route::get('obternerSelected', 'Usegresado\SeguimientoEncuestaController@obternerSelected');
        
    });

    Route::group(['prefix' => 'seguimiento'], function ($router) {
        Route::post('Taller/'						, 'Usegresado\SeguimientoEncuestaController@Taller');
        Route::post('Practicas/'						, 'Usegresado\SeguimientoEncuestaController@Practicas');
        Route::post('DatosPersonales/'						, 'Usegresado\SeguimientoEncuestaController@DatosPersonales');
        Route::post('Empresas/'						, 'Usegresado\SeguimientoEncuestaController@Empresas');
        Route::post('Avisos/'						, 'Usegresado\SeguimientoEncuestaController@Avisos');
		
        
    });

   
});

Route::group(['prefix' => 'seguimiento'], function ($router) {
    Route::get('BuscarEgresado', 			'Usegresado\SeguimientoEncuestaController@BuscarEgresado');
    Route::get('BuscarGraduado', 			'Usegresado\SeguimientoEncuestaController@BuscarGraduado');

});









