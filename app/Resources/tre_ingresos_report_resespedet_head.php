<?php
$this->SetFillColor(192, 192, 192);  $this->SetTextColor(0, 0, 0);
//$this->write1DBarcode($GLOBALS["_ah"][0]["doc_id"].fnZerosLeft($GLOBALS["_ah"][0]["orden_id"],9), "EAN13", 148, 8, 36, 11);
//$this->write2DBarcode($_SESSION["scUsursess_key"]." - ".$_SESSION["scUsuracce_key"], "QRCODE,L", 188, 3, 16, 16, "", "" , true);

$_h = 4;
$this->axisX = 14;  $this->axisY = 20;
$this->fnSetAxes($this->axisY, $this->axisX );
$this->SetFont("helvetica", "B", 10);
$this->Cell(190, $_h, "RESUMEN DE INGRESOS POR DOCUMENTO", 0, 0, "C");

$this->axisY += 6;  $this->fnSetAxes( $this->axisY, $this->axisX );
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
$this->Cell(62, $_h, $_filial.$_periodo, "", 0, "L");
$this->SetFont("helvetica", "B", 7);
$this->Cell(16, $_h, "Cajero:", "", 0, "L");
$this->SetFont("helvetica", "", 7);
if ( isset($GLOBALS["_au"][0]["cPersDocumento"]) ) {
    $this->Cell(100, $_h, $GLOBALS["_au"][0]["cPersDocumento"] ." - ".$GLOBALS["_au"][0]["cPersApeNom"], "", 0, "L");
} else {
    $this->Cell(100, $_h, "", "", 0, "L");
}

$this->axisY += 4;  $this->fnSetAxes( $this->axisY, $this->axisX );
$this->SetFont("helvetica", "B", 7);
$this->Cell(18, $_h, " Doc. / Serie:", "", 0, "L");
$this->SetFont("helvetica", "", 7);
$this->Cell(62, $_h, $GLOBALS["_param"]->get("DocNombre").($GLOBALS["_param"]->get("DocSerieF")==""?"":"  ".$GLOBALS["_param"]->get("DocSerieF")), "", 0, "L");
$this->SetFont("helvetica", "B", 7);
$this->Cell(16, $_h, "U. Orgánica:", "", 0, "L");
$this->SetFont("helvetica", "", 7);
$this->Cell(100, $_h, $GLOBALS["_param"]->get("DepenNombre"), "", 0, "L");

//$this->SetFont("helvetica", "B", 7);
//$this->Cell(18, $_h, "Clasificador:", "", 0, "L");
//$this->SetFont("helvetica", "", 7);
//$this->Cell(100, $_h, $GLOBALS["_param"]->get("EspeDetCodeName"), "", 0, "L");

$this->axisY += 5;  $this->fnSetAxes( $this->axisY, $this->axisX );  $_h = 5;
$this->SetFont("helvetica", "B", 7);
$this->Cell(8, $_h, "Item", 1, 0, "C", 1);
$this->Cell(20, $_h, "Documento", 1, 0, "C", 1);
$this->Cell(16, $_h, "Fecha", 1, 0, "C", 1);
$this->Cell(18, $_h, "Importe", 1, 0, "C", 1);
$_col = 0;
foreach ($GLOBALS["_aedg"] as $row){ $_col++;
    $this->Cell(17, $_h, $row["cEspeDetCodigo"], 1, 0, "C", 1);
}

$_col = (count($GLOBALS["_aedg"]) <= 7 ? 7 : 14)-$_col;
for ($i = 0; $i < $_col; $i++) {
    $this->Cell(17, $_h, "", 1, 0, "C", 1);
}
//$this->setXY(14,60);
if ($this->PageNo()*1 > 1 ) { $this->axisY += 6;  $this->fnSetAxes( $this->axisY, $this->axisX );  $_h = 5; }