<?php

namespace App\Http\Controllers\Patrimonio;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Oc extends Controller
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
          $_SEC_EJE=1230; //UNAM 
        //  $_anio=2019; //UNAM 
           $_anio=$request->anio;

          $order=$request->order;
          $value_filtro_cDocAdqNro="";
          $order="";
          //if($request->order){$order=$request->order;}
          if($request->filter){                    
            for($i=0;$i<count($request->filter[0]['predicates']);$i++){              
              if($request->filter[0]['predicates'][$i]['field']=="NRO_ORDEN"){
                 $value_filtro_cDocAdqNro=$request->filter[0]['predicates'][$i]['value'];
              }
            }

          }
          $datos = null;        
         $datos =  \DB::select("EXEC pat.SP_SIGEUN_PAT_SEL_OC ?,?,?,?,?",array( $skip,$top,$_SEC_EJE, $_anio,$value_filtro_cDocAdqNro));
          
     // print_r($datos)
         // $total=45;
          $total=\DB::select("EXEC pat.SP_SIGEUN_PAT_COUNT_OC ?,?,?",array($value_filtro_cDocAdqNro, $_anio,$_SEC_EJE));
          $data = [       
                    'filter'=> $value_filtro_cDocAdqNro,              
                    'results' =>$datos,
                    'count' => $total[0]->iTotalRegistros
                                             
                  ];

       return response()->json($data);
        //  return $data;
       // return response()->"{\"result\":" .json($respuesta). ",\"count\":".$total."}";
    }
}
