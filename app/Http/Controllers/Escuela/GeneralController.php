<?php

namespace App\Http\Controllers\Escuela;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralController extends Controller
{
    public function getAsignaturas($carreraId, $curricula, $ciclo)
    {
        $data = DB::select('exec [ura].[Sp_ESCU_SEL_asignaturasXcCurricDetCicloCursoXiCarreraIdXiCurricId] ?, ?, ?', array( $ciclo, $carreraId, $curricula));

        return response()->json( $data );
    }
    public function getAulas($iCarrrera, $iFil, $cicloAcad){
        $data = DB::select('select * from  ura.tipos_aulas');
        $resAulas = DB::select('exec [ura].[SP_SEL_aulasxiCarreraId] ?, ?, ?', array( $iCarrrera, $iFil, $cicloAcad));

        return response()->json( ['tipo'=> $data, 'aulas'=> $resAulas] );
    }
    public function saveUpAulas(Request $request){
        $data = [
            $request->iAulaCod, 
            $request->iCarreraId, 
            $request->iTiposAulasId, 
            $request->iFilId, 
            $request->cAulasDesc, 
            $request->cAulasOrden,
            $request->nAulasAforo, 
            $request->bAulaActivo ?? 1, 
            $request->iAulaPiso, 

            auth()->user()->cCredUsuario,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];
        $result = DB::select('exec [ura].[Sp_INS_UPD_aulas] ?,?,?,?,?,?,?,?,?   ,?,?,?,?', $data);
        return response()->json( $result );
    }
    public function obtenerSemestres()
    {
        $data = \DB::table('ura.controles')->orderBy('iControlCicloAcad', 'desc')->get();

        return response()->json( $data );
    }

}
