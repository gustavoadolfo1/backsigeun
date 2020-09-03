<?php

namespace App\Http\Controllers\AulaVirtual;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GrlConfiguracionGeneral;
use App\GrlReniec;

use Hashids\Hashids;

class RecursosController extends Controller
{
    private $hashids;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->hashids = new Hashids('SIGEUN UNAM', 15);
        date_default_timezone_set("America/Lima");
    }

    public function obtenerCursos($dni)
    {
        try {
            $queryResult = \DB::select("exec [aula].[Sp_SEL_cursosDisponibles] ?, ?", [ $dni, null ]);
            $codeResponse = 200;

            $queryResult = $queryResult[0];

            foreach ($queryResult as $key => $value) {
                $queryResult->$key = json_decode($value);
                if ($key == 'cursos_estudiante' && $queryResult->$key != null) {
                    foreach ($queryResult->$key as $row) {
                        $row->hashedId = $this->hashids->encode($row->iCurricCursoId, $row->iSeccionId, $row->iFilId, $row->iDocenteId, $row->iControlCicloAcad);
                    }
                }
                if ($key == 'cursos_docente' && $queryResult->$key != null) {
                    foreach ($queryResult->$key as $row) {
                        $row->hashedId = $this->hashids->encode($row->iCurricCursoId, $row->iSeccionId, $row->iFilId, $row->iDocenteId, $row->iControlCicloAcad);
                    }
                }
            }

        } catch (\aException $e) {
            $queryResult = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }

        return response()->json($queryResult, $codeResponse);
    }

    public function obtenerDatosCurso($hashedId)
    {
        $ids = $this->hashids->decode($hashedId); //iCurricCursoId, iSeccionId, iFilId, iDocenteId, iControlCicloAcad

        $queryResult = \DB::select("exec [aula].[Sp_SEL_cursosSeleccionado] ?, ?, ?, ?, ?", $ids);
        // $grid0 = \DB::select("exec aula.Sp_SEL_actividadesMatrix ?, ?, ?, ?", [ $ids[4], $ids[0], $ids[1], $ids[2] ]);
        //@iControlCicloAcad int=20192, @iCurricCursoId int=676, @iSeccionId int=1, @iFilId int=1
        // $grid = \DB::select("exec [aula].[Sp_SEL_actividadesMatrixHeaders] ?, ?, ?, ?", [ $ids[4], $ids[0], $ids[1], $ids[2] ]);
        //exec aula.Sp_SEL_actividadesMatrixHeaders 20201,676,1,1
        //@iControlCicloAcad int=20192, @iCurricCursoId int=676, @iSeccionId int=1, @iFilId int=1

        $queryResult = $queryResult[0];

        foreach ($queryResult as $key => $value) {
            $queryResult->$key = json_decode($value);
        }
        // $queryResult->grid = json_decode(json_encode($grid),true);
        // $queryResult->grid0 = json_decode(json_encode($grid0),true);

        $curso = \DB::table('ura.curriculas_cursos')->where('iCurricCursoId', $ids[0])->first();

        $estadoSilabo = \DB::table('ura.silabo_actual')->where([
            [ 'iControlCicloAcad', $ids[4] ],
            [ 'iFilId', $ids[2] ],
            [ 'iCarreraId', $curso->iCarreraId ],
            [ 'iCurricId', $curso->iCurricId ],
            [ 'cSilActCodCurso', $curso->cCurricCursoCod ],
            [ 'iSeccionId', $ids[1] ],
            [ 'iDocenteId', $ids[3] ],
        ])->first();
            
        $hasSilabo = null;
        if ($estadoSilabo) {
            $hasSilabo = $estadoSilabo->iEstadoSilabo;
        }

        $carrera = \DB::table('ura.carreras')->where('iCarreraId', $curso->iCarreraId)->first();

        $queryResult->cCurso[0]->hasSilabo = $hasSilabo;
        $queryResult->cCurso[0]->cCarreraDsc = $carrera->cCarreraDsc;
        $queryResult->cCurso[0]->iDocenteId = $ids[3];
        $queryResult->cCurso[0]->iControlCicloAcad = $ids[4];
        $queryResult->cCurso[0]->iCurricCursoId = $ids[0];

        return response()->json( $queryResult );
    }
    public function matrisNotas($hashedId, $iPersIdEstudiante = 0){
        $ids = $this->hashids->decode($hashedId);
        $queryResult = \DB::select("exec aula.Sp_SEL_actividadesMatrix ?, ?, ?, ?, ?", [ $ids[4], $ids[0], $ids[1], $ids[2], $iPersIdEstudiante ]);
        $queryResult2 = \DB::select("exec [aula].[Sp_SEL_actividadesMatrixHeaders] ?, ?, ?, ?", [ $ids[4], $ids[0], $ids[1], $ids[2] ]);
        return response()->json( ['box1' => $queryResult, 'box2'=> $queryResult2] );
    }
    public function obtenerTemasCurso($hashedId)
    {
        $ids = $this->hashids->decode($hashedId); //iCurricCursoId, iSeccionId, iFilId, iDocenteId, iControlCicloAcad

        $queryResult = \DB::select("exec [aula].[Sp_SEL_temasCursosXiDocenteIdXiCurricCursoIdXiFilId] ?, ?, ?, ?, 0 ,?", [ $ids[3], $ids[0], $ids[2], $ids[4] , $ids[1] ]);

        $unidades = [];

        foreach ($queryResult as $value) {
            $value->json_actividades = json_decode($value->json_actividades);

            $unidades[$value->cUnidad][] = $value;
        }

        $response = [ 'temas' => $queryResult, 'unidades' => $unidades ];

        return response()->json( $response );
    }
    public function obtenerTemasCursoEst(Request $request)
    {
        $ids = $this->hashids->decode($request->code); //iCurricCursoId, iSeccionId, iFilId, iDocenteId, iControlCicloAcad

        $queryResult = \DB::select("exec [aula].[Sp_SEL_temasCursosXiDocenteIdXiCurricCursoIdXiFilId_v2] ?, ?, ?, ?, ?, ?", [ $ids[3], $ids[0], $ids[2], $ids[4], $request->pers, $ids[1] ]);

        $unidades = [];

        foreach ($queryResult as $value) {
            $value->json_actividades = json_decode($value->json_actividades);

            $unidades[$value->cUnidad][] = $value;
        }

        $response = [ 'temas' => $queryResult, 'unidades' => $unidades ];

        return response()->json( $response );
    }
    public function obtenerTiposRecurso()
    {
        $queryResult = \DB::select('exec [aula].[Sp_SEL_tiposRecursos]');

        return response()->json( $queryResult );
    }

    public function obtenerEstudiantes($hashedId)
    {
        $ids = $this->hashids->decode($hashedId); //iCurricCursoId, iSeccionId, iFilId, iDocenteId, iControlCicloAcad

        $queryResult = \DB::select("exec [aula].[Sp_SEL_personasCursoXiCurricCursoIdXiFilIdXiSeccionIdXiDocenteXiControlCicloAcad] ?, ?, ?, ?", [ $ids[0], $ids[2], $ids[1], $ids[4] ]);

        $configGrl = GrlConfiguracionGeneral::get();
        $configGrlFotoPath = $configGrl->where('cConfigGrlesNombre', 'rutaPersonas')->first();
        // $ids = [];
       
        for ($i=0; $i < count($queryResult); $i++) { 
            if (!($queryResult[$i]->cPersFotografia == null)) {
                $configGrlFotoPath = $configGrl->where('cConfigGrlesNombre', 'rutaPersonas')->first();
                $queryResult[$i]->srcFoto = $configGrlFotoPath->cConfigGrlesValor . $queryResult[$i]->cPersFotografia;
            }
        }

        // $reniecFotos = GrlReniec::select('iPersId', 'cReniecFotografia')->whereIn('iPersId', $ids)->get();

        // for ($i=0; $i < count($queryResult); $i++) { 
        //     foreach ($reniecFotos as $reniecFoto) {
        //         if ($reniecFoto->iPersId == $queryResult[$i]->iPersId) {
        //             if (isset($reniecFoto->cReniecFotografia)) {
        //                 $configGrlFotoPath = $configGrl->where('cConfigGrlesNombre', 'rutaReniec')->first();
        //                 $queryResult[$i]->srcFoto = $configGrlFotoPath->cConfigGrlesValor . $reniecFoto->cReniecFotografia;
        //             }
        //             break;
        //         }
                
        //     }
        // }

        return response()->json( $queryResult );
    }
    public function obtenerEstudiantesv2($hashedId,$act)
    {
        $ids = $this->hashids->decode($hashedId); //iCurricCursoId, iSeccionId, iFilId, iDocenteId, iControlCicloAcad

        $queryResult = \DB::select("exec [aula].[Sp_SEL_personasCursoActividadesResumenXiCurricCursoIdXiFilIdXiSeccionIdXiDocenteXiControlCicloAcad]  ?, ?, ?, ?, ?", [ $ids[0], $ids[2], $ids[1], $ids[4], $act ]);
        return response()->json( $queryResult );
    }
    public function obtenerEstudiantesForo($hashedId,$act)
    {
        $ids = $this->hashids->decode($hashedId); //iCurricCursoId, iSeccionId, iFilId, iDocenteId, iControlCicloAcad

        $queryResult = \DB::select("exec [aula].[Sp_SEL_personasCursoActividadesResumenXiCurricCursoIdXiFilIdXiSeccionIdXiDocenteXiControlCicloAcadForo]  ?, ?, ?, ?, ?", [ $ids[0], $ids[2], $ids[1], $ids[4], $act ]);
        return response()->json( $queryResult );
    }
    public function obtenerEstudiantesExamen($hashedId,$act)
    {
        $ids = $this->hashids->decode($hashedId); //iCurricCursoId, iSeccionId, iFilId, iDocenteId, iControlCicloAcad

        $queryResult = \DB::select("exec [aula].[Sp_SEL_personasCursoActividadesResumenXiCurricCursoIdXiFilIdXiSeccionIdXiDocenteXiControlCicloAcadExamen]  ?, ?, ?, ?, ?", [ $ids[0], $ids[2], $ids[1], $ids[4], $act ]);
        return response()->json( $queryResult );
    }
    public function obtenerTareasEstudiante($activId,$iPersId,$iDocenteId){
        
        $queryResult = \DB::select('exec [aula].[Sp_SEL_tiposRecursos]');
        return response()->json( $queryResult );
    }
    public function addRecurso(Request $request){
        $predata = $request->all();
        try {
            if($request->has('files')){
                foreach($predata['files'] as $key=>$file){
                    $data = \DB::table('aula.actividades_recursos')->insert([
                        'iActividadesId' => $request->idActividad,
                        'iArchivoId' => $file['iArchivoId'],
                        'cActividadesRecRuta' => $file['address'],
                        'iTiposRecId' => 1,
                        'cActividadesRecUsuarioSis' => auth()->user()->cCredUsuario,
                        'dtActividadesRecFechaSis' => date("Y-m-d\TH:i:s"),
                        'cActividadesRecEquipoSis' => 'equipo',
                        'cActividadesRecIpSis' => $request->server->get('REMOTE_ADDR') ,
                        'cActividadesRecOpenUsr' => 1,
                        'cActividadesRecMacNicSis' =>  'mac',
                    ]);
                }
            }
            if($request->has('videos')){
                foreach($predata['videos'] as $key=>$video){
                    $data = \DB::table('aula.actividades_recursos')->insert([
                        'iActividadesId' => $request->idActividad,
                        'iArchivoId' => 0,
                        'cActividadesRecRuta' => $video,
                        'iTiposRecId' => 2,
                        'cActividadesRecUsuarioSis' => auth()->user()->cCredUsuario,
                        'dtActividadesRecFechaSis' => date("Y-m-d\TH:i:s"),
                        'cActividadesRecEquipoSis' => 'equipo',
                        'cActividadesRecIpSis' => $request->server->get('REMOTE_ADDR') ,
                        'cActividadesRecOpenUsr' => 1,
                        'cActividadesRecMacNicSis' =>  'mac',
                    ]);
                }
            }
            if($request->has('links')){
                foreach($predata['links'] as $key=>$url){
                    $data = \DB::table('aula.actividades_recursos')->insert([
                        'iActividadesId' => $request->idActividad,
                        'iArchivoId' => 0,
                        'cActividadesRecRuta' => $url,
                        'iTiposRecId' => 3,
                        'cActividadesRecUsuarioSis' => auth()->user()->cCredUsuario,
                        'dtActividadesRecFechaSis' => date("Y-m-d\TH:i:s"),
                        'cActividadesRecEquipoSis' => 'equipo',
                        'cActividadesRecIpSis' => $request->server->get('REMOTE_ADDR') ,
                        'cActividadesRecOpenUsr' => 1,
                        'cActividadesRecMacNicSis' =>  'mac',
                    ]);
                }
            }

            $response = ['validated' => true, 'mensaje' => 'Se ha creado la nueva actividad.', 'data' => $data];
            $codeResponse = 200;

        } catch (\fException $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
    }
    public function deleteRecurso($idRecurso){
        $delete = \DB::table('aula.actividades_recursos')->where('iActividadesRecId', $idRecurso)->delete();
    }
}
