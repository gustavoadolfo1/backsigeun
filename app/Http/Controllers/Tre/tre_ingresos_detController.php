<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class tre_ingresos_detController extends Controller{
    public function tre_ingresos_det_select(Request $data){
        $IngDetId      = $data->get("IngDetId");
        $IngDetKey     = $data->get("IngDetKey");
        $IngId         = $data->get("IngId");
        $IngDetItem    = $data->get("IngDetItem");
        $ConcepReqId   = $data->get("ConcepReqId");
        $Tablex        = $data->get("Tablex");
        $TablexId      = $data->get("TablexId");
        $EspeDetId     = $data->get("EspeDetId");
        $IngKey        = $data->get("IngKey");
        $UniEjeId      = $data->get("UniEjeId");
        $FilId         = $data->get("FilId");
        $DocId         = $data->get("DocId");
        $DocSerie      = $data->get("DocSerie");
        $FechaIni      = $data->get("FechaIni");
        $FechaFin      = $data->get("FechaFin");
        $PersId        = $data->get("PersId");
        $EstudId       = $data->get("EstudId");
        $TipPagId      = $data->get("TipPagId");
        $IngCredDepen  = $data->get("CredDepenKey");
        $ConcepReqKey  = $data->get("ConcepReqKey");
        $ConcepImptId  = $data->get("ConcepImptId");
        $SessKey     = $data->get("SessKey");
        $MenuId      = $data->get("MenuId");
        $TypeRecord  = $data->get("TypeRecord");
        $TypeQuery   = $data->get("TypeQuery");
        $OrderBy     = $data->get("OrderBy");
        $RecordLimit = $data->get("RecordLimit");
        $RecordStart = $data->get("RecordStart");

        $_records = \DB::select('exec tre.[ingresos_det_sp_select] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',array($IngDetId,$IngDetKey,$IngId,$IngDetItem,$ConcepReqId,$Tablex,$TablexId,$EspeDetId,$IngKey,$UniEjeId,$FilId,$DocId,$DocSerie,$FechaIni,$FechaFin,$PersId,$EstudId,$TipPagId,$IngCredDepen,$ConcepReqKey,$ConcepImptId,$SessKey,$MenuId,$TypeRecord,$TypeQuery,$OrderBy,$RecordLimit,$RecordStart));
        return response()->json($_records);
    }
}