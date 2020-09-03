<?php
namespace App\Http\Controllers\Tre\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Resources\Functions;
use App\Resources\Pdf;
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

$_param = ""; $_au = ""; $_aedg = "";
class tre_ingresos_reportRESESPEDETT1Controller extends Controller{ 
    public function report(Request $data){
        $GLOBALS["_param"] = $data;
        $GLOBALS["_fn"] = new Functions();

        if ( $data->get("IngCredDepenKey") != "" ) {
            $_rec = app("App\Http\Controllers\Tre\seg_credenciales_dependenciasController")->seg_credenciales_dependencias_select($data, Array("CredDepenKey"=>$data->get("IngCredDepenKey"),"TypeRecord"=>"headApeNom"));
            $GLOBALS["_au"] = json_decode($_rec->getContent(), true);
        }

        $_rec = app('App\Http\Controllers\Tre\grl_conceptos_importesController')->grl_conceptos_importes_select($data, Array("DepenId"=>$data->get("DepenId"),"TypeRecord"=>"cboODEDT1" ,"RecordLimit"=>1));
        /*if ( $data->get("DepenId")*1 <= 0 ) {
            $_rec = app('App\Http\Controllers\Tre\tre_ingresos_especificas_detController')->tre_ingresos_especificas_det_select($data, Array("TypeRecord"=>"cbo" ,"RecordLimit"=>1));
        } else {
            $_rec = app('App\Http\Controllers\Tre\grl_conceptos_importesController')->grl_conceptos_importes_select($data, Array("DepenId"=>$data->get("DepenId"),"TypeRecord"=>"cboODED" ,"RecordLimit"=>1));
        }*/
        $_aed = json_decode($_rec->getContent(), true);
        $GLOBALS["_aedg"] = json_decode($_rec->getContent(), true);

        $_rec = app('App\Http\Controllers\Tre\tre_ingresosController')->tre_ingresos_select($data);
        $_ac = json_decode($_rec->getContent(), true);


        if ( $data->get("pType") == 33 ) {        
            $_xls = new Spreadsheet();
            $_xls->getProperties()->setCreator("Sigeun")->setTitle("Resumen");
            $_xls->getDefaultStyle()->getFont()->setSize(9)->setName("Arial Narrow");;
            $_formatNume2 = "#,#0.00;[Red]-#,#0.00"; $_formatNume3 = "#,#0.000;[Red]-#,#0.000"; $_formatNume6 = "#,#0.000000;[Red]-#,#0.000000";

            $_sheet1 = $_xls->getActiveSheet();
            $_sheet1->setTitle("Resumen");
            $_sheet1->getDefaultRowDimension()->setRowHeight(15);

            $_sheet1->getColumnDimension("A")->setWidth(8);
            $_sheet1->getColumnDimension("B")->setWidth(14);
            $_sheet1->getColumnDimension("C")->setWidth(16);
            $_cf = "C";
            for ( $_i=3; $_i <= count($_aed)+2; $_i++ ){ $_cf = $GLOBALS["_fn"]->fnSheetColum($_i);
                $_sheet1->getColumnDimension($_cf)->setWidth(16);
            }

            $_sheet1->setCellValue("A1", "RESUMEN DE INGRESOS POR DOCUMENTO");
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
            $_sheet1->setCellValueByColumnAndRow(4, $_fila, "Cajero");
            if ( isset($GLOBALS["_au"][0]["cPersDocumento"]) ) {
                $_sheet1->setCellValueByColumnAndRow(5, $_fila, $GLOBALS["_au"][0]["cPersDocumento"] ." - ".$GLOBALS["_au"][0]["cPersApeNom"]);
            }

            $_fila++;
            $_sheet1->setCellValueByColumnAndRow(1, $_fila, "Sede");
            $_sheet1->setCellValueByColumnAndRow(2, $_fila, $data->get("FilNombre"));
            $_sheet1->getStyle("A2:A4")->getFont()->setBold(true);
            $_sheet1->getStyle("D2:D4")->getFont()->setBold(true);

            $_fila++; $_col = 2;
            $_xls->getDefaultStyle()->getFont()->setSize(9)->setName("Arial Narrow");
            $_sheet1->setCellValueByColumnAndRow(1, $_fila, "Nro");
            $_sheet1->setCellValueByColumnAndRow(2, $_fila, "Documento");
            $_sheet1->setCellValueByColumnAndRow(3, $_fila, "Importe");
            foreach ($_aed as $row) { $_col++;
                $_sheet1->setCellValueByColumnAndRow($_col+1, $_fila, $row["cEspeDetCodigo"]); //"1.3. 1. 5. 1. 1"
                $_sheet1->getComment($GLOBALS["_fn"]->fnSheetColum($_col).$_fila)->getText()->createTextRun($row["cEspeDetNombre"]);
            }
            $_sheet1->getStyle("A".$_fila.":".$_cf.$_fila)->getFont()->setBold(true);
            $_sheet1->getStyle("A".$_fila.":".$_cf.$_fila)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => true));
            $_sheet1->getStyle("A".$_fila.":".$_cf.$_fila)->getBorders()->applyFromArray( array( 'allBorders' => array( 'borderStyle' => Border::BORDER_THIN)));
            $_sheet1->getStyle("A".$_fila.":".$_cf.$_fila)->getFill()->applyFromArray( [ 'fillType' => Fill::FILL_GRADIENT_LINEAR, 'rotation' => 0, 'startColor' => [ 'rgb' => 'E0E0E0' ], 'endColor' => [ 'rgb' => 'E0E0E0' ] ] );


            $_nro = 0; $_filaIni = ($_fila+1);
            foreach ($_ac as $row) { $_fila++; $_nro++;
                $_sheet1->setCellValue("A".$_fila, $_nro); // PHPExcel_Cell_DataType::TYPE_NUMERIC
                $_sheet1->setCellValue("B".$_fila, $row["cIngDocument"]); // $row["cIngDocument"]
                $_sheet1->setCellValue("C".$_fila, $row["nIngImpt"]);

                $_col = 2;
                foreach ($_aed as $r) { $_col++;
                    $_sheet1->setCellValue($GLOBALS["_fn"]->fnSheetColum($_col).$_fila, $row[$r["cEspeDetCodigo"]]);
                }
                $_sheet1->getStyle("A".$_fila.":".$_cf.$_fila)->getFont()->getColor()->setARGB($row["nIngImpt"]*1 > 0 ? Color::COLOR_BLACK : Color::COLOR_RED);
            }
            $_fila++;
            for ( $_i=2; $_i <= count($_aed)+2; $_i++ ){ $_c = $GLOBALS["_fn"]->fnSheetColum($_i);
                $_sheet1->setCellValue($_c.$_fila, "=SUM(".$_c.$_filaIni.":".$_c.($_fila-1).")");
            }

            $_sheet1->getStyle("A".$_fila.":".$_cf.$_fila)->getFont()->setBold(true);
            $_sheet1->getStyle("C".$_filaIni.":AL".$_fila)->getNumberFormat()->applyFromArray( [ 'formatCode' => $_formatNume2 ] );
            $_sheet1->getStyle("C".$_fila.":".$_cf.$_fila)->getBorders()->applyFromArray( array( 'allBorders' => array( 'borderStyle' => Border::BORDER_THIN)));
            $_sheet1->getStyle("C".$_fila.":".$_cf.$_fila)->getFill()->applyFromArray( [ 'fillType' => Fill::FILL_GRADIENT_LINEAR, 'rotation' => 0, 'startColor' => [ 'rgb' => 'E0E0E0' ], 'endColor' => [ 'rgb' => 'E0E0E0' ] ] );

            header('Access-Control-Allow-Origin: *');
            header("Access-Control-Allow-Methods: GET, POST, PATCH, OPTIONS, PUT, DELETE");
            header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');
            header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="result.xlsx"');
            $writer = IOFactory::createWriter($_xls, 'Xlsx');
            $writer->save('php://output');
            exit;
        }
            
        //$_t = (count($_aed) <= 7 ? "P" : "L");
        $pdf = new PDF("L", "mm", "A4", true, "UTF-8", false);
        $pdf->setFile_header("tre_ingresos_report_resespedett1_head.php");
        $pdf->SetFillColor(192, 192, 192);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->h_row = 5;  $pdf->max = 185;  $_nro = 0;   $_impt = 0;  $_id = 0;  $_item = 0;

        $pdf->fnNewPage(2500);  $pdf->SetFont("helvetica", "", 5);
        //$pdf->SetLineStyle(array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 3));
        $_coltot = (count($GLOBALS["_aedg"]) <= 7 ? 7 : 18); $espedet = array();
        for ($i = 1; $i <= $_coltot; $i++) {
            $espedet[$i] = 0;
        }
        $_col = $_coltot - count($GLOBALS["_aedg"]);
        foreach ($_ac as $row) { $_nro++;
            $pdf->axisY += ($pdf->h_row + ( $_nro == 0 ? 1 : 0)); $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
            if ( $row["nIngImpt"]*1 > 0 ){ $pdf->SetTextColor(0, 0, 0); }else{ $pdf->SetTextColor(236, 0, 0); }

            $pdf->Cell(6, $pdf->h_row, $_nro, 1, 0, "R");
            $pdf->Cell(14, $pdf->h_row, $row["cIngDocument"], 1, 0, "L");
            //$pdf->Cell(16, $pdf->h_row, $GLOBALS["_fn"]->fnDateDDMMAAAA($row["dDocFecha"]), 1, 0, "C");
            $pdf->Cell(14, $pdf->h_row, $GLOBALS["_fn"]->fnNumFormat($row["nIngImpt"],2), 1, 0, "R");
            $nroespe = 0;
            foreach ($_aed as $r) { $nroespe++;
                $pdf->Cell(13, $pdf->h_row, $GLOBALS["_fn"]->fnNumFormat($row[$r["cEspeDetCodigo"]],2), 1, 0, "R");
                $espedet[$nroespe] = $espedet[$nroespe]*1 + $row[$r["cEspeDetCodigo"]]*1;
            }
            for ($i = 1; $i <= $_col; $i++) {
                $pdf->Cell(13, $pdf->h_row, "", 1, 0, "C");
            }
            $_impt += $row["nIngImpt"]*1;
        }

        $pdf->SetLineStyle(array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0));
        $pdf->axisY += $pdf->h_row + ( $_nro == "0" ? 1 : 0);  $pdf->fnNewPage();  $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->SetTextColor(0, 0, 0);
	    $pdf->SetFont("helvetica", "B", 5);
	    $pdf->Cell(20, $pdf->h_row, "TOTAL  IMPORTE  ", "TR", 0, "R");
	    $pdf->Cell(14, $pdf->h_row, $GLOBALS["_fn"]->fnNumFormat($_impt*1), 1, 0, "R", 1);
        for ($i = 1; $i <= $_coltot; $i++) {
            $pdf->Cell(13, $pdf->h_row, $GLOBALS["_fn"]->fnNumFormat($espedet[$i]*1), 1, 0, "R", 1);
        }


        header('Content-type: application/pdf'); header('Content-Disposition: attachment; filename="ingresos_IC.pdf"');
        return $pdf->Output('ingresos_detallado.pdf', 'S');
    }
}