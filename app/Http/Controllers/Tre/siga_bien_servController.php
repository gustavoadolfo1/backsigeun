<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class siga_bien_servController extends Controller{
    public function siga_bien_serv_select(Request $data){
        $UniEjeId     = $data->get("UniEjeId");
        $BstCode     = $data->get("BstCode");
        $BsgCode     = $data->get("BsgCode");
        $BscCode     = $data->get("BscCode");
        $BsfCode     = $data->get("BsfCode");
        $BsiCode     = $data->get("BsiCode");
        $BsNombre    = $data->get("BsNombre");
        $SessKey     = $data->get("SessKey");
        $MenuId      = $data->get("MenuId");
        $TypeRecord  = $data->get("TypeRecord"); 
        $TypeQuery   = $data->get("TypeQuery"); 
        $OrderBy     = $data->get("OrderBy");
        $RecordLimit = $data->get("RecordLimit");
        $RecordStart = $data->get("RecordStart");

        $_records = \DB::select('exec siga.[bien_serv_sp_select] ?,?,?,?,?,?,?,?,?,?,?,?,?,?', array($UniEjeId,$BstCode,$BsgCode,$BscCode,$BsfCode,$BsiCode,$BsNombre,$SessKey,$MenuId,$TypeRecord,$TypeQuery,$OrderBy,$RecordLimit,$RecordStart));
        return response()->json($_records);
    }
}