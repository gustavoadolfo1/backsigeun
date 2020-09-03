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
use PhpOffice\PhpSpreadsheet\Cell\DataType;

$_fn = ""; $_au = "";
class tre_ingresos_reportCONESPEDETF03Controller extends Controller{ 
    public function report(Request $data){
        $GLOBALS["_fn"] = new Functions();
        global $pdf, $_adep, $_nrocol, $_width, $_width_fin, $_width_tot, $_coltot;

        $_periodo = ""; $_dia = ""; $_mes = ""; $_yea = "";
        $_fechaI = $data->get("FechaIni"); $_fechaF = $data->get("FechaFin");
        if ( $_fechaI != "" || $_fechaF != "" ){
            if ( $_fechaI == $_fechaF ){ $_date = $GLOBALS["_fn"]->fnDateDDMMAAAA($_fechaI);
                $_dia = substr($_date, 0, 2); $_mes = substr($_date, 3, 2); $_yea = substr($_date, 6, 4);
            } else if ( $_fechaI == "" ){
                $_periodo = "Hasta el " . $GLOBALS["_fn"]->fnDateDDMMAAAA($_fechaF);
            } else if ( $_fechaF == "" ){
                $_periodo = "Desde el " . $GLOBALS["_fn"]->fnDateDDMMAAAA($_fechaI);
            } else{
                $_periodo = $GLOBALS["_fn"]->fnDateDDMMAAAA($_fechaI)."  -  ".$GLOBALS["_fn"]->fnDateDDMMAAAA($_fechaF);
            }
        }

        $_FilId = 0; $_FilAbrev = ""; $_PersApeNom = "";
        if ( $data->get("IngCredDepenKey") != "" ){
            $_rec = app("App\Http\Controllers\Tre\seg_credenciales_dependenciasController")->seg_credenciales_dependencias_select($data, Array("CredDepenKey"=>$data->get("IngCredDepenKey"),"TypeRecord"=>"headFilApeNom"));
            $_au = json_decode($_rec->getContent(), true);
            $_FilId = $_au[0]["iFilId"];
            $_PersApeNom = $_au[0]["cPersApeNom"];
        }

        /*if ( $data->get("FilId")*1 > 0 ){
            $_arr = Array("FilId"=>$data->get("FilId"),"TypeRecord"=>"cboARC","RecordLimit"=>1);  
            $_rec = app('App\Http\Controllers\Tre\grl_filialesController')->grl_filiales_select($data, $_arr);
            $_af = json_decode($_rec->getContent(), true);
            $_FilAbrev = strtoupper($_af[0]["cFilAbrev"]);
        }*/

        $_rec = app('App\Http\Controllers\Tre\grl_conceptos_importesController')->grl_conceptos_importes_select($data, Array("TypeRecord"=>"cboODDTR","TypeQuery"=>"","OrderBy"=>"_DepenOrden","RecordLimit"=>1));
        $_adep = json_decode($_rec->getContent(), true);

        echo $data->get("TypeRecord");
        $_arr = Array("DocId"=>$data->get("DocId"),"DocSerie"=>$data->get("DocSerie"),"FechaIni"=>$data->get("FechaIni"),"FechaFin"=>$data->get("FechaFin"),"IngCredDepenKey"=>$data->get("IngCredDepenKey"),"TypeRecord"=>$data->get("TypeRecord"),"RecordLimit"=>1);
        $_rec = app('App\Http\Controllers\Tre\tre_ingresosController')->tre_ingresos_select($data, $_arr);
        $_ac = json_decode($_rec->getContent(), true);

        /*$_rec = app('App\Http\Controllers\Tre\tre_ingresosController')->tre_ingresos_select($data);
        $_ac = json_decode($_rec->getContent(), true);*/

        $pdf = new PDF("L", "mm", "A4", true, "UTF-8", false);
        $pdf->SetFillColor(192, 192, 192);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->setPrinter_footer(0);
        $pdf->SetAutoPageBreak(TRUE, 0);
        //$pdf->SetFooterMargin(-40);
        $pdf->axisX = 8; $pdf->h_row = 3; $pdf->max = 200; $_nro = 0; $_item = 0; $_impt = 0;

        $pdf->fnNewPage(2500);  $pdf->SetFont("helvetica", "B", 6);  $pdf->SetTextColor(0, 0, 0);
        $pdf->axisY = 16;
        $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(270, $pdf->h_row, "CONSOLIDADO DE INGRESOS AL DETALLE".($_FilAbrev==""?"":" - ".$_FilAbrev), 0, 0, "C");
        
        $pdf->SetFont("helvetica", "", 4);
        $pdf->axisY += 4; $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(20, $pdf->h_row, "NUMERACION", "LTR", 0, "C", 1);
        $pdf->Cell(15, $pdf->h_row, "", "", 0, "C");
        $pdf->SetFont("helvetica", "B", 6);
        $pdf->Cell(204, $pdf->h_row, $_periodo, "", 0, "C");
        $pdf->SetFont("helvetica", "", 4);
        $pdf->Cell(22, $pdf->h_row, "REGISTRO", "LTR", 0, "C", 1);
        $pdf->Cell(6, 6, "DIA", 1, 0, "C", 1);
        $pdf->Cell(6, 6, "MES", 1, 0, "C", 1);
        $pdf->Cell(10, 6, "AÑO", 1, 0, "C", 1);

        $pdf->axisY += $pdf->h_row; $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(20, $pdf->h_row, "TESORERO", "LBR", 0, "C", 1);
        $pdf->Cell(219, $pdf->h_row, "", "", 0, "C");
        $pdf->Cell(22, $pdf->h_row, "SIAF N°", "LBR", 0, "C", 1);

        $pdf->axisY += $pdf->h_row; $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX ); $pdf->h_row = 4;
        $pdf->Cell(20, $pdf->h_row, "", 1, 0, "C");
        $pdf->Cell(219, $pdf->h_row, "", "", 0, "C");
        $pdf->Cell(22, $pdf->h_row, "", 1, 0, "C");
        $pdf->Cell(6, $pdf->h_row, $_dia, 1, 0, "C");
        $pdf->Cell(6, $pdf->h_row, $_mes, 1, 0, "C");
        $pdf->Cell(10, $pdf->h_row, $_yea, 1, 0, "C");

        $pdf->SetFont("helvetica", "B", 4);
        $pdf->axisY += $pdf->h_row + 1; $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(12, 9, "CLASIFICADOR", 1, 0, "C", 1);
        $pdf->Cell(40, 9, "DESCRIPCION", 1, 0, "C", 1);
        $pdf->Cell(109.2, 3, "SEDE CENTRAL", 1, 0, "C", 1);
        $pdf->Cell(110.8, 3, "FILIAL ILO", 1, 0, "C", 1);
        $pdf->MultiCell(11, 9, "TOTAL"."\n", 1, 'C', 1, 0, $pdf->GetX(), 31, true, 0, false, true, 6, 'M');

        $pdf->axisX = 60; $pdf->axisY += 6; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->h_row = 3;
        $_width_tot = 11; $_width_depen = 231 - $_width_tot; $_nrocol = count($_adep)*2;
        $_width = floor(($_width_depen / $_nrocol)*10)/10;
        $_width_fin = ($_width_depen - ($_width*($_nrocol-1)));
        
        $_depen = array(); $_col = 0; $_ax = $pdf->GetX();
        foreach ($_adep as $r){ $_col++;
            $_ddd = ($r["_DepenAbrevRepCaja"] == "" ? "Sede Central" : $r["_DepenAbrevRepCaja"]);
            $pdf->MultiCell($_width, 6, $_ddd."\n", 1, 'C', 1, 0, $_ax, 34, true, 0, false, true, 6, 'M');
            $_ax += $_width; $_depen[$_col] = 0;
        }
        //$pdf->axisX = 120;
        foreach ($_adep as $r){ $_col++;
            $_cwidth = ($_col == $_nrocol?$_width_fin:$_width);
            $_ddd = ($r["_DepenAbrevRepCaja"] == "" ? "Filial Ilo" : $r["_DepenAbrevRepCaja"]);
            $pdf->MultiCell($_cwidth, 6, $_ddd."\n", 1, 'C', 1, 0, $_ax, 34, true, 0, false, true, 6, 'M');
            $_ax += $_cwidth; $_depen[$_col] = 0;
        }

        
        //$pdf->Cell($_width_tot, $pdf->h_row, "TOTAL", 1, 0, "C", 1);

        $pdf->axisX = 8; $pdf->SetFont("helvetica", "B", 4);
        $pdf->h_row = 2; $pdf->axisY += 0.5;
        $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(12, $pdf->h_row, "1", "LR", 0, "L");
        $pdf->Cell(40, $pdf->h_row, "Ingresos Presupuestales", "LR", 0, "L");
        $this->columnas();

        $pdf->SetFont("helvetica", "", 4);
        $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(12, $pdf->h_row, "", "LR", 0, "L");
        $pdf->Cell(40, $pdf->h_row, "Por los ingresos captados de Recursos Directamente Recaudados en la Universidad Nacional de Moquegua", "LR", 0, "L");
        $this->columnas();

        $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->SetFont("helvetica", "B", 4);
        $pdf->Cell(12, $pdf->h_row, "", "LR", 0, "L");
        $pdf->Cell(15, $pdf->h_row, "Documentos", "L", 0, "L");
        $pdf->Cell(11, $pdf->h_row, "Efectivo", "", 0, "R");
        $pdf->Cell(11, $pdf->h_row, "Dep. Cuenta", "", 0, "R");
        $pdf->Cell(3, $pdf->h_row, "", "R", 0, "R");
        $this->columnas();

        $_num = 0;
        /*
        foreach ($_ad as $row){ $_num++;
            $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
            $pdf->SetFont("helvetica", "", 4);
            $pdf->Cell(20, $pdf->h_row, "", "LR", 0, "L");
            $pdf->Cell(10, $pdf->h_row, $row["cDocSerie"], "L", 0, "L");
            $pdf->SetFont("helvetica", "B", 4);
            $pdf->Cell(30, $pdf->h_row, $row["cDocNroMin"]." - ".$row["cDocNroMax"]." (".$row["iDocNum"].")", "", 0, "L");
            $pdf->SetFont("helvetica", "", 4);
            $pdf->Cell(20, $pdf->h_row, $GLOBALS["_fn"]->fnNumFormat($row["nIngImpt"]*1 - $row["nIngDepImpt"]*1), "", 0, "R");
            $pdf->Cell(20, $pdf->h_row, $GLOBALS["_fn"]->fnNumFormat($row["nIngDepImpt"]*1), "", 0, "R");
            $pdf->Cell(20, $pdf->h_row, "", "R", 0, "L");
            $this->columnas();
        }*/
        if ( $_num < 2 ){
            for ($_i = 1; $_i <= (2-$_num); $_i++){
                $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
                $pdf->SetFont("helvetica", "", 4);
                $pdf->Cell(12, $pdf->h_row, "", "LR", 0, "L");
                $pdf->Cell(40, $pdf->h_row, "", "LR", 0, "L");
                $this->columnas();
            }
        }
    
        $_GeneCode = ""; $_SubGeneCode = ""; $_SubGeneDetCode = ""; $_EspeCode = "";
        $_impt = 0; $_0301 = 0; $_0302 = 0; $_0303 = 0; $_0401 = 0; $_0901 = 0; $_0301 = 0; $_0098 = 0;
        foreach ($_ac as $row) {
            if ( $row["cGeneCodigo"] != $_GeneCode ) {
                $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX ); $_nro++;
                $pdf->SetFont("helvetica", "B", 4);
                $pdf->Cell(12, $pdf->h_row, $row["cGeneCodigo"], "LR", 0, "L");
                $pdf->Cell(40, $pdf->h_row, $row["cGeneNombre"], "LR", 0, "L");
                $this->columnas();
            }
            if ( $row["cSubGeneCodigo"] != $_SubGeneCode ) {
                $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX ); $_nro++;
                $pdf->SetFont("helvetica", "B", 5);
                $pdf->Cell(12, $pdf->h_row, $row["cSubGeneCodigo"], "LR", 0, "L");
                $pdf->Cell(40, $pdf->h_row, $row["cSubGeneNombre"], "LR", 0, "L");
                $this->columnas();
            }
            $pdf->SetFont("helvetica", "", 4);
            if ( $row["cSubGeneDetCodigo"] != $_SubGeneDetCode ) {
                $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX ); $_nro++;
                $pdf->Cell(12, $pdf->h_row, $row["cSubGeneDetCodigo"], "LR", 0, "L");
                $pdf->Cell(40, $pdf->h_row, $row["cSubGeneDetNombre"], "LR", 0, "L");
                $this->columnas();
            }
            if ( $row["cEspeCodigo"] != $_EspeCode ) {
                $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX ); $_nro++;
                $pdf->Cell(12, $pdf->h_row, $row["cEspeCodigo"], "LR", 0, "L");
                $pdf->Cell(40, $pdf->h_row, $row["cEspeNombre"], "LR", 0, "L");
                $this->columnas();
            }

            $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX ); $_nro++;
            $pdf->Cell(12, $pdf->h_row, $row["cEspeDetCodigo"], "LR", 0, "L");
            $pdf->Cell(40, $pdf->h_row, substr($row["cEspeDetNombre"],0,43), "LR", 0, "L");

            $_col = 0; $_subtot = 0; //$_depen = 0; $_depen++;
            foreach ($_adep as $r) { $_col++;
                //($_col == $_nrocol?$_width_fin:$_width)
                $pdf->Cell($_width, $pdf->h_row, $GLOBALS["_fn"]->fnNumFormat($row["_1".$r["_DepenAbrev"]],2), "LR", 0, "R");
                //$pdf->Cell(9, $pdf->h_row, $row["_1_"], 1, 0, "R");
                $_depen[$_col] = $_depen[$_col]*1 + $row["_1".$r["_DepenAbrev"]]*1;
                $_subtot += $row["_1".$r["_DepenAbrev"]];
            }
            foreach ($_adep as $r) { $_col++;
                //($_col == $_nrocol?$_width_fin:$_width)
                $pdf->Cell(($_col == $_nrocol?$_width_fin:$_width), $pdf->h_row, $GLOBALS["_fn"]->fnNumFormat($row["_2".$r["_DepenAbrev"]],2), "LR", 0, "R");
                //$pdf->Cell(9, $pdf->h_row, $row["_1_"], 1, 0, "R");
                //$_depen[$_col] = $_depen[$_col]*1 + $row["_1_"]*1;
                $_depen[$_col] = $_depen[$_col]*1 + $row["_2".$r["_DepenAbrev"]]*1;
                $_subtot += $row["_2".$r["_DepenAbrev"]];
            }

            $pdf->Cell(11, $pdf->h_row, $GLOBALS["_fn"]->fnNumFormat($_subtot,2), "LR", 0, "R");
            
            $_impt += $_subtot;
            $_GeneCode = $row["cGeneCodigo"]; $_SubGeneCode = $row["cSubGeneCodigo"]; $_SubGeneDetCode = $row["cSubGeneDetCodigo"]; $_EspeCode = $row["cEspeCodigo"];
        }

    	$pdf->axisY += $pdf->h_row;  $pdf->fnNewPage();  $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
	    $pdf->SetFont("helvetica", "B", 4);
        $pdf->Cell(52, $pdf->h_row+0.4, "TOTAL  IMPORTE  ", 1, 0, "R");
        for ( $_i=1; $_i <= $_nrocol; $_i++ ){
            $pdf->Cell(($_i == $_nrocol?$_width_fin:$_width), $pdf->h_row+0.4, $GLOBALS["_fn"]->fnNumFormat($_depen[$_i],2), "1", 0, "R", 1);
        }
	    $pdf->Cell(11, $pdf->h_row+0.4, $GLOBALS["_fn"]->fnNumFormat($_impt*1), 1, 0, "R", 1);

        header('Content-type: application/pdf'); header('Content-Disposition: attachment; filename="ingresos_IC.pdf"');
        return $pdf->Output('ingresos_IC.pdf', 'S');

        /*
        $GLOBALS["_fn"] = new Functions();
        $_dia = ""; $_mes = ""; $_yea = "";
        if ($data->get("FechaIni") == $data->get("FechaFin")){ $_date = $GLOBALS["_fn"]->fnDateDDMMAAAA($data->get("FechaIni"));
            $_dia = substr($_date, 0, 2); $_mes = substr($_date, 3, 2); $_yea = substr($_date, 6, 4);
        }

        $_FilId = 0; $_PersApeNom = "";
        if ( $data->get("IngCredDepenKey") != "" ){
            $_rec = app("App\Http\Controllers\Tre\seg_credenciales_dependenciasController")->seg_credenciales_dependencias_select($data, Array("CredDepenKey"=>$data->get("IngCredDepenKey"),"TypeRecord"=>"headFilApeNom"));
            $_au = json_decode($_rec->getContent(), true);
            $_FilId = $_au[0]["iFilId"];
            $_PersApeNom = $_au[0]["cPersApeNom"];
        }
        
        $_rec = app('App\Http\Controllers\Tre\grl_conceptos_importesController')->grl_conceptos_importes_select($data, Array("TypeRecord"=>"cboODD","TypeQuery"=>($_FilId*1>0?$_FilId:""),"OrderBy"=>"_DepenAbrev","RecordLimit"=>1));
        $_ad = json_decode($_rec->getContent(), true);*/

        /*$_arr = Array("FilId"=>$data->get("FilId"),"DocId"=>$data->get("DocId"),"DocSerie"=>$data->get("DocSerie"),"FechaIni"=>$data->get("FechaIni"),"FechaFin"=>$data->get("FechaFin"),"IngCredDepenKey"=>$data->get("IngCredDepenKey"),"TypeRecord"=>"REP_CONESPEDETF01_DOC","RecordLimit"=>1);
        $_rec = app('App\Http\Controllers\Tre\tre_ingresosController')->tre_ingresos_select($data, $_arr);
        $_ad = json_decode($_rec->getContent(), true);

        $_rec = app('App\Http\Controllers\Tre\tre_ingresosController')->tre_ingresos_select($data);
        $_ac = json_decode($_rec->getContent(), true);*/
        /*$_rec = app('App\Http\Controllers\Tre\tre_ingresosController')->tre_ingresos_select($data);
        $_ac = json_decode($_rec->getContent(), true);

        $_xls = new Spreadsheet();
        $_xls->getProperties()->setCreator("Sigeun")->setTitle("Resumen");
        $_xls->getDefaultStyle()->getFont()->setSize(9)->setName("Arial Narrow");;
        $_formatNume2 = "#,#0.00;[Red]-#,#0.00"; $_formatNume3 = "#,#0.000;[Red]-#,#0.000"; $_formatNume6 = "#,#0.000000;[Red]-#,#0.000000";

        $_sheet1 = $_xls->getActiveSheet();
        $_sheet1->setTitle("Resumen");
        $_sheet1->getDefaultRowDimension()->setRowHeight(15);

		$_sheet1->getColumnDimension("A")->setWidth(15);
		$_sheet1->getColumnDimension("B")->setWidth(80);
        $_cf = "C";
        for ( $_i=2; $_i <= count($_ad)+1; $_i++ ){ $_cf = $GLOBALS["_fn"]->fnSheetColum($_i);
            $_sheet1->getColumnDimension($_cf)->setWidth(16);
        }
        $_cf = $GLOBALS["_fn"]->fnSheetColum($_i);
        $_sheet1->getColumnDimension($_cf)->setWidth(16);

        $_sheet1->setCellValue("A1", "CONSOLIDADO POR CLASIFICADOR - UNIDAD ORGANICA".$_i.$_cf);
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
        $_sheet1->setCellValueByColumnAndRow(4, $_fila, "U. Orgánica");
        $_sheet1->setCellValueByColumnAndRow(5, $_fila, $data->get("DepenNombre"));

        $_fila++;
        $_sheet1->setCellValueByColumnAndRow(1, $_fila, "Sede");
        $_sheet1->setCellValueByColumnAndRow(2, $_fila, $data->get("FilNombre"));
        $_sheet1->getStyle("A2:A4")->getFont()->setBold(true);
        $_sheet1->getStyle("D2:D4")->getFont()->setBold(true);

        $_fila++; $_col = 1;
        $_xls->getDefaultStyle()->getFont()->setSize(9)->setName("Arial Narrow");
        $_sheet1->setCellValueByColumnAndRow(1, $_fila, "Clasificador");
        $_sheet1->setCellValueByColumnAndRow(2, $_fila, "Descripción");
        foreach ($_ad as $row) { $_col++;
            $_sheet1->setCellValueByColumnAndRow($_col+1, $_fila, $row["_DepenAbrev"]);
            $_sheet1->getComment($GLOBALS["_fn"]->fnSheetColum($_col).$_fila)->getText()->createTextRun($row["_DepenNombre"]);
        }
        $_col++;
        $_sheet1->setCellValueByColumnAndRow($_col+1, $_fila, "IMPORTE");

        $_sheet1->getStyle("A".$_fila.":".$_cf.$_fila)->getFont()->setBold(true);
        $_sheet1->getStyle("A".$_fila.":".$_cf.$_fila)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => true));
        $_sheet1->getStyle("A".$_fila.":".$_cf.$_fila)->getBorders()->applyFromArray( array( 'allBorders' => array( 'borderStyle' => Border::BORDER_THIN)));
        $_sheet1->getStyle("A".$_fila.":".$_cf.$_fila)->getFill()->applyFromArray( [ 'fillType' => Fill::FILL_GRADIENT_LINEAR, 'rotation' => 0, 'startColor' => [ 'rgb' => 'E0E0E0' ], 'endColor' => [ 'rgb' => 'E0E0E0' ] ] );

        $_GeneCode = ""; $_SubGeneCode = ""; $_SubGeneDetCode = ""; $_EspeCode = "";
        $_nro = 0; $_filaIni = ($_fila+1);
        foreach ($_ac as $row) { $_nro++;
            if ( $row["cGeneCodigo"] != $_GeneCode ) {
                $_fila++;
                $_sheet1->setCellValueExplicit("A".$_fila, $row["cGeneCodigo"], DataType::TYPE_STRING);
                $_sheet1->setCellValue("B".$_fila, $row["cGeneNombre"]);
            }
            if ( $row["cSubGeneCodigo"] != $_SubGeneCode ) {
                $_fila++;
                $_sheet1->setCellValue("A".$_fila, $row["cSubGeneCodigo"]); // $row["cIngDocument"]
                $_sheet1->setCellValue("B".$_fila, $row["cSubGeneNombre"]);
            }
            if ( $row["cSubGeneDetCodigo"] != $_SubGeneDetCode ) {
                $_fila++;
                $_sheet1->setCellValue("A".$_fila, $row["cSubGeneDetCodigo"]); // $row["cIngDocument"]
                $_sheet1->setCellValue("B".$_fila, $row["cSubGeneDetNombre"]);
            }
            if ( $row["cEspeCodigo"] != $_EspeCode ) {
                $_fila++;
                $_sheet1->setCellValue("A".$_fila, $row["cEspeCodigo"].""); // $row["cIngDocument"]
                $_sheet1->setCellValue("B".$_fila, $row["cEspeNombre"]);
            }

            $_fila++;
            $_sheet1->setCellValue("A".$_fila, $row["cEspeDetCodigo"]); // $row["cIngDocument"]
            $_sheet1->setCellValue("B".$_fila, $row["cEspeDetNombre"]);
            //$_sheet1->setCellValue("C".$_fila, $row["_"].$_FilId);

            $_col = 1;
            foreach ($_ad as $r) { $_col++;
                $_sheet1->setCellValue($GLOBALS["_fn"]->fnSheetColum($_col).$_fila, ($r["_DepenAbrev"]==''?$row['_']:$row[$r["_DepenAbrev"]]));
            }

            $_GeneCode = $row["cGeneCodigo"]; $_SubGeneCode = $row["cSubGeneCodigo"]; $_SubGeneDetCode = $row["cSubGeneDetCodigo"]; $_EspeCode = $row["cEspeCodigo"];
        }
        $_fila++;
        for ( $_i=2; $_i <= count($_ad)+1; $_i++ ){ $_c = $GLOBALS["_fn"]->fnSheetColum($_i);
            $_sheet1->setCellValue($_c.$_fila, "=SUM(".$_c.$_filaIni.":".$_c.($_fila-1).")");
        }

        $_sheet1->getStyle("A".$_fila.":".$_cf.$_fila)->getFont()->setBold(true);
        $_sheet1->getStyle("C".$_filaIni.":".$_cf.$_fila)->getNumberFormat()->applyFromArray( [ 'formatCode' => $_formatNume2 ] );
        $_sheet1->getStyle("C".$_fila.":".$_cf.$_fila)->getBorders()->applyFromArray( array( 'allBorders' => array( 'borderStyle' => Border::BORDER_THIN)));
        $_sheet1->getStyle("C".$_fila.":".$_cf.$_fila)->getFill()->applyFromArray( [ 'fillType' => Fill::FILL_GRADIENT_LINEAR, 'rotation' => 0, 'startColor' => [ 'rgb' => 'E0E0E0' ], 'endColor' => [ 'rgb' => 'E0E0E0' ] ] );

        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, PATCH, OPTIONS, PUT, DELETE");
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');
        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="result.xlsx"');
        $writer = IOFactory::createWriter($_xls, 'Xlsx');
        $writer->save('php://output');
        exit;*/
    }

    function columnas(){
        global $pdf, $_adep, $_nrocol, $_width, $_width_fin, $_width_tot, $_coltot;
        $_col = 0;
        foreach ($_adep as $r){ $_col++;
            $pdf->Cell($_width, $pdf->h_row, "", "LR", 0, "C");
        }
        foreach ($_adep as $r){ $_col++;
            $pdf->Cell(($_col == $_nrocol?$_width_fin:$_width), $pdf->h_row, "", "LR", 0, "C");
        }
        $pdf->Cell(11, $pdf->h_row, "", "LR", 0, "C");
    }
}