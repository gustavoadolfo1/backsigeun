<?php

namespace App\Http\Controllers\Cotizaciones;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CuadroComparativoController extends Controller
{
    public function insertarCuadroComparativo(Request $request)
    {
        $parametros = [
            $request->pedidoId,
            $request->cuadroObs,
            auth()->user()->iCredId,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];
        try {
            $queryResult = \DB::select('EXEC log.Sp_INS_cuadros_comparativos  ?, ?, ?, ?, ?, ?', $parametros );

            $response = ['validated' => true, 'mensaje' => 'Se creó el cuadro comparativo.', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\Exception $e) {
            
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }
}
