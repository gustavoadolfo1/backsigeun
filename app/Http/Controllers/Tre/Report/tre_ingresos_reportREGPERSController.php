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

$_param = ""; $_au = ""; $_ape = "";
class tre_ingresos_reportREGPERSController extends Controller{ 
    public function report(Request $data){
        $GLOBALS["_param"] = $data;
        $GLOBALS["_fn"] = new Functions();

        if ( $data->get("IngCredDepenKey") != "" ) {
            $_rec = app("App\Http\Controllers\Tre\seg_credenciales_dependenciasController")->seg_credenciales_dependencias_select($data, Array("CredDepenKey"=>$data->get("IngCredDepenKey"),"TypeRecord"=>"headApeNom"));
            $GLOBALS["_au"] = json_decode($_rec->getContent(), true);
        }

        $_rec = app('App\Http\Controllers\Tre\grl_personasController')->grl_personas_select($data,Array("PersId"=>$data->get("PersId"),"TypeRecord"=>"win","RecordLimit"=>1));
        $GLOBALS["_ape"] = json_decode($_rec->getContent(), true);

        $_rec = app('App\Http\Controllers\Tre\tre_ingresosController')->tre_ingresos_select($data);
        $_ac = json_decode($_rec->getContent(), true);

        if ( $data->get("pType") == 33 ) {
            $_xls = new Spreadsheet();
            $_xls->getProperties()->setCreator("Sigeun")->setTitle("Registro Documentos");
            $_xls->getDefaultStyle()->getFont()->setSize(9)->setName("Arial Narrow");
            $_formatNume2 = "#,#0.00;[Red]-#,#0.00"; $_formatNume3 = "#,#0.000;[Red]-#,#0.000"; $_formatNume4 = "#,#0.0000;[Red]-#,#0.0000";
    
            $_sheet1 = $_xls->getActiveSheet();
            $_sheet1->setTitle("Registros x Concepto");
            $_sheet1->getDefaultRowDimension()->setRowHeight(15);
            
            $_sheet1->getColumnDimension("A")->setWidth(9);
            $_sheet1->getColumnDimension("B")->setWidth(17);
            $_sheet1->getColumnDimension("C")->setWidth(14);
            $_sheet1->getColumnDimension("D")->setWidth(10);
            $_sheet1->getColumnDimension("E")->setWidth(16);
            $_sheet1->getColumnDimension("F")->setWidth(60);
            $_sheet1->getColumnDimension("G")->setWidth(16);
            $_cf = "G";
    
            $_sheet1->setCellValue("A1", "REGISTRO DE INGRESOS SEGUN PERSONA");
            $_sheet1->getStyle("A1")->getFont()->setBold(true)->setSize(11);

            $_subTitFecha = "Fecha"; $_periodo = "";
            $_FechaIni = $GLOBALS["_fn"]->fnDateDDMMAAAA($data->get("FechaIni"));
            $_FechaFin = $GLOBALS["_fn"]->fnDateDDMMAAAA($data->get("FechaFin"));
            if ( $_FechaIni != "" && $_FechaFin != "" ){ if ($_FechaIni==$_FechaFin){ $_periodo = $_FechaIni; }else{ $_periodo = $_FechaIni ." al ". $_FechaFin; $_subTitFecha = "Periodo"; } }
            else if ( $_FechaIni != "" ){ $_periodo = "Desde el ".$_FechaIni; $_subTitFecha = "Periodo"; }
            else if ( $_FechaFin !="" ){ $_periodo = "Hasta el ".$_FechaFin; $_subTitFecha = "Periodo"; }
    
            $_fila = 3;
            $_xls->getDefaultStyle()->getFont()->setSize(10)->setName("Arial Narrow");
            $_sheet1->setCellValueByColumnAndRow(1, $_fila, "Persona");
            $_sheet1->setCellValueByColumnAndRow(2, $_fila, isset($GLOBALS["_ape"][0]["cPersDocumento"]) ? $GLOBALS["_ape"][0]["cPersDocumento"] ." - ".$GLOBALS["_ape"][0]["cPersApeNom"] : "" );
            
            $_fila++;
            $_sheet1->setCellValueByColumnAndRow(1, $_fila, "Sede");
            $_sheet1->setCellValueByColumnAndRow(2, $_fila, $data->get("FilNombre"));
            
            $_fila++;
            $_sheet1->setCellValueByColumnAndRow(1, $_fila, $_subTitFecha);
            $_sheet1->setCellValueByColumnAndRow(2, $_fila, $_periodo);

            $_fila++; $_col = 2;
            $_xls->getDefaultStyle()->getFont()->setSize(9)->setName("Arial Narrow");
            $_sheet1->setCellValueByColumnAndRow(1, $_fila, "Nro");
            $_sheet1->setCellValueByColumnAndRow(2, $_fila, "Documento");
            $_sheet1->setCellValueByColumnAndRow(3, $_fila, "Fecha");
            $_sheet1->setCellValueByColumnAndRow(4, $_fila, "Sede");
            $_sheet1->setCellValueByColumnAndRow(5, $_fila, "Cod. Univ.");
            $_sheet1->setCellValueByColumnAndRow(6, $_fila, "Concepto");
            $_sheet1->setCellValueByColumnAndRow(7, $_fila, "Importe");
            
            $_sheet1->getStyle("A".$_fila.":".$_cf.$_fila)->getFont()->setBold(true);
            $_sheet1->getStyle("A".$_fila.":".$_cf.$_fila)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => true));
            $_sheet1->getStyle("A".$_fila.":".$_cf.$_fila)->getBorders()->applyFromArray( array( 'allBorders' => array( 'borderStyle' => Border::BORDER_THIN)));
            $_sheet1->getStyle("A".$_fila.":".$_cf.$_fila)->getFill()->applyFromArray( [ 'fillType' => Fill::FILL_GRADIENT_LINEAR, 'rotation' => 0, 'startColor' => [ 'rgb' => 'E0E0E0' ], 'endColor' => [ 'rgb' => 'E0E0E0' ] ] );
            
            $_nro = 0; $_filaIni = ($_fila+1); $_id = "";
            foreach ($_ac as $row) {
                $_fila++; $_nro++;

                $_sheet1->setCellValue("A".$_fila, $_nro);
                $_sheet1->setCellValue("B".$_fila, $row["cIngDocument"]);
                $_sheet1->setCellValue("C".$_fila, Date::PHPToExcel($row["dDocFecha"]));
                $_sheet1->setCellValue("D".$_fila, $row["cFilAbrev"]);
                $_sheet1->setCellValue("E".$_fila, $row["cEstudCodUniv"]);
                $_sheet1->setCellValue("F".$_fila, $row["cConcepReqNombre"]);
                $_sheet1->setCellValue("G".$_fila, $row["nIngImpt"]);
                //$_sheet1->getStyle("A".$_fila.":T".$_fila)->getFont()->getColor()->setARGB($row["nIngImpt"]*1 > 0 ? Color::COLOR_BLACK : Color::COLOR_RED);
            }

            $_fila++;
            $_sheet1->getStyle("A".$_fila.":G".($_fila+10))->getFont()->getColor()->setARGB(Color::COLOR_BLACK);
            $_sheet1->setCellValue("F".$_fila, "TOTAL IMPORTE ");
            $_sheet1->setCellValue("G".$_fila, "=SUM(G".$_filaIni.":G".($_fila-1).")");

            $_sheet1->getStyle("A".$_fila.":G".$_fila)->getFont()->setBold(true);
            $_sheet1->getStyle("A".$_fila.":G".$_fila)->getBorders()->applyFromArray( array( 'top' => array( 'borderStyle' => Border::BORDER_THIN)));
            $_sheet1->getStyle("G".$_fila.":G".$_fila)->getBorders()->applyFromArray( array( 'allBorders' => array( 'borderStyle' => Border::BORDER_THIN)));
            $_sheet1->getStyle("C".$_filaIni.":C".$_fila)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'textRotation' => 0, 'wrapText' => true));
            //$_sheet1->getStyle("J".$_fila.":".$_cf.$_fila)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT, 'textRotation' => 0, 'wrapText' => true));
            $_sheet1->getStyle("C".$_filaIni.":C".$_fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
            $_sheet1->getStyle("G".$_filaIni.":G".$_fila)->getNumberFormat()->applyFromArray( [ 'formatCode' => $_formatNume2 ] );
            $_sheet1->getStyle("G".$_fila.":G".$_fila)->getFill()->applyFromArray( [ 'fillType' => Fill::FILL_GRADIENT_LINEAR, 'rotation' => 0, 'startColor' => [ 'rgb' => 'E0E0E0' ], 'endColor' => [ 'rgb' => 'E0E0E0' ] ] );
            //$_sheet1->getStyle("Q".$_fila.":Q".$_fila)->getFill()->applyFromArray( [ 'fillType' => Fill::FILL_GRADIENT_LINEAR, 'rotation' => 0, 'startColor' => [ 'rgb' => 'E0E0E0' ], 'endColor' => [ 'rgb' => 'E0E0E0' ] ] );

            header('Access-Control-Allow-Origin: *');
            header("Access-Control-Allow-Methods: GET, POST, PATCH, OPTIONS, PUT, DELETE");
            header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');
            header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="result.xlsx"');
            $writer = IOFactory::createWriter($_xls, 'Xlsx');
            $writer->save('php://output');
            exit;
        }

        $pdf = new PDF("P", "mm", "A4", true, "UTF-8", false);
        $pdf->setFile_header("tre_ingresos_report_regpers_head.php");
        $pdf->SetFillColor(192, 192, 192);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->h_row = 5;  $pdf->max = 185;  $_nro = 0;  $_impt = 0;  $_id = 0;  $_item = 0;

        $pdf->fnNewPage(2500);  $pdf->SetFont("helvetica", "", 6);
        $pdf->SetLineStyle(array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 3));
        foreach ($_ac as $row) { $_nro++;
            $pdf->axisY += ($pdf->h_row + ( $_nro == 1 ? 1 : 0)); $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );

            $pdf->Cell(8, $pdf->h_row, $_nro, "T", 0, "R");
            $pdf->Cell(22, $pdf->h_row, $row["cIngDocument"], "T", 0, "L");
            $pdf->Cell(16, $pdf->h_row, $GLOBALS["_fn"]->fnDateDDMMAAAA($row["dDocFecha"]), "T", 0, "C");
            $pdf->Cell(12, $pdf->h_row, $row["cFilAbrev"], "T", 0, "L");
            //$pdf->Cell(20, $pdf->h_row, $row["cPersDocumento"], "T", 0, "L");
            //$_PersApeNom = utf8_encode(substr(utf8_decode($row["cPersApeNom"]),0,35));
            //$pdf->Cell(85, $pdf->h_row, $_PersApeNom, "T", 0, "L");
            $pdf->Cell(18, $pdf->h_row, $row["cEstudCodUniv"], "T", 0, "L");
            $_ConcepReqNombre = utf8_encode(substr(utf8_decode($row["cConcepReqNombre"]),0,60));
            $pdf->Cell(85, $pdf->h_row, $_ConcepReqNombre, "T", 0, "L");
            $pdf->Cell(10, $pdf->h_row, $row["cTipPagAbrev"], "T", 0, "L");
            $pdf->Cell(18, $pdf->h_row, $GLOBALS["_fn"]->fnNumFormat($row["nIngImpt"],2), "T", 0, "R");

            $_id = $row["iIngId"]; $_impt += $row["nIngImpt"];
        }

    	$pdf->axisY += $pdf->h_row + ( $_nro == "0" ? 1 : 0);  $pdf->fnNewPage();  $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont("helvetica", "B", 6);
	    $pdf->Cell(171, $pdf->h_row, "TOTAL  IMPORTE  ", "TR", 0, "R");
        //$pdf->Cell(18, $pdf->h_row, $GLOBALS["_fn"]->fnNumFormat($_impt*1), 1, 0, "R", 1);
        //$pdf->Cell(93, $pdf->h_row, "", "LTR", 0, "R");
        $pdf->Cell(18, $pdf->h_row, $GLOBALS["_fn"]->fnNumFormat($_impt*1), 1, 0, "R", 1);

        header('Content-type: application/pdf'); header('Content-Disposition: attachment; filename="ingresos_IC.pdf"');
        return $pdf->Output('ingresos_detallado.pdf', 'S');
    }
}