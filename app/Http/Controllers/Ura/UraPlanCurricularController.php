<?php

namespace App\Http\Controllers\Ura;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\UraCurricula;

use App\Libraries\COM\CrystalReportConnector;

class UraPlanCurricularController extends Controller
{
    /**
     * Obtiene el plan curricular por estudiante
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerPlanCurricularEstudiante($codigoEstudiante)
    {
        $plan = \DB::select('exec ura.sp_selPlanEstudios_x_cCodEstudiante ?',array($codigoEstudiante));

        return response()->json( $plan );
    }

    /**
     * Obtiene los cursos de un plan curricular por Carrera
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerPlanCurricularCarrera($codigoCarrera)
    {
        $data = \DB::select('exec ura.sp_selPlanEstudios_x_iCarreraId ?',array($codigoCarrera));

        $curriculas = UraCurricula::all();
        
        foreach ($curriculas as $curricula) {
            $cursos = [];
            foreach ($data as $curso) {
                if ($curricula->iCurricId == $curso->iCurricId) {
                    $cursos[] = $curso;
                }
            }
            $curricula->cursos = $cursos;
        }

        return response()->json( $curriculas );
    }

    /**
     * Obtiene el historial de notas de un estudiante
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerRecordAcademicoEstudiante($codigoEstudiante)
    {
        $cabecera = \DB::select('exec ura.sp_sel_CabeceraHistorialAcademico_x_cEstudCodUniv ?',array($codigoEstudiante));

        $plan = \DB::select('exec ura.sp_selPlanEstudios_x_cCodEstudiante ?',array($codigoEstudiante));

        $iCarreraId = $plan[0]->iCarreraId;
        
        $max = 0;
        foreach ($plan as $i => $curso) {
            $notas = \DB::select('exec ura.sp_sel_NotasEstudiante_x_cEstudCodUniv_x_cCarreraCod_x_cCursoCod ?, ?, ?',array($codigoEstudiante, $iCarreraId, $curso->cCurricCursoCod));
            if (count($notas) > $max )
                $max = count($notas);

            $plan[$i]->notas = $notas;
        }

        $cabecera[0]->plan = $plan;
        $cabecera[0]->max = $max;
    
        return response()->json( $cabecera[0] );
    }

    public function generarReporte()
    {
        $conect = new CrystalReportConnector();
    }
}
