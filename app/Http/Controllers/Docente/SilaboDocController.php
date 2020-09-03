<?php

namespace App\Http\Controllers\Docente;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class SilaboDocController extends Controller
{
    public function obternerDatosSDocente($semestre,$id,$curso,$opcion,$seccion){
        /*
        switch ($opcion) {
            case 'CONSULTAR':
                $docente = \DB::select('exec ura.Sp_DOCE_SEL_Informacion_Silabo_Prueba ?, ?, ?', array($semestre,$id,$curso));
                $silabus = \DB::select('exec [ura].Sp_DOCE_SEL_Informacion_Silabus_CRUD ?,?, ?, ?', array($opcion,$semestre,$id,$curso));
                $titulo = [];
            break;

            case'':
                $actual = \DB::select('exec [ura].Sp_DOCE_SEL_Informacion_Silabus_CRUD ?,?, ?, ?', array('CONSULTAR_ACTUAL',$semestre,$id,$curso));
            break;
            */
        //$docente = \DB::select('exec ura.Sp_DOCE_SEL_Informacion_Silabo_Prueba ?, ?, ?', array($semestre,$id,$curso));
        //$silabus = \DB::select('exec [ura].Sp_DOCE_SEL_Informacion_Silabus_CRUD ?,?, ?, ?', array('CONSULTAR',$semestre,$id,$curso));
        //$actual = \DB::select('exec [ura].Sp_DOCE_SEL_Informacion_Silabus_CRUD ?,?, ?, ?', array('CONSULTAR_ACTUAL',$semestre,$id,$curso));
        //$elemento = \DB::select('exec [ura].Sp_DOCE_SEL_Informacion_Silabus_CRUD ?,?, ?, ?', array('CONSULTAR_ELEMENTO',$semestre,$id,$curso));
        //$conocimiento = \DB::select('exec [ura].Sp_DOCE_SEL_Informacion_Silabus_CRUD ?,?, ?, ?', array('CONSULTAR_CONOCIMIENTO',$semestre,$id,$curso));
        
        //$unidad = \DB::select('exec [ura].Sp_DOCE_SEL_Informacion_Silabus_CRUD ?,?, ?, ?', array('CONSULTAR_UNIDAD',$semestre,$id,$curso));
        //$unidad_detalle = \DB::select('exec [ura].Sp_DOCE_SEL_Informacion_Silabus_CRUD ?,?, ?, ?', array('CONSULTAR_UNIDAD_DETALLE',$semestre,$id,$curso));
        //$metodologia = \DB::select('exec [ura].Sp_DOCE_SEL_Informacion_Silabus_CRUD ?,?, ?, ?', array('CONSULTAR_METODOLOGIA',$semestre,$id,$curso));
        //$evaluacion = \DB::select('exec [ura].Sp_DOCE_SEL_Informacion_Silabus_CRUD ?,?, ?, ?', array('CONSULTAR_EVALUACION',$semestre,$id,$curso));
        //$bibliografia = \DB::select('exec [ura].Sp_DOCE_SEL_Informacion_Silabus_CRUD ?,?, ?, ?', array('CONSULTAR_BIBLIOGRAFIA',$semestre,$id,$curso));
        
        $silabo = \DB::select('exec [ura].Sp_DOCE_SEL_Informacion_Silabus_CRUD ?,?, ?, ?,?', array('SILABO_IMPORTAR_CONSULTAR',$semestre,$id,$curso,$seccion));
                
        $docente = \DB::select('exec ura.Sp_DOCE_SEL_Informacion_Silabo_Prueba ?, ?, ?,?', array($semestre,$id,$curso,$seccion));
        $datos = \DB::select('exec [ura].Sp_DOCE_SEL_Informacion_Silabus_CRUD ?,?, ?, ?,?', array($opcion,$semestre,$id,$curso,$seccion));
        //return response()->json( $docente );
        if($opcion=="CONSULTAR_UNIDAD") {
            $totalsemanas = \DB::table('ura.controles')
            ->where('iControlCicloAcad', $semestre)
            ->get();
            $nsemana = \DB::select('exec [ura].Sp_DOCE_SEL_Informacion_Silabus_CRUD ?,?, ?, ?,?', array('CONSULTAR_UNIDAD_DETALLE',$semestre,$id,$curso,$seccion));
            return Response::json(['docente' => $docente,'datos' => $datos,'nsemana' => $nsemana,'totalsemanas' => $totalsemanas,'silabo'=>$silabo]);
        }
        if($opcion=="CONSULTAR_BIBLIOGRAFIA") {
            $titulo = \DB::select('exec [ura].Sp_DOCE_SEL_Informacion_Silabus_CRUD ?,?, ?, ?,?', array('CONSULTAR_TITULO',$semestre,$id,$curso,$seccion));    
            return Response::json(['docente' => $docente,'datos' => $datos,'titulo' => $titulo,'silabo'=>$silabo]);
        } 
        
        if($opcion!="CONSULTAR_BIBLIOGRAFIA" && $opcion!="CONSULTAR_UNIDAD"){
            return Response::json(['docente' => $docente,'datos' => $datos,'silabo'=>$silabo]);
        }            
        /*$semanas = \DB::table('ura.controles')
        ->where('iControlCicloAcad', $semestre)
        ->get();*/
        //return response()->json( $docente );
       
        
    }


    public function obternerSilaboSemanaActual()
    {
        $semanasa =  \DB::table('ura.semana_actual')->get();
        return response()->json( $semanasa );
    }
    public function obternerSilaboMetodologiaTipo($id)
    {
        //header("Access-Control-Allow-Origin: *");
        $metodologiatipo =  \DB::select('exec ura.Sp_SEL_metodologia_tipo_Silabo ?', array($id));
        return Response::json(['metodologiatipo' => $metodologiatipo]);

        //return response()->json( $metodologiatipo );
    }
    public function obternerSilaboEvaluacionTipo()
    {
        //header("Access-Control-Allow-Origin: *");
        $evaluaciontipo =  \DB::table('ura.evaluacion_tipo')->get();
        //$evaluaciontipo = \DB::select('exec ura.Sp_SEL_metodologia_tipo_Silabo ?', array($id));
        //return response()->json( $evaluaciontipo );
        return Response::json(['evaluaciontipo' => $evaluaciontipo]);
    }
    public function obternerSilaboBibliografiaTipo()
    {
        //header("Access-Control-Allow-Origin: *");
        $bibliografiatipo =  \DB::table('ura.bibliografia_tipo_fuente')->get();
        //return response()->json( $bibliografiatipo );
        return Response::json(['bibliografiatipo' => $bibliografiatipo]);
    }

    /*
     *Inserta un registro en la tabla competencia_actual
     *
     * 3. COMPETENCIAS
     *
     *@return \Illuminate\Http\JsonResponse
     */
    public function insertarCompetenciaActual(Request $request)
    {
        //return $request->all();

        $validator = Validator::make($request->all(), [
                'iSilaboActId'      => 'required',
                'aCompetenciasAct'	=> 'required'
            ],
            [
                'iSilaboActId.required' => 'Ingrese el Id del sílabo correspondiente.',
                'aCompetenciasAct.required' => 'Ingrese la Competencias correspondiente.',
            ]
        );
        try {

                $parametros = array(
                    $request->iSilaboActId,
                    $request->aCompetenciasAct,
                    auth()->user()->cCredUsuario,
                    gethostname(),
                    $request->server->get('REMOTE_ADDR'),
                    'mac',
                    $request->iComActId,
                    $request->cDesComAct,
                    $request->opcion
                );
                $data = \DB::select('exec ura.Sp_DOCE_INS_Silabo_Competencia_Actual ?, ?, ?, ?, ?, ?,?,?,?', $parametros);

            if ($data[0]->id >= -1){
                if ($data[0]->id == 0 ){
                $response = ['validated' => true, 'mensaje' => 'Se guardó las Competencias exitosamente.'];
                $codeResponse = 200;
                }
                if ($data[0]->id > 0){
                    $response = ['validated' => true, 'mensaje' => 'Se actualizó las Competencias exitosamente.'];
                $codeResponse = 200;
                }
                if ($data[0]->id == -1){
                    $response = ['validated' => true, 'mensaje' => 'Se eliminó las Competencias exitosamente.'];
                    $codeResponse = 200;

                }


            }
            else {
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar las Competencias.'];
                $codeResponse = 500;
            }
        } catch (\QueryException $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }

    /*
     *Inserta un registro en la tabla competencia_actual
     *
     * 3. COMPETENCIAS
     *
     *@return \Illuminate\Http\JsonResponse
     */
    public function insertarCompetenciaElemento(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'iSilaboActId'      => 'required',
            'aCompetenciasEle'	=> 'required'
        ],
        [
            'iSilaboActId.required' => 'Ingrese el Id del sílabo correspondiente.',
            'aCompetenciasEle.required' => 'Ingrese la Competencias correspondiente.',
        ]
    );
        try {

            $parametros = array(
                $request->iSilaboActId,
                $request->aCompetenciasEle,
                auth()->user()->cCredUsuario,
                gethostname(),
                $request->server->get('REMOTE_ADDR'),
                'mac',
                $request->iComEleId,
                $request->cDesComEle,
                $request->opcion
            );
                $data = \DB::select('exec ura.Sp_DOCE_INS_Silabo_Competencia_Elemento ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros);
            if ($data[0]->id >= -1){
                if ($data[0]->id == 0 ){
                    $response = ['validated' => true, 'mensaje' => 'Se guardó los Elementos de la Competencias exitosamente.'];
                    $codeResponse = 200;
                }
                if ($data[0]->id > 0){
                    $response = ['validated' => true, 'mensaje' => 'Se actualizó los Elementos de la Competencias exitosamente.'];
                    $codeResponse = 200;
                }
                if ($data[0]->id == -1){
                    $response = ['validated' => true, 'mensaje' => 'Se eliminó los Elementos de la Competencias exitosamente.'];
                    $codeResponse = 200;

                }


            }
           
            else {
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar los Elementos de la Competencias.'];
                $codeResponse = 500;
            }
        } catch (\QueryException $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }

    /*
     *Inserta un registro en la tabla competencia_conocimientos
     *
     * 3. COMPETENCIAS
     *
     *@return \Illuminate\Http\JsonResponse
     */
    public function insertarCompetenciaConocimientos(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'iSilaboActId'      => 'required',
            'aCompetenciasCon'	=> 'required'
        ],
        [
            'iSilaboActId.required' => 'Ingrese el Id del sílabo correspondiente.',
            'aCompetenciasCon.required' => 'Ingrese la Competencia de Conocimiento correspondiente.',
        ]
    );
        try {

                $parametros = array(
                    $request->iSilaboActId,
                    $request->aCompetenciasCon,
                    auth()->user()->cCredUsuario,
                    gethostname(),
                    $request->server->get('REMOTE_ADDR'),
                    'mac',
                    $request->iComConId,
                    $request->cDesComCon,
                    $request->opcion
                );
                $data = \DB::select('exec ura.Sp_DOCE_INS_Silabo_Competencia_Conocimientos ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros);
            if ($data[0]->id >= -1){
                if ($data[0]->id == 0 ){
                    $response = ['validated' => true, 'mensaje' => 'Se guardó los Conocimientos y Comprensión Escenciales de las Competencias exitosamente.'];
                    $codeResponse = 200;
                }
                if ($data[0]->id > 0){
                    $response = ['validated' => true, 'mensaje' => 'Se actualizó los Conocimientos y Comprensión Escenciales de las Competencias exitosamente.'];
                    $codeResponse = 200;
                }
                if ($data[0]->id == -1){
                    $response = ['validated' => true, 'mensaje' => 'Se eliminó los Conocimientos y Comprensión Escenciales de las Competencias exitosamente.'];
                    $codeResponse = 200;

                }


            }
            
            else {
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar los Conocimientos y Comprensión Escenciales de las Competencias.'];
                $codeResponse = 500;
            }
        } catch (\QueryException $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }

    /*
    *Inserta un registro en la tabla detalle_unidad_actual
    *
    * 4. SECUENCIA DE APRENDIZAJE
    *
    *@return \Illuminate\Http\JsonResponse
     */
    public function insertarDetalleUnidadActual(Request $request)
    {
        $this->validate(
            $request, [
                'aSilaboUnidadAct'=> 'required',
                //'iControlCicloAcad'=> 'required',
                //'iDocenteId'=> 'required',
                //'iCarreraId'=> 'required',
                //'iFilId'=> 'required',
               // 'cFilSigla'=> 'required',
            ],
            [
                'aSilaboUnidadAct.required' => 'Ingrese la Unidad Academica correspondiente.',
               // 'iControlCicloAcad' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
               // 'iDocenteId' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
               // 'iCarreraId' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
               // 'iFilId' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                //'cFilSigla' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
            ]
        );
        try {
            //return $request;
            //foreach ($request->aSilaboUnidadAct as $index=>$silabounidadact) {
                $parametros = array(
                    $request->iSilaboActId,
                   // $request->iControlCicloAcad,
                   // $request->iDocenteId,
                   // $request->iCarreraId,
                   // $request->iFilId,
                   // $request->cFilSigla,
                  //  $request->iCurricId,
                    $request->aSilaboUnidadAct,
                    $request->iUniSilActId,
                    $request->cDesUnidad,
                    $request->opcion,
                    auth()->user()->cCredUsuario,
                    gethostname(),
                    $request->server->get('REMOTE_ADDR'),
                    'mac'
                );
                $data = \DB::select('exec ura.Sp_DOCE_INS_Silabo_Actual_Detalle_Unidad  ?, ?, ?, ?, ?, ?,?,?,?', $parametros);
            //}
            if ($data[0]->id >= -1){
                if ($data[0]->id == 0 ){
                    $response = ['validated' => true, 'mensaje' => 'Se guardó las Unidades Academicas exitosamente.'];
                    $codeResponse = 200;
                }
                if ($data[0]->id > 0){
                    $response = ['validated' => true, 'mensaje' => 'Se actualizó las Unidades Academicas exitosamente.'];
                    $codeResponse = 200;
                }
                if ($data[0]->id == -1){
                    $response = ['validated' => true, 'mensaje' => 'Se eliminó las Unidades Academicas exitosamente.'];
                    $codeResponse = 200;

                }


            }    

            
            else {
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar las Unidad Academicas.'];
                $codeResponse = 500;
            }
        } catch (\QueryException $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }
    /*
    *Inserta un registro en la tabla detalle_secuencias_actual
    *
    * 5. UNIDADES DE APRENDIZAJE
    *
    *@return \Illuminate\Http\JsonResponse
     */
    public function insertarDetalleSecuenciasActual(Request $request)
    {
        $this->validate(
            $request, [
                
                'iSilaboActId'=> 'required',
                'opcion'=> 'required'
            ],
            [
                'iSilaboActId.required' => 'Ingrese el id del Silabo correspondiente.',
                'opcion.required'       => 'Ingrese la opcion correspondiente.',
            ]
        );
        try {
                

                $silabodetsecu = \DB::table('ura.detalle_secuencias_actual')
                ->where('iSilaboActId', $request->iSilaboActId)
                ->count();

                $silabodetsecu = $silabodetsecu + 1;

                $nunidades =\DB::table('ura.detalle_unidad_actual')
                ->where('iSilaboActId', $request->iSilaboActId)
                ->count();

                $unidades = \DB::table('ura.detalle_unidad_actual')
                ->where('iSilaboActId', $request->iSilaboActId)
                ->get();

                $semanas = \DB::table('ura.controles')
                ->where('iControlCicloAcad', $request->iControlCicloAcad)
                ->get();

                $ndia =  $semanas[0]->iNroSemanasAsistencia;
               
                if($ndia==16) {$ndia=$ndia+1;}
                if($silabodetsecu<=$ndia || $request->variable=='EDITAR') {
               
                $arrayy = [] ;

                $divisor = intval($ndia/$nunidades);
                $residuo = intval($ndia%$nunidades);
               
                $i = 0;
                $j = 0;
                $mas = 1;
                while($i<$nunidades){
                    if($residuo==$j){
                        $mas = 0;
                        }
                    $arrayy[$i] = $divisor + $mas;
                    if($residuo>0){
                    $residuo = $residuo - 1;
                    }
                    $i = $i + 1;
                }

                $sum = 0;
                $i=0;
                $iUniSilActId=0;
                $iSemSilActId=0;
                while($i<$nunidades){
                    $sum = $sum + $arrayy[$i];
                    //return $sum;    
                    if($silabodetsecu<=$sum){
                        $iUniSilActId = $unidades[$i]->iUniSilActId;
                        $iSemSilActId   =  $silabodetsecu; 
                        $i=$i+1+$nunidades;
                    }
                    $i=$i+1;

                }
                
                    $parametros = array(

                        $request->iSilaboActId,
                        $request->cDesConoAct,
                        $request->cDesResuAct,
                        $request->cDesMateAct,
                        $iUniSilActId,
                        //$iSemSilActId,
                        $request->opcion,
                        $request->iSecuActId,
                        $request->cDesConoAct,
                        $request->cDesResuAct,
                        $request->cDesMateAct,
                        $request->iControlCicloAcad,
                        auth()->user()->cCredUsuario,
                        gethostname(),
                        $request->server->get('REMOTE_ADDR'),
                        'mac'
                    );
                    $data = \DB::select('exec ura.Sp_DOCE_INS_Silabo_Detalle_Secuencias_Actual  ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros);
            
            //return response()->json( $parametros );
            if ($data[0]->id >= -1){
                if ($data[0]->id == 0 ){
                    $response = ['validated' => true, 'mensaje' => 'Se guardó los Detalles de las UNIDADES DE APRENDIZAJE exitosamente.'];
                    $codeResponse = 200;
                }
                if ($data[0]->id > 0){
                    $response = ['validated' => true, 'mensaje' => 'Se actualizó los Detalles de las UNIDADES DE APRENDIZAJE exitosamente.'];
                    $codeResponse = 200;
                }
                if ($data[0]->id == -1){
                    $response = ['validated' => true, 'mensaje' => 'Se eliminó los Detalles de las UNIDADES DE APRENDIZAJE exitosamente.'];
                    $codeResponse = 200;

                }


            } 

            
            else {
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar los Detalles de las UNIDADES DE APRENDIZAJE.'];
                $codeResponse = 500;
            }

            }

            else{
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar, porque ya se excedió el número de semanas correspondiente.'];
                $codeResponse = 500;
            }


        } catch (\QueryException $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }

    /*
    *Inserta un registro en la tabla detalle_metodologias
    *
    * 6. METODOLOGIAS
    *
    *@return \Illuminate\Http\JsonResponse
     */
    public function insertarDetalleMetodologias(Request $request)
    {
        $this->validate(
            $request, [
                //'aSilaboDetMeto'=> 'required',
                'iSilaboActId'=> 'required',
                //'iTipMetoId'=> 'required'
                //'cDesMetoAct'=> 'required',
            ],
            [
                //'aSilaboDetMeto.required' => 'Ingrese las Metodologías correspondiente.',
                'iSilaboActId.required' => 'Ingrese el Id del Sílabo correspondiente.',
                //'iTipMetoId.required' => 'Ingrese el Tipo de Metología correspondiente.'
                //'cDesMetoAct.required' => 'Ingrese la descripción de la metodología correspondiente.',
            ]
        );
        try {
                    //return $request->all();
                    $parametros = array(
                        $request->iSilaboActId,
                        $request->iTipMetoId,
                        $request->itipoMeto,
                        $request->descripcion_metodologia,
                        $request->iMetoActId,
                        $request->cDesMetoAct == NULL ? $request->descripcion_metodologia : $request->cDesMetoAct ,
                        $request->opcion,
                        auth()->user()->cCredUsuario,
                        gethostname(),
                        $request->server->get('REMOTE_ADDR'),
                        'mac'
                    );
                    $data = \DB::select('exec ura.Sp_DOCE_INS_Silabo_Detalle_Metodologias ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros);
            if ($data[0]->id >= -1){
                if ($data[0]->id == 0 ){
                    $response = ['validated' => true, 'mensaje' => 'Se guardó los Detalles de las Metodologías exitosamente.'];
                    $codeResponse = 200;
                }
                if ($data[0]->id > 0){
                    $response = ['validated' => true, 'mensaje' => 'Se actualizó los Detalles de las Metodologías exitosamente.'];
                    $codeResponse = 200;
                }
                if ($data[0]->id == -1){
                    $response = ['validated' => true, 'mensaje' => 'Se eliminó los Detalles de las Metodologías exitosamente.'];
                $codeResponse = 200;

                }


            } 
            
            else {
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar los Detalles de las Metodologías.'];
                $codeResponse = 500;
            }
        } catch (\QueryException $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }
    /*
    *Inserta un registro en la tabla detalle_evaluacion_actual
    *
    * 7. EVALUACIÓN DEL APRENDIZAJE
    *
    *@return \Illuminate\Http\JsonResponse
     */
    public function insertarDetalleEvaluacionesActual(Request $request)
    {
        $this->validate(
            $request, [
                //'aSilaboDetEvalAct'=> 'required',
            ],
            [
                //'aSilaboDetEvalAct.required' => 'Ingrese las Evaluaciones de Aprendizaje correspondiente.',
            ]
        );
        try {
            foreach ($request->evaluacion as $eva) {
                    if(isset($eva['iEvaActId'])) {$e=$eva['iEvaActId'];}
                    else{$e=$request->iEvaActId;}
                   
                    $parametros = array(
                        $e,
                        $request->iSilaboActId,
                        $eva['iTipEvaId'],
                        $eva['cDesEvaRes'],
                        $eva['cDesEvaEvi'],
                        $eva['cDesEvaIns'],
                        $eva['iPonTipEva'],
                        $request->opcion,
                        auth()->user()->cCredUsuario,
                        gethostname(),
                        $request->server->get('REMOTE_ADDR'),
                        'mac'
                    );
                    $data = \DB::select('exec ura.Sp_DOCE_INS_Silabo_Detalle_Evaluacion_Actual ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?', $parametros);
               
            }

            if ($data[0]->id >= -1){
                if ($data[0]->id == 0 ){
                    $response = ['validated' => true, 'mensaje' => 'Se guardó los Detalles de las Evaluaciones de Aprendizaje exitosamente.'];
                    $codeResponse = 200;
                }
                if ($data[0]->id > 0){
                    $response = ['validated' => true, 'mensaje' => 'Se actualizó los Detalles de las Evaluaciones de Aprendizaje exitosamente.'];
                    $codeResponse = 200;
                }
                if ($data[0]->id == -1){
                    $response = ['validated' => true, 'mensaje' => 'Se eliminó los Detalles de las Evaluaciones de Aprendizaje exitosamente.'];
                    $codeResponse = 200;

                }


            } 

           
            else {
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar los Detalles de las Evaluaciones de Aprendizaje .'];
                $codeResponse = 500;
            }
        } catch (\QueryException $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }

    /*
    *Inserta un registro en la tabla detalle_evaluacion_instrumento
    *
    * 7. EVALUACIÓN DEL APRENDIZAJE
    *
    *@return \Illuminate\Http\JsonResponse
     */
    public function insertarDetalleBibliografiaActual(Request $request)
    {
        $this->validate(
            $request, [
                //'aSilaboDetBiblioAct'=> 'required',
            ],
            [
                //'aSilaboDetBiblioAct.required' => 'Ingrese las Bibliografías correspondiente.',
            ]
        );
        try {
            
                //foreach ($silabodetmate['detmate'] as $asdm) {
                   
                    $parametros = array(
                        $request->iSilaboActId,
                        $request->iBiblioActId,
                        $request->autor,
                        $request->nombre_titulo,
                        $request->anio,
                        $request->editorial,
                        $request->pais,
                        $request->tipo_bibliografia, 
                        $request->opcion,
                        $request->iBienId,
                        auth()->user()->cCredUsuario,
                        gethostname(),
                        $request->server->get('REMOTE_ADDR'),
                        'mac'
                    );
                    $data = \DB::select('exec ura.Sp_DOCE_INS_Silabo_Bibliografia_Actual ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,  ?, ?,?', $parametros);
            
             if ($data[0]->id >= -1){
                if ($data[0]->id == 0 ){
                    $response = ['validated' => true, 'mensaje' => 'Se guardó los Detalles Bibliograficos  exitosamente.'];
                    $codeResponse = 200;
                }
                if ($data[0]->id > 0){
                    $response = ['validated' => true, 'mensaje' => 'Se actualizó los Detalles Bibliograficos  exitosamente.'];
                    $codeResponse = 200;
                }
                if ($data[0]->id == -1){
                    $response = ['validated' => true, 'mensaje' => 'Se eliminó los Detalles Bibliograficos  exitosamente.'];
                    $codeResponse = 200;

                }


            } 


           
            else {
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar los Detalles Bibliograficos.'];
                $codeResponse = 500;
            }
        } catch (\QueryException $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }


    public function insertarDatosBasicos(Request $request){

        //return $request['datoSilabo']->all();

        $validator = Validator::make($request->all(), [
                'iControlCicloAcad'=> 'required',
                'iDocenteId'=> 'required',
                'iCarreraId'=> 'required',
                'iFilId'=> 'required',
                'cFilSigla'=> 'required',
                'iAulaCod'=> 'required',
                'iCurricId'=> 'required',
                'iSeccionId'=> 'required',
                'cAulaCiclo'=> 'required',
                'cSilActNomCurso'=> 'required',
                'cSilActCodCurso'=> 'required',
                'cSilActModal'=> 'required',
                'cSilActCiclo'=> 'required',
                'cSilActVersion'=> 'required',
                'cSilActAreaCur'=> 'required',
                'cSilActTipAsig'=> 'required',
                'cSilActTipEsp'=> 'required',
                'cSilActNivel'=> 'required',
                'cSilActConsidera'=> 'required',
                'cSilActCred'=> 'required',
                'cSilActHrsT'=> 'required',
                'cSilActHrsP'=> 'required',
                'datosbasicos','cSilActHrsV'=> 'required',
                'cSilActPreRequi'=> 'required',
                'cSilActSumilla'=> 'required',
                'cSilActAutor'=> 'required',
                'cSilActRevisa'=> 'required',
                //'cSilActFecApro'=> 'required',
                'cSilaUsuarioSis'=> 'required',
                'dtSilaFechaSis'=> 'required',
                'cSilaActEquipoSis'=> 'required',
                'cSilaActIpSis'=> 'required',
                'cSilaActUsuarioSis'=> 'required',
                'cSilaActMacNicSis'=> 'required',
            ],
            [
                'iControlCicloAcad' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                'iDocenteId' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                'iCarreraId' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                'iFilId' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                'iAulaCod' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                'iCurricId' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                'iSeccionId' => 'Ingrese el Id de la Sección correspondiente.',
                'cAulaCiclo' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                'cSilActNomCurso ' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                'cSilActCodCurso' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                'cSilActModal' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                'cSilActCiclo' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                'cSilActVersion' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                'cSilActAreaCur' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                'cSilActTipAsig' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                'cSilActTipEsp' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                'cSilActNivel' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                'cSilActConsidera' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                'cSilActCred' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                'cSilActHrsT' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                'cSilActHrsP' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                'datosbasicos','cSilActHrsV' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                'cSilActPreRequi' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                'cSilActSumilla' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                'cSilActAutor' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                'cSilActRevisa' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                //'cSilActFecApro' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                'cSilaUsuarioSis' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                'dtSilaFechaSis' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                'cSilaActEquipoSis' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                'cSilaActIpSis' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                'cSilaActUsuarioSis' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
                'cSilaActMacNicSis' => 'Ingrese los Instrumentos de Evaluación correspondiente.',
            ]
        );


        try {
                    $parametros = array(
                        $request['datosbasicos']['iControlCicloAcad'],
                        $request['datosbasicos']['iDocenteId'],
                        $request['datosbasicos']['iCarreraId'],
                        $request['datosbasicos']['iFilId'],

                        $request['datosbasicos']['iAulaCod'],
                        $request['datosbasicos']['iCurricId'],//7
                        $request['datosbasicos']['iSeccionId'],
                        $request['datosbasicos']['cAulaCiclo'],
                        $request['datosbasicos']['cSilActNomCurso'],
                        $request['datosbasicos']['cSilActCodCurso'],
                        $request['datosbasicos']['cSilActModal'],
                        $request['datosbasicos']['cSilActCiclo'],
                        $request['datosbasicos']['cSilActVersion'],
                        $request['datosbasicos']['cSilActAreaCur'],
                        $request['datosbasicos']['cSilActTipAsig'],
                        $request['datosbasicos']['cSilActTipEsp'],
                        $request['datosbasicos']['cSilActNivel'],//10

                        $request['datosbasicos']['cSilActConsidera'],
                        $request['datosbasicos']['cSilActCred'],
                        $request['datosbasicos']['cSilActHrsT'],
                        $request['datosbasicos']['cSilActHrsP'],
                        $request['datosbasicos']['cSilActHrsV'],
                        $request['datosbasicos']['cSilActPreRequi'],
                        $request['datosbasicos']['cSilActSumilla'],
                        $request['datosbasicos']['cSilActAutor'],
                        $request['datosbasicos']['cSilActRevisa'],

                        $request['datosbasicos']['cSilActConCat'],
                        $request['datosbasicos']['cSilActEspecial'],
                        auth()->user()->cCredUsuario,
                        gethostname(),
                        $request->server->get('REMOTE_ADDR'),
                        'mac',//14


                    );

                    //return $parametros;
                    $data = \DB::select('exec ura.Sp_DOCE_INS_Silabo_Actual ?, ?, ?, ?, ?, ?, ?, ? , ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ,? ', $parametros);

            if ($data[0]->id >= 0 ){

                if($data[0]->id == 0) {
                $response = ['validated' => true, 'mensaje' => 'Se guardó la información ingresada exitosamente.'];
                $codeResponse = 200;    }

                if($data[0]->id > 0) {
                    $response = ['validated' => true, 'mensaje' => 'Se actualizó la información ingresada exitosamente.'];
                    $codeResponse = 200;    }

            }
            else {
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar los Datos Basícos - Silabo.'];
                $codeResponse = 500;
            }
        } catch (\QueryException $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    
    
    }



    public function descargaSilaboPdf($iSilaboActId){

        $silabo = \DB::table('ura.silabo_actual')
        ->join('ura.carreras', 'ura.silabo_actual.iCarreraId', '=', 'ura.carreras.iCarreraId')
        ->where('iSilaboActId', $iSilaboActId)
        ->get();

        $competenciaActual= \DB::table('ura.competencia_actual')
        ->where('iSilaboActId', $iSilaboActId)
        ->get();


        $elementoActual = \DB::table('ura.competencia_elemento')
        ->where('iSilaboActId', $iSilaboActId)
        ->get();


        $conocimientoActual = \DB::table('ura.competencia_conocimientos')
        ->where('iSilaboActId', $iSilaboActId)
        ->get();

        $unidad= \DB::table('ura.detalle_unidad_actual')
        ->where('iSilaboActId', $iSilaboActId)
        ->get();

        $detalleSecuencia= \DB::table('ura.detalle_secuencias_actual')
       ->where('iSilaboActId', $iSilaboActId)
       ->get();

        $metodologia = \DB::table('ura.detalle_Metodologias')
        ->join('ura.metodologias_tipo', 'ura.detalle_Metodologias.iTipMetoId', '=', 'ura.metodologias_tipo.iTipMetoId')
        ->where('iSilaboActId', $iSilaboActId)
        ->get();

        $evaluacion = \DB::table('ura.detalle_evaluacion_actual')
       ->where('iSilaboActId', $iSilaboActId)
       ->join('ura.evaluacion_tipo', 'ura.detalle_evaluacion_actual.iTipEvaId', '=', 'ura.evaluacion_tipo.iTipEvaId')
       ->get();

       $bibliografia = \DB::table('ura.bibliografia_actual')
        ->where('iSilaboActId', $iSilaboActId)
        ->join('ura.bibliografia_tipo_fuente', 'ura.bibliografia_actual.iBiblioTipId', '=', 'ura.bibliografia_tipo_fuente.iBiblioTipId')
        ->get();


        $pdf = \PDF::loadView('docente.SilaboPdf', compact(['silabo','competenciaActual','elementoActual','conocimientoActual','unidad','metodologia','detalleSecuencia','evaluacion','bibliografia']));
        return $pdf->stream();


    }

    public function  SilaboFinalizado($semestre,$id,$curso,$seccion){
        //return $request->all();
        $estado = \DB::select('exec [ura].Sp_DOCE_SEL_Informacion_Silabus_CRUD ?,?, ?, ?,?', array('CAMBIAR_ESTADO',$semestre,$id,$curso,$seccion));
        return $estado;
    }

    
    public function descargaSilaboDocentePdf($iControlCicloAcad,$cCurricCursoCod,$iSeccionId,$iDocenteId){

       
        
        $silabus = \DB::select('exec [ura].Sp_DOCE_SEL_Informacion_Silabus_CRUD ?,?, ?, ?,?', array('CONSULTAR',$iControlCicloAcad,$iDocenteId,$cCurricCursoCod,$iSeccionId));
        $actual = \DB::select('exec [ura].Sp_DOCE_SEL_Informacion_Silabus_CRUD ?,?, ?, ?,?', array('CONSULTAR_ACTUAL',$iControlCicloAcad,$iDocenteId,$cCurricCursoCod,$iSeccionId));
        $elemento = \DB::select('exec [ura].Sp_DOCE_SEL_Informacion_Silabus_CRUD ?,?, ?, ?,?', array('CONSULTAR_ELEMENTO',$iControlCicloAcad,$iDocenteId,$cCurricCursoCod,$iSeccionId));
        $conocimiento = \DB::select('exec [ura].Sp_DOCE_SEL_Informacion_Silabus_CRUD ?,?, ?, ?,?', array('CONSULTAR_CONOCIMIENTO',$iControlCicloAcad,$iDocenteId,$cCurricCursoCod,$iSeccionId));
        
        $unidad = \DB::select('exec [ura].Sp_DOCE_SEL_Informacion_Silabus_CRUD ?,?, ?, ?,?', array('CONSULTAR_UNIDAD',$iControlCicloAcad,$iDocenteId,$cCurricCursoCod,$iSeccionId));
        $unidad_detalle = \DB::select('exec [ura].Sp_DOCE_SEL_Informacion_Silabus_CRUD ?,?, ?, ?,?', array('CONSULTAR_UNIDAD_DETALLE',$iControlCicloAcad,$iDocenteId,$cCurricCursoCod,$iSeccionId));
        $metodologia = \DB::select('exec [ura].Sp_DOCE_SEL_Informacion_Silabus_CRUD ?,?, ?, ?,?', array('CONSULTAR_METODOLOGIA',$iControlCicloAcad,$iDocenteId,$cCurricCursoCod,$iSeccionId));
        $evaluacion = \DB::select('exec [ura].Sp_DOCE_SEL_Informacion_Silabus_CRUD ?,?, ?, ?,?', array('CONSULTAR_EVALUACION',$iControlCicloAcad,$iDocenteId,$cCurricCursoCod,$iSeccionId));
        $bibliografia = \DB::select('exec [ura].Sp_DOCE_SEL_Informacion_Silabus_CRUD ?,?, ?, ?,?', array('CONSULTAR_BIBLIOGRAFIA',$iControlCicloAcad,$iDocenteId,$cCurricCursoCod,$iSeccionId));
        
        $carrera = \DB::table('ura.carreras')
       
        ->where('iCarreraId', $silabus[0]->iCarreraId)
        ->get();

        $pdf = \PDF::loadView('docente.SilaboDocumento', compact(['silabus','actual','elemento','conocimiento','unidad','unidad_detalle','metodologia','evaluacion','bibliografia','carrera']));
        return $pdf->stream();


    }

    public function  LinkCapacitacion(){
        //return $request->all();
        $link =  \DB::table('ura.capacitaciones_link')->get();
        return response()->json($link);
    }

    public function importar_silabo($semestre,$id,$curso,$opcion,$seccion){
                
        $docente = \DB::select('exec ura.Sp_DOCE_SEL_Informacion_Silabo_Prueba ?, ?, ?,?', array($semestre,$id,$curso,$seccion));
        //$silabo = \DB::select('exec [ura].Sp_DOCE_SEL_Informacion_Silabus_CRUD ?,?, ?, ?,?', array('SILABO_IMPORTAR_CONSULTAR',$semestre,$id,$curso,$seccion));
        //return response()->json($docente);

        //cAulasDesc
        //cPersDirector
        //return response()->json( $docente[0]->cPersDirector); 
        $datos = \DB::select('exec ura.Sp_DOCE_IMPORTAR_SILABO_ACTUAL ?, ?, ?,?,?,?, ?, ?,?,?', 
        array(
        $semestre,
        $id,
        $curso,
        $seccion,
        $docente[0]->cAulasDesc,
        $docente[0]->cPersDirector,
        auth()->user()->cCredUsuario,
        gethostname(),
        '',
        'mac'));
        
        $dato = $datos[0]->id;
        //return response()->json($dato);    
        /*
        $silabodetsecu = \DB::table('ura.detalle_secuencias_actual')
        ->where('iSilaboActId', $dato)
        ->count();
      
            
        $silabodetsecu = $silabodetsecu + 1;
        */
        
        $silabo_semanas = \DB::table('ura.detalle_secuencias_actual')
        ->where('iSilaboActId', $dato)
        ->get();       

        
        $nunidades =\DB::table('ura.detalle_unidad_actual')
        ->where('iSilaboActId', $dato)
        ->count();

        $unidades = \DB::table('ura.detalle_unidad_actual')
        ->where('iSilaboActId', $dato)
        ->get();

        $semanas = \DB::table('ura.controles')
        ->where('iControlCicloAcad', $semestre)
        ->get();

        $ndia =  $semanas[0]->iNroSemanasAsistencia;
        $silabodetsecu = 1;
        if($ndia==16) {$ndia=$ndia+1;}
        while($silabodetsecu<=$ndia) {
       
        $arrayy = [] ;

        $divisor = intval($ndia/$nunidades);
        $residuo = intval($ndia%$nunidades);
       
        $i = 0;
        $j = 0;
        $mas = 1;
        while($i<$nunidades){
            if($residuo==$j){
                $mas = 0;
                }
            $arrayy[$i] = $divisor + $mas;
            if($residuo>0){
            $residuo = $residuo - 1;
            }
            $i = $i + 1;
        }

        $sum = 0;
        $i=0;
        $iUniSilActId=0;
        $iSemSilActId=0;
        while($i<$nunidades){
            $sum = $sum + $arrayy[$i];
            //return $sum;    
            if($silabodetsecu<=$sum){
                $iUniSilActId = $unidades[$i]->iUniSilActId;
                $iSemSilActId   =  $silabodetsecu; 
                $i=$i+1+$nunidades;
            }
            $i=$i+1;

        }
          
            $actualizarUNIDAD = \DB::table('ura.detalle_secuencias_actual')
                ->where('iSecuActId', $silabo_semanas[$silabodetsecu-1]->iSecuActId )
                ->where('iSilaboActId',  $dato)
                ->update(array('iUniSilActId' => $iUniSilActId ));

            $silabodetsecu = $silabodetsecu + 1;

        }   

        $silabo_actual = \DB::table('ura.silabo_actual')
        ->where('iSilaboActId', $dato)
        ->get();       
        $fecha_asistencia = \DB::select('EXEC [ura].[Sp_DOCE_INS_Notas_Genera_ListadoUnidadesXSilaboActual] ?, ?, ?,?,?, ?, ?,?,?, ?, ?,?', 
        array(
            $silabo_actual[0]->iDocenteId,
            $silabo_actual[0]->iControlCicloAcad,
            $silabo_actual[0]->iCurricId,
            $silabo_actual[0]->iFilId,
            $silabo_actual[0]->iCarreraId,
            $silabo_actual[0]->cSilActCodCurso,
            $silabo_actual[0]->iSeccionId,
            auth()->user()->cCredUsuario,
            gethostname(),
            '-',
            '-',
            '-'
            ));
        
        return response()->json($dato); 

    }

}
