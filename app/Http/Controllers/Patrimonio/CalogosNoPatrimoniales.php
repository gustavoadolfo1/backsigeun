<?php

namespace App\Http\Controllers\Patrimonio;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CalogosNoPatrimoniales extends Controller
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
          $value_filtro_cCatalogoNoPatDescripcion="";
          $order="";
          if($request->order){$order=$request->order;}
          if($request->filter){                    
            for($i=0;$i<count($request->filter[0]['predicates']);$i++){              
              if($request->filter[0]['predicates'][$i]['field']=="cCatalogoNoPatDescripcion"){
                 $value_filtro_cCatalogoNoPatDescripcion=$request->filter[0]['predicates'][$i]['value'];
              }
            }

          }
          $datos = null;        
       
       // $respuesta = DB::select('EXECUTE tram.Sp_SEL_CiclosAcademicosMatricXcEstudCodUniv ?', $data);
         $datos = \DB::select("EXEC pat.Sp_SEL_catalogoNP ?,?,?,?",array( $skip,$top,$value_filtro_cCatalogoNoPatDescripcion,$order));
     // print_r($datos)
         // $total=45;
          $total=\DB::select("EXEC pat.Sp_COUNT_catalogoNP ?",array($value_filtro_cCatalogoNoPatDescripcion));
          $data = [       
                    'filter'=> $value_filtro_cCatalogoNoPatDescripcion,              
                    'results' =>$datos,
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
                'cCatalogoNoPatDescripcion' => 'required',
                 'cCatalogoNoPatCodigo' => 'required',
            ], 
            [
               
                'cCatalogoNoPatDescripcion.required' => 'Hubo un problema al obtener la descripcion ',
                  'cCatalogoNoPatCodigo.required' => 'Hubo un problema el codigo ',
            ]
        );

        $ip = $request->server->get('REMOTE_ADDR');
        try {
            $queryResult = \DB::select("exec [pat].[Sp_INS_catalogoNP] ?,?,? ",array($request->cCatalogoNoPatDescripcion,$request->cCatalogoNoPatCodigo,20648) );   
            $response = ['validated' => true, 'mensaje' => 'Se guardó  : '. $queryResult[0]->cCatalogoNoPatDescripcion.', exitosamente.', 'queryResult' => $queryResult[0] ];
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
                'iCatalogoNoPatId' => 'required',
                'cCatalogoNoPatDescripcion' => 'required',
                 'cCatalogoNoPatCodigo' => 'required',
            ], 
            [
                'iCatalogoNoPatId.required' => 'Hubo un problema al obtener EL ID ',
                'cCatalogoNoPatDescripcion.required' => 'Hubo un problema al obtener la descripcion ',
                  'cCatalogoNoPatCodigo.required' => 'Hubo un problema el codigo ',
            ]
        );

        $ip = $request->server->get('REMOTE_ADDR');
       /* $response = ['validated' => true, 'mensaje' => 'Se guardó el Local exitosamente.'];
            $codeResponse = 200;  */
        try {
            $queryResult = \DB::select("exec [pat].[Sp_UPD_catalogoNP] ?, ?, ?,?", array($id,$request->cCatalogoNoPatDescripcion,$request->cCatalogoNoPatCodigo,20648));   
            $response = ['validated' => true, 'mensaje' => 'Se Modifico   : '. $queryResult[0]->cCatalogoNoPatDescripcion.', exitosamente.', 'queryResult' => $queryResult[0] ];
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
            $data = \DB::select('exec pat.Sp_DEL_catalogoNP ?', array($id));
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
