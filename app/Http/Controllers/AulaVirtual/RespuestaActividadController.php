<?php

namespace App\Http\Controllers\AulaVirtual;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RespuestaActividadController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set("America/Lima");
    }
    public function responderActividad(Request $request)
    {
        $this->validate(
            $request,
            [
                'actividadId' => 'required',
                'persId' => 'required',
            ],
            [
                'actividadId.required' => 'Hubo un problema al obtener información de la actividad.',
                'persId.required' => 'Hubo un problema al obtener información personal.',
            ]
        );
        try {
            if($request->iGrupoId != 0){
                $personas = \DB::table('aula.actividades_grupopersonas')->where('iActividadGrupoId', $request->iGrupoId)->get();
                if(count($personas) > 0){
                    for ($i=0; $i < count($personas) ; $i++) {
                        $data = \DB::table('aula.actividades_respuestas')
                            ->updateOrInsert(
                                ['iActividadesId' => $request->actividadId, 'iPersId' => $personas[$i]->iPersId],
                                [
                                    'dtActivRptaEval' => date("Y-m-d\TH:i:s"),
                                    'iGrupoId'=>$request->iGrupoId,
                                ]
                            );
                    }
                }
                // return response()->json($request->all());
            }

            if(count( $request->archivo) > 0){
                for ($i=0; $i < count( $request->archivo); $i++) {
                    $parametros = [
                        $request->actividadId,
                        $request->estudId,
                        $request->persId,
                        $request->archivo[$i]['iArchivoId'],
                        auth()->user()->cCredUsuario,
                        'equipo',
                        $request->server->get('REMOTE_ADDR'),
                        'mac'
                    ];
                    $data = \DB::select('exec [aula].[Sp_INS_ActividadesRespuestas_Tareas] ?, ?, ?, ?, ?, ?, ?, ?', $parametros);
                }
            }

            $response = ['validated' => true, 'mensaje' => 'Se ha entregado la tarea.', 'data' => $data];
            $codeResponse = 200;

        } catch (\dException $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }
    public function generarRegistrosTareas($data){




    }
    public function actualizarIntegrantesGrupos($activdad,$grupo){
        $integrantes = \DB::table('aula.actividades_grupopersonas')->where('iPersId', $request->persId);
    }
    public function actualizarEstadoActividadRespuesta(Request $request)
    {

        try {
            if($request->grupo == 0){
                $data = \DB::table('aula.actividades_respuestas')
                ->where('iActividadesId', $request->actividadId)
                ->where('iPersId', $request->persId)
                ->update(['iActivRptaEstadoCierre' => 1, 'dtActivRptaEval' => date("Y-m-d\TH:i:s")]);
            }else{
                $data = \DB::table('aula.actividades_respuestas')
                ->where('iActividadesId', $request->actividadId)
                ->where('iGrupoId', $request->grupo)
                ->update(['iActivRptaEstadoCierre' => 1, 'dtActivRptaEval' => date("Y-m-d\TH:i:s")]);
            }

            $response = ['validated' => true, 'mensaje' => 'Actualizado estado de tarea.', 'data' => $data];
            $codeResponse = 200;

        } catch (\Exception $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);

    }
    public function getTareasActividad($idActiv,$iPers)
    {

        try {
            $tipoTarea = \DB::select("exec [aula].[Sp_SEL_tipoTareasXestudiante] ?,?", [$idActiv,$iPers]);
            $data = \DB::select("exec [aula].[Sp_SEL_tareasXestudiante] ?,?", [$idActiv,$iPers]);
            $response = ['validated' => true, 'mensaje' => 'Exito en consultar la  tarea.', 'data' => $data, 'tipoTarea'=> $tipoTarea];
            $codeResponse = 200;

        } catch (\Exception $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);

    }
    public function saveNota(Request $request){
        try {


                $data = \DB::table('aula.actividades_respuestas')
                ->where('iActividadesId', $request->actividadId)
                ->where('iPersId', $request->persId)
                ->update(['nActivRptaNotaEval' => $request->nota, 'iDocenteIdEval' => $request->docente, 'cObservacion' => $request->obs ]);

                if($data == 0){
                    $data = \DB::table('aula.actividades_respuestas')->insert([
                        'iActividadesId' => $request->actividadId,
                        'nActivRptaNotaEval' => $request->nota,
                        'iPersId' => $request->persId
                    ]);
                }
            $response = ['validated' => true, 'mensaje' => 'Actualizado estado de tarea.', 'data' => $data];
            $codeResponse = 200;

        } catch (\Exception $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }
    public function deleteFile($id)
    {
        try {
            $data = \DB::table('aula.actividades_respuestas_archivos')->where('iActRespArchId', $id)->delete();
            $response = ['validated' => true, 'mensaje' => 'Elimando tarea.', 'data' => $data];
            $codeResponse = 200;

        } catch (\Exception $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);

    }
    public function comentariosEstudiante(Request $request){
        try {
            $data = \DB::select("exec [aula].[Sp_SEL_comentarioForosXiPersIdXiActividadesId] ?, ?", [ $request->iPersId,$request->iActividadesId ]);
            $nota = \DB::table('aula.foros_respuestas')
                     ->where('iPersId', $request->iPersId)
                     ->where('iActividadesId', $request->iActividadesId)
                     ->get();
            $response = ['validated' => true, 'data' => $data, 'nota'=>$nota];
            $codeResponse = 200;

        } catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }
    public function getExamenEstudiante(Request $request){
        try {
            $data = \DB::table('aula.evaluaciones_respuestas')->where('iActividadesId', $request->iActividadesId)->where('iPersId', $request->iPersId)->get();
            $actividad = \DB::table('aula.actividades')->where('iActividadesId', $request->iActividadesId)->first();
            $examen = \DB::table('aula.evaluaciones')->where('iActividadesId', $request->iActividadesId)->first();
            if($examen){
                $nexamen = \DB::select('select count(*) as n from aula.evaluaciones_detalles where iEvaluacionesId = ?',[$examen->iEvaluacionesId]);
            }
            $response = ['validated' => true, 'mensaje' => 'Examen encontrado', 'data' => $data,'infoExamen' => $examen, 'infoActividad' => $actividad,'nPreguntas' => $nexamen[0]->n];
            $codeResponse = 200;

        } catch (\Exception $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }
    public function inicarExamen(Request $request){
        try {

            $data = \DB::table('aula.evaluaciones_respuestas')->insert([
                'iActividadesId' => $request->iActividadesId,
                'iEvaluacionesId' => $request->iEvaluacionesId,
                'iPersId' => $request->iPersId,
                'iDocenteId' => $request->iDocenteId,
                'dtEvalRptaInicar' => date("Y-m-d\TH:i:s"),
                'dtEvalRptaFechaSis' => date("Y-m-d\TH:i:s"),
                'cEvalRptaIpSis' => $request->server->get('REMOTE_ADDR'),
            ] );
            $response = ['validated' => true, 'mensaje' => 'Examen encontrado', 'data' => $data];
            $codeResponse = 200;

        } catch (\Exception $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);

    }
    public function PreguntasEstudiantes($idAct)
    {
        $preguntas = \DB::select("exec [aula].[Sp_SEL_preguntasXiActividadesIdEstu] ?", [ $idAct ]);

        return response()->json( $preguntas );
    }
    public function respuestaCorrecta($iEvalDetId,$iEvalDetAltId,$iPreg,$val){

        if($iPreg == 2 || $iPreg == 3){
            $reset = \DB::table('aula.evaluaciones_detalles_alternativas')
              ->where('iEvalDetId', $iEvalDetId)
              ->update(['bEvalDetRptaCorrecta' => 0]);
        }
        $data = \DB::table('aula.evaluaciones_detalles_alternativas')
                ->where('iEvalDetAltId', $iEvalDetAltId)
                ->update(['bEvalDetRptaCorrecta' => $val]);
        return response()->json( $data );
    }
    public function getPreguntaExamen(Request $request){

        // abort(500, json_encode($request->toArray()));
        $keyCache = 'getPreguntaExamen_' . $request->iEvaluacionesId . '_' . auth()->user()->iPersId . '_' .$request->iEvalRptaId;
        $results = cache()->remember($keyCache, now()->addHour(), function() use ($request){
            $data = [
                $request->iEvaluacionesId,
                $request->iPersId,
            ];
            $results = \DB::select("exec aula.Sp_SEL_preguntasExamenXiEvaluacionesIdXiPersId ?,?", $data );

            if(count($results) > 0){
                $row = $results[0];
                $registro = \DB::table('aula.evaluaciones_respuestas_detalle')
                    ->where('iEvalRptaId', $request->iEvalRptaId)
                    ->where('iEvalDetId', $row->iEvalDetId)
                    ->get();
                // abort(500, json_encode($registro));
                if(count($registro) == 0){
                    $res = \DB::table('aula.evaluaciones_respuestas_detalle')->insert([
                        'iEvalRptaId' => $request->iEvalRptaId,
                        'iEvalDetId' => $row->iEvalDetId,
                        'iEvalDetInicio' => date("Y-m-d\TH:i:s"),

                        'dtEvalRptaDetFechaSis' => date("Y-m-d\TH:i:s"),
                        'cEvalRptaDetIpSis' => $request->server->get('REMOTE_ADDR'),
                    ]);
                    $registro2 = \DB::table('aula.evaluaciones_respuestas_detalle')
                        ->where('iEvalRptaId', $request->iEvalRptaId)
                        ->where('iEvalDetId', $row->iEvalDetId)
                        ->get();
                    $results[0]->info = $registro2[0];
                }else{

                    $res = \DB::table('aula.evaluaciones_respuestas_detalle')
                        ->where('iEvalRptaId', $request->iEvalRptaId)
                        ->where('iEvalDetId', $row->iEvalDetId)
                        ->update([
                            //'iEvalDetInicio' => date("Y-m-d\TH:i:s"),
                            'dtEvalRptaDetFechaSis' => date("Y-m-d\TH:i:s"),
                            'cEvalRptaDetIpSis' => $request->server->get('REMOTE_ADDR'),
                        ]);

                    $results[0]->info = $registro[0];
                }

            }
            else{
                $results = [ 'estado'=> 'termino las preguntas' ];
            }
            return $results;
        });
        if (isset($results['estado']) && $results['estado'] == 'termino las preguntas'){
            cache()->forget($keyCache);
        }
        if (isset($results[0])) {
            $fechaInicio = new Carbon($results[0]->info->iEvalDetInicio);
            $results[0]->fechaActual = now()->toDateTimeString();
            $results[0]->fechaInicio = $fechaInicio->toDateTimeString();
            $fechaFin = $fechaInicio->addSeconds(($results[0]->nEvalDetTiempo / 1000));
            $results[0]->segundosFaltantes = $fechaFin->diffInSeconds(now());
            $results[0]->fechaFin = $fechaFin->toDateTimeString();
        }

        return response()->json( $results );
    }
    public function saveRespuestaExamen(Request $request){
        $detalleEval = \DB::table('aula.evaluaciones_detalles')
            ->where('iEvalDetId', $request->iEvalDetId)
            ->first();
        $keyCache = 'getPreguntaExamen_' . $detalleEval->iEvaluacionesId . '_' . auth()->user()->iPersId . '_' .$request->iEvalRptaId;
        $res = \DB::table('aula.evaluaciones_respuestas_detalle')
                                ->where('iEvalRptaId', $request->iEvalRptaId)
                                ->where('iEvalDetId', $request->iEvalDetId)
                                ->update([
                                    'iEvalDetTermino' => date("Y-m-d\TH:i:s"),
                                    'iEvalDetRespuesta' => $request->respuesta,
                                ]);
        cache()->forget($keyCache);
        return response()->json( $res );
    }
    public function getExamenEstudiante2(Request $request){
        $res = \DB::table('aula.evaluaciones_respuestas_detalle')
                                ->where('iEvalRptaId', $request->iEvalRptaId)
                                ->where('iEvalDetId', $request->iEvalDetId)
                                ->update([
                                    'iEvalDetTermino' => date("Y-m-d\TH:i:s"),
                                    'iEvalDetRespuesta' => $request->respuesta,
                                ]);
        return response()->json( $res );
    }
    public function updatePreguntaExamen(Request $request){
        $res = \DB::table('aula.evaluaciones_detalles')
                                ->where('iEvalDetId', $request->iEvalDetId)
                                ->update([
                                    'cEvalDetPregunta' => $request->cEvalDetPregunta,
                                    'nEvalDetTiempo' => $request->nEvalDetTiempo,
                                    'nEvalDetPuntaje' => $request->nEvalDetPuntaje,
                                ]);
    }



    /**
     * Calificacion grupos
     */
    public function getTareaGrupal($grupoId)
    {
        $archivos = \DB::select("exec [aula].[Sp_SEL_tareaGrupalXiGrupoId] ?", [ $grupoId ]);

        return response()->json( $archivos );
    }

    public function guardarNotaGrupal(Request $request){
        try {
            $data = \DB::table('aula.actividades_respuestas')
              ->where('iGrupoId', $request->grupoId)
              ->update(['nActivRptaNotaEval' => $request->nota, 'iDocenteIdEval' => $request->docente, 'cObservacion' => $request->obs ]);

            $response = ['validated' => true, 'mensaje' => 'Actualizado estado de tarea.', 'data' => $data];
            $codeResponse = 200;

        } catch (\Exception $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }
}
