<?php

namespace App\Http\Controllers\Patrimonio;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CentroCostoPat extends Controller
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
              if($request->filter[0]['predicates'][$i]['field']=="cCentroCostoNombre"){
                 $value_filtro_cDocAdqNro=$request->filter[0]['predicates'][$i]['value'];
              }
            }

          }
          $datos = null;        
        // $datos = \DB::connection('sqlsrvSiga')->select("EXEC pat.Sp_SEL_Documento ?,?,?,?",array( $skip,$top,$value_filtro_cDocAdqNro,$order));
          $datos = \DB::select("EXEC pat.Sp_SEL_CentroCosto ?,?,?,?",array( $skip,$top,$value_filtro_cDocAdqNro,$order));
     // print_r($datos)
         // $total=45;
          $datos_centros_costo = null;  

           foreach ($datos as $d) {
               $e=true;
              if($d->cCentroCostoEstado==0){ $e=false;}

              $datos_centros_costo[]=array(    
                    'RowNumber'=>$d->RowNumber,
                     'iCentroCostoId'=>$d->iCentroCostoId,      
                    'cCentroCostoNombre'=>$d->cCentroCostoNombre,                

                    'cCentroCostoAbre'=>$d->cCentroCostoAbre,
                    'cCentroCostoEstado' =>$e,
                    'cCentroCostoPadre'=>$d->cCentroCostoPadre,
                    'iDepenId' =>$d->iDepenId,
                    'cDepenNombre'=>$d->cDepenNombre,                           
                );
           }
         
         


          $total=\DB::select("EXEC pat.Sp_COUNT_CentroCosto ?",array($value_filtro_cDocAdqNro));
          $data = [       
                    'filter'=> $value_filtro_cDocAdqNro,              
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

    
    public function getComboAndroid($id){
       // $respuesta = DB::select('EXECUTE tram.Sp_SEL_CiclosAcademicosMatricXcEstudCodUniv ?', $data);
         $datos = \DB::select("EXEC pat.Sp_SEL_Combo_CentroCostoAndroid ?",array( $id));   



         $datos_subD= null; 
         $datos_subD[]=array(    
                    'iCentroCostoId'=>0,
                    'cCentroCostoNombre'=>'',
                    'iDepenId'=>0,

                  );
           foreach ($datos as $d) {
            $datos_subD[]=array(    
                    'iCentroCostoId'=>$d->iCentroCostoId,
                    'cCentroCostoNombre'=>$d->cCentroCostoNombre,
                    'iDepenId'=>$d->iDepenId

                  );
         }

         $data = [  'sundepende' =>$datos_subD  ];
         return response()->json($data);
    }

 


    public function guardar(Request $request)
    {
        $this->validate(
            $request, 
            [
                'cCentroCostoNombre' => 'required',
               
                'iDepenId' => 'required',

            ], 
            [
               
                'cCentroCostoNombre.required' => 'Hubo un problema el nombre',                
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
            $queryResult = \DB::select("exec [pat].[Sp_INS_CentroCosto] ?,?,?,?,?,?",array($request->cCentroCostoNombre,$request->cCentroCostoAbre,$request->cCentroCostoPadre,$request->iDepenId,$request->cCentroCostoEstado,20648) );   
            $response = ['validated' => true, 'mensaje' => 'Se guardó  el centro costo : '. $queryResult[0]->cCentroCostoNombre.', exitosamente.', 'queryResult' => $queryResult[0] ];
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
                'iCentroCostoId' => 'required',
                'cCentroCostoNombre' => 'required',
               
                'iDepenId' => 'required',

            ], 
            [
                 'iCentroCostoId.required' => 'Hubo un problema el ID',   
               
                'cCentroCostoNombre.required' => 'Hubo un problema el nombre',                
                'iDepenId.required' => 'Hubo un problema la dependencia'
            ]
        );







        $ip = $request->server->get('REMOTE_ADDR');
       /* $response = ['validated' => true, 'mensaje' => 'Se guardó el Local exitosamente.'];
            $codeResponse = 200;  */
        try {
             $queryResult = \DB::select("exec [pat].[Sp_UPD_CentroCosto] ?,?,?,?,?,?,? ",array($id,$request->cCentroCostoNombre,$request->cCentroCostoAbre,$request->cCentroCostoPadre,$request->iDepenId,$request->cCentroCostoEstado,20648) );  
            $response = ['validated' => true, 'mensaje' => 'Se Modifico  el centro de costo : '. $queryResult[0]->cCentroCostoNombre.', exitosamente.', 'queryResult' => $queryResult[0] ];
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
            $data = \DB::select('exec pat.Sp_DEL_CentroCosto ?', array($id));
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
