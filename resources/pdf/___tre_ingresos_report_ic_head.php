<?php
$this->SetFillColor(192, 192, 192);  $this->SetTextColor(0, 0, 0);
//$this->write1DBarcode($GLOBALS["_ah"][0]["doc_id"].fnZerosLeft($GLOBALS["_ah"][0]["orden_id"],9), "EAN13", 148, 8, 36, 11);
//$this->write2DBarcode($_SESSION["scUsursess_key"]." - ".$_SESSION["scUsuracce_key"], "QRCODE,L", 188, 3, 16, 16, "", "" , true);

$this->setXY(168,22);
$this->SetFont("helvetica", "B", 8);
$this->Cell(11, 5, "DIA", "1", 0, "C", 1);
$this->Cell(11, 5, "MES", "1", 0, "C", 1);
$this->Cell(14, 5, "AÑO", "1", 0, "C", 1);
$this->setXY(168,27);
$this->SetFont("helvetica", "", 8);
//$this->Cell(11, 5, substr($GLOBALS["_ah"][0]["orden_fecha"],8,2), "1", 0, "C");
//$this->Cell(11, 5, substr($GLOBALS["_ah"][0]["orden_fecha"],5,2), "1", 0, "C");
//$this->Cell(14, 5, substr($GLOBALS["_ah"][0]["orden_fecha"],0,4), "1", 0, "C");

$_h = 4.5;
//$this->setXY(14,20);
/*$this->eje_x = 14;  $this->eje_y = 20;
$this->fnEstablecerEjes($this->eje_y, $this->eje_x );
$this->SetFont("helvetica", "B", 10);
$this->Cell(190, $_h, strtoupper($GLOBALS["_ah"][0]["doc_nombre"]), 0, 0, "C");
$this->eje_y += 5;  $this->fnEstablecerEjes( $this->eje_y, $this->eje_x );
$this->SetFont("helvetica", "B", 11);
$this->Cell(190, $_h, $GLOBALS["_ah"][0]["orden_documento"], 0, 0, "C");

$this->eje_y += 8;  $this->fnEstablecerEjes( $this->eje_y, $this->eje_x ); $_h = 5;
$this->SetFont("helvetica", "B", 8);
$this->Cell(18, $_h, " Señor(es)", "LT", 0, "L");
$this->Cell(3,  $_h, ":", "T", 0, "L");
$this->SetFont("helvetica", "B", 8);
$this->Cell(109, $_h, substr(utf8_encode(utf8_decode($GLOBALS["_ah"][0]["pers_nombre"])),0,60), "TR", 0, "L");
$this->Cell(1, $_h, "", 0, 0, "C");
$this->SetFont("helvetica", "B", 8);
$this->Cell(22, 4.5, " Nro SIAF", "LT", 0, "L");
$this->Cell(3, 4.5, ":", "T", 0, "L");
$this->SetFont("helvetica", "B", 9);
$this->Cell(34, 4.5, $GLOBALS["_ah"][0]["expe_nro"], "RT", 0, "L");

$this->eje_y += $_h;  $this->fnEstablecerEjes( $this->eje_y, $this->eje_x );
$this->SetFont("helvetica", "B", 8);
$this->Cell(18, $_h, " Dirección", "L", 0, "L");
$this->Cell(3, $_h, ":", "", 0, "L");
$this->SetFont("helvetica", "", 8);
$this->Cell(109, $_h, substr($GLOBALS["_ah"][0]["pers_domicilio"],0,60), "R", 0, "L");
$this->Cell(1, $_h, "", 0, 0, "C");
$this->SetFont("helvetica", "B", 8);
$this->Cell(22, 4.5, " Certificado", "L", 0, "L");
$this->Cell(3, 4.5, ":", "", 0, "L");
$this->SetFont("helvetica", "B", 8);
$this->Cell(34, 4.5, $GLOBALS["_ah"][0]["certif_nro"], "R", 0, "L");

$this->eje_y += $_h;  $this->fnEstablecerEjes( $this->eje_y, $this->eje_x );
$this->SetFont("helvetica", "B", 8);
$this->Cell(18, $_h, " R.U.C.", "LB", 0, "L");
$this->Cell(3, $_h, ":", "B", 0, "L");
$this->SetFont("helvetica", "", 8);
$this->Cell(31, $_h, $GLOBALS["_ah"][0]["pers_ruc"], "B", 0, "L");
$this->SetFont("helvetica", "B", 8);
$this->Cell(14, $_h, " Teléfono", "B", 0, "L");
$this->Cell(3, $_h, ":", "B", 0, "L");
$this->SetFont("helvetica", "", 8);
$this->Cell(23, $_h, $GLOBALS["_fono"], "B", 0, "L");
$this->SetFont("helvetica", "B", 8);
$this->Cell(12, $_h, " Celular", "B", 0, "L");
$this->Cell(3, $_h, ":", "B", 0, "L");
$this->SetFont("helvetica", "", 8);
$this->Cell(23, $_h, $GLOBALS["_cel"], "BR", 0, "L");
$this->Cell(1, $_h, "", 0, 0, "C");
$this->SetFont("helvetica", "B", 8);
$this->Cell(22, 4.5, " Notificación", "LB", 0, "L");
$this->Cell(3, 4.5, ":", "B", 0, "L");
$this->SetFont("helvetica", "", 8);
$this->Cell(34, 4.5, fnDateDDMMAAAA($GLOBALS["_ah"][0]["orden_fechanotif"] == "" ? $GLOBALS["_ordennotif_fecha"] : $GLOBALS["_ah"][0]["orden_fechanotif"]), "RB", 0, "L");

$this->eje_y += $_h;  $this->fnEstablecerEjes( $this->eje_y, $this->eje_x );  //$_h = 3;
$this->SetFont("helvetica", "B", 8);
$this->Cell(18, 4.5, " U. Orgánica", "LT", 0, "L");
$this->Cell(3,  4.5, ":", "T", 0, "L");
$this->SetFont("helvetica", "", 8);
$this->Cell(109, 4.5, $GLOBALS["_ah"][0]["area_nombre"], "TR", 0, "L");
$this->Cell(1, $_h, "", 0, 0, "C");
$this->SetFont("helvetica", "B", 8);
$this->Cell(22, $_h, " Plazo ".($GLOBALS["_ah"][0]["doc_id"]==516?"Entrega":"Ejecución"), "LT", 0, "L");
$this->Cell(3, $_h, ":", "T", 0, "L");
$this->SetFont("helvetica", "", 8);
//$_plazo_entrega = $GLOBALS["_ah"][0]["orden_plazo"]*1>0 ? $GLOBALS["_ah"][0]["orden_plazo"] . ($GLOBALS["_ah"][0]["orden_plazo"]*1 > 1 ? " Días Calendario" : " Día Calendario") : "**********";
$_plazo_entrega = ($GLOBALS["_ah"][0]["orden_plazo"]*1>0?$GLOBALS["_ah"][0]["orden_plazo"]."  ":"").($GLOBALS["_ah"][0]["tipplaz_nombre"]==""?($GLOBALS["_ah"][0]["orden_plazo"]*1>0?"Días Calendario":"Producto Entregado"):$GLOBALS["_ah"][0]["tipplaz_nombre"]);
$this->Cell(34, $_h, $_plazo_entrega, "TR", 0, "L");

$this->eje_y += $_h;  $this->fnEstablecerEjes( $this->eje_y, $this->eje_x );
$this->SetFont("helvetica", "B", 8);
$this->Cell(18, $_h, " Rubro", "L", 0, "L");
$this->Cell(3, $_h, ":", "", 0, "L");
$this->SetFont("helvetica", "", 8);
$this->Cell(109, $_h, $GLOBALS["_ah"][0]["fuefin_code"]." ".substr($GLOBALS["_ah"][0]["fuefin_nombre"],0,55), "R", 0, "L");
$this->Cell(1, $_h, "", 0, 0, "C");
$this->SetFont("helvetica", "B", 8);
$this->Cell(22, $_h, " Periodo", "L", 0, "L");
$this->Cell(3, $_h, ":", "", 0, "L");
$this->SetFont("helvetica", "", 8);
$_periodo = ($GLOBALS["_ah"][0]["orden_fechaini"] == "" ? "" : fnDateDDMMAAAA($GLOBALS["_ah"][0]["orden_fechaini"]) ." - ". fnDateDDMMAAAA($GLOBALS["_ah"][0]["orden_fechafin"]));
$this->Cell(34, $_h, $_periodo, "R", 0, "L");

$this->eje_y += $_h;  $this->fnEstablecerEjes( $this->eje_y, $this->eje_x );
$this->SetFont("helvetica", "B", 8);
$this->Cell(18, $_h, " Tipo Recur.", "LB", 0, "L");
$this->Cell(3, $_h, ":", "B", 0, "L");
$this->SetFont("helvetica", "", 8);
$this->Cell(109, $_h, $GLOBALS["_ah"][0]["tiprecur_code"]." ".substr($GLOBALS["_ah"][0]["tiprecur_nombre"],0,55), "RB", 0, "L");
$this->Cell(1, 4.5, "", 0, 0, "C");
$this->SetFont("helvetica", "B", 8);
$this->Cell(22, $_h, " Lugar Entrega", "LB", 0, "L");
$this->Cell(3, $_h, ":", "B", 0, "L");
$this->SetFont("helvetica", "", 8);
$this->Cell(34, $_h, $GLOBALS["_ah"][0]["lugentr_nombre"], "RB", 0, "L");*/

$this->axisY += 5;  $this->fnSetAxes( $this->axisY, $this->axisX );  $_h = 5;
$this->SetFont("helvetica", "B", 7);
$this->Cell(6, $_h, "Item", 1, 0, "C", 1);
$this->Cell(21, $_h, "Código", 1, 0, "C", 1);
$this->Cell(97, $_h, "Descripción", 1, 0, "C", 1);
$this->Cell(15, $_h, "Unidad", 1, 0, "C", 1);
$this->Cell(16, $_h, "Cantid", 1, 0, "C", 1);
$this->Cell(18, $_h, "P.U.", 1, 0, "C", 1);
$this->Cell(17, $_h, "Importe", 1, 0, "C", 1);

//$this->setXY(14,60);
if ($this->PageNo()*1 > 1 ) { $this->axisY += 6;  $this->fnSetAxes( $this->axisY, $this->axisX );  $_h = 5; }