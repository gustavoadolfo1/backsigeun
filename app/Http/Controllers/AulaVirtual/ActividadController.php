<?php

namespace App\Http\Controllers\AulaVirtual;

use App\Notifications\Actividades;
use App\UraEstudiante;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Hashids\Hashids;
use Illuminate\Support\Facades\Notification;

class ActividadController extends Controller
{
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

    /**
     * Crea una nueva actividad
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'titulo' => 'required',
                'descripcion' => 'required',
                'tipoActividadId' => 'required',
                'temaId' => 'required',
                'hashedId' => 'required'
            ],
            [
                'titulo.required' => 'El campo Título es obligatorio',
                'descripcion.required' => 'El campo Descripción es obligatorio',
                'tipoActividadId.required' => 'Debe escoger un tipo de actividad',
                'temaId.required' => 'Hubo un problema al obtener información del tema.',
                'hashedId.required' => 'Hubo un problema al obtener información de la actividad.'
            ]
        );
        $predata = $request->all();
        //return response()->json( $predata['files'] );
        $ids = $this->hashids->decode($request->hashedId); //iCurricCursoId, iSeccionId, iFilId, iDocenteId, iControlCicloAcad


        $ini = str_replace("T", " ", $request->fechaEntregaString);
        $fin = str_replace("T", " ", $request->fechaFinString);


        $parametros = [
            $request->actividadId,
            $request->titulo,
            $request->descripcion,
            $request->fechaEntregaString,
            $request->fechaFinString,
            $request->fechaPublicacionString,
            $request->tipoActividadId,
            $request->tipo_eval ?? NULL,
            $ids[0],
            $ids[1],
            $ids[3],
            $ids[2],
            $request->temaId,
            $request->respEstud,
            $request->editResp,
            $request->calificada,
            $request->cicloAcad,
            auth()->user()->cCredUsuario,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];
        try {
            $data = \DB::select('exec [aula].[Sp_INS_Actividades] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros);
            $idActividad = $data[0]->id;
            if($request->has('files')){
                foreach($predata['files'] as $key=>$file){
                    $result = \DB::table('aula.actividades_recursos')->insert([
                        'iActividadesId' => $idActividad,
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
                    $result = \DB::table('aula.actividades_recursos')->insert([
                        'iActividadesId' => $idActividad,
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
                    $result = \DB::table('aula.actividades_recursos')->insert([
                        'iActividadesId' => $idActividad,
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


            // if (isset($idActividad)) {

            //     $actividad = collect( DB::select('exec [aula].[Sp_SEL_ActividadesXiActividadesId] ? ', [ $idActividad ]) )->first();
            //     if ($actividad){
            //         $curso =  collect(DB::select('select * from ura.curriculas_cursos where iCurricCursoId =  ? ', [ $actividad->iCurricCursoId ]))->first();
            //         $estudiantes = collect(DB::select('exec [aula].[Sp_SEL_personasCursoActividadesResumenXiCurricCursoIdXiFilIdXiSeccionIdXiDocenteXiControlCicloAcadExamen]  ?, ?, ?, ?, ?', [
            //             $actividad->iCurricCursoId,
            //             $actividad->iFilId,
            //             $actividad->iSeccionId,
            //             $actividad->iControlCicloAcad,
            //             $idActividad
            //         ]));

            //         $codUnivEstudiantes = $estudiantes->pluck('cEstudCodUniv')->toArray();

            //         $estudiantesEnviar = UraEstudiante::whereIn('cEstudCodUniv', $codUnivEstudiantes)->get();

            //         Notification::send($estudiantesEnviar, new Actividades($curso, $actividad));
            //     }
            // }

            $response = ['validated' => true, 'mensaje' => 'Se ha creado la nueva actividad.', 'data' => $data];
            $codeResponse = 200;

        } catch (\fException $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }

    public function obtenerTiposActividad()
    {
        $queryResult = \DB::select('exec [aula].[Sp_SEL_tiposActividades]');

        return response()->json( $queryResult );
    }

    public function obtenerActividadesTema($temaId)
    {
        $queryResult = \DB::select('exec ura.[Sp_SEL_ActividadesXiSecuActId] ? ', [ $temaId ]);

        return response()->json( $queryResult );
    }

    public function obtenerDetallesActividad($atividadId)
    {
        $queryResult = \DB::select('exec [aula].[Sp_SEL_ActividadesXiActividadesId] ? ', [ $atividadId ]);
        $curso =  \DB::select('select * from ura.curriculas_cursos where iCurricCursoId =  ? ', [ $queryResult[0]->iCurricCursoId ]);
        $files = \DB::select('select  ac.iActividadesRecId,ac.iActividadesId,ac.iArchivoId,ar.cNombre,ar.cTipo, ar.cPeso, ar.address from aula.actividades_recursos  as ac inner join aula.archivos as ar on ar.iArchivoId = ac.iArchivoId where ac.iActividadesId =  ? ', [ $atividadId ]);
        $videos = DB::table('aula.actividades_recursos')->where('iActividadesId', $atividadId)->where('iTiposRecId', 2)->get();
        $links = DB::table('aula.actividades_recursos')->where('iActividadesId', $atividadId)->where('iTiposRecId', 3)->get();
        $queryResult[0]->files =  $files;
        $queryResult[0]->curso =  $curso;
        $queryResult[0]->videos =  $videos;
        $queryResult[0]->links =  $links;
        $queryResult[0]->hashedId = $this->hashids->encode($queryResult[0]->iCurricCursoId, $queryResult[0]->iSeccionId, $queryResult[0]->iFilId, $queryResult[0]->iDocenteId, $queryResult[0]->iControlCicloAcad);

        return response()->json( $queryResult[0] );
    }
    public function obtenerRecursosActividad($atividadId)
    {
        $files = \DB::select('select  ac.iActividadesRecId,ac.iActividadesId,ac.iArchivoId,ar.cNombre,ar.cTipo, ar.cPeso, ar.address from aula.actividades_recursos  as ac inner join aula.archivos as ar on ar.iArchivoId = ac.iArchivoId where ac.iActividadesId =  ? ', [ $atividadId ]);
        $videos = DB::table('aula.actividades_recursos')->where('iActividadesId', $atividadId)->where('iTiposRecId', 2)->get();
        $links = DB::table('aula.actividades_recursos')->where('iActividadesId', $atividadId)->where('iTiposRecId', 3)->get();
        return response()->json( ['files'=> $files, 'videos'=> $videos, 'links'=> $links ] );
    }

    public function destroy($atividadId)
    {
        try {
            $queryResult = \DB::select('exec [aula].[Sp_DEL_ActividadesXiActividadesId] ? ', [ $atividadId ]);

            if ($queryResult[0]->iRowcount > 0) {
                $response = [ 'mensaje' => "Se ha eliminado la actividad.", 'rowCount' => $queryResult[0]->iRowcount];
                $codeResponse = 200;
            }
            else {
                $response = [ 'mensaje' => "No se ha podido eliminar la actividad.", 'rowCount' => $queryResult[0]->iRowcount];
                $codeResponse = 500;
            }
        } catch (\fException $e) {

            $response = ['mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
            $codeResponse = 500;
        }

        return response()->json( $response, $codeResponse );
    }

    public function obtenerActividadesNotas($hashedId)
    {
        $ids = $this->hashids->decode($hashedId); //iCurricCursoId, iSeccionId, iFilId, iDocenteId, iControlCicloAcad

        $actividades = \DB::select('exec [aula].[Sp_SEL_actividadesNotasEstudiantesXiCurricCursoIdXiSeccionIdXiDocenteIdXiFilId] ?, ?, ?, ?', $ids);

        $estudiantes = \DB::select("exec [aula].[Sp_SEL_personasCursoXiCurricCursoIdXiFilIdXiSeccionIdXiDocenteXiControlCicloAcad] ?, ?, ?, ?", [ $ids[0], $ids[2], $ids[1], $ids[4] ]);

        return response()->json( [ 'actividades' => $actividades, 'estudiantes' => $estudiantes ] );
    }
    public function verTiposPreguntas()
    {
        $preguntas = \DB::select("exec [aula].[Sp_SEL_tipos_preguntas]");

        return response()->json( $preguntas );
    }
    public function verPreguntasEval($idAct)
    {
        $preguntas = \DB::select("exec [aula].[Sp_SEL_preguntasXiActividadesId] ?", [ $idAct ]);

        return response()->json( $preguntas );
    }
    public function insertPreguntasEval(Request $request)
    {
        $data = [
            $request->iEvalDetId,
            $request->iEvaluacionesId,
            $request->iTiposPregId,
            $request->titulo,
            '',
            $request->puntaje,
            $request->tiempo,

            auth()->user()->cCredUsuario,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac',
            $request->opciones,
        ];
        $preguntas = \DB::select("exec [aula].[Sp_INS_PreguntasEvaluacion] ?,?,?,?,?,?,?,     ?,?,?,?,?", $data );

        return response()->json( $preguntas );
    }
    public function getPreguntaExamen(Request $request){
        $data = [
            $request->iEvaluacionesId,
            $request->iPersId,
        ];
        $results = \DB::select("exec aula.Sp_SEL_preguntasExamenXiEvaluacionesIdXiPersId ?,?", $data );

        return response()->json( $results );
    }
    public function getEvaluciones($idAct){
        $data = \DB::select("exec [aula].[Sp_SEL_preguntasXiActividadesId] ?", [ $idAct ]);
        return response()->json( $data );
    }
    public function getExamen(Request $request){

        $data = DB::table('aula.evaluaciones_respuestas')
            ->where('iActividadesId', $request->iActividadesId)
            ->where('iPersId', $request->iPersId)
            ->get();
        if(count($data) > 0){
            $examen = \DB::select("exec [aula].[Sp_SEL_examenEstudiante]  ?,?", [ $data[0]->iEvaluacionesId,$data[0]->iEvalRptaId]);
            $data[0]->examen = $examen;
        }

        return response()->json( $data );
    }
    public function saveNota(Request $request){
        $data = DB::table('aula.evaluaciones_respuestas')
              ->where('iEvalRptaId', $request->iEvalRptaId)
              ->update(['iEvalRptaNota' => floatval($request->iEvalRptaNota)]);
        if($request->noPresentoExamen){
            $data = DB::table('aula.evaluaciones_respuestas')->insert([
                'iActividadesId' => $request->iActividadesId,
                'iEvalRptaNota' => $request->iEvalRptaNota,
                'iPersId' => $request->iPersId
            ]);
        }
        return response()->json( $data );
    }
    public function openTareaNota(Request $request){
        $data = DB::table('aula.actividades_respuestas')
              ->where('iActividadesId', $request->actividadId)
              ->where('iPersId', $request->persId)
              ->update(['iActivRptaEstadoCierre' => 0 ]);
        return response()->json( $data );
    }
    public function saveNotaPregunta(Request $request){
        if( $request->id == 0){
            $data = DB::table('aula.evaluaciones_respuestas_detalle')->insert([
                'iEvalRptaId' => $request->iEvalRptaId, 
                'iEvalDetId' => $request->iEvalDetId,
                'iEvalDetAltId'=>NULL,
                'iEvalDetRespuesta'=>'[SIGEUN] El estudiante no respondio esta pregunta.'
            ]
            );
        }else{
            $data = DB::table('aula.evaluaciones_respuestas_detalle')
              ->where('iEvalRptaDetId', $request->id)
              ->update(['iEvalDetNota' => floatval($request->nota)]);
        }
        
        return response()->json( $data );
    }
    public function getEval($idAct){
        $data = DB::table('aula.evaluaciones')->where('iActividadesId', $idAct)->first();
        $nExam = 0;
        if( $data->cEvaluacionesTitulo){
            $cdata = [];
            $cdata = DB::table('aula.evaluaciones_respuestas')->where('iActividadesId', $idAct)->get();
            $nExam = count($cdata);
        }
        
        $data2 = \DB::select("exec [aula].[Sp_SEL_preguntasXiActividadesId] ?", [ $idAct ]);
        $data->examenesRealizados =  $nExam;
        // $data->examenesRealizados =  1;
        return response()->json( ['examen' => $data, 'preguntas' => $data2] );
    }

    public function getEvalucionesOne($idAct){
        $data = DB::table('aula.evaluaciones')->where('iActividadesId', $idAct)->first();
        return response()->json( $data );
    }
    public function guardarTemaForo(Request $request){
        if(!$request->idActividad){
            $res = [
                $request->iForosTemId ?? 0,
                $request->iForosId,
                $request->cForosTemDsc,
                $request->cForosTemDisc,
                auth()->user()->cCredUsuario,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ];
        }else{
            
            $foro = DB::table('aula.foros')->where('iActividadesId',$request->idActividad)->get();
            $res = [
                0,
                $foro[0]->iForosId,
                $request->cForosTemDsc,
                $request->cForosTemDisc,
                auth()->user()->cCredUsuario,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ];
            
        }
        $data = \DB::select("exec [aula].[Sp_INS_UPD_foros_temas] ?,?,?,?  ,?,?,?,?", $res);
        return response()->json( $data );
    }
    public function getForo($idAct){
        $data = DB::table('aula.foros')->where('iActividadesId', $idAct)->first();
        $creador = DB::table('grl.personas')->where('cPersDocumento', $data->cForosUsuarioSis)->first();
        $data2 = [];
        if($data){

            $data2 = DB::table('aula.foros_temas')->where('iForosId', $data->iForosId )->get();
            foreach($data2 as $key=>$row){
                $idComent = 'FOROCM'.$row->iForosTemId;
                $count = \DB::select("select count(*) as num from aula.comentarios_publicos where cComentariosPubId = ?", [$idComent]);
                $last = \DB::select("select TOP 1 c.dtComentariosPubFechaSis as date, concat(p.cPersNombre, ' ',p.cPersPaterno ) as name from aula.comentarios_publicos  as c
                inner join grl.personas as p on p.iPersId = c.iPersId
                where c.cComentariosPubId = ?
                order by c.dtComentariosPubFechaSis desc", [$idComent]);
                $data2[$key]->count = $count;
                $data2[$key]->last = $last;
            }

        }

        return response()->json( ['foro'=> $data , 'temas' => $data2, 'creador' => $creador] );
    }
    public function getTemaForo($idTema){
        $data = \DB::select("exec [aula].[Sp_SEL_foroTema]  ?", [ $idTema ]);
        return response()->json( $data);
    }
    public function saveNotaForo(Request $request){

        $rows = \DB::table('aula.foros_respuestas')
            ->where('iPersId', $request->iPersId)
            ->where('iActividadesId', $request->iActividadesId)->get();
        //return response()->json( $rows);
        if(count($rows) > 0){
            $data = \DB::table('aula.foros_respuestas')
                ->where('iPersId', $request->iPersId)
                ->where('iActividadesId', $request->iActividadesId)
                ->update(['nForoRptaNota' => $request->nForoRptaNota]);
        }else{
            $data = \DB::table('aula.foros_respuestas')->insert( [
                'iActividadesId' => $request->iActividadesId,
                'iForosId' => $request->iForosId,
                'iPersId' => $request->iPersId,
                'nForoRptaNota' =>$request->nForoRptaNota
            ]);
        }
        
        return response()->json( $data);
    }
    public function deletePreguntas($idPre){

        try{
            $response = \DB::select("exec [aula].[Sp_DEL_PreguntasXEvaluacion]  ?", [ $idPre ]);
            $codeResponse = 200;
        }catch (\fException $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }
    public function getCalendario(Request $request){
        $data = [
            $request->iAnio,
            $request->iMes,
            $request->iPersId,
            $request->iControlCicloAcad
        ];
        $dataEstudiante = [
            $request->iAnio,
            $request->iMes,
            $request->iCarreraId,
            $request->iFilialId,
            $request->iControlCicloAcad,
            $request->iPersId,
        ];
        try{
            $response = \DB::select("exec [aula].[Sp_SEL_calendarioDocente_EstudianteXiAnioXiMesXiPersIdXiControlCicloAcad] ?,?,?,?", $data);
            $codeResponse = 200;
        }catch (\fException $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }
    public function updateActividadSimple(Request $request){
        $res = DB::table('aula.actividades')
              ->where('iActividadesId', $request->iActividadesId)
              ->update([
                  'cActividadesTitulo' => $request->cActividadesTitulo,
                  'cActividadesDsc' => $request->cActividadesDsc,
                  'dActividadesEntrega' =>$request->fecha1,
                  'dActividadesFin' => $request->fecha2,
                  'dActividadesPublicacion' => $request->fechaPublicacionString,
                  'iTipEvaId' => $request->iTipEvaId,
                ]);

        $response = ['validated' => true, 'mensaje' => $res];
    }
    public function updateActividadSimpleReprog(Request $request){
        $res = DB::table('aula.actividades')
              ->where('iActividadesId', $request->iActividadesId)
              ->update([
                  'dActividadesEntrega' =>$request->fecha1
                ]);

        $response = ['validated' => true, 'mensaje' => $res];
    }
    public function cerrarTarea($idActiv){

        try{
            $response = DB::table('aula.actividades_respuestas')->where('iActividadesId', $idActiv)->update(['iActivRptaEstadoCierre' => 1]);
            $codeResponse = 200;
        }catch (\fException $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }
    public function rutaConferencia(){

        try{
            $response = \DB::select("exec [ura].[Sp_SEL_rutaVideoconferencia]");
            $codeResponse = 200;
        }catch (\fException $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }
    public function updateActividadSimple2(Request $request){
        $res = DB::table('aula.evaluaciones')
              ->where('iActividadesId', $request->iActividadesId)
              ->update([
                  'cEvaluacionesTitulo' => $request->cActividadesTitulo,
                  'cEvaluacionesDsc' => $request->cActividadesDsc,
                  'nEvaluacionesNpreg' =>$request->nEvaluacionesNpreg,
                  'nEvalDetPuntaje' =>$request->nEvalDetPuntaje,
                ]);
        $res = DB::table('aula.actividades')
                ->where('iActividadesId', $request->iActividadesId)
                ->update([
                    'cActividadesTitulo' => $request->cActividadesTitulo,
                    'cActividadesDsc' => $request->cActividadesDsc,
                    'dActividadesEntrega' =>$request->fecha1,
                    'dActividadesFin' => $request->fecha2,
                    'dActividadesPublicacion' => $request->fechaPublicacionString,
                    'iTipEvaId' => $request->iTipEvaId,
                  ]);

        $response = ['validated' => true, 'mensaje' => $res];
    }

    public function getTiposEvaluacion()
    {
        $data = DB::select('exec [aula].Sp_SEL_evaluacionTipo');

        return response()->json( $data );
    }
    public function eliminarAlternativa($id){
        $data = \DB::table('aula.evaluaciones_detalles_alternativas')->where('iEvalDetAltId', $id)->delete();
        return response()->json( $data );
    }
    public function eliminarTema($id){
        $data = \DB::table('aula.foros_temas')->where('iForosTemId', $id)->delete();
        return response()->json( $data );
    }

    public function getHoraHorario($iDocenteId,$cCursoCod,$iSeccionId,$iNroSemana,$iCurricCursoId)
    {
        /*
        $fechats = strtotime($fecha);
        switch (date('w', $fechats)){
            case 0: $dia = 7; break;
            case 1: $dia = 1; break;
            case 2: $dia = 2; break;
            case 3: $dia = 3; break;
            case 4: $dia = 4; break;
            case 5: $dia = 5; break;
            case 6: $dia = 6; break;
        }*/
        $ConsultaiControlCicloAcad = \DB::table('ura.controles')
        ->where('iControlEstado',  1)  
        ->get();

        $ConsultaFilial = \DB::table('ura.cargas_horarias')
        ->where('iDocenteId',  $iDocenteId) 
        ->where('cCargaHCurso',  $cCursoCod) 
        ->where('iSeccionId',   $iSeccionId)  
        ->where('iControlCicloAcad', $ConsultaiControlCicloAcad[(count($ConsultaiControlCicloAcad))-1]->iControlCicloAcad)  
        ->get();

        $ConsultaCarreraCurric = \DB::table('ura.curriculas_cursos')
        ->where('iCurricCursoId', $iCurricCursoId) //
        ->get();
        //return count($ConsultaFilialCarrera);
        $data = \DB::select('exec [aula].[Sp_SEL_listaDiasClaseSemanaXiNroSemana] ?, ?, ?, ?, ?, ?, ?, ?', array(
            $iDocenteId,
            $ConsultaiControlCicloAcad[(count($ConsultaiControlCicloAcad))-1]->iControlCicloAcad,
            $ConsultaCarreraCurric[(count($ConsultaCarreraCurric))-1]->iCurricId,
            $ConsultaFilial[(count($ConsultaFilial))-1]->iFilId,
            $ConsultaCarreraCurric[(count($ConsultaCarreraCurric))-1]->iCarreraId,
            $cCursoCod,
            $iSeccionId,
            $iNroSemana
        
        ));
        
        return response()->json( $data );
    }

    public function ActividadeVideoConferencias(Request $request)
    {   

        $this->validate(
            $request,
            [
               
                'tipoActividadId' => 'required',
                'temaId' => 'required',
                'hashedId' => 'required'
            ],
            [
               
                'tipoActividadId.required' => 'Debe escoger un tipo de actividad',
                'temaId.required' => 'Hubo un problema al obtener información del tema.',
                'hashedId.required' => 'Hubo un problema al obtener información de la actividad.'
            ]
        );

        $ids = $this->hashids->decode($request->hashedId);

        $ini = str_replace("T", " ", $request->fechaEntregaString);
        $fin = str_replace("T", " ", $request->fechaFinString);
        $p=0;
        foreach($request->horario as $key=>$horarioVideoconferencia){
            
            if($horarioVideoconferencia['iExisteReunion']==true){
                $parametros = [
                    $request->actividadId,
                    $horarioVideoconferencia['titulo']?? NULL,
                    $horarioVideoconferencia['descripcion']?? NULL,
                    $horarioVideoconferencia['dFechaAsis'].'T00:00:00',
                    $horarioVideoconferencia['dFechaAsis'].'T'.$horarioVideoconferencia['tHorariosFin'],
                    NULL,
                    $request->tipoActividadId,
                    $request->tipo_eval ?? NULL,
                    $ids[0],
                    $ids[1],
                    $ids[3],
                    $ids[2],
                    $request->temaId,
                    $request->respEstud ?? NULL,
                    $request->editResp ?? NULL,
                    $request->calificada ?? NULL,
                    $ids[4],
                    auth()->user()->cCredUsuario,
                    'equipo',
                    $request->server->get('REMOTE_ADDR'),
                    'mac',
                    $horarioVideoconferencia['tHorariosInicio'],
                    $horarioVideoconferencia['tHorariosFin'],
                    1,
                    ' ',
                    $horarioVideoconferencia['iDuracionMin'],
                    '0',
                    0,
                    ' ',
                    ' ',
                    ' ',
                    ' ',
                    ' ',
                    ' ',
                    $horarioVideoconferencia['descripcion']?? NULL,
                    0,
                    ' ',
                    ' '
                ];
                
                try {
                    $data = \DB::select('exec [aula].[Sp_INS_Actividades_pruebas] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros);
                    $idActividad = $data[0]->id;

                    $dataAviso =  $data;
                    $codeResponse = 200;
                    $p++;

                } catch (\fException $e) {

                    $error = substr($e->errorInfo[2], 54);
                    $code = $e->getCode();
                    $codeResponse = 500;
                }
            } else {
                $response = ['validated' => true, 'mensaje' => 'No ingresó fechas...', 'code' => ''];
                $codeResponse = 500;
            }
        }
            
        if($p>0){
            $response = ['validated' => true, 'mensaje' => 'Se ha creado la nueva actividad.', 'data' => $dataAviso];
            $codeResponse = 200;
        }
        else{
            $response = ['validated' => true, 'mensaje' => $error, 'code' => $code];
            $codeResponse = 500;
        }
        if(count($request->horario) == 0){
            $response = ['validated' => true, 'mensaje' => 'No ingresó fechas...', 'code' => ''];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }

    public function addAlternativa(Request $request){
        $data = \DB::table('aula.evaluaciones_detalles_alternativas')->insert([
            'iEvalDetId' => $request->iEvalDetId, 
            'cEvalDetAlt' => $request->cEvalDetAlt,
            'bEvalDetRptaCorrecta' => 0,
        ]);
        return response()->json( $data );
    }

    public function getObtenerReunionLink($iReunionProgId){
        
        try{
            $response = \DB::select("exec [aula].[Sp_SEL_enlaceEstudianteReunionVideoconferencia] ?", array($iReunionProgId));
            $codeResponse = 200;
        }catch (\fException $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );

    }

    public function guardarAsistencia(Request $request){
        //return $request->all();
        try{
            $response = \DB::select("exec [aula].[Sp_INS_asistenciaReunionVideoconferencia] ?,?,?,?,?,?,?", array(
                $request->iReunionProgId,
                $request->iPersId,
                $request->cEstudCodUniv,
                auth()->user()->cCredUsuario,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ));
            $codeResponse = 200;
        }catch (\fException $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }

    /** Grupos actividades */
    public function crearGrupo(Request $request)
    {
        try {
            $id = DB::table('aula.actividades_grupos')->insertGetId(
                [
                    'cActividadGrupoNombre' => $request->nombre, 
                    'iActividadesId' => $request->actividadId,
                    'cActividadGrupoUsuarioSis' => auth()->user()->cCredUsuario,
                    'dtActividadGrupoFechaSis' => date('Y-m-d\TH:i:s'),
                    'cActividadGrupoEquipoSis' => 'equipo',
                    'cActividadGrupoIpSis' => $request->server->get('REMOTE_ADDR'),
                    'cActividadGrupoOpenUsr' => 'N',
                    'cActividadGrupoMacNicSis' => 'mac'
                ]
            );

            $response = ['validated' => true, 'mensaje' => 'Grupo creado con éxito', 'id' => $id];

            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        
        return response()->json( $response, $codeResponse );
    }
    public function getGrupos($id)
    {
        try {
           
            $data = \DB::select("exec [aula].[Sp_SEL_personasActividadesGruposXiActivId] ?", [$id]);

            $response = ['validated' => true, 'mensaje' => 'Grupo creado con éxito', 'data' => $data];

            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        
        return response()->json( $response, $codeResponse );
    }
    public function getEstudiantesGrupo($idActividad){
        try {
           
            $data = \DB::select("exec [aula].[Sp_SEL_personasCursoActividadesGruposXiActivId] ?", [$idActividad]);
            $response = ['validated' => true, 'mensaje' => 'Grupo creado con éxito', 'data' => $data];

            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        
        return response()->json( $response, $codeResponse );
    }
    public function guardarIntegrantes(Request $request)
    {
        try {
            
            $parametros = [
                $request->actividadGrupoId,
                $request->json_integrantes,

                auth()->user()->cCredUsuario,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ];

            $queryResult = \DB::select("exec [aula].[Sp_INS_integrantes_grupo] ?, ?, ?, ?, ?, ?", $parametros);
            
            $response = ['validated' => true, 'mensaje' => 'Integrantes guardados con éxito.', 'queryResult' => $queryResult];

            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
            $codeResponse = 500;
        }
        
        return response()->json( $response, $codeResponse );
    }

    public function eliminarGrupo($actividadGrupoId)
    {
        try {
            DB::table('aula.actividades_grupos')->where('iActividadGrupoId', $actividadGrupoId)->delete();

            $response = ['mensaje' => 'Grupo eliminado'];
            $codeResponse = 200;

        } catch (\Exception $e) {
            $response = ['mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
            $codeResponse = 500;
        }

        return response()->json( $response, $codeResponse );
    }

    public function eliminarIntegrante($actGrupoPersId)
    {
        try {
            DB::table('aula.actividades_grupopersonas')->where('iActGrupoPersId', $actGrupoPersId)->delete();

            $response = ['mensaje' => 'Integrante eliminado'];
            $codeResponse = 200;

        } catch (\Exception $e) {
            $response = ['mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
            $codeResponse = 500;
        }

        return response()->json( $response, $codeResponse );
    }

}
