<?php
namespace App\Http\Controllers\Tre\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Resources\Functions;
use App\Resources\Pdf;

$_fn = ""; $_au = "";
class tre_ingresos_reportCONESPEDETF01Controller extends Controller{ 
    public function report(Request $data){
        $GLOBALS["_fn"] = new Functions();
        $_periodo = ""; $_dia = ""; $_mes = ""; $_yea = "";

        $_fechaI = $data->get("FechaIni"); $_fechaF = $data->get("FechaFin");
        if ( $_fechaI != "" || $_fechaF != "" ){
            if ( $_fechaI == $_fechaF ){ $_date = $GLOBALS["_fn"]->fnDateDDMMAAAA($_fechaI);
                $_dia = substr($_date, 0, 2); $_mes = substr($_date, 3, 2); $_yea = substr($_date, 6, 4);
            } else if ( $_fechaI == "" ){
                $_periodo = "Hasta el " . $GLOBALS["_fn"]->fnDateDDMMAAAA($_fechaF);
            } else if ( $_fechaF == "" ){
                $_periodo = "Desde el " . $GLOBALS["_fn"]->fnDateDDMMAAAA($_fechaI);
            } else {
                $_periodo = $GLOBALS["_fn"]->fnDateDDMMAAAA($_fechaI)."  -  ".$GLOBALS["_fn"]->fnDateDDMMAAAA($_fechaF);
                /*$date1 = new DateTime($dia1);
                $_fechaI = $data->get("FechaIni"); $_fiInt = strtotime($_fechaI);
                $_fechaF = $data->get("FechaFin"); $_ffInt = strtotime($_fechaF);
                //$_fechaI->modify('first day of this month');
                //$_fechaF->modify('last day of this month');
                $_yi = date("Y", $_fiInt); $_mi = date("m", $_fiInt); $_di = date("d", $_fiInt);
                $_yf = date("Y", $_ffInt); $_mf = date("m", $_ffInt); $_df = date("d", $_ffInt);
                periodo 
                if ( $_yi.$_mi == $_yf.$_mf ){
                    if ($_df == $GLOBALS["_fn"]->fnLatestDayMonth($_yf, $_mf)){
                        $_df = $GLOBALS["_fn"]->fnLatestDayMonth($_yf, $_mf);
                        $_periodo = 'AGOSTO 2019';
                    }
                } if ( $_yi == $_yf ){

                } else {
                    $_periodo = $_fechaI;
                }*/
            }
        }
        //$_dateInteger = strtotime($data->get("FechaIni"));
        //$_year = date("Y", $_dateInteger);
        //$_mes = date("m", $_dateInteger);
        $_FilAbrev = ""; $_PersApeNom = "";
        if ( $data->get("IngCredDepenKey") != "" ){
            $_rec = app("App\Http\Controllers\Tre\seg_credenciales_dependenciasController")->seg_credenciales_dependencias_select($data, Array("CredDepenKey"=>$data->get("IngCredDepenKey"),"TypeRecord"=>"headFilApeNom"));
            $_au = json_decode($_rec->getContent(), true);
            $_FilAbrev = $_au[0]["cFilAbrevRepCaja"];
            $_PersApeNom = $_au[0]["cPersApeNom"];
        }
        if ( $data->get("FilId")*1 > 0 ){
            $_arr = Array("FilId"=>$data->get("FilId"),"TypeRecord"=>"cboARC","RecordLimit"=>1);  
            $_rec = app('App\Http\Controllers\Tre\grl_filialesController')->grl_filiales_select($data, $_arr);
            $_af = json_decode($_rec->getContent(), true);
            $_FilAbrev = $_af[0]["cFilAbrev"];
        }

        $_arr = Array("FilId"=>$data->get("FilId"),"DocId"=>$data->get("DocId"),"DocSerie"=>$data->get("DocSerie"),"FechaIni"=>$data->get("FechaIni"),"FechaFin"=>$data->get("FechaFin"),"IngCredDepenKey"=>$data->get("IngCredDepenKey"),"TypeRecord"=>"REP_CONESPEDETF01_DOC","RecordLimit"=>1);
        $_rec = app('App\Http\Controllers\Tre\tre_ingresosController')->tre_ingresos_select($data, $_arr);
        $_ad = json_decode($_rec->getContent(), true);

        $_rec = app('App\Http\Controllers\Tre\tre_ingresosController')->tre_ingresos_select($data);
        $_ac = json_decode($_rec->getContent(), true);

        $pdf = new PDF("P", "mm", "A4", true, "UTF-8", false);
        $pdf->SetFillColor(192, 192, 192);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->h_row = 3;  $pdf->max = 270;  $_nro = 0;  $_item = 0;  $_impt = 0;

        $pdf->fnNewPage(2500);  $pdf->SetFont("helvetica", "B", 6);  $pdf->SetTextColor(0, 0, 0); 
        
        $pdf->axisY = 16;
        $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        //$pdf->Cell(185, $pdf->h_row, "RECIBO DE INGRESOS CONSOLIDADO ".$_periodo."--".$_yi.$_mi."--".$_yf.$_mf.$_df, 0, 0, "C");
        $pdf->Cell(185, $pdf->h_row, "RECIBO DE INGRESOS CONSOLIDADO", 0, 0, "C");
        $pdf->setX(155);
        $pdf->SetFont("helvetica", "", 6);
        $pdf->Cell(44, $pdf->h_row, $_FilAbrev, 0, 0, "C");
        
        $pdf->axisX = 14;
        $pdf->SetFont("helvetica", "", 5);
        $pdf->axisY += 4; $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(20, $pdf->h_row, "NUMERACION", "LTR", 0, "C", 1);
        $pdf->Cell(24, $pdf->h_row, "", "", 0, "C");
        $pdf->SetFont("helvetica", "B", 6);
        $pdf->Cell(97, $pdf->h_row, $_periodo, "", 0, "C");
        $pdf->SetFont("helvetica", "", 6);
        $pdf->Cell(22, $pdf->h_row, "REGISTRO", "LTR", 0, "C", 1);
        $pdf->Cell(6, 6, "DIA", 1, 0, "C", 1);
        $pdf->Cell(6, 6, "MES", 1, 0, "C", 1);
        $pdf->Cell(10, 6, "AÑO", 1, 0, "C", 1);

        $pdf->axisY += $pdf->h_row; $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(20, $pdf->h_row, "TESORERO", "LBR", 0, "C", 1);
        $pdf->Cell(121, $pdf->h_row, "", "", 0, "C");
        $pdf->Cell(22, $pdf->h_row, "SIAF N°", "LBR", 0, "C", 1);

        $pdf->axisY += $pdf->h_row; $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX ); $pdf->h_row = 4;
        $pdf->Cell(20, $pdf->h_row, "", 1, 0, "C");
        $pdf->Cell(121, $pdf->h_row, "", "", 0, "C");
        $pdf->Cell(22, $pdf->h_row, "", 1, 0, "C");
        $pdf->Cell(6, $pdf->h_row, $_dia, 1, 0, "C");
        $pdf->Cell(6, $pdf->h_row, $_mes, 1, 0, "C");
        $pdf->Cell(10, $pdf->h_row, $_yea, 1, 0, "C");

        $pdf->SetFont("helvetica", "B", 5);
        $pdf->axisY += $pdf->h_row + 1; $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(20, 6, "CLASIFICADOR", 1, 0, "C", 1);
        $pdf->Cell(121, 6, "DESCRIPCION", 1, 0, "C", 1);
        $pdf->Cell(44, 3, "IMPORTE", 1, 0, "C", 1);

        $pdf->h_row = 3;
        $pdf->axisX = 155; $pdf->axisY += 3; $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(22, $pdf->h_row, "PARCIAL", 1, 0, "C", 1);
        $pdf->Cell(22, $pdf->h_row, "TOTAL", 1, 0, "C", 1);

        $pdf->axisX = 14; $pdf->SetFont("helvetica", "B", 5);

        $pdf->h_row = 2.5; $pdf->axisY += 0.5;
        $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(20, $pdf->h_row, "1", "LTR", 0, "L");
        $pdf->Cell(121, $pdf->h_row, "Ingresos Presupuestales", "LTR", 0, "L");
        $pdf->Cell(22, $pdf->h_row, "", "LTR", 0, "R");
        $pdf->Cell(22, $pdf->h_row, "", "LTR", 0, "R");

        $pdf->SetFont("helvetica", "", 5);
        $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(20, $pdf->h_row, "", "LR", 0, "L");
        $pdf->Cell(121, $pdf->h_row, "Por los ingresos captados de Recursos Directamente Recaudados en la Universidad Nacional de Moquegua", "LR", 0, "L");
        $pdf->Cell(22, $pdf->h_row, "", "LR", 0, "R");
        $pdf->Cell(22, $pdf->h_row, "", "LR", 0, "R");

        $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->SetFont("helvetica", "B", 5);
        $pdf->Cell(20, $pdf->h_row, "", "LR", 0, "L");
        $pdf->Cell(40, $pdf->h_row, "Documentos", "L", 0, "L");
        $pdf->Cell(20, $pdf->h_row, "Efectivo", "", 0, "R");
        $pdf->Cell(20, $pdf->h_row, "Dep. Cuenta", "", 0, "R");
        $pdf->Cell(41, $pdf->h_row, "", "R", 0, "R");
        $pdf->Cell(22, $pdf->h_row, "", "LR", 0, "R");
        $pdf->Cell(22, $pdf->h_row, "", "LR", 0, "R");

        $_num = 0;
        foreach ($_ad as $row) { $_num++;
            $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
            $pdf->SetFont("helvetica", "", 5);
            $pdf->Cell(20, $pdf->h_row, "", "LR", 0, "L");
            $pdf->Cell(10, $pdf->h_row, $row["cDocSerie"], "L", 0, "L");
            $pdf->SetFont("helvetica", "B", 5);
            $pdf->Cell(30, $pdf->h_row, $row["cDocNroMin"]." - ".$row["cDocNroMax"]." (".$row["iDocNum"].")", "", 0, "L");
            $pdf->SetFont("helvetica", "", 5);
            $pdf->Cell(20, $pdf->h_row, $GLOBALS["_fn"]->fnNumFormat($row["nIngImpt"]*1 - $row["nIngDepImpt"]*1), "", 0, "R");
            $pdf->Cell(20, $pdf->h_row, $GLOBALS["_fn"]->fnNumFormat($row["nIngDepImpt"]*1), "", 0, "R");
            $pdf->Cell(41, $pdf->h_row, "", "R", 0, "L");
            $pdf->Cell(22, $pdf->h_row, "", "LR", 0, "R");
            $pdf->Cell(22, $pdf->h_row, "", "LR", 0, "R");    
        }
        if ( $_num < 2 ){
            for ($_i = 1; $_i <= (2-$_num); $_i++){
                $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
                $pdf->SetFont("helvetica", "", 5);
                $pdf->Cell(20, $pdf->h_row, "", "LR", 0, "L");
                $pdf->Cell(121, $pdf->h_row, "", "LR", 0, "L");
                $pdf->Cell(22, $pdf->h_row, "", "LR", 0, "R");
                $pdf->Cell(22, $pdf->h_row, "", "LR", 0, "R");    
            }
        }
    
        $_GeneCode = ""; $_SubGeneCode = ""; $_SubGeneDetCode = ""; $_EspeCode = "";
        $_impt = 0; $_0301 = 0; $_0302 = 0; $_0303 = 0; $_0401 = 0; $_0901 = 0; $_0301 = 0; $_0098 = 0;
        foreach ($_ac as $row) {
            if ( $row["cGeneCodigo"] != $_GeneCode ) {
                $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX ); $_nro++;
                $pdf->SetFont("helvetica", "B", 5);
                $pdf->Cell(20, $pdf->h_row, $row["cGeneCodigo"], "LR", 0, "L");
                $pdf->Cell(121, $pdf->h_row, $row["cGeneNombre"], "LR", 0, "L");
                $pdf->Cell(22, $pdf->h_row, "", "LR", 0, "R");
                $pdf->Cell(22, $pdf->h_row, "", "LR", 0, "R");            
            }
            if ( $row["cSubGeneCodigo"] != $_SubGeneCode ) {
                $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX ); $_nro++;
                $pdf->SetFont("helvetica", "B", 5);
                $pdf->Cell(20, $pdf->h_row, $row["cSubGeneCodigo"], "LR", 0, "L");
                $pdf->Cell(121, $pdf->h_row, $row["cSubGeneNombre"], "LR", 0, "L");
                $pdf->Cell(22, $pdf->h_row, "", "LR", 0, "R");
                $pdf->Cell(22, $pdf->h_row, $GLOBALS["_fn"]->fnNumFormat($row["nSubGeneImpt"]), "LR", 0, "R");
                $_impt += $row["nSubGeneImpt"];
                if ( $row["cSubGeneCodigo"] == "1.3. 1" ){ $_0301 = $row["nSubGeneImpt"]; }
                else if ( $row["cSubGeneCodigo"] == "1.3. 2"){ $_0302 = $row["nSubGeneImpt"]; }
                else if ( $row["cSubGeneCodigo"] == "1.3. 3"){ $_0303 = $row["nSubGeneImpt"]; }
                else if ( $row["cSubGeneCodigo"] == "1.5. 1"){ $_0401 = $row["nSubGeneImpt"]; }
                else if ( $row["cSubGeneCodigo"] == "1.5. 2"){ $_0901 = $row["nSubGeneImpt"]; }
                else if ( $row["cSubGeneCodigo"] == "1.5. 5"){ $_0098 = $row["nSubGeneImpt"]; }
            }
            $pdf->SetFont("helvetica", "", 5);
            if ( $row["cSubGeneDetCodigo"] != $_SubGeneDetCode ) {
                $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX ); $_nro++;
                $pdf->Cell(20, $pdf->h_row, $row["cSubGeneDetCodigo"], "LR", 0, "L");
                $pdf->Cell(121, $pdf->h_row, $row["cSubGeneDetNombre"], "LR", 0, "L");
                $pdf->Cell(22, $pdf->h_row, "", "LR", 0, "R");
                $pdf->Cell(22, $pdf->h_row, "", "LR", 0, "R");
            }
            if ( $row["cEspeCodigo"] != $_EspeCode ) {
                $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX ); $_nro++;
                $pdf->Cell(20, $pdf->h_row, $row["cEspeCodigo"], "LR", 0, "L");
                $pdf->Cell(121, $pdf->h_row, $row["cEspeNombre"], "LR", 0, "L");
                $pdf->Cell(22, $pdf->h_row, "", "LR", 0, "R");
                $pdf->Cell(22, $pdf->h_row, "", "LR", 0, "R");     
            }

            $pdf->axisY += $pdf->h_row; $pdf->fnNewPage(); $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX ); $_nro++;
            $pdf->Cell(20, $pdf->h_row, $row["cEspeDetCodigo"], "LR", 0, "L");
            $pdf->Cell(121, $pdf->h_row, $row["cEspeDetNombre"], "LR", 0, "L");
            $pdf->Cell(22, $pdf->h_row, $GLOBALS["_fn"]->fnNumFormat($row["nEspeDetImpt"]), "LR", 0, "R");
            $pdf->Cell(22, $pdf->h_row, "", "LR", 0, "R");
            
            $_GeneCode = $row["cGeneCodigo"]; $_SubGeneCode = $row["cSubGeneCodigo"]; $_SubGeneDetCode = $row["cSubGeneDetCodigo"]; $_EspeCode = $row["cEspeCodigo"];
        }

    	$pdf->axisY += $pdf->h_row;  $pdf->fnNewPage();  $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
	    $pdf->SetFont("helvetica", "B", 5);
	    $pdf->Cell(163, $pdf->h_row, "TOTAL  IMPORTE  ", 1, 0, "R");
	    $pdf->Cell(22, $pdf->h_row, $GLOBALS["_fn"]->fnNumFormat($_impt*1), 1, 0, "R", 1);

    	$pdf->axisY += $pdf->h_row;  $pdf->fnNewPage();  $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
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
        $pdf->Cell(63, 16, "", 1, 0, "C");

        $pdf->axisY += 16; $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(63, 3, $_PersApeNom, 1, 0, "C");

        $pdf->axisY += 3; $pdf->fnSetAxes( $pdf->axisY, $pdf->axisX );
        $pdf->Cell(63, 16, "", 1, 0, "C");

        header('Content-type: application/pdf'); header('Content-Disposition: attachment; filename="ingresos_IC.pdf"');
        return $pdf->Output('ingresos_IC.pdf', 'S');
    }
}