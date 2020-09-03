<?php
$this->SetFillColor(192, 192, 192);  $this->SetTextColor(0, 0, 0);
//$this->write1DBarcode($GLOBALS["_ah"][0]["doc_id"].fnZerosLeft($GLOBALS["_ah"][0]["orden_id"],9), "EAN13", 148, 8, 36, 11);
//$this->write2DBarcode($_SESSION["scUsursess_key"]." - ".$_SESSION["scUsuracce_key"], "QRCODE,L", 188, 3, 16, 16, "", "" , true);

$_h = 4;
$this->axisX = 14;  $this->axisY = 20;
$this->fnSetAxes($this->axisY, $this->axisX );
$this->SetFont("helvetica", "B", 10);
$this->Cell(273, $_h, "REGISTRO CONCEPTOS DE INGRESO x UNIDAD ORGANICA", 0, 0, "C");

$this->axisY += 6;  $this->fnSetAxes( $this->axisY, $this->axisX );
$this->SetFont("helvetica", "B", 7);
$this->Cell(18, $_h, " Clasificador:", "", 0, "L");
$this->SetFont("helvetica", "", 7);
$this->Cell(130, $_h, $GLOBALS["_param"]->get("EspeDetCodeName"), "", 0, "L");

$this->axisY += 5;  $this->fnSetAxes( $this->axisY, $this->axisX );  $_h = 5;
$this->SetFont("helvetica", "B", 7);
$this->Cell(8, $_h, "Item", 1, 0, "C", 1);
$this->Cell(80, $_h, "Concepto", 1, 0, "C", 1);
$this->Cell(38, $_h, "Ticket", 1, 0, "C", 1);
$this->Cell(23, $_h, "Código", 1, 0, "C", 1);
$this->Cell(16, $_h, "Doc. Gest.", 1, 0, "C", 1);
$this->Cell(8, $_h, "Est.", 1, 0, "C", 1);
$this->Cell(12, $_h, "% UIT", 1, 0, "C", 1);
$this->Cell(15, $_h, "Importe", 1, 0, "C", 1);
$this->Cell(15, $_h, "Impt. UIT", 1, 0, "C", 1);
$this->Cell(10, $_h, "Aprx.", 1, 0, "C", 1);
$this->Cell(8, $_h, "Dec.", 1, 0, "C", 1);
$this->Cell(8, $_h, "OE", 1, 0, "C", 1);
$this->Cell(8, $_h, "MPU", 1, 0, "C", 1);
$this->Cell(8, $_h, "OExt", 1, 0, "C", 1);
$this->Cell(8, $_h, "PPrg", 1, 0, "C", 1);
$this->Cell(8, $_h, "PUO", 1, 0, "C", 1);

if ($this->PageNo()*1 == 1 ) { $this->axisY += 1; } else { $this->axisY += 5; }
$this->fnSetAxes( $this->axisY, $this->axisX );