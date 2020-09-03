<?php
namespace App\Resources;

class Functions {
	public function __construct(){

	}
	
	function fnDateAddDays( $pdFecha, $pnNumero = 0, $pcFormat = "") {
		if ( $pcFormat == "SQL" ) {
			$_return = date( "Y-m-d", strtotime("+" .$pnNumero. " day", strtotime($pdFecha)) );
		} else {
			$_return = date( "d/m/Y", strtotime("+" .$pnNumero. " day", strtotime($pdFecha)) );
		} 
		return $_r;
	}

	function fnDateDDMMAAAA( $pdFech ) {
		if ( $pdFech == "" ) {
			$_return = "";
		} else {
			$laData = explode("-", $pdFech);
			$_return = str_pad($laData[2], 2, "0", STR_PAD_LEFT) ."/". str_pad($laData[1], 2, "0", STR_PAD_LEFT) ."/". $laData[0];
			//$_return = "DAY";
		}
		return $_return;
	}

	function fnDateLetters( $pnDsm, $pnDia, $pnMes, $pnAno ) {
		$_return = fnDayWeekLetters($pnDsm) .', '. $pnDia . ' de ' . fnMonthLetters($pnMes) . ' del ' . $pnAno;
		return $_return;
	}

	function fnDateSQL( $pdFech ) {
		if ( $pdFech == "" ) {
			$_return = "";
		} else {
			$laData = split( "/", $pdFech );
			$_return = $laData[2] ."-". $laData[1] ."-". $laData[0];
		}
		return $_return;
	}

	function fnDateSQLReturnDay( $pdFecha ) {
		if ( $pdFecha == "" ) { $_return = ""; } 
		else { $_return = substr($pdFecha, 8, 2);
		}
		return $_return;
	}

	function fnDateSQLReturnMonthLetters( $pdFecha ) {
		if ( $pdFecha == "" ) { 
			$_return = ""; 
		} else { 
			$laMeses = Array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre');
			$_return = $laMeses[(substr($pdFecha, 5, 2)*1)-1];
		}
		return $_return;
	}

	function fnDateSQLReturnYear( $pdFecha ) {
		if ( $pdFecha == "" ) { $_return = ""; } 
		else { $_return = substr($pdFecha, 0, 4);
		}
		return $_return;
	}

	function fnDatesDifference( $diff, $pcTipo ) {
		$segundos = $diff % 60;
		$segundos = str_pad( $segundos, 2, "0", STR_PAD_LEFT );
		$diff     = floor($diff / 60);
		$minutos  = $diff % 60;
		$minutos  = str_pad($minutos, 2, "0", STR_PAD_LEFT);
		$diff     = floor($diff / 60);
		$horas    = $diff;
		$_return   = ( pcTipo == "" ) ? $horas .":". $minutos .":". $segundos : $horas;
		return $_return;
	}

	function fnDateTimeDiff($_dt1, $_dt2, $_return="DHM"){
		$_unixI = strtotime($_dt1); $_unixF = strtotime($_dt2); $_diff = $_unixF - $_unixI; $_r = "";
		$_d = (int)($_diff / 86400);
		if ( $_d > 0 ){ $_diff -= (86400*$_d); }
		$_h = (int)($_diff / 3600);
		if ( $_h > 0 ){ $_diff -= (3600*$_h); }
		$_m = (int)($_diff / 60);
		if ( $_m > 0 ){ $_diff -= (60*$_m); }
		$_s = $_diff;

		if ( $_return == "DH" ) { $_r = ($_d*1>0?$_d."d":"") . ($_h*1>0?($_d*1>0?", ":"").$_h."h":""); }
		else if ( $_return == "DHM" ) { $_r = ($_d*1>0?$_d."d":"") . ($_h*1>0?($_d*1>0?", ":"").$_h."h":""). ($_m*1>0?($_h*1>0?", ":"").$_m."m":""); }
		else if ( $_return == "oh" ) { $_r = (int)(($_unixF - $_unixI) / 3600); }
		return $_r;
	}

	function fnDatesRange( $pdFech, $pdFe01, $pdFe02 ) {
		$fe = fnDTOS( $pdFech );
		$f1 = fnDTOS( $pdFe01 );
		$f2 = fnDTOS( $pdFe02 );
		if ( $f2 == 0 ) { $f2 = 99999999; }
		if ( $fe >= $f1 && $fe <= $f2 ) { return true; }
		else                            { return false; }
	}

	function fnDayWeekLetters( $pnDsm ) {
		$_return = Array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo');
		return $_return[$pnDsm];
	}

	function fnDayWeekLettersTilde( $pnDsm ) {
		$_return = Array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
		return $_return[$pnDsm];
	}

	function fnDTOS( $pdFech ) {
		if ( pdFech == "" ) { $lcRetu = 0; } else { $lcRetu = substr($pdFech,0,4) . substr($pdFech,5,2) . substr($pdFech,8,2); }
		return $lcRetu;
	}

	function fnEmptyIfZero( $pnNume ) {
		if ( $pnNume == 0 ) { return ""; } else { return number_format( $pnNume, 2 ); }
	}

	function fnEncrypta( $pcCadena, $pcTipo ) {
		$lc = "";
		$ln = strlen(trim($pcCadena));
		for ( $i=0; $i<$ln; $i++ ) {
			$c = substr($pcCadena, $i, 1);
			$c = ord($c)*1;

			if ( $pcTipo == "E" ) {
				if      ( $c <= 16  ) { $c += 16; }
				else if ( $c <= 32  ) { $c -= 16; }
				else if ( $c <= 48  ) { $c += 16; }
				else if ( $c <= 64  ) { $c -= 16; }
				else if ( $c <= 80  ) { $c += 16; }
				else if ( $c <= 96  ) { $c -= 16; }
				else if ( $c <= 112 ) { $c += 16; }
				else if ( $c <= 128 ) { $c -= 16; }
				else if ( $c <= 144 ) { $c += 16; }
				else if ( $c <= 160 ) { $c -= 16; }
				else if ( $c <= 176 ) { $c += 16; }
				else if ( $c <= 192 ) { $c -= 16; }
				else if ( $c <= 208 ) { $c += 16; }
				else if ( $c <= 224 ) { $c -= 16; }
				else if ( $c <= 240 ) { $c += 16; }
				else if ( $c <= 256 ) { $c -= 16; }
				
				if      ( $c <= 32 )  { $c += 32; }
				else if ( $c <= 64 )  { $c -= 32; }
				else if ( $c <= 96 )  { $c += 32; }
				else if ( $c <= 128 ) { $c -= 32; }
				else if ( $c <= 160 ) { $c += 32; }
				else if ( $c <= 192 ) { $c -= 32; }
				else if ( $c <= 224 ) { $c += 32; }
				else if ( $c <= 256 ) { $c -= 32; }

				if      ( $c <= 64  ) { $c += 64; }
				else if ( $c <= 128 ) { $c -= 64; }
				else if ( $c <= 192 ) { $c += 64; }
				else if ( $c <= 256 ) { $c -= 64; }
			} else if ( $pcTipo == "D" ) {
				if      ( $c <= 64  ) { $c += 64; }
				else if ( $c <= 128 ) { $c -= 64; }
				else if ( $c <= 192 ) { $c += 64; }
				else if ( $c <= 256 ) { $c -= 64; }

				if      ( $c <= 32 )  { $c += 32; }
				else if ( $c <= 64 )  { $c -= 32; }
				else if ( $c <= 96 )  { $c += 32; }
				else if ( $c <= 128 ) { $c -= 32; }
				else if ( $c <= 160 ) { $c += 32; }
				else if ( $c <= 192 ) { $c -= 32; }
				else if ( $c <= 224 ) { $c += 32; }
				else if ( $c <= 256 ) { $c -= 32; }

				if      ( $c <= 16  ) { $c += 16; }
				else if ( $c <= 32  ) { $c -= 16; }
				else if ( $c <= 48  ) { $c += 16; }
				else if ( $c <= 64  ) { $c -= 16; }
				else if ( $c <= 80  ) { $c += 16; }
				else if ( $c <= 96  ) { $c -= 16; }
				else if ( $c <= 112 ) { $c += 16; }
				else if ( $c <= 128 ) { $c -= 16; }
				else if ( $c <= 144 ) { $c += 16; }
				else if ( $c <= 160 ) { $c -= 16; }
				else if ( $c <= 176 ) { $c += 16; }
				else if ( $c <= 192 ) { $c -= 16; }
				else if ( $c <= 208 ) { $c += 16; }
				else if ( $c <= 224 ) { $c -= 16; }
				else if ( $c <= 240 ) { $c += 16; }
				else if ( $c <= 256 ) { $c -= 16; }            
			}

			$lc = $lc ."". chr($c);
		}
		return $lc;
	}

	function fnExecuteSQL( $pc ) {
		echo $lcCade;
	}

	function fnFirstDayYear( $ldFech ) {
		$_return = substr($ldFech, 0, 5) . "01-01";
		return $_return;
	}

	function fnFirstDayMonth( $ldFech ) {
		$_return = substr($ldFech, 0, 8) . "01";
		return $_return;
	}

	function fnGetColumnExcel( $piCol ) {
		$_ac = Array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
		if ( $piCol*1 <= 25 ) {
			$_return = $_ac[$piCol];
		} else {
			$_1 = floor($piCol / 25)*1 - 1;
			$_2 = ($piCol % 25)*1 - 1;
			$_return = $_ac[$_1].$_ac[$_2];
		}
		return $_return;
	}

	function fnGetRealIP() {
		if ( !empty($_SERVER["HTTP_CLIENT_IP"]) ) return $_SERVER["HTTP_CLIENT_IP"];
		if ( !empty($_SERVER["HTTP_X_FORWARDED_FOR"]) ) return $_SERVER["HTTP_X_FORWARDED_FOR"];
		return $_SERVER["REMOTE_ADDR"];
	}
		
	function fnHoursElapsed( $pcFere, $pcHore ) {
		$ti01 = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
		$lcF = fnDTOS( $pcFere );
		$lcH = fnTTOS( $pcHore );
		$ti02 = mktime( substr($lcH,0,2)*1, substr($lcH,2,2)*1, substr($lcH,4,2)*1,
						substr($lcF,4,2)*1, substr($lcF,6,2)*1, substr($lcF,0,4)*1 );
		$ln = $ti01 - $ti02;
		return fnDiferenciaFechas( $ln, 'H' );
	}

	function fnLatestDayMonth($Year,$Month) {
		return date("d", (mktime(0,0,0,$Month+1,1,$Year)-1));
	}

	function fnMonthLetters( $pnMes ) {
		$_return = Array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre');
		return $_return[$pnMes];
	}

	function fnNumFormat( $pnNume, $pnNdec = 2 ) {
		if ($pnNume == 0) {
			return "";
		} else {
			return number_format( $pnNume, $pnNdec );
		}
	}

	function fnPadLeft( $pnNume, $pnLeng, $pcCade ) {
		if ( $pnNume*1 > 0 ) {
			return sprintf( "%0" . $pnCero."s", $pnNume );
		} else {
			return "";
		}
	}

	function fnPhp2js ($var) {
		if (is_array($var)) {
			$res = "[";
			$array = array();
			foreach ($var as $a_var) {
				$array[] = php2js($a_var);
			}
			return "[" . join(",", $array) . "]";
		}
		elseif (is_bool($var)) {
			return $var ? "true" : "false";
		}
		elseif (is_int($var) || is_integer($var) || is_double($var) || is_float($var)) {
			return $var;
		}
		elseif (is_string($var)) {
			return "\"" . addslashes(stripslashes($var)) . "\"";
		}

		return FALSE;
	}

	function fnRepeat( $pcChar, $pnNume ) {
		$_return = "";
		for ( $i=1; $i <= $pnNume*1; $i++ ) { $_return = $_return ."". $pcChar; }     
		return $_return;
	}

	function fnRoundDosDec( $pcNume ) {
		$_return = round($pcNume * 100) / 100;
		return $_return;
	}

	function fnTTOS( $pcHora ) {
		if ( pcHora == "" ) { $_return = 0; }
		else                { $_return = substr($pcHora,0,2) . substr($pcHora,3,2) . substr($pcHora,6,2); }
		return $_return;
	}

	function fnSheetColum( $pn ) {
		$_return = Array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ");
		return $_return[$pn];
	}

	function fnSQLChar( $pcCadena ) {
		$_return = trim($pcCadena);

		//$_return = str_replace("\", "\", $pcCadena);
		$_return = str_replace("'", "\'", $pcCadena);
		$_return = str_replace(",", "\,", $pcCadena);

		return $_return;
	}

	function fnZerosLeft( $pnNume, $pnCero ){
		if ( $pnNume == "" ) {
			return "";
		} else {
			return sprintf("%0" . $pnCero."s", $pnNume);
		}
	}
}