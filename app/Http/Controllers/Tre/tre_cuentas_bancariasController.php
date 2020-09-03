<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class tre_cuentas_bancariasController extends Controller{
    public function tre_cuentas_bancarias_select(Request $data){
        $CuenBancId    = $data->get("CuenBancId");
        $CuenBancKey   = $data->get("CuenBancKey");
        $UniEjeId      = $data->get("UniEjeId");
        $EntiBancId    = $data->get("EntiBancId");
        $TipCuenBancId = $data->get("TipCuenBancId");
        $MoneId        = $data->get("MoneId");
        $CueBancNro    = $data->get("CueBancNro");
        $CueBancCci    = $data->get("CueBancCci");
        $UniEjeKey     = $data->get("UniEjeKey");
        $SessKey     = $data->get("SessKey");
        $MenuId      = $data->get("MenuId");
        $TypeRecord  = $data->get("TypeRecord"); 
        $TypeQuery   = $data->get("TypeQuery"); 
        $OrderBy     = $data->get("OrderBy");
        $RecordLimit = $data->get("RecordLimit");
        $RecordStart = $data->get("RecordStart");

        $_records = \DB::select('exec tre.[cuentas_bancarias_sp_select] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',
        array($CuenBancId,$CuenBancKey,$UniEjeId,$EntiBancId,$TipCuenBancId,$MoneId,$CueBancNro,$CueBancCci,$UniEjeKey,$SessKey,$MenuId,$TypeRecord,$TypeQuery,$OrderBy,$RecordLimit,$RecordStart));
        return response()->json($_records);
    }
}