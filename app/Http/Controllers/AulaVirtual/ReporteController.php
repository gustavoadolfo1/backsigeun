<?php

namespace App\Http\Controllers\AulaVirtual;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ZoomController;
use DB;
use Carbon\Carbon;

class ReporteController extends Controller
{
    public function getAsistentesReunion($iReunionProgId, $cTipoReturn, $cTipoArchivo = 'excel')
    {
        $dataZoom = DB::select('exec [aula].[Sp_SEL_seguridadTokenZoom]');

        $reunion = DB::table('aula.reunion_programacion as rp')
                    ->select(DB::raw('*, (select cCarreraDsc from ura.carreras where iCarreraId = rp.iCarreraId) as cCarreraDsc, (select cSeccionDsc from ura.secciones where iSeccionId = rp.iSeccionId) as cSeccionDsc'))
                    ->where('iReunionProgId', $iReunionProgId)->first();

        if ($reunion->iId && $reunion->iId > 0) {

            $zoom = new ZoomController($dataZoom[0]->cSegTokensJwtTtoken);
            $response = $zoom->getAsistentesMeeting($reunion->iId);

            if ($response['response']) {
                foreach ($response['response']->participants as $participante) {
                    $participante->duration = round(($participante->duration / 60), 1);
                    $participante->join_time = Carbon::parse($participante->join_time)->format('d-m-Y H:i:s');
                    $participante->leave_time = Carbon::parse($participante->leave_time)->format('d-m-Y H:i:s');
                }    
                
                if ($cTipoReturn == 'json') {
                    return response()->json($response);
                } else {
                    return ExportController::getArchivoReporteAsistentesReunion($response['response']->participants, $reunion, $cTipoArchivo);
                }
            } else {
                return response()->json(['mensaje' => $response['mensaje']], 500);
            }
        } else {
            return response()->json(['mensaje' => 'La reunión zoom consultada no existe o no se ha generado aún.'], 500);
        }
    }
}
