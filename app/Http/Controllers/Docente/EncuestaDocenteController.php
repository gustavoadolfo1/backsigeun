<?php

namespace App\Http\Controllers\Docente;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class EncuestaDocenteController extends Controller
{


    public function ConsultarEncuesta()
    {

        $encuesta = \DB::select('EXEC ura.Sp_EncuestaDocente_CRUD  ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', array('CONSULTAR', '', '', '', '', '', '', '', '', '', '', '', ''));
        //$verificar = \DB::select('EXEC ura.Sp_EncuestaDocente_CRUD  ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', array('VERIFICAR', '', '', '', '', '', '', '', '', '', '', '', ''));
            
        return response()->json($encuesta);
    }

    public function VerificarEncuesta( $iControlCicloAcad,$iPersId)
    {

        $verificar = \DB::select('EXEC ura.Sp_EncuestaDocente_CRUD  ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', array('VERIFICAR',  $iPersId, $iControlCicloAcad, '', '', '', '', '', '', '', '', '', ''));
            
        return response()->json($verificar);
    }

    public function EncuestaFinalizado( $iControlCicloAcad,$iPersId)
    {

        $finalizar = \DB::select('EXEC ura.Sp_EncuestaDocente_CRUD  ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', array('FINALIZAR',  $iPersId, $iControlCicloAcad, '', '', '', '', '', '', '', '', '', ''));
            
        return response()->json($finalizar);
    }

    public function EncuestaBuscar($iControlCicloAcad,$iPersId)
    {

        $buscar = \DB::select('EXEC ura.Sp_EncuestaDocente_CRUD  ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', array('BUSCAR', $iPersId, $iControlCicloAcad, '', '', '', '', '', '', '', '', '', ''));
        //$verificar = \DB::select('EXEC ura.Sp_EncuestaDocente_CRUD  ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', array('VERIFICAR', '', '', '', '', '', '', '', '', '', '', '', ''));
            
        return response()->json($buscar);
    }

    public function GuardarEncuesta(Request $request)
    {
        //EN PROCESO

        $validator = Validator::make(
            $request->all(),
            [
                'iControlCicloAcad' => 'required',
                'iPersId' => 'required',

            ],
            [
                'iControlCicloAcad' => 'Ingrese el semestre académico.',
                'iPersId' => 'Ingrese el Idenfiticador de la Persona.',

            ]
        );


        try {
            $parametros = array(
                'GUARDAR',
                $request->iPersId,
                $request->iControlCicloAcad,
                $request->iEncOpcId,
                $request->iEncPregId,
                $request->iEncTemaId,
                $request->cEncObs ?? null,
                $request->cEncExt ?? null,

                auth()->user()->cCredUsuario,
                gethostname(),
                $request->server->get('REMOTE_ADDR'),
                '1',
                'mac', //14

            );


            $data = \DB::select('EXEC ura.Sp_EncuestaDocente_CRUD  ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros);

            if ($data[0]->id >= 0) {

                if ($data[0]->id == 0) {
                    $response = ['validated' => true, 'mensaje' => 'Se guardó correctamente.'];
                    $codeResponse = 200;
                }

                if ($data[0]->id > 0) {
                    $response = ['validated' => true, 'mensaje' => 'Se actualizó correctamente.'];
                    $codeResponse = 200;
                }
            } else {
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar. Problema con la Conexión'];
                $codeResponse = 500;
            }
        } catch (\QueryException $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }

    public function RestaurarEncuesta(Request $request)
    {
        //EN PROCESO

        $validator = Validator::make(
            $request->all(),
            [
                'iControlCicloAcad' => 'required',
                'iPersId' => 'required',

            ],
            [
                'iControlCicloAcad' => 'Ingrese el semestre académico.',
                'iPersId' => 'Ingrese el Idenfiticador de la Persona.',

            ]
        );


        try {
            $parametros = array(
                'RESTAURAR',
                $request->iPersId,
                $request->iControlCicloAcad,
                $request->iEncOpcId ?? null,
                $request->iEncPregId ?? null,
                $request->iEncTemaId ?? null,
                $request->cEncObs ?? null,
                $request->cEncExt ?? null,

                auth()->user()->cCredUsuario,
                gethostname(),
                $request->server->get('REMOTE_ADDR'),
                '1',
                'mac', //14

            );


            $data = \DB::select('EXEC ura.Sp_EncuestaDocente_CRUD  ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros);

            if ($data[0]->id == -1) {

               
                    $response = ['validated' => true, 'mensaje' => 'Se eliminó correctamente.'];
                    $codeResponse = 200;
               

               
            } else {
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar. Problema con la Conexión'];
                $codeResponse = 500;
            }
        } catch (\QueryException $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
}
