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

$_param = ""; $_to = "";
class tre_ingresos_reportREGOPERController extends Controller{
    public function report(Request $data){
        //header("Access-Control-Allow-Origin: *");
        $GLOBALS["_param"] = $data;
        $GLOBALS["_fn"] = new Functions();

        $_rec = app('App\Http\Controllers\Tre\tre_operacionesController')->tre_operaciones_select($data, Array("OperKey"=>$data->get("OperKey"),"TypeRecord"=>"headRep","RecordLimit"=>1));
        $GLOBALS["_to"] = json_decode($_rec->getContent(), true);

        $_rec = app('App\Http\Controllers\Tre\tre_ingresosController')->tre_ingresos_select($data);
        $_ac = json_decode($_rec->getContent(), true);

        if ( $data->get("pType") == 33 ) {
            $_xls = new Spreadsheet();
            $_xls->getProperties()->setCreator("Sigeun")->setTitle("Registro Documentos");
            $_xls->getDefaultStyle()->getFont()->setSize(9)->setName("Arial Narrow");;
            $_formatNume2 = "#,#0.00;[Red]-#,#0.00"; $_formatNume3 = "#,#0.000;[Red]-#,#0.000"; $_formatNume6 = "#,#0.000000;[Red]-#,#0.000000";
    
            $_sheet1 = $_xls->getActiveSheet();
            $_sheet1->setTitle("Registro Documentos");
            $_sheet1->getDefaultRowDimension()->setRowHeight(15);
    
            $_sheet1->getColumnDimension("A")->setWidth(9);
            $_sheet1->getColumnDimension("B")->setWidth(17);
            $_sheet1->getColumnDimension("C")->setWidth(14);
            $_sheet1->getColumnDimension("D")->setWidth(10);
            $_sheet1->getColumnDimension("E")->setWidth(13);
            $_sheet1->getColumnDimension("F")->setWidth(60);
            $_sheet1->getColumnDimension("G")->setWidth(14);
            $_sheet1->getColumnDimension("H")->setWidth(10);
            $_sheet1->getColumnDimension("I")->setWidth(10);
            $_sheet1->getColumnDimension("J")->setWidth(14);
            $_sheet1->getColumnDimension("K")->setWidth(16);
            $_cf = "K";
    
            $_sheet1->setCellValue("A1", "REGISTRO DOCUMENTOS SEGUN OPERACION");
            $_sheet1->getStyle("A1")->getFont()->setBold(true)->setSize(11);

            $_subTitFecha = "Fecha"; $_periodo = "";
            $_FechaIni = $GLOBALS["_fn"]->fnDateDDMMAAAA($data->get("FechaIni"));
            $_FechaFin = $GLOBALS["_fn"]->fnDateDDMMAAAA($data->get("FechaFin"));
            if ( $_FechaIni != "" && $_FechaFin != "" ){ if ($_FechaIni==$_FechaFin){ $_periodo = $_FechaIni; }else{ $_periodo = $_FechaIni ." al ". $_FechaFin; $_subTitFecha = "Periodo"; } }
            else if ( $_FechaIni != "" ){ $_periodo = "Desde el ".$_FechaIni; $_subTitFecha = "Periodo"; }
            else if ( $_FechaFin !="" ){ $_periodo = "Hasta el ".$_FechaFin; $_subTitFecha = "Periodo"; }
    
            $_fila = 3;
            $_xls->getDefaultStyle()->getFont()->setSize(10)->setName("Arial Narrow");
            $_sheet1->setCellValueByColumnAndRow(1, $_fila, $_subTitFecha);
            $_sheet1->setCellValueByColumnAndRow(2, $_fila, $_periodo);

            $_fila++;
            $_sheet1->setCellValueByColumnAndRow(1, $_fila, "Cajero");
            $_sheet1->setCellValueByColumnAndRow(2, $_fila, $GLOBALS["_to"][0]["cPersDocumento"] ." - ".$GLOBALS["_to"][0]["cPersApeNom"] );
            
            $_fila++;
            $_sheet1->setCellValueByColumnAndRow(1, $_fila, "U. Org.");
            $_sheet1->setCellValueByColumnAndRow(2, $_fila, $GLOBALS["_to"][0]["cDepenNombre"]);
            $_sheet1->getStyle("A2:A6")->getFont()->setBold(true);
            //$_sheet1->getStyle("E2:E4")->getFont()->setBold(true);
    
            $_fila++; $_col = 2;
            $_xls->getDefaultStyle()->getFont()->setSize(9)->setName("Arial Narrow");
            $_sheet1->setCellValueByColumnAndRow(1, $_fila, "Nro");
            $_sheet1->setCellValueByColumnAndRow(2, $_fila, "Documento");
            $_sheet1->setCellValueByColumnAndRow(3, $_fila, "Doc. Ident.");
            $_sheet1->setCellValueByColumnAndRow(4, $_fila, "Apellidos y Nombre");
            $_sheet1->setCellValueByColumnAndRow(5, $_fila, "Cod. Univ.");
            $_sheet1->setCellValueByColumnAndRow(6, $_fila, "Ciclo");
            $_sheet1->setCellValueByColumnAndRow(7, $_fila, "Ciclo");
            $_sheet1->setCellValueByColumnAndRow(8, $_fila, "TP");
            $_sheet1->setCellValueByColumnAndRow(9, $_fila, "Nro Oper.");
            $_sheet1->setCellValueByColumnAndRow(10, $_fila, "Importe");

            $_sheet1->getStyle("A".$_fila.":".$_cf.$_fila)->getFont()->setBold(true);
            $_sheet1->getStyle("A".$_fila.":".$_cf.$_fila)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => true));
            $_sheet1->getStyle("A".$_fila.":".$_cf.$_fila)->getBorders()->applyFromArray( array( 'allBorders' => array( 'borderStyle' => Border::BORDER_THIN)));
            $_sheet1->getStyle("A".$_fila.":".$_cf.$_fila)->getFill()->applyFromArray( [ 'fillType' => Fill::FILL_GRADIENT_LINEAR, 'rotation' => 0, 'startColor' => [ 'rgb' => 'E0E0E0' ], 'endColor' => [ 'rgb' => 'E0E0E0' ] ] );
    
            $_nro = 0; $_filaIni = ($_fila+1);
            foreach ($_ac as $row) { $_fila++; $_nro++;
                $_sheet1->setCellValue("A".$_fila, $_nro); // PHPExcel_Cell_DataType::TYPE_NUMERIC
                $_sheet1->setCellValue("B".$_fila, $row["cIngDocument"]); // $row["cIngDocument"]
                $_sheet1->setCellValueExplicit("C".$_fila, $row["cPersDocumento"], DataType::TYPE_STRING);
                $_sheet1->setCellValue("D".$_fila, $row["cPersApeNom"]);
                $_sheet1->setCellValue("E".$_fila, $row["cEstudCodUniv"]);
                $_sheet1->setCellValue("F".$_fila, "");
                $_sheet1->setCellValue("G".$_fila, "");
                $_sheet1->setCellValue("H".$_fila, "");
                $_sheet1->setCellValue("I".$_fila, $row["nIngImpt"]);
                $_sheet1->getStyle("A".$_fila.":I".$_fila)->getFont()->getColor()->setARGB($row["nIngImpt"]*1 > 0 ? Color::COLOR_BLACK : Color::COLOR_RED);
            }

            $_fila++;
            $_sheet1->setCellValue("H".$_fila, "TOTAL IMPORTE ");
            $_sheet1->setCellValue("I".$_fila, "=SUM(I".$_filaIni.":I".($_fila-1).")");

            $_sheet1->getStyle("A".$_fila.":I".$_fila)->getFont()->setBold(true);
            $_sheet1->getStyle("A".$_fila.":I".$_fila)->getBorders()->applyFromArray( array( 'top' => array( 'borderStyle' => Border::BORDER_THIN)));
            $_sheet1->getStyle("I".$_fila.":I".$_fila)->getBorders()->applyFromArray( array( 'allBorders' => array( 'borderStyle' => Border::BORDER_THIN)));
            //$_sheet1->getStyle("C".$_filaIni.":C".$_fila)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'textRotation' => 0, 'wrapText' => true));
            $_sheet1->getStyle("H".$_fila.":".$_cf.$_fila)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT, 'textRotation' => 0, 'wrapText' => true));
            $_sheet1->getStyle("K".$_filaIni.":I".$_fila)->getNumberFormat()->applyFromArray( [ 'formatCode' => $_formatNume2 ] );
            $_sheet1->getStyle("K".$_fila.":I".$_fila)->getFill()->applyFromArray( [ 'fillType' => Fill::FILL_GRADIENT_LINEAR, 'rotation' => 0, 'startColor' => [ 'rgb' => 'E0E0E0' ], 'endColor' => [ 'rgb' => 'E0E0E0' ] ] );
            
            $writer = IOFactory::createWriter($_xls, 'Xlsx');
            //$writer->save('registro_documentos.xlsx');
            $writer->save('php://output');    
            exit;
        }

        $pdf = new PDF("P", "mm", "A4", true, "UTF-8", false);
        $pdf->setFile_header("tre_ingresos_report_regoper_head.php");
        $pdf->SetFillColor(192, 192, 192);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->h_row = 5;  $pdf->max = 270;  $_nro = 0;  $_item = 0;  $_impt = 0;

        $pdf->fnNewPage(2500);  $pdf->SetFont("helvetica", "", 6);
        foreach ($_ac as $row) { 
            $pdf->axisY += ($pdf->h_row + ( $_nro == "0" ? 1 : 0)); $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX ); $_nro++;
            if ( $row["nIngImpt"]*1 > 0 ){ $pdf->SetTextColor(0, 0, 0); }else{ $pdf->SetTextColor(236, 0, 0); }
    		$pdf->Cell(8, $pdf->h_row, $_nro, 1, 0, "R");
    		$pdf->Cell(21, $pdf->h_row, $row["cIngDocument"], 1, 0, "L");
            $pdf->Cell(15, $pdf->h_row, $row["cPersDocumento"], 1, 0, "L");
            $pdf->Cell(55, $pdf->h_row, substr($row["cPersApeNom"],0,35), 1, 0, "L");
            $pdf->Cell(16, $pdf->h_row, $row["cEstudCodUniv"], 1, 0, "L");
            $pdf->Cell(10, $pdf->h_row, "", 1, 0, "L");
            $pdf->Cell(28, $pdf->h_row, "", 1, 0, "L");
            $pdf->Cell(16, $pdf->h_row, "", 1, 0, "L");
		    $pdf->Cell(16, $pdf->h_row, $GLOBALS["_fn"]->fnNumFormat($row["nIngImpt"]), 1, 0, "R");
            $_impt += $row["nIngImpt"];
        }

    	$pdf->axisY += $pdf->h_row + ( $_nro == "0" ? 1 : 0);  $pdf->fnNewPage();  $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX ); $_nro++;
	    $pdf->SetFont("helvetica", "B", 6);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(169, $pdf->h_row, "TOTAL  IMPORTE  ", "TR", 0, "R");
	    $pdf->Cell(16, $pdf->h_row, $GLOBALS["_fn"]->fnNumFormat($_impt*1), 1, 0, "R", 1);

        header('Content-type: application/pdf'); header('Content-Disposition: attachment; filename="ingresos_IC.pdf"');
        return $pdf->Output('ingresos_IC.pdf', 'S');
    }
}