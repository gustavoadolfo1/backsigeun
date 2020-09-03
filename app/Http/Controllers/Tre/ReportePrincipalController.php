<?php

namespace App\Http\Controllers\Reporte;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ReportePrincipalController extends Controller
{
    /**
     * Obtiene el total de Identificaciones
     */
    public function getReportes($iRepId=NULL,$iUniEjeId=NULL,$iMenuId=NULL,$vcRepNombre=NULL,$vcRepAbrev=NULL,$vcRepCode=NULL,$siRepEstado=NULL,$vcSessKey=NULL,$vcTypeRecord=NULL,$vcTypeQuery=NULL,$vcOrderBy=NULL,$vcRecordLimit=NULL,$vcRecordStart=NULL)
    {
        $conceptos = \DB::select('exec grl.[reportes_sp_select] ?,?,?,?,?,?,?,?,?,?,?,?,?',array($iRepId,$iUniEjeId,$iMenuId,$vcRepNombre,$vcRepAbrev,$vcRepCode,$siRepEstado,$vcSessKey,$vcTypeRecord,$vcTypeQuery,$vcOrderBy,$vcRecordLimit,$vcRecordStart));

        return response()->json( $conceptos );
    }
    

}