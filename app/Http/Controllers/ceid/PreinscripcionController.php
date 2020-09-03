<?php

namespace App\Http\Controllers\ceid;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;


class PreinscripcionController extends Controller
{
    public function crearPreInscripcion(Request $request)
    {
        $modulos = $request->modulos;
        $modulosValid = [];
        foreach ($modulos as $key => $value) {
            $data = json_encode($value);
            $data = json_decode($data);
            $valid = $this->validPreinscripcion($request->dni, $data->tallerID);
            if (empty($valid)) {
                array_push($modulosValid, $value);
            } else {
                continue;
            }
        }

        if (empty($modulosValid)) {
            return response()->json(['validated' => false, 'message' => 'Solo puede preinscribirse en un modulo por taller'], 400);
        }

        $modulos = json_encode($modulosValid);
        if (empty($request->horarieSel)) {
            $preHorario= json_encode($request->horarioSel);
        } else {
            $preHorario = null;
        }
        $parametros = [
            1,
            1,
            $request->dni,
            $request->paterno,
            $request->materno,
            $request->nombre,
            $request->sexo,
            $request->fechaNac,
            $request->fotoPerfil,
            $request->telefono,
            $request->celular,
            $request->email,
            $request->idEstudiante,
            $request->modalidad,
            $request->programaAcad,
            $request->ingresante,
            $modulos,
            $request->dni,
            '2',
            $request->server->get('REMOTE_ADDR'),
            '4',
            $request->filial,
            $preHorario,
        ];


        try {
            $queryResult = \DB::select('exec [acad].[Sp_INS_pre_inscripciones] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros);
            $param = [
                $modulosValid[0]['moduloID']
            ];

            $id = $queryResult[0]->id;
            $data = \DB::select('[acad].[SP_SEL_modulos_carreraX_id] ?', $param);
            $response = ['validated' => true, 'mensaje' => 'Se ah creado correctamente la pre inscripcion', 'data' => $data, 'idResult' => $id];
            $codeRespones = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'mensaje' => $e->getMessage()];
            $codeRespones = 500;
        }
        return response()->json($response, $codeRespones);
    }

    
    public function preInscripcionesByModulo($moduloID)
    {
        $parameters = [
            $moduloID,
            Input::get('fechaInicio'),
            Input::get('fechaFin'),
            Input::get('filial'),
            Input::get('programaAcad'),
        ];


        try {
            $preInscripciones = \DB::select('[acad].[Sp_SEL_preinscripcionesXModuloFechas] ?, ?, ?, ?, ?', $parameters);
            $response = ['success' => true, 'data' => $preInscripciones];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => $e->getMessage()];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }

//    lista de preinscricpiones dashboard
    public function obtenerPreinsByDiasRitmoFilialModulo()
    {
        $parameters = [
            Input::get('fechaInicio'),
            Input::get('fechaFin'),
            Input::get('filial'),
            Input::get('programaAcad'),
            Input::get('carrera'),
            Input::get('iRitmoId'),
            Input::get('iConfigDias'),
            Input::get('modProg'),
        ];


        try {
            $preInscripciones = \DB::select('[acad].[Sp_SEL_cant_preinscrip_ModuloXfechas_progAcad_filial] ?, ?, ?, ?, ?, ?, ?, ?', $parameters);
            foreach ($preInscripciones as $key => $row) {
                $paramnsPreinscrios = [
                    $row->iModProgd,
                    Input::get('fechaInicio'),
                    Input::get('fechaFin'),
                    Input::get('filial'),
                    Input::get('programaAcad'),
                    $row->iConfigDiasId,
                    $row->horaInicio,
                    $row->horaFin,
                    $row->iRitmoId,
                ];
                $row->listaPreinscritos = \DB::select('exec [acad].[Sp_SEL_preinscripcionesXModuloFechas] ?, ?, ?, ?, ?, ?, ?, ?, ?', $paramnsPreinscrios);
            }
            $response = ['success' => true, 'data' => $preInscripciones];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['success' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }


    public function preinscritosXModProgHorasFilialRitmoFechas()
    {

        $parameters = [
            Input::get('iModProgd'),
            Input::get('fechaInicio'),
            Input::get('fechaFin'),
            Input::get('iFilId'),
            Input::get('iProgramasAcadId'),
            Input::get('iRitmoId'),
            Input::get('iConfigDiasId'),
            Input::get('horaInicio'),
            Input::get('horaFin')
        ];

//        return response()->json(['adat' => $parameters]);
        try {
            $preinscritos = \DB::select('exec [acad].[Sp_SEL_preinscritosXModProgHorasFilialRitmoFechas] ?, ?, ?,  ?, ?, ?, ?, ?, ?', $parameters);
            $response = ['validated' => true, 'message' => 'datos obtenidos correctamente', 'data' => $preinscritos];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }
}
