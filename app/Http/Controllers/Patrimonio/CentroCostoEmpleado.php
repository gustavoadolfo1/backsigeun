<?php

namespace App\Http\Controllers\Patrimonio;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CentroCostoEmpleado extends Controller
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
          //filtro
          $value_filtro_cEmpleadoDni="";
          $value_filtro_cDepenNombre="";
          $value_filtro_cCentroCostoNombre="";    
          $value_filtro_empleado="";          
          //fin filtro
          $order="";
          if($request->order){$order=$request->order;}
          if($request->filter){                    
            for($i=0;$i<count($request->filter[0]['predicates']);$i++){   
               for($i=0;$i<count($request->filter[0]['predicates']);$i++){    
                  switch ($request->filter[0]['predicates'][$i]['field']) {
                    case 'cEmpleadoDni':
                     $value_filtro_cEmpleadoDni=$request->filter[0]['predicates'][$i]['value'];  
                    break;
                    case 'cDepenNombre':
                     $value_filtro_cDepenNombre=$request->filter[0]['predicates'][$i]['value'];  
                    break;
                    case 'empleado':
                     $value_filtro_empleado=$request->filter[0]['predicates'][$i]['value'];  
                    break;
                 }
              }
            }

          }
          $datos = null;        
        // $datos = \DB::connection('sqlsrvSiga')->select("EXEC pat.Sp_SEL_Documento ?,?,?,?",array( $skip,$top,$value_filtro_cDocAdqNro,$order));
          $datos = \DB::select("EXEC pat.Sp_SEL_CentroCostoEmpleado ?,?,?,?,?,?",array( $skip,$top,$value_filtro_cEmpleadoDni,$value_filtro_cDepenNombre,$value_filtro_empleado,$order));
     // print_r($datos)
         // $total=45;
          $datos_centros_costo = null;  

           foreach ($datos as $d) {
               $e=true;
              if($d->bCentroCostoEmpleadoEstado==0){ $e=false;}

              $datos_centros_costo[]=array(    
                    'RowNumber'=>$d->RowNumber,
                     'idCentroCostoEmpleado'=>$d->idCentroCostoEmpleado,      
                    'iCentroCostoId'=>$d->iCentroCostoId,                

                    'iEmpleadoId'=>$d->iEmpleadoId,
                    'bCentroCostoEmpleadoEstado' =>$e,
                    'iDepenId'=>$d->iDepenId,
                    'cCentroCostoNombre' =>$d->cCentroCostoNombre,
                    'cDepenNombre'=>$d->cDepenNombre,  
                    'cEmpleadoDni'=>$d->cEmpleadoDni, 
                    'empleado'=>$d->empleado,                          
                );
           }
         
         


          $total=\DB::select("EXEC pat.Sp_COUNT_CentroCostoEmpleado ?,?,?",array($value_filtro_cEmpleadoDni,$value_filtro_cDepenNombre,$value_filtro_empleado));
          $data = [       
                    'filter'=> $value_filtro_cCentroCostoNombre,              
                    'results' =>$datos_centros_costo,
                    'count' => $total[0]->iTotalRegistros
                                             
                  ];





       return response()->json($data);
        //  return $data;
       // return response()->"{\"result\":" .json($respuesta). ",\"count\":".$total."}";
    }

     public function getCombo(){
       // $respuesta = DB::select('EXECUTE tram.Sp_SEL_CiclosAcademicosMatricXcEstudCodUniv ?', $data);
         $datos = \DB::select("EXEC pat.Sp_SEL_Combo_CentroCosto");
  
          $data = [         
                    'results' =>$datos                                             
                  ];

       return response()->json($datos);
        //  return $data;
       // return response()->"{\"result\":" .json($respuesta). ",\"count\":".$total."}";
    }


 
 public function getCombo_x_Dependencia_Android($id){
       // $respuesta = DB::select('EXECUTE tram.Sp_SEL_CiclosAcademicosMatricXcEstudCodUniv ?', $data);
         $datos = \DB::select("EXEC pat.Sp_SEL_Combo_CentroCostoEmpleado_X_DependenciaAndroid ?",array( $id));   
         $data = [  'empleado' =>$datos  ];
         return response()->json($data);
    }
 public function getCombo_x_Subdependencia_Android($id,$idSub){
       // $respuesta = DB::select('EXECUTE tram.Sp_SEL_CiclosAcademicosMatricXcEstudCodUniv ?', $data);
         $datos = \DB::select("EXEC pat.Sp_SEL_Combo_CentroCostoEmpleado_X_SubdependenciaAndroid ?,?",array( $id,$idSub));   
         $data = [  'empleado' =>$datos  ];
         return response()->json($data);
    }



    public function guardar(Request $request)
    {

      /*iCentroCostoId int,
  iEmpleadoId int,
  bCentroCostoEmpleadoEstado bit,
  iDepenId int,
  iCredId INTEGER*/
        $this->validate(
            $request, 
            [
                'iEmpleadoId' => 'required',
               
                'iDepenId' => 'required',

            ], 
            [
               
                'iEmpleadoId.required' => 'Hubo un problema el empleado',                
                'iDepenId.required' => 'Hubo un problema la dependencia'
            ]
        );

/*cCentroCostoNombre nvarchar (250),
  cCentroCostoAbre varchar(50),
  cCentroCostoPadre int,  
  iDepenId int,
  @_iCredId INTEGER*/



        $ip = $request->server->get('REMOTE_ADDR');
        try {
            $queryResult = \DB::select("exec [pat].[Sp_INS_CentroCostoEmpleado] ?,?,?,?,?",array($request->iCentroCostoId,$request->iEmpleadoId,$request->bCentroCostoEmpleadoEstado,$request->iDepenId,20648) );   
            $response = ['validated' => true, 'mensaje' => 'Se guardó   : '. $queryResult[0]->idCentroCostoEmpleado.', exitosamente.', 'queryResult' => $queryResult[0] ];
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
                'idCentroCostoEmpleado'=> 'required',
                'iEmpleadoId' => 'required',
               
                'iDepenId' => 'required',

            ], 
            [
               
                'idCentroCostoEmpleado.required' => 'Hubo un problema el ID',      
                'iEmpleadoId.required' => 'Hubo un problema el empleado',                
                'iDepenId.required' => 'Hubo un problema la dependencia'
            ]
        );







        $ip = $request->server->get('REMOTE_ADDR');
       /* $response = ['validated' => true, 'mensaje' => 'Se guardó el Local exitosamente.'];
            $codeResponse = 200;  */
        try {
             $queryResult = \DB::select("exec [pat].[Sp_UPD_CentroCostoEmpleado] ?,?,?,?,?,? ",array($id,$request->iCentroCostoId,$request->iEmpleadoId,$request->bCentroCostoEmpleadoEstado,$request->iDepenId,20648) );  
            $response = ['validated' => true, 'mensaje' => 'Se Modifico   : '. $queryResult[0]->idCentroCostoEmpleado.', exitosamente.', 'queryResult' => $queryResult[0] ];
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
            $data = \DB::select('exec pat.Sp_DEL_CentroCostoEmpleado ?', array($id));
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
