<?php
$this->SetFillColor(192, 192, 192);  $this->SetTextColor(0, 0, 0);

//$this->setCellPaddings(2, 1, 2, 1);
//$this->setCellPaddings(0, 0, 0, 0);

$_h = 6;
$this->axisX = 20;  $this->axisY = 10;
$this->fnSetAxes($this->axisY, $this->axisX);

$this->SetFont("helvetica", "B", 8);
$this->MultiCell(30, 20, "", 1, 'J', 0, 0, "", "", true); 
$this->Cell(100, $_h, "DENOMINACION DEL PROCEDIMIENTO", 1, 0, "C", 1);
$this->Cell(40, $_h, "CODIGO", 1, 0, "C", 1);
$this->Image("../resources/images/escudox.jpg", 23, 11, 24, 18);

$this->axisX = 50;  $this->axisY = 16;
$this->fnSetAxes($this->axisY, $this->axisX);
$this->MultiCell(100, 14, $GLOBALS["_ac"][0]["cConcepNombre"], 1, 'C', 0, 0, '', '', true, 0, false, true, 14, 'M');
$this->MultiCell(40, 14, $GLOBALS["_ac"][0]["cConcepCodigo"], 1, 'C', 0, 0, '', '', true, 0, false, true, 14, 'M');


$this->SetFont("helvetica", "B", 8);

$this->axisX = 20;  $this->axisY = 30;
$this->fnSetAxes( $this->axisY, $this->axisX );