<?php

namespace App\Http\Controllers\Patrimonio;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DocVerificacionBienes extends Controller
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
              if($request->filter[0]['predicates'][$i]['field']=="iYearId"){
                 $value_filtro_cDocAdqNro=$request->filter[0]['predicates'][$i]['value'];
              }
            }

          }
          $datos = null;        
        // $datos = \DB::connection('sqlsrvSiga')->select("EXEC pat.Sp_SEL_Documento ?,?,?,?",array( $skip,$top,$value_filtro_cDocAdqNro,$order));
          $datos = \DB::select("EXEC pat.Sp_SEL_DocVerificacion ?,?,?,?",array( $skip,$top,$value_filtro_cDocAdqNro,$order));
     // print_r($datos)
         // $total=45;
          $total=\DB::select("EXEC pat.Sp_COUNT_DocVerificacion ?",array($value_filtro_cDocAdqNro));
          $data = [       
                    'filter'=> $value_filtro_cDocAdqNro,              
                    'results' =>$datos,
                    'count' => $total[0]->iTotalRegistros
                                             
                  ];

       return response()->json($data);
        //  return $data;
       // return response()->"{\"result\":" .json($respuesta). ",\"count\":".$total."}";
    }

     public function getCombo(){
        $datos = \DB::select("EXEC pat.Sp_SEL_Combo_DocVerificacion");
  
         $data = [  'DocVerificacion' =>$datos  ];
           // $datos['DocVerificacion']=array();

            

                 

       return response()->json($data);


     ///  echo  json_encode($response);
        //  return $data;
       // return response()->"{\"result\":" .json($respuesta). ",\"count\":".$total."}";
    }

 


    public function guardar(Request $request)
    {
        $this->validate(
            $request, 
            [
                'iYearId' => 'required',               
                'dDocVerBienFechaInicio' => 'required',
                'dDocVerBienFechaFin' => 'required'

            ], 
            [
               
                'iYearId.required' => 'Hubo un problema al obtener el año',                
                'dDocVerBienFechaInicio.required' => 'Hubo un problema la fecha de inicio ',
                'dDocVerBienFechaFin.required' => 'Hubo un problema la fecha fin'
            ]
        );



        $ip = $request->server->get('REMOTE_ADDR');
        try {
            $queryResult = \DB::select("exec [pat].[Sp_INS_DocVerificacion] ?,?,?,?,?,?,? ",array($request->iYearId,$request->cDocVerBienDocRef,$request->dDocVerBienFechaInicio,$request->dDocVerBienFechaFin,$request->dDocVerBienEstado,$request->cDocVerBienObs,20648) );   
            $response = ['validated' => true, 'mensaje' => 'Se guardó  el : '. $queryResult[0]->iYearId.', exitosamente.', 'queryResult' => $queryResult[0] ];
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
                'iYearId' => 'required',               
                'dDocVerBienFechaInicio' => 'required',
                'dDocVerBienFechaFin' => 'required'

            ], 
            [
               
                'iYearId.required' => 'Hubo un problema al obtener el año',                
                'dDocVerBienFechaInicio.required' => 'Hubo un problema la fecha de inicio ',
                'dDocVerBienFechaFin.required' => 'Hubo un problema la fecha fin'
            ]
        );





        $ip = $request->server->get('REMOTE_ADDR');
       /* $response = ['validated' => true, 'mensaje' => 'Se guardó el Local exitosamente.'];
            $codeResponse = 200;  */
        try {
             $queryResult = \DB::select("exec [pat].[Sp_UPD_DocVerificacion] ?,?,?,?,?,?",array($request->iYearId,$request->cDocVerBienDocRef,$request->dDocVerBienFechaInicio,$request->dDocVerBienFechaFin,$request->cDocVerBienObs,20648) );  
            $response = ['validated' => true, 'mensaje' => 'Se Modifico   : '. $queryResult[0]->iYearId.', exitosamente.', 'queryResult' => $queryResult[0] ];
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
        $data = \DB::select('exec pat.Sp_DEL_Empleado ?', array($id));
        if ($data[0]->eliminados > 0) {
            $response = [ 'validated' => true, 'mensaje' => 'Se eliminó el horario exitosamente.', 'eliminados' => $data[0]->eliminados ];
            $codeResponse = 200;
        } else {
            $response = [ 'validated' => true, 'mensaje' => 'El empleado no se ha podido eliminar o no existe.', 'eliminados' => $data[0]->eliminados ];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }

   

}
