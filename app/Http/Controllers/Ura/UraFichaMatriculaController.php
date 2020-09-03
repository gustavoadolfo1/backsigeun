<?php

namespace App\Http\Controllers\Ura;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UraFichaMatricula;
use App\UraCurriculaCurso;

class UraFichaMatriculaController extends Controller
{
    /**
     * Obtiene las fichas de matrícula por estudiante
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerFichasMatriculasEstudiante($codigoEstudiante)
    {
        $cabeceras =  UraFichaMatricula::where('cEstudCodUniv', $codigoEstudiante)->where('iMatricEstado', 1)->join('ura.carreras', 'ura.ficha_matriculas.iCarreraId', '=', 'ura.carreras.iCarreraId')->orderBy('iControlCicloAcad', 'DESC')->get();  

        $detalles = \DB::select('exec [ura].[Sp_ESTUD_SEL_boletaNotasXcEstudCodUniv] ?', array($codigoEstudiante));
        $semestres = [];
        foreach ($cabeceras as $i => $cabecera) {
            $semestres[$i]['iControlCicloAcad'] = $cabecera->iControlCicloAcad;
            $semestres[$i]['ficha'] = $cabecera;

            $ura_ficha_matricula_detalles = [];
            foreach ($detalles as $key => $detalle) {
                if ($detalle->iMatricId == $cabecera->iMatricId) {
                    $detalle->ura_curricula_curso['cCurricCursoCod'] = $detalle->cCurricCursoCod;
                    $detalle->ura_curricula_curso['cCurricCursoDsc'] = $detalle->cCurricCursoDsc;
                    $ura_ficha_matricula_detalles[] = $detalle;
                }
            }
            $semestres[$i]['ficha']->ura_ficha_matricula_detalles = $ura_ficha_matricula_detalles;
        } 

        return response()->json( $semestres );
    }

    public function obtenerFichaMatriculaVigente($estudId, $cicloAcad)
    {
        $ficha =  UraFichaMatricula::select('ura.ficha_matriculas.iMatricId', 'ura.ficha_matriculas.iControlCicloAcad','ura.ficha_matriculas.cNumFicha', 'ura.ficha_matriculas.iEstudId','ura.ficha_matriculas.cEstudCodUniv', 'ura.ficha_matriculas.iCarreraId', 'ura.ficha_matriculas.iFilId', 'ura.ficha_matriculas.iCurricId', 'ura.curriculas.cCurricAnio', 'ura.carreras.cCarreraDsc', 'tre.ingresos.iDocSerie', 'tre.ingresos.iDocNro', 'tre.ingresos.dDocFecha', 'tre.ingresos.nIngImpt','ura.ficha_matriculas.iMatricEstado', 'ura.ficha_matriculas.dMatricFecha', 'ura.ficha_matriculas.cMatricObs', 'ura.ficha_matriculas.cMatricTipo', 'ura.ficha_matriculas.iTiposMatId', 'ura.ficha_matriculas.iMatricEstado')
        ->where([['ura.ficha_matriculas.iEstudId', $estudId], ['iMatricEstado', 1], ['cMatricEstadoFicha', 'A']])
        ->join('ura.carreras', 'ura.ficha_matriculas.iCarreraId', '=', 'ura.carreras.iCarreraId')
        ->join('ura.curriculas', 'ura.ficha_matriculas.iCurricid', '=', 'ura.curriculas.iCurricid')
        ->leftJoin('tre.ingresos', 'ura.ficha_matriculas.iReciboId', '=', 'tre.ingresos.iIngId')
        ->orderBy('ura.ficha_matriculas.iControlCicloAcad', 'DESC')
        ->get();

        return response()->json( $ficha );
    }

    public function obtenerTiposMatricula()
    {
        $data = \DB::select('exec [ura].[Sp_DASA_SEL_tiposMatriculas_TipoA]');

        return response()->json( $data );
    }
}
