<?php

namespace App\Http\Controllers\Docente;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DocenteSilaboController extends Controller
{
    public function CursosDocenteSilabo($cicloa, $cursoa)
    {
        //$cursos = \DB::select('exec ura.Sp_SEL_Silabo_Cabecera ?, ?, ?', array( $cicloa, $plana, $cursoa));
        $cursos = \DB::select('exec ura.Sp_SEL_Silabo_Cabecera_Nueva ?, ?', array( $cicloa, $cursoa));
        return response()->json( $cursos );
    }

    public function obtenerSilaboEquipos()
    {
        $equipos =  \DB::select('exec ura.Sp_DOCE_SEL_Silabo_Equipos');
        return response()->json( $equipos );
    }
    public function obtenerSilaboMateriales()
    {
        $materiales =  \DB::select('exec ura.Sp_DOCE_SEL_Silabo_Materiales');
        return response()->json( $materiales );
    }
    public function obtenerSilaboProcedimientosTecnicas()
    {
        $tecnicas =  \DB::select('exec ura.Sp_DOCE_SEL_Silabo_procedimientos_didacticos_tecnicas');
        return response()->json( $tecnicas );
    }
    public function obtenerSilaboEvaluacionPermanente()
    {
        $evaluaciones =  \DB::select('exec ura.Sp_DOCE_SEL_Silabo_Evaluacion_Permanente');
        return response()->json( $evaluaciones );
    }
    
    public function obtenerSilaboClaseSilabo()
    {
        $clases =  \DB::select('exec ura.Sp_DOCE_SEL_Silabo_Clase_Silabo');
        return response()->json( $clases );
    }
    
    public function obtenerSilaboSemanaSilabo()
    {
        $semanas =  \DB::select('exec ura.Sp_DOCE_SEL_Silabo_Semana_Silabo');
        return response()->json( $semanas );
    }


//---------------------------------------------------
     /*
    *Inserta un registro en la tabla detalle_unidad
    *
    * 4. PROGRAMACION DE LOS CONTENIDOS - UNIDADES
    *
    *@return \Illuminate\Http\JsonResponse
     */
    public function insertarDetalleUnidad(Request $request)
    {
        $this->validate(
            $request, [
                //'iUniSilId			'=> 'required',
                //'iSilaboId			'=> 'required',
                //'aCombounidades'=> 'required',
                //'aUnidades'=> 'required',
                'aSilabounidad'=> 'required',
                
                //'cUniSilUsuarioSis	'=> 'required',
                //'dtUniSilFechaSis	'=> 'required',
                //'cUniSilEquipoSis	'=> 'required',
                //'cUniSilIpSis		'=> 'required',
                //'cUniSilOpenUsr		'=> 'required',
                //'cUniSilMacNicSis	'=> 'required',,	
            ], 
            [
                'aSilabounidad.required' => 'Ingrese la descripcion de la Unidad Academica.',
            ]
        );
        try {
            foreach ($request->aSilabounidad as $silabounidad) {
            //foreach ($request->$aSilabounidad as $silabounidad) {
                //foreach ($silabounidad as $key=>$value) {
                    $parametros = array( 
                        //'iSilaboId'=> 'required',
                        1,
                        implode(', ', $silabounidad['combounidades']),
                        implode(', ', $silabounidad['unidades']),
                        auth()->user()->cCredUsuario,
                        'equipo',
                        $request->server->get('REMOTE_ADDR'),
                        'mac'
                    );
                    $data = \DB::select('exec ura.Sp_DOCE_INS_Silabo_Detalle_Unidad ?, ?, ?, ?, ?, ?, ?', $parametros);
            }  
            return response()->json( $parametros );

            if ($data[0]->id > 0){
                $response = ['validated' => true, 'mensaje' => 'Se guardó los Detalles Conceptuales exitosamente.'];
                // 'id'=>$data[0]->id];
                $codeResponse = 200;
            }
            else {
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar los Detalles Conceptuales.'];
                $codeResponse = 500;
            }
        } catch (\QueryException $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500; 
        }
        return response()->json( $response, $codeResponse );
    }    

    /*
    *Inserta un registro en la tabla detalle_conceptuales
    *
    * 4. PROGRAMACION DE LOS CONTENIDOS - CONCEPTUALES
    *
    *@return \Illuminate\Http\JsonResponse
     */
    public function insertarDetalleConceptuales(Request $request)
    {
        $this->validate(
            $request, [
            //'aSilabo			'=> 'required',
            //'iConceId			'=> 'required',
            //'iSilaboId		'=> 'required',
            //'iUniSilId		'=> 'required',
            //'iSemSilId		'=> 'required',
            //'iClaseId			'=> 'required',
            //'iConceAP			'=> 'required',
            //'iConceAA			'=> 'required',
            //'tConceHoraIni	'=> 'required',
            //'tConceHoraFin	'=> 'required',
            //'cConceUsuarioSis	'=> 'required',
            //'dtConceFechaSis	'=> 'required',
            //'cConceEquipoSis	'=> 'required',
            //'cConceIpSis		'=> 'required',
            //'cConceOpenUsr	'=> 'required',
            //'cConceMacNicSis	'=> 'required',	
            ], 
            [
                //'iSilaboId.required' => 'Ingrese el Silabo correspondiente.',
                //'aSilabo.required' => 'Ingrese los Detalles Conceptuales correspondiente.',
            ]
        );
        /*
        foreach ($request->aSilabo as $conce) {
            
        } 

        foreach($conce['conceptuales'] as $c){
            ++$c;
        }
        
        foreach($conce['avanceparcial'] as $ap){
            ++$ap;
        }
       
        
        foreach($conce['avanceacumulado'] as $aa){
            ++$aa;
        }
    
        foreach ($i = 0; $i < 10; $i++){
            $i;
        }
          */  


       
            /*
            foreach ($request->aSilabo as $key=> $conce) {
               
            //foreach ($request->aSilabo as $conce) {
                foreach($conce['conceptuales'] as $index=>$c){
                    $array[$key][0]=$c[$index];
                }

                foreach($conce['avanceparcial'] as $index=>$ap){
                    $array[$key][1]=$ap[$index];
                }

        */
        
        
        foreach ($request->aSilabo as  $conce) {
                
            $a = 0;
            $cc = 0;
                do{
                    if((isset($conce['conceptuales'][$a])) && (isset($conce['avanceparcial'][$a])) )
                    {

                        $data = \DB::select('exec ura.Sp_DOCE_INS_Silabo_Detalle_Conceptuales ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', [
                            1,
                            1,
                            1,
                            1,
                            ($conce['conceptuales'][$a]),
                            ($conce['avanceparcial'][$a]),
                            1,
                            '12-12-12',
                            '12-12-12',
                            auth()->user()->cCredUsuario,
                            'equipo',
                            $request->server->get('REMOTE_ADDR'),
                            'mac']);
                        $a++;
                        
                    }
                    else{$cc=1;}
                    
                }
                while($cc!=1);
               
                
            }
                
                
                
                /*
                //foreach($conce['conceptuales'] as $c){
                    $parametros = array( 
                    //'iSilaboId			'=> 'required',
                    1,
                    //'iUniSilId			'=> 'required',
                    1,
                    //'iSemSilId			'=> 'required',
                    1,
                    //'iClaseId			'=> 'required',
                    1,
                    
                    $c,
                    //implode(', ', $conce['conceptuales']),
                    //implode(', ', $conce['avanceparcial']),
                    $ap,
                    1,
                    //$aa,
                    //implode(', ', $conce['avanceacumulado']),
                    //$conce->toDateString(),
                    //$conce->toDateString(),
                    '12-12-12',
                    '12-12-12',
                    auth()->user()->cCredUsuario,
                    'equipo',
                    $request->server->get('REMOTE_ADDR'),
                    'mac'
                );
                $data = \DB::select('exec ura.Sp_DOCE_INS_Silabo_Detalle_Conceptuales ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros);
            //}
             */
            //}
           
            //return response()->json( $parametros );
            
        return response()->json( );
    } 

    /*
    *Inserta un registro en la tabla detalle_actitudinales
    *
    * 4. PROGRAMACION DE LOS CONTENIDOS - ACTITUDINALES
    *
    *@return \Illuminate\Http\JsonResponse
     */
    public function insertarDetalleActitudinales(Request $request)
    {
        $this->validate(
            $request, [
            //'aActitudinal		    '=> 'required',
            //'iActiId		    '=> 'required',
            //'iSilaboId		'=> 'required',
            //'iUniSilId		'=> 'required',
            //'iSemSilId		'=> 'required',
            //'cDesActi			'=> 'required',
            //'cActiUsuarioSis	'=> 'required',
            //'dtActiFechaSis	'=> 'required',
            //'cActiEquipoSis	'=> 'required',
            //'cActiIpSis		'=> 'required',
            //'cActiOpenUsr		'=> 'required',
            //'cActiMacNicSis 	'=> 'required',	
            ], 
            [
                //'aActitudinal.required' => 'Ingrese los Detalles Actitudinales.',
            ]
        );
        try {
            foreach ($request->aSilabo as $acti) {
                foreach ($acti['actitudinales'] as $ac) {
                    $parametros = array( 
                        //'iSilaboId'=> 'required',
                        1,
                        1,
                        1,
                        //implode(', ', $acti['actitudinales']),
                        $ac,
                        auth()->user()->cCredUsuario,
                        'equipo',
                        $request->server->get('REMOTE_ADDR'),
                        'mac'
                    );
                    $data = \DB::select('exec ura.Sp_DOCE_INS_Silabo_Detalle_Actitudinales ?, ?, ?, ?, ?, ?, ?, ?', $parametros);
                }
            }  
            return response()->json( $parametros );

            if ($data[0]->id > 0){
                $response = ['validated' => true, 'mensaje' => 'Se guardó los Detalles Aptitudinales exitosamente.'];
                $codeResponse = 200;
            }
            else {
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar los Detalles Aptitudinales.'];
                $codeResponse = 500;
            }
        } catch (\QueryException $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500; 
        }
        return response()->json( $response, $codeResponse );
    }  

    /*
    *Inserta un registro en la tabla detalle_actitudinales
    *
    * 4. PROGRAMACION DE LOS CONTENIDOS - PROCEDIMENTALES
    *
    *@return \Illuminate\Http\JsonResponse
     */
    public function insertarDetalleProcedimentales(Request $request)
    {
        $this->validate(
            $request, [
            //'aProcedimental			'=> 'required',
            //'iProceId			'=> 'required',
            //'iSilaboId		'=> 'required',
            //'iUniSilId		'=> 'required',
            //'iSemSilId		'=> 'required',
            //'cDesProce		'=> 'required',
            //'cProceUsuarioSis	'=> 'required',
            //'dtProceFechaSis	'=> 'required',
            //'cProceEquipoSis	'=> 'required',
            //'cProceIpSis		'=> 'required',
            //'cProceOpenUsr	'=> 'required',
            //'cProceMacNicSis	'=> 'required',	
            ], 
            [
                //'aProcedimental.required' => 'Ingrese los Detalles Procedimentales.',
            ]
        );
        try {
            foreach ($request->aSilabo as $proce) {
                foreach ($proce['procedimentales'] as $pro) {
                    $parametros = array( 
                        //'iSilaboId'=> 'required',
                        1,
                        1,
                        1,
                        //implode(', ', $pro['procedimentales']),
                        $pro,
                        auth()->user()->cCredUsuario,
                        'equipo',
                        $request->server->get('REMOTE_ADDR'),
                        'mac'
                    );
                    $data = \DB::select('exec ura.Sp_DOCE_INS_Silabo_Detalle_Procedimentales ?, ?, ?, ?, ?, ?, ?, ?', $parametros);
                }  
            }
            return response()->json( $parametros );

            if ($data[0]->id > 0){
                $response = ['validated' => true, 'mensaje' => 'Se guardó los Detalles Procedimentales exitosamente.'];
                $codeResponse = 200;
            }
            else {
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar los Detalles Procedimentales.'];
                $codeResponse = 500;
            }
        } catch (\QueryException $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500; 
        }
        return response()->json( $response, $codeResponse );
    }  








//---------------------------------------------------    

    /*
     *Inserta un registro en la tabla competencia_capacidad
     *
     * 3. COMPETENCIAS
     *     
     *@return \Illuminate\Http\JsonResponse
     */
    public function insertarDetalleCompetencias(Request $request)
    {
        $this->validate(
            $request, [
            //'iSilaboId'=> 'required',
            'competencias'	=> 'required',
            'aCapacidades'=> 'required',
            //'cComCapUsuarioSis'=> 'required',
            //'dtComCapFechaSis'=> 'required',
            //'cComCapEquipoSis'=> 'required',
            //'cComCapIpSis'=> 'required',
            //'cComCapOpenUsr'	=> 'required',
            //'cComCapMacNicSis'=> 'required',
            ], 
            [
                //'iSilaboId.required' => 'Ingrese el Silabo correspondiente.',
                'competencias.required' => 'Ingrese la Competencia General correspondiente.',
                'aCapacidades.required' => 'Ingrese las Capacidades correspondiente.',
            ]
        );
        try {
            foreach ($request->aCapacidades as $capacidades) {
                $parametros = array( 
                    1,
                    $request->competencias, 
                    $capacidades,
                    //$request->iSilaboId, 
                    auth()->user()->cCredUsuario,
                    'equipo',
                    $request->server->get('REMOTE_ADDR'),
                    'mac'
                );
                $data = \DB::select('exec ura.Sp_DOCE_INS_Silabo_Competencia_Capacidad ?, ?, ?, ?, ?, ?, ?', $parametros);
            }    
            if ($data[0]->id > 0){
                $response = ['validated' => true, 'mensaje' => 'Se guardó las Competencia y Capacidades exitosamente.'];
                $codeResponse = 200;
            }
            else {
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar las Competencia y Capacidades.'];
                $codeResponse = 500;
            }
        } catch (\QueryException $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500; 
        }
        return response()->json( $response, $codeResponse );
    }
    
    /*
    *Inserta un registro en la tabla detalle_evaluacion
    *
    * 5. PROCEDIMIENTOS DIDÁCTICOS
    * 
    *@return \Illuminate\Http\JsonResponse
    */    
    public function insertarDetalleProcedimientos(Request $request)
    {
        $this->validate(
            $request, [
            //'iProDidId'=> 'required'
            'aProcedimientos'=> 'required',
            //'iSilaboId'=> 'required',
            'metodos'=> 'required',
            'mediosdidacticos'=> 'required',
            //'cProcDidUsuarioSis'=> 'required'
            //'dtProcDidFechaSis'=> 'required'
            //'cProcDidEquipoSis'=> 'required'
            //'cProcDidIpSis'=> 'required'
            //'cProcDidOpenUsr'=> 'required'
            //'cProcDidMacNicSis'=> 'required'
            ], 
            [
                'aProcedimientos.required' => 'Marque las opciones de Evaluacion Permanente.',            
                //'iSilaboId.required' => 'Ingrese el Silabo correspondiente.',
                'metodos.required' => 'Ingrese la descripcion de la Evaluación.',
                'mediosdidacticos.required' => 'Marque las opciones de Evaluacion Permanente.',

            ]
        );
        try {
            foreach ($request->aProcedimientos as $procedimientos) {
                $parametros = array( 
                    $procedimientos['iProDidTecId'],
                    //'iSilaboId'=> 'required',
                    1,                      
                    $request->metodos, 
                    $request->mediosdidacticos, 
                    auth()->user()->cCredUsuario,
                    'equipo',
                    $request->server->get('REMOTE_ADDR'),
                    'mac'
                );
                $data = \DB::select('exec ura.Sp_DOCE_INS_Silabo_Detalle_Procedimientos_Didacticos ?, ?, ?, ?, ?, ?, ?, ?', $parametros);
            }
            if ($data[0]->id > 0){
                $response = ['validated' => true, 'mensaje' => 'Se guardó los Procedimientos Didacticos exitosamente.'];
                $codeResponse = 200;
            }
            else {
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar los Procedimientos Didacticos .'];
                $codeResponse = 500;
            }
        } catch (\QueryException $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500; 
        }
        return response()->json( $response, $codeResponse );
    }


     /*
     *Inserta un registro en la tabla aprendizaje
     *
     * 6. ACTIVIDADES DE APRENDIZAJE
     * 
     *@return \Illuminate\Http\JsonResponse
     */
    public function insertarAprendizajes(Request $request)
    {
        $this->validate(
            $request, [
            'memorizacion' => 'required',
            'adiestramiento' => 'required',
            'significacion' => 'required',
            ], 
            [
                'memorizacion.required' => 'Ingrese Descripción de la Actividad de Aprendizaje.',
                'adiestramiento.required' => 'Ingrese Descripción de la Actividad de Aprendizaje.',
                'significacion.required' => 'Ingrese Descripción de la Actividad de Aprendizaje.',
            ]
        );

        $parametros = array( 
            $request->memorizacion, 
            $request->adiestramiento, 
            $request->significacion, 
            'user',
            //auth()->user()->cCredUsuario,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        );
        try {
            $data = \DB::select('exec ura.Sp_DOCE_INS_Silabo_Aprendizaje ?, ?, ?, ?, ?, ?, ?', $parametros);
            if ($data[0]->id > 0){
                $response = ['validated' => true, 'mensaje' => 'Se guardó los Aprendizajes exitosamente.'];
                $codeResponse = 200;
            }
            else {
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar los Aprendizajes.'];
                $codeResponse = 500;
            }
        } catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500; 
        }
        return response()->json( $response, $codeResponse );
    }

     /*
    *Inserta un registro en la tabla detalle_equipos 
    *
    * 7.1 EQUIPOS Y MATERIALES
    *
    *@return \Illuminate\Http\JsonResponse
    */
    public function insertarDetalleEquipos(Request $request)
    {
        $this->validate(
            $request, [
            //'iEquiMateId'=> 'required'
            //'iSilaboId'=> 'required',
            'aEquipos'=> 'required',
            //'cEquiMateUsuarioSis'=> 'required'
            //'dtEquiMateFechaSis'=> 'required'
            //'cEquiMateEquipoSis'=> 'required'
            //'cEquiMateIpSis'=> 'required'
            //'cEquiMateOpenUsr'=> 'required'
            //'cEquiMateMacNicSis'=> 'required'
            ], 
            [
                //'iSilaboId.required' => 'Ingrese el Silabo correspondiente.',
                'aEquipos.required' => 'Marque las opciones de Equipos a Utilizar.',
            ]
        );
        
        try {
            foreach ($request->aEquipos as $equipo) {

                $parametros = array( 
                    1,
                    //$request->iSilaboId, 
                    $equipo['iEquiId'], 
                    auth()->user()->cCredUsuario,
                    'equipo',
                    $request->server->get('REMOTE_ADDR'),
                    'mac'
                );

                $data = \DB::select('exec ura.Sp_DOCE_INS_Silabo_Detalle_Equipos ?, ?, ?, ?, ?, ?', $parametros);
            }
            
            if ($data[0]->id > 0){
                $response = ['validated' => true, 'mensaje' => 'Se guardó el Detalles de los Equipos exitosamente.'];
                $codeResponse = 200;
            }
            else {
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar el Detalles de los Equipos .'];
                $codeResponse = 500;
            }
        } catch (\QueryException $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500; 
        }
        return response()->json( $response, $codeResponse );
    }

    /*
    *Inserta un registro en la tabla detalle_materiales
    *
    * 7.2 EQUIPOS Y MATERIALES
    * 
    *@return \Illuminate\Http\JsonResponse
    */    
    public function insertarDetalleMateriales(Request $request)
    {
        $this->validate(
            $request, [
            //'iEquiMateId'=> 'required'
            //'iSilaboId'=> 'required',
            'aMateriales'=> 'required',
            //'cEquiMateUsuarioSis'=> 'required'
            //'dtEquiMateFechaSis'=> 'required'
            //'cEquiMateEquipoSis'=> 'required'
            //'cEquiMateIpSis'=> 'required'
            //'cEquiMateOpenUsr'=> 'required'
            //'cEquiMateMacNicSis'=> 'required'
            ], 
            [
                //'iSilaboId.required' => 'Ingrese el Silabo correspondiente.',
                'aMateriales.required' => 'Marque las opciones de Materiales a Utilizar.',
            ]
        );
        try {
            foreach ($request->aMateriales as $material) {
                $parametros = array( 
                    1,
                    //$request->iSilaboId, 
                    $material['iMateId'], 
                    auth()->user()->cCredUsuario,
                    'equipo',
                    $request->server->get('REMOTE_ADDR'),
                    'mac'
                );
                $data = \DB::select('exec ura.Sp_DOCE_INS_Silabo_Detalle_Materiales ?, ?, ?, ?, ?, ?', $parametros);
            }
            if ($data[0]->id > 0){
                $response = ['validated' => true, 'mensaje' => 'Se guardó el Detalle de los Materiales exitosamente.'];
                $codeResponse = 200;
            }
            else {
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar el Detalle de los Materiales.'];
                $codeResponse = 500;
            }
        } catch (\QueryException $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500; 
        }
        return response()->json( $response, $codeResponse );
    }
 
    /*
    *Inserta un registro en la tabla detalle_evaluacion
    *
    * 8. EVALUACIÓN
    * 
    *@return \Illuminate\Http\JsonResponse
    */    
    public function insertarDetalleEvaluacion(Request $request)
    {
        $this->validate(
            $request, [
            //'iDetEvalId'=> 'required'
            'evaluacion'=> 'required',
            //'cPorDetEval',
            //'iSilaboId'=> 'required',
            'aEvaluaciones'=> 'required',
            //'cDetEvalUsuarioSis'=> 'required'
            //'dtDetEvalFechaSis'=> 'required'
            //'cDetEvalEquipoSis'=> 'required'
            //'cDetEvalIpSis'=> 'required'
            //'cDetEvalOpenUsr'=> 'required'
            //'cDetEvalMacNicSis'=> 'required'
            ], 
            [
                //'iSilaboId.required' => 'Ingrese el Silabo correspondiente.',
                'evaluacion.required' => 'Ingrese la descripcion de la Evaluación.',
                'aEvaluaciones.required' => 'Marque las opciones de Evaluacion Permanente.',
            ]
        );
        try {
            foreach ($request->aEvaluaciones as $evaluaciones) {
                $parametros = array( 
                    $request->evaluacion, 
                    //$evaluacion['cNomDetEval'],
                    'cPorDetEval',
                    //'iSilaboId'=> 'required',
                    1, 
                    $evaluaciones['iEvaPerId'], 
                    auth()->user()->cCredUsuario,
                    'equipo',
                    $request->server->get('REMOTE_ADDR'),
                    'mac'
                );
                $data = \DB::select('exec ura.Sp_DOCE_INS_Silabo_Detalle_Evaluacion ?, ?, ?, ?, ?, ?, ?, ?', $parametros);
            }
            if ($data[0]->id > 0){
                $response = ['validated' => true, 'mensaje' => 'Se guardó el Detalle de los Materiales exitosamente.'];
                $codeResponse = 200;
            }
            else {
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar el Detalle de los Materiales.'];
                $codeResponse = 500;
            }
        } catch (\QueryException $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500; 
        }
        return response()->json( $response, $codeResponse );
    }

    /*
     *Inserta un registro en la tabla fuente_texto_base
     *
     * 9.1 FUENTE - TEXTO BASE
     *      * 
     *@return \Illuminate\Http\JsonResponse
     */
    public function insertarFuenteTextoBase(Request $request)
    {
        $this->validate(
            $request, [
            //'cDesFueTex'	=> 'required'
            //'iSilaboId'=> 'required',
            'aFuenteTB'=> 'required',
            //'cFueTexUsuarioSis'=> 'required',
            //'dtFueTexFechaSis'=> 'required',
            //'cFueTexEquipoSis'=> 'required',
            //'cFueTexIpSis'=> 'required',
            //'cFueTexOpenUsr'	=> 'required',
            //'cFueTexMacNicSis'=> 'required',
            ], 
            [
                //'iSilaboId.required' => 'Ingrese el Silabo correspondiente.',
                'aFuenteTB.required' => 'Ingrese la Fuente de Texto Base correspondiente.',
            ]
        );
        try {
            foreach ($request->aFuenteTB as $fuentetb) {
                $parametros = array( 
                    $fuentetb, 
                     1,
                    //$request->iSilaboId, 
                    auth()->user()->cCredUsuario,
                    'equipo',
                    $request->server->get('REMOTE_ADDR'),
                    'mac'
                );
                $data = \DB::select('exec ura.Sp_DOCE_INS_Silabo_Fuente_Texto_base ?, ?, ?, ?, ?, ?', $parametros);
            }
            if ($data[0]->id > 0){
                $response = ['validated' => true, 'mensaje' => 'Se guardó la Fuente de Textos Base exitosamente.'];
                $codeResponse = 200;
            }
            else {
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar la Fuente de Textos Base.'];
                $codeResponse = 500;
            }
        } catch (\QueryException $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500; 
        }
        return response()->json( $response, $codeResponse );
    }

    /*
     *Inserta un registro en la tabla fuente_bibliografia_complementaria
     *
     * 9.2 FUENTE - BIBLIOGRAFiA COMPLEMENTARIA
     *      * 
     *@return \Illuminate\Http\JsonResponse
     */
    public function insertarFuenteBibliografiaComplementaria(Request $request)
    {
        $this->validate(
            $request, [
            //'cDesFueTex'	=> 'required'
            //'iSilaboId'=> 'required',
            'aFuenteBC'=> 'required',
            //'cFueTexUsuarioSis'=> 'required',
            //'dtFueTexFechaSis'=> 'required',
            //'cFueTexEquipoSis'=> 'required',
            //'cFueTexIpSis'=> 'required',
            //'cFueTexOpenUsr'	=> 'required',
            //'cFueTexMacNicSis'=> 'required',
            ], 
            [
                //'iSilaboId.required' => 'Ingrese el Silabo correspondiente.',
                'aFuenteBC.required' => 'Ingrese la Fuente Bibliografica Complementaria correspondiente.',
            ]
        );
        try {
            foreach ($request->aFuenteBC as $fuentebc) {
                $parametros = array( 
                    //$fuentebc->aFuenteBC, -->se pone de esta manera cuando traemos datos que ya contienen informacion (tabla)
                    $fuentebc, 
                    1,
                    //$request->iSilaboId, 
                    auth()->user()->cCredUsuario,
                    'equipo',
                    $request->server->get('REMOTE_ADDR'),
                    'mac'
                );
                $data = \DB::select('exec ura.Sp_DOCE_INS_Silabo_Fuente_Bibliografia_Complementaria ?, ?, ?, ?, ?, ?', $parametros);
            }
            if ($data[0]->id > 0){
                $response = ['validated' => true, 'mensaje' => 'Se guardó las Fuentes Bibliografica Complementaria exitosamente.'];
                $codeResponse = 200;
            }
            else {
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar las Fuentes Bibliografica Complementaria.'];
                $codeResponse = 500;
            }
        } catch (\QueryException $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500; 
        }
        return response()->json( $response, $codeResponse );
    }

    /*
    *Inserta un registro en la tabla fuente_bibliografia_complementaria
    *
    * 9.3 FUENTES ELECTRONICAS
    *
    *@return \Illuminate\Http\JsonResponse
     */
    public function insertarFuenteElectronicas(Request $request)
    {
        $this->validate(
            $request, [
            //'cDesFueTex'	=> 'required'
            //'iSilaboId'=> 'required',
            'aFuenteE'=> 'required',
            //'cFueTexUsuarioSis'=> 'required',
            //'dtFueTexFechaSis'=> 'required',
            //'cFueTexEquipoSis'=> 'required',
            //'cFueTexIpSis'=> 'required',
            //'cFueTexOpenUsr'	=> 'required',
            //'cFueTexMacNicSis'=> 'required',
            ], 
            [
                //'iSilaboId.required' => 'Ingrese el Silabo correspondiente.',
                'aFuenteE.required' => 'Ingrese las Fuentes Electronicas correspondiente.',
            ]
        );
        try {
            foreach ($request->aFuenteE as $fuentee) {
                $parametros = array( 
                    $fuentee, 
                    1,
                    //$request->iSilaboId, 
                    auth()->user()->cCredUsuario,
                    'equipo',
                    $request->server->get('REMOTE_ADDR'),
                    'mac'
                );
                $data = \DB::select('exec ura.Sp_DOCE_INS_Silabo_Fuente_Electronicas ?, ?, ?, ?, ?, ?', $parametros);
            }
            if ($data[0]->id > 0){
                $response = ['validated' => true, 'mensaje' => 'Se guardó las Fuentes Electronicas exitosamente.'];
                $codeResponse = 200;
            }
            else {
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar las Fuentes Electronicas.'];
                $codeResponse = 500;
            }
        } catch (\QueryException $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500; 
        }
        return response()->json( $response, $codeResponse );
    }    

}