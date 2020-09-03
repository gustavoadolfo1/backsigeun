<?php

namespace App\Http\Controllers\CCU;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class PlanTrabajoController extends Controller
{
    public function crearPlanTrabajo(Request $request)
    {
        $dataResult = [];
        $parameters = [
            $request->cNombre,
            $request->cDocAprobacion,
            auth()->user()->iCredId,
            $request->iActividadId,
            $request->cCodigoCurso,
            $request->iControlCicloAcad,
            $request->cNumeroHoras,
            $request->iNumeroSesiones,
            $request->dFechaInicio,
            $request->dFechaTermino,
            $request->iFilId,
            $request->iCarreraId

        ];


        $dataResult = DB::select('EXEC [ccu].[Sp_INS_Curso_Actividad_Proyecto] ?,?,?,?,?,?,?,?,?,?,?,?',$parameters);

        return response()->json($dataResult);
    }

    public function buscarDocente(Request $request)
    {
        $dataResult = [];
        $parameters = [
            $request->cBusqueda,
            $request->iControlCicloAcad
        ];


        $dataResult = DB::select('EXEC [ccu].[Sp_GRAL_SEL_Personas_Docente] ?,?',$parameters);

        return response()->json($dataResult);
    }

    public function obtenerDocenteResponsable($idProyecto)
    {

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Proyecto_Docente] ?',[$idProyecto]);

        return response()->json($dataResult);
    }

    public function eliminarDocenteResponsable(Request $request)
    {
        $dataResult = [];
        $parameters = [
            $request->iProyectoId,
            $request->iCursoActividadId,
            $request->iPersId,
            auth()->user()->iCredId
        ];


        $dataResult = DB::select('EXEC [ccu].[Sp_DEL_Proyecto_Docente] ?,?,?,?',$parameters);

        return response()->json($dataResult);
    }

    public function crearDocenteSecundario(Request $request)
    {
        $dataResult = [];
        $parameters = [
            $request->iProyectoId,
            $request->iPersId,
            $request->iDocenteId,
            $request->iControlCicloAcad,
            $request->iFilId,
            $request->cFilSigla,
            $request->cDNIPersona,
            $request->iCursoActividadId,
            $request->iActividadId,
            $request->cCodigoCurso,
            auth()->user()->iCredId
        ];


        $dataResult = DB::select('EXEC [ccu].[Sp_INS_Docente_Secundario] ?,?,?,?,?,?,?,?,?,?,?',$parameters);

        return response()->json($dataResult);
    }

    public function eliminarDocenteSecundario(Request $request)
    {
        $dataResult = [];
        $parameters = [
            $request->iProyectoDocenteId,
            auth()->user()->iCredId
        ];


        $dataResult = DB::select('EXEC [ccu].[Sp_DEL_Proyecto_Docente_Secundario] ?,?',$parameters);

        return response()->json($dataResult);
    }


    public function obtenerDocenteSecundario($idProyecto)
    {

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Proyecto_Docente_Secundario] ?',[$idProyecto]);

        return response()->json($dataResult);
    }

    public function buscarEstudianteProyecto($texto)
    {

        $dataResult = DB::select('EXEC [ccu].[Sp_GRAL_SEL_Estudiantes_Proyecto] ?',[$texto]);

        return response()->json($dataResult);
    }

    public function obtenerProyectos(Request $request)
    {
        $dataResult = [];
        $parameters = [
            $request->iControlCicloAcad,
            $request->iCarreraId

        ];


        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Proyecto] ?,?',$parameters);

        return response()->json($dataResult);
    }

    public function verificarEstadoProyecto()
    {

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Proyecto_Estado]');

        return response()->json($dataResult);
    }

    public function actualizarProyecto(Request $request)
    {
        $dataResult = [];
        $parameters = [
            $request->cNombre,
            $request->cDocAprobacion,
            auth()->user()->iCredId,
            $request->iActividadId,
            $request->cCodigoCurso,
            $request->iControlCicloAcad,
            $request->cNumeroHoras,
            $request->iNumeroSesiones,
            $request->dFechaInicio,
            $request->dFechaTermino,
            $request->iFilId,
            $request->iCarreraId,
            $request->iCursoActividadId
        ];


        $dataResult = DB::select('EXEC [ccu].[Sp_UPD_Curso_Actividad_Proyecto] ?,?,?,?,?,?,?,?,?,?,?,?,?',$parameters);

        return response()->json($dataResult);
    }

    public function cambiarEstadoProyecto(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iProyectoId,
            auth()->user()->iCredId //OBSERVACION
        ];



        $dataResult = DB::select('EXEC [ccu].[Sp_UPD_Proyecto_Estado] ?,?',$parameters);

        return response()->json($dataResult);
    }

    public function obtenerProyectoId(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iProyectoId,
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Proyecto_Id] ?',$parameters);

        return response()->json($dataResult);
    }

    public function mostrarProyectos()
    {


        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Combo_Proyecto]');

        return response()->json($dataResult);
    }

    public function obtenerSeguimientoProyecto(Request $request)
    {
        $dataResult = [];
        $parameters = [
            $request->iProyectoId,
            $request->iNumeroSesion,
            auth()->user()->iCredId
        ];
        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Proyecto_Seguimiento] ?,?,?',$parameters);

        //   $collection = collect($dataResult)->each(function ($item, $key) {
        //       $item->cImagen = URL::asset('assets/'.$item->cImagen);
        //   });
        return response()->json($dataResult);
    }

    public function actualizarEstadoDocenteSec(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iProyectoDocenteId,
            $request->bEstado, //--1 PARTICIPO, 0--NO PARTICIPO
            auth()->user()->iCredId
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_UPD_Proyecto_Docente_Secundario_Estado] ?,?,?',$parameters);

        return response()->json($dataResult);
    }

    public function actualizarHorasEstudianteProy(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iMatriculaDetalleid,
            $request->nHorasCurso,
            auth()->user()->iCredId
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_UPD_Proyecto_Estudiante_Horas] ?,?,?',$parameters);

        return response()->json($dataResult);
    }

    public function actualizarEstadoInformeFinal(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iProyectoid,
            $request->bEstadoInforme,
            auth()->user()->iCredId
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_UPD_Proyecto_Informe_Estado] ?,?,?',$parameters);

        return response()->json($dataResult);
    }

    public function obtenerAsistenciaResumenIF($id)
    {

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Asistencia_Resumen_Pivot] ?',[$id]);

        return response()->json($dataResult);
    }

    public function obtenerSeguimientoDocente(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iControlCicloAcad,
            $request->iPersId
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Seguimiento_Docente] ?,?',$parameters);

        return response()->json($dataResult);
    }

    public function ingresarProyectoSeguimiento(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iProyectoId,
            $request->cDescripcionSesion,
            $request->iNumeroSesion,
            $request->iCursoActividadId,
            $request->iControlCicloAcad,
            $request->iCarreraId,
            $request->iLugarId,
            auth()->user()->iCredId
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_INS_Proyecto_Seguimiento] ?,?,?,?,?,?,?,?',$parameters);

        return response()->json($dataResult);
    }

    public function editarProyectoSeguimiento(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->cDescripcionSesion,
            $request->iLugarId,
            $request->iProyectoSeguimientoId,
            auth()->user()->iCredId
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_UPD_Proyecto_Seguimiento] ?,?,?,?',$parameters);

        return response()->json($dataResult);
    }

    public function ingresarProyectoAsistencia(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iCargasHorariasId,
            $request->iHorariosid,
            $request->iPersId,
            $request->iDocenteId,
            $request->iControlCicloAcad,
            $request->iFilId,
            $request->ICarreraId,
            $request->cCodigoCurso,
            $request->iSeccionId,
            $request->iDiaSemId,
            $request->dFechaAsistencia,
            $request->dHoraAsistencia,
            $request->iEstadoAsistencia,
            $request->dFechaRegistro,
            $request->iNumeroSesion,
            auth()->user()->iCredId
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_INS_Proyecto_Asistencia] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',$parameters);

        return response()->json($dataResult);
    }

    public function editarProyectoAsistencia(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->dFechaAsistencia,
            $request->dHoraAsistencia,
            $request->iCargasHorariasId,
            $request->iNumeroSesion,
            auth()->user()->iCredId
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_UPD_Proyecto_Asistencia_Sesion] ?,?,?,?,?',$parameters);

        return response()->json($dataResult);
    }

    public function editarProyectoAsistenciaEst(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iAsistenciaDetalleId,
            $request->bAsistenciaEstado,
            auth()->user()->iCredId
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_UPD_Proyecto_Asistencia_Estudiante] ?,?,?',$parameters);

        return response()->json($dataResult);
    }

    public function ingresarImagenSeguimiento(Request $request)
    {

        $procAdjunto = null;

        $resultTodos = [];

        if (isset($request->imagenes)){
            if (is_array($request->imagenes)){
                foreach ($request->imagenes as $file){
                    $pos = strpos($file, 'proyectosccuImagenes/'.$request->iProyectoSeguimientoId);
                    if ($pos === false) {
                        $file = str_replace('storage/', '', $file);
                        $nuevaUbicacion = 'proyectosccuImagenes/'.$request->iProyectoSeguimientoId.basename($file);
                        Storage::disk('public')->move($file, $nuevaUbicacion);
                    }
                    else {
                        $nuevaUbicacion = $file;
                    }

                    $parameters = [
                        $request->iProyectoSeguimientoId,
                        $request->cDescripcion,
                        $nuevaUbicacion,
                        auth()->user()->iCredId
                    ];

                    $resultTodos[] = DB::select('EXEC [ccu].[Sp_INS_Proyecto_Seguimiento_Detalle] ?,?,?,?',$parameters);

                }
            }
        }
/*
        $dataResult = [];
        $parameters = [
            $request->iProyectoSeguimientoId,
            $request->cDescripcion,
            $request->cImagen,
            auth()->user()->iCredId
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_INS_Proyecto_Seguimiento_Detalle] ?,?,?,?',$parameters);
*/
        return response()->json($resultTodos);
    }

    public function obtenerImagenSeguimiento(Request $request)
    {
        // abort(500, json_encode($request->toArray()));

        $dataResult = [];
        $parameters = [
            $request->iProyectoSeguimientoId
        ];
        //abort(500, json_encode($parameters));
        //DB::enableQueryLog();
        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Proyecto_Seguimiento_Detalle] ?',$parameters);
        //print_r(DB::getQueryLog());

        //abort(500, json_encode($dataResult));

        return response()->json($dataResult);
    }

    public function obtenerProyectoEstudiante(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->cEstudCodUniv,
		    $request->iActividadId,
		    $request->iTipoNota,
		    $request->iHorariosId
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Estudiante_Record_Notas_Detalles_Proyecto] ?,?,?,?',$parameters);
        return response()->json($dataResult);
    }

    public function editarDescripcionDetSeguimiento(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->cDescripcion,
            $request->iProyectoSeguimientoDetalleId,
            auth()->user()->iCredId
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_UPD_Descripcion_Seguimiento] ?,?,?',$parameters);
        return response()->json($dataResult);
    }

    public function eliminarProyectoSeguimiento(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iProyectoSeguimientoDetalleId,
            auth()->user()->iCredId
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_DEL_Proyecto_Seguimiento] ?,?',$parameters);
        return response()->json($dataResult);
    }

    public function cambiarEstadoSeguimiento(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iProyectoId,
            $request->bEstadoSeguimiento,
            auth()->user()->iCredId
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_UPD_Estado_Seguimiento] ?,?,?',$parameters);
        return response()->json($dataResult);
    }
}
