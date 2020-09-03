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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('forgot', 'AuthController@forgot');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('register', 'AuthController@register');
});
Route::get('reporteExcelPPA/{grupo}/{carrera}/{ciclo}', 'DASA\ExportReporteController@reporteExcelPPA');

Route::get('getLinkCapacitacion/{moduloCod?}', 'Ura\GeneralController@getLinkCapacitacion');

Route::get('getLinkCapacitacion2', 'AulaVirtual\RecursosController@rutaConferencia');

Route::middleware('auth:api')->any('fotografia/{iPersId?}', 'Generales\GrlPersonasController@getFotografia');
Route::middleware('auth:api')->get('obtenerValorUIT', 'Generales\TributoController@obtenerValorUIT');

Route::middleware('auth:api')->get('obtenerInfoCredencial', 'Seguridad\SegCredencialController@obtenerInfoCredencial');
Route::middleware('auth:api')->get('verificarLogueo/{moduloCod}', 'Seguridad\SegCredencialController@verificarLogueo');

Route::group(['middleware' => 'api', 'prefix' => 'ura/estudiante'], function ($router) {
    Route::get('obtenerPlanCurricularEstudiante/{codigoEstudiante}', 'Ura\UraPlanCurricularController@obtenerPlanCurricularEstudiante');

    Route::get('obtenerRecordAcademicoEstudiante/{codigoEstudiante}', 'Ura\UraPlanCurricularController@obtenerRecordAcademicoEstudiante');

    Route::get('obtenerFichasMatriculasEstudiante/{codigoEstudiante}', 'Ura\UraFichaMatriculaController@obtenerFichasMatriculasEstudiante');

    Route::get('cambioplan/{codigo}/{carrera}/{plan}', 'Ura\UraCambioPlanController@cambioPlan');
    Route::get('cambioplanN/{codigo}/{carrera}/{plan}', 'Ura\UraCambioPlanController@cambioPlanN');
    Route::get('cambioPlanActiva/{codigo}/{carrera}/{plan}', 'Ura\UraCambioPlanController@cambioPlanActiva');

    Route::get('obtenerFichaMatriculaVigente/{estudId}/{cicloAcad}', 'Ura\UraFichaMatriculaController@obtenerFichaMatriculaVigente');

    route::get('getAsistenciaEstudianteCurso/{codigo}/{cursoCod}/{cicloAcad}', 'Estudiante\EstudianteController@getAsistenciaEstudianteCurso');
    route::get('getNotasEstudianteCurso/{codigo}/{cursoCod}/{cicloAcad}', 'Estudiante\EstudianteController@getNotasEstudianteCurso');

    route::get('getReporteEncuesta/{codigo}/{cicloAcad}', 'Estudiante\EstudianteController@getReporteEncuesta');
    route::get('getAtencion/{iPersId}/{desde}/{hasta}', 'Estudiante\EstudianteController@getAtencion');

    Route::group(['prefix' => 'encuesta'], function ($router) {

        Route::get('ConsultarEncuesta', 'Estudiante\EncuestaEstudianteController@ConsultarEncuesta');
        Route::get('VerificarEncuesta/{a}/{b}', 'Estudiante\EncuestaEstudianteController@VerificarEncuesta');
        Route::get('EncuestaFinalizado/{a}/{b}', 'Estudiante\EncuestaEstudianteController@EncuestaFinalizado');
        Route::get('EncuestaBuscar/{a}/{b}', 'Estudiante\EncuestaEstudianteController@EncuestaBuscar');
        Route::post('GuardarEncuesta', 'Estudiante\EncuestaEstudianteController@GuardarEncuesta');
        Route::post('RestaurarEncuesta', 'Estudiante\EncuestaEstudianteController@RestaurarEncuesta');
    });

    Route::group(['prefix' => 'encuestas'], function ($router) {
        Route::post('getEncuesta/{encuesta}', 'Estudiante\EncuestaEstudianteController@getEncuesta');
        Route::post('guardarRespuestaEncuesta', 'Estudiante\EncuestaEstudianteController@guardarRespuestaEncuesta');

        Route::get('verificarEstadoEncuesta/{codUniv}', 'Estudiante\EncuestaEstudianteController@verificarEstadoEncuesta');
    });
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'ura/docente'], function ($router) {
    Route::get('obtenerPlanCurricularCarrera/{codigoCarrera}', 'Ura\UraPlanCurricularController@obtenerPlanCurricularCarrera');

    Route::get('obtenerPlanesCiclosAcademicos/{ignoreVerano?}', 'Ura\UraHorarioController@obtenerPlanesCiclosAcademicos');

    Route::get('obtenerHorariosPorCarreraFilialCiclo', 'Ura\UraHorarioController@obtenerHorariosPorCarreraFilialCiclo');

    Route::post('obtenerHorarios2do', 'Ura\UraHorarioController@obtenerHorarios2do');

    Route::get('obtenerCursosAulasSecciones', 'Ura\UraHorarioController@obtenerCursosAulasSecciones');

    Route::get('obtenerPlanesConCursosActivosPorCarrera/{carreraId}', 'Ura\UraHorarioController@obtenerPlanesConCursosActivosPorCarrera');

    Route::get('obtenerPaginacionDocentesPorCarrera', 'Ura\UraHorarioController@obtenerPaginacionDocentesPorCarrera');

    Route::post('insertarBloqueHorario', 'Ura\UraHorarioController@insertarBloqueHorario');

    Route::delete('eliminarBloqueHorario/{id}', 'Ura\UraHorarioController@eliminarBloqueHorario');

    Route::get('obtenerConfigHorarioCarrera', 'Ura\UraHorarioController@obtenerConfigHorarioCarrera');

    Route::post('guardarConfigHorarioCarrera', 'Ura\UraHorarioController@guardarConfigHorarioCarrera');

    Route::post('guardarCargaHoraria', 'Ura\UraHorarioController@guardarCargaHoraria');

    Route::get('obtenerPlanesConCargasAcademicas', 'Ura\UraHorarioController@obtenerPlanesConCargasAcademicas');

    Route::get('obtenerSelectsCargaHoraria', 'Ura\UraHorarioController@obtenerSelectsCargaHoraria');

    Route::get('generarReporte', 'Ura\UraPlanCurricularController@generarReporte');

    Route::get('obtenerHorarioDocente/{idDocente}/{IdCiclo}', 'Docente\DocenteController@HorarioDocente');

    Route::post('InsertarAsistenciaCurso', 'Docente\DocenteController@InsertarAsistenciaCurso');
});

Route::group(['middleware' => 'api', 'prefix' => 'ura/general'], function ($router) {
    Route::get('obtenerCicloAcademicoActivo', 'Ura\UraControlCicloAcademicoController@obtenerCicloAcademicoActivo');

    Route::get('buscarDocentes/{parametro}', 'Ura\UraDocenteController@buscarDocentes');
    Route::get('buscarEstudiantes', 'Ura\UraEstudianteController@buscarEstudiantes');
    Route::get('buscarEstudiantesDocentes/{parametro}/{carreraId?}/{filialId?}', 'Ura\GeneralController@buscarEstudiantesDocentes');

    Route::get('obtenerDatosEstudiante/{codigo}', 'Ura\GeneralController@obtenerDatosEstudiante');
    Route::get('obtenerDatosDocente/{id}', 'Ura\GeneralController@obtenerDatosDocente');

    Route::get('obtenerHorarioEstudiante/{codigo}/{cicloAcad}', 'Ura\GeneralController@obtenerHorarioEstudiante');

    Route::get('obtenerFilialesCarreras', 'Ura\GeneralController@obtenerFilialesCarreras');
    Route::get('obtenerPlanesCarrera/{carreraFilialId}', 'Ura\GeneralController@obtenerPlanesCarrera');

    Route::get('obtenerTiposMatricula', 'Ura\UraFichaMatriculaController@obtenerTiposMatricula');

    Route::get('obtenerFilialesCarrerasPlanesCiclos', 'Ura\GeneralController@obtenerFilialesCarrerasPlanesCiclos');

    Route::get('getCarrerasSemestres', 'Ura\GeneralController@getCarrerasSemestres');
    Route::get('getPlanesCiclosCarrera/{carreraId}', 'Ura\GeneralController@getPlanesCiclosCarrera');
});

Route::get('dasa/horarios/reporteExportableCargasAcademicasExportable/{carreraId}/{filId}/{ciclo}/{plan}/{tipo}', 'DASA\ExportReporteController@reporteCargasAcademicas');

Route::group(['middleware' => 'auth:api', 'prefix' => 'dasa'], function ($router) {
    Route::group(['prefix' => 'matricula'], function ($router) {
        Route::get('obtenerCarrerasPlanes', 'DASA\CurriculaCursoController@obtenerCarrerasPlanes');
        Route::get('obtenerCurriculaPorCarreraPlan/{carreraId}/{curricId}', 'DASA\CurriculaCursoController@obtenerCurriculaPorCarreraPlan');

        Route::get('obtenerCurriculaPorCarreraPlanMod/{carreraId}/{curricId}/{cicloAcad}', 'DASA\CurriculaCursoController@obtenerCurriculaPorCarreraPlanMod');

        Route::post('guardarEstadoCheckCurso', 'DASA\CurriculaCursoController@guardarEstadoCheckCurso');

        Route::get('obtenerCarrerasAutorizaciones/{cicloAcad}', 'DASA\CarreraController@obtenerCarrerasAutorizaciones');
        Route::post('guardarEstadoCheckEscuela', 'DASA\CarreraController@guardarEstadoCheckEscuela');

        Route::get('allCiclos', 'Ura\UraControlCicloAcademicoController@allCiclos');
        Route::post('editCiclos', 'Ura\UraControlCicloAcademicoController@editCiclos');

        Route::get('sancionesEstudiante/{codigo}', 'DASA\EstudianteController@sancionesEstudiante');
        Route::post('updateSancionesEstudiante', 'DASA\EstudianteController@updateSancionesEstudiante');
    });

    Route::group(['prefix' => 'notas'], function ($router) {
        Route::post('updNotaEstudianteMatrExtr', 'DASA\EstudianteController@updNotaEstudianteMatrExtr');
    });

    Route::group(['prefix' => 'procesar'], function ($router) {
        Route::get('obtenerEstudiantesObservados', 'ura\UraEstudianteController@obtenerEstudiantesObservados');
        Route::get('obtenerEstudiantesAEgresarPorCarreraPlan/{carreraId}/{curricId}', 'ura\UraEstudianteController@obtenerEstudiantesAEgresarPorCarreraPlan');
        Route::get('obtenerEstudiantesPPA/{grupo}/{carreraId}/{ciclo}', 'ura\UraEstudianteController@obtenerEstudiantesPPA');
        Route::get('obtenerEstudiantesPPS/{carreraId}/{cicloAcad}', 'ura\UraEstudianteController@obtenerEstudiantesPPS');
        Route::get('obtenerEstudiantesReservaExcedida', 'DASA\ProcesoEstudianteController@obtenerEstudiantesReservaExcedida');
        Route::get('obtenerEstudiantesCuartaDesaprobada/{carreraId}/{cicloAcad}/{tipo?}', 'DASA\ProcesoEstudianteController@obtenerEstudiantesCuartaDesaprobada');
        Route::get('obtenerEstudiantescambioPlan/{carreraId}/{plan}/{ciclo}', 'DASA\ProcesoEstudianteController@obtenerEstudiantescambioPlan');
        Route::get('obtenerEstudiantesASancionar/{carreraId}/{cicloAcad}/{tipo?}', 'DASA\ProcesoEstudianteController@obtenerEstudiantesASancionar');
        Route::get('obtenerEstudiantesSinMatricula/{carreraId}/{cicloAcad}', 'DASA\ProcesoEstudianteController@obtenerEstudiantesSinMatricula');

        Route::post('actualizarEstadoEstudiante', 'DASA\ProcesoEstudianteController@actualizarEstadoEstudiante');

        Route::get('listProcesamientos/{cicloAcad}', 'DASA\ProcesoEstudianteController@listProcesamientos');

        Route::post('cambiarEstadoProcesamiento', 'DASA\ProcesoEstudianteController@cambiarEstadoProcesamiento');
    });

    Route::group(['prefix' => 'estado'], function ($router) {
        Route::get('obtenerEstadosEstudiante', 'DASA\EstudianteController@obtenerEstadosEstudiante');
        Route::post('cambiarEstadoEstudiante', 'DASA\EstudianteController@cambiarEstadoEstudiante');
    });

    Route::group(['prefix' => 'estudiante'], function ($router) {
        Route::post('resetearContraseniaEstudiante', 'DASA\EstudianteController@resetearContraseniaEstudiante');
        Route::post('cambiarContraseniaEstudiante', 'DASA\EstudianteController@cambiarContraseniaEstudiante');

        Route::get('obtenerFichasMatriculas/{codigo}', 'DASA\EstudianteController@obtenerFichasMatriculas');
        Route::get('obtenerCurriculaCursoDetalle/{carreraId}/{curricId}', 'DASA\CurriculaCursoController@obtenerCurriculaCursoDetalle');
        Route::get('getObservacionesEstudiante/{codigo}', 'DASA\EstudianteController@getObservacionesEstudiante');
        Route::post('guardarObservacionEstudiante', 'DASA\EstudianteController@guardarObservacionEstudiante');

        Route::get('obtenerDatosFichaMatricula/{codigo}/{libre?}', 'DASA\EstudianteController@obtenerDatosFichaMatricula');

        Route::get('obtenerDatosFichaMatricula-MovilidadE/{codigo}/{ciclo}', 'DASA\EstudianteController@obtenerDatosFichaMatricula2');

        Route::post('guardarReservaMatricula', 'DASA\EstudianteController@guardarReservaMatricula');

        Route::get('getPdfReserva/{matricId}', 'DASA\EstudianteController@getPdfReserva');

        Route::post('rectificarMatricula', 'DASA\EstudianteController@rectificarMatricula');

        Route::post('matricularMovilidad', 'DASA\EstudianteController@matricularMovilidad');
        Route::post('matricularMovilidad2', 'DASA\EstudianteController@matricularMovilidad2');

        Route::post('verificarCruceHorarioRectificacion', 'DASA\EstudianteController@verificarCruceHorarioRectificacion');

        Route::get('buscarCursoMatriculaMovilidad/{busqueda}', 'DASA\EstudianteController@buscarCursoMatriculaMovilidad');

        Route::post('actualizarSeccion', 'DASA\EstudianteController@actualizarSeccion');

        Route::get('obtenerDatosGenerales/{codigo}', 'DASA\EstudianteController@obtenerDatosGenerales');

        Route::get('obtenerPagosEstudiante/{codigo}/{page}/{pageSize}', 'DASA\EstudianteController@obtenerPagosEstudiante');
        Route::get('buscarCursoConvalidacion/{param}/{estudId}', 'DASA\EstudianteController@buscarCursoConvalidacion');
        Route::post('convalidarCursos', 'DASA\EstudianteController@convalidarCursos');
    });

    Route::group(['prefix' => 'docente'], function ($router) {
        Route::get('obtenerActaDocente/{cargaAcadId}/{cicloAcad}', 'DASA\DocenteController@obtenerActaDocente');
        Route::post('resetearContraseniaDocente', 'DASA\DocenteController@resetearContraseniaDocente');
    });

    Route::group(['prefix' => 'reportes'], function ($router) {
        Route::get('obtenerReporteMatriculados/{cicloAcad}', 'DASA\ReporteController@obtenerReporteMatriculados');

        Route::get('matriculadosPorCurso/{carrFilId}/{curricId}', 'DASA\ReporteController@matriculadosPorCurso');
        Route::get('matriculadosPorCursoFilial/{carreraId}/{filialId}/{curricId}/{semestre}', 'DASA\ReporteController@matriculadosPorCursoFilial');
        Route::post('matriculadosPorCursoFilialDetallado', 'DASA\ReporteController@matriculadosPorCursoFilialDetallado');


        Route::get('matriculadosPorCarrera/{carreraId}/{semestre}', 'DASA\ReporteController@matriculadosPorCarrera');
        Route::get('matriculadosPorSemestre/{semestre}', 'DASA\ReporteController@matriculadosPorSemestre');

        Route::get('obtenerHorariosDocentes/{filCarreraId}/{curricId}/{cicloAcad}', 'DASA\ReporteController@obtenerHorariosDocentes');
        Route::get('matriculadosPorSemestreAll/{semestre}', 'DASA\ReporteController@matriculadosPorSemestreAll');
        Route::get('matriculadosPorCursoCarrera/{filialId}/{semestre}/{carreraId}/{curriId}', 'DASA\ReporteController@matriculadosPorCursoCarrera');
        Route::get('matriculadosPorIngresantes', 'DASA\ReporteController@matriculadosPorIngresantes');

        Route::get('matriculadosPorSemestreCarreraFilialCiclo/{iSemestre}/{iCarreraId}/{iFilId}/{cCiclo}', 'DASA\ReporteController@matriculadosPorSemestreCarreraFilialCiclo');

        Route::get('cantidadDocentesEscuela/{cicloAcad}/{condicionId}', 'DASA\ReporteController@cantidadDocentesEscuela');
        Route::get('ReporteRelacionDocentes/{iFilId}/{iCarreraId}/{iSemestre}', 'DASA\ReporteController@ReporteRelacionDocentes');

        Route::post('ReporteDetallesMatriculadosXCurso', 'DASA\ReporteController@ReporteDetallesMatriculadosXCurso');

        Route::get('ingresantesPorModalidadYSemestreIngreso/{modalidadCod}/{semeIngre}/{tipoReturn}/{tipo?}', 'DASA\ReporteController@ingresantesPorModalidadYSemestreIngreso');

        Route::get('obtenerPlanEstudiosPorCarreraYCurricula/{carreraId}/{curricId}/{tipoReturn}/{tipo?}', 'DASA\ReporteController@obtenerPlanEstudiosPorCarreraYCurricula');
        Route::get('obtenerPlanEstudiosEquivalente/{carreraId}/{curricId}/{tipoReturn}/{tipo?}', 'DASA\ReporteController@obtenerPlanEstudiosEquivalente');

        Route::get('getMatriculadosPorNumeroMatricula/{semestre}/{numMatricula}/{tipoReturn}/{tipo?}', 'DASA\ReporteController@getMatriculadosPorNumeroMatricula');
        Route::get('getMatriculadosNumMatriculaDetallado', 'DASA\ReporteController@getMatriculadosNumMatriculaDetallado');

        Route::get('getOrdenMeritoModo1', 'DASA\ReporteController@getOrdenMeritoModo1');
        Route::get('getOrdenMeritoModo2', 'DASA\ReporteController@getOrdenMeritoModo2');

        Route::get('getRecojoInfoMINEDU/{tipoReturn}/{tipo?}', 'DASA\ReporteController@getRecojoInfoMINEDU');

        Route::get('getBachilleresOTitulados/{tipoGrado}/{tipoReturn}/{tipo?}', 'DASA\ReporteController@getBachilleresOTitulados');
        Route::get('getBachilleresOTituladosDetallado/{tipoGrado}/{carreraId}/{year}/{tipoReturn}/{tipo?}', 'DASA\ReporteController@getBachilleresOTituladosDetallado');
        Route::get('getEgresadosDetallado/{carreraId}/{semestre}/{tipoReturn}/{tipo?}', 'DASA\ReporteController@getEgresadosDetallado');
        Route::get('getRelacionEgresadosBachilleresTitulados/{carreraId}/{semestre}/{tipoReturn}/{tipo?}', 'DASA\ReporteController@getRelacionEgresadosBachilleresTitulados');
        Route::get('reporteasistencianotas/{iDocenteId}/{iCargaHId}/{iControlCicloAcad}', 'DASA\ReporteController@ReporteAsistenciaNotas');

        Route::get('getSituacionRacionalizacion/{iControlCicloAcad}/{tipoReturn}/{tipo?}', 'DASA\ReporteController@getSituacionRacionalizacion');
        Route::get('getResumenEgresados/{tipoReturn}/{tipo?}', 'DASA\ReporteController@getResumenEgresados');

        Route::get('reporteIngresantesEscuela/{tipo}', 'DASA\ReporteController@ReporteIngresantesEscuela');
        Route::get('ReporteIngresantesEscuelaDetalles/{year}/{carrera}/{fil}/{tipo}', 'DASA\ReporteController@ReporteIngresantesEscuelaDetalles');

        Route::get('getCabecerasActasEvalExtraordinaria/{carreraId}/{filialId}/{cicloAcad}', 'DASA\ReporteController@getCabecerasActasEvalExtraordinaria');

        Route::get('reporteExcelPPA/{grupo}/{carrera}/{ciclo}', 'DASA\ReporteController@reporteExcelPPA');

        Route::any('racionalizacionFormatos/{id?}', 'DASA\ReporteController@razDocenteFormatos');

        Route::get('ReporteRelacionDocentesSilabo/{iFilId}/{iCarreraId}/{iSemestre}/{opcion}', 'DASA\ReporteController@ReporteRelacionDocentesSilabo');

        Route::get('getReporteSIRIES/{semestre}/{tipoReturn}/{tipo?}', 'DASA\ReporteController@getReporteSIRIES');
    });

    Route::group(['prefix' => 'reporteExportable'], function ($router) {
        Route::get('matriculadosPorSemestreCarreraFilialCiclo/{cicloAcad}/{carreraId}/{filialId}/{ciclo}/{tipo}', 'DASA\ExportReporteController@matriculadosPorSemestreCarreraFilialCiclo');
        Route::post('matriculadosPorCurso/{tipo}', 'DASA\ExportReporteController@matriculadosPorCurso');
    });

    /*Route::group([ 'prefix' => 'horarios' ], function ($router) {

        Route::get('reporteExportableCargasAcademicasExportable/{carreraId}/{filId}/{ciclo}/{plan}/{tipo}', 'DASA\ExportReporteController@reporteCargasAcademicas');
    });*/

    /**
     *
     * Api para el Mantenimineto de Calenario Academico modulo dasa
     * route: /api/dasa/mantenimiento/{prefix}
     */
    Route::group(['prefix' => 'mantenimiento'], function ($router) {
        Route::get('filial', 'Api\Grl\FilialController@index')->name('api.filial.index');

        Route::get('periodo', 'Api\Grl\PeriodoController@index')->name('api.periodo.index');

        Route::get('semestre', 'Api\Ura\SemestreController@index')->name('api.semestre.index');

        Route::get('tipocalendario', 'Api\Ura\TipoCalendarioController@index')->name('api.tipocalendario.index');

        Route::get('tipoactividad', 'Api\Ura\TipoActividadController@index')->name('api.tipoactividad.index');

        Route::get('actividad', 'Api\Ura\ActividadCalendarioController@index')->name('api.actividad.index');

        Route::get('actividades', 'Api\Ura\CalendarioAcademicoController@getActividades')->name('api.actividad.index');

        Route::get('semestres', 'Api\Ura\CalendarioAcademicoController@getSemestres');

        Route::get('calendarios', 'Api\Ura\CalendarioAcademicoController@index')->name('api.calendario.index');

        Route::get('calendariosDetalles/{id}', 'Api\Ura\CalendarioAcademicoController@getDetallesCalendario')->name('api.calendario.index');

        Route::post('saveDetallesCalendario', 'Api\Ura\CalendarioAcademicoController@saveDetallesCalendario');

        Route::post('editDetallesCalendario', 'Api\Ura\CalendarioAcademicoController@editDetallesCalendario');

        Route::post('calendarioacademico', 'Api\Ura\CalendarioAcademicoController@store')->name('api.calendario.store');

        Route::get('calendarioacademico/{id}/edit', 'Api\Ura\CalendarioAcademicoController@edit')->name('api.calendario.edit');

        Route::patch('calendarioacademico/{id}', 'Api\Ura\CalendarioAcademicoController@update')->name('api.calendario.update');

        Route::delete('calendarioacademico/{id}', 'Api\Ura\CalendarioAcademicoController@destroy')->name('api.calendario.destroy');

        Route::get('calendariodetalle/{id}/edit', 'Api\Ura\CalendarioAcadDetController@edit')->name('api.caldetalle.edit');

        Route::get('calendariodetalle', 'Api\Ura\CalendarioAcadDetController@index')->name('api.caldetalle.index');

        Route::post('calendariodetalle', 'Api\Ura\CalendarioAcadDetController@store')->name('api.caldetalle.store');
    });
});

Route::group(['middleware' => 'api', 'prefix' => 'escuela'], function ($router) {

    /**
     *
     * Api para la Carga Horaria [Modulo Escuela]
     * route: /api/escuela/horario/{prefix}
     */
    Route::get('getAulas/{iCarrrera}/{iFil}/{cicloAcad}', 'Escuela\GeneralController@getAulas');

    Route::post('saveUpAulas', 'Escuela\GeneralController@saveUpAulas');

    Route::get('obtenerSemestres', 'Escuela\GeneralController@obtenerSemestres');

    Route::group(['prefix' => 'horario'], function ($router) {
        Route::get('carga', 'Api\Ura\HorarioController@index')->name('api.horario.index');

        Route::get('docente', 'Api\Ura\HorarioController@docente')->name('api.horario.docente');

        Route::get('cursos', 'Api\Ura\HorarioController@cursos')->name('api.horario.cursos');

        Route::get('calculoHoras', 'Api\Ura\HorarioController@calculoHoras');

        Route::get('secciones', 'Api\Ura\HorarioController@secciones')->name('api.horario.secciones');

        Route::post('reporte', 'Api\Ura\HorarioController@reporteHorarios');
    });

    Route::group(['prefix' => 'cargaAcademica'], function ($router) {
        Route::delete('eliminar/{cargaId}', 'Escuela\CargaAcademicaController@eliminarCargaAcademica');
    });

    Route::group(['prefix' => 'reportes'], function ($router) {
        Route::get('getSelectsReportes/{carreraId?}', 'Escuela\ReporteController@getSelectsReportes');

        Route::get('obtenerMatriculadosGeneral', 'Escuela\ReporteController@obtenerMatriculadosGeneral');
        Route::post('obtenerMatriculadosPorCurso', 'Escuela\ReporteController@obtenerMatriculadosPorCurso');
        Route::get('obtenerIngresantesMatriculados', 'Escuela\ReporteController@obtenerIngresantesMatriculados');

        Route::post('getFileReporteHorarios/{tipo}', 'Escuela\ExportReporteController@getFileReporteHorarios');
    });

    Route::group(['prefix' => 'general'], function ($router) {
        Route::get('getAsignaturas/{carreraId}/{curricula}/{ciclo}', 'Escuela\GeneralController@getAsignaturas');
    });
});

Route::group(['middleware' => 'api', 'prefix' => 'estudiante'], function ($router) {
    Route::group(['prefix' => 'matricula'], function ($router) {
        Route::get('verificarRequisitosOBU/{codUniv}/{dni}', 'ura\UraEstudianteController@verificarRequisitosOBU');
        Route::get('obtenerCursosDisponiblesMatricula/{codUniv}', 'Estudiante\MatriculaController@obtenerCursosDisponiblesMatricula');
        Route::get('obtenerHorarios/{codigoUniv}/{cicloAcad}', 'Estudiante\MatriculaController@obtenerHorarios');
        Route::get('obtenerSemestresAcademicosEstudiante/{codigoUniv}', 'Estudiante\MatriculaController@obtenerSemestresAcademicosEstudiante');

        Route::post('guardarProforma', 'Estudiante\MatriculaController@guardarProforma');
        Route::delete('eliminarProforma/{idProforma}/{codigo}', 'Estudiante\MatriculaController@eliminarProforma');

        Route::get('getEstudiantesMatriculaExtraordinaria/{carreraId}/{filial}/{cicloAcad}', 'DASA\EstudianteController@getEstudiantesMatriculaExtraordinaria');
    });

    Route::group(['prefix' => 'pdf'], function ($router) {
        Route::get('documentos/{tipo}/{codigo}/{cicloAcad}', 'Estudiante\ReportePDFController@getDocumentoPDF');
        Route::get('getHistorialAcademico/{codigo}', 'Estudiante\ReportePDFController@getHistorialPdf');
    });

    Route::get('obtenerDatosContacto/{codigo}', 'Estudiante\EstudianteController@obtenerDatosContacto');

    Route::post('editarDatosContacto', 'Estudiante\EstudianteController@editarDatosContacto');
    Route::post('listasustitutorio', 'Estudiante\EstudianteController@getSustitutorio');
    Route::post('genpredeuda', 'Estudiante\EstudianteController@genPreDeuda');
});

Route::group(['middleware' => 'api', 'prefix' => 'dbu'], function ($router) {
    Route::group(['prefix' => 'control'], function ($router) {
        Route::post('guardarActualizarChecksObu/', 'DBU\UraCheckObuController@guardarActualizarChecksObu');
    });
});

Route::get('enviarDatosCorreo', 'Docente\DocenteController@enviarDatosCorreo');

Route::group(['middleware' => 'auth:api', 'prefix' => 'docente'], function ($router) {




    Route::group(['prefix' => 'cursos'], function ($router) {
        Route::get('obternerCursosDocente/{ciclo}/{id}', 'Docente\DocenteController@CursosDocente');

        Route::get('obtenerDatosContacto/{codigo}', 'Docente\DocenteController@obtenerDatosContacto');

        Route::post('editarDatosContacto', 'Docente\DocenteController@editarDatosContacto');

        Route::get('obtenerDatosCargaHorariaDocente/{id}/{ciclo}', 'Docente\DocenteController@obtenerDatosCargaHorariaDocente');
        Route::get('obtenerDatosCargaHorariaDocente2/{id}/{ciclo}', 'Docente\DocenteController@obtenerDatosCargaHorariaDocente2');
    });



    Route::group(['prefix' => 'cursossilabo'], function ($router) {
        Route::get('obternerCursosDocenteSilabo/{cicloa}/{cursoa}', 'Docente\DocenteSilaboController@CursosDocenteSilabo');

        Route::get('obtenerSilaboProcedimientosTecnicas', 'Docente\DocenteSilaboController@obtenerSilaboProcedimientosTecnicas');
        Route::get('obtenerSilaboEquipos', 'Docente\DocenteSilaboController@obtenerSilaboEquipos');
        Route::get('obtenerSilaboMateriales', 'Docente\DocenteSilaboController@obtenerSilaboMateriales');
        Route::get('obtenerSilaboEvaluacionPermanente', 'Docente\DocenteSilaboController@obtenerSilaboEvaluacionPermanente');

        Route::get('obtenerSilaboClaseSilabo', 'Docente\DocenteSilaboController@obtenerSilaboClaseSilabo');
        Route::get('obtenerSilaboSemanaSilabo', 'Docente\DocenteSilaboController@obtenerSilaboSemanaSilabo');


        Route::post('insertarDetalleCompetencias', 'Docente\DocenteSilaboController@insertarDetalleCompetencias');

        Route::post('insertarDetalleUnidad', 'Docente\DocenteSilaboController@insertarDetalleUnidad');
        Route::post('insertarDetalleConceptuales', 'Docente\DocenteSilaboController@insertarDetalleConceptuales');
        Route::post('insertarDetalleActitudinales', 'Docente\DocenteSilaboController@insertarDetalleActitudinales');
        Route::post('insertarDetalleProcedimentales', 'Docente\DocenteSilaboController@insertarDetalleProcedimentales');


        Route::post('insertarDetalleProcedimientos', 'Docente\DocenteSilaboController@insertarDetalleProcedimientos');

        Route::post('insertarAprendizajes', 'Docente\DocenteSilaboController@insertarAprendizajes');

        Route::post('insertarDetalleEquipos', 'Docente\DocenteSilaboController@insertarDetalleEquipos');
        Route::post('insertarDetalleMateriales', 'Docente\DocenteSilaboController@insertarDetalleMateriales');

        Route::post('insertarDetalleEvaluacion', 'Docente\DocenteSilaboController@insertarDetalleEvaluacion');

        Route::post('insertarFuenteTextoBase', 'Docente\DocenteSilaboController@insertarFuenteTextoBase');
        Route::post('insertarFuenteBibliografiaComplementaria', 'Docente\DocenteSilaboController@insertarFuenteBibliografiaComplementaria');
        Route::post('insertarFuenteElectronicas', 'Docente\DocenteSilaboController@insertarFuenteElectronicas');
    });

    Route::group(['prefix' => 'racionalizacion'], function ($router) {
        Route::get('obternerDatosDocente/{ciclo}/{id}', 'Docente\RacionalizacionDocenteController@DatosDocente');
        Route::get('obternerDatosR/{ciclo}/{id}', 'Docente\RacionalizacionDocenteController@DatosR');
        Route::get('obternerHorarioDocente/{ciclo}/{id}', 'Docente\RacionalizacionDocenteController@HorarioDocente');
        Route::get('obternerActividadesDocente', 'Docente\RacionalizacionDocenteController@ActividadesDocente');
    });

    Route::group(['prefix' => 'silabo'], function ($router) {
        Route::get('obternerDatosSDocente/{a}/{b}/{c}/{d}/{e}', 'Docente\SilaboDocController@obternerDatosSDocente');
        Route::post('insertarDatosBasicos', 'Docente\SilaboDocController@insertarDatosBasicos');

        Route::get('obternerSilaboSemanaActual/', 'Docente\SilaboDocController@obternerSilaboSemanaActual');
        Route::get('obternerSilaboMetodologiaTipo/{a}', 'Docente\SilaboDocController@obternerSilaboMetodologiaTipo');
        Route::get('obternerSilaboEvaluacionTipo', 'Docente\SilaboDocController@obternerSilaboEvaluacionTipo');
        Route::get('obternerSilaboBibliografiaTipo', 'Docente\SilaboDocController@obternerSilaboBibliografiaTipo');

        Route::post('insertarCompetenciaActual', 'Docente\SilaboDocController@insertarCompetenciaActual');
        Route::post('insertarCompetenciaElemento', 'Docente\SilaboDocController@insertarCompetenciaElemento');
        Route::post('insertarCompetenciaConocimientos', 'Docente\SilaboDocController@insertarCompetenciaConocimientos');
        Route::post('insertarDetalleUnidadActual', 'Docente\SilaboDocController@insertarDetalleUnidadActual');
        Route::post('insertarDetalleSecuenciasActual', 'Docente\SilaboDocController@insertarDetalleSecuenciasActual');
        //Route::post('insertarDetalleConocimientosActual', 'Docente\SilaboDocController@insertarDetalleConocimientosActual');
        //Route::post('insertarDetalleResultadosActual', 'Docente\SilaboDocController@insertarDetalleResultadosActual');
        //Route::post('insertarDetalleMaterialesActual', 'Docente\SilaboDocController@insertarDetalleMaterialesActual');
        Route::post('insertarDetalleMetodologias', 'Docente\SilaboDocController@insertarDetalleMetodologias');
        Route::post('insertarDetalleEvaluacionesActual', 'Docente\SilaboDocController@insertarDetalleEvaluacionesActual');
        //Route::post('insertarDetalleEvaluacionesResultado', 'Docente\SilaboDocController@insertarDetalleEvaluacionesResultado');
        //Route::post('insertarDetalleEvaluacionesEvidenciar', 'Docente\SilaboDocController@insertarDetalleEvaluacionesEvidenciar');
        //Route::post('insertarDetalleEvaluacionesInstrumento', 'Docente\SilaboDocController@insertarDetalleEvaluacionesInstrumento');
        Route::post('insertarDetalleBibliografiaActual', 'Docente\SilaboDocController@insertarDetalleBibliografiaActual');
        Route::get('SilaboFinalizado/{a}/{b}/{c}/{d}', 'Docente\SilaboDocController@SilaboFinalizado');
        Route::get('LinkCapacitacion', 'Docente\SilaboDocController@LinkCapacitacion');

        Route::get('importar_silabo/{a}/{b}/{c}/{d}/{e}', 'Docente\SilaboDocController@importar_silabo');

        Route::get('obternerDatosS/{a}/{b}/{c}/{d}/{e}/{f}', 'Docente\SilaboDocController@obternerDatosS');
    });
});


Route::group(['prefix' => 'pdfexcel'], function ($router) {

    //REPORTE ESCUELA: matriculados Por Curso Filial Detallado
    Route::get('ExportMatriculadoxCurso/{a}/{b}/{c}/{d}/{e}', 'DASA\ReporteController@ExportMatriculadoxCurso');
    //

    //REPORTE DASA: Matriculados por semestre académico
    Route::get('MatriculadoSemestreExcel/{a}', 'DASA\ReporteController@MatriculadoSemestreExcel');
    //

    //REPORTE DASA Matriculados por curso
    Route::get('MatriculadoCursoExcel/{a}/{b}/{c}/{d}', 'DASA\ReporteController@MatriculadoCursoExcel');
    //

    //REPORTE DASA Ingresantes por modalidad / escuelas
    Route::get('IngresantesModalidadEscuelaExcel/', 'DASA\ReporteController@IngresantesModalidadEscuelaExcel');
    //

    //REPORTE DASA Horarios de Clases
    Route::get('HorarioClasesExcel/{a}/{b}/{c}', 'DASA\ReporteController@HorarioClasesExcel');
    //

    //REPORTE DASA Acta de notas Evaluacion Extraordinaria
    Route::get('ActaEvalucionExtraordinaria/{iCarreraId}/{iFilId}/{iControlCicloAcad}/{iDocenteId}/{iCurricId}/{seccionId}/{cursoCod}', 'DASA\ReporteController@ActaEvalucionExtraordinaria');
    //
    //REPORTE DASA report
    Route::get('UnidadesCerradasCarrera/{tipo}/{iControlCicloAcad}/{iFilId}/{iCarreraId}', 'DASA\ReporteController@UnidadesCerradasCarrera');
    //

});


Route::group(['prefix' => 'pide', /*'middleware' => 'auth:api'*/], function () {
    Route::get('checkIfHasPIDEReniec/{dni}', 'PideController@checkIfHasPIDEReniec');

    Route::any('{tipo}/{persona_id?}', 'PideController@consultar');
});

Route::group(['prefix' => 'grl', 'middleware' => 'auth:api'], function () {

    Route::get('getLinksModulos/{moduloId}', 'Ura\GeneralController@getLinksModulos');
    Route::get('ingresarConferencia/{conferenciaId}/{codEstud}', 'Ura\GeneralController@ingresarConferencia');

    Route::group(['prefix' => 'sugerencias'], function ($router) {
        Route::post('guardarSugerencia', 'Generales\SugerenciaController@guardarSugerencia');
    });
    Route::group(['prefix' => 'seguridad'], function ($router) {
        Route::post('cambiarPassword', 'Seguridad\SegCredencialController@cambiarPassword');

        Route::post('guardarLogInicioSesion/{iniciador}', 'AuthController@guardarLogInicioSesion');
    });
});

//PDF ACTA
Route::get('pdfActaDocente/{cargaId}/{cicloAcad}', 'Docente\ReportePDFController@pdfActaDocente');

Route::get('pdfRegEva/{a}/{b}/{c}/{d}/{e}/{f}/{g}', 'Docente\ReportePDFController@pdfRegEvaDocente');
//
//PDF REGISTRO
Route::get('pdfRegistroDocente', 'Docente\ReportePDFController@pdfRegistroDocente');
Route::post('pdfRegistroDocente2', 'Docente\ReportePDFController@pdfRegistroDocente2');

Route::get('getReporteSIRIES/{semestre}/{tipoReturn}/{tipo?}', 'DASA\ReporteController@getReporteSIRIES');
//

Route::group(['prefix' => 'racionalizacion'], function ($router) {

    //PDF
    Route::get('descargaFormato1Pdf/{a}/{b}', 'Docente\RacionalizacionDocenteController@descargaFormato1Pdf');
    Route::get('descargaFormato1APdf/{a}/{b}', 'Docente\RacionalizacionDocenteController@descargaFormato1APdf');
    Route::get('descargaFormato1BPdf/', 'Docente\RacionalizacionDocenteController@descargaFormato1BPdf');
});
