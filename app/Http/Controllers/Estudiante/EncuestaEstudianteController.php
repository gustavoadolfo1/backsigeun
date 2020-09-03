<?php

namespace App\Http\Controllers\Estudiante;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class EncuestaEstudianteController extends Controller
{


    public function ConsultarEncuesta()
    {
        $encuesta1 = \DB::select('EXEC ura.Sp_EncuestaEstudiante_CRUD  ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', array('TEMA', '', '', '', '', '', '', '', '', '', '', '', ''));
        $encuesta2 = \DB::select('EXEC ura.Sp_EncuestaEstudiante_CRUD  ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', array('PREGUNTA', '', '', '', '', '', '', '', '', '', '', '', ''));
        $encuesta3 = \DB::select('EXEC ura.Sp_EncuestaEstudiante_CRUD  ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', array('OPCION', '', '', '', '', '', '', '', '', '', '', '', ''));

        $estudianteEncuesta = [];
        $pregunta = [];
        $opcion = [];
        foreach ($encuesta1 as $index => $tema) {

            $estudianteEncuesta[$index] = $tema;
            $estudianteEncuesta[$index]->pregunta = [];
        }

        foreach ($encuesta2 as $index => $pregunta) {
            $pregunta->opcion = [];
        }

        for ($i = 0; $i < count($encuesta2); $i++) {
            $q = 0;
            foreach ($encuesta3 as $key => $opc) {
                if ($encuesta2[$i]->iEncPregId == $opc->iEncPregId) {
                    $encuesta2[$i]->opcion[$q] = $opc;
                    $q++;
                }
            }
        }

        for ($i = 0; $i < count($estudianteEncuesta); $i++) {
            $p = 0;
            foreach ($encuesta2 as $key => $preg) {
                if ($estudianteEncuesta[$i]->iEncTemaId == $preg->iEncTemaId) {
                    $estudianteEncuesta[$i]->pregunta[$p] = $preg;
                    $p++;
                }
            }
        }



        return response()->json($estudianteEncuesta);
    }

    public function VerificarEncuesta($iControlCicloAcad, $iPersId)
    {

        $verificar = \DB::select('EXEC ura.Sp_EncuestaEstudiante_CRUD  ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', array('VERIFICAR',  $iPersId, $iControlCicloAcad, '', '', '', '', '', '', '', '', '', ''));

        return response()->json($verificar);
    }

    public function EncuestaFinalizado($iControlCicloAcad, $iPersId)
    {

        $finalizar = \DB::select('EXEC ura.Sp_EncuestaEstudiante_CRUD  ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', array('FINALIZAR',  $iPersId, $iControlCicloAcad, '', '', '', '', '', '', '', '', '', ''));

        return response()->json($finalizar);
    }

    public function EncuestaBuscar($iControlCicloAcad, $iPersId)
    {

        $buscar = \DB::select('EXEC ura.Sp_EncuestaEstudiante_CRUD  ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', array('BUSCAR', $iPersId, $iControlCicloAcad, '', '', '', '', '', '', '', '', '', ''));
        //$verificar = \DB::select('EXEC ura.Sp_EncuestaEstudiante_CRUD  ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', array('VERIFICAR', '', '', '', '', '', '', '', '', '', '', '', ''));

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


            $data = \DB::select('EXEC ura.Sp_EncuestaEstudiante_CRUD  ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros);

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


            $data = \DB::select('EXEC ura.Sp_EncuestaEstudiante_CRUD  ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros);

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


    /**
     * Encuesta Satisfaccion Capacitacion
     */

    public function verificarEstadoEncuesta($codUniv)
    {
        $data = \DB::select('EXEC ura.Sp_VIDEOCONF_SEL_verificaEncuestaXcEstudCodUniv ?', [$codUniv]);

        return response()->json( $data );
    }

    public function getEncuesta(Request $request, $encuesta)
    {
        switch ($encuesta) {
            case 'encuesta_conferencia':
                    $encuesta = \DB::select('EXEC ura.Sp_VIDEOCONF_SEL_encuestaXcEstudCodUniv ?', [$request->codUniv]);
                break;
            
            default:
                # code...
                break;
        }

        return response()->json( $encuesta );
    }

    public function guardarRespuestaEncuesta(Request $request)
    {
        #exec ura.Sp_VIDEOCONF_INS_UPD_encuestaPregunta @iEncVideoId int, @cEstudCodUniv varchar(25), @iEncVideoPregId int, @iEncVideoOpId int, @cEncVideoPregResp varchar(max)

        try {

            $params = [
                $request->iEncVideoId,
                $request->cEstudCodUniv,
                $request->iEncVideoPregId ?? NULL,
                $request->iEncVideoOpId ?? NULL,
                $request->cEncVideoPregResp ?? NULL
            ];

            $queryResult = \DB::select('exec ura.Sp_VIDEOCONF_INS_UPD_encuestaPregunta ?, ?, ?, ?, ?', $params);

            if ($queryResult[0]->iResult == 1) {
                $response = ['validated' => true, 'mensaje' => 'Guardado', 'queryResult' => $queryResult[0] ];
                $codeResponse = 200;
            } else {
                $response = ['validated' => true, 'mensaje' => 'No se pudo guardar'];
                $codeResponse = 500;
            }
        } catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }

        return response()->json( $response, $codeResponse );
    }
}
