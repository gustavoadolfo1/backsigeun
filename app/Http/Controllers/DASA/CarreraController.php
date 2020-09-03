<?php

namespace App\Http\Controllers\DASA;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UraMatriculaCarreraAutorizacion;
use App\GrlFilial;

class CarreraController extends Controller
{
    public function obtenerCarrerasAutorizaciones($cicloAcad)
    {
        //$cicloVigente = UraControlCicloAcademico::where('iControlEstado', 1)->first();
        $carrerasAutorizaciones = UraMatriculaCarreraAutorizacion::where('iControlCicloAcad', $cicloAcad)->with('carrera')->get();

        $filiales = GrlFilial::select('iFilId', 'cFilDescripcion', 'bFilPrincipal')->with('carreras')->get();

        foreach ($filiales as $filial) {
            foreach ($filial->carreras as $carrera) {
                $carrera->bAperturado = 0;
                $carrera->store = true;
                foreach ($carrerasAutorizaciones as $key => $carreraAutorizacion) {
                    if ($carreraAutorizacion->iFilId == $filial->iFilId && $carreraAutorizacion->iCarreraId == $carrera->iCarreraId) {
                        $carrera->bAperturado = (int)$carreraAutorizacion->bAperturado;
                        $carrera->store = false;
                        unset($carrerasAutorizaciones[$key]);
                        break;
                    }
                }
            }
        }

        return response()->json( $filiales );
    }

    public function guardarEstadoCheckEscuela(Request $request)
    {
        $this->validate(
            $request, 
            [
                'iControlCicloAcad' => 'required|integer',
                'iCarreraId' => 'required|integer',
                'iFilId' => 'required|integer',
                'bAperturado' => 'required|boolean',
            ], 
            [
                'iControlCicloAcad.required' => 'Hubo un problema al obtener información del curso.',
                'iCarreraId.required' => 'Hubo un problema al obtener información del Checkbox.',
                'iFilId.required' => 'Hubo un problema al obtener información del Checkbox.',
                'bAperturado.required' => 'Hubo un problema al obtener información del Checkbox.',
            ]
        );

        $parametros = [
            $request->iControlCicloAcad,
            $request->iCarreraId,
            $request->iFilId,
            $request->bAperturado,
            auth()->user()->cCredUsuario,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];

        try {
            $queryResult = \DB::select("EXEC [ura].[Sp_DASA_INS_UPD_matriculasCarrerasAutorizaciones] ?, ?, ?, ?, ?, ?, ?, ?", $parametros);

            $response = ['validated' => true, 'mensaje' => 'Se cambió el estado de la carrera exitosamente.', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;

        } catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }
}
