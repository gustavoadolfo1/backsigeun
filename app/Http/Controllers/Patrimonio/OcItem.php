<?php

namespace App\Http\Controllers\Patrimonio;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OcItem extends Controller
{
    /**
     * 
     * 
     * @return \Illuminate\Http\JsonResponse
     */

    //public function getResult($skip,$top,$inlinecount,$format){
     public function getResult(Request $request){
       $data=array();
       $tt=is_numeric($request->nrooc);
       if(is_numeric($request->nrooc)){
           if($request->nrooc!=""){
         $skip=$request->skip;
          $top=$request->top;
          $_SEC_EJE=1230; //UNAM 
          //$_anio=2019; //UNAM 
          $_anio=$request->anio;
         // $_oc=$request->ocnro; //UNAM 
          $_oc=$request->nrooc;
          $order=$request->order;
          $value_filtro_CODIGO="";
          $order="";
          //if($request->order){$order=$request->order;}
          if($request->filter){                    
            for($i=0;$i<count($request->filter[0]['predicates']);$i++){              
              if($request->filter[0]['predicates'][$i]['field']=="CODIGO"){
                 $value_filtro_CODIGO=$request->filter[0]['predicates'][$i]['value'];
              }
            }

          }
          $datos = null;        
         $datos = \DB::select("EXEC pat.SP_SIGEUN_PAT_SEL_OC_ITEM ?,?,?,?,?,?",array( $skip,$top,$_SEC_EJE, $_anio,$value_filtro_CODIGO,$_oc));


         $datos_items = null;  

          foreach ($datos as $d) {//recorremos y chekeamos si escompatible SIGEUN CON SIGA

           //EXTRAEMOS el clasificador
            $cuentas=array();
            $clasificador_='';
            $clasificador = \DB::select("EXEC pat.SP_SIGEUN_PAT_SEL_OC_ITEM_PPTO ?,?,?,?,?",array( $_SEC_EJE,$_anio,$d->NRO_ORDEN,$d->SEC_ITEM,'B'));
            if($clasificador){
             $clasificador_= $clasificador[0]->CLASIFICADOR;
             

   
            }  
           //FIN CUENTA CONTRABLE 

        
          //CONSULTAMOS SI ESTA EN NUESTRA  BASE DE DATOS
            $b=0;
            $datos_catalogoSBN = \DB::select("EXEC pat.Sp_SEL_CatalogoSBN ?,?,?,?,?",array( 1,1,'',substr($d->CODIGO,0, 8),'')); 
            if($datos_catalogoSBN){$b=1;}
            $datos_items[]=array(    
                  'RowNumber'=>$d->RowNumber, 

              
                  'GRUPO_BIEN'=>$d->GRUPO_BIEN,
                  'CLASE_BIEN'=>$d->CLASE_BIEN,
                  'FAMILIA_BIEN'=>$d->FAMILIA_BIEN,
                  'anio'=>$d->ANO_EJE,  
                  'CODIGO'=>$d->CODIGO,
                  'NOMBRE_ITEM' =>$d->NOMBRE_ITEM,
                  'CANT_ITEM'=>$d->CANT_ITEM,
                  'PREC_UNIT_MONEDA' =>$d->PREC_UNIT_MONEDA,
                  'PREC_TOT_SOLES'=>$d->PREC_TOT_SOLES,
                  'b'=>$b,
                  'clasificador'=>$clasificador_,
                  'cuentas'=> [         
                    'results' =>$cuentas                                             
                  ]  

            );
           }
           

          
         
          
     // print_r($datos)
         // $total=45;
          $total=\DB::select("EXEC pat.SP_SIGEUN_PAT_COUNT_OC_ITEM ?,?,?,?",array($_SEC_EJE,$value_filtro_CODIGO, $_oc, $_anio));
          $data = [       
                    'filter'=> $value_filtro_CODIGO,              
                    'results' =>$datos_items,
                    'count' => $total[0]->iTotalRegistros,
                    'numeric'=>$tt
                                             
                  ];

       
       }

       }
    
        return response()->json($data);
        //  return $data;
       // return response()->"{\"result\":" .json($respuesta). ",\"count\":".$total."}";
    }

   
  public function comboCuentas($_anio,$clasificador,$GRUPO_BIEN,$CLASE_BIEN,$FAMILIA_BIEN){  
          $cuentas_data=array();
          $cuentas =  \DB::select("EXEC pat.SP_SIGEUN_PAT_SEL_OC_ITEM_PPTO_CUENTAS ?,?,?,?,?",array( $_anio,$clasificador, $GRUPO_BIEN,$CLASE_BIEN,$FAMILIA_BIEN));
          if($cuentas){
            foreach ($cuentas as $d) {
              $idCuentaMayor=127;
              $idSubCuenta=1774;      
              $cuentasId = \DB::select("EXEC pat.Sp_SEL_CuentaMayorSubCuenta ?,?",array($d->MAYOR,$d->SUB_CTA));
              if($cuentasId){
                 $idCuentaMayor=$cuentasId[0]->iPlanConMayorId;
                $idSubCuenta=$cuentasId[0]->iPlanConSubCueId;   
              }
                $cuentas_data[]=array(    
                  'CUENTA'=>$d->CUENTA,               
                  'MAYOR'=>$d->MAYOR,
                  'NOMBRE'=>$d->NOMBRE,
                  'NOMBRE_SUBCUENTA'=>$d->NOMBRE_SUBCUENTA,
                  'SUB_CTA'=>$d->SUB_CTA,
                  'iPlanConMayorId'=> $idCuentaMayor,
                  'iPlanConSubCueId'=>$idSubCuenta,
                );
              }

            }
          
          
          //recuperamos los id de las cuentas y sub cuentas
          
          //fin  
         /* $data = [         
                    'results' =>$cuentas_data                                             
                  ];*/

       return response()->json($cuentas_data);
        //  return $data;
       // return response()->"{\"result\":" .json($respuesta). ",\"count\":".$total."}";
    }




   

}
