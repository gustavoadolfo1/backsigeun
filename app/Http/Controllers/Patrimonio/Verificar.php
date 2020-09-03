<?php

namespace App\Http\Controllers\Patrimonio;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Verificar extends Controller
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
              if($request->filter[0]['predicates'][$i]['field']=="cCodigoBien"){
                 $value_filtro_cDocAdqNro=$request->filter[0]['predicates'][$i]['value'];
              }
            }

          }
          $datos = null;    
         // Sp_SEL_VerificacionBien_X_SubDepenencia
          if($request->iCentroCostoId<>''){
            $datos = \DB::select("EXEC pat.Sp_SEL_VerificacionBien_X_SubDepenencia ?,?,?,?,?,?",array( $skip,$top,2019,$request->iCentroCostoId,$value_filtro_cDocAdqNro,$order));

           $total=\DB::select("EXEC pat.Sp_COUNT_VerificacionBien_X_SubDependencia ?,?,?",array(2019,$request->iCentroCostoId,$request->iCentroCostoId,$value_filtro_cDocAdqNro));

          }else{
            $datos = \DB::select("EXEC pat.Sp_SEL_VerificacionBien ?,?,?,?,?,?",array( $skip,$top,2019,$request->iDepenId,$value_filtro_cDocAdqNro,$order));

           $total=\DB::select("EXEC pat.Sp_COUNT_VerificacionBien ?,?,?",array(2019,$request->iDepenId,$request->iCentroCostoId,$value_filtro_cDocAdqNro));
          }
     
          
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

 public function getResultAndroid($iYearId,$iDepenId,$idCentroCostoEmpleado){
        $datos = \DB::select("EXEC pat.Sp_SEL_VerificacionBien_x_Dependencia_Empleado_Android ?,?,?",array($iYearId,$iDepenId,$idCentroCostoEmpleado)); 
         $data = [  'bienVerificado' =>$datos  ];
         return response()->json($data);

 }


 public function getResultBienesDesverificadosAndroid($iYearId,$iDepenId,$idCentroCostoEmpleado){
        $datos = \DB::select("EXEC pat.Sp_SEL_VerificacionBien_x_Dependencia_Empleado_Android_Bienes_Sin_Verificar ?,?,?",array($iYearId,$iDepenId,$idCentroCostoEmpleado)); 
         $data = [  'bienD' =>$datos  ];
         return response()->json($data);

 }


 //por sub dependencia

  public function getResult_x_sub_depemdemcoa_Android($iYearId,$iDepenId,$iCentroCostoId,$idCentroCostoEmpleado){
        $datos = \DB::select("EXEC pat.Sp_SEL_VerificacionBien_x_Dependencia_Empleado_x_sub_dependencia_Android ?,?,?,?",array($iYearId,$iDepenId,$iCentroCostoId,$idCentroCostoEmpleado)); 
         $data = [  'bienVerificado' =>$datos  ];
         return response()->json($data);

 }
 public function getResult_x_sub_depemdemcoa_BienesDesverificadosAndroid($iYearId,$iDepenId,$iCentroCostoId,$idCentroCostoEmpleado){
        $datos = \DB::select("EXEC pat.Sp_SEL_VerificacionBien_x_Dependencia_Empleado_x_sub_dependicia_Android_Bienes_Sin_Verificar ?,?,?,?",array($iYearId,$iDepenId,$iCentroCostoId,$idCentroCostoEmpleado)); 
         $data = [  'bienD' =>$datos  ];
         return response()->json($data);

 }

    public function guardar(Request $request )
    {
        /*$this->validate(
            $request, 
            [
                'iBienId' => 'required',               
                'iYearId' => 'required'

            ], 
            [
               
                'iBienId.required' => 'Hubo un problema al obtener el el Bien',                
                'iYearId.required' => 'Hubo un problema EL AÑO '
            ]
        );

  /*iBienId bigint,
  iYearId int,
  iEstadoBienId tinyint,  
  cVerificacionObs varchar(250),
  iCredId INTEGER*/

       // $ip = $request->server->get('REMOTE_ADDR');
        try {
            $queryResult = \DB::select("exec [pat].[Sp_INS_VerificarBien] ?,?,?,?,? ",array($request->iBienId,$request->iYearId,$request->iEstadoBienId,$request->cVerificacionObs,20648) );   
            $response = ['validated' => true, 'mensaje' => 'Se guardó  el : '. $queryResult[0]->iBienId.', exitosamente.', 'queryResult' => $queryResult[0] ];
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
