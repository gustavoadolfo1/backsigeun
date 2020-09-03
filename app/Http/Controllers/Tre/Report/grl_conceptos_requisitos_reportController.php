<?php
namespace App\Http\Controllers\Tre\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Resources\Functions;
use App\Resources\Pdf;
//use TCPDF;

class grl_conceptos_requisitos_reportController extends Controller{ 
    public $_ac = "";
   
    //$GLOBALS["_ac"]; 

    public function report(Request $data){ global $_ac;
        $_cab = app('App\Http\Controllers\Tre\grl_conceptosController')->grl_conceptos_select($data);
        $_det = app('App\Http\Controllers\Tre\grl_conceptos_requisitosController')->grl_conceptos_requisitos_select($data);
        $_ac = json_decode($_cab->getContent(), TRUE);
        $_ad = json_decode($_det->getContent(), TRUE);
    
        $_fn = new Functions();
        $pdf = new PDF("P", "mm", "A4", true, "UTF-8", false);
        $pdf->setPrinter_header(0);
        $pdf->setFile_header("grl_conceptos_requisitos_report_head.php");
        $pdf->SetFillColor(192, 192, 192);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetAutoPageBreak(true, 52);
        $pdf->h_row = 6;  $pdf->max = 240;  $_nro = 0;  $_item = 0;  $_impt = 0; 
        //$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        $pdf->fnNewPage(2500); $pdf->SetFont("helvetica", "", 8); $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont("helvetica", "", 8);
        foreach ($_ad as $row) { 
            $pdf->axisY += ($pdf->h_row + ( $_nro == 0 ? 1 : 0)); $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX ); $_nro++;
            
    		$pdf->Cell(40, $pdf->h_row, $row["cTipConcepReqNombre"], 1, 0, "L");
            $pdf->Cell(140, $pdf->h_row, $row["cConcepReqNombre"], 1, 0, "L");
        }

    	//$pdf->axisY += $pdf->h_row + ( $_nro == "0" ? 1 : 0);  $pdf->fnNewPage();  $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX ); $_nro++;
	    //$pdf->SetFont("helvetica", "B", 6);
	    //$pdf->Cell(167, $pdf->h_row, "TOTAL  IMPORTE  ", "TR", 0, "R");
	    //$pdf->Cell(16, $pdf->h_row, $_fn->fnNumFormat($_impt*1), 1, 0, "R", 1);

        header("Content-type: application/pdf"); header("Content-Disposition: attachment; filename='concepto_requisitos.pdf'");
        return $pdf->Output("concepto_requisitos.pdf", "S");
    }
}