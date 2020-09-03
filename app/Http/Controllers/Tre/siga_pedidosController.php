<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class siga_pedidosController extends Controller{
    public function siga_pedidos_select(Request $data){
        $YearId      = $data->get("YearId");
        $UniEjeId    = $data->get("UniEjeId");
        $BstCode     = $data->get("BstCode");
        $TipPed      = $data->get("TipPed");
        $PedNro      = $data->get("PedNro");
        $CenCosCode  = $data->get("CenCosCode");
        $FechaIni    = $data->get("FechaIni");
        $FechaFin    = $data->get("FechaFin");
        $SecFuncCode = $data->get("SecFuncCode");
        $FueFinCode  = $data->get("FueFinCode");
        $ActProyCode = $data->get("ActProyCode");
        $SessKey     = $data->get("SessKey");
        $MenuId      = $data->get("MenuId");
        $TypeRecord  = $data->get("TypeRecord"); 
        $TypeQuery   = $data->get("TypeQuery"); 
        $OrderBy     = $data->get("OrderBy");
        $RecordLimit = $data->get("RecordLimit");
        $RecordStart = $data->get("RecordStart");

        $_records = \DB::select('exec siga.[pedidos_sp_select] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', array($YearId,$UniEjeId,$BstCode,$TipPed,$PedNro,$CenCosCode,$FechaIni,$FechaFin,$SecFuncCode,$FueFinCode,$ActProyCode,$SessKey,$MenuId,$TypeRecord,$TypeQuery,$OrderBy,$RecordLimit,$RecordStart));
        return response()->json($_records);
    }
}