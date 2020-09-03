<?php

namespace App\Http\Controllers\inv;

use App\ClasesLibres\TramiteDocumentario\PdfCreator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Milon\Barcode\DNS1D;
use PDF;

class ReportePdfController extends Controller
{
    public function descargaPptTotalProyXanyoEstProptPdf($iDepenId, $fecha, $iEstadoPropuesta){
        $fecha = (($fecha == 'all' || $fecha == 'undefined') ? '' : $fecha);
        $iEstadoPropuesta = (($iEstadoPropuesta == 'all' || $iEstadoPropuesta == 'undefined') ? '' : $iEstadoPropuesta);
        $data1 = \DB::select('EXEC grl.[Sp_SEL_dependenciasXiDepenId] ?', array($iDepenId));
        $data2 = \DB::select('EXEC inv.[Sp_INV_presupuesto_total_Xanyo_iEstadoPropuesta] ?, ?, ?', array($iDepenId, $fecha, $iEstadoPropuesta));
        $pdf = \PDF::loadView('inv.Pdf_pptTotalProyXanyoEstPropt', compact(['data1', 'data2','fecha']))->setPaper('A4','landscape');
        return $pdf->download("pptTotalProyXanyoEstPropt.pdf");
    }

    public function descargaPptxRubroXanyoEstProptPdf($iDepenId, $fecha, $iEstadoPropuesta){
        $fecha = (($fecha == 'all' || $fecha == 'undefined') ? '' : $fecha);
        $iEstadoPropuesta = (($iEstadoPropuesta == 'all' || $iEstadoPropuesta == 'undefined') ? '' : $iEstadoPropuesta);
        $data1 = \DB::select('EXEC grl.[Sp_SEL_dependenciasXiDepenId] ?', array($iDepenId));
        $data2 = \DB::select('EXEC inv.[Sp_INV_presupuesto_x_rubro_Xanyo_iEstadoPropuesta] ?, ?, ?', array($iDepenId, $fecha, $iEstadoPropuesta));
        $pdf = \PDF::loadView('inv.Pdf_pptxRubroXanyoEstPropt', compact(['data1', 'data2','fecha']))->setPaper('A4','landscape');
        return $pdf->download("pptxRubroXanyoEstPropt.pdf");
    }

    public function descargaGtxRubroXanyoEstProptPdf($iDepenId, $fecha, $iEstadoPropuesta){
        $fecha = (($fecha == 'all' || $fecha == 'undefined') ? '' : $fecha);
        $iEstadoPropuesta = (($iEstadoPropuesta == 'all' || $iEstadoPropuesta == 'undefined') ? '' : $iEstadoPropuesta);
        $data1 = \DB::select('EXEC grl.[Sp_SEL_dependenciasXiDepenId] ?', array($iDepenId));
        $data2 = \DB::select('EXEC inv.[Sp_INV_gasto_x_rubro_Xanyo_iEstadoPropuesta] ?, ?, ?', array($iDepenId, $fecha, $iEstadoPropuesta));
        $pdf = \PDF::loadView('inv.Pdf_gtxRubroXanyoEstPropt', compact(['data1', 'data2','fecha']))->setPaper('A4','landscape');
        return $pdf->download("gtxRubroXanyoEstPropt.pdf");
    }

    public function descargaInfAvTecPdf($idProyecto, $idInfAvTec, $idHito){
        $dataEnviar = ['', '', '', '', '', '', 9, $idProyecto];
        $dataProy = \DB::select('EXEC inv.Sp_SEL_proyecto_XcConsultaVariablesCampos ?, ?, ?, ?, ?, ?, ?, ?', $dataEnviar);
        $dataHito = \DB::select("EXEC inv.Sp_SEL_hitos_proyecto_XiProyectoId_iHitoId  ?, ?, ?", array($idProyecto, $idHito, null));
        $dataInfTec = \DB::select("EXEC inv.Sp_SEL_informe_avance_tecnico_XiHitoId  ?", array($idHito));
        $obj = \DB::select("EXEC inv.Sp_REP_avance_tecnico_obj_ind_XiProyectoId_iHitoId  ?, ?, ?", array($idProyecto, $idHito, $idInfAvTec));
        $nObj = count($obj);
        for ($i = 0; $i < $nObj; $i++) {
            if ($obj[$i]->iTipoObjetivoId == 1) {
                $objGral[$i] = $obj[$i];
            } else {
                $objEspecifico[$i] = $obj[$i];
            }
        }
        $act = \DB::select("EXEC inv.Sp_REP_avance_tecnico_obj_act_XiProyectoId_iHitoId  ?, ?, ?, ?", array($idProyecto, $idHito, $idInfAvTec, 2));

        $hito = \DB::select("EXEC inv.Sp_REP_avnace_tecnico_ht_ind_XiProyectoId_iHitoId  ?, ?, ?", array($idProyecto, $idHito, $idInfAvTec));
        $resultado = DB::select("EXEC inv.Sp_SEL_resultado_hito_XiHitoId  ?", array($idInfAvTec));
        $riesgo = DB::select("EXEC inv.Sp_SEL_riesgo_hito_XiHitoId  ?", array($idInfAvTec));
        $problema = DB::select("EXEC inv.Sp_SEL_problema_hito_XiHitoId  ?", array($idInfAvTec));
        $observacion = DB::select("EXEC inv.Sp_REP_avnace_tecnico_obs_XiProyectoId_iHitoId  ?, ?", array($idProyecto, $idHito));
        $observacionEvaluada = DB::select("EXEC inv.Sp_REP_avnace_tecnico_obs_evaluada_XiProyectoId_iHitoId  ?, ?", array($idProyecto, $idHito));
        $miembro = DB::select("EXEC inv.Sp_SEL_miembro_hito_XiInfoAvTecId  ?", array($idInfAvTec));
        $avFinanciero = DB::select('EXEC inv.Sp_SEL_avance_financiero ?, ?', array($idProyecto, $idHito));
        $resumenEjecPresupuestal = DB::select('EXEC inv.Sp_SEL_avance_financiero_resumen ?, ?', array($idProyecto, $idHito));

        $pdf = \PDF::loadView('inv.Pdf_infAvTec', compact(['dataProy', 'dataHito',
            'dataInfTec',
            'objGral',
            'objEspecifico',
            'act',
            'hito',
            'resultado',
            'riesgo',
            'problema',
            'observacion',
            'observacionEvaluada',
            'miembro',
            'avFinanciero',
            'resumenEjecPresupuestal'
        ]))->setPaper('A4');
        return $pdf->download("infAvTec.pdf");
    }
//reportes sistema antiguo
    public function reporte_web_funcion($iDepenId, $Tipo, $Anyo){
        $dataEnviar =[$Anyo];
        $datafecha = \DB::select('EXEC inv.Sp_SEL_yearsXiYearId ?', $dataEnviar);
        $data1= \DB::select('EXEC grl.[Sp_SEL_dependenciasXiDepenId] ?', array($iDepenId));
        $resumen = \DB::select('EXEC inv.[Sp_REP_avance_presupuestal_tecnico] ?,?', array( $Tipo, $Anyo));
        $pdf = \PDF::loadView('inv.reporte_webblade', compact(['resumen']))->setPaper('A4', 'landscape');
        return $pdf->download("reporteweb_cualquiera.pdf");
    }
    public function reporte_detalle_trimestral($iDepenId, $Tipo, $Anyo){
        $data1 = \DB::select('EXEC grl.[Sp_SEL_dependenciasXiDepenId] ?', array($iDepenId));
        $resumen = \DB::select('EXEC inv.[Sp_REP_anual_trimestral] ?,?', array( $Tipo, $Anyo));
        $pdf = \PDF::loadView('inv.reporte_trimestralblade', compact(['resumen']))->setPaper('A4', 'landscape');
        return $pdf->download("reportetrimestral_cualquiera.pdf");
    }
    public function reporte_miembros($iDepenId, $Tipo, $Anyo){
        $data1 = \DB::select('EXEC grl.[Sp_SEL_dependenciasXiDepenId] ?', array($iDepenId));
        $resumen = \DB::select('EXEC inv.[Sp_REP_miembros_proyecto] ?,?', array( $Tipo, $Anyo));
        $nRes = collect($resumen)->groupBy(['cNombreProyecto']);

        //dd($nRes);
        $pdf = \PDF::loadView('inv.reporte_miembrosblade', compact(['resumen', 'nRes']))->setPaper('A4', 'landscape');
        return $pdf->download("reportemiembros_cualquiera.pdf");
    }
    public function reporte_consolidado($iDepenId, $Anyo){
    $data1 = \DB::select('EXEC grl.[Sp_SEL_dependenciasXiDepenId] ?', array($iDepenId));
    $resumen = \DB::select('EXEC inv.[Sp_REP_consolidado_item_financiable] ?', array( $Anyo));
    $resumen1 = \DB::select('EXEC inv.[Sp_REP_consolidado_item_financiable_saldos] ?', array( $Anyo));
    $pdf = \PDF::loadView('inv.reporte_consolidadoblade', compact(['resumen','resumen1']))->setPaper('A4', 'landscape');
    return $pdf->download("reporteconsolidado_cualquiera.pdf");
    }
    public function reporte_revisionitems($iDepenId, $Anyo){
        $data1 = \DB::select('EXEC grl.[Sp_SEL_dependenciasXiDepenId] ?', array($iDepenId));
        $resumen = \DB::select('EXEC inv.[Sp_REP_revision_items_fianciables] ?', array( $Anyo));
        $pdf = \PDF::loadView('inv.reporte_revisionitemsblade', compact(['resumen',]))->setPaper('A4', 'landscape');
        return $pdf->download("reporterevisionitems_cualquiera.pdf");
    }
    //reportes presupuestos
    public function descargaRepPptPresupuestoXrubroResumenPdf( $idAnyo){
        $dataEnviar =[$idAnyo];
        $datafecha = \DB::select('EXEC inv.Sp_SEL_yearsXiYearId ?', $dataEnviar);
        $resumen = \DB::select('EXEC inv.[Sp_REP_financiero_tecnico] ?', array( $idAnyo));
        $pdf = \PDF::loadView('inv.Pdf_repPptPtXrubroResumen', compact(['resumen', 'datafecha']))->setPaper('A4');
        return $pdf->download("repPptPtXrubroResumen.pdf");
    }
    public function descargaProyectosXescuela_docentesPdf($idCarrera){
       $dataEnviar = [$idCarrera];
       $dataProy = \DB::select('EXEC inv.Sp_SEL_carrerasXid ?', $dataEnviar);
        $resumen = \DB::select('EXEC inv.[Sp_REP_proyectosXescuela_docentes] ?', array($idCarrera));
        $pdf = \PDF::loadView('inv.Pdf_proyectosXescuela_docentes', compact(['resumen', 'dataProy']))->setPaper('A4','landscape');
        return $pdf->download("Proyectos-docentesXescuela.pdf");
    }
    // reportes de proyectos
    public function descargaRepPptGastoXrubroResumenPdf($idProyecto){
        $dataEnviar = ['', '', '', '', '', '', 9, $idProyecto];
        $dataProy = \DB::select('EXEC inv.Sp_SEL_proyecto_XcConsultaVariablesCampos ?, ?, ?, ?, ?, ?, ?, ?', $dataEnviar);
        $resumen = \DB::select('EXEC inv.[Sp_SEL_rubro_presupuesto_gasto_resumen] ?', array($idProyecto));
        $avPresupuestal = \DB::select('EXEC inv.[Sp_SEL_avance_presupuestal] ?', array($idProyecto));
        $pdf = \PDF::loadView('inv.Pdf_repPptGtXrubroResumen', compact(['dataProy','resumen','avPresupuestal']))->setPaper('A4');
        return $pdf->download("repPptGtXrubroResumen.pdf");
    }

    public function descargaRepPptGastoXrubroDetalladoPdf($idProyecto){
        $dataEnviar = ['', '', '', '', '', '', 9, $idProyecto];
        $dataProy = \DB::select('EXEC inv.Sp_SEL_proyecto_XcConsultaVariablesCampos ?, ?, ?, ?, ?, ?, ?, ?', $dataEnviar);
        $detallado = \DB::select('EXEC inv.[Sp_SEL_rubro_presupuesto_gasto_detalle] ?, ?', array($idProyecto, null));
        $avPresupuestal = \DB::select('EXEC inv.[Sp_SEL_avance_presupuestal] ?', array($idProyecto));
        $mio = array();
        $det = array();
        $iRubroIdAnt = 0;
        $cRubroDescripcionAnt ='';
        foreach ($detallado as $clave=>$valor)
        {
            if ($valor->iRubroId !== $iRubroIdAnt && $iRubroIdAnt !== 0){
                $mio[] = ['rubro' => $cRubroDescripcionAnt, 'detalle' => $det];
                unset($det);
            }
            $iRubroIdAnt = $valor->iRubroId;
            $cRubroDescripcionAnt = $valor->cRubroDescripcion;
            $det[] = $detallado[$clave];
        }
        if ($iRubroIdAnt !== 0){
            $mio[] = ['rubro' => $cRubroDescripcionAnt, 'detalle' => $det];
            unset($det);
        }

        $pdf = \PDF::loadView('inv.Pdf_repPptGtXrubroDetallado', compact(['dataProy','detallado','avPresupuestal','mio']))->setPaper('A4','landscape');
        return $pdf->download("repPptGtXrubroDetallado.pdf");
    }

    //reportes de antonio parece

    public function Pdf_certificados($id, $porQr = false)
    {

        $variable = $id;
        $nombre = DB::select("EXEC tram.Sp_SEL_documentos_estudiantes_DASAXiDocId ?", [$variable]);
        // dd($nombre);

        $val = $this->getPdfData($nombre, $porQr);
        //dd('SSS');

        //dd($val);
        if ($val) {
            return $this->salidaPdf($val);
        }

        // $variable=$request->input('variable');

        // dd($nombre);
        $div = number_format(($nombre[0]->iControlCicloAcad / 10), 0, '.', '');
        $mod = ($nombre[0]->iControlCicloAcad) % 10;

        $nombreEstudiante = $nombre[0]->cNombreEstudiante;
        $codigoEstudiante = $nombre[0]->cEstudCodUniv;
        $escuelaEstudiante = $nombre[0]->cCarreraDsc;
        $sedeEstudiante = $nombre[0]->cFilDescripcion;
        $fecha = $nombre[0]->cDocFechaDoc;
        $car = explode(" ", $nombre[0]->cDocNumDoc);

        $ncarpeta = $car[(count($car) - 1)];

        $nrecibo = $nombre[0]->cDocNumRecibo;
        $dniEstudiante = $nombre[0]->cDocumentoEstudiante;
        $departamentoEstudiante = $nombre[0]->cEstudDepartamento;
        $provinciaEstudiante = $nombre[0]->cEstudProvincia;
        $distritoEstudiante = $nombre[0]->cEstudDistrito;
        $direccionEstudiante = $nombre[0]->cEstudDirecc;
        $primerSemestre = $nombre[0]->iControlCicloAcadPrimera;
        $pf = explode(" ", $nombre[0]->dMatricPrimeraFecha);
        $primeraFecha = $pf[0];

        $anio = $nombre[0]->cYearOficial;
        foreach ($nombre as $index => $dato) {
            switch ($dato->iTipoDocId) {
                case (1):
                    $pdf = PDF::loadView('Pdf_constancia_egresado', compact(['nombre', 'nombreEstudiante', 'codigoEstudiante', 'escuelaEstudiante', 'sedeEstudiante', 'div', 'mod', 'anio']))->setPaper('A4');
                    return $pdf->stream();


                case (2):
                    $pdf = PDF::loadView('Pdf_constancia_reserva_matricula', compact(['nombre', 'nombreEstudiante', 'codigoEstudiante', 'escuelaEstudiante', 'sedeEstudiante']))->setPaper('A4');
                    return $pdf->stream();
                    break;

                case (3):
                    $cod = $nombre[0]->cDocNumDoc;
                    $array[0] = "CERO";
                    $array[1] = "UNO";
                    $array[2] = "DOS";
                    $array[3] = "TRES";
                    $array[4] = "CUATRO";
                    $array[5] = "CINCO";
                    $array[6] = "SEIS";
                    $array[7] = "SIETE";
                    $array[8] = "OCHO";
                    $array[9] = "NUEVE";
                    $array[10] = "DIEZ";
                    $array[11] = "ONCE";
                    $array[12] = "DOCE";
                    $array[13] = "TRECE";
                    $array[14] = "CATORCE";
                    $array[15] = "QUINCE";
                    $array[16] = "DIECISEIS";
                    $array[17] = "DIECISIETE";
                    $array[18] = "DIECIOCHO";
                    $array[19] = "DIECINUEVE";
                    $array[20] = "VEINTE";
                    $no = 0;
                    $nc = 0;
                    $fecha = $nombre[0]->cDocFechaDoc;
                    $curricula = $nombre[0]->cCurricAnio;
                    $regime = "";
                    $codigoEstudiante = $nombre[0]->cEstudCodUniv;
                    $plan = \DB::select('exec ura.sp_selPlanEstudios_x_cCodEstudiante ?', array($codigoEstudiante));
                    $iCarreraId = $plan[0]->iCarreraId;
                    $nplan = count($plan);
                    $total = 0;
                    $tcredito = 0;
                    $tcn = 0;
                    for ($i = 0; $i < $nplan; $i++) {

                        $notas = \DB::select('exec ura.sp_sel_NotasEstudiante_x_cEstudCodUniv_x_cCarreraCod_x_cCursoCod ?, ?, ?', array($codigoEstudiante, $iCarreraId, $plan[$i]->cCurricCursoCod));
                        $cant = (count($notas)) - 1;
                        if (isset($notas[$cant])) {
                            $div = number_format(($notas[$cant]->iControlCicloAcad) / 10, 0, '.', '');

                            $mod = ($notas[$cant]->iControlCicloAcad) % 10;
                            $cred = explode('.', $notas[0]->iControlCicloAcad);

                            $historial[$total][0] = $plan[$i]->cCurricDetCicloCurso;
                            $historial[$total][1] = $plan[$i]->cCurricCursoDsc;
                            $cred = number_format(($plan[$i]->nCurricDetCredCurso), 0);
                            $historial[$total][2] = $cred;
                            $historial[$total][3] = "( " . $notas[$cant]->nMatricDetNotaCurso . " )  " . $array[$notas[$cant]->nMatricDetNotaCurso];

                            $historial[$total][4] = $div . "-" . $mod;
                            $total++;
                            $tcredito = $tcredito + $cred;
                            $tcn = $tcn + ($cred * $notas[$cant]->nMatricDetNotaCurso);
                            if (($plan[$i]->tipo_curso) == 'O') {
                                $no++;
                            }
                        }
                    }
                    $prom = number_format($tcn / $tcredito, 2);
                    set_time_limit(300);
                    $pdf = PDF::loadView('Pdf_certificado_estudios', compact(['nombre', 'nombreEstudiante', 'codigoEstudiante', 'escuelaEstudiante', 'sedeEstudiante', 'array', 'nplan', 'historial', 'no', 'total', 'tcredito', 'prom', 'cod', 'fecha']))->setPaper('A4');
                    return $pdf->stream();
                    break;
                case (4):
                    $pdf = PDF::loadView('Duplicado_boleta_de_notas', compact(['nombre', 'nombreEstudiante', 'codigoEstudiante', 'escuelaEstudiante', 'sedeEstudiante']))->setPaper('A4');
                    return $pdf->stream();
                    break;

                case (5):
                    $pdf = PDF::loadView('Duplicado_ficha_matricula', compact(['nombre', 'nombreEstudiante', 'codigoEstudiante', 'escuelaEstudiante', 'sedeEstudiante']))->setPaper('A4');
                    return $pdf->stream();
                    break;
                case (6):
                    $no = 0;
                    $nc = 0;

                    $curricula = $nombre[0]->cCurricAnio;
                    $regime = "";
                    $codigoEstudiante = $nombre[0]->cEstudCodUniv;
                    $plan = \DB::select('exec ura.sp_selPlanEstudios_x_cCodEstudiante ?', array($codigoEstudiante));
                    $iCarreraId = $plan[0]->iCarreraId;
                    $nplan = count($plan);
                    for ($i = 0; $i < $nplan; $i++) {

                        $historial[$i][0] = $plan[$i]->cCurricDetCicloCurso;
                        $historial[$i][1] = $plan[$i]->cCurricCursoCod;
                        $historial[$i][2] = $plan[$i]->cCurricCursoDsc;
                        $cred = number_format(($plan[$i]->nCurricDetCredCurso), 0);
                        $historial[$i][3] = $cred;

                        $notas = \DB::select('exec ura.sp_sel_NotasEstudiante_x_cEstudCodUniv_x_cCarreraCod_x_cCursoCod ?, ?, ?', array($codigoEstudiante, $iCarreraId, $plan[$i]->cCurricCursoCod));
                        $nnotas = count($notas);
                        $k = 4;
                        for ($j = 0; $j < 5; $j++) {
                            if (isset($notas[$j])) {
                                $div = number_format(($notas[$j]->iControlCicloAcad) / 10, 0, '.', '');
                                $mod = ($notas[$j]->iControlCicloAcad) % 10;
                                $cred = explode('.', $notas[$j]->iControlCicloAcad);
                                $historial[$i][$k] = $notas[$j]->nMatricDetNotaCurso . " / " . $div . "-" . $mod;

                                if (($notas[$j]->nMatricDetNotaCurso) > 10) {
                                    $nc = $nc + $plan[$i]->nCurricDetCredCurso;
                                }
                            } else {
                                $historial[$i][$k] = "---";
                            }
                            $k++;
                        }
                        if (($plan[$i]->tipo_curso) == 'O') {
                            $no++;
                        }

                    }
                    $barra = new DNS1D();
                    set_time_limit(300);
                    $pdf = PDF::loadView('Pdf_historial_academico', compact(['nombre', 'nombreEstudiante', 'codigoEstudiante', 'escuelaEstudiante', 'sedeEstudiante', 'historial', 'nplan', 'no', 'nc', 'fecha', 'curricula', 'barra', 'codigoEstudiante']))->setPaper('A4');
                    return $pdf->stream();
                    break;

                case (7):

                    $pdf = PDF::loadView('Pdf_constancia_estudio', compact(['nombre', 'nombreEstudiante', 'codigoEstudiante', 'escuelaEstudiante', 'sedeEstudiante', 'div', 'mod', 'anio']))->setPaper('A4');
                    return $pdf->stream();
                    break;

                case (8):
                    $certificado = DB::select("EXEC tram.Sp_SEL_Constancia_Orden_Merito_SuperiorXcEstudCodUniv ?,?", [$codigoEstudiante, 3]);
                    $pdf = PDF::loadView('Pdf_constancia_tercio_superior_semestre', compact(['nombre', 'nombreEstudiante', 'codigoEstudiante', 'escuelaEstudiante', 'sedeEstudiante', 'certificado', 'anio']))->setPaper('A4');
                    return $pdf->stream();
                    break;


                case (9):
                    $certificado = DB::select("EXEC tram.Sp_SEL_Constancia_Orden_Merito_SuperiorXcEstudCodUniv ?,?", [$codigoEstudiante, 5]);
                    $pdf = PDF::loadView('Pdf_constancia_quinto_superior_semestre', compact(['nombre', 'nombreEstudiante', 'codigoEstudiante', 'escuelaEstudiante', 'sedeEstudiante', 'certificado', 'anio']))->setPaper('A4');
                    return $pdf->stream();
                    break;


                case (10):
                    $ciclo = $nombre[0]->cTramContenido;
                    $certificado = DB::select("EXEC tram.Sp_SEL_Constancia_Orden_MeritoXcEstudCodUnivXcSemestre ?,?", [$codigoEstudiante, $ciclo]);
                    $pdf = PDF::loadView('Pdf_constancia_orden_merito', compact(['nombre', 'nombreEstudiante', 'codigoEstudiante', 'escuelaEstudiante', 'sedeEstudiante', 'certificado', 'anio']))->setPaper('A4');
                    return $pdf->stream();
                    break;

                case (12):
                    //$pdf = PDF::loadView('Pdf_constancia_egresado', compact(['nombre', 'nombreEstudiante', 'codigoEstudiante', 'escuelaEstudiante', 'sedeEstudiante']))->setPaper('A4');
                    //return $pdf->stream();
                    break;



                case (15):
                    // $pdf = PDF::loadView('Pdf_constancia_egresado', compact(['nombre', 'nombreEstudiante', 'codigoEstudiante', 'escuelaEstudiante', 'sedeEstudiante']))->setPaper('A4');
                    //return $pdf->stream();
                    break;
                case (16):
                    //$pdf = PDF::loadView('Pdf_constancia_egresado', compact(['nombre', 'nombreEstudiante', 'codigoEstudiante', 'escuelaEstudiante', 'sedeEstudiante']))->setPaper('A4');
                    //return $pdf->stream();
                    break;


                case (17):
                    $ciclo = $nombre[0]->cTramContenido;
                    $certificado = DB::select("EXEC tram.Sp_SEL_Constancia_Orden_MeritoSuperiorIngresantesXcEstudCodUnivXcSemestre ?,?,?", [$codigoEstudiante, $ciclo, '3']);
                    $pdf = PDF::loadView('Pdf_constancia_tercio_superior_ingresante', compact(['nombre', 'nombreEstudiante', 'codigoEstudiante', 'escuelaEstudiante', 'sedeEstudiante', 'certificado', 'ciclo', 'anio']))->setPaper('A4');
                    return $pdf->stream();
                    break;


                case (18):
                    $ciclo = $nombre[0]->cTramContenido;
                    $certificado = DB::select("EXEC tram.Sp_SEL_Constancia_Orden_MeritoSuperiorIngresantesXcEstudCodUnivXcSemestre ?,?,?", [$codigoEstudiante, $ciclo, '5']);
                    $pdf = PDF::loadView('Pdf_constancia_quinto_superior_ingresante', compact(['nombre', 'nombreEstudiante', 'codigoEstudiante', 'escuelaEstudiante', 'sedeEstudiante', 'certificado', 'ciclo', 'anio']))->setPaper('A4');
                    return $pdf->stream();
                    break;


                case (19):
                    $pdf = PDF::loadView('Pdf_constancia_conformidad', compact(['nombre', 'nombreEstudiante', 'codigoEstudiante', 'escuelaEstudiante', 'sedeEstudiante']))->setPaper('A4');
                    return $pdf->stream();
                    break;

                //$pdf->render();
                //$_pdf=$pdf->output();
                //@file_put_contents('sigeun'.".pdf", $_pdf);
                //return response()->json($data, 200, $headers);
                //return $pdf->stream();
                //      break;


            }
        }
    }

    public function getPdfData($dataTramite, $porQr)
    {
        // return 'BBB';
        // SECCION DE TRAMITE DATA
        //$iDocId = 375;
        // FIN SECCION

        // dd($dataTramite);

        $arrayFormatosHT = [3, 6, 13, 14, 19];

        if (!in_array($dataTramite[0]->iTipoDocId, $arrayFormatosHT)) {
            return false;
        }


        if (count($dataTramite) > 0) {
            $pdf = new PdfCreator(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->formatoSeleccionado = 6;

            header("Access-Control-Allow-Origin: *");


            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Antonio Salas');
            $pdf->SetTitle('Reportes UNAM');
            $pdf->SetSubject('Tramites - UNAM');
            $pdf->SetKeywords('UNAM, Moquegua, Ilo, EPISI');

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP + 10, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            // CONFIGURACION PARA ARCHIVO
            $pdf->qrValue = $dataTramite[0]->cDocQrId;
            $pdf->fechaAceptado = $dataTramite[0]->cDocFechaDoc;
            $pdf->porQr = $porQr;


            // ---------------------------------------------------------

            switch ($dataTramite[0]->iTipoDocId) {
                case 3:
                    $pdf->mostrarLogoHeader = false;
                    $pdf->mostrarQr = false;
                    $pdf->numeracionValue = sprintf("%04d", $dataTramite[0]->cDocNumDoc) . ' - ' . $dataTramite[0]->iDocAnioEmision;
                    // add a page
                    $pdf->AddPage();
                    $pdf->mostrarLogoHeader = true;
                    $pdf->mostrarQr = true;

                    $htmlFooter = '<p style="font-size: 11px; text-align: justify;">Las enmendaduras invalidan el certificado.<br>';
                    $htmlFooter .= 'NOTA APROBATORIA DE 11 A 20 PUNTOS</p>';

                    $pdf->addHtmlFooter = $htmlFooter;

                    $pdf->certificadoDeEstudios($dataTramite);

                    break;
                case 6:
                    // add a page
                    $pdf->AddPage();
                    $pdf->SetFont('helvetica', 'B', 16, '', 'default', true);
                    $pdf->Cell(0, 0, 'HISTORIAL ACADEMICO', 0, 1, 'C', 0, '', 0);

                    $pdf->infoEstudiante($dataTramite[0]);

                    $pdf->writeHTML('<strong style="font-size: 12px;">DETALLE DE CURSOS</strong>', true, false, false, false, '');

                    $pdf->Ln();

                    $pdf->historialAcademico($dataTramite[0]->cEstudCodUniv);
                    break;

                case 13:
                    $pdf->setPrintHeader(false);
                    $pdf->setPrintFooter(false);

                    // dd($dataTramite[0]);

                    $pdf->carpetaBachiller($dataTramite[0]);

                    break;

                case 14:
                    $pdf->setPrintHeader(false);
                    $pdf->setPrintFooter(false);

                    // dd($dataTramite[0]);

                    $pdf->carpetaTitulacion($dataTramite[0]);

                    break;

                case 19:


                    $pdf->setPrintHeader();
                    $pdf->setPrintFooter();

                    $pdf->constanciaDeConformidad($dataTramite[0]);

                    break;
                default:
                    return false;
                    break;
            }


            // set font


            // $pdf->generarTabla(['HOLA', 'Como', 'Estas'], []);


            // column titles
            $header = array('ID', 'Descripcion', 'SIAF', 'Clasificador');

            // data loading
            // $data = $this->LoadData($funcion);

            // print colored table
            // $pdf->ColoredTable($header, $data,$funcion);
            ob_end_clean();
            // ---------------------------------------------------------

            return $pdf;

        }


    }

    public function salidaPdf($pdf)
    {
        // close and output PDF document
        $pdf->Output('unam-SIGEUN.pdf', 'I');
    }

    public function getPdfFromUrl(Request $request, $iDocIdEncoded)
    {
        // dd($request->toArray());
        // dd($iDocIdEncoded);
        $data = DB::select('EXEC tram.Sp_SEL_documentos_estudiantes_DASAXcDocQrId ?', [$iDocIdEncoded]);

        if (isset($data[0])) {
            if (isset($request->print)) {
                return $this->Pdf_certificados($data[0]->iDocId);
            } else {
                return $this->Pdf_certificados($data[0]->iDocId, true);
            }
        } else {
            return 'Archivo Inexistente.';
        }

        // dd($data[0]->iDocId);


        //$this->getPdfData(null, $iDocIdEncoded);

    }
}
