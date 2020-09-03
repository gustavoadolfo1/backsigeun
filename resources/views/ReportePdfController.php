<?php

namespace App\Http\Controllers\Tram;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use \Milon\Barcode\DNS1D;
use \Milon\Barcode\DNS2D;   
use Illuminate\Support\Facades\DB;
class ReportePdfController extends Controller
{
   

    public function Pdf_certificados(Request $request)
    {
        
        $variable=$request->input('variable');
        $nombre = DB::select("EXEC tram.Sp_SEL_documentos_estudiantes_DASAXiDocId ?", [$variable]);
        $div = number_format(($nombre[0]->iControlCicloAcad/10),0,'.','');
        $mod = ($nombre[0]->iControlCicloAcad)%10;
        
        $nombreEstudiante = $nombre[0]->cNombreEstudiante;
        $codigoEstudiante = $nombre[0]->cEstudCodUniv;
        $escuelaEstudiante = $nombre[0]->cCarreraDsc;
        $sedeEstudiante = $nombre[0]->cFilDescripcion;
        $fecha=$nombre[0]->cDocFechaDoc;
        $car=explode(" ",$nombre[0]->cDocNumDoc);
        
        $ncarpeta=$car[(count($car)-1)];
        
        $nrecibo=$nombre[0]->cDocNumRecibo;
        $dniEstudiante = $nombre[0]->cDocumentoEstudiante;
        $departamentoEstudiante=$nombre[0]->cDocDepartamento;
        $provinciaEstudiante=$nombre[0]->cDocProvincia;
        $distritoEstudiante=$nombre[0]->cDocDistrito;
        $direccionEstudiante=$nombre[0]->cEstudDirecc;
        $primerSemestre=$nombre[0]->iControlCicloAcadPrimera;
        $primeraFecha=$nombre[0]->dMatricPrimeraFecha;
        foreach ($nombre as $index=>$dato){
                switch($dato->iTipoDocId){
                case (1):
                $pdf = PDF::loadView('Pdf_constancia_egresado',compact(['nombre','nombreEstudiante','codigoEstudiante','escuelaEstudiante','sedeEstudiante','div','mod']))->setPaper('A4');
                return $pdf->stream();
                        break;
                case (2):
                $pdf = PDF::loadView('Pdf_constancia_reserva_matricula',compact(['nombre','nombreEstudiante','codigoEstudiante','escuelaEstudiante','sedeEstudiante']))->setPaper('A4');
                return $pdf->stream();
                        break;

                case (3):
                $cod=$nombre[0]->cDocNumDoc;
                $ciclo=$request->input('semestre');
                
                $certificado= DB::select("EXEC tram.Sp_SEL_CertificadoEstudiosXcMatricCodUnivXcCadenaCodigoCiclo ?,?", [$codigoEstudiante,$ciclo]);
                $pdf = PDF::loadView('Pdf_certificado_estudios',compact(['semestre','ciclo','certificado','nombreEstudiante','escuelaEstudiante','sedeEstudiante','fecha','cod','codigoEstudiante']))->setPaper('A4');
                return $pdf->stream();
                        break;
                case (4):
                $pdf = PDF::loadView('Duplicado_boleta_de_notas',compact(['nombre','nombreEstudiante','codigoEstudiante','escuelaEstudiante','sedeEstudiante']))->setPaper('A4');
                return $pdf->stream();
                        break;

                case (5):
                $pdf = PDF::loadView('Duplicado_ficha_matricula',compact(['nombre','nombreEstudiante','codigoEstudiante','escuelaEstudiante','sedeEstudiante']))->setPaper('A4');
                return $pdf->stream();
                        break;
                case (6):
                $no=0;
                $nc=0;
               
                $curricula=$nombre[0]->cCurricAnio;
                $regime="";
                $codigoEstudiante = $nombre[0]->cEstudCodUniv;
                $plan = \DB::select('exec ura.sp_selPlanEstudios_x_cCodEstudiante ?',array($codigoEstudiante)); 
                $iCarreraId = $plan[0]->iCarreraId;
                $nplan = count($plan);
                for($i=0;$i<$nplan;$i++){
                    
                    $historial[$i][0] = $plan[$i]->cCurricDetCicloCurso;
                    $historial[$i][1] = $plan[$i]->cCurricCursoCod;
                    $historial[$i][2] = $plan[$i]->cCurricCursoDsc;
                    $cred = number_format(($plan[$i]->nCurricDetCredCurso),0);
                    $historial[$i][3] = $cred;
                    
                    $notas = \DB::select('exec ura.sp_sel_NotasEstudiante_x_cEstudCodUniv_x_cCarreraCod_x_cCursoCod ?, ?, ?',array($codigoEstudiante, $iCarreraId, $plan[$i]->cCurricCursoCod));
                    $nnotas = count($notas);
                    $k=4;
                    for($j=0;$j<5;$j++){
                            if(isset($notas[$j])){
                             
                                $div = number_format(($notas[$j]->iControlCicloAcad)/10,0,'.','');
                                $mod = ($notas[$j]->iControlCicloAcad)%10;
                                $cred = explode('.',$notas[$j]->iControlCicloAcad);
                                $historial[$i][$k]=$notas[$j]->nMatricDetNotaCurso." / ".$div."-".$mod;
                                
                                if(($notas[$j]->nMatricDetNotaCurso)>10){$nc=$nc+$plan[$i]->nCurricDetCredCurso;}
                                                
                        }
                            else{
                                $historial[$i][$k]="---";
                                    }
                            $k++;
                            }
                    if(($plan[$i]->tipo_curso )=='O'){$no++;}
            
                }
                $barra = new DNS1D();
                
                $pdf = PDF::loadView('Pdf_historial_academico',compact(['nombre','nombreEstudiante','codigoEstudiante','escuelaEstudiante','sedeEstudiante','historial','nplan','no','nc','fecha','curricula','regimen','barra','codigoEstudiante']))->setPaper('A4');
                return $pdf->stream();
                        break;

                case (7):
                
                $pdf = PDF::loadView('Pdf_constancia_estudio',compact(['nombre','nombreEstudiante','codigoEstudiante','escuelaEstudiante','sedeEstudiante','div','mod']))->setPaper('A4');
                return $pdf->stream();
                        break;
                case (8):
                $pdf = PDF::loadView('Pdf_constancia_egresado',compact(['nombre','nombreEstudiante','codigoEstudiante','escuelaEstudiante','sedeEstudiante']))->setPaper('A4');
                return $pdf->stream();
                        break;

                case (9):
                $pdf = PDF::loadView('Pdf_constancia_egresado',compact(['nombre','nombreEstudiante','codigoEstudiante','escuelaEstudiante','sedeEstudiante']))->setPaper('A4');
                return $pdf->stream();
                        break;
                case (10):
                $ciclo=$request->input('semestre');
                $certificado= DB::select("EXEC tram.Sp_SEL_Constancia_Orden_MeritoXcEstudCodUnivXcSemestre ?,?", [$codigoEstudiante,$ciclo]);
                $pdf = PDF::loadView('Pdf_constancia_orden_merito',compact(['nombre','nombreEstudiante','codigoEstudiante','escuelaEstudiante','sedeEstudiante','certificado']))->setPaper('A4');
                return $pdf->stream();
                        break;

                case (12):
                $pdf = PDF::loadView('Pdf_constancia_egresado',compact(['nombre','nombreEstudiante','codigoEstudiante','escuelaEstudiante','sedeEstudiante']))->setPaper('A4');
                return $pdf->stream();
                        break;
                case (13):
                set_time_limit(300);
                $pdf = PDF::loadView('Pdf_carpeta_bachiller',compact(['nombre','nombreEstudiante','codigoEstudiante','escuelaEstudiante','sedeEstudiante','ncarpeta','nrecibo','dniEstudiante','departamentoEstudiante','provinciaEstudiante','distritoEstudiante','direccionEstudiante','primerSemestre','primeraFecha']))->setPaper('A4');
                return $pdf->stream();
                        break;

                case (14):
               
                $pdf = PDF::loadView('Pdf_carpeta_titulacion',compact(['nombre','nombreEstudiante','codigoEstudiante','escuelaEstudiante','sedeEstudiante','ncarpeta','nrecibo','dniEstudiante','departamentoEstudiante','provinciaEstudiante','distritoEstudiante','direccionEstudiante','primerSemestre','primeraFecha']))->setPaper('A4');
                return $pdf->stream();
                        break;
                case (15):
                $pdf = PDF::loadView('Pdf_constancia_egresado',compact(['nombre','nombreEstudiante','codigoEstudiante','escuelaEstudiante','sedeEstudiante']))->setPaper('A4');
                return $pdf->stream();
                        break;
                case (16):
                $pdf = PDF::loadView('Pdf_constancia_egresado',compact(['nombre','nombreEstudiante','codigoEstudiante','escuelaEstudiante','sedeEstudiante']))->setPaper('A4');
                return $pdf->stream();
                        break;
                case (17):
                $pdf = PDF::loadView('Pdf_constancia_egresado',compact(['nombre','nombreEstudiante','codigoEstudiante','escuelaEstudiante','sedeEstudiante']))->setPaper('A4');
                return $pdf->stream();
                        break;
                case (18):
                $pdf = PDF::loadView('Pdf_constancia_egresado',compact(['nombre','nombreEstudiante','codigoEstudiante','escuelaEstudiante','sedeEstudiante']))->setPaper('A4');
                return $pdf->stream();
                        break;
                case (19):
                $pdf = PDF::loadView('Pdf_constancia_egresado',compact(['nombre','nombreEstudiante','codigoEstudiante','escuelaEstudiante','sedeEstudiante']))->setPaper('A4');
                return $pdf->stream();
                        break;
                
                
                
                
                
                        }
                    }
          
        
       

    }

}
