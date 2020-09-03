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

$pdf = ""; $_fn = ""; $_au = ""; $_adep = "";
$_nrocol = ""; $_width = ""; $_width_fin = ""; $_width_tot = ""; $_coltot = "";
class tre_ingresos_reportCONESPEDETF02Controller extends Controller{
    function report(Request $data){
        $GLOBALS["_fn"] = new Functions();
        global $pdf, $_adep, $_nrocol, $_width, $_width_fin, $_width_tot, $_coltot;

        $_periodo = ""; $_dia = ""; $_mes = ""; $_yea = "";
        $_fechaI = $data->get("FechaIni"); $_fechaF = $data->get("FechaFin");
        /*if ($data->get("FechaIni") == $data->get("FechaFin")){ $_date = $GLOBALS["_fn"]->fnDateDDMMAAAA($data->get("FechaIni"));
            $_dia = substr($_date, 0, 2); $_mes = substr($_date, 3, 2); $_yea = substr($_date, 6, 4);
        }*/
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

        if ( $data->get("FilId")*1 > 0 ){
            $_arr = Array("FilId"=>$data->get("FilId"),"TypeRecord"=>"cboARC","RecordLimit"=>1);  
            $_rec = app('App\Http\Controllers\Tre\grl_filialesController')->grl_filiales_select($data, $_arr);
            $_af = json_decode($_rec->getContent(), true);
            $_FilAbrev = strtoupper($_af[0]["cFilAbrev"]);
        }

        $_rec = app('App\Http\Controllers\Tre\grl_conceptos_importesController')->grl_conceptos_importes_select($data, Array("TypeRecord"=>"cboODDTR","TypeQuery"=>($_FilId*1>0?$_FilId:""),"OrderBy"=>"_DepenOrden","RecordLimit"=>1));
        $_adep = json_decode($_rec->getContent(), true);

        $_arr = Array("FilId"=>$data->get("FilId"),"DocId"=>$data->get("DocId"),"DocSerie"=>$data->get("DocSerie"),"FechaIni"=>$data->get("FechaIni"),"FechaFin"=>$data->get("FechaFin"),"IngCredDepenKey"=>$data->get("IngCredDepenKey"),"TypeRecord"=>"REP_CONESPEDETF01_DOC","RecordLimit"=>1);
        $_rec = app('App\Http\Controllers\Tre\tre_ingresosController')->tre_ingresos_select($data, $_arr);
        $_ad = json_decode($_rec->getContent(), true);

        $_rec = app('App\Http\Controllers\Tre\tre_ingresosController')->tre_ingresos_select($data);
        $_ac = json_decode($_rec->getContent(), true);

        $pdf = new PDF("L", "mm", "A4", true, "UTF-8", false);
        $pdf->SetFillColor(192, 192, 192);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->setPrinter_footer(0);
        $pdf->SetAutoPageBreak(TRUE, 0);
        //$pdf->SetFooterMargin(-40);
        $pdf->h_row = 3; $pdf->max = 200; $_nro = 0; $_item = 0; $_impt = 0;

        $pdf->fnNewPage(2500);  $pdf->SetFont("helvetica", "B", 6);  $pdf->SetTextColor(0, 0, 0);
        $pdf->axisY = 16;
        $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(270, $pdf->h_row, "CONSOLIDADO DE INGRESOS AL DETALLE".($_FilAbrev==""?"":" - ".$_FilAbrev), 0, 0, "C");
        
        $pdf->SetFont("helvetica", "", 5);
        $pdf->axisY += 4; $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(20, $pdf->h_row, "NUMERACION", "LTR", 0, "C", 1);
        $pdf->Cell(26, $pdf->h_row, "", "", 0, "C");
        $pdf->SetFont("helvetica", "B", 6); 
        $pdf->Cell(180, $pdf->h_row, $_periodo, "", 0, "C");
        $pdf->SetFont("helvetica", "", 5); 
        $pdf->Cell(22, $pdf->h_row, "REGISTRO", "LTR", 0, "C", 1);
        $pdf->Cell(6, 6, "DIA", 1, 0, "C", 1);
        $pdf->Cell(6, 6, "MES", 1, 0, "C", 1);
        $pdf->Cell(10, 6, "AÑO", 1, 0, "C", 1);

        $pdf->axisY += $pdf->h_row; $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(20, $pdf->h_row, "TESORERO", "LBR", 0, "C", 1);
        $pdf->Cell(206, $pdf->h_row, "", "", 0, "C");
        $pdf->Cell(22, $pdf->h_row, "SIAF N°", "LBR", 0, "C", 1);

        $pdf->axisY += $pdf->h_row; $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX ); $pdf->h_row = 4;
        $pdf->Cell(20, $pdf->h_row, "", 1, 0, "C");
        $pdf->Cell(206, $pdf->h_row, "", "", 0, "C");
        $pdf->Cell(22, $pdf->h_row, "", 1, 0, "C");
        $pdf->Cell(6, $pdf->h_row, $_dia, 1, 0, "C");
        $pdf->Cell(6, $pdf->h_row, $_mes, 1, 0, "C");
        $pdf->Cell(10, $pdf->h_row, $_yea, 1, 0, "C");

        $pdf->SetFont("helvetica", "B", 5);
        $pdf->axisY += $pdf->h_row + 1; $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(20, 6, "CLASIFICADOR", 1, 0, "C", 1);
        $pdf->Cell(100, 6, "DESCRIPCION", 1, 0, "C", 1);
        //$pdf->Cell(150, 3, "IMPORTE", 1, 0, "C", 1);

        $pdf->axisX = 134; $pdf->axisY += 3; $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX ); $pdf->h_row = 3;
        $_width_tot = 16; $_width_depen = 150 - $_width_tot; $_nrocol = count($_adep);
        $_width = floor(($_width_depen / count($_adep))*10)/10;
        $_width_fin = ($_width_depen - ($_width*($_nrocol-1)));
        $_depen = array(); $_col = 0; $_ax = $pdf->GetX();
        foreach ($_adep as $r){ $_col++;
            $_cwidth = ($_col == $_nrocol?$_width_fin:$_width);
            $pdf->MultiCell($_cwidth, 6, $r["_DepenAbrevRepCaja"]."\n", 1, 'C', 1, 0, $_ax, 31, true, 0, false, true, 6, 'M');
            $_ax += $_cwidth; $_depen[$_col] = 0;
        }
        $pdf->MultiCell($_width_tot, 6, "TOTAL"."\n", 1, 'C', 1, 0, $_ax, 31, true, 0, false, true, 6, 'M');
        //$pdf->Cell($_width_tot, $pdf->h_row, "TOTAL", 1, 0, "C", 1);

        $pdf->axisX = 14; $pdf->SetFont("helvetica", "B", 5);
        $pdf->h_row = 2; $pdf->axisY += 0.5;
        $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(20, $pdf->h_row, "1", "LR", 0, "L");
        $pdf->Cell(100, $pdf->h_row, "Ingresos Presupuestales", "LR", 0, "L");
        $this->columnas();

        $pdf->SetFont("helvetica", "", 5);
        $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(20, $pdf->h_row, "", "LR", 0, "L");
        $pdf->Cell(100, $pdf->h_row, "Por los ingresos captados de Recursos Directamente Recaudados en la Universidad Nacional de Moquegua", "LR", 0, "L");
        $this->columnas();

        $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->SetFont("helvetica", "B", 5);
        $pdf->Cell(20, $pdf->h_row, "", "LR", 0, "L");
        $pdf->Cell(40, $pdf->h_row, "Documentos", "L", 0, "L");
        $pdf->Cell(20, $pdf->h_row, "Efectivo", "", 0, "R");
        $pdf->Cell(20, $pdf->h_row, "Dep. Cuenta", "", 0, "R");
        $pdf->Cell(20, $pdf->h_row, "", "R", 0, "R");
        $this->columnas();

        $_num = 0;
        foreach ($_ad as $row){ $_num++;
            $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
            $pdf->SetFont("helvetica", "", 5);
            $pdf->Cell(20, $pdf->h_row, "", "LR", 0, "L");
            $pdf->Cell(10, $pdf->h_row, $row["cDocSerie"], "L", 0, "L");
            $pdf->SetFont("helvetica", "B", 5);
            $pdf->Cell(30, $pdf->h_row, $row["cDocNroMin"]." - ".$row["cDocNroMax"]." (".$row["iDocNum"].")", "", 0, "L");
            $pdf->SetFont("helvetica", "", 5);
            $pdf->Cell(20, $pdf->h_row, $GLOBALS["_fn"]->fnNumFormat($row["nIngImpt"]*1 - $row["nIngDepImpt"]*1), "", 0, "R");
            $pdf->Cell(20, $pdf->h_row, $GLOBALS["_fn"]->fnNumFormat($row["nIngDepImpt"]*1), "", 0, "R");
            $pdf->Cell(20, $pdf->h_row, "", "R", 0, "L");
            $this->columnas();
        }
        if ( $_num < 2 ){
            for ($_i = 1; $_i <= (2-$_num); $_i++){
                $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
                $pdf->SetFont("helvetica", "", 5);
                $pdf->Cell(20, $pdf->h_row, "", "LR", 0, "L");
                $pdf->Cell(100, $pdf->h_row, "", "LR", 0, "L");
                $this->columnas();
            }
        }
    
        $_GeneCode = ""; $_SubGeneCode = ""; $_SubGeneDetCode = ""; $_EspeCode = "";
        $_impt = 0; $_0301 = 0; $_0302 = 0; $_0303 = 0; $_0401 = 0; $_0901 = 0; $_0301 = 0; $_0098 = 0;
        foreach ($_ac as $row) {
            if ( $row["cGeneCodigo"] != $_GeneCode ) {
                $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX ); $_nro++;
                $pdf->SetFont("helvetica", "B", 5);
                $pdf->Cell(20, $pdf->h_row, $row["cGeneCodigo"], "LR", 0, "L");
                $pdf->Cell(100, $pdf->h_row, $row["cGeneNombre"], "LR", 0, "L");
                $this->columnas();
            }
            if ( $row["cSubGeneCodigo"] != $_SubGeneCode ) {
                $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX ); $_nro++;
                $pdf->SetFont("helvetica", "B", 5);
                $pdf->Cell(20, $pdf->h_row, $row["cSubGeneCodigo"], "LR", 0, "L");
                $pdf->Cell(100, $pdf->h_row, $row["cSubGeneNombre"], "LR", 0, "L");
                $this->columnas();
            }
            $pdf->SetFont("helvetica", "", 5);
            if ( $row["cSubGeneDetCodigo"] != $_SubGeneDetCode ) {
                $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX ); $_nro++;
                $pdf->Cell(20, $pdf->h_row, $row["cSubGeneDetCodigo"], "LR", 0, "L");
                $pdf->Cell(100, $pdf->h_row, $row["cSubGeneDetNombre"], "LR", 0, "L");
                $this->columnas();
            }
            if ( $row["cEspeCodigo"] != $_EspeCode ) {
                $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX ); $_nro++;
                $pdf->Cell(20, $pdf->h_row, $row["cEspeCodigo"], "LR", 0, "L");
                $pdf->Cell(100, $pdf->h_row, $row["cEspeNombre"], "LR", 0, "L");
                $this->columnas();
            }

            $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX ); $_nro++;
            $pdf->Cell(20, $pdf->h_row, $row["cEspeDetCodigo"], "LR", 0, "L");
            $pdf->Cell(100, $pdf->h_row, $row["cEspeDetNombre"], "LR", 0, "L");

            $_col = 0; $_subtot = 0; //$_depen = 0; $_depen++;
            foreach ($_adep as $r) { $_col++;
                $pdf->Cell(($_col == $_nrocol?$_width_fin:$_width), $pdf->h_row, $GLOBALS["_fn"]->fnNumFormat($row[$r["_DepenAbrev"]],2), "LR", 0, "R");
                $_depen[$_col] = $_depen[$_col]*1 + $row[$r["_DepenAbrev"]]*1;
                $_subtot += $row[$r["_DepenAbrev"]];
            }
            $pdf->Cell(16, $pdf->h_row, $GLOBALS["_fn"]->fnNumFormat($_subtot,2), "LR", 0, "R");
            
            $_impt += $_subtot;
            $_GeneCode = $row["cGeneCodigo"]; $_SubGeneCode = $row["cSubGeneCodigo"]; $_SubGeneDetCode = $row["cSubGeneDetCodigo"]; $_EspeCode = $row["cEspeCodigo"];
        }

    	$pdf->axisY += $pdf->h_row;  $pdf->fnNewPage();  $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
	    $pdf->SetFont("helvetica", "B", 5);
        $pdf->Cell(120, $pdf->h_row+0.2, "TOTAL  IMPORTE  ", 1, 0, "R");
        for ( $_i=1; $_i <= $_nrocol; $_i++ ){
            $pdf->Cell(($_i == $_nrocol?$_width_fin:$_width), $pdf->h_row+0.4, $GLOBALS["_fn"]->fnNumFormat($_depen[$_i],2), "1", 0, "R", 1);
        }
	    $pdf->Cell(16, $pdf->h_row+0.2, $GLOBALS["_fn"]->fnNumFormat($_impt*1), 1, 0, "R", 1);

    	/*$pdf->axisY += $pdf->h_row;  $pdf->fnNewPage();  $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
	    $pdf->SetFont("helvetica", "", 5);
	    $pdf->Cell(145, $pdf->h_row, "CODIGO DE CONTABILIDAD GUBERNAMENTAL", 0, 0, "C");
	    $pdf->Cell(40, $pdf->h_row, "PROGRAMATICA DE GASTO", 0, 0, "C");

        $pdf->axisY += $pdf->h_row;  $pdf->fnNewPage();  $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX ); $pdf->h_row = 3;
	    $pdf->SetFont("helvetica", "", 5);
	    $pdf->Cell(37, $pdf->h_row, "CUENTA MAYOR", 1, 0, "C");
        $pdf->Cell(19, 6, "", 1, 0, "C");
        $pdf->Cell(19, 6, "SECTOR", 1, 0, "C");
        $pdf->Cell(19, 6, "PLIEGO", 1, 0, "C");
        $pdf->Cell(10, 6, "U.G.", 1, 0, "C");
        $pdf->Cell(18, 6, "U.E.", 1, 0, "C");
        $pdf->Cell(19, 6, "FUNC", 1, 0, "C");
        $pdf->Cell(22, 6, "FUENTE FINANC", 1, 0, "C");
        $pdf->Cell(22, $pdf->h_row, "CTA. CTE. V°B°", "LTR", 0, "C");

        $pdf->axisY += 3;  $pdf->fnNewPage();  $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
	    $pdf->SetFont("helvetica", "", 5);
	    $pdf->Cell(18, $pdf->h_row, "DEBE", 1, 0, "C");
        $pdf->Cell(19, $pdf->h_row, "HABER", 1, 0, "C");
        $pdf->axisX = 177;
        $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(22, $pdf->h_row, "SUB CUENTA", "LBR", 0, "C");

        $pdf->axisX = 14;
        $pdf->axisY += $pdf->h_row;  $pdf->fnNewPage();  $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
	    $pdf->SetFont("helvetica", "", 5);
        $pdf->Cell(18, $pdf->h_row, "", 1, 0, "C");
        $pdf->Cell(19, $pdf->h_row, "", 1, 0, "C");
        $pdf->Cell(19, $pdf->h_row, "", 1, 0, "C");
        $pdf->Cell(19, $pdf->h_row, "", 1, 0, "C");
        $pdf->Cell(19, $pdf->h_row, "", 1, 0, "C");
        $pdf->Cell(10, $pdf->h_row, "", 1, 0, "C");
        $pdf->Cell(18, $pdf->h_row, "", 1, 0, "C");
        $pdf->Cell(19, $pdf->h_row, "", 1, 0, "C");
        $pdf->Cell(22, $pdf->h_row, "R.D.R.", 1, 0, "C");
        $pdf->Cell(22, $pdf->h_row, "0141-028154", 1, 0, "C");

        $pdf->axisY += 4;  $pdf->fnNewPage();  $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $_y = $pdf->axisY;
        $pdf->Cell(94, 3, "CONTABILIDAD PATRIMONIAL", 1, 0, "C", 1);

        $pdf->axisY += 3;  $pdf->fnNewPage();  $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(56, 3, "CODIGO", 1, 0, "C", 1);
        $pdf->Cell(38, 3, "IMPORTE", 1, 0, "C", 1);

        $pdf->axisY += 3;  $pdf->fnNewPage();  $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(18, 3, "CUENTA", "LTR", 0, "C", 1);
        $pdf->Cell(38, 3, "SUB", "LTR", 0, "C", 1);
        $pdf->Cell(19, 6, "DEBE", 1, 0, "C", 1);
        $pdf->Cell(19, 6, "HABER", 1, 0, "C", 1);

        $pdf->axisY += 3;  $pdf->fnNewPage();  $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(18, 3, "MAYOR", "LBR", 0, "C", 1);
        $pdf->Cell(38, 3, "CUENTAS", "LBR", 0, "C", 1);

        $pdf->axisY += 3;  $pdf->fnNewPage();  $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(18, 2.5, "1101.03", "LTR", 0, "L");
        $pdf->Cell(19, 2.5, "", "LTR", 0, "L");
        $pdf->Cell(19, 2.5, "", "LTR", 0, "R");
        $pdf->Cell(19, 2.5, "", "LTR", 0, "R");
        $pdf->Cell(19, 2.5, "", "LTR", 0, "R");

        $pdf->axisY += 2.5;  $pdf->fnNewPage();  $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(18, 2.5, "", "LR", 0, "L");
        $pdf->Cell(19, 2.5, "1101.030102", "LR", 0, "L");
        $pdf->Cell(19, 2.5, "", "LR", 0, "R");
        $pdf->Cell(19, 2.5, $GLOBALS["_fn"]->fnNumFormat($_impt*1), "LR", 0, "R");
        $pdf->Cell(19, 2.5, "", "LR", 0, "R");

        $pdf->axisY += 2.5;  $pdf->fnNewPage();  $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(18, 2.5, "", "LR", 0, "L");
        $pdf->Cell(19, 2.5, "", "LR", 0, "L");
        $pdf->Cell(19, 2.5, "1201.0301", "LR", 0, "R");
        $pdf->Cell(19, 2.5, "", "LR", 0, "R");
        $pdf->Cell(19, 2.5, $GLOBALS["_fn"]->fnNumFormat($_0301*1), "LR", 0, "R");

        $pdf->axisY += 2.5;  $pdf->fnNewPage();  $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(18, 2.5, "", "LR", 0, "L");
        $pdf->Cell(19, 2.5, "", "LR", 0, "L");
        $pdf->Cell(19, 2.5, "1201.0302", "LR", 0, "R");
        $pdf->Cell(19, 2.5, "", "LR", 0, "R");
        $pdf->Cell(19, 2.5, $GLOBALS["_fn"]->fnNumFormat($_0302*1), "LR", 0, "R");

        $pdf->axisY += 2.5;  $pdf->fnNewPage();  $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(18, 2.5, "", "LR", 0, "L");
        $pdf->Cell(19, 2.5, "", "LR", 0, "L");
        $pdf->Cell(19, 2.5, "1201.0303", "LR", 0, "R");
        $pdf->Cell(19, 2.5, "", "LR", 0, "R");
        $pdf->Cell(19, 2.5, $GLOBALS["_fn"]->fnNumFormat($_0303*1), "LR", 0, "R");

        $pdf->axisY += 2.5;  $pdf->fnNewPage();  $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(18, 2.5, "", "LR", 0, "L");
        $pdf->Cell(19, 2.5, "", "LR", 0, "L");
        $pdf->Cell(19, 2.5, "1201.0401", "LR", 0, "R");
        $pdf->Cell(19, 2.5, "", "LR", 0, "R");
        $pdf->Cell(19, 2.5, $GLOBALS["_fn"]->fnNumFormat($_0401*1), "LR", 0, "R");

        $pdf->axisY += 2.5;  $pdf->fnNewPage();  $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(18, 2.5, "", "LR", 0, "L");
        $pdf->Cell(19, 2.5, "", "LR", 0, "L");
        $pdf->Cell(19, 2.5, "1201.0901", "LR", 0, "R");
        $pdf->Cell(19, 2.5, "", "LR", 0, "R");
        $pdf->Cell(19, 2.5, $GLOBALS["_fn"]->fnNumFormat($_0901*1), "LR", 0, "R");

        $pdf->axisY += 2.5;  $pdf->fnNewPage();  $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(18, 2.5, "", "LRB", 0, "L");
        $pdf->Cell(19, 2.5, "", "LRB", 0, "L");
        $pdf->Cell(19, 2.5, "1202.98", "LRB", 0, "R");
        $pdf->Cell(19, 2.5, "", "LRB", 0, "R");
        $pdf->Cell(19, 2.5, $GLOBALS["_fn"]->fnNumFormat($_0098*1), "LRB", 0, "R");

        $pdf->axisX = 136; $pdf->axisY = $_y; $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(63, 18, "", 1, 0, "C");

        $pdf->axisY += 18; $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(63, 3, $_PersApeNom, 1, 0, "C");

        $pdf->axisY += 3; $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(63, 20, "", 1, 0, "C");*/

        header('Content-type: application/pdf'); header('Content-Disposition: attachment; filename="ingresos_IC.pdf"');
        return $pdf->Output('ingresos_IC.pdf', 'S');
    }

    function columnas(){
        global $pdf, $_adep, $_nrocol, $_width, $_width_fin, $_width_tot, $_coltot;
        $_col = 0;
        foreach ($_adep as $r){ $_col++;
            $pdf->Cell(($_col == $_nrocol?$_width_fin:$_width), $pdf->h_row, "", "LR", 0, "C");
        }
        $pdf->Cell($_width_tot, $pdf->h_row, "", "LR", 0, "C");
    }
}