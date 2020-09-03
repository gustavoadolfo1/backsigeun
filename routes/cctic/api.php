<?php

use Illuminate\Http\Request;

Route::group(['prefix' => 'cctic'], function () {
    Route::get('testModel', 'cctic\GestionCursosController@testModel');
    Route::get('pide/{dni}', 'CCTIC\GeneralController@pide');
    Route::get('test/pdf', 'cctic\GeneralController@test');
    Route::post('testImage', 'cctic\GestionCursosController@testImage');

    Route::POST('checkDNI', 'CCTIC\GeneralController@checkDNI');
    Route::get('tiposConceptos', 'CCTIC\GeneralController@tiposConceptos');
    Route::get('listaConceptos/{tipoConcepto}', 'CCTIC\GeneralController@listaConceptos');
    //obtener grados academicos
    Route::get('gradosAcademicos', 'CCTIC\GradosAcademicosController@index');
    Route::get('tiposDedicacion', 'CCTIC\DocentesController@getTiposDedicacion');
    Route::get('modalidadesEstudio', 'CCTIC\PublicacionesController@obtenerModalidadesEstudio');

    //obtener publico objetivo
    Route::get('obtenerPublicoObjetivo', 'CCTIC\PublicoObjetivoController@index');
    Route::get('obtenerCarrerasProfesionales', 'CCTIC\PublicoObjetivoController@professionalCareers');

    Route::group(['prefix' => 'publico-objetivo'], function () {
        Route::get('byPublicacionId/{id}', 'CCTIC\PublicoObjetivoController@byPublicacionId');
    });


    Route::get('obtenerTurnos', 'cctic\GeneralController@obtenerTurnos');

    Route::post('sendEmailPreinscrito', 'cctic\emailController@sendEmailPreinscrito');
    Route::POST('sendEmailGrupo/{grupoID}', 'cctic\emailController@sendEmailGrupo');

    // Route::post('preinscripcion/registro', 'CCTIC\PreInscripcionController@RegistrarPreInscripcion');
    // Route::get('preinscripcion/recursos', 'cctic\PreInscripcionController@preInscripcionRecursos');
    // Route::get('preinscripcion/tipo-persona/{dni}', 'cctic\PreInscripcionController@tipoPersona');
    // Route::post('test', 'cctic\PreInscripcionController@validPreinscripcion');
    // Route::get('preinscripcion/dni/{dni}', 'cctic\PreInscripcionController@byDNI');
    Route::get('obtenerFilialesValidByCarreraID/{carreraID}', 'CCTIC\GeneralController@obtenerFilialesValidByCarreraID');

    Route::get('obtenerFiliales', 'CCTIC\GeneralController@obtenerFiliales');
    Route::get('obtenerCarrerasXprogramAcadFilial/{programAacd}/{filial}', 'CCTIC\GeneralController@obtenerCarrerasXprogramAcadFilial');

    Route::group(['prefix' => 'carreras'], function () {
        Route::get('obtenerCareras/{proAcadId}', 'cctic\CarrerasController@obtenerCarerras');
        Route::get('obtenerCarrerasXprogAcadFilialActiva/{proAcadId}', 'cctic\CarrerasController@obtenerCarrerasXprogAcadFilialActiva');
        Route::get('obtenerCarreraByID/{carreaId}', 'cctic\CarrerasController@ObtenerCarreraByID');
        Route::get('obtenerCarrerasModulos', 'CCTIC\CarrerasController@obtenerCarrerasModulos');
    });


    Route::group(['prefix' => 'programas-academicos'], function () {
        Route::get('obtener', 'CCTIC\ProgramasAcademicosController@index');
    });


    Route::group(['prefix' => 'preHorario'], function () {
        Route::get('preHorariosXFilialidCarreraid', 'cctic\HorarioController@preHorariosXFilialidCarreraid');
    });

    //        test
    Route::group(['prefix' => 'preinscripcion-linea'], function () {
        Route::put('actualizar', 'CCTIC\PreInscripcionController@actualizarPreinscripcion');
        Route::get('obtenerPreinscripcionesPorDNI', 'CCTIC\PreInscripcionController@obtenerPreinscripcionesPorDNI');
        Route::get('buscarPersonaNumDocumento', 'CCTIC\PersonasController@buscarPersonaNumDocumento');
        Route::post('registrarPersonaYPreinscribir', 'CCTIC\PreInscripcionController@registrarPreInscripcion');
    });

    Route::group(['prefix' => 'publicaciones'], function () {
        Route::get('obtenerPublicaciones', 'CCTIC\PublicacionesController@obtenerPublicaciones');
        Route::get('obtenerPublicacionById/{id}', 'CCTIC\PublicacionesController@obtenerPublicacionById');
        Route::get('obtenerGrupoHorariosByPublicacionId/{id}', 'CCTIC\PublicacionesController@obtenerGrupoHorariosByPublicacionId');
        Route::get('obtenerTiposIdentificaciones', 'CCTIC\GeneralController@getIdentificacionesTipos');
        Route::get('obtenerNacionalidades', 'CCTIC\GeneralController@getNacionalidades');
        // Route::get('obtenerNacionalidades', 'cctic\GeneralController@getNacionalidades');
    });

//    Route::get('listaPreinscrtiosPDF', 'cctic\PreInscripcionController@listaPreinscrtiosPDF');
        Route::get('cronograma', 'CCTIC\GrupoDetalleController@cronogramaPDF');
        Route::get('obtenerCronograma/{id}', 'CCTIC\GruposController@obtenerCronograma');
});

Route::group(['prefix' => 'cctic', 'middleware' => 'auth:api'], function ($router) {
    Route::group(['prefix' => 'preinscripcion'], function () {

        Route::get('lista', 'cctic\PreInscripcionController@obtenerPreInscripciones');
        Route::get('preInscripcion/{id}', 'cctic\PreInscripcionController@obtenerPreInscripcion');
        Route::post('validacion', 'cctic\PreInscripcionController@validarPreInscripcion');
        Route::get('obtenerPreinscritos', 'cctic\PreInscripcionController@obtenerPreinscritos');
        Route::get('obtenerPreinscripByModulo', 'cctic\PreInscripcionController@preInscripcionesByModulo');
        Route::get('getCantidadPreinsModulo', 'CCTIC\PreInscripcionController@getCantidadPreinsModulo');
        Route::get('byPersona', 'CCTIC\PreInscripcionController@byPersona');
        Route::POST('listaPreinscrtiosPDF', 'CCTIC\PreInscripcionController@listaPreinscrtiosPDF');
        Route::delete('eliminarPreinscripcionByID/{id}', 'CCTIC\PreInscripcionController@eliminarPreinscripcionByID');

        Route::group(['prefix' => 'adeudo'], function () {
            Route::get('obtenerCantidadPreinsAdeudo', 'CCTIC\PreInscripcionController@obtenerCantidadPreinsAdeudo');
            Route::get('obtenerPreinscritosAdeudo', 'CCTIC\PreInscripcionController@obtenerPreinscritosAdeudos');
        });
    });

    Route::group(['prefix' => 'certificados'], function ($router) {
        Route::get('obtenerCertificados', 'CCTIC\CertificadosController@obtenerCertificadosGrupos');
        Route::get('validarDatosCertificadoGrupo', 'CCTIC\CertificadosController@validarDatosCertificadoGrupo');
        Route::get('validarDatosCertificadoSuficiencia', 'CCTIC\CertificadosController@validarDatosCertificadoSuficiencia');
    });

    //    Matricula
    Route::group(['prefix' => 'matriculas'], function () {
        Route::get('obtenerCarrerasxProgramaAcad/{progAcad}', 'CCTIC\Matricula@obtenerCarrerasxProgramaAcad');
        Route::get('obtenerPlanCarreras/{id}', 'CCTIC\Matricula@planByCarreraID');
        Route::post('generarPredeuda', 'CCTIC\Matricula@MatricularPreinscritos');
    });

    //  Horario
    Route::group(['prefix' => 'horarios'], function () {
        Route::get('ObtenerPlanXCarrera/{carreraID}', 'cctic\HorarioController@obtenerPlanCarrera');
        Route::get('obtenerCursosXModuloPlan', 'cctic\HorarioController@cursosXModuloPlan');
        Route::get('obtenerSeccionCurso', 'cctic\HorarioController@obtenerSeccionCurso');
        Route::get('obtenerAulasDisponibles', 'cctic\HorarioController@obtenerAulasDisponibles');
        Route::post('guardarHorario', 'cctic\HorarioController@guardarHorario');
        Route::get('obtenerHorarioXCarreraidFilialidCurricidGrupoid', 'cctic\HorarioController@obtenerHorarioXCarreraidFilialidCurricidGrupoid');
        Route::delete('eliminarHorarioBloqueByID/{horarioid}', 'cctic\HorarioController@eliminarHorarioBloqueByID');
        Route::put('actualizarHorarioXhorarioID/{horarioid}', 'cctic\HorarioController@actualizarHorarioXhorarioID');

        Route::post('eliminarHorarioByPublicacion', 'cctic\HorarioController@eliminarHorarioByPublicacion');

        Route::delete('eliminarDetalleHorarioByHorarioID/{id}', 'CCTIC\HorarioController@eliminarDetalleHorarioByHorarioID');
    });

    Route::get('obtenerGruposXProgramAcad/{programAcad}',  'CCTIC\GeneralController@obtenerGruposXProgramAcad');

    Route::get('listaPersonasTipo', 'CCTIC\GeneralController@listaPersonasTipo');

    Route::group(['prefix' => 'docentes'], function () {
        Route::put('active', 'CCTIC\DocentesController@activarDocente');
        Route::get('lista', 'CCTIC\DocentesController@index');
        Route::post('create', 'CCTIC\DocentesController@store');
        Route::post('asignarFilialPrograma', 'CCTIC\DocentesController@asignarFilialPrograma');
        Route::post('obtenerFilialDocentexDocumento', 'CCTIC\DocentesController@obtenerFilialDocentexDocumento');
        Route::get('byDNI/{dni}', 'CCTIC\DocentesController@byDNI');
    });

    Route::group(['prefix' => 'gestion-cursos'], function () {

        Route::get('ObtenerCursosXFiliales', 'cctic\GestionCursosController@ObtenerCursosXFiliales');
        Route::get('obtenerPreinscritosByPublicacionHoario', 'CCTIC\PreInscripcionController@obtenerPreinscritosByPublicacionHoario');

        Route::group(['prefix' => 'preinscritos'], function () {
            Route::put('updateObservacionesPreinscrito', 'cctic\PreInscripcionController@updateObservacionesPreinscrito');
        });

        Route::get('obtenerPUblacicanesCantidadPreinscritos', 'cctic\GestionCursosController@obtenerPUblacicanesCantidadPreinscritos');

        Route::put('ActualizarEstadoPublicacion', 'cctic\GestionCursosController@ActualizarEstadoPublicacion');


        Route::group(['prefix' => 'cursos'], function () {
            Route::get('obtenerCursos', 'cctic\GestionCursosController@obtenerCursos');
            Route::get('cursoById/{id}', 'cctic\GestionCursosController@cursoById');
            Route::post('crearCurso', 'cctic\GestionCursosController@crearCurso');
            Route::get('recursosCrearCurso', 'cctic\GestionCursosController@recursosCrearCurso');
            Route::get('downloadSilabus', 'cctic\GestionCursosController@downloadSilabus');
            Route::get('recursosEditarCurso', 'cctic\GestionCursosController@recursosEditarCurso');
            Route::post('editarCurso', 'cctic\GestionCursosController@editarCurso');
            Route::POST('cambiarEstadoCurso', 'cctic\GestionCursosController@cambiarEstadoCurso');
            Route::post('EliminarCursosModulos', 'cctic\GestionCursosController@EliminarCursosModulos');
        });

        Route::group(['prefix' => 'publicaciones'], function () {
            Route::get('recursosCrearPublicacion/{iCursoId}', 'CCTIC\GestionCursosController@recursosCrearPublicacion');
            Route::post('crearPublicaicon', 'CCTIC\GestionCursosController@crearPublicaicon');
            Route::get('publicacionById/{id}', 'CCTIC\PublicacionesController@publicacionById');

            Route::get('obtenerFiltrosPreinscritos/{publicacionId}', 'CCTIC\PublicacionesController@obtenerFiltrosPreinscritos');

            Route::get('recursosEditarPublicacion/{publicacionId}', 'CCTIC\PublicacionesController@recursosEditarPublicacion');

            Route::post('editarPublicacion', 'CCTIC\PublicacionesController@editarPublicacion');
        });

        Route::group(['prefix' => 'plan-trabajo'], function () {
            Route::post('crear', 'CCTIC\GestionCursosController@crearPlanTrabajo');
            Route::get('recursosCrearPlanTrabajo', 'CCTIC\GestionCursosController@recursosCrearPlanTrabajo');
            Route::get('lista', 'CCTIC\GestionCursosController@listaPlanTrabajo');
            Route::get('obtenerUltimoPlan', 'CCTIC\PlanTrabajoController@obtenerUltimoPlan');
        });

        Route::get('obtenerCurriculasDisponibles', 'CCTIC\GeneralController@obtenerCurriculasDisponibles');

        Route::get('obtenerCurriculas', 'CCTIC\GeneralController@obtenerCurriculas');
    });

    Route::group(['prefix' => 'grupos'], function () {
        Route::post('generarGrupo', 'cctic\GruposController@generarGrupo');
        Route::put('cambioGrupo', 'cctic\GruposController@cambioGrupo');
        Route::get('obtenerGrupos', 'cctic\GruposController@obtenerGrupos');
        Route::get('getGrupoByid/{id}', 'cctic\GruposController@getGrupoByid');
        Route::post('cambiarEstadoGrupo', 'cctic\GruposController@cambiarEstadoGrupo');
        Route::put('cambiarFechaInicio', 'cctic\GruposController@cambiarFechaInicio');
        Route::post('generarAsistencias', 'cctic\GruposController@generarAsistencias');
        Route::get('byDocente', 'cctic\GruposController@byDocente');
        Route::get('infoCambiaGrupo', 'cctic\GruposController@infoCambiaGrupo');

        Route::put('cambiarFecha', 'CCTIC\GrupoDetalleController@cambiarFecha');
        Route::get('grupoDetalle', 'CCTIC\GrupoDetalleController@grupoDetalle');
        Route::post('obtenerGruposActivos', 'CCTIC\GruposController@obtenerGruposActivos');
        Route::post('obtenerInfoGrupo', 'CCTIC\GruposController@obtenerCursoDocenteGrupo');
        Route::post('generarNuevoGrupo', 'CCTIC\GruposController@generarNuevoGrupo');
        Route::get('numeroMensualidades', 'CCTIC\GruposController@numeroMensualidades');
        Route::get('cambiaGrupoEstatus', 'CCTIC\GruposController@cambiaGrupoEstatus');

    });

    Route::group(['prefix' => 'grupo-detalle'], function () {
        Route::get('byGrupoDetaleID/{grupoDetalleID}', 'CCTIC\GrupoDetalleController@byGrupoDetaleID');
        Route::put('cerrarUnidad', 'CCTIC\GrupoDetalleController@cerrarUnidad');
    });

    Route::group(['prefix' => 'asistencia'], function () {
        Route::put('reprogramar', 'CCTIC\AsistenciaController@reprogramar');
        Route::put('update', 'CCTIC\AsistenciaController@update');
        Route::put('actuailzarAsistenciaDocente', 'CCTIC\AsistenciaController@actuailzarAsistenciaDocente');
    });

    Route::group(['prefix' => 'estudiantes'], function () {
        Route::get('byAsistencia', 'CCTIC\EstudianteController@byAsistencia');
        route::get('byNotas/{id}', 'CCTIC\EstudianteController@byNotas');
        route::get('byDNI', 'CCTIC\EstudianteController@byDNI');
    });

    //    EXAMENES EXTEMPORANEO - UBICACION - SUFICIENCIA
    Route::group(['prefix' => 'gestion-examenes'], function () {
        Route::get('obtenerTiposExamenes', 'CCTIC\GestionExamenesController@obtenerTiposExamenes');
        Route::get('obtenerExamenesAgendados', 'CCTIC\GestionExamenesController@obtenerExamenesAgendados');
        Route::get('obtenerExamenAgendadoXId', 'CCTIC\GestionExamenesController@obtenerExamenAgendadoXId');
        // Route::get('obtenerAlumnosExtemporaneo', 'CCTIC\GestionExamenesController@obtenerAlumnosExtemporaneo');
        Route::get('buscarPersonaPorIpers', 'CCTIC\PersonasController@buscarPersonaPorIpersId');
        Route::get('obtenerInfoGrupoExtemporaneo', 'CCTIC\GestionExamenesController@obtenerInfoGrupoExtemporaneo');
        Route::put('aprobarExamenExtemporaneo', 'CCTIC\GestionExamenesController@aprobarExamenExtemporaneo');
        Route::post('registrarAlumnosExtemporaneo', 'CCTIC\GestionExamenesController@registrarAlumnosExtemporaneo');
        Route::put('actualizarNotasExtemporaneo', 'CCTIC\GestionExamenesController@actualizarNotasExtemporaneo');
        Route::post('registraExamenUbicación', 'CCTIC\GestionExamenesController@registraExamenUbicación');
        Route::put('actualizarNotasExamenUbicación', 'CCTIC\GestionExamenesController@actualizarNotasExamenUbicación');
        Route::put('actualizarNotasExamenSuficiencia', 'CCTIC\GestionExamenesController@actualizarNotasExamenSuficiencia');
        Route::get('obtenerDataExamenSufAgendado', 'CCTIC\GestionExamenesController@obtenerDataExamenSufAgendado');
        Route::get('obtenerTiposCriterios', 'CCTIC\GestionExamenesController@obtenerTiposCriterios');
        Route::post('registraExamenSuficiencia', 'CCTIC\GestionExamenesController@registraExamenSuficiencia');
        Route::get('obtenerGruposToUbicacion', 'CCTIC\GestionExamenesController@obtenerGruposToUbicacion');
        Route::get('validarDatosParaActaExamenUbicacion', 'CCTIC\CertificadosController@validarDatosParaActaExamenUbicacion');
        Route::put('asignaGrupoAExamenUbicacion', 'CCTIC\GestionExamenesController@asignaGrupoAExamenUbicacion');
    });



    Route::group(['prefix' => 'inscripcion'], function () {
        Route::post('store', 'CCTIC\InscripcionController@store');
        Route::get('byPersdDocumento', 'cctic\InscripcionController@byPersdDocumento');
        Route::get('byGrupoId/{id}', 'CCTIC\InscripcionController@byGrupoId');
        Route::get('byDNI', 'cctic\InscripcionController@byDNI');
        Route::get('inscripcionConDetalles', 'CCTIC\InscripcionController@inscripcionConDetalles');
    });

    Route::group(['prefix' => 'descuentos'], function () {
        Route::get('index', 'CCTIC\DescuentosController@index');
    });

    Route::group(['prefix' => 'notas'], function () {
        Route::put('actualizarNotaEstudiante', 'CCTIC\NotasController@actualizarNotaEstudiante');
        Route::get('actaNotasByUnidad', 'CCTIC\NotasController@actaNotasByUnidad');
    });
});

Route::get('cctic/certificado/{iPersId}/{iGrupoId}/{iFilId}', 'CCTIC\CertificadosController@obtenerDatosCertificadoGrupo');
// Route::get('cctic/actaNotasExamen/{iPersId}/{iGrupoId}/{iFilId}', 'CCTIC\CertificadosController@imprimirActaNotasExamenSuficiencia');
Route::get('cctic/certificadoSuf/{iExamenId}/{iFilId}', 'CCTIC\CertificadosController@obtenerDatosCertificadoSuficiencia');
Route::get('cctic/actaNotasExamen/{iExamenId}/{iFilId}', 'CCTIC\CertificadosController@imprimirActaNotasExamenSuficiencia');
Route::get('cctic/actaNotasExamen/{iExamenId}/{iFilId}', 'CCTIC\CertificadosController@imprimirActaNotasExamenUbicacion');
