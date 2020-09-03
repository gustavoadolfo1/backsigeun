<?php
namespace App\Http\Controllers\Tre\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Resources\Functions;
use App\Resources\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

$_param = ""; $_fn = ""; $_au = ""; 
class grl_conceptos_requisitos_reportREGDEPENController extends Controller{ 
    public function report(Request $data){
        $GLOBALS["_param"] = $data;
        $GLOBALS["_fn"] = new Functions();

        $_rec = app('App\Http\Controllers\Tre\grl_conceptos_requisitosController')->grl_conceptos_requisitos_select($data);
        $_ac = json_decode($_rec->getContent(), true);

        if ( $data->get("pType") == 33 ) {
            $_xls = new Spreadsheet();
            $_xls->getProperties()->setCreator("Sigeun")->setTitle("Registro Conceptos Ingreso");
            $_xls->getDefaultStyle()->getFont()->setSize(9)->setName("Arial Narrow");;
            $_formatNume2 = "#,#0.00;[Red]-#,#0.00"; $_formatNume4 = "#,#0.0000;[Red]-#,#0.0000"; $_formatNume6 = "#,#0.000000;[Red]-#,#0.000000";
    
            $_sheet1 = $_xls->getActiveSheet();
            $_sheet1->setTitle("Registro Conceptos Ingreso");
            $_sheet1->getDefaultRowDimension()->setRowHeight(16);
    
            $_sheet1->getColumnDimension("A")->setWidth(9);
            $_sheet1->getColumnDimension("B")->setWidth(80);
            $_sheet1->getColumnDimension("C")->setWidth(40);
            $_sheet1->getColumnDimension("D")->setWidth(23);
            $_sheet1->getColumnDimension("E")->setWidth(16);
            $_sheet1->getColumnDimension("F")->setWidth(8);
            $_sheet1->getColumnDimension("G")->setWidth(12);
            $_sheet1->getColumnDimension("H")->setWidth(14);
            $_sheet1->getColumnDimension("I")->setWidth(14);
            $_sheet1->getColumnDimension("J")->setWidth(10);
            $_sheet1->getColumnDimension("K")->setWidth(10);
            $_sheet1->getColumnDimension("L")->setWidth(10);
            $_sheet1->getColumnDimension("M")->setWidth(10);
            $_sheet1->getColumnDimension("N")->setWidth(10);
            $_sheet1->getColumnDimension("O")->setWidth(10);
            $_sheet1->getColumnDimension("P")->setWidth(10);
            $_sheet1->getColumnDimension("Q")->setWidth(16);
            $_sheet1->getColumnDimension("R")->setWidth(60);
            $_sheet1->getColumnDimension("S")->setWidth(60);
            $_cf = "S";
    
            $_sheet1->setCellValue("A1", "REGISTRO CONCEPTOS DE INGRESO x UNIDAD ORGANICA");
            $_sheet1->getStyle("A1")->getFont()->setBold(true)->setSize(11);

            $_fila = 3;
            $_xls->getDefaultStyle()->getFont()->setSize(10)->setName("Arial Narrow");
            $_sheet1->setCellValueByColumnAndRow(1, $_fila, "U. Org.");
            $_sheet1->setCellValueByColumnAndRow(2, $_fila, $data->get("DepenNombre"));

            $_fila++;
            $_sheet1->setCellValueByColumnAndRow(1, $_fila, "Clasif.");
            $_sheet1->setCellValueByColumnAndRow(2, $_fila, $data->get("EspeDetCodeName"));
            $_sheet1->getStyle("A2:A4")->getFont()->setBold(true);

            $_fila++;
            $_xls->getDefaultStyle()->getFont()->setSize(9)->setName("Arial Narrow");
            $_sheet1->setCellValueByColumnAndRow(1, $_fila, "Nro");
            $_sheet1->setCellValueByColumnAndRow(2, $_fila, "Concepto");
            $_sheet1->setCellValueByColumnAndRow(3, $_fila, "Ticket");
            $_sheet1->setCellValueByColumnAndRow(4, $_fila, "Codigo");
            $_sheet1->setCellValueByColumnAndRow(5, $_fila, "Doc. Gest.");
            $_sheet1->setCellValueByColumnAndRow(6, $_fila, "Est.");
            $_sheet1->setCellValueByColumnAndRow(7, $_fila, "% UIT");
            $_sheet1->setCellValueByColumnAndRow(8, $_fila, "Importe");
            $_sheet1->setCellValueByColumnAndRow(9, $_fila, "Impt. UIT");
            $_sheet1->setCellValueByColumnAndRow(10, $_fila, "Aprx.");
            $_sheet1->setCellValueByColumnAndRow(11, $_fila, "Dec.");
            $_sheet1->setCellValueByColumnAndRow(12, $_fila, "OE");
            $_sheet1->getComment("L".$_fila)->getText()->createTextRun("Solo Estudiantes");
            $_sheet1->setCellValueByColumnAndRow(13, $_fila, "TE");
            $_sheet1->getComment("M".$_fila)->getText()->createTextRun("Solo Trámite Estudiantes");
            $_sheet1->setCellValueByColumnAndRow(14, $_fila, "MPU");
            $_sheet1->getComment("N".$_fila)->getText()->createTextRun("Modificar Precio Unitario");
            $_sheet1->setCellValueByColumnAndRow(15, $_fila, "PPrg");
            $_sheet1->getComment("O".$_fila)->getText()->createTextRun("Imprmir Programa Profesional");
            $_sheet1->setCellValueByColumnAndRow(16, $_fila, "PUO");
            $_sheet1->getComment("P".$_fila)->getText()->createTextRun("Imprmir Unidad Orgánica");
            $_sheet1->setCellValueByColumnAndRow(17, $_fila, "Clasificador");
            $_sheet1->setCellValueByColumnAndRow(18, $_fila, "Descripción Clasificador");
            $_sheet1->setCellValueByColumnAndRow(19, $_fila, "Enlace Académico");

            $_sheet1->getStyle("A".$_fila.":".$_cf.$_fila)->getFont()->setBold(true);
            $_sheet1->getStyle("A".$_fila.":".$_cf.$_fila)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => true));
            $_sheet1->getStyle("A".$_fila.":".$_cf.$_fila)->getBorders()->applyFromArray( array( 'allBorders' => array( 'borderStyle' => Border::BORDER_THIN)));
            $_sheet1->getStyle("A".$_fila.":".$_cf.$_fila)->getFill()->applyFromArray( [ 'fillType' => Fill::FILL_GRADIENT_LINEAR, 'rotation' => 0, 'startColor' => [ 'rgb' => 'E0E0E0' ], 'endColor' => [ 'rgb' => 'E0E0E0' ] ] );
    
            $_nro = 0; $_filaIni = ($_fila+1); $_DepenId = "";
            foreach ($_ac as $row) { 
                if ( $_DepenId != $row["_DepenId"] ){
                    $_fila++;
                    $_sheet1->getStyle("A".$_fila.":S".$_fila)->getFont()->setBold(true);
                    $_sheet1->setCellValue("B".$_fila, $row["_DepenNombre"]==""?"###################":$row["_DepenNombre"]);
                }

                $_fila++; $_nro++;
                $_sheet1->getStyle("A".$_fila.":K".$_fila)->getFont()->setBold(false);
                $_sheet1->setCellValue("A".$_fila, $_nro);
                $_sheet1->setCellValue("B".$_fila, $row["cConcepReqNombrex"]);
                $_sheet1->setCellValue("C".$_fila, $row["cConcepReqNombrey"]);
                $_sheet1->setCellValue("D".$_fila, $row["cConcepReqCode"]);
                $_sheet1->setCellValue("E".$_fila, $row["cDocGestAbrev"]);
                $_sheet1->setCellValue("F".$_fila, $row["iConcepReqEstado"] == 1 ? "A" : "");
                $_sheet1->setCellValue("G".$_fila, $row["nConcepReqPuit"]);
                $_sheet1->setCellValue("H".$_fila, $row["nConcepReqImpt"]);
                $_sheet1->setCellValue("I".$_fila, $row["nConcepReqImptPuit"]);
                $_sheet1->setCellValue("J".$_fila, $row["cTipAproxImptAbrev"]);
                $_sheet1->setCellValue("K".$_fila, $row["iConcepReqDec"]);
                $_sheet1->setCellValue("L".$_fila, $row["iConcepReqOnlyEstud"] == 1 ? "A" : "");
                $_sheet1->setCellValue("M".$_fila, $row["iConcepReqOnlyEstudTram"] == 1 ? "A" : "");
                $_sheet1->setCellValue("N".$_fila, $row["iConcepReqModPreUni"] == 1 ? "A" : "");
                $_sheet1->setCellValue("O".$_fila, $row["iConcepReqPrintProg"] == 1 ? "A" : "");
                $_sheet1->setCellValue("P".$_fila, $row["iConcepReqPrintDepen"] == 1 ? "A" : "");
                $_sheet1->setCellValue("Q".$_fila, $row["cEspeDetCodigo"]);
                $_sheet1->setCellValue("R".$_fila, $row["cEspeDetNombre"]);
                $_sheet1->setCellValue("S".$_fila, $row["cConcepEnlacNombre"]);
                $_sheet1->getStyle("A".$_fila.":T".$_fila)->getFont()->getColor()->setARGB($row["iConcepReqEstado"]*1 > 0 ? Color::COLOR_BLACK : Color::COLOR_RED);

                $_DepenId = $row["_DepenId"];
            }

            $_sheet1->getStyle("F".$_filaIni.":F".$_fila)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
            $_sheet1->getStyle("L".$_filaIni.":P".$_fila)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
            $_sheet1->getStyle("G".$_filaIni.":G".$_fila)->getNumberFormat()->applyFromArray( [ 'formatCode' => $_formatNume4 ] );
            $_sheet1->getStyle("H".$_filaIni.":I".$_fila)->getNumberFormat()->applyFromArray( [ 'formatCode' => $_formatNume2 ] );
            
            header('Access-Control-Allow-Origin: *');
            header("Access-Control-Allow-Methods: GET, POST, PATCH, OPTIONS, PUT, DELETE");
            header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');
            header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="Registro Conceptos de Ingreso.xlsx"');
            $writer = IOFactory::createWriter($_xls, 'Xlsx');
            $writer->save('php://output');
            exit;
        }

        $pdf = new PDF("L", "mm", "A4", true, "UTF-8", false);
        $pdf->setFile_header("grl_conceptos_requisitos_report_regdepen_head.php");
        $pdf->SetFillColor(192, 192, 192);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->h_row = 4;  $pdf->max = 185;  $_nro = 0;  $_item = 0;  $_depen = "=";

        $pdf->fnNewPage(2500);
        foreach ($_ac as $row) { $_nro++;
            if ( $row["_DepenNombre"] != $_depen ) { $_item = 0;
                $pdf->axisY += ($pdf->h_row+($_nro==1?1:4)); $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
                $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0));
                $pdf->SetFont("helvetica", "B", 7);
                $pdf->Cell(149, $pdf->h_row, $row["_DepenNombre"]==""?str_repeat ("#",40):$row["_DepenNombre"], "B", 0, "L");
            }
            $_item++;
            $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
            $pdf->SetLineStyle(array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 4));
            if ( $row["iConcepReqEstado"]*1 == 1 ){ $pdf->SetTextColor(0, 0, 0); }else{ $pdf->SetTextColor(236, 0, 0); }
            $pdf->SetFont("helvetica", "", 6);
            $pdf->Cell(8, $pdf->h_row, $_item, "", 0, "R");
            $pdf->Cell(80, $pdf->h_row, substr($row["cConcepReqNombrex"],0,80), "", 0, "L");
            $pdf->Cell(38, $pdf->h_row, $row["cConcepReqNombrey"], "", 0, "L");
            $pdf->Cell(23, $pdf->h_row, $row["cConcepReqCode"], "", 0, "L");
            $pdf->Cell(16, $pdf->h_row, $row["cDocGestAbrev"], "", 0, "L");
            $pdf->Cell(8, $pdf->h_row, $row["iConcepReqEstado"] == 1 ? "A" : "", "", 0, "C");
            $pdf->Cell(12, $pdf->h_row, $GLOBALS["_fn"]->fnNumFormat($row["nConcepReqPuit"],4), "", 0, "R");
            $pdf->Cell(15, $pdf->h_row, $GLOBALS["_fn"]->fnNumFormat($row["nConcepReqImpt"]), "", 0, "R");
            $pdf->Cell(15, $pdf->h_row, $GLOBALS["_fn"]->fnNumFormat($row["nConcepReqImptPuit"]), "", 0, "R");
            $pdf->Cell(10, $pdf->h_row, $row["cTipAproxImptAbrev"], "", 0, "L");
            $pdf->Cell(8, $pdf->h_row, $GLOBALS["_fn"]->fnNumFormat($row["iConcepReqDec"],0), "", 0, "C");
            $pdf->Cell(8, $pdf->h_row, $row["iConcepReqOnlyEstud"] == 1 ? "A" : "", "", 0, "C");
            $pdf->Cell(8, $pdf->h_row, $row["iConcepReqOnlyEstudTram"] == 1 ? "A" : "", "", 0, "C");
            $pdf->Cell(8, $pdf->h_row, $row["iConcepReqModPreUni"] == 1 ? "A" : "", "", 0, "C");
            $pdf->Cell(8, $pdf->h_row, $row["iConcepReqPrintProg"] == 1 ? "A" : "", "", 0, "C");
            $pdf->Cell(8, $pdf->h_row, $row["iConcepReqPrintDepen"] == 1 ? "A" : "", "", 0, "C");

            $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
    		$pdf->Cell(8, $pdf->h_row, "", "B", 0, "R");
            $pdf->Cell(118, $pdf->h_row, "", "B", 0, "L");
            $pdf->Cell(18, $pdf->h_row, $row["cEspeDetCodigo"], "B", 0, "L");
            $pdf->Cell(81, $pdf->h_row, $row["cEspeDetNombre"], "B", 0, "L");
            $pdf->Cell(47, $pdf->h_row, substr($row["cConcepEnlacNombre"],0,40), "B", 0, "L");

            $_depen = $row["_DepenNombre"];
        }

        //header('Content-type: application/pdf'); header('Content-Disposition: attachment; filename="ingresos_IC.pdf"');
        return $pdf->Output('ingresos_IC.pdf', 'S');
    }
}