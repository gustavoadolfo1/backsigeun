<?php

namespace App\Http\Controllers\Patrimonio;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Bienes extends Controller
{
    /**
     * 
     * 
     * @return \Illuminate\Http\JsonResponse
     */

    //public function getResult($skip,$top,$inlinecount,$format){
     public function getResult(Request $request){
      $generator = new \Picqer\Barcode\BarcodeGeneratorJPG();

     // 'data:image/png;base64,etiqueta'
     

          $skip=$request->skip;
          $top=$request->top;
          $baja=$request->baja;
          $iSituacionBienId=$request->iSituacionBienId;
          $order=$request->order;
          $value_filtro_cBienDescripcion="";
          $value_filtro_cBienCodigo="";
          $value_filtro_cDocAdqNro="";
          $order="";
          $anio=$request->anio;
          $intAnio = (int)$anio;
          $anio=$intAnio-1;
          if($request->order){$order=$request->order;}
          if($request->filter){                    
            for($i=0;$i<count($request->filter[0]['predicates']);$i++){    
                  switch ($request->filter[0]['predicates'][$i]['field']) {
                    case 'cBienDescripcion':
                     $value_filtro_cBienDescripcion=$request->filter[0]['predicates'][$i]['value'];  
                    break;
                    case 'cBienCodigo':
                     $value_filtro_cBienCodigo=$request->filter[0]['predicates'][$i]['value'];  
                    break;
                    case 'cDocAdqNro':
                     $value_filtro_cDocAdqNro=$request->filter[0]['predicates'][$i]['value'];  
                    break;
                 }
            }

          }
          $datos_bienes = null;  
          if($baja==0){
            $datos = \DB::select("EXEC pat.Sp_SEL_BienPorSituacion ?,?,?,?,?,?,?,?",array( $skip,$top,$anio,$iSituacionBienId,$value_filtro_cBienDescripcion,$value_filtro_cBienCodigo,$value_filtro_cDocAdqNro,$order));//obtencion de datos
            $total=\DB::select("EXEC pat.Sp_COUNT_BienPorSituacion ?,?,?,?",array($iSituacionBienId,$value_filtro_cBienDescripcion,$value_filtro_cBienCodigo,$value_filtro_cDocAdqNro));//obtencion de total
          }else{
            $datos = \DB::select("EXEC pat.Sp_SEL_BienBaja ?,?,?,?,?,?,?",array( $skip,$top,$anio,$value_filtro_cBienDescripcion,$value_filtro_cBienCodigo,$value_filtro_cDocAdqNro,$order));//obtencion de datos
            $total=\DB::select("EXEC pat.Sp_COUNT_BienBaja ?,?,?",array($value_filtro_cBienDescripcion,$value_filtro_cBienCodigo,$value_filtro_cDocAdqNro));//obtencion de total
          }

        if($datos) {

         foreach ($datos as $d) {
         //  $imagen = $generador->getBarcode($texto, $tipo);
          
          //  $base64 = chunk_split(base64_encode($imagen));

           $base64 = 'data:image/jpeg;base64,'.chunk_split(base64_encode($generator->getBarcode($d->cBienCodigo, $generator::TYPE_CODE_128,2,30,array(69, 50, 94))));
           $datos_colores= \DB::select("EXEC pat.Sp_SEL_Bien_color ?",array( $d->iBienId));
         //  $datos_c=null;

           $datos_c = array();
          //  array_push($pila, "manzana", "arándano");
             if($datos_colores){
              foreach ($datos_colores as $dc) {                 
                     // $datos_c=array($dc->iColorId);
                 array_push($datos_c, $dc->iColorId);
              }
             }
            
              $datos_bienes[]=array(    
                    'RowNumber'=>$d->RowNumber,
                    'etiqueta'=>$base64,                

                    'iTipoCId'=>$d->iTipoCId,
                    'iBienId' =>$d->iBienId,
                    'cBienCodigo'=>$d->cBienCodigo,
                    'cBienDescripcion' =>$d->cBienDescripcion,
                    'nBienValor'=>$d->nBienValor,
                    'cBienSerie'=>$d->cBienSerie,
                    'cBienDimension'=>$d->cBienDimension,
                    'cBienOtrasCaracteristicas'=>$d->cBienOtrasCaracteristicas,
                    'bBienBaja'=>$d->bBienBaja,
                   'dBienFechaBaja'=>$d->dBienFechaBaja,
                    'cBienCausalBaja'=>$d->cBienCausalBaja,
                    'cBienResolucionBaja'=>$d->cBienResolucionBaja,
                   'dBienAnioFabricacion'=>$d->dBienAnioFabricacion,
                   'cBienObs'=>$d->cBienObs,
                   // 'iEstadoBienId' =>$d->iEstadoBienId,
                   'iPlanConSubCueId'=>$d->iPlanConSubCueId,
                   'iPlanConMayorId'=>$d->iPlanConMayorId,   


                    'iBienVidaUtil'=>$d->iBienVidaUtil,  
                    'dBienFinVida'=>$d->dBienFinVida,  
                    'dBienInicioVida'=>$d->dBienInicioVida,  
                    'nBienValorDepreciacion'=>$d->nBienValorDepreciacion,  
                    'nBienCuotaSalvamiento'=>$d->nBienCuotaSalvamiento,  
                    'nBienTasaDepreciacion'=>$d->nBienTasaDepreciacion,  
                    'nBienDepreciacionAcumulada'=>$d->nBienDepreciacionAcumulada,    


                    'iTipoId'  =>$d->iTipoId,
                    
                    

                    'iYearId'=>$d->iYearId,
                    'iCatalogoNoPatId'=>$d->iCatalogoNoPatId,
                    'iCatSbnId'=>$d->iCatSbnId,
                    'iDocAdqId'=>$d->iDocAdqId,


                    'cDocAdqNro'=>$d->cDocAdqNro,
                    'dDocAdqFecha'=>$d->dDocAdqFecha,
                    'nDocAdqValor'=>$d->nDocAdqValor,
                    'cFormaAdqDescripcion'=>$d->cFormaAdqDescripcion,

                    'cTipoDescripcion'=>$d->cTipoDescripcion,
                    'cModeloDescripcion'=>$d->cModeloDescripcion,
                    'cMarcaDescripcion'=>$d->cMarcaDescripcion,

                    'iCatalogoId' =>'0',   

                     'colores'=> $datos_c ,
                     'iTipoDespId'=>$d->iTipoDespId, 
                     'bVerificacionEstado'=> $d->bVerificacionEstado         
                );
           }
         }
         
     // print_r($datos)
         // $total=45;
         
          $data = [       
                    'filter'=> $value_filtro_cDocAdqNro,              
                    'results' =>$datos_bienes,
                    'count' => $total[0]->iTotalRegistros
                                             
                  ];

       return response()->json($data);
        //  return $data;
       // return response()->"{\"result\":" .json($respuesta). ",\"count\":".$total."}";
    }

     public function getCombo(){
       // $respuesta = DB::select('EXECUTE tram.Sp_SEL_CiclosAcademicosMatricXcEstudCodUniv ?', $data);
         $datos = \DB::select("EXEC pat.Sp_SEL_Combo_Grupo_generico");
  
          $data = [         
                    'results' =>$datos                                             
                  ];

       return response()->json($datos);
        //  return $data;
       // return response()->"{\"result\":" .json($respuesta). ",\"count\":".$total."}";
    }

 


    public function guardar(Request $request)
    {
        $this->validate(
            $request, 
            [
                'iTipoCId' => 'required',
                'cBienCodigo' => 'required',
                'cBienDescripcion' => 'required',
                'cBienSerie' => 'required',
                'cBienDimension' => 'required',
                'dBienAnioFabricacion' => 'required',
                'iEstadoBienId' => 'required',
                'iDocAdqId' => 'required',
               
                'iTipoId' => 'required',
                'iCatalogoId' => 'required',
                'nBienValor' => 'required',
                'colores' => 'required',
            ], 
            [
               
                'iTipoCId.required' => 'Hubo un problema al obtener Id Tipo Catalogo ',
                'cBienCodigo.required' => 'Hubo un problema al obtener el codigo ',
                'cBienDescripcion.required' => 'Hubo un problema al obtener la descripcion ',
                'cBienSerie.required' => 'Hubo un problema al obtener la Serie ',
                'dBienAnioFabricacion.required' => 'Hubo un problema al obtener año de fabricacion ',
                'iEstadoBienId.required' => 'Hubo un problema al obtener el ID de estado de bien ',
                'iDocAdqId.required' => 'Hubo un problema al obtener el ID de documento de adquisición ',                
                'iTipoId.required' => 'Hubo un problema al obtenerel ID de tipo/modelo/marca ',
                'iCatalogoId.required' => 'Hubo un problema al obtener el ID de catalogo  ',
                'nBienValor.required' => 'Hubo un problema al obtener el valor del bien  ',
                 'colores.required' => 'Hubo un problema al obtener colores ',
            ]
        );
        //recorremos el objeto colores
        //y lo ttrasformamos en un xml
      for($ii=1;$ii<=$request->cantidad;$ii++){


        $xml="<raiz>";
        $longitud = count($request->colores);        
        //Recorro todos los elementos
        for($i=0; $i<$longitud; $i++)
         {            
         $xml=$xml.'<parametro iColorId="'.$request->colores[$i].'" />';             
         }      
         $xml=$xml."</raiz>";
        $ip = $request->server->get('REMOTE_ADDR');
        try {
              $queryResult = \DB::select("exec [pat].[Sp_INS_Bien] ?, ?,?,?,?,?,?,?,?,?,?,?,?,?,?,? ",array(
              $request->iTipoCId,
              $request->iCatalogoId,
              $request->cBienCodigo,$request->cBienDescripcion,$request->nBienValor,$request->cBienSerie,$request->cBienDimension,
              $request->cBienOtrasCaracteristicas,
              $request->dBienAnioFabricacion,
              $request->iEstadoBienId,
              $request->iTipoId,
               $request->iYearId,
              $request->iDocAdqId,
              $xml,
              $request->iPlanConSubCueId,
              20648) );   
            $response = ['validated' => true, 'mensaje' => 'Se guardó  el Bien: '. $queryResult[0]->cBienDescripcion.', exitosamente.', 'queryResult' => $queryResult[0] ];
            $codeResponse = 200;            
        } catch (\QueryException $e) {;
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'code' => $e->getCode(), 'exception' => $e];
            $codeResponse = 500;
        }  
      } 
       // echo $xml;      
       return response()->json( $response, $codeResponse );

    }

     public function modificar(Request $request, $id)
    {
            
        $this->validate(
            $request, 
            [
                'iBienId' => 'required',
                'cBienDescripcion' => 'required',
                'cBienSerie' => 'required',
                'cBienDimension' => 'required',
                'dBienAnioFabricacion' => 'required',
               
             
                
                'iTipoId' => 'required',
                'nBienValor' => 'required',
                 'colores' => 'required',
            ], 
            [
                 'iBienId.required' => 'Hubo un problema al obtener Id del Bien ',      
                      'cBienDescripcion.required' => 'Hubo un problema al obtener la descripcion ',
                'cBienSerie.required' => 'Hubo un problema al obtener la Serie ',
                'dBienAnioFabricacion.required' => 'Hubo un problema al obtener año de fabricacion ',
                
            
               
                'iTipoId.required' => 'Hubo un problema al obtenerel ID de tipo/modelo/marca ',
                'nBienValor.required' => 'Hubo un problema al obtener el valor del bien  ',
                'colores.required' => 'Hubo un problema al obtener colores ',
            ]
        );
        $ip = $request->server->get('REMOTE_ADDR');
       /* $response = ['validated' => true, 'mensaje' => 'Se guardó el Local exitosamente.'];
            $codeResponse = 200;  */


         $xml="<raiz>";
        $longitud = count($request->colores);        
        //Recorro todos los elementos
        for($i=0; $i<$longitud; $i++)
         {            
         $xml=$xml.'<parametro iColorId="'.$request->colores[$i].'" />';             
         }      
         $xml=$xml."</raiz>";






        try {
            $queryResult = \DB::select("exec [pat].[Sp_UPD_Bien] ?, ?, ?,?,?,?,?,?,?,?,?", array($id,$request->cBienDescripcion,$request->nBienValor,$request->cBienSerie,$request->cBienDimension,$request->_cBienOtrasCaracteristicas,$request->dBienAnioFabricacion,$request->iTipoId, $xml,$request->iPlanConSubCueId,20648));   

            $response = ['validated' => true, 'mensaje' => 'Se Modifico  el BIen : '. $queryResult[0]->cBienDescripcion.', exitosamente.', 'queryResult' => $queryResult[0] ];
            $codeResponse = 200;            
        } catch (\QueryException $e) {
            //return response()->json( $e );
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'code' => $e->getCode(), 'exception' => $e];
            $codeResponse = 500;
        }
        //NO CAPTURAN LOS EERROS SQL DB
        
        return response()->json( $response, $codeResponse );

    }


      public function baja(Request $request, $id)
    {


            
        $this->validate(
            $request, 
            [
                'iBienId' => 'required',
                'cBienDescripcion' => 'required',
                'dBienFechaBaja' => 'required',
                'cBienCausalBaja' => 'required',
                'cBienResolucionBaja' => 'required',
               
            ], 
            [
                 'iBienId.required' => 'Hubo un problema al obtener Id del Bien ',      
                 'cBienDescripcion.required' => 'Hubo un problema al obtener la descripcion ',
                 'dBienFechaBaja.required' => 'Hubo un problema al obtener la fecha de baja ',
                 'cBienCausalBaja.required' => 'Hubo un problema al obtener el causal de la baja ',
                 'cBienResolucionBaja.required' => 'Hubo un problema al obtener la resolucion o documento ',
              
            ]
        );
        $ip = $request->server->get('REMOTE_ADDR');
       /* $response = ['validated' => true, 'mensaje' => 'Se guardó el Local exitosamente.'];
            $codeResponse = 200;  */








        try {
            $queryResult = \DB::select("exec [pat].[Sp_UPD_BienBaja] ?, ?, ?,?,?,?", array($id,$request->cBienDescripcion,$request->dBienFechaBaja,$request->cBienCausalBaja,$request->cBienResolucionBaja,20648));   

            $response = ['validated' => true, 'mensaje' => 'Se dio de baja  el BIen : '. $queryResult[0]->cBienDescripcion.', exitosamente.', 'queryResult' => $queryResult[0] ];
            $codeResponse = 200;            
        } catch (\QueryException $e) {
            //return response()->json( $e );
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'code' => $e->getCode(), 'exception' => $e];
            $codeResponse = 500;
        }
        //NO CAPTURAN LOS EERROS SQL DB
        
        return response()->json( $response, $codeResponse );

    }


    public function eliminar($id)
    {
       

       /* $data = \DB::select('exec pat.Sp_DEL_Bien ?', array($id));
        if ($data[0]->eliminados > 0) {
            $response = [ 'validated' => true, 'mensaje' => 'Se eliminó el horario exitosamente.', 'eliminados' => $data[0]->eliminados ];
            $codeResponse = 200;
        } else {
            $response = [ 'validated' => true, 'mensaje' => 'El horario no se ha podido eliminar o no existe.', 'eliminados' => $data[0]->eliminados ];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);*/


          try {
            $data = \DB::select('exec pat.Sp_DEL_Bien ?', array($id));
            $response = [ 'validated' => true, 'mensaje' => 'Se eliminó el registro exitosamente.'];         
            $codeResponse = 200;  
            return response()->json( $response, $codeResponse );
          }catch (\Illuminate\Database\QueryException $e){
              // $response = $e->errorInfo[2];//[ 'validated' => false, 'mensaje' => 'El registro no se ha podido eliminar o no existe.'];
                $response = [ 'validated' => false, 'mensaje' => str_replace("[Microsoft][ODBC Driver 17 for SQL Server][SQL Server]"," ",$e->errorInfo[2])  ];
                $codeResponse = 500;                  
          }
          return response()->json( $response, $codeResponse );         
      
    }


     public function shear_x_codigoAndroid($codigo,$centroCosto){

        $datos = \DB::select("EXEC pat.Sp_SEL_Bien_x_Codigo ?,?",array( $codigo,$centroCosto)); 

         $data = [  'bienEmpleado' =>$datos  ];
         return response()->json($data);

     
    }

   

}
