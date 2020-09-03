<?php

namespace App\Http\Controllers\Patrimonio;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GruposClasesGenericoSBN extends Controller
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
          $value_filtro_cClaseGenDescripcion="";
          $order="";
          if($request->order){$order=$request->order;}
          if($request->filter){                    
            for($i=0;$i<count($request->filter[0]['predicates']);$i++){              
              if($request->filter[0]['predicates'][$i]['field']=="cClaseGrupoDescripcion"){
                 $value_filtro_cClaseGenDescripcion=$request->filter[0]['predicates'][$i]['value'];
              }
            }

          }
          $datos = null;        
       
       // $respuesta = DB::select('EXECUTE tram.Sp_SEL_CiclosAcademicosMatricXcEstudCodUniv ?', $data);
         $datos = \DB::select("EXEC pat.Sp_SEL_Grupo_Clase_generico ?,?,?,?",array( $skip,$top,$value_filtro_cClaseGenDescripcion,$order));
     // print_r($datos)
         // $total=45;
          $total=\DB::select("EXEC pat.Sp_COUNT_Grupo_Clase_generico ?",array($value_filtro_cClaseGenDescripcion));
          $data = [       
                    'filter'=> $value_filtro_cClaseGenDescripcion,              
                    'results' =>$datos,
                    'count' => $total[0]->iTotalRegistros
                                             
                  ];

       return response()->json($data);
        //  return $data;
       // return response()->"{\"result\":" .json($respuesta). ",\"count\":".$total."}";
    }

     public function getCombo(){
       // $respuesta = DB::select('EXECUTE tram.Sp_SEL_CiclosAcademicosMatricXcEstudCodUniv ?', $data);
         $datos = \DB::select("EXEC pat.Sp_SEL_Combo_Grupo_Clase_generico ");
  
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



    public function guardar(Request $request)
    {
        $this->validate(
            $request, 
            [
                'iGrupoGenId' => 'required',
                'iClaseGenId' => 'required',
            ], 
            [
                'iGrupoGenId.required' => 'Hubo un problema al obtener el ID del grup generico.',
                'iClaseGenId.required' => 'Hubo un problema al obtener el código  del clase genérico',
            ]
        );

        $ip = $request->server->get('REMOTE_ADDR');
       /* $response = ['validated' => true, 'mensaje' => 'Se guardó el Local exitosamente.'];
            $codeResponse = 200;  */
        try {
            $queryResult = \DB::select("exec [pat].[Sp_INS_Grupo_Clase_generico] ?,?,? ",array($request->iGrupoGenId,$request->iClaseGenId,20648) );   
            $response = ['validated' => true, 'mensaje' => 'Se guardó el el grupo-clase genérico: '. $queryResult[0]->iGrupoClaseDescripcion.', exitosamente.', 'queryResult' => $queryResult[0] ];
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
                'iGrupoClaseGenId' => 'required',
                'iGrupoGenId' => 'required',
                'iClaseGenId' => 'required',
            ], 
            [
                'iGrupoClaseGenId.required' => 'Hubo un problema al obtener el ID del grupo-clase generico.',
                'iGrupoGenId.required' => 'Hubo un problema al obtener el ID grupo genérico',
                'iClaseGenId.required' => 'Hubo un problema al obtener el ID clase genérico',
            ]
        );

        $ip = $request->server->get('REMOTE_ADDR');
       /* $response = ['validated' => true, 'mensaje' => 'Se guardó el Local exitosamente.'];
            $codeResponse = 200;  */
        try {
            $queryResult = \DB::select("exec [pat].[Sp_UPD_Grupo_Clase_generico] ?, ?, ?,?", array($id,$request->iGrupoGenId ,$request->iClaseGenId,20648));   
            $response = ['validated' => true, 'mensaje' => 'Se Modifico el grupo genérico: '. $queryResult[0]->iGrupoClaseDescripcion.', exitosamente.', 'queryResult' => $queryResult[0] ];
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
            $data = \DB::select('exec pat.Sp_DEL_Grupo_Clase_generico ?', array($id));
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
