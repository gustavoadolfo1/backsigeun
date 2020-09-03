<?php

namespace App\Http\Controllers\Escuela;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UraCurricula;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function getSelectsReportes($carreraId = null)
    {
        $semestres = DB::table('ura.controles')->orderBy('iControlCicloAcad', 'DESC')->get();
        $ciclos = DB::table('ura.curriculas_cursos_detalles')->select('cCurricDetCicloCurso as cicloKey', 'cCurricDetCicloCurso as ciclo')->distinct()->orderBy('cCurricDetCicloCurso')->get();
        $tiposMat = DB::table('ura.tipos_matriculas')->get();
        $carreras = DB::table('ura.carreras')->where('iProgramasAcadId', 1)->get();
        $curriculas = UraCurricula::all();
        $secciones = DB::table('ura.secciones')->select('iSeccionId', 'cSeccionDsc')->get();

        return response()->json( [ 'semestres' => $semestres, 'ciclos' => $ciclos, 'tiposMat' => $tiposMat, 'curriculas' => $curriculas, 'secciones' => $secciones, 'carreras' => $carreras ] );
    }

    public function obtenerMatriculadosGeneral(Request $request)
    {
        $data = \DB::select('exec [ura].[Sp_ESCU_REP_matriculadosXiControlCicloAcadXcMatricCicloXiNumMatriculaXiCondicion] ?, ?, ?, ?, ?', array( $request->semestre, $request->carreraId, $request->ciclo, $request->nMat, $request->tipoMat ));

        if ($request->tipoReturn == 'json') {
            return response()->json( $data );
        } else {
            return ExportReporteController::obtenerMatriculadosGeneral($data, $request->tipo, $request->all());
        }
    }

    public function obtenerMatriculadosPorCurso(Request $request)
    {
        $data = \DB::select("exec [ura].[Sp_ESCU_REP_matriculadosCursosXiControlCicloAcadXiCurricIdXcMatricCicloXcCurricCursoCodXiSeccionIdXiNumMatricula] ?, ?, ?, ?, ?, ?, ?", array( $request->semestre, $request->carreraId, $request->plan['iCurricId'], $request->ciclo, $request->curso['cCurricCursoCod'], $request->seccion['iSeccionId'], $request->nMat ));

        if ($request->tipoReturn == 'json') {
            return response()->json( $data );
        } else {
            return ExportReporteController::obtenerMatriculadosPorCurso($data, $request->tipo, $request->all());
        }   
    }

    public function obtenerIngresantesMatriculados(Request $request)
    {
        $data = \DB::select("exec [ura].[Sp_ESCU_REP_ingresantesMatriculadosXiControlCicloAcadXiCarreraIdXiCondicion] ?, ?, ?", array( $request->semestre, $request->carreraId, $request->tipoMat ));

        if ($request->tipoReturn == 'json') {
            return response()->json( $data );
        } else {
            return ExportReporteController::obtenerIngresantesMatriculados($data, $request->tipo, $request->all());
        }
    }
}
