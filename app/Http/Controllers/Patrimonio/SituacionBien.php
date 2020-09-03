<?php

namespace App\Http\Controllers\Patrimonio;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SituacionBien extends Controller
{
    /**
     * 
     * 
     * @return \Illuminate\Http\JsonResponse
     */

   

     public function getCombo(){
       // $respuesta = DB::select('EXECUTE tram.Sp_SEL_CiclosAcademicosMatricXcEstudCodUniv ?', $data);
         $datos = \DB::select("EXEC pat.Sp_SEL_Combo_Situacion");
  
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
                'iBienId' => 'required',
                'iSituacionBienId' => 'required',
                'cSituacionesBienDocRef' => 'required',
                'cSituacionesBienCausal' => 'required',
            ], 
            [
               
                'iBienId.required' => 'Hubo un problema al obtener el bien ',
                'iSituacionBienId.required' => 'Hubo un problema al obtener el tipo de situacion ',
                'cSituacionesBienDocRef.required' => 'Hubo un problema al obtener documento',
                'cSituacionesBienCausal.required' => 'Hubo un problema al obtener la causa ',
            ]
        );
        //recorremos el objeto colores
        //y lo ttrasformamos en un xml

     
        $ip = $request->server->get('REMOTE_ADDR');
        try {
              $queryResult = \DB::select("exec [pat].[Sp_INS_MoverBien] ?, ?,?,?,?",array(
              $request->cSituacionesBienCausal,
              $request->cSituacionesBienDocRef,
              $request->iSituacionBienId,
              $request->iBienId,
              20648) );   
            $response = ['validated' => true, 'mensaje' => 'Se guardó  : '. $queryResult[0]->iSituacionesBienId.', exitosamente.', 'queryResult' => $queryResult[0] ];
            $codeResponse = 200;            
        } catch (\QueryException $e) {;
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'code' => $e->getCode(), 'exception' => $e];
            $codeResponse = 500;
        }  
      
       // echo $xml;      
       return response()->json( $response, $codeResponse );

 
}
 



   

}
