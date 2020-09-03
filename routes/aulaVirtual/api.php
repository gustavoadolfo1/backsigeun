<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'aulaVirtual', 'middleware' => 'auth:api'], function () {
    Route::get('obtenerCursos/{dni}', 'AulaVirtual\RecursosController@obtenerCursos');
    Route::get('obtenerDatosCurso/{hashedId}', 'AulaVirtual\RecursosController@obtenerDatosCurso');
    Route::get('obtenerTemasCurso/{hashedId}', 'AulaVirtual\RecursosController@obtenerTemasCurso');
    Route::post('obtenerTemasCursoEst', 'AulaVirtual\RecursosController@obtenerTemasCursoEst');
    Route::get('obtenerTiposRecurso', 'AulaVirtual\RecursosController@obtenerTiposRecurso');
    Route::get('obtenerEstudiantes/{hashedId}', 'AulaVirtual\RecursosController@obtenerEstudiantes');

    Route::get('obtenerEstudiantesTareas/{hashedId}/{act}', 'AulaVirtual\RecursosController@obtenerEstudiantesv2');

    Route::get('obtenerEstudiantesForo/{hashedId}/{act}', 'AulaVirtual\RecursosController@obtenerEstudiantesForo');
    

    Route::get('obtenerTareasEstudiante/{activId}/{iPersId}/{iDocenteId}', 'AulaVirtual\RecursosController@obtenerTareasEstudiante');

    Route::group(['prefix' => 'cursos', 'middleware' => 'auth:api'], function () {
        Route::get('obtenerAreasCurriculares', 'AulaVirtual\CursoController@obtenerAreasCurriculares');
    });


    Route::group(['prefix' => 'files', 'middleware' => 'auth:api'], function () {
        Route::post('obtenerArchivos', 'AulaVirtual\FilesController@getFilesUser');
        Route::post('subirArchivos', 'AulaVirtual\FilesController@saveFilesUser'); 
        Route::post('saveFilesUserExamen', 'AulaVirtual\FilesController@saveFilesUserExamen'); 
        Route::delete('deleteFile/{id}', 'AulaVirtual\FilesController@deleteFilesUser');

        Route::post('saveFolder', 'AulaVirtual\FilesController@saveFolder');
        
    });


    Route::group(['prefix' => 'actividades', 'middleware' => 'auth:api'], function () {
        Route::get('obtenerTiposActividad', 'AulaVirtual\ActividadController@obtenerTiposActividad');
        Route::get('obtenerActividadesTema', 'AulaVirtual\ActividadController@obtenerActividadesTema');
        Route::get('obtenerDetallesActividad/{actividadId}', 'AulaVirtual\ActividadController@obtenerDetallesActividad');
        Route::get('obtenerRecursosActividad/{actividadId}', 'AulaVirtual\ActividadController@obtenerRecursosActividad');
        Route::post('store', 'AulaVirtual\ActividadController@store');
        Route::delete('eliminarActividad/{actividadId}', 'AulaVirtual\ActividadController@destroy');
        Route::get('obtenerActividadesNotas/{hashedId}', 'AulaVirtual\ActividadController@obtenerActividadesNotas');
        Route::post('updateActividadSimple', 'AulaVirtual\ActividadController@updateActividadSimple');
        Route::post('updateActividadSimple2', 'AulaVirtual\ActividadController@updateActividadSimple2');
        Route::post('updateActividadSimpleReprog','AulaVirtual\ActividadController@updateActividadSimpleReprog');
        Route::get('matrisNotas/{hashedId}/{iPersIdEstudiante?}', 'AulaVirtual\RecursosController@matrisNotas');

        Route::get('rutaConferencia', 'AulaVirtual\RecursosController@rutaConferencia');
        Route::post('ActividadeVideoConferencias', 'AulaVirtual\ActividadController@ActividadeVideoConferencias');
    });


    Route::group(['prefix' => 'tareas', 'middleware' => 'auth:api'], function () {
        Route::post('responderActividad', 'AulaVirtual\RespuestaActividadController@responderActividad');
        Route::post('actualizarEstadoActividadRespuesta', 'AulaVirtual\RespuestaActividadController@actualizarEstadoActividadRespuesta');
        Route::get('getTareasActividad/{idActiv}/{iPers}', 'AulaVirtual\RespuestaActividadController@getTareasActividad');
        Route::delete('deleteFile/{id}', 'AulaVirtual\RespuestaActividadController@deleteFile');
        Route::post('saveNota', 'AulaVirtual\RespuestaActividadController@saveNota');
        Route::get('cerrarTarea/{idActiv}', 'AulaVirtual\ActividadController@cerrarTarea');
        Route::post('abrirTareaEstudiante', 'AulaVirtual\ActividadController@openTareaNota');

        Route::post('crearGrupo', 'AulaVirtual\ActividadController@crearGrupo');
        Route::get('getGrupos/{id}', 'AulaVirtual\ActividadController@getGrupos');
        Route::get('getEstudiantesGrupo/{idActividad}','AulaVirtual\ActividadController@getEstudiantesGrupo');
        Route::post('guardarIntegrantes', 'AulaVirtual\ActividadController@guardarIntegrantes');
        Route::delete('eliminarGrupo/{actividadGrupoId}', 'AulaVirtual\ActividadController@eliminarGrupo');
        Route::delete('eliminarIntegrante/{actGrupoPersId}', 'AulaVirtual\ActividadController@eliminarIntegrante');

        Route::get('getTareaGrupal/{grupoId}', 'AulaVirtual\RespuestaActividadController@getTareaGrupal');
        Route::post('guardarNotaGrupal', 'AulaVirtual\RespuestaActividadController@guardarNotaGrupal');
    });


    Route::group(['prefix' => 'comentarios', 'middleware' => 'auth:api'], function () {
        Route::get('ver/{id}', 'AulaVirtual\ComentarioController@verComentarios');
        Route::post('insertar', 'AulaVirtual\ComentarioController@insertar');
        Route::post('responderComentario', 'AulaVirtual\ComentarioController@responderComentario');
        Route::post('insertarComentarioPrivado', 'AulaVirtual\ComentarioController@insertarComentarioPrivado');
        Route::post('responderComentarioPrivado', 'AulaVirtual\ComentarioController@responderComentarioPrivado');
        Route::post('editarComentario','AulaVirtual\ComentarioController@editarComentario');
        Route::delete('eliminarComentario/{id}', 'AulaVirtual\ComentarioController@eliminarComentario');
        
    });
    Route::group(['prefix' => 'examenes', 'middleware' => 'auth:api'], function () {
        Route::get('getEvaluciones/{idActividad}', 'AulaVirtual\ActividadController@getEvaluciones');
        Route::get('getEvalucionesOne/{idActividad}', 'AulaVirtual\ActividadController@getEvalucionesOne');
        Route::get('getEval/{idActividad}', 'AulaVirtual\ActividadController@getEval');
        Route::get('listPreguntas', 'AulaVirtual\ActividadController@verTiposPreguntas');
        Route::get('listPreguntasEval/{idAct}', 'AulaVirtual\ActividadController@verPreguntasEval');
        Route::post('savePreguntasEval', 'AulaVirtual\ActividadController@insertPreguntasEval');
        Route::delete('deletePreguntas/{idPre}', 'AulaVirtual\ActividadController@deletePreguntas');
        Route::post('getExamen', 'AulaVirtual\ActividadController@getExamen');
        Route::post('saveNotaPregunta', 'AulaVirtual\ActividadController@saveNotaPregunta');
        Route::post('updatePreguntaExamen','AulaVirtual\RespuestaActividadController@updatePreguntaExamen');
        Route::get('respuestaCorrecta/{iEvalDetId}/{iEvalDetAltId}/{iPreg}/{val}','AulaVirtual\RespuestaActividadController@respuestaCorrecta');
        Route::post('saveNota', 'AulaVirtual\ActividadController@saveNota');
        Route::post('addAlternativa', 'AulaVirtual\ActividadController@addAlternativa');
        Route::delete('eliminarAlternativa/{id}', 'AulaVirtual\ActividadController@eliminarAlternativa');
        /* Para Estudiantes*/
        Route::post('getExamenEstudiante', 'AulaVirtual\RespuestaActividadController@getExamenEstudiante');
        Route::post('inicarExamen', 'AulaVirtual\RespuestaActividadController@inicarExamen');
        Route::get('PreguntasEstudiantes/{idAct}', 'AulaVirtual\RespuestaActividadController@PreguntasEstudiantes');
        Route::post('getPreguntaExamen', 'AulaVirtual\RespuestaActividadController@getPreguntaExamen');
        Route::post('saveRespuestaExamen','AulaVirtual\RespuestaActividadController@saveRespuestaExamen');
        
        Route::get('obtenerEstudiantesExamen/{hashedId}/{act}', 'AulaVirtual\RecursosController@obtenerEstudiantesExamen');
    });
    Route::group(['prefix' => 'foros', 'middleware' => 'auth:api'], function () {
        Route::get('info/{idActividad}', 'AulaVirtual\ActividadController@getForo');
        Route::get('tema/{idTema}', 'AulaVirtual\ActividadController@getTemaForo');
        Route::post('guardarTemaForo', 'AulaVirtual\ActividadController@guardarTemaForo');
        Route::post('comentariosEstudiante', 'AulaVirtual\RespuestaActividadController@comentariosEstudiante');
        Route::post('saveNotaForo','AulaVirtual\ActividadController@saveNotaForo');
        Route::delete('eliminarTema/{id}','AulaVirtual\ActividadController@eliminarTema');
        
    });
    
    Route::group(['prefix' => 'glosario', 'middleware' => 'auth:api'], function () {
        Route::get('terminos/{idActividad}', 'AulaVirtual\GlosarioController@getGlosario');
        Route::post('savUpdGlosario', 'AulaVirtual\GlosarioController@saveGlosario');
        Route::delete('deleteGlosario/{id}', 'AulaVirtual\GlosarioController@deleteGlosario');
    });
    Route::group(['prefix' => 'calendario', 'middleware' => 'auth:api'], function () {
        Route::post('actividades', 'AulaVirtual\ActividadController@getCalendario');
    });

    Route::group(['prefix' => 'exports', 'middleware' => 'auth:api'], function () {
        Route::post('calificaciones', 'AulaVirtual\ExportController@exportCalificaciones');
        });

    Route::get('getTiposEvaluacion', 'AulaVirtual\ActividadController@getTiposEvaluacion');
    Route::post('addRecurso','AulaVirtual\RecursosController@addRecurso');
    Route::delete('deleteRecurso/{idRecurso}','AulaVirtual\RecursosController@deleteRecurso');
    Route::get('getHoraHorario/{iDocenteId}/{cCursoCod}/{iSeccionId}/{iNroSemana}/{iCurricCursoId}', 'AulaVirtual\ActividadController@getHoraHorario');
    Route::get('getObtenerReunionLink/{iReunionProgId}', 'AulaVirtual\ActividadController@getObtenerReunionLink');
    Route::post('guardarAsistencia','AulaVirtual\ActividadController@guardarAsistencia');

    Route::group(['prefix' => 'rubrica', 'middleware' => 'auth:api'], function () {
        Route::post('store', 'AulaVirtual\RubricaController@store');
        Route::get('getRubricaActividad/{actividadId}', 'AulaVirtual\RubricaController@getRubricaActividad');
        Route::post('guardarRespNivelEstudiante', 'AulaVirtual\RubricaController@guardarRespNivelEstudiante');
        Route::get('getEvalRubricaEstudiante/{actividadId}/{personaId}', 'AulaVirtual\RubricaController@getEvalRubricaEstudiante');

        Route::get('getRubricasDocente/{docenteId}/{cicloAcad}/{cursoId}', 'AulaVirtual\RubricaController@getRubricasDocente');

        Route::get('clonarRubrica/{nuevaActividadId}/{actividadIdRubrica}', 'AulaVirtual\RubricaController@clonarRubrica');

        Route::post('guardarNivelGrupalRubrica', 'AulaVirtual\RubricaController@guardarNivelGrupalRubrica');
        
    });

    Route::group(['prefix' => 'monitoreo', 'middleware' => 'auth:api'], function () {
        Route::get('getListadoCursosMonitoreo/{carrFilId}/{curricId}', 'AulaVirtual\MonitoreoController@getListadoCursosMonitoreo');
    });

    Route::group(['prefix' => 'reportes', 'middleware' => 'auth:api'], function () {
        Route::get('getAsistentesReunion/{iReunionProgId}/{cTipoReturn}/{cTipoArchivo?}', 'AulaVirtual\ReporteController@getAsistentesReunion');
    });
});

Route::get('aulaVirtual/obtenerComentarios/{actividadId}', 'AulaVirtual\ActividadController@obtenerComentarios');
Route::get('aulaVirtual/AsistenciaVideoconferenciaExcel/{a}/{b}/{c}/{d}', 'AulaVirtual\ExportController@AsistenciaVideoconferenciaExcel');
    



