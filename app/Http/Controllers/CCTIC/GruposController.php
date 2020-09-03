<?php

namespace App\Http\Controllers\cctic;

use App\Model\cctic\Grupo;
use function foo\func;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Model\cctic\Unidad;
use App\Model\cctic\PreInscripcion;
use phpDocumentor\Reflection\Types\Object_;
use PDF;



class GruposController extends Controller
{
    public function generarGrupo(Request $request)
    {

        $dataHorario = [
            'iProgramaAcadId' => $request->iProgramasAcadId,
            'iFilId' => $request->iFilId,
            'cUsuarioSis' => auth()->user()->cCredUsuario,
            'cIpSis' => $request->server->get('REMOTE_ADDR'),
        ];

        $horarios = $request->horario['detalles'];


        if ($request->cModalEstudDsc != 'VIRTUAL') {
            //      Validar si el horari si existe  un cruce con un dia.
            try {
                foreach ($horarios as $horario) {
                    DB::enableQueryLog();

                    $horarioFind = DB::table('acad.pre_horarios_detalle as phd')
                        ->join('acad.pre_horarios as p', 'phd.iPreHorariosId', '=', 'p.iPreHorariosId')
                        ->join('acad.grupos as g', 'g.iPreHorariosId', '=','p.iPreHorariosId')
                        ->join('ura.dias_semanas  as us', 'us.iDiaSemId', '=', 'phd.iDia')
                        ->where('p.bHabilitado', '=', 1)
                        ->where('g.iProgramasAcadId', '=', $request->iProgramasAcadId)
                        ->where('g.iFilId', '=', $request->iFilId)
                        ->where('phd.iDia', '=', $horario['iDia'])
                        ->where('phd.tHoraInicio', '<', $horario['tHoraFin'])
                        ->where('phd.tHoraFin', '>', $horario['tHoraInicio'])
                        ->where(function ($query) {
                            $query->orWhere('g.bEstadoInscripciones', '=', 1);
                            $query->orWhere('g.bEstadoAcademico', '=', 1);
                        })
                        ->first();

//                dd(DB::getQueryLog());

                    if (!is_null($horarioFind)) {
                        $data = [
                            'dia' => $horario['cDiaSemDsc'],
                            'horario' => $horarioFind
                        ];
                        $response = ['validated' => true, 'message' => 'error posible conflico  de hroraio', 'data' => $data];
                        $responseCode = 400;
                        return response()->json($response, $responseCode);
                    }
                }
            } catch (\Exception $e) {
                $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
                $responseCode = 500;
                return response()->json($response, $responseCode);
            }
        }



//        buscar un horario exacto para reutilizarlo.

        DB::beginTransaction();

        try {

            $horarioId = DB::table('acad.pre_horarios')->insertGetId(
                $dataHorario
            );

            foreach ($horarios as $horario) {
                DB::table('acad.pre_horarios_detalle')->insert(
                    [
                        'iPreHorariosId' => $horarioId,
                        'iDia' => $horario['iDia'],
                        'tHoraInicio' => $horario['tHoraInicio'],
                        'tHoraFin' => $horario['tHoraFin'],
                        'nHorasAcademicas' => $horario['nHorasAcademicas'],
                    ]
                );
            }

           $cGrupoDsc = $this->generarPathGrupo( $request->iFilId);

        } catch (\Exception $e) {
            DB::rollback();
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $responseCode = 500;
            return response()->json($response, $responseCode);
        }


        $parameters = [
            $cGrupoDsc,
            $request->iProgramasAcadId,
            $request->iModalEstudId,
            1, // bEstadoInscripciones
            $request->iFilId,
            $request->dFechaIni,
            $request->dFechaFin,
            $request->iCurriculaModuloId,
            $request->iDocenteId,
            $horarioId,
            $request->cPublicacionDuracion,
            $request->iMaxCantMatricula,
            0, // iCantidadEstudiantes
            null,
            $request->iPublicacionId,
            $request->iMinCantMatricula,
            $request->iModuloPlanTrabajoCostoId,
            $request->iPreHorariosPublicacionId
        ];




        //        return response()->json($parameters);
        try {
            $grupo = \DB::select('exec acad.Sp_INS_Grupo  ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parameters);

            $response = ['validated' => true, 'data' => $grupo, 'message' => 'grupo generado correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }
        DB::commit();

        $email = new emailController();

        $email->sendEmailGrupo($grupo[0]->id);

        return response()->json($response, $responseCode);
    }

    public function generarPathGrupo($filial)
    {

        return DB::select('select [acad].[Fn_CCTIC_SEL_Grupos_CalculaGrupo](?) as grupoPath', [$filial])[0]->grupoPath;
    }


    public function crearPreHorario($tHoraInicio, $horaFin, $dias, $filial)
    {
        foreach ($dias as $i => $dia) {
            if ($dia['checked']) {
                $dias[$i]['checked'] = 1;
            } else {
                $dias[$i]['checked'] = 0;
            }
        }

        try {

            $horario = DB::table('acad.pre_horarios ')
                ->where('iFilId', '=', $filial)
                ->where('tHoraInicio', '=', $tHoraInicio)
                ->where('tHoraFin', '=', $horaFin)
                ->where('bDiaLunes', '=', $dias[0]['checked'])
                ->where('bDiaMartes', '=', $dias[1]['checked'])
                ->where('bDiaMiercoles', '=', $dias[2]['checked'])
                ->where('bDiaJueves', '=', $dias[3]['checked'])
                ->where('bDiaViernes', '=', $dias[4]['checked'])
                ->where('bDiaSabado', '=', $dias[5]['checked'])
                ->where('bDiaDomingo', '=', $dias[6]['checked'])
                ->where('bActivo', '=', 1)
                //                    ->whereNotNull('iRitmoId')
                ->first();
            //            dd($horario);
            if ($horario) {
                return 0;
            }

            $nuevoHorario = DB::select('exec  [acad].[Sp_INS_pre_horarios] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', [$tHoraInicio, $horaFin, $dias[0]['checked'], $dias[1]['checked'],  $dias[2]['checked'],  $dias[3]['checked'],  $dias[4]['checked'],  $dias[5]['checked'],  $dias[6]['checked'], 1, $filial]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return $nuevoHorario[0]->id;
    }




    public function obtenerGrupos(Request $request)
    {

        $params = [
            $request->iFilId,
            $request->programAcad
        ];

        switch ($request->code) {
            case 'INSCRIPCION':
                array_push($params, 1, 0, 0, 0);
                break;
            case 'ACADEMICO':
                array_push($params, 0, 1, 0, 0);
                break;
            case 'CERRADO':
                array_push($params, 1, 1, 1, 1);
                break;
            case 'CULMINADO':
                array_push($params, 0, 0, 1, 0);
                break;
            default:
                return response()->json(['message' => 'Debe proporcionar un codigo valido'], 400);
                break;
        }

        try {
            $listaGrupos = DB::select(
                'exec [acad].SP_SEL_gruposbyProgramaAcadiFilId
                @iFilId = ?,
                @iProgramasAcadId = ?,
                @bEstadoInscripciones = ?,
                @bEstadoAcademico = ? ,
                @bEstadoCulminado  = ?,
                @bEstadoCerrado  = ?',
                $params
            );

            foreach ($listaGrupos as $grupo) {
                $grupo->unidades = json_decode($grupo->unidades);
                $grupo->publicoObjetivo = json_decode($grupo->publicoObjetivo);
                $grupo->horario = json_decode($grupo->horario);
                $grupo->grupo_detalle = json_decode($grupo->grupo_detalle);
            }

            $response = ['validated' => true, 'data' => $listaGrupos, 'message' => 'lista de grupos obtenidos correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function getGrupoByid($id)
    {
        //        todo filtar programa y filial

        try {


            $grupo = DB::select('exec [acad].SP_SEL_grupoById @iGruposId = ?', [$id]);

            if (count($grupo) == 0) {
                return response()->json(['validated' => true, 'data' => [], 'message' => 'No se encontraron datos de este grupo'], 200);
            }
            $grupo = $grupo[0];

            $grupo->unidades = json_decode($grupo->unidades);
            $grupo->horario = json_decode($grupo->horario);

            $response = ['validated' => true, 'data' => $grupo,  'message' => 'Datos obtenidos correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], $e->getMessage()];
            $responseCode = 500;
        }
        return response()->json($response, $responseCode);
    }


    public function cambiarEstadoGrupo(Request $request)
    {
        DB::beginTransaction();
        try {
            DB::table('acad.grupos')->where('iGruposId', $request->iGruposId)
                ->update([
                    'bEstadoCerrado' => $request->bEstadoCerrado,
                    'cEstadoCerradoDsc' => $request->description
                ]);

            $response = ['validated' => true, 'message' => 'Actualizado correctamente'];
            $responseCode = 200;
        } catch(\Exception $e) {
            DB::rollBack();
            $response = ['validated' => false, 'message' => 'No se pudo realizar la accion', 'error' => $e->getMessage()];
            $responseCode = 500;
        }

        DB::commit();

        return response()->json($response, $responseCode);
    }


    public function cambiarFechaInicio(Request $request)
    {


        DB::beginTransaction();

        try {

           DB::table('acad.grupos')
               ->where('iGruposId', '=', $request->iGruposId)
               ->update([
                   'dFechaIni' => $request->dFechaIni,
                   'dFechaFin' => $request->dFechaFin
               ]);


           $response = ['validated' => true, 'message' => 'Fecha actualizada correctamente', 'data' => []];

           $responseCode = 200;

       } catch (\Exception $e) {
           DB::rollBack();
           $response = ['validated' => false, 'message' => 'No se pudo actualizar la fecha de inicio', 'data' => [], 'error' => $e->getMessage()];

            $responseCode = 500;
       }

       DB::commit();

        return response()->json($response, $responseCode);
    }

    public function generarAsistencias(Request $request)
    {
        $data = [
            $request->iGruposId,
            auth()->user()->cCredUsuario,
            'E',
            $request->server->get('REMOTE_ADDR'),
            'M'
        ];
        try {

            $resp = DB::select('exec [acad].[Sp_CCTIC_INS_Asistencias_Genera_Fechas]
               ?, ?, ?, ?, ?',
                $data
            );

            $response = ['validated' => true, 'message' => 'Inicio de clases correctamente', 'data' => $resp];
            $responseCode = 200;

        } catch (\Exception  $e) {
            $response = ['validated' => false, 'message' => 'No se pudo iniciar las clases', 'data' => [], 'error' => substr($e->errorInfo[2] ?? '', 54)];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function obtenerGruposActivos(Request $request)
    {
        $parameters = [
            $request->iFilId, 3
        ];
        // return $parameters;
        try {
            $grupos = DB::select('exec [acad].[Sp_CCTIC_SEL_General_Listado_GruposActivos] ?, ?', $parameters);

            $response = ['validated' => true, 'data' => $grupos,  'message' => 'datos obtenidos correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function obtenerCursoDocenteGrupo(Request $request)
    {
        $parameters = [
            $request->id
        ];
        // return $parameters;
        try {
            $datos = DB::select('exec [acad].[Sp_CCTIC_SEL_General_Datos_Grupo] ?', $parameters);

            $response = ['validated' => true, 'data' => $datos[0],  'message' => 'datos obtenidos correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function byDocente(Request $request)
    {
        try {
            $grupos = DB::select('exec [acad].[Sp_CCTIC_SEL_General_Datos_Grupo_GrupoDetalle]
            @iPersId = ?', [$request->iPersId]);

            if (count($grupos) == 0) {
                return response(['validated' => true, 'message' => 'No se encontraron datos'], 200);
            }

            foreach ($grupos as $grupo) {
                $grupo->detalles = json_decode($grupo->detalles);
                $grupo->Horarios = json_decode($grupo->Horarios);
            }

            $response = ['validated' => true, 'message' => 'Datos obtenidos correctamente', 'data' => $grupos];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => 'No se obtuvieron los datos correctamente', 'data' => [], 'error' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function generarNuevoGrupo(Request $request)
    {

        $dataHorario = [
          'iProgramaAcadId' => $request->horario['programaAcad'],
          'iFilId' => $request->horario['filial'],
        ];

        DB::beginTransaction();


        try {

            $horarioId = DB::table('acad.pre_horarios')->insertGetId(
                $dataHorario
            );

            $horarios = $request->horario['detalles'];

            foreach ($horarios as $horario) {
                DB::table('acad.pre_horarios_detalle')->insert(
                    [
                        'iPreHorariosId' => $horarioId,
                        'iDia' => $horario['dia'],
                        'tHoraInicio' => $horario['horaInicio'],
                        'tHoraFin' => $horario['horaFin'],
                        'nHorasAcademicas' => $horario['totalHoras'],
                    ]
                );
            }

        } catch(\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage());
        }

        DB::commit();



    }

    public function infoCambiaGrupo(Request $request)
    {

        $data = [
          $request->dni,
          $request->programAcad,
          $request->filial
        ];

        try {

            $data = DB::select('exec [acad].[Sp_CCTIC_SEL_Panel_Muestra_Informacion_CambioGrupo] ?, ?, ?', $data);

            foreach ($data as $d) {
                $d->Horarios = json_decode($d->Horarios);
                $d->Inscripciones_Detalle = json_decode($d->Inscripciones_Detalle);
                $d->Unidades = json_decode($d->Unidades);
            }

            $response = ['validated' => true, 'message' => 'Estudiante obtenido correctamente', 'data' => $data];
            $responseCode = 200;

        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => 'No se puedo obtener el estudiante', 'error' => $e->getMessage(), 'data' => []];
            $responseCode = 500;
        }


        return response()->json($response, $responseCode);


    }

    public function numeroMensualidades(Request $request)
    {
        $params = [
            $request->iPersId,
            $request->iGrupoId,
            0
        ];



        try {

            $data = DB::select('[acad].[Sp_CCTIC_SEL_Grupos_CambioGrupo_NumeroMensualidades] ?, ?, ?', $params);


            $response = ['validated' => true, 'message' => 'Numero mensualidades obtenidas correctamente', 'data' => $data[0]];
            $responseCode = 200;

        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => 'No se puedo obtener correctamente', 'error' => $e->getMessage(), 'data' => []];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);

    }




    public function cambioGrupo(Request $request)
    {
        $parameters = [
            $request->iPersId,
            $request->iGrupoId,
            $request->iGrupoDestinoId,
            0,
            auth()->user()->cCredUsuario,
//            null,
            $request->server->get('REMOTE_ADDR'),
        ];

        try {
            $grupo = DB::select('exec [acad].[Sp_CCTIC_UPD_Grupos_CambioGrupo] ?, ?, ?, ?, ?, ?', $parameters);
            $response = ['validated' => true, 'data' => $grupo, 'message' => 'Se realizo el cambio de grupo corrctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function cambiaGrupoEstatus(Request $request)
    {
        $params = [
            $request->iPersId,
            $request->iGrupoId,
            $request->iGrupoIdCam,

            auth()->user()->cCredUsuario,
            $request->server->get('REMOTE_ADDR'),
        ];
        try {
            $datosGrupo = DB::select('[acad].[Sp_CCTIC_SEL_Grupos_CambioGrupo_Estatus] ?, ?, ?, ?, ?', $params);

            $response = ['validated' => true, 'data' => $datosGrupo, 'message' => 'Estatus obtenido correctamante'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'error' => $e->getMessage(), 'message' => substr($e->errorInfo[2] ?? '', 54)];
            $responseCode = 500;
        }


        return response()->json($response, $responseCode);

    }

    public function obtenerCronograma($id)
    {

        try {
            $grupo = DB::select('[acad].[Sp_Sel_grupoById] ?', [$id]);

            if (count($grupo) == 0) {
                $grupo = new stdClass();
            }

            $grupo = $grupo[0];
        } catch(\Exception $e) {
            $grupo = new stdClass();
        }

//        return view('cctic.cronograma', ['data' => $grupo]);

        $pdf = PDF::loadView('cctic.cronograma', ['data' => $grupo])->setPaper('A4', 'landscape');

        return $pdf->stream();
    }



}
