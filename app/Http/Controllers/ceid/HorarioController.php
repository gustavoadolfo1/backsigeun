<?php

namespace App\Http\Controllers\ceid;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;


class HorarioController extends Controller
{
    public function preHorariosXFilialModRitmoNivelCarrera()
    {
        $parameters = [
            Input::get('ritmo'),
            Input::get('filial'),
            Input::get('modulo'),
            Input::get('carrera')
        ];

        try {
            $preHorarios = \DB::select('exec [acad].[SP_SEL_pre_horariosXFilialModRitmoNivelCarrera] ?, ?, ?, ?', $parameters);

            foreach ($preHorarios as $row) {
                $row->horas =   json_decode($row->horas,true);
            }
            $data = [
                'preHorarios' => $preHorarios,
            ];
            $response = ['validated' => true, 'data' => $data];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => []];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function preHorariosXFilialidMoProg()
    {
        $filial = Input::get('iFilId');
        $modulo = Input::get('iModProgd');
        $programaAcad = Input::get('iProgramAcad');
        $parameters = [
            $filial,
            $modulo,
            $programaAcad,
        ];

        try {
            $preHorarios = \DB::select('EXEC [acad].[SP_SEL_preHorariosXFilialidMoProg] ?, ?, ?', $parameters);

            foreach ($preHorarios as $key =>  $row){
                $row->horas = json_decode($row->horas,true);
                foreach ($row->horas as $i =>  $horas ) {
                    $row->horas[$i]['cantidadPreinscritos'] = $this->cantidadPreInscritosHoras($horas['iPreHorariosId'], $horas['tHoraInicio'], $horas['tHoraFin'], $programaAcad);
                }
            }

            $general = new GeneralController();
            $ritmos = $general->obtenerRitmosCiclos(Input::get('iModProgd'));
            $data = (object) array('preHorarios' => $preHorarios, 'ritmos' => $ritmos);
            $response = ['validated' => true, 'message' => 'pre horario obtenido correctamente', 'data' => $data];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function cantidadPreInscritosHoras($horarioid, $horaInicio, $horaFin, $programAcad)
    {
        return $cant = \DB::select('exec [acad].[Sp_SEL_cantidadPreInscritosXhorarioXhoraIniXfinXProgramAcad] ?, ?, ?, ?', [$horarioid, $horaInicio, $horaFin, $programAcad])[0]->cantidad;
    }
}
