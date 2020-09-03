<?php

use Illuminate\Http\Request;

Route::group(['middleware' => 'auth:api', 'prefix' => 'docente'], function ($router) {

    Route::group(['prefix' => 'control'], function ($router) {

        Route::get('asistencia/{a}/{b}/{c}/{d}/{e}/{f}', 'Docente\DocenteController@asistenciaCabecera');

        Route::get('asistencialist/{a}/{b}/{c}/{d}/{e}/{f}', 'Docente\DocenteController@asistenciaList');

        Route::get('listestudiante/{a}/{b}/{c}/{d}/{e}/{f}/{g}', 'Docente\DocenteController@listadoEstudiantes');

        Route::get('listfechas/{a}/{b}/{c}/{d}/{e}/{f}/{g}', 'Docente\DocenteController@listadoFechas');

        Route::get('downloadestudiante/{a}/{b}/{c}/{d}/{e}/{f}/{g}', 'Docente\DocenteController@exportlistEstudiantes');

        Route::get('descargahorario/{a}/{b}', 'Docente\DocenteController@HorarioDocente');

        Route::get('descargaestudiante/{a}/{b}/{c}/{d}/{e}/{f}/{g}', 'Docente\DocenteController@exportlistEstudiantesXls');

        // Route::get('descargaSilaboDocentePdf/{a}/{b}/{c}/{d}', 'Docente\SilaboDocController@descargaSilaboDocentePdf');

        /*

        */

        Route::post('NotasEstudiante/', 'Docente\DocenteController@NotasEstudiante');

        /*   Route::get('descargaAsistenciaExcel/{a}/{b}/{c}/{d}/{e}/{f}/{g}', 'Docente\DocenteController@exportlistAsistenciaExcel'); */

        /*  Route::get('descargaAsistenciaPdf/{a}/{b}/{c}/{d}/{e}/{f}/{g}', 'Docente\DocenteController@exportlistAsistenciaPdf'); */

        Route::post('clases', 'Docente\AsistenciaController@numeroClasesDocente');


        Route::post('insertarUnidadesCurso', 'Docente\DocenteController@insertarUnidadesCurso');

        Route::get('obtenerUnidadesCurso/{a}/{b}/{c}/{d}/{e}/{f}/{g}', 'Docente\DocenteController@obtenerUnidadesCurso');

        Route::get('cambiarFechaParcial/{a}/{b}', 'Docente\DocenteController@cambiarFechaParcial');

        Route::get('listTotalestudiante/{a}/{b}/{c}', 'Docente\DocenteController@listTotalestudiante');
    });
    Route::group(['prefix' => 'datos'], function ($router) {

        Route::get('datosdocente/{id}', 'Docente\AsistenciaController@datosDocente');

        Route::get('docenten/{id}', 'Docente\AsistenciaController@docentenotas');
    });

    Route::group(['prefix' => 'asistencia'], function ($router) {

        Route::get('generar/{a}/{b}/{c}/{d}/{e}/{f}/{g}', 'Docente\AsistenciaController@generarAsistencia');

        Route::post('generareq', 'Docente\AsistenciaController@generarAsistenciaEQ');

        Route::post('equivalente', 'Docente\AsistenciaController@equivalente');

        Route::post('asistencias', 'Docente\AsistenciaController@listAsistencias');

        Route::post('editequiv', 'Docente\AsistenciaController@editEstudianteAsistenciaEq');

        Route::post('enviar', 'Docente\AsistenciaController@enviarAsistencia');

        Route::get('estudiante/{a}/{b}/{c}/{d}/{e}/{f}/{g}/{h}', 'Docente\AsistenciaController@editEstudianteAsistencia');
        Route::post('DeleteLista/', 'Docente\AsistenciaController@DeleteEstudianteAsistencia');

        Route::post('update', 'Docente\AsistenciaController@updEstudianteAsistencia');

        Route::get('porcentaje/{a}/{b}', 'Docente\NotasController@porcentajeAsistencia');

        Route::get('resumen/{a}/{b}/{c}/{d}/{e}/{f}/{g}', 'Docente\AsistenciaController@resumenAsistencia');

        Route::get('general/{a}/{b}', 'Docente\AsistenciaController@asistencialistadogeneral');
    });

    Route::group(['prefix' => 'notas'], function ($router) {

        Route::get('unidades', 'Docente\AsistenciaController@unidadesDocente');
        Route::post('unidadesdel', 'Docente\NotasController@notasUnidadEliminar');

        Route::get('unidadlist/{a}/{b}/{c}/{d}/{e}/{f}/{g}/{h}', 'Docente\NotasController@notasListadoUnidad');

        Route::get('listsusti/{a}/{b}/{c}/{d}/{e}/{f}/{g}', 'Docente\NotasController@notasListadosusti');

        Route::get('listfinal/{a}/{b}/{c}/{d}/{e}/{f}/{g}', 'Docente\NotasController@notasListadoPromedioFinal');

        Route::get('btnactiva/{a}/{b}/{c}/{d}/{e}/{f}/{g}', 'Docente\NotasController@activaBtnPromedio');

        Route::post('fecha', 'Docente\NotasController@updateActualizaFechaExamen');

        Route::post('generasusti', 'Docente\NotasController@notasGeneraListsusti');

        Route::post('notasusti', 'Docente\NotasController@notasInsertSusti');
        Route::post('cerrarsusti', 'Docente\NotasController@notasCerrarSusti');
        Route::post('cerrarcurso', 'Docente\NotasController@notasCerrarCurso');


        Route::get('final/{a}/{b}/{c}/{d}/{e}/{f}/{g}', 'Docente\NotasController@finalPromedio');

        Route::post('insnotas', 'Docente\NotasController@Guardar_NotaSustitutoria');

        Route::any('vernotasusti', 'Docente\NotasController@ver_NotaSusti_Pro');
    });

    Route::group(['prefix' => 'encuesta'], function ($router) {

        Route::get('ConsultarEncuesta', 'Docente\EncuestaDocenteController@ConsultarEncuesta');
        Route::get('VerificarEncuesta/{a}/{b}', 'Docente\EncuestaDocenteController@VerificarEncuesta');
        Route::get('EncuestaFinalizado/{a}/{b}', 'Docente\EncuestaDocenteController@EncuestaFinalizado');
        Route::get('EncuestaBuscar/{a}/{b}', 'Docente\EncuestaDocenteController@EncuestaBuscar');
        Route::post('GuardarEncuesta', 'Docente\EncuestaDocenteController@GuardarEncuesta');
        Route::post('RestaurarEncuesta', 'Docente\EncuestaDocenteController@RestaurarEncuesta');
    });

    Route::group(['prefix' => 'programar'], function ($router) {

        Route::any('reunion', 'Docente\ReunionController@reunion');

        Route::post('create', 'Docente\NotificationController@createuser');

        Route::get('listusers', 'Docente\NotificationController@listUsers');

        Route::post('prgreunion', 'Docente\NotificationController@prgreunion');

        Route::get('listmeeting', 'Docente\NotificationController@listmeetings');
        Route::delete(
            'delmeeting/{id?}',
            'Docente\NotificationController@deletemeeting'
        );
        Route::get('getmeeting/{id?}', 'Docente\NotificationController@getmeeting');
    });

    Route::post('importExcel', 'Docente\NotasController@importListEstudiates');
});


Route::group(['middleware' => 'auth:api', 'prefix' => 'docente'], function ($router) {
    Route::group(['prefix' => 'control'], function ($router) {
        Route::post('notas', 'Docente\NotasController@insertarNotasUnidad');

        Route::get('conteo/{a}/{b}/{c}/{d}/{e}/{f}/{g}', 'Docente\NotasController@conteoEstudianteARunidad');

        Route::post('listedit', 'Docente\NotasController@listNotasEstudianteEdit');

        Route::post('editnotas', 'Docente\NotasController@updateNotasEstudiante');

        Route::post('cerrarnotas', 'Docente\NotasController@notasCerrarIngreso');

        Route::get('evaluacion/{a}/{b}/{c}/{d}/{e}/{f}/{g}', 'Docente\NotasController@registroEvaluacionEstudiantes');



        Route::get('unidad/{a}/{b}/{c}/{d}/{e}/{f}/{g}', 'Docente\NotasController@muestraUnidadesXCursoConFecha');


        Route::get('push/{a}/{b}/{c}', 'Docente\NotificationController@sendPushNotification');
    });

    Route::group(['prefix' => 'control'], function ($router) {
    });

    Route::group(['prefix' => 'racionalizacion'], function ($router) {
        Route::post('guardarRacionalizaciones/', 'Docente\RacionalizacionDocenteController@guardarRacionalizaciones');
        Route::post('guardarCargaNoLectiva/', 'Docente\RacionalizacionDocenteController@guardarCargaNoLectiva');
        Route::post('guardarCargaLectiva/', 'Docente\RacionalizacionDocenteController@guardarCargaLectiva');
        Route::get('ListAsistenciaTotal/{a}/{b}/{c}/{d}/{e}/{f}/{g}', 'Docente\RacionalizacionDocenteController@ListAsistenciaTotal');
        Route::get('CargaNoLectivaDocente/{a}/{b}', 'Docente\RacionalizacionDocenteController@CargaNoLectivaDocente');
        Route::get('AulasxCarrera/{a}/{b}', 'Docente\RacionalizacionDocenteController@AulasxCarrera');
        //        Route::get('OficinasxFilial/{a}', 'Docente\RacionalizacionDocenteController@OficinasxFilial');
        Route::post('insertarCargaLectivaNoLectiva/', 'Docente\RacionalizacionDocenteController@insertarCargaLectivaNoLectiva');
        Route::post('guardarHorarioCargaNoLectiva/', 'Docente\RacionalizacionDocenteController@guardarHorarioCargaNoLectiva');
    });
});

Route::group(['prefix' => 'silaboPdf'], function ($router) {

    Route::get('descargaSilaboPdf/{a}', 'Docente\SilaboDocController@descargaSilaboPdf');
    // Route::get('descargaSilaboDocentePdf/{a}/{b}/{c}/{d}', 'Docente\SilaboDocController@descargaSilaboDocentePdf');
});

Route::group(['prefix' => 'docente/silabo/descargas'], function ($router) {

    Route::get('descargaSilaboDocentePdf/{a}/{b}/{c}/{d}', 'Docente\SilaboDocController@descargaSilaboDocentePdf');
    //Route::get('LinkCapacitacion', 'Docente\SilaboDocController@LinkCapacitacion');

});

Route::middleware('jwt.auth')->get('auth/me', function (Request $request) {
    return auth()->user();
});
