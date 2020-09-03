<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class tre_adeudosController extends Controller{
    public function tre_adeudos_select(Request $data){
        $AdeuId           = $data->get("AdeuId");
        $AdeuKey          = $data->get("AdeuKey");
        $AdeuCabId        = $data->get("AdeuCabId");
        $ConcepReqId      = $data->get("ConcepReqId");
        $ConcepEnlacId    = $data->get("ConcepEnlacId");
        $Tablex           = $data->get("Tablex");
        $TablexId         = $data->get("TablexId");
        $Fecha            = $data->get("Fecha");
        $UniEjeId         = $data->get("UniEjeId");
        $FilId            = $data->get("FilId");
        $TipAdeuId        = $data->get("TipAdeuId");
        $PersId           = $data->get("PersId");
        $AdeuCabTablex    = $data->get("AdeuCabTablex");
        $AdeuCabTablexId  = $data->get("AdeuCabTablexId");
        $AdeuCabTabley    = $data->get("AdeuCabTabley");
        $AdeuCabTableyId  = $data->get("AdeuCabTableyId");
        $SessKey     = $data->get("SessKey");
        $MenuId      = $data->get("MenuId");
        $TypeRecord  = $data->get("TypeRecord"); 
        $TypeQuery   = $data->get("TypeQuery"); 
        $OrderBy     = $data->get("OrderBy");
        $RecordLimit = $data->get("RecordLimit");
        $RecordStart = $data->get("RecordStart");


        $_records = \DB::select('exec tre.[adeudos_sp_select] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',array($AdeuId,$AdeuKey,$AdeuCabId,$ConcepReqId,$ConcepEnlacId,$Tablex,$TablexId,$Fecha,$UniEjeId,$FilId,$TipAdeuId,$PersId,$AdeuCabTablex,$AdeuCabTablexId,$AdeuCabTabley,$AdeuCabTableyId,$SessKey,$MenuId,$TypeRecord,$TypeQuery,$OrderBy,$RecordLimit,$RecordStart));
        return response()->json($_records);
    }
}
