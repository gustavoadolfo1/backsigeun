<?php
$this->SetFillColor(192, 192, 192);  $this->SetTextColor(0, 0, 0);
//$this->write1DBarcode($GLOBALS["_ah"][0]["doc_id"].fnZerosLeft($GLOBALS["_ah"][0]["orden_id"],9), "EAN13", 148, 8, 36, 11);
//$this->write2DBarcode($_SESSION["scUsursess_key"]." - ".$_SESSION["scUsuracce_key"], "QRCODE,L", 188, 3, 16, 16, "", "" , true);

$_h = 4;
$this->axisX = 14;  $this->axisY = 20;
$this->fnSetAxes($this->axisY, $this->axisX );
$this->SetFont("helvetica", "B", 10);
$this->Cell(189, $_h, "REGISTRO DE INGRESOS SEGUN CONCEPTO DE INGRESO", 0, 0, "C");
$this->axisY += 6;  $this->fnSetAxes( $this->axisY, $this->axisX );

$this->SetFont("helvetica", "B", 7);
$this->Cell(22, $_h, " Doc. Gestión:", "", 0, "L");
$this->SetFont("helvetica", "", 7);
$this->Cell(120, $_h, $GLOBALS["_aco"][0]["cDocGestAY"]." "." ".$GLOBALS["_aco"][0]["cConcepNombre"], "", 0, "L");
$this->SetFont("helvetica", "B", 7);
$this->Cell(16, $_h, " Sede:", "", 0, "L");
$this->SetFont("helvetica", "", 7);
$this->Cell(102, $_h, ($GLOBALS["_param"]->get("FilId")*1>0?$GLOBALS["_af"][0]["cFilNombre"]:""), "", 0, "L");

$this->axisY += 4;  $this->fnSetAxes( $this->axisY, $this->axisX );
$this->SetFont("helvetica", "B", 7);
$this->Cell(22, $_h, " Concepto Ingr.:", "", 0, "L");
$this->SetFont("helvetica", "", 7);
$this->Cell(120, $_h, "[".$GLOBALS["_aco"][0]["cConcepReqCode"]."] "." ".utf8_encode(substr(utf8_decode($GLOBALS["_aco"][0]["cConcepReqNombrex"]),0,65)), "", 0, "L");
$this->SetFont("helvetica", "B", 7);
$this->Cell(16, $_h, " Doc. / Serie:", "", 0, "L");
$this->SetFont("helvetica", "", 7);
$this->Cell(102, $_h, $GLOBALS["_param"]->get("DocNombre").($GLOBALS["_param"]->get("DocSerieF")==""?"":"  ".$GLOBALS["_param"]->get("DocSerieF")), "", 0, "L");
$this->SetFont("helvetica", "B", 7);

$this->axisY += 4;  $this->fnSetAxes( $this->axisY, $this->axisX );
$this->SetFont("helvetica", "B", 7);
$this->Cell(22, $_h, " U. Organica:", "", 0, "L");
$this->SetFont("helvetica", "", 7);
$this->Cell(120, $_h, $GLOBALS["_aco"][0]["cDepenNombre"], "", 0, "L");

$_subtitle = "Periodo"; $_periodo = "";
if ($GLOBALS["_param"]->get("FechaIni")!="" && $GLOBALS["_param"]->get("FechaFin")!=""){
    if ($GLOBALS["_param"]->get("FechaIni") == $GLOBALS["_param"]->get("FechaFin")){ $_subtitle = "Fecha"; $_periodo = $GLOBALS["_fn"]->fnDateDDMMAAAA($GLOBALS["_param"]->get("FechaFin")); }
    else{ $_periodo = $GLOBALS["_fn"]->fnDateDDMMAAAA($GLOBALS["_param"]->get("FechaIni")) ." al ".$GLOBALS["_fn"]->fnDateDDMMAAAA($GLOBALS["_param"]->get("FechaFin")); }}
else if ($GLOBALS["_param"]->get("FechaIni")!=""){ $_periodo = "Desde el ".$GLOBALS["_fn"]->fnDateDDMMAAAA($GLOBALS["_param"]->get("FechaIni")); }
else if ($GLOBALS["_param"]->get("FechaFin")!=""){ $_periodo = "Hasta el ".$GLOBALS["_fn"]->fnDateDDMMAAAA($GLOBALS["_param"]->get("FechaFin")); }
$this->SetFont("helvetica", "B", 7);
$this->Cell(18, $_h, " ".$_subtitle.":", "", 0, "L");
$this->SetFont("helvetica", "", 7);
$this->Cell(102, $_h, $_periodo, "", 0, "L");

$this->axisY += 4;  $this->fnSetAxes( $this->axisY, $this->axisX );
$this->SetFont("helvetica", "B", 7);
$this->Cell(22, $_h, " Clasificador:", "", 0, "L");
$this->SetFont("helvetica", "", 7);
$this->Cell(115, $_h, $GLOBALS["_aco"][0]["cEspeDetCodeName"], "", 0, "L");

$this->axisY += 5;  $this->fnSetAxes( $this->axisY, $this->axisX );  $_h = 5;
$this->SetFont("helvetica", "B", 7);
$this->Cell(8, $_h, "Item", 1, 0, "C", 1);
$this->Cell(22, $_h, "Documento", 1, 0, "C", 1);
$this->Cell(16, $_h, "Fecha", 1, 0, "C", 1);
$this->Cell(12, $_h, "Sede", 1, 0, "C", 1);
$this->Cell(20, $_h, "Doc. Ident.", 1, 0, "C", 1);
$this->Cell(65, $_h, "Apellidos y Nombres / Razon Social", 1, 0, "C", 1);
$this->Cell(18, $_h, "Cod. Univ.", 1, 0, "C", 1);
$this->Cell(10, $_h, "T.P.", 1, 0, "C", 1);
$this->Cell(18, $_h, "Importe", 1, 0, "C", 1);

if ($this->PageNo()*1 > 1 ) { $this->axisY += 6;  $this->fnSetAxes( $this->axisY, $this->axisX );  $_h = 5; }