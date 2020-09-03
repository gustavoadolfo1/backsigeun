<?php

namespace App\Http\Controllers\CCU;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeporteArteController extends Controller
{
    public function obtenerCursos()
    {

        try {
            $cursos = \DB::select('EXEC [ccu].[Sp_SEL_Combo_Curso_Deporte_Arte]');
            $response = ['validated'=> true, 'message' => 'se obtuvo los semestres correctamente', 'data' => $cursos];
            $responseCode = 200;
        } catch (\Exception $e)  {
          $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
          $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function buscarEstudianteSemestreCarrera(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->cBusqueda,
            $request->iControlCicloAcad,
            $request->iCarreraId
        ];
        
        

        $dataResult = DB::select('EXEC [ccu].[Sp_GRAL_SEL_Estudiantes_Matricula_Carrera] ?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function ingresarEstudianteArteDeporte(Request $request)
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
        
        

        $dataResult = DB::select('EXEC [ccu].[Sp_INS_Estudiante_Ficha_Matricula_Arte_Deporte] ?,?,?,?,?,?,?,?,?,?,?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function agregarSeccionEstudiante(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->iMatriculaDetalleId,
            $request->iHorariasId,
            $request->cEstudCodUniv,
            $request->iSeccionid,
            auth()->user()->iCredId
        ];
        
        

        $dataResult = DB::select('EXEC [ccu].[Sp_UPD_Estudiante_Seccion] ?,?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

}