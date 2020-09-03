<?php

namespace App\Http\Controllers\Patrimonio;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Tipos extends Controller
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
          $value_filtro_cTipoDescripcion="";
          $value_filtro_cModeloDescripcion="";
          $value_filtro_cMarcaDescripcion="";

          $order="";
          if($request->order){$order=$request->order;}
          if($request->filter){
            $filter=count($request->filter[0]['predicates']);
            
            for($i=0;$i<count($request->filter[0]['predicates']);$i++){
              //  $filtro_all=$filtro_all.$request->filter[0]['predicates'][$i]['field']." ";
              if($request->filter[0]['predicates'][$i]['field']=="cTipoDescripcion"){
                 $value_filtro_cTipoDescripcion=$request->filter[0]['predicates'][$i]['value'];
              }
              if($request->filter[0]['predicates'][$i]['field']=="cModeloDescripcion"){               
                 $value_filtro_cModeloDescripcion=$request->filter[0]['predicates'][$i]['value'];
              }
              if($request->filter[0]['predicates'][$i]['field']=="cMarcaDescripcion"){               
                 $value_filtro_cMarcaDescripcion=$request->filter[0]['predicates'][$i]['value'];
              }

            }

          }
          $datos = null;        
       
       // $respuesta = DB::select('EXECUTE tram.Sp_SEL_CiclosAcademicosMatricXcEstudCodUniv ?', $data);
         $datos = \DB::select("EXEC pat.Sp_SEL_Tipo ?,?,?,?,?,?",array( $skip,$top,$value_filtro_cTipoDescripcion,$value_filtro_cModeloDescripcion,$value_filtro_cMarcaDescripcion,$order));
     // print_r($datos)
         // $total=45;
          $total=\DB::select("EXEC pat.Sp_COUNT_Tipo ?,?,?",array($value_filtro_cTipoDescripcion,$value_filtro_cModeloDescripcion,$value_filtro_cMarcaDescripcion));
          $data = [       
                     'filter'=> $value_filtro_cTipoDescripcion,              
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
                'cTipoDescripcion' => 'required',
                'iModeloId' => 'required'
            ], 
            [
                'cTipoDescripcion.required' => 'Hubo un problema al obtener la descripcion del tipo',
                'iModeloId.required' => 'Hubo un problema al obtener el ID de la modelo.',
            ]
        );

        $ip = $request->server->get('REMOTE_ADDR');
       /* $response = ['validated' => true, 'mensaje' => 'Se guardó el Local exitosamente.'];
            $codeResponse = 200;  */
        try {
            $queryResult = \DB::select("exec [pat].[Sp_INS_Tipo] ?,?,? ",array($request->cTipoDescripcion,$request->iModeloId,20648) );   
            $response = ['validated' => true, 'mensaje' => 'Se guardó el Tipo: '. $queryResult[0]->cTipoDescripcion.', exitosamente.', 'queryResult' => $queryResult[0] ];
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
               'iTipoId' => 'required',    
              'cTipoDescripcion' => 'required',
               'iMarcaId' => 'required'
            ], 
            [
              'iTipoId.required' => 'Hubo un problema al obtener el ID del modelo.',
              'cTipoDescripcion.required' => 'Hubo un problema al obtener la descripcion del modelo',
               'iMarcaId.required' => 'Hubo un problema al obtener el ID de la marca.',
            ]
        );

        $ip = $request->server->get('REMOTE_ADDR');
       /* $response = ['validated' => true, 'mensaje' => 'Se guardó el Local exitosamente.'];
            $codeResponse = 200;  */
        try {
            $queryResult = \DB::select("exec [pat].[Sp_UPD_Tipo] ?, ?, ?,?", array($id, $request->cTipoDescripcion,$request->iMarcaId,20648));   
            $response = ['validated' => true, 'mensaje' => 'Se Modifico el tipo: '. $queryResult[0]->cTipoDescripcion.', exitosamente.', 'queryResult' => $queryResult[0] ];
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
      try {
            $data = \DB::select('exec pat.Sp_DEL_Tipo ?', array($id));
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

   

}
