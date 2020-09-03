<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class siga_pedidos_detController extends Controller{
    public function siga_pedidos_det_select(Request $data){
        $YearId        = $data->get("YearId");
        $UniEjeId      = $data->get("UniEjeId");
        $BstCode       = $data->get("BstCode");
        $TipPed        = $data->get("TipPed");
        $PedNro        = $data->get("PedNro");
        $PedDetItem    = $data->get("PedDetItem");
        $BsgCode       = $data->get("BsgCode");
        $BscCode       = $data->get("BscCode");
        $BsfCode       = $data->get("BsfCode");
        $BsCode        = $data->get("BsCode");
        $AlmaCode      = $data->get("AlmaCode");
        $EspeDetCodigo = $data->get("EspeDetCodigo");
        $MayCode       = $data->get("MayCode");
        $SubCtaCode    = $data->get("SubCtaCode");
        $PecNro        = $data->get("PecNro");
        $SessKey     = $data->get("SessKey");
        $MenuId      = $data->get("MenuId");
        $TypeRecord  = $data->get("TypeRecord"); 
        $TypeQuery   = $data->get("TypeQuery"); 
        $OrderBy     = $data->get("OrderBy");
        $RecordLimit = $data->get("RecordLimit");
        $RecordStart = $data->get("RecordStart");

        $_records = \DB::select('exec siga.[pedidos_det_sp_select] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', array($YearId,$UniEjeId,$BstCode,$TipPed,$PedNro,$PedDetItem,$BsgCode,$BscCode,$BsfCode,$BsCode,$AlmaCode,$EspeDetCodigo,$MayCode,$SubCtaCode,$PecNro,$SessKey,$MenuId,$TypeRecord,$TypeQuery,$OrderBy,$RecordLimit,$RecordStart));
        return response()->json($_records);
    }
}