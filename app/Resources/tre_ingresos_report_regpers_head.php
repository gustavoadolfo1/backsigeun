<?php
$this->SetFillColor(192, 192, 192);  $this->SetTextColor(0, 0, 0);
//$this->write1DBarcode($GLOBALS["_ah"][0]["doc_id"].fnZerosLeft($GLOBALS["_ah"][0]["orden_id"],9), "EAN13", 148, 8, 36, 11);
//$this->write2DBarcode($_SESSION["scUsursess_key"]." - ".$_SESSION["scUsuracce_key"], "QRCODE,L", 188, 3, 16, 16, "", "" , true);

$_h = 4;
$this->axisX = 14;  $this->axisY = 20;
$this->fnSetAxes($this->axisY, $this->axisX );
$this->SetFont("helvetica", "B", 10);
$this->Cell(190, $_h, "REGISTRO DE INGRESOS SEGUN PERSONA", 0, 0, "C");
$this->axisY += 6;  $this->fnSetAxes( $this->axisY, $this->axisX );

$this->SetFont("helvetica", "B", 7);
$this->Cell(18, $_h, " ".$GLOBALS["_ape"][0]["cDocAbrev"].":", "", 0, "L");
$this->SetFont("helvetica", "", 7);
$this->Cell(100, $_h, $GLOBALS["_ape"][0]["cPersDocumento"], "", 0, "L");
$_filial = ($GLOBALS["_param"]->get("FilNombre")=="" ? "" : "(".$GLOBALS["_param"]->get("FilNombre").") ");
$_subtitle = "Periodo"; $_periodo = "";
if ($GLOBALS["_param"]->get("FechaIni")!="" && $GLOBALS["_param"]->get("FechaFin")!=""){ 
    if ($GLOBALS["_param"]->get("FechaIni") == $GLOBALS["_param"]->get("FechaFin")){ $_subtitle = "Fecha"; $_periodo = $GLOBALS["_fn"]->fnDateDDMMAAAA($GLOBALS["_param"]->get("FechaFin")); }
    else{ $_periodo = $GLOBALS["_fn"]->fnDateDDMMAAAA($GLOBALS["_param"]->get("FechaIni")) ." al ".$GLOBALS["_fn"]->fnDateDDMMAAAA($GLOBALS["_param"]->get("FechaFin")); }}
else if ($GLOBALS["_param"]->get("FechaIni")!=""){ $_periodo = "Desde el ".$GLOBALS["_fn"]->fnDateDDMMAAAA($GLOBALS["_param"]->get("FechaIni")); }
else if ($GLOBALS["_param"]->get("FechaFin")!=""){ $_periodo = "Hasta el ".$GLOBALS["_fn"]->fnDateDDMMAAAA($GLOBALS["_param"]->get("FechaFin")); }
$this->SetFont("helvetica", "B", 7);
$this->Cell(18, $_h, " ".$_subtitle.":", "", 0, "L");
$this->SetFont("helvetica", "", 7);
$this->Cell(102, $_h, $_filial.$_periodo, "", 0, "L");

$this->axisY += 4;  $this->fnSetAxes( $this->axisY, $this->axisX );
$this->SetFont("helvetica", "B", 7);
$this->Cell(18, $_h, " Nombre:", 0, "L");
$this->SetFont("helvetica", "", 7);
$this->Cell(100, $_h, $GLOBALS["_ape"][0]["cPersApeNom"], "", 0, "L");
$this->SetFont("helvetica", "B", 7);
$this->Cell(18, $_h, " Doc. / Serie:", "", 0, "L");
$this->SetFont("helvetica", "", 7);
$this->Cell(102, $_h, $GLOBALS["_param"]->get("DocNombre").($GLOBALS["_param"]->get("DocSerieF")==""?"":"  ".$GLOBALS["_param"]->get("DocSerieF")), "", 0, "L");
$this->SetFont("helvetica", "B", 7);

$this->axisY += 5;  $this->fnSetAxes( $this->axisY, $this->axisX );  $_h = 5;
$this->SetFont("helvetica", "B", 7);
$this->Cell(8, $_h, "Item", 1, 0, "C", 1);
$this->Cell(22, $_h, "Documento", 1, 0, "C", 1);
$this->Cell(16, $_h, "Fecha", 1, 0, "C", 1);
$this->Cell(12, $_h, "Sede", 1, 0, "C", 1);
$this->Cell(18, $_h, "Cod. Univ.", 1, 0, "C", 1);
$this->Cell(85, $_h, "Concepto", 1, 0, "C", 1);
$this->Cell(10, $_h, "T.P.", 1, 0, "C", 1);
$this->Cell(18, $_h, "Importe", 1, 0, "C", 1);
//$this->setXY(14,60);
if ($this->PageNo()*1 > 1 ) { $this->axisY += 6;  $this->fnSetAxes( $this->axisY, $this->axisX );  $_h = 5; }