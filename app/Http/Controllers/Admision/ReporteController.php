<?php

namespace App\Http\Controllers\Admision;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function getInscritos($fil, $filtro, $modalidad, $lugar, $carrera, $sexo, $modaCod = '0', $tipo, Request $request)
    {
        $carreraFilial = explode('-', $carrera);

        $data = DB::select('exec adm.SP_SEL_inscritosOnline ?, ?, ?, ?, ?, ?, ?, ?', [ $fil, $filtro, $modalidad, $lugar, $carreraFilial[0], $carreraFilial[1], $sexo, $modaCod ]);
       
        return ExportableController::reporteInscritos($data, $tipo, $request->all());
    }

    public function getRecaudacionModalidadReporte($proceso, $tipoReturn, $tipo)
    {
        $data = DB::select("exec [adm].[SP_SEL_ingresosAdmision] ?", array($proceso));

        if ($tipoReturn == 'json') {
            return response()->json($data);
        } else {
            return ExportableController::reporteRecaudacionModalidad($data, $proceso, $tipo);
        }
    }

    public function getRecaudacionModalidadReporteDet( Request $request, $tipoReturn, $tipo )
    {
        $data = DB::select("exec [adm].[SP_SEL_ingresosAdmisionDetalles] ?, ?", [ $request->proceso, $request->modalidadCod ]);

        if ($tipoReturn == 'json') {
            return response()->json($data);
        } else {
            return ExportableController::reporteRecaudacionModalidadDet($data, $request->all(), $tipo);
        }
    }

    public function getRecaudacionEscuelaReporte($proceso, $tipoReturn, $tipo)
    {
        $data = DB::select("exec [adm].[SP_SEL_ingresosAdmisionCarreraGestion] ?", array($proceso));

        if ($tipoReturn == 'json') {
            return response()->json($data);
        } else {
            return ExportableController::reporteRecaudacionEscuela($data, $proceso, $tipo);
        }
    }
    
    public function getAsistenciaReporte($proceso, $modalidad, $filial, $tipoReturn, $tipo)
    {
        $data = DB::select("exec [adm].[SP_SEL_asistenciaExamenXiCicloControlXiTipoModalidadIdXiFilId] ?, ?, ?", array($proceso, $modalidad, $filial));

        if ($tipoReturn == 'json') {
            return response()->json($data);
        } else {
            return ExportableController::reporteAsistenciaExamen($data, $proceso, $tipo);
        }
    }
}
