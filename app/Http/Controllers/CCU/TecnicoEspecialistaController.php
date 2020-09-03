<?php

namespace App\Http\Controllers\CCU;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TecnicoEspecialistaController extends Controller
{
    public function obtenerCursosDocente(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iPersId,
            $request->iControlCicloAcad
        ];
        
        

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Cursos_Docente] ?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function crearAsistenciaCurso(Request $request)
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
            auth()->user()->iCredId
        ];
        
        

        $dataResult = DB::select('EXEC [ccu].[Sp_INS_Asistencia_Cursos_Cabecera] ?,?,?,?,?,?,?,?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function obtenerAsistenciaCurso($id)
    {

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Asistencia_Cursos_Cabecera] ?',[$id]);
       
        return response()->json($dataResult);
    }

    public function ingresarAsistenciaEstudiante(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iAsistenciaId,
            $request->iHorariosId,
            auth()->user()->iCredId
        ];
        
        

        $dataResult = DB::select('EXEC [ccu].[Sp_INS_Asistencia_Cursos_Detalle] ?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function obtenerAsistenciaEstudiante($id)
    {

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Asistencia_Cursos_Detalle] ?',[$id]);
       
        return response()->json($dataResult);
    }

    public function actualizarEstadoAsistenciaEst(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iAsistenciaDetalleId,
            $request->bAsistenciaEstado,
            auth()->user()->iCredId
        ];
        
        

        $dataResult = DB::select('EXEC [ccu].[Sp_UPD_Asistencia_Cursos_Detalle_Estado] ?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function actualizarEstadoAsistencia(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iAsistenciaId,
            $request->iEstadoAsistencia,
            auth()->user()->iCredId
        ];
        
        

        $dataResult = DB::select('EXEC [ccu].[Sp_UPD_Asistencia_Cursos_Cabecera_Estado] ?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function mostrarHistorialAsistencia($id)
    {

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Asistencia_Cursos_Historial] ?',[$id]);
       
        return response()->json($dataResult);
    }

    public function mostrarResumenAsistencia($id)
    {

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Asistencia_Cursos_Resumen] ?',[$id]);
       
        return response()->json($dataResult);
    }

    public function obtenerUnidadNotas($id)
    {

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Notas_Estudiantes_Unidad] ?',[$id]);
       
        return response()->json($dataResult);   
    }

    public function ingresarUnidadNotas(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iUnidadAcademicaId,
            $request->iCargasHorariasId,
            $request->iHorariosId,
            $request->iDocenteId,
            $request->iPersId,
            $request->iControlCicloAcad,
            $request->iFilId,
            $request->iCarreraId,
            $request->cCodigoCurso,
            $request->iSeccionId,
            auth()->user()->iCredId
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_INS_Notas_Estudiante_Cabecera] ?,?,?,?,?,?,?,?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function obtenerUnidadCurso($id)
    {

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Unidad_Academica] ?',[$id]);
       
        return response()->json($dataResult);
    }

    public function ingresarUnidadCurso(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iCargasHorariasId,
            $request->iHorariosId,
            $request->iControlCicloAcad,
            $request->iCarreraId,
            $request->iFilId,
            $request->cFilSigla,
            $request->cCodigoCurso,
            $request->iSeccionId,
            $request->iCursoActividadId,
            auth()->user()->iCredId
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_INS_Unidad_Academica] ?,?,?,?,?,?,?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function editarNotasUnidad(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->NotasDetalleId,
            $request->NotaConceptual,
            $request->NotaProcedimental,
            $request->NotaActitudinal,
            auth()->user()->iCredId
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_UPD_Notas_Estudiante] ?,?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function cambiarEstadoUnidad(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iUnidadAcademicaId,
            $request->iHorariosid,
            $request->bEstado,
            auth()->user()->iCredId
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_UPD_Notas_Estado] ?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function obtenerPromedioFinal($id)
    {

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Notas_Consolidado_Curso] ?',[$id]);
       
        return response()->json($dataResult);
    }

    public function verificarEstadoUnidad($id)
    {

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Unidad_Estado] ?',[$id]);
       
        return response()->json($dataResult);
    }

    public function savSilabo(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iControlCicloAcad,
	        $request->iCarreraId,
	        $request->iFilId,
	        $request->iDocenteId,
	        $request->iPersId,
	        $request->cCodigoCurso,
	        $request->iSeccionId,
	        $request->iCursoActividadId,
	        $request->cCursoDsc,
	        $request->iCreditos,
	        $request->iHrsSemanales,
	        $request->iHrsSemestrales,
	        $request->cCiclo,
	        $request->cPreRequi,
	        $request->cObjetivoGral,
	        $request->cAutor,
	        $request->cRevisa,
	        $request->cEvaluacion,
	        $request->dtFecApro,
	        $request->iEstadoSilabo,
            auth()->user()->iCredId,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_INS_Silabo] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function savBibliografia(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iSilaboId,
            $request->iBiblioTipId,
            $request->cAutor,
            $request->cTittulo,
            $request->iAnio,
            $request->cEditorial,
            $request->cPais,
            $request->iBienId,
            auth()->user()->iCredId,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_INS_Silabo_bibliografia] ?,?,?,?,?,?,?,?,?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }
    public function savContenido(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iSilaboId,
	        $request->iSilaUniId,
	        $request->iObjetivoEspId,
	        $request->cContenido,
	        $request->cMaterial,
	        $request->cInstEvaluacion,
	        $request->iHrsTP,
            auth()->user()->iCredId,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_INS_Silabo_contenido] ?,?,?,?,?,?,?,?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }
    public function savUnidad(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iSilaboId,
	        $request->cDesSilaUni,
            auth()->user()->iCredId,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_INS_Silabo_unidad] ?,?,?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }
    public function savEvaluacion(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iSilaboId,
	        $request->iTipEvaId,
	        $request->cDesEvaluacion,
	        $request->iPonderacion,
            auth()->user()->iCredId,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_INS_Silabo_evaluacion] ?,?,?,?,?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }
    public function savMetodologia(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iSilaboId,
	        $request->cMetodologia,
            auth()->user()->iCredId,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_INS_Silabo_metodologia] ?,?,?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }
    public function savObjetivoEspecifico(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iSilaboId,
	        $request->cObjetivoEsp,
            auth()->user()->iCredId,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_INS_Silabo_objetivo_especifico] ?,?,?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }
    public function obtenerSilabo(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iControlCicloAcad,
            $request->iFilId,
            $request->iCarreraId,
            $request->iPersId,
            $request->cCodigoCurso,
            $request->iSeccionId
        ];
        
        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Silabo] ?,?,?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function obtenerObjetivoEspecifico($id)
    {

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Silabo_Objetivo_Especifico] ?',[$id]);
       
        return response()->json($dataResult);
    }

    public function updateSilabo(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iSilaboId,
            $request->iControlCicloAcad,
	        $request->iCarreraId,
	        $request->iFilId,
	        $request->iDocenteId,
	        $request->iPersId,
	        $request->cCodigoCurso,
	        $request->iSeccionId,
	        $request->iCursoActividadId,
	        $request->cCursoDsc,
	        $request->iCreditos,
	        $request->iHrsSemanales,
	        $request->iHrsSemestrales,
	        $request->cCiclo,
	        $request->cPreRequi,
	        $request->cObjetivoGral,
	        $request->cAutor,
	        $request->cRevisa,
	        $request->cEvaluacion,
	        $request->dtFecApro,
	        $request->iEstadoSilabo,
            auth()->user()->iCredId,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_UPD_Silabo] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function updateObjetivoEspecifico(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iObjetivoEspId,
	        $request->iSilaboId,
            $request->cObjetivoEsp,
            auth()->user()->iCredId,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];
        
        $dataResult = DB::select('EXEC [ccu].[Sp_UPD_Silabo_objetivo_especifico] ?,?,?,?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function deleteObjetivoEspecifico(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iObjetivoEspId,
            auth()->user()->iCredId,
        ];
        
        $dataResult = DB::select('EXEC [ccu].[Sp_DEL_Silabo_Objetivo_Especifico] ?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function obtenerUnidad($id)
    {

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Silabo_Unidad] ?',[$id]);
       
        return response()->json($dataResult);
    }
    
    public function obtenerContenido($id)
    {

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Silabo_Contenido] ?',[$id]);
       
        return response()->json($dataResult);
    }

    public function deleteContenido(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iSilaContId,
            auth()->user()->iCredId,
        ];
        
        $dataResult = DB::select('EXEC [ccu].[Sp_DEL_Silabo_Contenido] ?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function updateContenido(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iSilaContId,
            $request->iSilaboId,
	        $request->iSilaUniId,
	        $request->iObjetivoEspId,
	        $request->cContenido,
	        $request->cMaterial,
	        $request->cInstEvaluacion,
	        $request->iHrsTP,
            auth()->user()->iCredId,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_UPD_Silabo_contenido] ?,?,?,?,?,?,?,?,?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function updateMetodologia(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iSilaMetoId,
	        $request->iSilaboId,
	        $request->cMetodologia,
            auth()->user()->iCredId,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];
        
        $dataResult = DB::select('EXEC [ccu].[Sp_UPD_Silabo_metodologia] ?,?,?,?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function obtenerMetodologia($id)
    {

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Silabo_Metodologia] ?',[$id]);
       
        return response()->json($dataResult);
    }

    public function obtenerBibliografia($id)
    {

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Silabo_Bibliografia] ?',[$id]);
       
        return response()->json($dataResult);
    }

    public function deleteBibliografia(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iSilaBiblioId,
            auth()->user()->iCredId,
        ];
        
        $dataResult = DB::select('EXEC [ccu].[Sp_DEL_Silabo_Bibliografia] ?,?',$parameters);
       
        return response()->json($dataResult);
    }
    public function updateBibliografia(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iSilaBiblioId,
            $request->iSilaboId,
            $request->iBiblioTipId,
            $request->cAutor,
            $request->cTittulo,
            $request->iAnio,
            $request->cEditorial,
            $request->cPais,
            $request->iBienId,
            auth()->user()->iCredId,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_UPD_Silabo_bibliografia] ?,?,?,?,?,?,?,?,?,?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }
}