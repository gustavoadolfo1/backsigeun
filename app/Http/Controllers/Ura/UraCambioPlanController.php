<?php

namespace App\Http\Controllers\Ura;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class UraCambioPlanController extends Controller
{
    public function cambioPlan($codigo,$carrera,$plan)
    {
        try {
        $cambio =
            DB::select(
                'EXEC ura.Sp_ESTUD_SEL_Cambio_Plan_Estudio ?,?,?',
                [$codigo, $carrera, $plan]
            );

            } catch (\Exception $e) {
            $cambio = ['mensaje' => substr($e->errorInfo[2] ?? '', 54), 'code' => $e->getCode(), 'exception' => $e];

        }
        return response()->json($cambio);

    }

    public function cambioPlanN($codigo,$carrera,$plan)
    {
        try {
        $cambio =
            DB::select(
                'EXEC ura.Sp_ESTUD_SEL_Cambio_Plan_Estudio_1 ?,?,?',
                [$codigo, $carrera, $plan]
            );

        } catch (\Exception $e) {
            $cambio = ['mensaje' => substr($e->errorInfo[2] ?? '', 54), 'code' => $e->getCode(), 'exception' => $e];

        }
        return response()->json($cambio);

    }

    public function cambioPlanActiva($codigo,$carrera,$plan)
    {
        try {

            $activa =
            DB::select(
                'EXEC ura.Sp_ESTUD_SEL_Cambio_Plan_Estudio_Activa ?,?,?',
                [$codigo, $carrera, $plan]
            );

        } catch (\Exception $e) {

            $activa = ['mensaje' => substr($e->errorInfo[2] ?? '', 54), 'code' => $e->getCode(), 'exception' => $e];

        }
        return response()->json($activa);

    }

}
