<?php

namespace App\Http\Controllers\Ura;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\UraEstudiante;
use App\UraControlCicloAcademico;
use App\Http\Controllers\PideController;

class UraEstudianteController extends Controller
{
    /**
     * Obtiene los estudiantes 
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscarEstudiantes(Request $request)
    {
        $data = \DB::select('exec ura.Sp_OBU_SEL_estudiantesPaginadoXcBusquedaXsSortDirXpageNumberXpageSize ?, ?, ?, ?', array($request->busqueda, $request->orden ?? 'asc', $request->pagina ?? 1, $request->nRegistros ?? 10));

        return response()->json( $data );
    }


    /**
     * Obtiene los estudiantes observados
     * 
     * Mod: DASA - Procesar
     */
    public function obtenerEstudiantesObservados()
    {
        $ciclo = UraControlCicloAcademico::where('iControlEstado', 1)->first();
        $estudiantes = \DB::select('exec adm.Sp_SEL_observados_ingresantesXiGrupoControl ?', [ $ciclo->iControlCicloAcad ]);

        return response()->json( $estudiantes );
    }

    /**
     * Obtiene los estudiantes que debe egresar en el ciclo académico
     * 
     * Mod: DASA - Procesar
     */
    public function obtenerEstudiantesAEgresarPorCarreraPlan($carreraId, $curricId)
    {
		$estudiantes = \DB::select('exec [ura].[Sp_GRAL_PROC_egresados] ?, ?', array( $carreraId, $curricId));

        return response()->json( $estudiantes );
    }

    /**
     * Obtiene los estudiantes con Promedio Ponderado acumulado
     * 
     * Mod: DASA - Procesar
     */
    public function obtenerEstudiantesPPA($grupo,$carreraId,$ciclo)
    {
        $data = [
            $grupo,
            $carreraId,
            $ciclo
        ];
		$estudiantes = \DB::select('exec [ura].[Sp_GRAL_PROC_promedioPonderadoAcumulado] ?,?,?', $data);

        return response()->json( $estudiantes );
    }

    /**
     * Obtiene los estudiantes con Promedio Ponderado semestral
     * 
     * Mod: DASA - Procesar
     */
    public function obtenerEstudiantesPPS($carreraId, $cicloAcad)
    {
		$estudiantes = \DB::select('exec [ura].[Sp_GRAL_PROC_promedioPonderadoSemestral] ?, ?', array( $carreraId, $cicloAcad));

        return response()->json( $estudiantes );
    }

    /**
     * Verifica los requisitos en Obu
     * 
     * Mod: Estudiante - Matricula
     */
    public function verificarRequisitosOBU(Request $request, $codUniv, $dni)
    {
        $data = \DB::select('exec [ura].[Sp_OBU_verificarRequisitosMatriculaXcEstudCodUniv] ?', array( $codUniv ));

        $cicloAcademico = \DB::select('exec ura.[Sp_GRAL_cicloAcademicoActivo]');
        
        $data[0]->seguro_proveedor = null;
        $data[0]->errorPIDE = null;
        // return response()->json($data);
         if ($data[0]->bCheckObuSeguro == 1) {

             $request = new \Illuminate\Http\Request();
             $request->replace(['dni' => $dni]);
            
             $pc = new PideController();
             $response = $pc->consultar( $request, 'seguro', null, true);
             //return response()->json( $response );
             //$data[0]->response = $response;
             if(!$response['error']) {
                 $response = $response['data'];
                 if($response->vigencia || $response->vigencia == 1) {
                     $data[0]->bCheckObuSeguro = 1;
                     $data[0]->seguro_proveedor = $response->tipo_seguro;
                    
                     $queryResponse = \DB::select('exec ura.Sp_OBU_INS_UPD_check ?, ?, ?, ?, ?, ?, ?, ?, ?', array( 2, 1, $codUniv, $cicloAcademico[0]->iControlCicloAcad, $response->tipo_seguro, 'user', 'equipo', $request->server->get('REMOTE_ADDR'), 'mac' ));
                 }
             }
             else {
                 $data[0]->errorPIDE = $response['msg'];
             }
        }

        return response()->json( $data[0] );
    }

}