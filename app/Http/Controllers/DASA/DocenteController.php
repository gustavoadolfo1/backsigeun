<?php

namespace App\Http\Controllers\DASA;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DocenteController extends Controller
{
    public function obtenerActaDocente($cargaAcadId, $cicloAcad)
    {
        $result = \DB::select("exec [ura].[Sp_DASA_SEL_actasDocente] ?, ?", array( $cargaAcadId, $cicloAcad ));

        return response()->json( $result );
    }

    /**
     * RESETAR CONTRASEÑA
     * 
     */
    public function resetearContraseniaDocente(Request $request)
    {
        $this->validate(
            $request, 
            [
                'persDocumento' => 'required',
            ], 
            [
                'persDocumento.required' => 'Hubo un problema al obtener el Docente.',
            ]
        );
        $parametros = [
            $request->persDocumento,
            auth()->user()->cCredUsuario,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];
        try {
            $queryResult = \DB::select('exec [ura].[Sp_GRAL_UPD_reseteoContrasenia] ?, ?, ?, ?, ?', $parametros );
            $response = ['validated' => true, 'mensaje' => 'Se reestableció la contraseña correctamente.', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\Exception $e) {
            
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }
}

