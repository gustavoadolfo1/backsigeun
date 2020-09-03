<?php

namespace App\Http\Controllers\AulaVirtual;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Hashids\Hashids;

use DB;

class MonitoreoController extends Controller
{
    public function getListadoCursosMonitoreo($carrFilId, $curricID)
    {
        $matriculas = DB::select('exec [aula].[Sp_SEL_listadoCursosDocenteXiCarrFilIdXiCurricId] ?, ?', array($carrFilId, $curricID));

        $hashids = new Hashids('SIGEUN UNAM', 15);

        foreach ($matriculas as $row) {
            $row->hashedId = $hashids->encode($row->iCurricCursoId, $row->iSeccionId, $row->iFilId, $row->iDocenteId, $row->iControlCicloAcad);
        }

        return response()->json($matriculas);
    }
}