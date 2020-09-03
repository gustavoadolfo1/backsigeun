<?php

namespace App\Http\Controllers\ceid;

use Illuminate\Support\Facades\Input;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ceid\emailController;

class GeneralController extends Controller
{
    public function obtenerRitmosCiclos($moduloid)
    {
        try {
            $ritmos = \DB::select('SELECT * FROM acad.modProg_ritmos mr INNER JOIN acad.ritmos r
		ON r.iRitmoId = mr.iRitmoId WHERE mr.iModProgId = ?', [$moduloid]);
            $response = ['validated' => true, 'message' => 'cicloRitmo obtendios correctamante', 'data' => $ritmos];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->messag, 'data' => []];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function obtenerDiasDisponiblesXFilialIdModIdRItmoId()
    {
        $parameters = [
            Input::get('filial'),
            Input::get('moduloProg'),
            Input::get('ritmo'),
        ];
        try {
            $dias = \DB::select('EXEC [acad].[Sp_CEID_SEL_diasDisponiblesXFilialIdCarreraIdRItmoId] ?, ?, ?', $parameters);
            $response = ['validated'=> true, 'message' => 'se obtuvo los dias correctamente', 'data' => $dias];
            $responseCode = 200;
        } catch (\Exception $e)  {
          $response = ['valdiated' => false, 'message' => $e->getMessage(), 'data' => []];
          $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function handleEmail(Request $request)
    {
        $parameters = [
            $request->id,
            $iProgramasAcadId = 4
        ];
        $data = \DB::select('[acad].[SP_SEL_emailDataXiPreInscripId] ?, ?', $parameters)[0];

        $email = new emailController();

        return $email->sendEmailPreinscrito($data);
    }
}
