<?php

namespace App\Http\Controllers\Inv;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



use App\UraCurriculaCursoDetalle;
use Illuminate\Support\Facades\DB;
use App\Packages\Maatwebsite\Maatwebsite\Excel\Facades\Excel;
use PHPExcel;
use PDF;
use App\Http\Controllers\Tram\TramitesController;

class ReporteController extends Controller
{
    //
    public function getRecojoInfoMINEDU($tipoReturn, $tipo = null)
    {
        $data = DB::select("exec [ura].[Sp_DASA_REP_recojoInformacion_MINEDU]");

        $carreras = DB::table('ura.carreras')->select('iCarreraId', 'cCarreraDsc')->where('iProgramasAcadId', 1)->get();

        foreach ($data as $row) {
            foreach ($carreras as $carrera) {
                if ($row->iCarreraId == $carrera->iCarreraId) {
                    $carrera->data[] = $row;
                    break;
                }
            }
        }

        if ($tipoReturn == 'json') {
            return response()->json($carreras);
        } else {
            return ExportReporteController::getRecojoInfoMINEDU($carreras, $tipo);
        }
    }
}
