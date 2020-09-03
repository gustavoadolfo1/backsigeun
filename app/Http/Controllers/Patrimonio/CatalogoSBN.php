<?php

namespace App\Http\Controllers\Patrimonio;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CatalogoSBN extends Controller
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
          $value_filtro_cCatSbnDescripcion="";
          $value_filtro_cCatSbnCodigo="";
          $order="";
          if($request->order){$order=$request->order;}
          if($request->filter){
            $filter=count($request->filter[0]['predicates']);
            
            for($i=0;$i<count($request->filter[0]['predicates']);$i++){
              //  $filtro_all=$filtro_all.$request->filter[0]['predicates'][$i]['field']." ";
              if($request->filter[0]['predicates'][$i]['field']=="cCatSbnDescripcion"){
                 $value_filtro_cCatSbnDescripcion=$request->filter[0]['predicates'][$i]['value'];
              }
               if($request->filter[0]['predicates'][$i]['field']=="cCatSbnCodigo"){               
                 $value_filtro_cCatSbnCodigo=$request->filter[0]['predicates'][$i]['value'];
              }

            }

          }
          $datos = null;        
       
       // $respuesta = DB::select('EXECUTE tram.Sp_SEL_CiclosAcademicosMatricXcEstudCodUniv ?', $data);
         $datos = \DB::select("EXEC pat.Sp_SEL_CatalogoSBN ?,?,?,?,?",array( $skip,$top,$value_filtro_cCatSbnDescripcion,$value_filtro_cCatSbnCodigo,$order));
     // print_r($datos)
         // $total=45;
          $total=\DB::select("EXEC pat.Sp_COUNT_CatalogoSBN ?,?",array($value_filtro_cCatSbnDescripcion,$value_filtro_cCatSbnCodigo));
          $data = [       
                     'filter'=> $value_filtro_cCatSbnDescripcion,              
                    'results' =>$datos,
                    'count' => $total[0]->iTotalRegistros
                                             
                  ];

       return response()->json($data);
        //  return $data;
       // return response()->"{\"result\":" .json($respuesta). ",\"count\":".$total."}";
    }  
    public function guardar(Request $request)
    {
        $this->validate(
            $request, 
            [
                'cCatSbnCodigo' => 'required',
                'cCatSbnDescripcion' => 'required',
                'cCatSbnResolucion' => 'required',
                'bCatSbnEstado' => 'required',
                'iGrupoClaseGenId' => 'required'


            ], 
            [
                'cCatSbnCodigo.required' => 'Hubo un problema al obtener el Codigo del catalogo.',
                'cCatSbnDescripcion.required' => 'Hubo un problema al obtener la descripcion del catalogo',
                'cCatSbnResolucion.required' => 'Hubo un problema al obtener la descripcion del catalogo.',
                'bCatSbnEstado.required' => 'Hubo un problema al obtener el bCatSbnEstado del catalogo.',
                'iGrupoClaseGenId.required' => 'Hubo un problema al obtener el ID del Grupo Clase.',
            ]
        );

        $ip = $request->server->get('REMOTE_ADDR');
       /* $response = ['validated' => true, 'mensaje' => 'Se guardó el Local exitosamente.'];
            $codeResponse = 200;  */
        try {
            $queryResult = \DB::select("exec [pat].[Sp_INS_CatalogoSBN] ?,?,?,?,?,? ",array($request->cCatSbnDescripcion,$request->cCatSbnCodigo,$request->cCatSbnResolucion,$request->bCatSbnEstado,$request->iGrupoClaseGenId,$request->iAreaId,20648) );   
            $response = ['validated' => true, 'mensaje' => 'Se guardó el Local: '. $queryResult[0]->cCatSbnDescripcion.', exitosamente.', 'queryResult' => $queryResult[0] ];
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
                'iCatSbnId' => 'required',
                'cCatSbnCodigo' => 'required',
                'cCatSbnDescripcion' => 'required',
                'cCatSbnResolucion' => 'required',
                'bCatSbnEstado' => 'required',
                'iGrupoClaseGenId' => 'required'


            ], 
            [
                'iCatSbnId.required' => 'Hubo un problema al obtener el ID  del catalogo.',
                'cCatSbnCodigo.required' => 'Hubo un problema al obtener el Codigo del catalogo.',
                'cCatSbnDescripcion.required' => 'Hubo un problema al obtener la descripcion del catalogo',
                'cCatSbnResolucion.required' => 'Hubo un problema al obtener la descripcion del catalogo.',
                'bCatSbnEstado.required' => 'Hubo un problema al obtener el bCatSbnEstado del catalogo.',
                'iGrupoClaseGenId.required' => 'Hubo un problema al obtener el ID del Grupo Clase.',
            ]
        );

        $ip = $request->server->get('REMOTE_ADDR');
       /* $response = ['validated' => true, 'mensaje' => 'Se guardó el Local exitosamente.'];
            $codeResponse = 200;  */
        try {
            $queryResult = \DB::select("exec [pat].[Sp_UPD_CatalogoSBN] ?, ?, ?,?,?,?,?", array($id, $request->cCatSbnCodigo,$request->cCatSbnDescripcion,$request->cCatSbnResolucion,$request->bCatSbnEstado,$request->iGrupoClaseGenId,20648));   
            $response = ['validated' => true, 'mensaje' => 'Se Modifico el catalogo: '. $queryResult[0]->cCatSbnDescripcion.', exitosamente.', 'queryResult' => $queryResult[0] ];
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
            $data = \DB::select('exec pat.Sp_DEL_CatalogoSBN ?', array($id));
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
