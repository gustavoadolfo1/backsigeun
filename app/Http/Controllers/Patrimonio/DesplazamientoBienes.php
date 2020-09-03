<?php

namespace App\Http\Controllers\Patrimonio;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DesplazamientoBienes extends Controller
{
    /**
     * 
     * 
     * @return \Illuminate\Http\JsonResponse
     */

    //public function getResult($skip,$top,$inlinecount,$format){
     public function getResult(Request $request){

          $skip=$request->skip;
          $top=$request->top;
          $order=$request->order;
          $value_filtro_cDocAdqNro="";
          $order="";
          if($request->order){$order=$request->order;}
          if($request->filter){                    
            for($i=0;$i<count($request->filter[0]['predicates']);$i++){              
              if($request->filter[0]['predicates'][$i]['field']=="iDespBienId"){
                 $value_filtro_cDocAdqNro=$request->filter[0]['predicates'][$i]['value'];
              }
            }

          }
          $datos_desplazamientoData = [];        
        // $datos = \DB::connection('sqlsrvSiga')->select("EXEC pat.Sp_SEL_Documento ?,?,?,?",array( $skip,$top,$value_filtro_cDocAdqNro,$order));
          $datos_desplazamiento = \DB::select("EXEC pat.Sp_SEL_DesplazamientoBienes ?,?,?,?",array( $skip,$top,$value_filtro_cDocAdqNro,$order));




           foreach ($datos_desplazamiento as $d) {
         //  $imagen = $generador->getBarcode($texto, $tipo);
          
          //  $base64 = chunk_split(base64_encode($imagen));

         
           $datos_b= \DB::select("EXEC pat.Sp_SEL_DesplazamientoBienesDetalle ?",array( $d->iDespBienId));
         //  $datos_c=null;

            $datos_bienes= [];  
          //  array_push($pila, "manzana", "arándano");
             if($datos_b){
              foreach ($datos_b as $dd) {                 
                     // $datos_c=array($dc->iColorId);

                 //recuperamos los colores
                   $datos_bienes_colores= [];  
                  $datos_colores= \DB::select("EXEC pat.Sp_SEL_Bien_color ?",array( $dd->iBienId));
                 //  $datos_c=null;

                   $datos_c = array();
                  //  array_push($pila, "manzana", "arándano");
                     if($datos_colores){
                      foreach ($datos_colores as $dc) {                 
                          $datos_bienes_colores[]=array(    
                              
                               'iColorId'=>$dc->iColorId, 
                               'cColorCodigoHex'=>$dc->cColorCodigoHex,
                                'cColorNombre'=>$dc->cColorNombre,                                     
                          );
                      }
                     }
                  $datos_bienes[]=array(    
                      'RowNumber'=>$dd->RowNumber,
                       'iDespBienDetID'=>$dd->iDespBienDetID, 
                       'cDespBienDetObs'=>$dd->cDespBienDetObs, 
                       'bDespBienDetUltimoDesp'=>$dd->bDespBienDetUltimoDesp, 
                       'iDespBienId'=>$dd->iDespBienId, 
                       'iEstadoBienId'=>$dd->iEstadoBienId,                     
                       'iEstadoBienId'=>$dd->iEstadoBienId, 
                       'cBienCodigo'=>$dd->cBienCodigo, 
                       'cBienDescripcion'=>$dd->cBienDescripcion, 
                       'cBienSerie'=>$dd->cBienSerie, 
                       'cBienDimension'=>$dd->cBienDimension, 
                       'cTipoDescripcion'=>$dd->cTipoDescripcion, 
                       'cModeloDescripcion'=>$dd->cModeloDescripcion, 
                       'cMarcaDescripcion'=>$dd->cMarcaDescripcion, 
                       'cEstadoBienAbre'=>$dd->cEstadoBienAbre, 
                        'colores'=>$datos_bienes_colores  
                                 
                  );
              }
             }
            

              $datos_desplazamientoData[]=array(    
                    'RowNumber'=>$d->RowNumber,

                     'iDespBienId'=>$d->iDespBienId, 
                     'dDespBienFecha'=>$d->dDespBienFecha, 
                     'cDespBienDocRef'=>$d->cDespBienDocRef, 
                     'iTipoDespId'=>$d->iTipoDespId, 
                     'idCentroCostoEmpleado'=>$d->idCentroCostoEmpleado,                     
                     'iYearId'=>$d->iYearId, 
                     'cTipoDespDescripcion'=>$d->cTipoDespDescripcion, 
                     'cDepenNombre'=>$d->cDepenNombre, 
                     'empleado'=>$d->empleado, 
                     'cCargNombrsse'=>$d->cCargNombre, 
                     'cCentroCostoNombre'=>$d->cCentroCostoNombre, 
                     'bienes'=> $datos_bienes ,
                     'cDepenNombreO'=>$d->cDepenNombreO, 
                     'cEmpleadoO'=>$d->empleadoO  
                           
                );
           }
         

     // print_r($datos)
         // $total=45;
          $total=\DB::select("EXEC pat.Sp_COUNT_DesplazamientoBienes ?",array($value_filtro_cDocAdqNro));
          $data = [       
                    'filter'=> $value_filtro_cDocAdqNro,              
                    'results' =>$datos_desplazamientoData,
                    'count' => $total[0]->iTotalRegistros
                                             
                  ];

       return response()->json($data);
        //  return $data;
       // return response()->"{\"result\":" .json($respuesta). ",\"count\":".$total."}";
    }

     public function getResultRow($iDespBienId){

           
        // $datos = \DB::connection('sqlsrvSiga')->select("EXEC pat.Sp_SEL_Documento ?,?,?,?",array( $skip,$top,$value_filtro_cDocAdqNro,$order));
          $datos_desplazamiento = \DB::select("EXEC pat.Sp_SEL_DesplazamientoBienes_Row ?",array( $iDespBienId));



          $datos_desplazamientoData=array();
          if($datos_desplazamiento){

         
          $datos_b= \DB::select("EXEC pat.Sp_SEL_DesplazamientoBienesDetalle ?",array( $datos_desplazamiento[0]->iDespBienId));
         //  $datos_c=null;

          $datos_bienes= [];  
          //  array_push($pila, "manzana", "arándano");
             if($datos_b){
              foreach ($datos_b as $dd) {  
                   $datos_bienes_colores= [];  
                  $datos_colores= \DB::select("EXEC pat.Sp_SEL_Bien_color ?",array( $dd->iBienId));
                 //  $datos_c=null;

                   $datos_c = array();
                  //  array_push($pila, "manzana", "arándano");
                     if($datos_colores){
                      foreach ($datos_colores as $dc) {                 
                          $datos_bienes_colores[]=array(    
                              
                               'iColorId'=>$dc->iColorId, 
                               'cColorCodigoHex'=>$dc->cColorCodigoHex,
                                'cColorNombre'=>$dc->cColorNombre,                                     
                          );
                      }
                     }
                  $datos_bienes[]=array(    
                      'RowNumber'=>$dd->RowNumber,
                       'iDespBienDetID'=>$dd->iDespBienDetID, 
                       'cDespBienDetObs'=>$dd->cDespBienDetObs, 
                       'bDespBienDetUltimoDesp'=>$dd->bDespBienDetUltimoDesp, 
                       'iDespBienId'=>$dd->iDespBienId, 
                       'iEstadoBienId'=>$dd->iEstadoBienId,                     
                       'iEstadoBienId'=>$dd->iEstadoBienId, 
                       'cBienCodigo'=>$dd->cBienCodigo, 
                       'cBienDescripcion'=>$dd->cBienDescripcion, 
                       'cBienSerie'=>$dd->cBienSerie, 
                       'cBienDimension'=>$dd->cBienDimension, 
                       'cTipoDescripcion'=>$dd->cTipoDescripcion, 
                       'cModeloDescripcion'=>$dd->cModeloDescripcion, 
                       'cMarcaDescripcion'=>$dd->cMarcaDescripcion, 
                       'cEstadoBienAbre'=>$dd->cEstadoBienAbre, 
                        'colores'=>$datos_bienes_colores  
                                 
                  );
              }
             
            }

              $datos_desplazamientoData[]=array(    
                    'RowNumber'=>$datos_desplazamiento[0]->RowNumber,

                     'iDespBienId'=>$datos_desplazamiento[0]->iDespBienId, 
                     'dDespBienFecha'=>$datos_desplazamiento[0]->dDespBienFecha, 
                     'cDespBienDocRef'=>$datos_desplazamiento[0]->cDespBienDocRef, 
                     'iTipoDespId'=>$datos_desplazamiento[0]->iTipoDespId, 
                     'idCentroCostoEmpleado'=>$datos_desplazamiento[0]->idCentroCostoEmpleado,                     
                     'iYearId'=>$datos_desplazamiento[0]->iYearId, 
                     'cTipoDespDescripcion'=>$datos_desplazamiento[0]->cTipoDespDescripcion, 
                     'cDepenNombre'=>$datos_desplazamiento[0]->cDepenNombre, 
                     'empleado'=>$datos_desplazamiento[0]->empleado, 
                     'cCargNombrsse'=>$datos_desplazamiento[0]->cCargNombre, 
                     'cCentroCostoNombre'=>$datos_desplazamiento[0]->cCentroCostoNombre, 
                     'bienes'=> $datos_bienes ,
                     'cDepenNombreO'=>$datos_desplazamiento[0]->cDepenNombreO, 
                     'cEmpleadoO'=>$datos_desplazamiento[0]->empleadoO  
                           
                );
           }
         

     // print_r($datos)
         // $total=45;
         // $total=\DB::select("EXEC pat.Sp_COUNT_DesplazamientoBienes ?",array($value_filtro_cDocAdqNro));
          $data = [       
                                 
                    'results' =>$datos_desplazamientoData,
                    
                                             
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



  public function dataAsignacionBienes(Request $reques, $iDocAdqId ){
       // $respuesta = DB::select('EXECUTE tram.Sp_SEL_CiclosAcademicosMatricXcEstudCodUniv ?', $data);
         $datos = \DB::select("EXEC pat.Sp_SEL_Bien_X_Doc_Adq ?",array($iDocAdqId));
  
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
                'iTipoDespId' => 'required',
               
                'idCentroCostoEmpleado' => 'required',
                'idCentroCostoEmpleadoOrigen' => 'required',
                'bienes' => 'required',

            ], 
            [
               
                'iTipoDespId.required' => 'Hubo un problema al el tipo de desplazamiento',
                
                'idCentroCostoEmpleado.required' => 'Hubo un problema al obtener el empleado ',
                'idCentroCostoEmpleadoOrigen.required'=> 'Hubo un problema al obtener Empleado Origen',
                'bienes.required' => 'Hubo un problema al obtener los bienes',
            ]
        );



       $xml="<raiz>";
        $longitud = count($request->bienes);        
        //Recorro todos los elementos
        $iEstadoBienId=1;
        for($i=0; $i<$longitud; $i++)
         {          
         switch ($request->bienes[$i]['cEstadoBienDescripcion']) {
            case 'Bueno':
                $iEstadoBienId=1;
                break;
            case 'Malo':
                $iEstadoBienId=2;
                break;
            case 'Regular':
               $iEstadoBienId=3;
                break;
            case 'Nuevo':
                $iEstadoBienId=7;
                break;
        }  

         $xml=$xml.'<parametro iDespBienDetID="'.$request->bienes[$i]['iDespBienDetID'].'"  iEstadoBienId="'.$iEstadoBienId.'"  iBienId="'.$request->bienes[$i]['iBienId'].'"  cDespBienDetObs="'.$request->bienes[$i]['cDespBienDetObs'].'" />';             
         }      
         $xml=$xml."</raiz>";



        $ip = $request->server->get('REMOTE_ADDR');
        try {
            $queryResult = \DB::select("exec [pat].[Sp_INS_DesplazamientoBienes] ?,?,?,?,?,?,?,?,? ",array($request->dDespBienFecha,$request->cDespBienDocRef,$request->iTipoDespId,$request->idCentroCostoEmpleado
              ,$request->iYearId,$xml,$request->idCentroCostoEmpleadoOrigen,$request->asignacion,20648) );   
            $response = ['validated' => true, 'mensaje' => 'Se guardó  : '. $queryResult[0]->iDespBienId.', exitosamente.', 'queryResult' => $queryResult[0] ];
            $codeResponse = 200;            
        } catch (\QueryException $e) {;
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'code' => $e->getCode(), 'exception' => $e];
            $codeResponse = 500;
        }        
        return response()->json( $response, $codeResponse );

    }

     public function modificar(Request $request, $id)
    {
            
       $this->validate(
            $request, 
            [
                'iDocAdqId' => 'required',
                'cDocAdqNro' => 'required',
               
                'dDocAdqFecha' => 'required',
                'nDocAdqValor' => 'required',

            ], 
            [
               
                'iDocAdqId.required' => 'Hubo un problema al obtener el ID  la o/c',
                'cDocAdqNro.required' => 'Hubo un problema al obtener el nro  d la o/c',
                
                'dDocAdqFecha.required' => 'Hubo un problema al obtener  la fecha ',
                'nDocAdqValor.required' => 'Hubo un problema al obtener el valor',
            ]
        );

        $ip = $request->server->get('REMOTE_ADDR');
       /* $response = ['validated' => true, 'mensaje' => 'Se guardó el Local exitosamente.'];
            $codeResponse = 200;  */
        try {
            $queryResult = \DB::select("exec [pat].[Sp_UPD_Documento] ?, ?, ?,?,?,?,?", array($id,$request->cDocAdqNro,$request->dDocAdqFecha,$request->nDocAdqValor,$request->cDocAdqObs,$request->iFormaAdqId,20648));   
            $response = ['validated' => true, 'mensaje' => 'Se Modifico  el Documento : '. $queryResult[0]->cDocAdqNro.', exitosamente.', 'queryResult' => $queryResult[0] ];
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
        $data = \DB::select('exec pat.Sp_DEL_Documento ?', array($id));
        if ($data[0]->eliminados > 0) {
            $response = [ 'validated' => true, 'mensaje' => 'Se eliminó el horario exitosamente.', 'eliminados' => $data[0]->eliminados ];
            $codeResponse = 200;
        } else {
            $response = [ 'validated' => true, 'mensaje' => 'La O/C no se ha podido eliminar o no existe.', 'eliminados' => $data[0]->eliminados ];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }

   

}
