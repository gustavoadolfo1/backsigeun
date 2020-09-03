<?php

namespace App\Http\Controllers\CCTIC;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class PlanTrabajoController extends Controller
{
    public function obtenerUltimoPlan()
    {
        try {

            $plan = DB::select('exec [acad].[SP_SEL_ultimoPlanTrabajo]');

            $response = ['validated' => true, 'data' => $plan[0]];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => []];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }
}
