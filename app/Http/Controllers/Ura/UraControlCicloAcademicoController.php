<?php

namespace App\Http\Controllers\Ura;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\UraControlCicloAcademico;

class UraControlCicloAcademicoController extends Controller
{
    /**
     * Obtiene el ciclo académico activo
     */
    public function obtenerCicloAcademicoActivo()
    {
        $data = DB::select('exec ura.[Sp_GRAL_cicloAcademicoActivo]');

        return response()->json( $data[0] );
    }
    public function allCiclos(){
        $data = DB::table('ura.controles')->orderBy('iControlCicloAcad', 'desc')->distinct()->get();
        return response()->json( $data );
    }
    public function editCiclos(Request $request){
        $data = [
            $request->iControlCicloAcad,
            $request->iControlEstado,
        ];
        $result = DB::select('exec [ura].[Sp_DASA_INS_UPD_controles] ?,?' , $data );
        return response()->json( $result );
    }
}