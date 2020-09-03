<?php

namespace App\Http\Controllers\Inv;

use App\Exports\TrimestralExport;
use App\Packages\Maatwebsite\Maatwebsite\Excel\Facades\Excel;
use PHPExcel;
use PHPExcel_Shared_Font;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
class ReporteExcelController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set("America/Lima");
    }
    public function exportExcel(){
        return Excel::download(new TrimestralExport, 'trimestral.xlsx');
    }
    public function exportCalificaciones(Request $request)
    {
        $x = 'A';
        for ($i=0; $i < ($request->params['x'] - 1); $i++) {
            ++$x;
        }

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
        $spreadsheet = $reader->loadFromString($request->data);

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getColumnDimension('A')->setWidth(60);

        $sheet->getStyle("A1:".$x.$request->params['y'])->getBorders()->applyFromArray( array( 'allBorders' => array( 'borderStyle' => 'thin')));
        $sheet->getStyle("A1:" . $x. "4")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("A1:" . $x. "4")->getAlignment()->setVertical('center');
        $sheet->getStyle("A1:" . $x. "4")->getFill()->setFillType('solid')->getStartColor()->setARGB('c3c3c3c3');

        $sheet->insertNewRowBefore(1, 8);

        /**
         * Poniendo el title del archivo
         */
        $sheet->setCellValue('A1', 'Reporte de calificaciones');
        $sheet->mergeCells('A1:' . $x . "1" );
        $sheet->mergeCells('A2:' . $x . "2" );
        $sheet->getStyle("A1:" . $x. "1")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("A1:" . $x. "1")->getFill()->setFillType('solid')->getStartColor()->setARGB('c3c3c3c3');
        /**
         * Poniendo los params del archivo
         */

        $sheet->setCellValue('A3', 'Docente:');
        $sheet->setCellValue('B3', $request->params['docente']);
        $sheet->getStyle('B3' . ":" . $x . "3" )->getAlignment()->setHorizontal('left');
        $sheet->mergeCells('B3' . ":" . $x . "3" );

        $sheet->setCellValue('A4', 'Curso:');
        $sheet->setCellValue('B4', $request->params['curso']);
        $sheet->getStyle('B4' . ":" . $x . "4" )->getAlignment()->setHorizontal('left');
        $sheet->mergeCells('B4' . ":" . $x . "4" );

        $sheet->setCellValue('A5', 'Sección');
        $sheet->setCellValue('B5', $request->params['seccion']);
        $sheet->getStyle('B5' . ":" . $x . "5" )->getAlignment()->setHorizontal('left');
        $sheet->mergeCells('B5' . ":" . $x . "5" );

        $sheet->setCellValue('A6', 'Carrera profesional:');
        $sheet->setCellValue('B6', $request->params['carrera']);
        $sheet->getStyle('B6' . ":" . $x . "6" )->getAlignment()->setHorizontal('left');
        $sheet->mergeCells('B6' . ":" . $x . "6" );

        $sheet->setCellValue('A7', 'Fecha:');
        $sheet->setCellValue('B7', date('d-m-Y'));
        $sheet->getStyle('B7' . ":" . $x . "7" )->getAlignment()->setHorizontal('left');
        $sheet->mergeCells('B7' . ":" . $x . "7" );

        $sheet->getStyle("A3:A7")->getFill()->setFillType('solid')->getStartColor()->setARGB('c3c3c3c3');


        header("Access-Control-Allow-Origin: *");

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
    public function web_funcion($Tipo, $Anyo)
    {

        $resumen = \DB::select('exec inv.Sp_REP_avance_presupuestal_tecnico  ?,?', array($Tipo, $Anyo));
        $datafecha = \DB::select('exec inv.Sp_SEL_yearsXiYearId ?', array($Anyo));
        Excel::create('Excel WEB', function ($excel) use ($Tipo, $Anyo , $resumen,$datafecha) {

            $excel->sheet('REPORTE', function ($sheet) use ($Tipo, $Anyo, $resumen,$datafecha) {

///////////////////////////////////CABECERA/////////////////////////////////////////////
                $sheet->setCellValue('B1', 'UNIVERSIDAD NACIONAL DE MOQUEGUA');
                $sheet->mergeCells("B1:K1");
                $sheet->getStyle('B1')->getFont()->setName('Tahoma')->setBold(true)->setSize(22);
                //////////////////////////////////////
                $sheet->setCellValue('C6', 'AÑO');
                $sheet->setCellValue('C5', 'TIPO DE PROYECTO');
                $sheet->cells('B1:K1', function ($cells) {
                    $cells->setAlignment('center');
                });
                $sheet->getStyle('C5')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->mergeCells("C5:D5");
                $sheet->getStyle('C6')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->mergeCells("C6:D6");
                if(count($datafecha)>0) {
                    $sheet->setCellValue('E6', $datafecha[0]->iYearId);
                    //$sheet->setCellValue('E4', $data[0]->Carrera);
                }
                if(count($resumen)>0) {
                    $sheet->setCellValue('E5', $resumen[0]->cTipoProyDescripcion);
                    //$sheet->setCellValue('E4', $data[0]->Carrera);
                }
                $sheet->cells('C5', function ($cells) {
                    $cells->setAlignment('left');
                });
                $sheet->cells('C6', function ($cells) {
                    $cells->setAlignment('left');
                });
////////////////////////////////////head/////////////////////////////////////////////////////////////////////////////////////
                // $resumen = json_decode(json_encode($cursos), true);

                $sheet->setCellValue('C9', 'N°');
                $sheet->setCellValue('D9', 'DOCUMENTO QUE APRUEBA');
                $sheet->setCellValue('E9', 'PROYECTOS DE INVESTIGACION');
                $sheet->setCellValue('H9', 'PRESUPUESTO');
                $sheet->setCellValue('J9', 'EJECUTADO');
                $sheet->setCellValue('K9', '%');
                $sheet->setCellValue('M9', 'ESTADO');

                $sheet->getStyle('C9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('D9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('E9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('H9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('J9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('K9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('M9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);

                $sheet->mergeCells("E9:G9");
                $sheet->mergeCells("H9:I9");
                $sheet->mergeCells("K9:L9");
                $sheet->mergeCells("M9:N9");

                $sheet->cells('C9:N9', function ($cells) {

                    $cells->setFontSize(9);
                    $cells->setAlignment('center');
                });
////////////////////////////////////body////////////////////////////////////////////
                foreach ($resumen as $key => $value) {
                    $x = ($key + 10);
                    $c = "C" . $x;
                    $d = "D" . $x;

                    $e = "E" . $x;
                    $f = "F" . $x;
                    $g = "G" . $x;

                    $h = "H" . $x;
                    $i = "I" . $x;

                    $j = "J" . $x;

                    $k = "K" . $x;
                    $l = "L" . $x;

                    $m = "M" . $x;
                    $n = "N" . $x;

                    $sheet->setCellValue($c, $key + 1);

                    $sheet->setCellValue($d, $value->cResProyecto);

                    $sheet->setCellValue($e, $value->cNombreProyecto);
                    $sheet->mergeCells($e . ":" . $g);

                    $sheet->setCellValue($h, number_format(($value->nPresupuestoProyecto), 2, '.', ''));
                    $sheet->mergeCells($h . ":" . $i);

                    $sheet->setCellValue($j, number_format(($value->nPresupuestoEjecucion), 2, '.', ''));

                    $sheet->setCellValue($k, number_format(($value->avance), 2, '.', ''));
                    $sheet->mergeCells($k . ":" . $l);

                    $sheet->setCellValue($m, $value->cEstado);
                    $sheet->mergeCells($m . ":" . $n);


                }

                $sheet->setOrientation('landscape');
            });


        })->download('xlsx');
        ////////////////////////////////////final//////////////////////////////////////////////////////////////////////////
    }
    public function trimestral_funcion($Tipo, $Anyo)
    {

        $resumen = \DB::select('exec inv.Sp_REP_anual_trimestral ?,?', array($Tipo,$Anyo));
        $datafecha = \DB::select('exec inv.Sp_SEL_yearsXiYearId ?', array($Anyo));
        Excel::create('Detalle Trimestral', function ($excel) use ($Tipo, $Anyo,$resumen,$datafecha) {

            $excel->sheet('REPORTE', function ($sheet) use ($Tipo, $Anyo,$resumen,$datafecha) {
                ////////////////////////////////////cabecera///////////////////////////////////////
                $sheet->setCellValue('B1', 'UNIVERSIDAD NACIONAL DE MOQUEGUA');
                $sheet->mergeCells("B1:K1");
                $sheet->getStyle('B1')->getFont()->setName('Tahoma')->setBold(true)->setSize(22);
                //////////////////////////////////////
                $sheet->setCellValue('C6', 'AÑO');
                $sheet->setCellValue('C5', 'TIPO DE PROYECTO');
                $sheet->cells('B1:K1', function ($cells) {
                    $cells->setAlignment('center');
                });
                $sheet->getStyle('C5')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->mergeCells("C5:D5");
                $sheet->getStyle('C6')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->mergeCells("C6:D6");
                if(count($datafecha)>0) {
                    $sheet->setCellValue('E6', $datafecha[0]->iYearId);
                    //$sheet->setCellValue('E4', $data[0]->Carrera);
                }
                if(count($resumen)>0) {
                    $sheet->setCellValue('E5', $resumen[0]->cTipoProyDescripcion);
                    //$sheet->setCellValue('E4', $data[0]->Carrera);
                }
                $sheet->cells('C5', function ($cells) {
                    $cells->setAlignment('left');
                });
                $sheet->cells('C6', function ($cells) {
                    $cells->setAlignment('left');
                });
////////////////////////////////////head/////////////////////////////////////////////////////////////////////////////////////
                // $resumen = json_decode(json_encode($cursos), true);

                $sheet->setCellValue('C9', 'N°');
                $sheet->setCellValue('D9', 'PROYECTO');
                $sheet->setCellValue('E9', 'EQUIPO DE INVESTIGACION');
                $sheet->setCellValue('H9', 'LINEA DE INVESTIGACION');
                $sheet->setCellValue('J9', 'ESCUELA');
                $sheet->setCellValue('K9', 'RESOLUCION');
                $sheet->setCellValue('M9', 'ESTADO');
                $sheet->setCellValue('O9', 'PRESUPUESTO ASIGNADO');
                $sheet->setCellValue('P9', 'PRESUPUESTO EJECUTADO');
                $sheet->setCellValue('Q9', 'PRESUPUESTO POR EJECUTAR');
                $sheet->setCellValue('R9', 'AVANCE ECONOMICO');
                $sheet->setCellValue('S9', 'AVANCE TECNICO');
                $sheet->setCellValue('T9', 'Anyo');

                $sheet->getStyle('C9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('D9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('E9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('H9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('J9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('K9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('M9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('O9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('P9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('Q9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('R9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('S9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);

                $sheet->getStyle('T9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);

                $sheet->mergeCells("E9:G9");
                $sheet->mergeCells("H9:I9");
                $sheet->mergeCells("K9:L9");
                $sheet->mergeCells("M9:N9");

                $sheet->cells('C9:T9', function ($cells) {

                    $cells->setFontSize(9);
                    $cells->setAlignment('center');
                });
////////////////////////////////////body////////////////////////////////////////////
                foreach ($resumen as $key => $value) {
                    $x = ($key + 10);
                    $c = "C" . $x;
                    $d = "D" . $x;

                    $e = "E" . $x;
                    $f = "F" . $x;
                    $g = "G" . $x;

                    $h = "H" . $x;
                    $i = "I" . $x;

                    $j = "J" . $x;

                    $k = "K" . $x;
                    $l = "L" . $x;

                    $m = "M" . $x;
                    $n = "N" . $x;
                    $o = "O" . $x;
                    $p = "P" . $x;
                    $q = "Q" . $x;
                    $r = "R" . $x;
                    $s = "S" . $x;
                    $t = "T" . $x;


                    $sheet->setCellValue($c, $key + 1);

                    $sheet->setCellValue($d, $value->cNombreProyecto);

                    $sheet->setCellValue($e, $value->equipoInv);
                    $sheet->mergeCells($e . ":" . $g);

                    $sheet->setCellValue($h, $value->cLinea);
                    $sheet->mergeCells($h . ":" . $i);

                    $sheet->setCellValue($j, $value->cCarrera);

                    $sheet->setCellValue($k, $value->cResProyecto);
                    $sheet->mergeCells($k . ":" . $l);

                    $sheet->setCellValue($m, $value->cEstado);
                    $sheet->mergeCells($m . ":" . $n);
                    $sheet->setCellValue($o, $value->nPresupuestoProyecto);
                    $sheet->setCellValue($p, $value->nPresupuestoEjecucion);
                    $sheet->setCellValue($q, $value->saldo);
                    $sheet->setCellValue($r, $value->avance);
                    $sheet->setCellValue($s, $value->nPorcAvanceTenico);
                    $sheet->setCellValue($t, $Anyo);

                }

                $sheet->setOrientation('landscape');
            });


        })->download('xlsx');

////////////////////////////////////final////////////////////////////////////////////
    }
    public function miembros_funcion($Tipo, $Anyo)
    {
        $data = \DB::select('exec inv.Sp_REP_miembros_proyecto ?,?', array($Tipo, $Anyo));
        $datafecha = \DB::select('exec inv.Sp_SEL_yearsXiYearId ?', array($Anyo));
        $p=0;
        //////////
        $z='';
        $tm='';
        $mi='';
        $co='';
        $ce='';
        $di='';
        ///////////
        $x=0;
        $cNombreProyecto='';
        $cCarrera='';
        $cLinea='';
        $cResProyecto='';
        $cEstado='';
        $cTipoMiembroDescripcion='';
        $miembro='';
        $correo='';
        $celular='';
        $direccion='';
        foreach ($data  as $key => $r)  {
            $res[]=$r->iProyectoId;
        }

        $resultado = array_unique($res);

        foreach ($resultado  as $key => $res)  {

            $z='';
            foreach ($data  as $index => $d){

                if($res == $d->iProyectoId){

                    $cNombreProyecto = $d->cNombreProyecto;
                    $cCarrera = $d->cCarrera;
                    $cLinea =$d->cLinea;
                    $cResProyecto=$d->cResProyecto;
                    $cEstado=$d->cEstado;

                    ///////////////
                    $z = $z.$d->cPersDocumento.',';
                    $tm = $tm.$d->cTipoMiembroDescripcion.'/';
                    $mi = $mi.$d->miembro.'/';
                    $co = $co.$d->correo.',';
                    $ce = $ce.$d->celular.',';
                    $di = $di.$d->direccion.',';

                }


            }
            $resumen[$p]['iProyectoId'] = $res;
            $resumen[$p]['cNombreProyecto'] = $cNombreProyecto;
            $resumen[$p]['cCarrera'] = $cCarrera;
            $resumen[$p]['cLinea'] = $cLinea;
            $resumen[$p]['cResProyecto'] = $cResProyecto;
            $resumen[$p]['cEstado'] = $cEstado;

            /////////////////////////////

            $resumen[$p]['cPersDocumento'] = $z;
            $resumen[$p]['cTipoMiembroDescripcion'] = $tm;
            $resumen[$p]['miembro'] = $mi;
            $resumen[$p]['correo'] = $co;
            $resumen[$p]['celular'] = $ce;
            $resumen[$p]['direccion'] = $di;
            $p++;

            //////////
            $z='';
            $tm='';
            $mi='';
            $co='';
            $ce='';
            $di='';
            ///////////
            $x=0;
            $cNombreProyecto='';
            $cCarrera='';
            $cLinea='';
            $cResProyecto='';
            $cEstado='';
            $cTipoMiembroDescripcion='';
            $miembro='';
            $correo='';
            $celular='';
            $direccion='';
        }
        //return $resumen;
        Excel::create('REPORTE',function($excel) use ($Tipo, $Anyo,$resumen,$datafecha)  {
            $excel->sheet("page 1", function($sheet)  use ($Tipo, $Anyo,$resumen,$datafecha) {
                $sheet->loadView("inv.prueba", [
                    'resumen' =>$resumen]);
            });
        }

        )->download('xlsx');

    }
    public function consolidado_funcion($Anyo)
    {

        $resumen = \DB::select('exec inv.Sp_REP_consolidado_item_financiable ?', array($Anyo));
        $datafecha = \DB::select('exec inv.Sp_SEL_yearsXiYearId ?', array($Anyo));
        Excel::create('Reporte de Consolidado', function ($excel) use ($Anyo,$resumen,$datafecha) {

            $excel->sheet('REPORTE', function ($sheet) use ($Anyo,$resumen,$datafecha) {
                ////////////////////////////CABECERA/////////////////////////////////////////////////
                $sheet->setCellValue('B1', 'UNIVERSIDAD NACIONAL DE MOQUEGUA');
                $sheet->mergeCells("B1:K1");
                $sheet->getStyle('B1')->getFont()->setName('Tahoma')->setBold(true)->setSize(22);
                $sheet->setCellValue('C6', 'AÑO');
                //$sheet->setCellValue('C4', 'CARRERA');
                $sheet->cells('B1:K1', function ($cells) {
                    $cells->setAlignment('center');
                });

                $sheet->getStyle('C6')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->mergeCells("C6:D6");
                if(count($datafecha)>0) {
                    $sheet->setCellValue('E6', $datafecha[0]->iYearId);
                    //$sheet->setCellValue('E4', $data[0]->Carrera);
                }
                $sheet->cells('C6', function ($cells) {
                    $cells->setAlignment('left');
                });
////////////////////////////////////head/////////////////////////////////////////////////////////////////////////////////////
                // $resumen = json_decode(json_encode($cursos), true);

                $sheet->setCellValue('C9', 'N°');
                $sheet->setCellValue('D9', 'Tipo de Proyecto');
                $sheet->setCellValue('E9', 'PASAJES Y VIATICOS');
                $sheet->setCellValue('H9', 'CONTRATOS');
                $sheet->setCellValue('J9', 'EQUIPOS');
                $sheet->setCellValue('K9', 'MATERIAL FUNGIBLE');
                $sheet->setCellValue('M9', 'PROGRAMAS INFORMATICOS Y BIBLIOGRAFIA');
                $sheet->setCellValue('O9', 'GASTOS GENERALES');
                $sheet->setCellValue('P9', 'TOTAL PRESUPUESTADO');
                $sheet->setCellValue('Q9', 'TOTAL EJECUTADO');
                $sheet->setCellValue('R9', 'TOTAL DISPONIBLE');
                $sheet->setCellValue('S9', '% AVANCE');

                $sheet->getStyle('C9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('D9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('E9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('H9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('J9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('K9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('M9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('O9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('P9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('Q9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('R9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('S9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);

                $sheet->mergeCells("E9:G9");
                $sheet->mergeCells("H9:I9");
                $sheet->mergeCells("K9:L9");
                $sheet->mergeCells("M9:N9");

                $sheet->cells('C9:S9', function ($cells) {

                    $cells->setFontSize(9);
                    $cells->setAlignment('center');
                });
////////////////////////////////////body////////////////////////////////////////////
                foreach ($resumen as $key => $value) {
                    $x = ($key + 10);
                    $c = "C" . $x;
                    $d = "D" . $x;

                    $e = "E" . $x;
                    $f = "F" . $x;
                    $g = "G" . $x;

                    $h = "H" . $x;
                    $i = "I" . $x;

                    $j = "J" . $x;

                    $k = "K" . $x;
                    $l = "L" . $x;

                    $m = "M" . $x;
                    $n = "N" . $x;
                    $o = "O" . $x;
                    $p = "P" . $x;
                    $q = "Q" . $x;
                    $r = "R" . $x;
                    $s = "S" . $x;
                    $sheet->setCellValue($c, $key + 1);

                    $sheet->setCellValue($d, $value->cTipoProyDescripcion);

                    $sheet->setCellValue($e, number_format(($value->{'PASAJES Y VIÀTICOS'}), 2, '.', ''));
                    $sheet->mergeCells($e . ":" . $g);

                    $sheet->setCellValue($h, number_format(($value->CONTRATOS), 2, '.', ''));
                    $sheet->mergeCells($h . ":" . $i);

                    $sheet->setCellValue($j, number_format(($value->EQUIPOS), 2, '.', ''));

                    $sheet->setCellValue($k, number_format(($value->{'MATERIAL FUNGIBLE'}), 2, '.', ''));
                    $sheet->mergeCells($k . ":" . $l);
                    $sheet->setCellValue($m, number_format(($value->{'PROGRAMAS INFORMÁTICOS Y BIBLIOGRAFÌA'}), 2, '.', ''));
                    $sheet->mergeCells($m . ":" . $n);
                    $sheet->setCellValue($o, number_format(($value->{'GASTOS GENERALES'}), 2, '.', ''));

                    $sheet->setCellValue($p, number_format(($value->totalPresupuesto), 2, '.', ''));
                    $sheet->setCellValue($q, number_format(($value->totalGasto), 2, '.', ''));
                    $sheet->setCellValue($r, number_format(($value->totalDisponible), 2, '.', ''));
                    $sheet->setCellValue($s, number_format(($value->avance), 2, '.', ''));


                }

                $sheet->setOrientation('landscape');
            });


        })->download('xlsx');


    }
    public function consolidado_funcion_saldos($Anyo)
    {

        $resumen = \DB::select('exec inv.Sp_REP_consolidado_item_financiable_saldos ?', array($Anyo));
        $datafecha = \DB::select('exec inv.Sp_SEL_yearsXiYearId ?', array($Anyo));
        Excel::create('Reporte de Consolidado saldos', function ($excel) use ($Anyo,$resumen,$datafecha) {

            $excel->sheet('REPORTE', function ($sheet) use ($Anyo,$resumen,$datafecha) {
                ////////////////////////////CABECERA/////////////////////////////////////////////////
                $sheet->setCellValue('B1', 'UNIVERSIDAD NACIONAL DE MOQUEGUA');
                $sheet->mergeCells("B1:K1");
                $sheet->getStyle('B1')->getFont()->setName('Tahoma')->setBold(true)->setSize(22);
                $sheet->setCellValue('C6', 'AÑO');
                //$sheet->setCellValue('C4', 'CARRERA');
                $sheet->cells('B1:K1', function ($cells) {
                    $cells->setAlignment('center');
                });

                $sheet->getStyle('C6')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->mergeCells("C6:D6");
                if(count($datafecha)>0) {
                    $sheet->setCellValue('E6', $datafecha[0]->iYearId);
                    //$sheet->setCellValue('E4', $data[0]->Carrera);
                }
                $sheet->cells('C6', function ($cells) {
                    $cells->setAlignment('left');
                });
////////////////////////////////////head/////////////////////////////////////////////////////////////////////////////////////
                // $resumen = json_decode(json_encode($cursos), true);

                $sheet->setCellValue('C9', 'N°');
                $sheet->setCellValue('D9', 'Tipo de Proyecto');
                $sheet->setCellValue('E9', 'PASAJES Y VIATICOS');
                $sheet->setCellValue('H9', 'CONTRATOS');
                $sheet->setCellValue('J9', 'EQUIPOS');
                $sheet->setCellValue('K9', 'MATERIAL FUNGIBLE');
                $sheet->setCellValue('M9', 'PROGRAMAS INFORMATICOS Y BIBLIOGRAFIA');
                $sheet->setCellValue('O9', 'GASTOS GENERALES');

                $sheet->getStyle('C9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('D9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('E9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('H9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('J9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('K9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('M9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('O9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);

                $sheet->mergeCells("E9:G9");
                $sheet->mergeCells("H9:I9");
                $sheet->mergeCells("K9:L9");
                $sheet->mergeCells("M9:N9");

                $sheet->cells('C9:O9', function ($cells) {

                    $cells->setFontSize(9);
                    $cells->setAlignment('center');
                });
////////////////////////////////////body////////////////////////////////////////////
                foreach ($resumen as $key => $value) {
                    $x = ($key + 10);
                    $c = "C" . $x;
                    $d = "D" . $x;

                    $e = "E" . $x;
                    $f = "F" . $x;
                    $g = "G" . $x;

                    $h = "H" . $x;
                    $i = "I" . $x;

                    $j = "J" . $x;

                    $k = "K" . $x;
                    $l = "L" . $x;

                    $m = "M" . $x;
                    $n = "N" . $x;
                    $o = "O" . $x;

                    $sheet->setCellValue($c, $key + 1);

                    $sheet->setCellValue($d, $value->cTipoProyDescripcion);

                    $sheet->setCellValue($e, number_format(($value->{'PASAJES Y VIÀTICOS'}), 2, '.', ''));
                    $sheet->mergeCells($e . ":" . $g);

                    $sheet->setCellValue($h, number_format(($value->CONTRATOS), 2, '.', ''));
                    $sheet->mergeCells($h . ":" . $i);

                    $sheet->setCellValue($j, number_format(($value->EQUIPOS), 2, '.', ''));

                    $sheet->setCellValue($k, number_format(($value->{'MATERIAL FUNGIBLE'}), 2, '.', ''));
                    $sheet->mergeCells($k . ":" . $l);
                    $sheet->setCellValue($m, number_format(($value->{'PROGRAMAS INFORMÁTICOS Y BIBLIOGRAFÌA'}), 2, '.', ''));
                    $sheet->mergeCells($m . ":" . $n);
                    $sheet->setCellValue($o, number_format(($value->{'GASTOS GENERALES'}), 2, '.', ''));

                }

                $sheet->setOrientation('landscape');
            });


        })->download('xlsx');


    }
    public function items_funcion($Anyo)
    {

        $resumen = \DB::select('exec inv.Sp_REP_revision_items_fianciables ?', array($Anyo));

        Excel::create('Reporte de Revision de Items Financiables', function ($excel) use ($Anyo,$resumen) {

            $excel->sheet('REPORTE', function ($sheet) use ($Anyo,$resumen) {
                //////////////////////////////////cabecera inicio///////////////////////////////////////////////////////
                $sheet->setCellValue('B1', 'UNIVERSIDAD NACIONAL DE MOQUEGUA');
                $sheet->mergeCells("B1:K1");
                $sheet->getStyle('B1')->getFont()->setName('Tahoma')->setBold(true)->setSize(22);
                $sheet->setCellValue('C3', 'AÑO');
                //$sheet->setCellValue('C4', 'CARRERA');
                $sheet->cells('B1:K1', function ($cells) {
                    $cells->setAlignment('center');
                });

                $sheet->getStyle('C3')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->mergeCells("C3:D3");
                if(count($resumen)>0) {
                    $sheet->setCellValue('E3', $resumen[0]->iYearId);
                    //$sheet->setCellValue('E4', $data[0]->Carrera);
                }
                $sheet->cells('C3', function ($cells) {
                    $cells->setAlignment('left');
                });
////////////////////////////////////cabecera fin//////////////////////////////////////////////////////////////////////////////
                // $resumen = json_decode(json_encode($cursos), true);
                $sheet->setCellValue('C9', 'N°');
                $sheet->setCellValue('D9', 'Tipo de Proyecto');
                $sheet->setCellValue('E9', 'Proyecto');
                $sheet->setCellValue('H9', 'Distribuido General');
                $sheet->setCellValue('J9', 'Distribuido ITEMS');
                $sheet->setCellValue('K9', 'Gastado');

                $sheet->getStyle('C9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('D9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('E9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('H9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('J9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('K9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);


                $sheet->mergeCells("E9:G9");
                $sheet->mergeCells("H9:I9");
                $sheet->mergeCells("K9:L9");


                $sheet->cells('C9:L9', function ($cells) {

                    $cells->setFontSize(9);
                    $cells->setAlignment('center');
                });
////////////////////////////////////body////////////////////////////////////////////
                foreach ($resumen as $key => $value) {
                    $x = ($key + 10);
                    $c = "C" . $x;
                    $d = "D" . $x;

                    $e = "E" . $x;
                    $f = "F" . $x;
                    $g = "G" . $x;

                    $h = "H" . $x;
                    $i = "I" . $x;

                    $j = "J" . $x;

                    $k = "K" . $x;
                    $l = "L" . $x;

                    $sheet->setCellValue($c, $key + 1);

                    $sheet->setCellValue($d, $value->cTipoProyDescripcion);
                    $sheet->setCellValue($e, $value->cNombreProyecto);
                    $sheet->mergeCells($e . ":" . $g);

                    $sheet->setCellValue($h, number_format(($value->nPresupuestoProyecto), 2, '.', ''));
                    $sheet->mergeCells($h . ":" . $i);

                    $sheet->setCellValue($j, number_format(($value->totalPresupuesto), 2, '.', ''));


                    $sheet->setCellValue($k, number_format(($value->totalGasto), 2, '.', ''));
                    $sheet->mergeCells($k . ":" . $l);

                }

                $sheet->setOrientation('landscape');
            });


        })->download('xlsx');
        ////////////////////////////final/////////////////////////////////////////////////////////////
    }
}
