<?php

namespace App\Http\Controllers\Generales;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TributoController extends Controller
{
    public function obtenerValorUIT() {
        $uit = DB::table('grl.tributos AS t')
            ->select(['cTribAbrev', 'nTribValImpt', 'dTribValFechaIni'])
            ->join('grl.tributos_valores AS tv', 't.iTribId', '=', 'tv.iTribId')
            ->where('cTribAbrev', 'UIT')
            ->orderByDesc('dTribValFechaIni')->first();

        return response()->json( $uit );
    }
}
