<?php

namespace App\Http\Controllers\DASA;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UraCurriculaCursoDetalle;
use App\UraCurriculaCursoDetalleHistorial;

class CurriculaCursoController extends Controller
{   
    /**
     * 
     */
    public function obtenerCarrerasPlanes()
    {
        $carreras = \DB::select('exec ura.Sp_GRAL_SEL_RegistrosXcShemaXcNombreTabla ?, ?',array('ura', 'carreras'));

        $planes = \DB::select('exec ura.Sp_GRAL_SEL_RegistrosXcShemaXcNombreTabla ?, ?',array('ura', 'curriculas'));

        $secciones = \DB::select('exec ura.Sp_GRAL_SEL_RegistrosXcShemaXcNombreTabla ?, ?',array('ura', 'secciones'));

        $tipoAperturaCursos = \DB::select('exec [ura].[Sp_GRAL_SEL_tiposAperturasCursos]');

        $ciclos = \DB::table('ura.controles')->orderBy('iControlCicloAcad', 'desc')->get();

        $ciclosProceso = \DB::select('exec [ura].[SP_SEL_CicloAcad_Procesamiento]');

        $data = ['carreras' => $carreras, 'planes' => $planes, 'secciones' => $secciones, 'tipoAperturaCursos' => $tipoAperturaCursos, 'semestres'=> $ciclos, 'ciclosProceso' => $ciclosProceso ]; 

        return response()->json( $data );
    }
    public function obtenerSemestres()
    {
        $data = \DB::table('ura.controles')->orderBy('iControlCicloAcad', 'desc')->get();

        return response()->json( $data );
    }

    /**
     * 
     */
    public function obtenerCurriculaPorCarreraPlan($carreraId, $curricId)
    {
        $cursos = UraCurriculaCursoDetalle::select('iCurricDetId','iCurricCursoId','iCurricDetTtcurso', 'cCurricDetCicloCurso','cCurricDetActi','iCurricDetActiSecc', 'iCurricDetActiTipoApertura as iTipoApertura')->where('iCarreraId', $carreraId)->where('iCurricId', $curricId)->with(['uraCurriculaCurso'])->orderBy('cCurricDetCicloCurso', 'asc')->get();

        return response()->json( $cursos );
    }

    public function obtenerCurriculaPorCarreraPlanMod($carreraId, $curricId, $cicloAcad)
    {
        $cursos = UraCurriculaCursoDetalle::select('iCurricDetId','iCurricCursoId','iCurricDetTtcurso', 'cCurricDetCicloCurso','cCurricDetActi','iCurricDetActiSecc', 'iCurricDetActiTipoApertura as iTipoApertura')->where('iCarreraId', $carreraId)->where('iCurricId', $curricId)->with(['uraCurriculaCurso'])->orderBy('cCurricDetCicloCurso', 'asc')->get();

        $cursosHistorico = UraCurriculaCursoDetalleHistorial::select('iCurricDetId','iCurricCursoId','iCurricDetTtcurso', 'cCurricDetCicloCurso','cCurricDetActi','iCurricDetActiSecc', 'iCurricDetActiTipoApertura as iTipoApertura')->where('iCarreraId', $carreraId)->where('iCurricId', $curricId)->where('iControlCicloAcad', $cicloAcad)->with(['uraCurriculaCurso'])->orderBy('cCurricDetCicloCurso', 'asc')->get();


                                   

        return response()->json( ['cursos' => $cursos, 'cursosHistorico' => $cursosHistorico] );
    }


    /**
     * 
     */
    public function guardarEstadoCheckCurso(Request $request)
    {
        $this->validate(
            $request, 
            [
                'cursoId' => 'required|integer',
                'check' => 'required|boolean',
                'secciones' => 'required|integer',
            ], 
            [
                'cursoId.required' => 'Hubo un problema al obtener información del curso.',
                'check.required' => 'Hubo un problema al obtener información del Checkbox.',
                'secciones.required' => 'Hubo un problema al obtener información del select.',
            ]
        );  

        $parametros = [
            $request->cursoId,
            $request->iTipoApertura, 
            $request->check,
            $request->secciones,
            $request->control, 
            auth()->user()->cCredUsuario, 
            'equipo', 
            $request->server->get('REMOTE_ADDR'), 
            'mac'
        ];

        try {
            $data = \DB::select('exec ura.[Sp_DASA_UPD_activaCursosPlan] ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros);

            if ($data[0]->resultado == 1) {
                $mensaje = 'Datos actualizados exitosamente.';
            } else {
                $mensaje = 'No se pudo actualizar.';
            }

            $response = ['validated' => true, 'mensaje' => $mensaje, 'data' => $data];
            $codeResponse = 200;

        } catch (\Exception $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
            $codeResponse = 500; 
        }

        return response()->json($response, $codeResponse);
    }

    /**
     * 
     */
    public function obtenerCurriculaCursoDetalle($carreraId, $curricId)
    {
        $cursos = \DB::select('exec ura.[Sp_DASA_SEL_curriculasCursosDetalles] ?, ?', array( $carreraId, $curricId ));

        return response()->json( $cursos );
    }
}
