<?php

namespace App\Http\Controllers\AulaVirtual;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;

class RubricaController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set("America/Lima");
    }
    public function store(Request $request)
    {
        $parametros = [
            $request->iActividadesId,
            $request->conPuntuacion,

            auth()->user()->cCredUsuario,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac',

            $request->json_criterio_nivel,
        ];

        try {
            $queryResult = \DB::select('exec [aula].[Sp_INS_RubricaCriterioNivel] ?, ?, ?, ?, ?, ?, ?', $parametros );

            $response = ['validated' => true, 'mensaje' => 'Se actualizó su información exitosamente.', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }

    public function getRubricaActividad($actividadId)
    {
        $rubrica = DB::select('exec [aula].[Sp_SEL_RubricaCriterioNivelXiActividadesId] ?', [ $actividadId ]);

        return response()->json( $rubrica );
    }

    public function guardarRespNivelEstudiante(Request $request)
    {
        $parametros = [
            $request->actividadId,
            $request->personaId,
            $request->estudianteId,
            $request->docenteId,

            $request->iRubCritNivelId,

            auth()->user()->cCredUsuario,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac',
        ];

        try {
            $queryResult = \DB::select('exec [aula].[Sp_INS_UPD_actividades_respuestas_detalle] ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros );

            $rubrica = DB::select('exec [aula].[Sp_SEL_RubricaCriterioNivelXiActividadesIdXiPersId] ?, ?', [ $request->actividadId, $request->personaId ]);

            $response = ['validated' => true, 'mensaje' => 'Se guardó la respuesta correctamente.', 'queryResult' => $rubrica ];

            $codeResponse = 200;
        } catch (\Esxception $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }

    public function getEvalRubricaEstudiante($actividadId, $personaId)
    {
        $rubrica = DB::select('exec [aula].[Sp_SEL_RubricaCriterioNivelXiActividadesIdXiPersId] ?, ?', [ $actividadId, $personaId ]);

        return response()->json( $rubrica );
    }

    public function getRubricasDocente($docenteId, $cicloAcad, $cursoId)
    {
        $rubricas = DB::select('exec [aula].[Sp_SEL_RubricasXiDocenteIdXiControlCicloAcad] ?, ?, ?', [ $docenteId, $cicloAcad, $cursoId ]);

        return response()->json( $rubricas );
    }

    public function clonarRubrica(Request $request, $nuevaActividadId, $actividadIdRubrica)
    {
        $rubrica = DB::select('exec [aula].[Sp_SEL_RubricaCriterioNivelXiActividadesId] ?', [ $actividadIdRubrica ]);

        $criterios = json_decode($rubrica[0]->json_rubrica_criterio);

        $criteriosFormatted = [];

        for ($i=0; $i < count($criterios); $i++) { 
            $criterio = [];
            $criterio = [ 'cRubCritTitulo' => $criterios[$i]->cRubCritTitulo, 'cRubCritDsc' => $criterios[$i]->cRubCritDsc, 'nivel' => [] ];
            for ($j=0; $j < count($criterios[$i]->json_rubrica_criterio_nivel); $j++) { 
                $criterio['nivel'][] = [ 
                    'iPuntaje' => $criterios[$i]->json_rubrica_criterio_nivel[$j]->iPuntaje,
                    'cRubCritNvlTitulo' => $criterios[$i]->json_rubrica_criterio_nivel[$j]->cRubCritNvlTitulo,
                    'cRubCritNvlDsc' => $criterios[$i]->json_rubrica_criterio_nivel[$j]->cRubCritNvlDsc,
                ];
            }
            $criteriosFormatted[] = $criterio;
        }

        $parametros = [
            $nuevaActividadId,
            $rubrica[0]->bConPuntuacion,

            auth()->user()->cCredUsuario,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac',

            json_encode($criteriosFormatted),
        ];

        

        try {
            $queryResult = \DB::select('exec [aula].[Sp_INS_RubricaCriterioNivel] ?, ?, ?, ?, ?, ?, ?', $parametros );

            $response = ['validated' => true, 'mensaje' => 'Se actualizó su información exitosamente.', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }

        return response()->json( $response, $codeResponse );
    }

    public function guardarNivelGrupalRubrica(Request $request)
    {
        DB::beginTransaction();
        
        try {
            foreach ($request->estudiantes as $estudiante) {
                $parametros = [
                    $request->actividadId,
                    $estudiante['iPersId'],
                    NULL,
                    $request->docenteId,

                    $request->iRubCritNivelId,
        
                    auth()->user()->cCredUsuario,
                    'equipo',
                    $request->server->get('REMOTE_ADDR'),
                    'mac',
                ];

                $queryResult = \DB::select('exec [aula].[Sp_INS_UPD_actividades_respuestas_detalle] ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros );
            }
            DB::commit();

            $rubrica = DB::select('exec [aula].[Sp_SEL_RubricaCriterioNivelXiActividadesIdXiPersId] ?, ?', [ $request->actividadId, $request->estudiantes[0]['iPersId'] ]);

            $response = ['validated' => true, 'mensaje' => 'Se guardó la respuesta correctamente.', 'queryResult' => $rubrica ];

            $codeResponse = 200;

        } catch (\Exception $e) {
            DB::rollback();

            $response = ['validated' => true, 'message' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }

        return response()->json( $response, $codeResponse );
    }
}
