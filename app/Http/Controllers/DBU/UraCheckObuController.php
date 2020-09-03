<?php

namespace App\Http\Controllers\DBU;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UraCheckObuController extends Controller
{
    /**
     * Guarda o actualiza checks OBU
     * 
     * Mod: DBU - control
     */
    public function guardarActualizarChecksObu(Request $request)
    {

        $this->validate(
            $request, 
            [
                'modo' => 'required',
                'check' => 'required',
                'codUniv' => 'required',
                'cicloAcad' => 'required',

            ], 
            [
                'modo.required' => 'Hubo un problema al verificar el modo.',
                'check.required' => 'Hubo un problema al verificar el check.',
                'codUniv.required' => 'Hubo un problema al verificar el código universitario.',
                'cicloAcad.required' => 'Hubo un problema al verificar el ciclo académico.',
            ]
        );

        $parametros = [
            $request->modo,
            $request->check,
            $request->codUniv,
            $request->cicloAcad,
            'seguro',
            'user',
            //auth()->user()->cCredUsuario,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];

        try {
            $data = \DB::select('exec ura.Sp_OBU_INS_UPD_check ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros);

            $response = ['validated' => true, 'mensaje' => 'Cambios guardados exitosamente.', 'data' => $data];
            $codeResponse = 200;

        } catch (\Exception $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
            
            $codeResponse = 500; 
        }

        return response()->json($response, $codeResponse);

    }
}
