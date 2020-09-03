<?php
namespace App\Http\Controllers\Tre\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Resources\Functions;
use App\Resources\Pdf;
//use TCPDF;

class tre_ingresos_reportREGPRINTController extends Controller{ 
    public $_impt = 0; public $_cajero = ""; public $_qr = ""; 

    public function report(Request $data){
        $_rec = app('App\Http\Controllers\Tre\tre_ingresosController')->tre_ingresos_select($data);
        $_ac = json_decode($_rec->getContent(), TRUE);
    
        $_fn = new Functions();
        $pdf = new PDF("L", "mm", "A4", true, "UTF-8", false);
        $pdf->setPrinter_header(0);
        $pdf->SetFillColor(192, 192, 192);
        $pdf->SetAutoPageBreak(true, 52);
        $pdf->SetFooterMargin(0);
        $pdf->h_row = 5;  $pdf->max = 240;
        $_nro = 0;  $_IngId = "";

        foreach ($_ac as $row) {
            if ( $row["iIngId"] != $_IngId ) { $_nro++;
                if ( $_nro > 1 ) { $this->printer_total($pdf, $_fn); }
                
                $_nro = ( $_nro == 5 ? 1 : $_nro );
                if ( $_nro == 1 ) { $_xLogo = 25;
                    $pdf->fnNewPage(2500);
                    $pdf->axisX = 6;
                } else {
                    $pdf->axisX = $pdf->axisX + 71;
                    $_xLogo = $_xLogo + 71;
                }

                if ( $row["nIngImpt"]*1 > 0 ){ 
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetLineStyle(array('cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
                }else{ 
                    $pdf->SetTextColor(236, 0, 0); 
                    $pdf->SetLineStyle(array('cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(236, 0, 0)));
                }
                $pdf->Image("../resources/images/escudox.jpg", $_xLogo, 15, 34, 25);
                
                $pdf->SetFont("helvetica", "B", 8);
                $pdf->axisY = 45;
                $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
                $pdf->Cell(69, 4, $row["cUniEjeNombre"], 0, 0, "C");

                $pdf->axisY += 4; $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
                $pdf->Cell(69, 4, $row["cFilNombre"], 0, 0, "C");

                $pdf->axisY += 4; $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
                $pdf->Cell(69, 4, $row["cFilDomi"], 0, 0, "C");

                $pdf->axisY += 10; $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
                $pdf->SetFont("helvetica", "B", 9);
                $pdf->Cell(69, 4, $row["cDocNombre"], 0, 0, "C");

                $pdf->axisY += 5; $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
                $pdf->SetFont("helvetica", "B", 9);
                $pdf->Cell(69, 4, $row["cIngDocument"], 0, 0, "C");

                $pdf->axisY += 6; $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
                $pdf->SetFont("helvetica", "B", 8);
                $pdf->Cell(69, 4, "Fecha: ".$_fn->fnDateDDMMAAAA($row["dDocFecha"]), 0, 0, "R");

                $pdf->axisY += 10; $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
                $pdf->Cell(69, 4, $row["cEstudCodUniv"], 0, 0, "L");
                
                $pdf->axisY += 4; $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
                $pdf->Cell(69, 4, substr($row["cPersApeNom"], 0, 35), 0, 0, "L");

                $pdf->axisY += 5; $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
                $pdf->Cell(9, 5, "Cant.", 1, 0, "C", 1);
                $pdf->Cell(45, 5, "Concepto", 1, 0, "C", 1);
                $pdf->Cell(15, 5, "Importe", 1, 0, "C", 1);
            }

            $pdf->axisY += 5; $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
            $pdf->SetFont("helvetica", "", 6);
            $pdf->Cell(9, 5, $_fn->fnNumFormat($row["nIngDetCantid"],0)." ", 0, 0, "R");
            $pdf->Cell(45, 5, $row["cConcepReqNombrex"], 0, 0, "L");
            $pdf->Cell(15, 5, $_fn->fnNumFormat($row["nIngDetImpt"]), 0, 0, "R");
            
            $_IngId = $row["iIngId"]; $this->_impt = $row["nIngImpt"]; $this->_cajero = $row["cCredDepen"]; $this->_qr = $row["cIngKey"];
        }
        $this->printer_total($pdf, $_fn);
    
        //header('Content-type: application/pdf'); 
        return $pdf->Output('documentos_ingreso.pdf', 'S');
    }

    public function printer_total($_p, $_f){
        $_p->axisY += 6; $_p->fnSetAxes( $_p->axisY, $_p->axisX );
        $_p->SetFont("helvetica", "B", 6);
        $_p->Cell(9, 4, "", 0, 0, "R");
        $_p->Cell(45, 4, "TOTAL: ", "T", 0, "R");
        $_p->Cell(15, 4, $_f->fnNumFormat($this->_impt), "T", 0, "R");

        $_p->axisY += 10; $_p->fnSetAxes( $_p->axisY, $_p->axisX );
        $_p->Cell(69, 4, "UNIVERSITAS UNIVERSITATIS", 0, 0, "C");

        $_p->axisY += 4; $_p->fnSetAxes( $_p->axisY, $_p->axisX );
        $_p->Cell(69, 4, "Estudia, Investiga y Desarrolla", 0, 0, "C");

        $_p->axisY += 6; $_p->fnSetAxes( $_p->axisY, $_p->axisX );
        $_p->SetFont("helvetica", "B", 6);
        $_p->Cell(9, 4, "  Cajero:", 0, 0, "L");
        $_p->SetFont("helvetica", "", 6);
        $_p->Cell(60, 4, $this->_cajero, 0, 0, "L");

        $_p->SetMargins(0,0, 0);
        $style = array(
            'border' => "",
            'vpadding' => 0,
            'hpadding' => 0,
            'fgcolor' => $this->_impt*1 > 0 ? array(0,0,0) : array(255,0,0),
            'bgcolor' => array(255,255,255), //false, //
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        //$_p->write2DBarcode($this->_qr, 'QRCODE,L', $_p->axisX+25, ($_p->axisY*1)+5, 25, 25, $style, "");
    }
}