<?php

namespace App\Http\Controllers\Patrimonio;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Reportes extends Controller
{
    /**
     * 
     * 
     * @return \Illuminate\Http\JsonResponse
     */

    //public function getResult($skip,$top,$inlinecount,$format){
    public function ubicacionPorDependencia($iDepenId){///// busca los bienes por dependencia
       // $respuesta = DB::select('EXECUTE tram.Sp_SEL_CiclosAcademicosMatricXcEstudCodUniv ?', $data);
         $datos = \DB::select("EXEC pat.Sp_ReportBienesPorDependencia ?",array($iDepenId));
  
          $data = [         
                    'results' =>$datos                                             
                  ];

       return response()->json($datos);
        //  return $data;
       // return response()->"{\"result\":" .json($respuesta). ",\"count\":".$total."}";
    }
   
    public function ubicacionPorDepenSub($iDepenId,$iCentroCostoId){//
       // $respuesta = DB::select('EXECUTE tram.Sp_SEL_CiclosAcademicosMatricXcEstudCodUniv ?', $data);
         $datos = \DB::select("EXEC pat.Sp_ReportBienesPorSubDependencia ?,?",array($iDepenId,$iCentroCostoId));
  
          $data = [         
                    'results' =>$datos                                             
                  ];

       return response()->json($datos);
        //  return $data;
       // return response()->"{\"result\":" .json($respuesta). ",\"count\":".$total."}";
    }

    public function ubicacionPorDepenSubEmp($idCentroCostoEmpleado){
       // $respuesta = DB::select('EXECUTE tram.Sp_SEL_CiclosAcademicosMatricXcEstudCodUniv ?', $data);
         $datos = \DB::select("EXEC pat.Sp_ReportBienesPorCentroCosto ?",array($idCentroCostoEmpleado));
  
          $data = [         
                    'results' =>$datos                                             
                  ];

       return response()->json($datos);
        //  return $data;
       // return response()->"{\"result\":" .json($respuesta). ",\"count\":".$total."}";
    }

    public function getDataComboubicacionEmpleado($iDepenId,$iCentroCostoId){
       // $respuesta = DB::select('EXECUTE tram.Sp_SEL_CiclosAcademicosMatricXcEstudCodUniv ?', $data);
      if ($iCentroCostoId != 0) {
        $datos = \DB::select("EXEC pat.Sp_SEL_Combo_CentroCostoEmpleado_X_SubdependenciaAndroid ?,?",array($iDepenId,$iCentroCostoId));
  
          $data = [         
                    'results' =>$datos                                             
                  ];
      }else{
         $datos = \DB::select("EXEC pat.Sp_SEL_Combo_CentroCostoEmpleado_X_DependenciaAndroid ?",array($iDepenId));
  
          $data = [         
                    'results' =>$datos                                             
                  ];
      }
       return response()->json($datos);
        //  return $data;
       // return response()->"{\"result\":" .json($respuesta). ",\"count\":".$total."}";
    }


    public function ubicacionEmpleado(){
       // $respuesta = DB::select('EXECUTE tram.Sp_SEL_CiclosAcademicosMatricXcEstudCodUniv ?', $data);
         $datos = \DB::select("EXEC pat.Sp_SEL_Empelado ");
  
          $data = [         
                    'results' =>$datos                                             
                  ];

       return response()->json($datos);
        //  return $data;
       // return response()->"{\"result\":" .json($respuesta). ",\"count\":".$total."}";
    }

    public function getDataCentroCosto($iEmpleadoId){
       // $respuesta = DB::select('EXECUTE tram.Sp_SEL_CiclosAcademicosMatricXcEstudCodUniv ?', $data);
      $datos = \DB::select("EXEC pat.Sp_SEL_DependenciaSubDependenciaEmpleado ?",array($iEmpleadoId));
  		$data_=array();	
  		$datos_bienes=array();
          if($datos){
            	foreach ($datos as $d) {
              		$tipo=2;
              		$bienes=array();
                  $datos_bienes = \DB::select("EXEC pat.Sp_ReportBienesPorCentroCosto ?",array($d->idCentroCostoEmpleado));
                  if($datos_bienes){
                      foreach ($datos_bienes as $db) {
                          $bienes[]=array( 
                              'cBienCodigo' => $db->cBienCodigo,
                              'cBienDescripcion'   => $db->cBienDescripcion,
                              'cBienSerie'  => $db->cBienSerie,
                              'cBienDimension' => $db->cBienDimension,
                              'cEstadoBienAbre'  => $db->cEstadoBienAbre,
                              'cTipoDescripcion'  => $db->cTipoDescripcion,
                              'cModeloDescripcion'  => $db->cModeloDescripcion,
                              'cMarcaDescripcion'  => $db->cMarcaDescripcion,  
                              'iDespBienId'  => $db->iDespBienId,
                              'dDespBienFecha' => $db->dDespBienFecha,



                          );
                      }       
                  }

                  if($d->iCentroCostoId == ''){$tipo=1;}

                  $data_[]=array(                   
                      'idCentroCostoEmpleado'=>$d->idCentroCostoEmpleado,
                      'iDepenId' => $d->iDepenId,
                      'cDepenNombre' =>$d->cDepenNombre,
                      'iCentroCostoId' =>$d->iCentroCostoId,
                      'cCentroCostoNombre' =>$d->cCentroCostoNombre,  
                      'tipo' =>$tipo,      
                      'bienes'=>$bienes

                  );    
                  
              }	
          }

      

         /* $data = [         
                    'results' =>$data_,                                            
                  ];*/

       return response()->json($data_);
        //  return $data;
       // return response()->"{\"result\":" .json($respuesta). ",\"count\":".$total."}";
    }

    public function getBienNoDepreciable(){///// busca los bienes por dependencia
       // $respuesta = DB::select('EXECUTE tram.Sp_SEL_CiclosAcademicosMatricXcEstudCodUniv ?', $data);
         $datos = \DB::select("EXEC pat.Sp_ReportBienesNoDepreciables ");
  
          $data = [         
                    'results' =>$datos                                             
                  ];

       return response()->json($datos);
        //  return $data;
       // return response()->"{\"result\":" .json($respuesta). ",\"count\":".$total."}";
    }//no  implementado todavia
    public function getBienDepreciable(){///// busca los bienes por dependencia
       // $respuesta = DB::select('EXECUTE tram.Sp_SEL_CiclosAcademicosMatricXcEstudCodUniv ?', $data);
         $datos = \DB::select("EXEC pat.Sp_ReportBienesDepreciables ");
  
          $data = [         
                    'results' =>$datos                                             
                  ];

       return response()->json($datos);
        //  return $data;
       // return response()->"{\"result\":" .json($respuesta). ",\"count\":".$total."}";
    }
    public function getComboCuentaContable(){///// busca los bienes por dependencia
       // $respuesta = DB::select('EXECUTE tram.Sp_SEL_CiclosAcademicosMatricXcEstudCodUniv ?', $data);
         $datos = \DB::select("EXEC pat.Sp_SEL_Combo_CuentaMayor_SubCuenta ");
  
          $data = [         
                    'results' =>$datos                                             
                  ];

       return response()->json($datos);
        //  return $data;
       // return response()->"{\"result\":" .json($respuesta). ",\"count\":".$total."}";
    }
    public function getComboCuentaMayor(){///// busca los bienes por dependencia
       // $respuesta = DB::select('EXECUTE tram.Sp_SEL_CiclosAcademicosMatricXcEstudCodUniv ?', $data);
         $datos = \DB::select("EXEC pat.Sp_SEL_Combo_CuentaMayor ");
  
          $data = [         
                    'results' =>$datos                                             
                  ];

       return response()->json($datos);
        //  return $data;
       // return response()->"{\"result\":" .json($respuesta). ",\"count\":".$total."}";
    }
    public function getBienCuentaMayor($iCuentaContable){///// busca los bienes por dependencia
       // $respuesta = DB::select('EXECUTE tram.Sp_SEL_CiclosAcademicosMatricXcEstudCodUniv ?', $data);
         $datos = \DB::select("EXEC pat.Sp_ReportBienesPorCuentaContable ?",array($iCuentaContable));
  
          $data = [         
                    'results' =>$datos                                             
                  ];

       return response()->json($datos);
        //  return $data;
       // return response()->"{\"result\":" .json($respuesta). ",\"count\":".$total."}";
    }
}
