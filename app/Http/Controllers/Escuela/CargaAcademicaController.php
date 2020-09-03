<?php

namespace App\Http\Controllers\Escuela;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;

class CargaAcademicaController extends Controller
{
    public function eliminarCargaAcademica($cargaId)
    {
        try {
            DB::table('ura.cargas_horarias')->where('iCargaHId', $cargaId)->delete();
            $res = \DB::select('exec [ura].[Sp_DOCE_DEL_AsistenciaXCambioCargaHoraria] ?', array( $cargaId ));
            $response = ['mensaje' => 'Se eliminó la carga académica.'];
            
            $codeResponse = 200;

        } catch (\Exception $e) {
            $response = ['mensaje' => substr($e->errorInfo[2], 54)];

            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }
}
