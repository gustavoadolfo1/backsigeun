<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class siga_ordenes_detController extends Controller{
    public function siga_ordenes_det_select(Request $data){
        $YearId        = $data->get("YearId");
        $UniEjeId      = $data->get("UniEjeId");
        $OrdenNro      = $data->get("OrdenNro");
        $BstCode       = $data->get("BstCode");
        $OrdenDetItem  = $data->get("OrdenDetItem");
        $BsgCode       = $data->get("BsgCode");
        $BscCode       = $data->get("BscCode");
        $BsfCode       = $data->get("BsfCode");
        $BsiCode       = $data->get("BsiCode");
        $UniMedId      = $data->get("UniMedId");
        $MarcId        = $data->get("MarcId");
        $EspeDetCodigo = $data->get("EspeDetCodigo");
        $SessKey     = $data->get("SessKey");
        $MenuId      = $data->get("MenuId");
        $TypeRecord  = $data->get("TypeRecord"); 
        $TypeQuery   = $data->get("TypeQuery"); 
        $OrderBy     = $data->get("OrderBy");
        $RecordLimit = $data->get("RecordLimit");
        $RecordStart = $data->get("RecordStart");

        $_records = \DB::select('exec siga.[ordenes_det_sp_select] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', array($YearId,$UniEjeId,$OrdenNro,$BstCode,$OrdenDetItem,$BsgCode,$BscCode,$BsfCode,$BsiCode,$UniMedId,$MarcId,$EspeDetCodigo,$SessKey,$MenuId,$TypeRecord,$TypeQuery,$OrderBy,$RecordLimit,$RecordStart));
        return response()->json($_records);
    }
}