<?php

namespace App\Http\Controllers\CCTIC;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class GestionExamenesController extends Controller
{
    public function obtenerTiposExamenes(Request $request)
    {
        try {
            $tiposEvaluacion = DB::table('acad.tipos_evaluacion')
                ->where('iTipoEvaluacionId', '<>', 1)
                ->get();
            $response = ['validated' => true, 'data' => $tiposEvaluacion,  'message' => 'datos obtenidos correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], $e->getMessage()];
            $responseCode = 500;
        }
        return response()->json($response, $responseCode);
    }
    public function obtenerTiposCriterios(Request $request)
    {
        // Para examen de suficiencia
        try {
            $criterios =  DB::select('exec [acad].[Sp_CCTIC_SEL_Examenes_Listado_TipoCriterio]');
            $response = ['validated' => true, 'data' => $criterios,  'message' => 'datos obtenidos correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], $e->getMessage()];
            $responseCode = 500;
        }
        return response()->json($response, $responseCode);
    }
    public function obtenerExamenesAgendados(Request $request)
    {
        $this->validate(
            $request,
            [
                "iTipoEvaluacionId"        => "required|integer",
            ]
        );

        $parameters = [
            $request->iTipoEvaluacionId,
            3
        ];

        try {
            $examenes = DB::select('exec [acad].[Sp_CCTIC_SEL_Examenes_Muestra_ExamenesAgendados] ?, ?', $parameters);
            $response = ['validated' => true, 'data' => $examenes,  'message' => 'datos obtenidos correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], $e->getMessage()];
            $responseCode = 500;
        }
        return response()->json($response, $responseCode);
    }
    public function obtenerExamenAgendadoXId(Request $request)
    {
        $this->validate(
            $request,
            [
                "iExamenId"        => "required|integer",
                // "iExamenId"        => "required|integer",
            ]
        );

        $parameters = [
            (int) $request->iExamenId
        ];
        try {
            $examen = DB::select('exec [acad].[Sp_CCTIC_SEL_Examenes_Muestra_ExamenesAgendados_iExamenId] ?', $parameters);
            $tipoEvaluacion = $examen[0]->iTipoEvaluacionId;
            $data['examen'] = $examen[0];

            switch ($tipoEvaluacion) {
                case 2:
                    $grupoInfo = DB::select('exec [acad].[Sp_CCTIC_SEL_General_Datos_Grupo] ?', [$examen[0]->iGrupoId]);
                    // $data["alumnos"] = DB::select('exec [acad].[Sp_CCTIC_SEL_Examenes_Muestra_ListadoExtemporaneos_Aptos] ?', [$examen[0]->iGrupoId]);
                    $data["alumnos"] = DB::select('exec [acad].[Sp_CCTIC_SEL_Examenes_Muestra_ListadoExtemporaneos] ?', [$examen[0]->iGrupoId]);
                    $data["grupoInfo"] = count($grupoInfo) ?  $grupoInfo[0] : null;
                    break;
                case 3:
                    $dataExamen = DB::select('exec [acad].[Sp_CCTIC_SEL_Examenes_Muestra_ListadoUbicacion] ?', [$examen[0]->iModuloId]);
                    $data['unidades'] = json_decode($dataExamen[0]->Notas_Ubicacion);
                    break;
                case 4:
                    $dataExamen = DB::select('exec [acad].[Sp_CCTIC_SEL_Examenes_Muestra_ListadoSuficiencia] ?', [$examen[0]->iModuloId]);
                    $data['unidades'] = json_decode($dataExamen[0]->Notas_Suficiencia);
                    break;

                default:
                    # code...
                    break;
            }
            $response = ['validated' => true, 'data' => $data,  'message' => 'datos obtenidos correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], $e->getMessage()];
            $responseCode = 500;
        }
        return response()->json($response, $responseCode);
    }

    // public function obtenerAlumnosExtemporaneo(Request $request)
    // {
    //     $this->validate(
    //         $request,
    //         [
    //             "iGrupoId"        => "required|integer",
    //         ],
    //     );

    //     $parameters = [
    //         (int) $request->iGrupoId
    //     ];

    //     try {
    //         $alumnos = DB::select('exec [acad].[Sp_CCTIC_SEL_Examenes_Muestra_ListadoExtemporaneos] ?', $parameters);
    //         $response = ['validated' => true, 'data' => $alumnos,  'message' => 'datos obtenidos correctamente'];
    //         $responseCode = 200;
    //     } catch (\Exception $e) {
    //         $response = ['validated' => false, 'data' => [], $e->getMessage()];
    //         $responseCode = 500;
    //     }
    //     return response()->json($response, $responseCode);
    // }

    public function obtenerInfoGrupoExtemporaneo(Request $request)
    {
        $this->validate(
            $request,
            [
                "iGrupoId"        => "required|integer",
            ]
        );

        $parameters = [
            (int) $request->iGrupoId
        ];

        try {
            // $alumnos = DB::select('exec [acad].[Sp_CCTIC_SEL_Examenes_Muestra_ListadoExtemporaneos] ?', $parameters);
            $alumnos = DB::select('exec [acad].[Sp_CCTIC_SEL_Examenes_Muestra_ListadoExtemporaneos_Aptos] ?', $parameters);
            $dataGrupo = DB::select('exec [acad].[Sp_CCTIC_SEL_General_Datos_Grupo] ?', $parameters);

            $data["alumnos"] = $alumnos;
            $data["grupo"] = $dataGrupo[0];
            $response = ['validated' => true, 'data' => $data,  'message' => 'datos obtenidos correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], $e->getMessage()];
            $responseCode = 500;
        }
        return response()->json($response, $responseCode);
    }
    public function registrarAlumnosExtemporaneo(Request $request)
    {
        $this->validate(
            $request,
            [
                "json"            => "required",
                "iGrupoId"        => "required|integer",
                "iGrupoDetalleId" => "required|integer",
                "iLaboratorioId"  => "required|integer",
                "dFechaExamen"    => "required",
                "hHoraExamen"     => "required"
            ]
        );

        $parameters = [
            $request->json,
            (int) $request->iGrupoId,
            (int) $request->iGrupoDetalleId,
            $request->iLaboratorioId,
            $request->dFechaExamen,
            $request->hHoraExamen,

            gethostname(),
            gethostname(),
            $request->getClientIp()
        ];
        try {
            $alumnos = DB::select('exec [acad].[Sp_CCTIC_INS_Examenes_Genera_ListadoExtemporaneos] ?, ?, ?, ?, ?, ?, ?, ?, ?', $parameters);
            $response = ['validated' => true, 'data' => $alumnos,  'message' => 'datos guardados correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], $e->getMessage()];
            $responseCode = 500;
        }
        return response()->json($response, $responseCode);
    }
    public function aprobarExamenExtemporaneo(Request $request)
    {
        $this->validate(
            $request,
            [
                "iExamenId"        => "required|integer",
            ]
        );

        $parameters = [
            (int) $request->iExamenId,
        ];

        try {
            $alumnos = DB::select('exec [acad].[Sp_CCTIC_UPD_Examenes_Aprobar_ListadoExtemporaneos] ?', $parameters);
            $response = ['validated' => true, 'data' => [],  'message' => 'datos obtenidos correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], $e->getMessage()];
            $responseCode = 500;
        }
        return response()->json($response, $responseCode);
    }
    public function actualizarNotasExtemporaneo(Request $request)
    {
        $this->validate(
            $request,
            [
                "notas"   => "required",
            ]
        );
        try {
            foreach ($request->notas as $key => $value) {
                $parameters = [
                    $value['iNotaExamenId'],
                    $value['iExamenId'],
                    (float) $value['Nota'],
                    gethostname(),
                    gethostname(),
                    $request->getClientIp()
                ];

                // return $parameters;
                $save =  DB::select('exec [acad].[Sp_CCTIC_UPD_Examenes_ActualizaNota_extemporaneo] ?, ?, ?, ?, ?, ?', $parameters);
            }

            $response = ['validated' => true, 'data' => [],  'message' => 'datos guardados correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], $e->getMessage()];
            $responseCode = 500;
        }
        return response()->json($response, $responseCode);
    }

    public function registraExamenUbicación(Request $request)
    {
        $this->validate(
            $request,
            [
                "iPersId"        => "required|integer",
                "iModuloId"       => "required|integer",
                "iDocenteId"     => "required|integer",
                // "iProgramaId"    => "required|integer",
                "iLaboratorioId" => "required|integer",
                "dFechaExamen"   => "required",
                "hHoraExamen"    => "required"
            ]
        );

        $parameters = [
            $request->iPersId,
            $request->iModuloId,
            $request->iDocenteId,
            3,
            $request->iLaboratorioId,
            $request->dFechaExamen,
            $request->hHoraExamen,

            gethostname(),
            gethostname(),
            $request->getClientIp()
        ];

        try {
            $alumno = DB::select('exec [acad].[Sp_CCTIC_INS_Examenes_Genera_ExamenUbicacion] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parameters);
            $response = ['validated' => true, 'data' => $alumno,  'message' => 'datos guardados correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], $e->getMessage()];
            $responseCode = 500;
        }
        return response()->json($response, $responseCode);
    }
    public function actualizarNotasExamenUbicación(Request $request)
    {
        $this->validate(
            $request,
            [
                "notas"       => "required"
            ]
        );

        try {

            foreach ($request->notas as $key => $value) {
                $parameters = [
                    $value['iNotaExamenId'],
                    $value['iExamenId'],
                    $value['iPersId'],
                    $value['nNota'],
                    gethostname(),
                    gethostname(),
                    $request->getClientIp()
                ];

                $save = DB::select('exec [acad].[Sp_CCTIC_UPD_Examenes_ActualizaNota_Ubicacion] ?, ?, ?, ?, ?, ?, ?', $parameters);
            }
            $response = ['validated' => true, 'data' => [],  'message' => 'datos guardados correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], $e->getMessage()];
            $responseCode = 500;
        }
        return response()->json($response, $responseCode);
    }
    public function actualizarNotasExamenSuficiencia(Request $request)
    {
        $this->validate(
            $request,
            [
                "notas"       => "required"
            ]
        );

        try {

            foreach ($request->notas as $key => $value) {
                $parameters = [
                    $value['iExamenId'],
                    $value['iTipoCriterio'],
                    $value['Nota'],
                    gethostname(),
                    gethostname(),
                    $request->getClientIp()
                ];

                $save = DB::select('exec [acad].[Sp_CCTIC_UPD_Examenes_ActualizaNota_Suficiencia] ?, ?, ?, ?, ?, ?', $parameters);
            }
            $response = ['validated' => true, 'data' => [],  'message' => 'datos guardados correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], $e->getMessage()];
            $responseCode = 500;
        }
        return response()->json($response, $responseCode);
    }
    public function registraExamenSuficiencia(Request $request)
    { //obtenerInfoGrupoExtemporaneo
        $this->validate(
            $request,
            [
                "iPersId"        => "required|integer",
                "iModuloId"       => "required|integer",
                "iDocenteId"     => "required|integer",
                "iLaboratorioId" => "required|integer",
                "dFechaExamen"   => "required",
                "hHoraExamen"    => "required"
            ]
        );
        $parameters = [
            $request->iPersId,
            $request->iModuloId,
            $request->iDocenteId,
            3,
            $request->iLaboratorioId,
            $request->dFechaExamen,
            $request->hHoraExamen,
            gethostname(),
            gethostname(),
            $request->getClientIp()
        ];

        try {
            $alumno = DB::select('exec [acad].[Sp_CCTIC_INS_Examenes_Genera_ExamenSuficiencia] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parameters);
            $response = ['validated' => true, 'data' => $alumno,  'message' => 'datos guardados correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], $e->getMessage()];
            $responseCode = 500;
        }
        return response()->json($response, $responseCode);
    }

    public function obtenerGruposToUbicacion(Request $request)
    {
        // Para examen de ubicacion
        $this->validate(
            $request,
            [
                "iModuloId"       => "required|integer"
            ]
        );
        $parameters = [
            $request->iModuloId,
        ];
        try {
            $grupos =  DB::select('exec [acad].[Sp_CCTIC_SEL_Examenes_Ubicacion_ListadoGrupos] ?', $parameters);
            $response = ['validated' => true, 'data' => $grupos,  'message' => 'datos obtenidos correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], $e->getMessage()];
            $responseCode = 500;
        }
        return response()->json($response, $responseCode);
    }
    public function asignaGrupoAExamenUbicacion(Request $request)
    {
        // Para examen de ubicacion
        $this->validate(
            $request,
            [
                "iExamenId"       => "required|integer",
                "iGrupoId"       => "required|integer"
            ]
        );
        $parameters = [
            $request->iExamenId,
            $request->iGrupoId,
        ];
        try {
            $criterios =  DB::select('exec [acad].[Sp_CCTIC_SEL_Examenes_Ubicacion_AsignarGrupo] ?, ?', $parameters);
            $response = ['validated' => true, 'data' => $criterios,  'message' => 'datos obtenidos correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], $e->getMessage()];
            $responseCode = 500;
        }
        return response()->json($response, $responseCode);
    }
}
