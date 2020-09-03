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

Route::group(['prefix' => 'ccu', 'middleware' => 'auth:api'], function () {
    //General
    Route::get('obtenerSemestre', 'CCU\GeneralController@obtenerSemestresAcademicos');
    Route::get('obtenerSede', 'CCU\GeneralController@obtenerSedes');
    Route::post('obtenerHoras','CCU\GeneralController@obtenerHoras');
    //Route::get('obtenerHoras/{id}/{turno}','CCU\GeneralController@obtenerHoras');
    Route::get('obtenerDias','CCU\GeneralController@obtenerDias');
    Route::post('crearHorario','CCU\GeneralController@crearHorario');
    //Programa
    Route::post('crearPrograma','CCU\ProgramaController@crearPrograma');
    Route::get('obtenerPrograma', 'CCU\ProgramaController@obtenerProgramas');

    Route::get('obtenerProgramaId/{id}', 'CCU\ProgramaController@obtenerProgramaId');
    Route::get('obtenerDiaId/{id}','CCU\GeneralController@obtenerDiaId');
    Route::get('obtenerSedeId/{id}','CCU\GeneralController@obtenerSedeId');
    Route::post('eliminarHorario','CCU\GeneralController@eliminarHorario');
    Route::post('mostrarPrograma','CCU\ProgramaController@mostrarPrograma');
    Route::get('obtenerTipDoc','CCU\GeneralController@obtenerTipDoc');
    Route::post('crearResponsable','CCU\GeneralController@crearResponsable');
    Route::get('obtenerTipDocId/{id}','CCU\GeneralController@obtenerTipDocId');
    Route::get('buscarPersona/{text}','CCU\GeneralController@buscarPersona');
    Route::post('crearCargasHorarias','CCU\GeneralController@crearCargasHorarias');
    Route::get('obtenerPersonaDNI/{dni}','CCU\GeneralController@obtenerPersonaDNI');
    Route::post('eliminarEncargado','CCU\GeneralController@eliminarEncargado');
    Route::post('editarEncargado','CCU\GeneralController@editarEncargado');
    Route::post('buscarEstudiante','CCU\GeneralController@buscarEstudiante');
    Route::post('mostrarProgramaMat','CCU\GeneralController@mostrarProgramaMat');
    Route::post('mostrarHorario','CCU\GeneralController@mostrarHorario');
    Route::post('agregarEstudiantePrograma','CCU\ProgramaController@agregarEstudiantePrograma');
    Route::post('mostrarEstudiantePrograma','CCU\ProgramaController@mostrarEstudiantePrograma');
    Route::post('eliminarEstudiantePrograma','CCU\ProgramaController@eliminarEstudiantePrograma');
    Route::get('obtenerCarrera','CCU\GeneralController@obtenerCarrera');
    Route::post('buscarEstudianteCarrera','CCU\GeneralController@buscarEstudianteCarrera');
    Route::post('crearFolio','CCU\ProgramaController@crearFolio');
    Route::post('agregarEstudianteFolio','CCU\GeneralController@agregarEstudianteFolio');
    Route::post('mostrarFolio','CCU\GeneralController@mostrarFolio');
    Route::get('mostrarEstudianteFolio/{idFolio}','CCU\GeneralController@mostrarEstudianteFolio');
    Route::get('obtenerEstadoFolio/{idFolio}','CCU\GeneralController@obtenerEstadoFolio');
    Route::post('actualizarFolio','CCU\GeneralController@actualizarFolio');
    Route::get('verificarEstadoFolio','CCU\GeneralController@verificarEstadoFolio');
    Route::post('guardarCambiosFolio','CCU\GeneralController@guardarCambiosFolio');
    Route::post('eliminarEstudianteFolio','CCU\GeneralController@eliminarEstudianteFolio');
    Route::post('editarHorasPS','CCU\ProgramaController@editarHorasPS');
    Route::post('cambiarEstadoFolio','CCU\GeneralController@cambiarEstadoFolio');
    Route::post('buscarFolio','CCU\GeneralController@buscarFolio');
    Route::post('cambiarEstadoEstPrograma','CCU\ProgramaController@cambiarEstadoEstPrograma');
    Route::post('obtenerFechaFinMatricula','CCU\GeneralController@obtenerFechaFinMatricula');
    Route::get('obtenerCarrerasFiliales/{id}','CCU\GeneralController@obtenerCarrerasFiliales');
    Route::post('crearPlanTrabajo','CCU\PlanTrabajoController@crearPlanTrabajo');
    Route::post('ingresarLugar','CCU\GeneralController@ingresarLugar');
    Route::get('buscarLugar/{valor}','CCU\GeneralController@buscarLugar');
    Route::get('mostrarLugar/{idProyecto}','CCU\GeneralController@mostrarLugar');
    Route::post('eliminarLugar','CCU\GeneralController@eliminarLugar');
    Route::post('buscarDocente','CCU\PlanTrabajoController@buscarDocente');
    Route::get('obtenerDocenteResponsable/{idProyecto}','CCU\PlanTrabajoController@obtenerDocenteResponsable');
    Route::post('eliminarDocenteResponsable','CCU\PlanTrabajoController@eliminarDocenteResponsable');
    Route::post('crearDocenteSecundario','CCU\PlanTrabajoController@crearDocenteSecundario');
    Route::post('eliminarDocenteSecundario','CCU\PlanTrabajoController@eliminarDocenteSecundario');
    Route::get('obtenerDocenteSecundario/{idProyecto}','CCU\PlanTrabajoController@obtenerDocenteSecundario');
    Route::get('buscarEstudianteProyecto/{text}','CCU\PlanTrabajoController@buscarEstudianteProyecto');
    Route::post('obtenerProyectos','CCU\PlanTrabajoController@obtenerProyectos');
    Route::get('verificarEstadoProyecto','CCU\PlanTrabajoController@verificarEstadoProyecto');
    Route::post('actualizarProyecto','CCU\PlanTrabajoController@actualizarProyecto');
    Route::post('cambiarEstadoProyecto','CCU\PlanTrabajoController@cambiarEstadoProyecto');
    Route::post('obtenerProyectoId','CCU\PlanTrabajoController@obtenerProyectoId');
    Route::get('mostrarProyectos','CCU\PlanTrabajoController@mostrarProyectos');
    Route::get('obtenerUltimoRegistro/{id}','CCU\GeneralController@obtenerUltimoRegistro');
    Route::get('obtenerCursos','CCU\DeporteArteController@obtenerCursos');
    Route::post('editarHorario','CCU\GeneralController@editarHorario');
    Route::post('buscarEstudianteSemestreCarrera','CCU\DeporteArteController@buscarEstudianteSemestreCarrera');
    Route::post('ingresarEstudianteArteDeporte','CCU\DeporteArteController@ingresarEstudianteArteDeporte');
    Route::post('agregarSeccionEstudiante','CCU\DeporteArteController@agregarSeccionEstudiante');
    Route::post('obtenerSeguimientoProyecto','CCU\PlanTrabajoController@obtenerSeguimientoProyecto');
    Route::post('obtenerAsistenciaSesion','CCU\GeneralController@obtenerAsistenciaSesion');
    Route::post('actualizarEstadoDocenteSec','CCU\PlanTrabajoController@actualizarEstadoDocenteSec');
    Route::post('actualizarHorasEstudianteProy','CCU\PlanTrabajoController@actualizarHorasEstudianteProy');
    Route::post('actualizarEstadoInformeFinal','CCU\PlanTrabajoController@actualizarEstadoInformeFinal');
    Route::post('obtenerCursosDocente','CCU\TecnicoEspecialistaController@obtenerCursosDocente');
    Route::post('crearAsistenciaCurso','CCU\TecnicoEspecialistaController@crearAsistenciaCurso');
    Route::get('obtenerAsistenciaCurso/{id}','CCU\TecnicoEspecialistaController@obtenerAsistenciaCurso');
    Route::post('ingresarAsistenciaEstudiante','CCU\TecnicoEspecialistaController@ingresarAsistenciaEstudiante');
    Route::get('obtenerAsistenciaEstudiante/{id}','CCU\TecnicoEspecialistaController@obtenerAsistenciaEstudiante');
    Route::post('actualizarEstadoAsistenciaEst','CCU\TecnicoEspecialistaController@actualizarEstadoAsistenciaEst');
    Route::post('actualizarEstadoAsistencia','CCU\TecnicoEspecialistaController@actualizarEstadoAsistencia');
    Route::get('mostrarHistorialAsistencia/{id}','CCU\TecnicoEspecialistaController@mostrarHistorialAsistencia');
    Route::get('mostrarResumenAsistencia/{id}','CCU\TecnicoEspecialistaController@mostrarResumenAsistencia');
    Route::get('obtenerAsistenciaResumenIF/{id}','CCU\PlanTrabajoController@obtenerAsistenciaResumenIF');
    Route::post('ingresarUnidadNotas','CCU\TecnicoEspecialistaController@ingresarUnidadNotas');
    Route::get('obtenerUnidadNotas/{id}','CCU\TecnicoEspecialistaController@obtenerUnidadNotas');
    Route::post('ingresarUnidadCurso','CCU\TecnicoEspecialistaController@ingresarUnidadCurso');
    Route::get('obtenerUnidadCurso/{id}','CCU\TecnicoEspecialistaController@obtenerUnidadCurso');
    Route::post('editarNotasUnidad','CCU\TecnicoEspecialistaController@editarNotasUnidad');
    Route::get('obtenerCicloActual','CCU\GeneralController@obtenerCicloActual');
    Route::post('cambiarEstadoUnidad','CCU\TecnicoEspecialistaController@cambiarEstadoUnidad');
    Route::get('obtenerPromedioFinal/{id}','CCU\TecnicoEspecialistaController@obtenerPromedioFinal');
    Route::post('obtenerHorarioCurso','CCU\GeneralController@obtenerHorarioCurso');
    Route::get('verificarEstadoUnidad/{id}','CCU\TecnicoEspecialistaController@verificarEstadoUnidad');
    Route::get('obtenerHorasEstudiante/{codigo}','CCU\GeneralController@obtenerHorasEstudiante');
    Route::post('obtenerSeguimientoDocente','CCU\PlanTrabajoController@obtenerSeguimientoDocente');
    Route::post('ingresarProyectoSeguimiento','CCU\PlanTrabajoController@ingresarProyectoSeguimiento');
    Route::post('editarProyectoSeguimiento','CCU\PlanTrabajoController@editarProyectoSeguimiento');
    Route::post('ingresarProyectoAsistencia','CCU\PlanTrabajoController@ingresarProyectoAsistencia');
    Route::post('editarProyectoAsistencia','CCU\PlanTrabajoController@editarProyectoAsistencia');
    Route::post('editarProyectoAsistenciaEst','CCU\PlanTrabajoController@editarProyectoAsistenciaEst');
    Route::post('ingresarImagenSeguimiento','CCU\PlanTrabajoController@ingresarImagenSeguimiento');
    Route::post('obtenerImagenSeguimiento','CCU\PlanTrabajoController@obtenerImagenSeguimiento');
    Route::post('obtenerHorasEstudianteDetalle','CCU\GeneralController@obtenerHorasEstudianteDetalle');
    Route::post('obtenerNotasEstudianteDetalle','CCU\GeneralController@obtenerNotasEstudianteDetalle');
    Route::post('buscarEstudianteFilial','CCU\GeneralController@buscarEstudianteFilial');
    Route::post('obtenerAsistenciaEstudiante','CCU\GeneralController@obtenerAsistenciaEstudiante');
    Route::post('cerrarPrograma','CCU\ProgramaController@cerrarPrograma');
    Route::post('obtenerProyectoEstudiante','CCU\PlanTrabajoController@obtenerProyectoEstudiante');
    Route::post('editarDescripcionDetSeguimiento','CCU\PlanTrabajoController@editarDescripcionDetSeguimiento');
    Route::post('eliminarProyectoSeguimiento','CCU\PlanTrabajoController@eliminarProyectoSeguimiento');
    Route::post('obtenerCursoEstudiante','CCU\GeneralController@obtenerCursoEstudiante');
    Route::post('obtenerCursoCarreraEstudiante','CCU\GeneralController@obtenerCursoCarreraEstudiante');
    Route::post('mostrarReporteAsistencia','CCU\GeneralController@mostrarReporteAsistencia');
    Route::post('obtenerDocenteCurso','CCU\GeneralController@obtenerDocenteCurso');
    Route::post('mostrarReporteNota','CCU\GeneralController@mostrarReporteNota');
    Route::get('imprimirAsistenciaCurso/{a}/{b}', 'CCU\GeneralController@imprimirAsistenciaCurso');
    Route::post('consultarHorasProyectoEstudiante','CCU\GeneralController@consultarHorasProyectoEstudiante');
    Route::post('cambiarEstadoSeguimiento','CCU\PlanTrabajoController@cambiarEstadoSeguimiento');

    Route::group(['prefix' => 'silabo', 'middleware' => 'auth:api'], function () {
        Route::post('savSilabo','CCU\TecnicoEspecialistaController@savSilabo');
        Route::post('savObjetivoEspecifico','CCU\TecnicoEspecialistaController@savObjetivoEspecifico');
        Route::post('savBibliografia','CCU\TecnicoEspecialistaController@savBibliografia');
        Route::post('savContenido','CCU\TecnicoEspecialistaController@savContenido');
        Route::post('savEvaluacion','CCU\TecnicoEspecialistaController@savEvaluacion');
        Route::post('savMetodologia','CCU\TecnicoEspecialistaController@savMetodologia');
        Route::post('savUnidad','CCU\TecnicoEspecialistaController@savUnidad');
        Route::post('obtenerSilabo','CCU\TecnicoEspecialistaController@obtenerSilabo');
        Route::get('obtenerObjetivoEspecifico/{id}','CCU\TecnicoEspecialistaController@obtenerObjetivoEspecifico');
        Route::post('updateSilabo','CCU\TecnicoEspecialistaController@updateSilabo');
        Route::post('updateObjetivoEspecifico','CCU\TecnicoEspecialistaController@updateObjetivoEspecifico');
        Route::post('deleteObjetivoEspecifico','CCU\TecnicoEspecialistaController@deleteObjetivoEspecifico');
        Route::get('obtenerUnidad/{id}','CCU\TecnicoEspecialistaController@obtenerUnidad');
        Route::get('obtenerContenido/{id}','CCU\TecnicoEspecialistaController@obtenerContenido');
        Route::post('deleteContenido','CCU\TecnicoEspecialistaController@deleteContenido');  
        Route::post('updateContenido','CCU\TecnicoEspecialistaController@updateContenido');
        Route::post('updateMetodologia','CCU\TecnicoEspecialistaController@updateMetodologia');
        Route::get('obtenerMetodologia/{id}','CCU\TecnicoEspecialistaController@obtenerMetodologia');
        Route::get('obtenerBibliografia/{id}','CCU\TecnicoEspecialistaController@obtenerBibliografia');
        Route::post('deleteBibliografia','CCU\TecnicoEspecialistaController@deleteBibliografia');
        Route::post('updateBibliografia','CCU\TecnicoEspecialistaController@updateBibliografia');
    });


});
