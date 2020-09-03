<?php

namespace App\Http\Controllers\CCTIC;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;



class HorarioController extends Controller
{
    public function obtenerPlanCarrera($id)
    {
        try {

            $planes = \DB::select('exec [acad].[Sp_SEL_planXCarreraId]  ?', [$id]);

            $response = ['validated' => true, 'data' => $planes];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => []];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }

    public function cursosXModuloPlan(Request $request)
    {
        $parameters = [
            $request->iCarreraId,
            $request->cCurricDetCicloCurso,
            $request->iCurricId,
        ];

//        return response()->json($parameters);
        try {
            $cursos = \DB::select('[acad].[SP_SEL_cursosXcarrera_modulo_currid] ?, ?, ?', $parameters);
            $response = ['validated' => true, 'data' => $cursos];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }


    public function obtenerSeccionCurso(Request $request)
    {

        $parameters = [
            $request->iCarreraId,
            $request->iCurricId,
            $request->cCurricDetCicloCurso,
            $request->cCurricCursoCod,
            $request->cCurricDetActi
        ];
        try {
            $secciones = \DB::select('[acad].[SP_SEL_seccionesXcarrera_cursocod] ?, ?, ?, ?, ?', $parameters);
            $response = ['validated' => true, 'data' => $secciones];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }

    public function obtenerAulasDisponibles()
    {
        $parametros = [
            Input::get('carreraid'),
            Input::get('filialid'),
            Input::get('grupoid'),
            Input::get('horaini'),
            Input::get('horafin'),
            Input::get('iDiaSemId'),
            Input::get('iHorariosId')
        ];

        try {
            $aulas = \DB::select('[acad].[SP_SEL_aulasDisponiblesXcarrera_fililas_horas_dia_grupo] ?, ?, ?, ?, ?, ?, ?', $parametros);
            $response = ['validated' => true, 'data' => $aulas];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }


    public function guardarHorario(Request $request)
    {
//        return response()->json($request);
        $parameters = [
            $request->aula,
            $request->carrera,
            $request->filial,
            $request->plan,
            $request->curso,
            $request->seccion,
            $request->grupo,
            $request->iDiaSemId,
            $request->cHorariosDia,
            $request->tHorariosInicio,
            $request->tHorariosFin,
            $request->cantidadHoras,
            auth()->user()->cCredUsuario,
            'equipoSis',
            $request->server->get('REMOTE_ADDR'),
            'N',
            'mac'
        ];
        try {
            $resp = \DB::select('exec [acad].[SP_INS_horarios] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
			?, ?, ?, ?, ?', $parameters);
            $response = ['validated' => true, 'message' => 'insertado correctamente'];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage()];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);

    }


    public function obtenerHorarioXCarreraidFilialidCurricidGrupoid()
    {
        $parametros = [
            Input::get('filial'),
            Input::get('plan'),
            Input::get('grupo'),
            Input::get('carrera')
        ];
        try {
            $horarios = \DB::select('[acad].[SP_SEL_horarioXFilial_carrera_plan_grupo] ?, ?, ?, ?', $parametros);

            $response = ['validated' => true, 'message' => 'datos obtenidos correctamente', 'data' => $horarios];
            $responseCode = 200;
        } catch (\Exception  $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function eliminarHorarioBloqueByID($horarioid)
    {
        try {
            $result = \DB::select('[acad].[SP_DEL_horarioXiHorariosId] ?', [$horarioid]);
            $response = ['validated' => true, 'message' => 'eliminado correctamente', 'data' => $result];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function actualizarHorarioXhorarioID($horarioid, Request $request)
    {
        $parameters = [
            $horarioid,
            $request->aula,
            $request->curso,
            $request->seccion,
            $request->tHorariosInicio,
            $request->tHorariosFin,
            $request->cantidadHoras,
            auth()->user()->cCredUsuario,
            'equipoSis',
            $request->server->get('REMOTE_ADDR'),
            'N',
            'mac',
        ];
        try {
            $result = \DB::select('[acad].[SP_UPD_horarioXiHorarioId] ?, ?, ?, ?, ?, ?, ?,	?, ?, ?, ?,	?', $parameters);
            $response = ['validated' => true, 'message' => 'actualizado correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message'=> $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }




    public function preHorariosXFilialidCarreraid()
    {
        $filial = Input::get('iFilId');
        $programaAcad = Input::get('iProgramAcad');
        $carrera = Input::get('iCarreraId');
        $parameters = [
            $filial,
            $programaAcad,
            $carrera,
        ];

        try {
            $preHorarios = \DB::select('exec [acad].[Sp_SEL_preHorariosXFilialidCarreraid] ?, ?, ?', $parameters);

            foreach ($preHorarios as $key =>  $row){
                $row->horas = json_decode($row->horas,true);
                foreach ($row->horas as $i =>  $horas ) {
                    $row->horas[$i]['cantidadPreinscritos'] = $this->cantidadPreInscritosHoras($horas['iPreHorariosId'], $horas['tHoraInicio'], $horas['tHoraFin'], $programaAcad);
                }
            }


            $turnos = $turnos = \DB::select('SELECT * FROM acad.turnos where iFilId_xx = ? and iProgramasAcadId = ?', [$filial, $programaAcad]);
            $data = (object)array('preHorarios' => $preHorarios, 'turnos' => $turnos);


            $response = ['validated' => true, 'message' => 'datos obtenidos correctamente', 'data' => $data];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function cantidadPreInscritosHoras($horarioid, $horaInicio, $horaFin, $programAcadId)
    {
        return $cant = \DB::select('exec [acad].[Sp_SEL_cantidadPreInscritosXhorarioXhoraIniXfinXProgramAcad] ?, ?, ?, ?', [$horarioid, $horaInicio, $horaFin, $programAcadId])[0]->cantidad;
    }

    public function eliminarHorarioByPublicacion(Request $request)
    {
        DB::beginTransaction();
        try {

            // verificar si este horario tiene preinscritos

            $resp = DB::table('acad.pre_horario_publicaciones as phd')
                ->join('acad.preinscripciones as p', 'phd.iPreHorariosPublicacionId', '=', 'p.iPreHorariosPublicacionId')
                ->select(DB::raw('count(*) as total'))
                ->where('phd.iPreHorariosId', '=', $request->iPreHorariosId)
                ->where('phd.iPublicacionId', '=', $request->iPublicacionId)
                ->first();

            if ($resp->total > 0) {
                DB::commit();
                $response = ['validated' => true, 'message' => 'No se pudo eleminar el horario ya que cuenta con preinscrcitos', 'data' => []];
                $responseCode = 400;

                return response()->json($response, $responseCode);
            }
            DB::table('acad.pre_horario_publicaciones')
                ->where('iPreHorariosId', '=', $request->iPreHorariosId)
                ->where('iPublicacionId', '=', $request->iPublicacionId)
                ->update(
                    [
                        'bPreHorariosPublicacionEstado' => $request->bPreHorariosPublicacionEstado
                    ]
                );

            $response = ['validated' => true, 'message' => 'Horario Eliminado correctamente', 'data' => []];
            $responseCode = 200;
        }
        catch (\Exception $e) {
            DB::rollBack();
            $response = ['validated' => false, 'error' => $e->getMessage(), 'data' => [], 'message' => 'No se puedo eliminar el harario'];
            $responseCode = 500;
        }

        DB::commit();
        return response()->json($response, $responseCode);
    }

    public function eliminarDetalleHorarioByHorarioID($id)
    {
        DB::beginTransaction();
        try {

            $resp = DB::table('acad.pre_horarios_detalle as phdet')
                ->join('acad.pre_horario_publicaciones as phd', 'phdet.iPreHorariosId', '=', 'phd.iPreHorariosId')
                ->join('acad.preinscripciones as p', 'phd.iPreHorariosPublicacionId', '=', 'p.iPreHorariosPublicacionId')
                ->select(DB::raw('count(*) as total'))
                ->where('phdet.iPreHorariosDetalleId', '=', $id)
                ->first();

            if ($resp->total > 0) {
                DB::commit();
                $response = ['validated' => true, 'message' => 'No se pudo eleminar el horario ya que cuenta con preinscrcitos', 'data' => []];
                $responseCode = 400;

                return response()->json($response, $responseCode);
            }

            DB::table('acad.pre_horarios_detalle')->where('iPreHorariosDetalleId', '=', $id)->delete();

            $response = ['validated' => true, 'message' => 'Hora Eliminada correctamente', 'data' => []];
            $responseCode = 200;

        } catch(\Exception $e) {
            DB::rollBack();
            $response = ['validated' => false, 'error' => $e->getMessage(), 'data' => [], 'message' => 'No se puedo eliminar la hora'];
            $responseCode = 500;
        }

        DB::commit();
        return response()->json($response, $responseCode);

    }


}
