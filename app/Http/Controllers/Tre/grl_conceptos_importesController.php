<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class grl_conceptos_importesController extends Controller{
    public function grl_conceptos_importes_select(Request $data, $param = ""){
        if ( $param == "" ) {
            $ConcepImptId        = ($data->get("ConcepImptId") == "" ? 0 : $data->get("ConcepImptId"));
            $ConcepImptKey       = ($data->get("ConcepImptKey") == "" ? NULL : $data->get("ConcepImptKey"));
            $ConcepReqId         = $data->get("ConcepReqId");
            $ConcepReqNombrex    = ($data->get("ConcepReqNombrex") == "" ? NULL : $data->get("ConcepReqNombrex"));
            $ConcepReqNombrey    = ($data->get("ConcepReqNombrey") == "" ? NULL : $data->get("ConcepReqNombrey"));
            $DepenId             = $data->get("DepenId");
            $EspeDetId           = $data->get("EspeDetId");
            $PlanCtblId          = $data->get("PlanCtblId");
            $TipAproxImptId      = $data->get("TipAproxImptId");
            $ConcepReqOnlyEstud  = $data->get("ConcepReqOnlyEstud");
            $ConcepReqModPreUni  = $data->get("ConcepReqModPreUni");
            $ConcepReqkey        = ($data->get("ConcepReqkey") == "" ? NULL : $data->get("ConcepReqkey"));
            $ConcepId            = $data->get("ConcepId");
            $TipConcepReqId      = $data->get("TipConcepReqId");
            $ConcepReqParent     = $data->get("ConcepReqParent");
            $ConcepReqNombre     = $data->get("ConcepReqNombre");
            $ConcepReqCode       = $data->get("ConcepReqCode");
            $ConcepReqEstado     = $data->get("ConcepReqEstado");
            $Concepkey           = $data->get("Concepkey");
            $ConcepDepen         = $data->get("ConcepDepen");
            $DocGestId           = $data->get("DocGestId");
            $UniEjeId            = $data->get("UniEjeId");
            $TipDocGestId        = $data->get("TipDocGestId");
            $DocGestYear         = $data->get("DocGestYear");
            $Depenkey            = $data->get("Depenkey");
            $SessKey     = $data->get("SessKey");
            $MenuId      = $data->get("MenuId");
            $TypeRecord  = $data->get("TypeRecord"); 
            $TypeQuery   = $data->get("TypeQuery"); 
            $OrderBy     = $data->get("OrderBy");
            $RecordLimit = $data->get("RecordLimit");
            $RecordStart = $data->get("RecordStart");
        } else {
            $ConcepImptId        = isset($param["ConcepImptId"]) ? $param["ConcepImptId"] : "";
            $ConcepImptKey       = isset($param["ConcepImptKey"]) ? $param["ConcepImptKey"] : "";
            $ConcepReqId         = isset($param["ConcepReqId"]) ? $param["ConcepReqId"] : "";
            $ConcepReqNombrex    = "";
            $ConcepReqNombrey    = "";
            $DepenId             = isset($param["DepenId"]) ? $param["DepenId"] : "";
            $EspeDetId           = isset($param["EspeDetId"]) ? $param["EspeDetId"] : "";
            $PlanCtblId          = "";
            $TipAproxImptId      = "";
            $ConcepReqOnlyEstud  = "";
            $ConcepReqModPreUni  = "";
            $ConcepReqkey        = "";
            $ConcepId            = "";
            $TipConcepReqId      = "";
            $ConcepReqParent     = "";
            $ConcepReqNombre     = "";
            $ConcepReqCode       = "";
            $ConcepReqEstado     = "";
            $Concepkey           = "";
            $ConcepDepen         = "";
            $DocGestId           = "";
            $UniEjeId            = "";
            $TipDocGestId        = "";
            $DocGestYear         = "";
            $Depenkey            = isset($param["DepenKey"]) ? $param["DepenKey"] : "";
            $SessKey     = isset($param["SessKey"]) ? $param["SessKey"] : "";
            $MenuId      = isset($param["MenuId"]) ? $param["MenuId"] : "";
            $TypeRecord  = isset($param["TypeRecord"]) ? $param["TypeRecord"] : "";
            $TypeQuery   = isset($param["TypeQuery"]) ? $param["TypeQuery"] : "";
            $OrderBy     = isset($param["OrderBy"]) ? $param["OrderBy"] : "";
            $RecordLimit = isset($param["RecordLimit"]) ? $param["RecordLimit"] : "";
            $RecordStart = isset($param["RecordStart"]) ? $param["RecordStart"] : "";
        }
        $_records = \DB::select('exec grl.[conceptos_importes_sp_select] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', array($ConcepImptId,$ConcepImptKey,$ConcepReqId,$ConcepReqNombrex,$ConcepReqNombrey,$DepenId,$EspeDetId,$PlanCtblId,$TipAproxImptId,$ConcepReqOnlyEstud,$ConcepReqModPreUni,$ConcepReqkey,$ConcepId,$TipConcepReqId,$ConcepReqParent,$ConcepReqNombre,$ConcepReqCode,$ConcepReqEstado,$Concepkey,$ConcepDepen,$DocGestId,$UniEjeId,$TipDocGestId,$DocGestYear,$Depenkey,$SessKey,$MenuId,$TypeRecord,$TypeQuery,$OrderBy,$RecordLimit,$RecordStart));
        return response()->json($_records);
    }

    public function grl_conceptos_importes_update(Request $data){
        $TypeEdit            = ($data->get("ConcepImptId")*1 > 0 ? 2 : 1);
        $ConcepImptId        = $data->get("ConcepImptId");
        $ConcepImptKey       = $data->get("ConcepImptKey");
        $ConcepReqCode       = $data->get("ConcepReqCode");
        $ConcepReqObserv     = $data->get("ConcepReqObserv");
        $ConcepReqEstado     = $data->get("ConcepReqEstado");
        $ConcepReqNombrex    = $data->get("ConcepReqNombrex");
        $ConcepReqNombrey    = $data->get("ConcepReqNombrey");
        $DepenId             = $data->get("DepenId");
        $EspeDetId           = $data->get("EspeDetId");
        $ConcepReqPuit       = $data->get("ConcepReqPuit");
        $ConcepReqImpt       = $data->get("ConcepReqImpt");
        $TipAproxImptId      = $data->get("TipAproxImptId");
        $ConcepReqDec        = $data->get("ConcepReqDec");
        $ConcepReqOnlyEstud  = $data->get("ConcepReqOnlyEstud");
        $ConcepEnlacId       = $data->get("ConcepEnlacId");
        $ConcepReqOnlyEstudTram = $data->get("ConcepReqOnlyEstudTram");
        $ConcepReqModPreUni     = $data->get("ConcepReqModPreUni");
        $ConcepReqPrintProg     = $data->get("ConcepReqPrintProg");
        $ConcepReqPrintDepen    = $data->get("ConcepReqPrintDepen");
        $SessKey = $data->get("SessKey");
        $MenuId  = $data->get("MenuId");

        $_id = \DB::select('exec grl.[conceptos_importes_sp_update] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',array($TypeEdit,$ConcepImptId,$ConcepImptKey,$ConcepReqCode,$ConcepReqObserv,$ConcepReqEstado,$ConcepReqNombrex,$ConcepReqNombrey,$DepenId,$EspeDetId,$ConcepReqPuit,$ConcepReqImpt,$TipAproxImptId,$ConcepReqDec,$ConcepReqOnlyEstud,$ConcepEnlacId,$ConcepReqOnlyEstudTram,$ConcepReqModPreUni,$ConcepReqPrintProg,$ConcepReqPrintDepen,$SessKey,$MenuId));
        return $_id;
    }
}