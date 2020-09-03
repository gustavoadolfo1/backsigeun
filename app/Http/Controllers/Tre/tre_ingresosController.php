<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class tre_ingresosController extends Controller{
    public function tre_ingresos_annular(Request $data){
        $IngId     = $data->get("IngId");
        $IngKey    = $data->get("IngKey");
        $IngObserv = $data->get("IngObserv");
        $Password  = $data->get("Password");
        $SessKey = $data->get("SessKey");
        $MenuId  = $data->get("MenuId");

        $_id = \DB::select('exec tre.[ingresos_sp_annular] ?,?,?,?,?,?', array($IngId,$IngKey,$IngObserv,$Password,$SessKey,$MenuId));
        return $_id;
    }

    public function tre_ingresos_delete(Request $data){
        $IngId     = $data->get("IngId");
        $IngKey    = $data->get("IngKey");
        $IngObserv = $data->get("IngObserv");
        $Password  = $data->get("Password");
        $SessKey = $data->get("SessKey");
        $MenuId  = $data->get("MenuId");

        $_id = \DB::select('exec tre.[ingresos_sp_delete] ?,?,?,?,?,?', array($IngId,$IngKey,$IngObserv,$Password,$SessKey,$MenuId));
        return $_id;
    }

    public function tre_ingresos_select(Request $data, $param = ""){
        if ( $param == "" ) {
            $IngId           = $data->get("IngId");
            $IngKey          = $data->get("IngKey");
            $UniEjeId        = $data->get("UniEjeId");
            $FilId           = $data->get("FilId");
            $DocId           = $data->get("DocId");
            $DocSerie        = $data->get("DocSerie");
            $DocNro          = $data->get("DocNro");
            $FechaIni        = $data->get("FechaIni");
            $FechaFin        = $data->get("FechaFin");
            $PersId          = $data->get("PersId");
            $EstudId         = $data->get("EstudId");
            $TipPagId        = $data->get("TipPagId");
            $IngCredDepen    = $data->get("IngCredDepen");
            $IngCredDepenKey = $data->get("IngCredDepenKey");
            $ConcepReqId     = $data->get("ConcepReqId");
            $ConcepImptId    = $data->get("ConcepImptId");
            $ConcepId        = $data->get("ConcepId");
            $DepenId         = $data->get("DepenId");
            $EspeDetId       = $data->get("EspeDetId");
            $SessKey     = $data->get("SessKey");
            $MenuId      = $data->get("MenuId");
            $TypeRecord  = $data->get("TypeRecord");
            $TypeQuery   = $data->get("TypeQuery");
            $OrderBy     = $data->get("OrderBy");
            $RecordLimit = $data->get("RecordLimit");
            $RecordStart = $data->get("RecordStart");
        } else {
            $IngId           = isset($param["IngId"]) ? $param["IngId"] : "";
            $IngKey          = isset($param["IngKey"]) ? $param["IngKey"] : "";
            $UniEjeId        = isset($param["UniEjeId"]) ? $param["UniEjeId"] : "";
            $FilId           = isset($param["FilId"]) ? $param["FilId"] : "";
            $DocId           = isset($param["DocId"]) ? $param["DocId"] : "";
            $DocSerie        = isset($param["DocSerie"]) ? $param["DocSerie"] : "";
            $DocNro          = isset($param["DocNro"]) ? $param["DocNro"] : "";
            $FechaIni        = isset($param["FechaIni"]) ? $param["FechaIni"] : "";
            $FechaFin        = isset($param["FechaFin"]) ? $param["FechaFin"] : "";
            $PersId          = isset($param["PersId"]) ? $param["PersId"] : "";
            $EstudId         = isset($param["EstudId"]) ? $param["EstudId"] : "";
            $TipPagId        = isset($param["TipPagId"]) ? $param["TipPagId"] : "";
            $IngCredDepen    = isset($param["IngCredDepen"]) ? $param["IngCredDepen"] : "";
            $IngCredDepenKey = isset($param["IngCredDepenKey"]) ? $param["IngCredDepenKey"] : "";
            $ConcepReqId     = isset($param["ConcepReqId"]) ? $param["ConcepReqId"] : "";
            $ConcepImptId    = isset($param["ConcepImptId"]) ? $param["ConcepImptId"] : "";
            $ConcepId        = isset($param["ConcepId"]) ? $param["ConcepId"] : "";
            $DepenId         = isset($param["DepenId"]) ? $param["DepenId"] : "";
            $EspeDetId       = isset($param["EspeDetId"]) ? $param["EspeDetId"] : "";
            $SessKey     = isset($param["SessKey"]) ? $param["SessKey"] : "";
            $MenuId      = isset($param["MenuId"]) ? $param["MenuId"] : "";
            $TypeRecord  = isset($param["TypeRecord"]) ? $param["TypeRecord"] : "";
            $TypeQuery   = isset($param["TypeQuery"]) ? $param["TypeQuery"] : "";
            $OrderBy     = isset($param["OrderBy"]) ? $param["OrderBy"] : "";
            $RecordLimit = isset($param["RecordLimit"]) ? $param["RecordLimit"] : "";
            $RecordStart = isset($param["RecordStart"]) ? $param["RecordStart"] : "";
        }
        $_records = \DB::select("exec tre.[ingresos_sp_select] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?",array($IngId,$IngKey,$UniEjeId,$FilId,$DocId,$DocSerie,$DocNro,$FechaIni,$FechaFin,$PersId,$EstudId,$TipPagId,$IngCredDepen,$IngCredDepenKey,$ConcepReqId,$ConcepImptId,$ConcepId,$DepenId,$EspeDetId,$SessKey,$MenuId,$TypeRecord,$TypeQuery,$OrderBy,$RecordLimit,$RecordStart));
        return response()->json( $_records );
    }

    public function tre_ingresos_update(Request $data){
        $TypeEdit   = 1; //$data->get("TypeEdit");
        $IngId      = 0; //$data->get("IngId");
        $IngKey     = $data->get("IngKey");
        $UniEjeId   = 1230; //$data->get("UniEjeId");
        $FilId      = 0; //$data->get("FilId");
        $DocId      = $data->get("DocId");
        $DocSerie   = $data->get("DocSerie");
        $DocNro     = 0; //$data->get("DocNro");
        $DocFecha   = $data->get("DocFecha");
        $PersId     = $data->get("PersId");
        $EstudId    = $data->get("EstudId");
        $IngImpt    = $data->get("IngImpt");
        $IngObserv  = $data->get("IngObserv");
        $Pago11     = $data->get("Pago11");
        $CueBancId  = $data->get("CueBancId");
        $Pago15     = $data->get("Pago15");
        $OperFecha  = $data->get("OperFecha");
        $OperNro    = $data->get("OperNro");
        $json       = $data->get("data");
        $SessKey = $data->get("SessKey");
        $MenuId  = $data->get("MenuId");

        $_id = \DB::select('exec tre.[ingresos_sp_update] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',array($TypeEdit,$IngId,$IngKey,$UniEjeId,$FilId,$DocId,$DocSerie,$DocNro,$DocFecha,$PersId,$EstudId,$IngImpt,$IngObserv,$Pago11,$CueBancId,$Pago15,$OperFecha,$OperNro,($json),$SessKey,$MenuId));
        return $_id;
    }
}