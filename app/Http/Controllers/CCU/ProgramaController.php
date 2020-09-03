<?php

namespace App\Http\Controllers\CCU;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProgramaController extends Controller
{
    public function crearPrograma(Request $request)
    {
        $dataResult = [];
        
        $parameters = [
            isset($request->cNombre)?$request->cNombre:"", //es para que el campo pueda llegar vacio
            $request->cDocAprobacion,
            auth()->user()->iCredId,
            $request->iActividadId,
            $request->cCodigoCurso
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_INS_Curso_Actividad] ?,?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function obtenerProgramas()
    {
        
        try {
            $programas = \DB::select('EXEC [ccu].[Sp_SEL_Combo_Programa]');
            $response = ['validated'=> true, 'message' => 'se obtuvo los programas correctamente', 'data' => $programas];
            $responseCode = 200;
        } catch (\Exception $e)  {
          $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
          $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function obtenerProgramaId($id)
    {
        

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_CursoActividadId] ?',[$id]);
       
        return response()->json($dataResult);
    }
    public function mostrarPrograma(Request $request)
    {
        $dataResult = [];
        
        $parameters = [
            $request->iControlCicloAcad,
            $request->iActividadId,
            $request->iFilId
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Actividad_Cursos_Semestre] ?,?,?',$parameters);
       
        return response()->json($dataResult);
    }
    public function agregarEstudiantePrograma(Request $request) // para insertar estudiante en el plan de trabajo
    {
        $dataResult = [];      
        $parameters = [
            $request->iEstudId,
            $request->cEstudCodUniv,
            $request->iCarreraId,
            $request->iFilId,
            $request->cFilSigla,
            $request->iControlCicloAcad,
            $request->dFechaMatricula,
            $request->cCicloAcademicoEstudiante,
            $request->iHorariosId,
            $request->iCursoActividadId,
            $request->cCodigoCurso,
            $request->iSeccionId,
            $request->iActividadId,
            auth()->user()->iCredId
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_INS_Programa_Ficha_Matricula] ?,?,?,?,?,?,?,?,?,?,?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }
    public function mostrarEstudiantePrograma(Request $request)
    {
        $dataResult = [];
        
        $parameters = [
            $request->iControlCicloAcad,
            $request->iFilId,
            $request->iHorarioId
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Programa_Estudiantes_Matriculados] ?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function eliminarEstudiantePrograma(Request $request)
    {
        $dataResult = [];
        
        $parameters = [
            $request->iMatriculaDetalleId,
            auth()->user()->iCredId
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_DEL_Curso_Matricula_Estudiante] ?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function crearFolio(Request $request)
    {
        $dataResult = [];   
        
        $parameters = [
            $request->iCarreraId,
            $request->iDependenciaId,
            $request->dFechaRegistro,
            $request->cReferencia,
            auth()->user()->iCredId
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_INS_Compendio_por_Dependencia] ?,?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function editarHorasPS(Request $request)
    {
        $dataResult = [];   
        
        $parameters = [
            $request->iCompendioDetalleId,
            $request->iEstudianteId,
            $request->nHorasCurso,
            $request->dFechaRegistroDetalle,
            auth()->user()->iCredId
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_UPD_Compendio_Detalle_Proyeccion_Social] ?,?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function cambiarEstadoEstPrograma(Request $request)
    {
        $dataResult = [];   
        
        $parameters = [
            $request->iMatriculaDetalleId,
            auth()->user()->iCredId
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_UPD_Curso_Estudiante_Estado] ?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function cerrarPrograma(Request $request)
    {
        $dataResult = [];   
        
        $parameters = [
            $request->iHorariosid,
            $request->bEstado,
            auth()->user()->iCredId
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_UPD_Horas_Programa_Estado] ?,?,?',$parameters);
       
        return response()->json($dataResult);
    }


}
