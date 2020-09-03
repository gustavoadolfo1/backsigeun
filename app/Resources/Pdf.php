<?php
namespace App\Resources;
use Illuminate\Http\Request;
use TCPDF;
//require_once "../db/budget_unidades_ejecutoras.php";
//date_default_timezone_set("America/Lima");

Class PDF extends TCPDF {
	public $startX = 14;  public $startY = 20;  public $axisX = 14;  public $axisY = 20;  
	public $h_row = 0;  public $max = 0;  public $pdf_group = "";
	public $title1 = "";  public $title2 = "";  public $title3 = "";

	private $printer_header = "1";
	private $printer_footer = "1";
	private $file_header = "";

	public function setPrinter_header( $val ) { $this->printer_header = $val; }
	public function setPrinter_footer( $val ) { $this->printer_footer = $val; }
	public function setTitle1($val){ $this->title1 = $val; }
	public function setTitle2($val){ $this->title2 = $val; }
	public function setTitle3($val){ $this->title3 = $val; }
	public function setFile_header($val){ $this->file_header = $val; }

	function Header(){
		if ($this->printer_header == "1" ) {
			/*$ob = new Budget_Unidades_Ejecutoras();
			$ob->setUnieje_key($_SESSION["scUnieje_key"]);
			$ob->setType_record("rep_head");
			$_aue = $ob->registros();*/

			//$this->Image("../../resources/images/".$_aue[0]["unieje_logo"], 14, 8, 11, 10);
			$this->Image("../resources/images/escudo.jpg", 14, 8, 10, 9);
			$this->SetFont("helvetica", "", 7);
			$this->setXY(26, 8);
			//$this->Cell( 260 ,4, $this->title1==""?$_aue[0]["unieje_nombre"]:$this->title1, "", 0, "L", false);
			$this->Cell( 260 ,4, $this->title1==""?"Universidad Nacional de Moquegua":$this->title1, "", 0, "L", false);
			$this->setXY(26, 11);
			$this->SetFont("helvetica", "", 6);
			$this->Cell( 260, 4, $this->title2==""?"20449347448":$this->title2, "", 0, "L", false);
			$this->setXY(26, 14);
			$this->SetFont("helvetica", "", 6);
			//$this->Cell( 260, 4, $this->title3==""?$_aue[0]["rep_head3"]:$this->title3, "", 0, "L", false);
			$this->Cell( 260, 4, $this->title3==""?"SIGEUN v1.0.5":$this->title3, "", 0, "L", false);
		}

		if ( $this->file_header != "" ) {
			include $this->file_header;
		}
	}

	function Footer() {
		$this->SetFillColor(192,192,192);
		if ( $this->printer_footer == "1" ) {
			$this->SetXY(12, -8); $this->SetFont("helvetica", "B", 5);
			$this->Cell(1);

			$_npag = "Pág. ".$this->PageNo()." / ".$this->getAliasNbPages();
			$_dia = date("d")."/".date("m")."/".date("Y"); 
			$_hora = date("H").":".date("i").":".date("s");
			//$_usur = $_SESSION["scUsursess_key"]." - ".$_SESSION["scUsuracce_key"];
			$_usur = "sigeun"." - "."v1.0.5";
			$this->Cell( 150 , 4, $_npag."  *  [ ".$_usur." ]", 0, 0, "L", false); 
		}
	}
	
    public function MultiRow($_width, $_text, $_border, $_topmargin = ""){
        $page_1 = $this->getPage();
        $y_end_1 = $this->GetY();
        $y_start = $this->GetY();
        if ($_topmargin != "" ){ $this->SetTopMargin($_topmargin); }
        $this->MultiCell($_width, 0, $_text, $_border, 'J', 0, 1, $this->GetX(), $y_start, true, 0);

        $page_2 = $this->getPage();
        $y_end_2 = $this->GetY();

        // set the new row position by case
        if (max($page_1,$page_2) == $page_start) { $ynew = max($y_end_1, $y_end_2); } 
        elseif ($page_1 == $page_2) { $ynew = max($y_end_1, $y_end_2); } 
        elseif ($page_1 > $page_2) { $ynew = $y_end_1; } 
        else { $ynew = $y_end_2; }

        //$this->setPage(max($page_1,$page_2));
        //$this->SetXY($this->GetX(),$ynew);
        $this->setPage(max($page_1,$page_2));
        $this->SetXY($this->GetX(),62);
    }
	
	public function print_celda($value, $x, $y, $dim, $alto_linea = 4, $align='C', $max_chars = 50){
		$this->setXY($x,$y);
		$lines = wordwrap($value, $max_chars ,'_');
		$lines = explode('_',$lines);
		foreach($lines as $k => $line){
			if(strlen($line)> $max_chars){
				$lines[$k] = chunk_split($line, ($max_chars-3)).'..';
			}
		}
			
		$px = $x;
		$py = $y;
		$n_lineas = sizeof($lines);
		$border = 'TRL';
		if ( $n_lineas > 1) {
			foreach($lines as $k =>  $line) {
				if ($k == 0) {
					$border = 'TRL';
				} else {
					$border = 'RL';
				}
				$this->Cell($dim,$alto_linea, $line,$border,0, $align, false);
				$py+=$alto_linea;
				$this->setXY($px,$py);
			}
		} else {
			$this->Cell($dim,$alto_linea, $value,$border,0, $align , false);
		}
		return $n_lineas;
	} 

	/*public function emparejar($xi,$yi, $n_lineas, $altura_linea ){       
		$this->setXY($xi,$yi);
		for($i = 0; $i<=$n_lineas; $i++){
			$border = ($i == $n_lineas) ? 'BRL' : 'RL';
			$this->Cell($dim,$alto_linea, $line,$border,0,'C', false);
			$py+=$alto_linea;
			$this->setXY($px,$py);
		  }
	}*/

	public function fnSetAxes( $pnYyyy, $pnXxxx ) {
		$this->SetY( $pnYyyy );
		$this->SetX( $pnXxxx );
	}

	public function fnNewPage( $h = 0 ) {
		if ( $h == 0 ) {
			if ( $this->axisY*1 >= $this->max*1 ) {
				$this->axisY = $this->startY;
				$this->AddPage();
			}
		} else {
			if ( ($this->axisY*1 + $h*1) >= $this->max*1 ) {
				$this->axisY = $this->startY;
				$this->AddPage();
			}
		}
	}
}