<?php

namespace App\Http\Controllers\Reporte;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TCPDF;
class MYPDF extends TCPDF {
    public $funcion_g;
    public function setFunction($f)
    {
        $this->funcion_g = $f;
    }
    public function Header() {
        switch ($this->funcion_g) {
            case 1:
                $titulo = 'Reporte de Clasificadores';
                break;
            case 2:
                $titulo = 'Reporte de Conceptos';
                break;
            case 3:
                $titulo = 'Reporte de Conceptos Requisitos';
                break;
            default:
                /*$data = app('App\Http\Controllers\Conceptos\ConceptoPrincipalController')->getConceptos($this->funcion_g,' ',' ',' ',' ',' ',' ',' ',0,' ',' ',' ',' ','grd');
                $json = json_decode($data->getContent(), TRUE);
                $titulo = $json[0]['cConcepNombre'];*/
                //$image_file = K_PATH_IMAGES.'logo.png';
                //$this->Image($image_file, 15, 10, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
                $this->SetFont('helvetica', 'B', 14);
                $this->Cell(0, 0, 'Universidad Nacional de Moquegua', 0, false, 'C', 0, '', 0, false, 'M', 'M');
                break;
        }
        if(isset($titulo)){
            $image_file = K_PATH_IMAGES.'logo.png';
            $this->Image($image_file, 15, 10, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            $this->SetFont('helvetica', 'B', 20);
            $this->Cell(0, 15, $titulo, 0, false, 'C', 0, '', 0, false, 'M', 'M');
        }
        

    }
    // Colored table
    public function ColoredTable($header,$data,$funcion) {
        // Colors, line width and bold font
        $this->SetFillColor(47, 71, 194);
        $this->SetTextColor(255);
        $this->SetDrawColor(47, 71, 194);
        $this->SetLineWidth(0.3);
        $this->SetFont('', '', 10);
        // Header
        $w = array(15, 105, 25, 35);
        $num_headers = count($header);
        for($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        }
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
        $fill = 0;
        foreach($data as $k => $v) {
            switch ($funcion) {
                case 1:
                    $this->Cell($w[0], 6, $v['iEspeDetId'], 'LR', 0, 'C', $fill);
                    $this->Cell($w[1], 6, $v['cEspeDetNombre'], 'LR', 0, 'L', $fill);
                    $this->Cell($w[2], 6, $v['cEspeDetSiaf'], 'LR', 0, 'C', $fill);
                    $this->Cell($w[3], 6, $v['cEspeDetCodigo'], 'LR', 0, 'C', $fill);
                    break;
                case 2:
                    $this->Cell($w[0], 6, $v['iConcepId'], 'LR', 0, 'C', $fill);
                    $this->Cell($w[1], 6, $v['cConcepNombre'], 'LR', 0, 'L', $fill);
                    $this->Cell($w[2], 6, $v['cConcepCodigo'], 'LR', 0, 'C', $fill);
                    $this->Cell($w[3], 6, $v['cDocGestAbrev'], 'LR', 0, 'C', $fill);
                    break;
                case 3:
                    $this->Cell($w[0], 6, $v['iConcepReqId'], 'LR', 0, 'C', $fill);
                    $this->Cell($w[1], 6, $v['cConcepReqNombre'], 'LR', 0, 'L', $fill);
                    $this->Cell($w[2], 6, $v['nConcepReqOrden'], 'LR', 0, 'C', $fill);
                    $this->Cell($w[3], 6, $v['cTipConcepReqAbrev'], 'LR', 0, 'C', $fill);
                    break;
            }
            $this->Ln();
            $fill=!$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
    }
    public function CrearTabla($header,$data) {
        // Colors, line width and bold font
        $this->SetFillColor(47, 71, 194);
        $this->SetTextColor(255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', '', 10);
        // Header
        $w = array(15, 105, 25, 35);
        $num_headers = count($header);
        /*for($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        }*/
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
        $fill = 0;
        $acumulado='';
        foreach ($header as $x => $y) {
            $this->MultiCell(35, 23, $y, 1, 'C', 1, 0, '', '', true, 0, false, true, 23, 'M');
            foreach($data as $k => $v) {
                if($x==$v['cTipConcepReqAbrev']){
                    $acumulado.=$v['cConcepReqNombre'].chr(10);
                }
            }
            $this->MultiCell(145, 23, $acumulado, 1, 'C', 1, 0, '', '', true, 0, false, true, 23, 'M');
            $acumulado='';
            $this->Ln();
            $fill=!$fill;
            
        }
        
        $this->Cell(array_sum($w), 0, '', 'T');
    }
    public function EncabezadoRequisito($header,$data) {
        // Colors, line width and bold font
        $this->SetFillColor(47, 71, 194);
        $this->SetTextColor(255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', '', 10);
        // Header
        $w = array(35, 100, 45);
        $num_headers = count($header);
        for($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        }
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
        $fill = 0;
        foreach($data as $k => $v) {
            $this->MultiCell(35, 15, '', 1, 'C', 1, 0, '', '', true, 0, false, true, 15, 'M');
            $this->MultiCell(100, 15, $v['cConcepNombre'], 1, 'C', 1, 0, '', '', true, 0, false, true, 15, 'M');
            $this->MultiCell(45, 15, $v['cConcepCodigo'], 1, 'C', 1, 0, '', '', true, 0, false, true, 15, 'M');
            $this->Ln();
            $fill=!$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}

class ReporteController extends Controller
{
    public function LoadData($f) {
        switch ($f) {
            case 1:
                $data = app('App\Http\Controllers\Clasificador\ClasificadorPrincipalController')->getClasificadores('','','','','','','','','','','','','','','','','','','','','','','','','','','','grdTCIBED');
                $json = json_decode($data->getContent(), TRUE);
                break;
            case 2:
                $data = app('App\Http\Controllers\Conceptos\ConceptoPrincipalController')->getConceptos('','','','','','','','','','','','','','grd');
                $json = json_decode($data->getContent(), TRUE);
                break;
            case 3:
                $data = app('App\Http\Controllers\Conceptos\ConceptoPrincipalController')->getConceptosRequisitos(' ',' ',10026,' ',' ',' ',' ',' ',0,' ',' ',' ',' ',' ',' ',' ',' ',0,'grdTCB');
                $json = json_decode($data->getContent(), TRUE);
                break;
        }
        return $json;
    }
    public function CargarDatos($f) {
        $data = app('App\Http\Controllers\Conceptos\ConceptoPrincipalController')->getConceptosRequisitos(' ',' ',$f,' ',' ',' ',' ',' ',0,' ',' ',' ',' ',' ',' ',' ',' ',0,'grdTCB');
        $json = json_decode($data->getContent(), TRUE);
        return $json;
    }
    public function CargarDatos2($f) {
        $data = app('App\Http\Controllers\Conceptos\ConceptoPrincipalController')->getConceptos($f,' ',' ',' ',' ',' ',' ',' ',0,' ',' ',' ',' ','grd');
        $json = json_decode($data->getContent(), TRUE);
        return $json;
    }
    public function getReporte($funcion){
        
        // create new PDF document
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setFunction($funcion);
        $pdf->Header();
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Nicola Asuni');
        $pdf->SetTitle('Reporte de Clasificadores');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
        
        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Reporte de Clasificadores', PDF_HEADER_STRING);
        
        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        
        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        
        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        
        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        
        // ---------------------------------------------------------
        
        // set font
        $pdf->SetFont('helvetica', '', 12);
        
        // add a page
        $pdf->AddPage();
        
        // column titles
        $header = array('ID', 'Descripcion', 'SIAF', 'Clasificador');
        
        // data loading
        $data = $this->LoadData($funcion);
        
        // print colored table
        $pdf->ColoredTable($header, $data,$funcion);
        ob_end_clean();
        // ---------------------------------------------------------
        
        // close and output PDF document
        $pdf->Output('example_011.pdf', 'I');
        
        //============================================================+
        // END OF FILE
        //============================================================+ 
    }

    public function getReporteRequisito($funcion){
        
        // create new PDF document
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setFunction($funcion);
        $pdf->Header();
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Nicola Asuni');
        $pdf->SetTitle('Reporte de Clasificadores');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
        
        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Reporte de Clasificadores', PDF_HEADER_STRING);
        
        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        
        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        
        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        
        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        
        // ---------------------------------------------------------
        
        // set font
        $pdf->SetFont('helvetica', '', 12);
        
        // add a page
        $pdf->AddPage();
        
        // column titles
        $header = array('FIN'=>'Finalidad', 'BLG'=>'Base Legal', 'CAL'=>'Calificación', 'REQ'=>'Requisitos', 'IMP'=>'Importe','ALC'=>'Alcance','INS'=>'Instrucciones','ACT'=>'Actividades','PLA'=>'Plazo de Atención');
        $header2 = array('Logo','Denominación del Procedimiento','Código');
        // data loading
        $data = $this->CargarDatos($funcion);
        $data2 = $this->CargarDatos2($funcion);
        $pdf->EncabezadoRequisito($header2, $data2);
        // print colored table
        $pdf->CrearTabla($header, $data);
        ob_end_clean();
        // ---------------------------------------------------------
        
        // close and output PDF document
        $pdf->Output('example_011.pdf', 'I');
        
        //============================================================+
        // END OF FILE
        //============================================================+ 
    }
}