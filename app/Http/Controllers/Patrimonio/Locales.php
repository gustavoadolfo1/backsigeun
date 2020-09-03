<?php

namespace App\Http\Controllers\Patrimonio;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Locales extends Controller
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
          $value_filtro_cLocalDescripcioniLocalId="";
          $order="";
          if($request->order){$order=$request->order;}
          if($request->filter){
            $filter=count($request->filter[0]['predicates']);
            
            for($i=0;$i<count($request->filter[0]['predicates']);$i++){
              //  $filtro_all=$filtro_all.$request->filter[0]['predicates'][$i]['field']." ";
              if($request->filter[0]['predicates'][$i]['field']=="cLocalDescripcion"){
                 $value_filtro_cLocalDescripcioniLocalId=$request->filter[0]['predicates'][$i]['value'];
              }

            }

          }
          $datos = null;        
       
       // $respuesta = DB::select('EXECUTE tram.Sp_SEL_CiclosAcademicosMatricXcEstudCodUniv ?', $data);
         $datos = \DB::select("EXEC pat.Sp_SEL_locales ?,?,?,?",array( $skip,$top,$value_filtro_cLocalDescripcioniLocalId,$order));
     // print_r($datos)
         // $total=45;
          $total=\DB::select("EXEC pat.Sp_COUNT_Local ?",array($value_filtro_cLocalDescripcioniLocalId));
          $data = [       
                     'filter'=> $value_filtro_cLocalDescripcioniLocalId,              
                    'results' =>$datos,
                    'count' => $total[0]->iTotalRegistros
                                             
                  ];

       return response()->json($data);
        //  return $data;
       // return response()->"{\"result\":" .json($respuesta). ",\"count\":".$total."}";
    }

     public function getCombo(){
       // $respuesta = DB::select('EXECUTE tram.Sp_SEL_CiclosAcademicosMatricXcEstudCodUniv ?', $data);
         $datos = \DB::select("EXEC pat.Sp_SEL_Combo_locales ");
  
          $data = [         
                    'results' =>$datos                                             
                  ];

       return response()->json($datos);
        //  return $data;
       // return response()->"{\"result\":" .json($respuesta). ",\"count\":".$total."}";
    }

    public function verificarLogueo($moduloId)
    {
        try {
            $credencial = auth()->user();

            $modulos = \DB::select('exec [seg].[Sp_SEL_modulos_credencial] ?', array( $credencial->cCredUsuario ));

            foreach ($modulos as $modulo) {
                if ($modulo->iModuloId == $moduloId) {
                    $response = [ 'success' => true, 'access' => true ];
                    break;
                }
                else {
                    $response = [ 'success' => true, 'access' => false ];
                }
            }

        } catch (\Exception $th) {
            $response = [ 'success' => false, 'access' => false, 'throw' => $th ];
        }

        return response()->json( $response );
        
    }


    public function guardarLocal(Request $request)
    {
        $this->validate(
            $request, 
            [
                'iLocalId' => 'required',
                'cLocalDescripcion' => 'required',
            ], 
            [
                'iLocalId.required' => 'Hubo un problema al obtener el ID del Local.',
                'cLocalDescripcion.required' => 'Hubo un problema al obtener la descripcion del Local',
            ]
        );

        $ip = $request->server->get('REMOTE_ADDR');
       /* $response = ['validated' => true, 'mensaje' => 'Se guardó el Local exitosamente.'];
            $codeResponse = 200;  */
        try {
            $queryResult = \DB::select("exec [pat].[Sp_INS_Local] ?,? ",array($request->cLocalDescripcion,20648) );   
            $response = ['validated' => true, 'mensaje' => 'Se guardó el Local: '. $queryResult[0]->cLocalDescripcion.', exitosamente.', 'queryResult' => $queryResult[0] ];
            $codeResponse = 200;            
        } catch (\QueryException $e) {
            //return response()->json( $e );
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'code' => $e->getCode(), 'exception' => $e];
            $codeResponse = 500;
        }
        //NO CAPTURAN LOS EERROS SQL DB
        
        return response()->json( $response, $codeResponse );

    }

     public function modificarLocal(Request $request, $idLocal)
    {
            
       $this->validate(
            $request, 
            [              
              'cLocalDescripcion' => 'required',
            ], 
            [
              'cLocalDescripcion.required' => 'Hubo un problema al obtener la descripcion del Local',
            ]
        );

        $ip = $request->server->get('REMOTE_ADDR');
       /* $response = ['validated' => true, 'mensaje' => 'Se guardó el Local exitosamente.'];
            $codeResponse = 200;  */
        try {
            $queryResult = \DB::select("exec [pat].[Sp_UPD_Local] ?, ?, ?", array($idLocal, $request->cLocalDescripcion,20648));   
            $response = ['validated' => true, 'mensaje' => 'Se Modifico el Local: '. $queryResult[0]->cLocalDescripcion.', exitosamente.', 'queryResult' => $queryResult[0] ];
            $codeResponse = 200;            
        } catch (\QueryException $e) {
            //return response()->json( $e );
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'code' => $e->getCode(), 'exception' => $e];
            $codeResponse = 500;
        }
        //NO CAPTURAN LOS EERROS SQL DB
        
        return response()->json( $response, $codeResponse );

    }

    public function eliminarLocal($id)
    {
        $data = \DB::select('exec pat.Sp_DEL_Local ?', array($id));
        if ($data[0]->eliminados > 0) {
            $response = [ 'validated' => true, 'mensaje' => 'Se eliminó el horario exitosamente.', 'eliminados' => $data[0]->eliminados ];
            $codeResponse = 200;
        } else {
            $response = [ 'validated' => true, 'mensaje' => 'El horario no se ha podido eliminar o no existe.', 'eliminados' => $data[0]->eliminados ];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }

   

}
