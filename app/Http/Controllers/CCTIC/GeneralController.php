<?php

namespace App\Http\Controllers\CCTIC;

use App\Http\Controllers\PideController;
use App\Repositories\cctic\CursoRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Repositories\cctic\PlanTrabajoRepository;

class GeneralController extends Controller
{

    public function __construct(PlanTrabajoRepository $curricula, CursoRepository $curso)
    {
        $this->planTrabajo = $curricula;
        $this->curso = $curso;
    }
    public function obtenerFiliales()
    {
        try {
            $filiales = \DB::select('[acad].[SP_SEL_filiales]');
            $response = ['validated' => true, 'data' => $filiales];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }

    public function obtenerCarrerasXprogramAcadFilial($programAacd, $filial)
    {
        $preins = new PreInscripcionController();
        try {
            $carreras = \DB::select('[acad].[SP_SEL_carrerasXprogramAcadFilial] ?, ?', [$programAacd, $filial]);
            foreach ($carreras as $key => $value) {
                $carreras[$key]->modulos = $preins->obtenerModulosByCarrera($value->iCarreraId, $programAacd);
            }
            $response = ['validated' => true, 'data' => $carreras];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }

    public function obtenerFilialesValidByCarreraID($carreraID)
    {
        try {
            $filiales = \DB::select('[acad].[SP_SEL_Filial_activa] ?', [$carreraID]);
            $response = ['validated' => true, 'data' => $filiales];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }

    public function obtenerGruposXProgramAcad($programAcad)
    {
        try {
            $grupos = \DB::select('select * from acad.grupos WHERE iProgramasAcadId = ?', [$programAcad]);
            $response = ['validated' => true, 'data' => $grupos];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => []];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }

    public function pide($dni)
    {

        //Iniciamos una sesion
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        //Indicamos que queremos imprimir el resultado
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //Hacemos uso de un User Agent
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        //Enviamos los datos por post
        curl_setopt($ch, CURLOPT_URL, "http://200.48.160.218:8081/api/pide/reniec?dni=" . $dni);
        //Ejecutamos e imprimimos el resultado
        $pide = json_decode(curl_exec($ch));

        return response()->json($pide);
    }


    public function obtenerTurnos()
    {
        $parameters = [
            Input::get('filial'),
            Input::get('programa')
        ];

        try {
            $turnos = \DB::select('SELECT * FROM acad.turnos where iFilId_xx = ? and iProgramasAcadId = ?', $parameters);

            $response = ['validated' => true, 'message' => 'turnos obtenidos correctamente', 'data' => $turnos];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => true, 'message' => 'no se pudo obtener los turnos', 'data' => []];
            $responseCode = 500;
        }

        return response()->json([$response, $responseCode]);
    }

    public function getIdentificacionesTipos()
    {
        try {
            $documents = DB::table('grl.tipo_Identificaciones as ti')
                ->where([['iTipoIdentId', '<>', 2], ['iTipoIdentId', '<>', 4]])
                ->get(['ti.iTipoIdentId', 'ti.cTipoIdentSigla']);
            $response = ['validated' => true, 'data' => $documents, 'message' => 'Horarios obtenidos con éxito'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }
        return response()->json($response, $responseCode);
    }
    public function getNacionalidades()
    {
        try {
            $nacionalidades = DB::table('grl.nacionalidades as na')
                ->get(['na.iNacionId', 'na.cNacionNombre']);
            $response = ['validated' => true, 'message' => 'turnos obtenidos correctamente', 'data' => $nacionalidades];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }
        return response()->json($response, $responseCode);
    }
    //  curriculas
    public function obtenerCurriculas()
    {
        try {
            $curriculas = $this->planTrabajo->obtenerPlanTrabajo();
            $response = ['validated' => true, 'data' => $curriculas, 'message' => 'datos seleccionados correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function obtenerCurriculasDisponibles(Request $request)
    {
        $filial = $request->input('iFilId');
        $programAcad = $request->input('iProgramasAcadId');
        $estado = $request->input('estado');

        try {
            $planesAcad = $this->planTrabajo->obtenerPlanesTrabajo($filial, $programAcad);

            if (count($planesAcad) == 0) {
                return response()->json(['validated' => true, 'data' => $planesAcad, 'message' => 'no se encontro ningun plan de trabajo'], 400);
            }

            $firstCurso = $this->curso->getCursos($planesAcad[0]->iPlanTrabajoId, $filial, $programAcad, $estado);

            $data = [
                'planesAcad' => $planesAcad,
                'cursos' => $firstCurso
            ];
            $response = ['validated' => true, 'data' => $data, 'message' => 'datos obtenidos correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function checkDNI(Request $request)
    {

        $pide = new PideController();

        try {

            $persona = DB::select('exec [acad].[SP_SEL_personaMatricula] @cPersDocumento = ?', [$request->dni]);


            if (count($persona) != 0) {
                $persona = $persona[0];

                if ($persona->iPersId != 0) {
                    $persona->pide = false;
                    if ($persona->tipoIngresante == 'ESTUDIANTE') {
                        $persona->datosEstudiante = json_decode($persona->datosEstudiante);
                    }
                    if ($persona->bPreinscrito == 1) {
                        $persona->preinscripcion = json_decode($persona->preinscripcion, 1);
                    }
                }

                if ($persona->iPersId == 0) {

                    $pideResp = $pide->consultar($request, 'reniec');
                    $personaData = $pideResp->original;
                    if (!$personaData['error']) {
                        $persona->iPersId = $personaData['data']->iPersId;
                        $persona->cPersDocumento = $personaData['data']->cReniecDni;
                        $persona->cPersPaterno = $personaData['data']->cReniecApel_pate;
                        $persona->cPersMaterno = $personaData['data']->cReniecApel_mate;
                        $persona->cPersNombre = $personaData['data']->cReniecNombres;
                        $persona->cReniecDireccion = $personaData['data']->cReniecDireccion;
                    }
                    $persona->pide = true;
                }
            }

            $response = ['validated' => true, 'data' => $persona, 'message' => 'Datos obtenidos correctamente',];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => 'No se pudo obtener el dni', 'error' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }


    public function listaPersonasTipo(Request $request)
    {

        $params = [
            $request->dni,
            $request->iProgramAcad
        ];

        $pide = new PideController();

        try {

            $listado = DB::select('exec [acad].[Sp_CCTIC_SEL_Panel_Muestra_ListadoPersonas] ?, ?', $params);

            $data= [
                'listado' => $listado,
                'persona' => null,
                'pide' => false
            ];

            $response = ['validated' => true, 'data' => $data, 'message' => 'Datos obtenidos correctamente',];
            $responseCode = 200;
        } catch (\Exception $e) {
            $pideResp = $pide->consultar($request, 'reniec');
            $personaData = $pideResp->original;

            $data = [
                'listado' => [],
                'persona' => $personaData,
                'pide' => false
            ];

            $response = ['validated' => false, 'message' => 'No se pudo obtener los datos', 'error' => $e->getMessage(), 'data' => $data];
            $responseCode = 500;

        }

        return response()->json($response, $responseCode);
    }


    public function tiposConceptos()
    {
        try {

            $listado = DB::select('exec [acad].[Sp_CCTIC_SEL_General_Listado_TipoConcepto] ');

            $response = ['validated' => true, 'data' => $listado, 'message' => 'Lista de tipo conceptos obtenidos correctamente', ];
            $responseCode = 200;

        } catch(\Exception $e) {
            $response = ['validated' => false, 'message' => 'No se pudo obtener la lista de tipo conceptos', 'error' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);

    }

    public function listaConceptos($tipoConcepto)
    {
        try {

            $listado = DB::select('exec [acad].[Sp_CCTIC_SEL_General_Listado_Conceptos] ?', [$tipoConcepto]);

            $response = ['validated' => true, 'data' => $listado, 'message' => 'Lista de conceptos obtenidos correctamente', ];
            $responseCode = 200;

        } catch(\Exception $e) {
            $response = ['validated' => false, 'message' => 'No se pudo obtener la lista de  conceptos', 'error' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }


}
