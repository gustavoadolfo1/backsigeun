<?php

namespace App\Http\Controllers\Patrimonio;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Areas extends Controller
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
          $filter="";
          $filtro_all="ADN (";
          $value_filtro_cAreaDescripcioniLocalId="";
          $order="";
          if($request->order){$order=$request->order;}
          if($request->filter){
            $filter=count($request->filter[0]['predicates']);
            
            for($i=0;$i<count($request->filter[0]['predicates']);$i++){
              //  $filtro_all=$filtro_all.$request->filter[0]['predicates'][$i]['field']." ";
              if($request->filter[0]['predicates'][$i]['field']=="cAreaDescripcion"){
                 $value_filtro_cAreaDescripcioniLocalId=$request->filter[0]['predicates'][$i]['value'];
              }

            }

          }
          $datos = null;        
       
       // $respuesta = DB::select('EXECUTE tram.Sp_SEL_CiclosAcademicosMatricXcEstudCodUniv ?', $data);
         $datos = \DB::select("EXEC pat.Sp_SEL_Area ?,?,?,?",array( $skip,$top,$value_filtro_cAreaDescripcioniLocalId,$order));
     // print_r($datos)
         // $total=45;
          $total=\DB::select("EXEC pat.Sp_COUNT_Area ?",array($value_filtro_cAreaDescripcioniLocalId));
          $data = [       
                     'filter'=> $value_filtro_cAreaDescripcioniLocalId,              
                    'results' =>$datos,
                    'count' => $total[0]->iTotalRegistros
                                             
                  ];

       return response()->json($data);
        //  return $data;
       // return response()->"{\"result\":" .json($respuesta). ",\"count\":".$total."}";
    }  

     public function getCombo(){
       // $respuesta = DB::select('EXECUTE tram.Sp_SEL_CiclosAcademicosMatricXcEstudCodUniv ?', $data);
         $datos = \DB::select("EXEC pat.Sp_SEL_Combo_areas ");
  
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
                'iAreaId' => 'required',
                'cAreaDescripcion' => 'required',
                'iLocalId' => 'required'
            ], 
            [
                'iAreaId.required' => 'Hubo un problema al obtener el ID del Area.',
                'cAreaDescripcion.required' => 'Hubo un problema al obtener la descripcion del Local',
                'iLocalId.required' => 'Hubo un problema al obtener el ID del Local.',
            ]
        );

        $ip = $request->server->get('REMOTE_ADDR');
       /* $response = ['validated' => true, 'mensaje' => 'Se guardó el Local exitosamente.'];
            $codeResponse = 200;  */
        try {
            $queryResult = \DB::select("exec [pat].[Sp_INS_Area] ?,?,? ",array($request->cAreaDescripcion,$request->iLocalId,20648) );   
            $response = ['validated' => true, 'mensaje' => 'Se guardó el Local: '. $queryResult[0]->cAreaDescripcion.', exitosamente.', 'queryResult' => $queryResult[0] ];
            $codeResponse = 200;            
        } catch (\QueryException $e) {
            //return response()->json( $e );
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'code' => $e->getCode(), 'exception' => $e];
            $codeResponse = 500;
        }
        //NO CAPTURAN LOS EERROS SQL DB
        
        return response()->json( $response, $codeResponse );

    }

     public function modificar(Request $request, $id)
    {
            
       $this->validate(
            $request, 
            [              
              'cAreaDescripcion' => 'required',
               'iLocalId' => 'required'
            ], 
            [
              'cAreaDescripcion.required' => 'Hubo un problema al obtener la descripcion del Local',
               'iLocalId.required' => 'Hubo un problema al obtener el ID del Local.',
            ]
        );

        $ip = $request->server->get('REMOTE_ADDR');
       /* $response = ['validated' => true, 'mensaje' => 'Se guardó el Local exitosamente.'];
            $codeResponse = 200;  */
        try {
            $queryResult = \DB::select("exec [pat].[Sp_UPD_Area] ?, ?, ?,?", array($id, $request->cAreaDescripcion,$request->iLocalId,20648));   
            $response = ['validated' => true, 'mensaje' => 'Se Modifico el Local: '. $queryResult[0]->cAreaDescripcion.', exitosamente.', 'queryResult' => $queryResult[0] ];
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
        $data = \DB::select('exec pat.Sp_DEL_Area ?', array($id));
        if ($data[0]->eliminados > 0) {
            $response = [ 'validated' => true, 'mensaje' => 'Se eliminó el horario exitosamente.', 'eliminados' => $data[0]->eliminados ];
            $codeResponse = 200;
        } else {
            $response = [ 'validated' => true, 'mensaje' => 'El Area no se ha podido eliminar o no existe.', 'eliminados' => $data[0]->eliminados ];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }

   

}
