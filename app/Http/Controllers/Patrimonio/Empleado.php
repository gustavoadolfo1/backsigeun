<?php

namespace App\Http\Controllers\Patrimonio;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Empleado extends Controller
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
              if($request->filter[0]['predicates'][$i]['field']=="cEmpleadoDni"){
                 $value_filtro_cDocAdqNro=$request->filter[0]['predicates'][$i]['value'];
              }
            }

          }
          $datos = null;        
        // $datos = \DB::connection('sqlsrvSiga')->select("EXEC pat.Sp_SEL_Documento ?,?,?,?",array( $skip,$top,$value_filtro_cDocAdqNro,$order));
          $datos = \DB::select("EXEC pat.Sp_SEL_Empleado ?,?,?,?",array( $skip,$top,$value_filtro_cDocAdqNro,$order));
     // print_r($datos)
         // $total=45;
          $total=\DB::select("EXEC pat.Sp_COUNT_empleado ?",array($value_filtro_cDocAdqNro));
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
                'cEmpleadoApellidoP' => 'required',
               
                'cEmpleadoApellidoM' => 'required',
                'cEmpleadoNombre' => 'required',
                'cEmpleadoDni' => 'required',

            ], 
            [
               
                'cEmpleadoApellidoP.required' => 'Hubo un problema al obtener el apellido materno',
                
                'cEmpleadoApellidoM.required' => 'Hubo un problema al obtener  el apellido patarno ',
                'cEmpleadoNombre.required' => 'Hubo un problema al obtener el NOMBRE',
                'cEmpleadoDni.required' => 'Hubo un problema al obtener el DNI',
            ]
        );



        $ip = $request->server->get('REMOTE_ADDR');
        try {
            $queryResult = \DB::select("exec [pat].[Sp_INS_Empleado] ?,?,?,?,?,?,?,?,? ",array($request->cEmpleadoApellidoP,$request->cEmpleadoApellidoM,$request->cEmpleadoNombre,$request->cEmpleadoDireccion,$request->cEmpleadoTelefonos,$request->cEmpleadoEmail,$request->cEmpleadoDni,$request->iCargId,20648) );   
            $response = ['validated' => true, 'mensaje' => 'Se guardó  al empleado : '. $queryResult[0]->cEmpleadoNombre.', exitosamente.', 'queryResult' => $queryResult[0] ];
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
                'iEmpleadoId'=>'required',
                'cEmpleadoApellidoP' => 'required',
               
                'cEmpleadoApellidoM' => 'required',
                'cEmpleadoNombre' => 'required',
                'cEmpleadoDni' => 'required',

            ], 
            [   
                 'iEmpleadoId.required' => 'Hubo un problema ID del iEmpleadoId',
                
               
                'cEmpleadoApellidoP.required' => 'Hubo un problema al obtener el apellido materno',
                
                'cEmpleadoApellidoM.required' => 'Hubo un problema al obtener  el apellido patarno ',
                'cEmpleadoNombre.required' => 'Hubo un problema al obtener el NOMBRE',
                'cEmpleadoDni.required' => 'Hubo un problema al obtener el DNI',
            ]
        );






        $ip = $request->server->get('REMOTE_ADDR');
       /* $response = ['validated' => true, 'mensaje' => 'Se guardó el Local exitosamente.'];
            $codeResponse = 200;  */
        try {
             $queryResult = \DB::select("exec [pat].[Sp_UPD_Empleado] ?,?,?,?,?,?,?,?,?,? ",array($id,$request->cEmpleadoApellidoP,$request->cEmpleadoApellidoM,$request->cEmpleadoNombre,$request->cEmpleadoDireccion,$request->cEmpleadoTelefonos,$request->cEmpleadoEmail,$request->cEmpleadoDni,$request->iCargId,20648) );  
            $response = ['validated' => true, 'mensaje' => 'Se Modifico  el empleado : '. $queryResult[0]->cEmpleadoNombre.', exitosamente.', 'queryResult' => $queryResult[0] ];
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
            $data = \DB::select('exec pat.Sp_DEL_Empleado ?', array($id));
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
