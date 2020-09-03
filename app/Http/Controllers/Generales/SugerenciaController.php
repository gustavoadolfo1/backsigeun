<?php

namespace App\Http\Controllers\Generales;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SugerenciaController extends Controller
{
    public function guardarSugerencia(Request $request)
    {
        $this->validate(
            $request, 
            [
                'moduloId' => 'required|integer',
                'tipoObs' => 'required',
                'comentarioObs' => 'required',
            ], 
            [
                'moduloId.required' => 'Hubo un problema al obtener información del módulo.',
                'tipoObs.required' => 'Debe seleccionar una opción.',
                'comentarioObs.required' => 'Detalle el problema, por favor.',
            ]
        );

        $parametros = [
            $request->moduloId,
            $request->tipoObs,
            $request->comentarioObs,
            auth()->user()->cCredUsuario,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];

        try {
            $queryResult = \DB::select("EXEC [grl].[Sp_GRAL_INS_observacionesSistema] ?, ?, ?, ?, ?, ?, ?", $parametros);

            $response = ['validated' => true, 'mensaje' => 'Se ha guardado correctamente.', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;

        } catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }
}
