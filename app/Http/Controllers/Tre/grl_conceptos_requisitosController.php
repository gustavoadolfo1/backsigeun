<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class grl_conceptos_requisitosController extends Controller{
    public function grl_conceptos_requisitos_select(Request $data, $param = ""){
        $ConcepReqId     = $data->get("ConcepReqId");
        $ConcepReqKey    = $data->get("ConcepReqKey");
        $ConcepId        = $data->get("ConcepId");
        $TipConcepReqId  = $data->get("TipConcepReqId");
        $ConcepReqParent = $data->get("ConcepReqParent");
        $ConcepReqNombre = $data->get("ConcepReqNombre");
        $ConcepReqAbrev  = $data->get("ConcepReqAbrev");
        $ConcepReqCode   = $data->get("ConcepReqCode");
        $ConcepReqOrden  = $data->get("ConcepReqOrden");
        $DepenId         = $data->get("DepenId");
        $EspeDetId       = $data->get("EspeDetId");
        $ConcepReqEstado = $data->get("ConcepReqEstado");
        $ConcepKey       = $data->get("ConcepKey");
        $DocGestId       = $data->get("DocGestId");
        $UniEjeId        = $data->get("UniEjeId");
        $TipDocGestId    = $data->get("TipDocGestId");
        $DocGestYear     = $data->get("DocGestYear");
        $SessKey     = $data->get("SessKey");
        $MenuId      = $data->get("MenuId");
        $TypeRecord  = $data->get("TypeRecord"); 
        $TypeQuery   = $data->get("TypeQuery"); 
        $OrderBy     = $data->get("OrderBy");
        $RecordLimit = $data->get("RecordLimit");
        $RecordStart = $data->get("RecordStart");

        $_records = \DB::select('exec grl.[conceptos_requisitos_sp_select] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',
        array($ConcepReqId,$ConcepReqKey,$ConcepId,$TipConcepReqId,$ConcepReqParent,$ConcepReqNombre,$ConcepReqAbrev,$ConcepReqCode,$ConcepReqOrden,$DepenId,$EspeDetId,$ConcepReqEstado,$ConcepKey,$DocGestId,$UniEjeId,$TipDocGestId,$DocGestYear,$SessKey,$MenuId,$TypeRecord,$TypeQuery,$OrderBy,$RecordLimit,$RecordStart));
        return response()->json( $_records );
    }

    public function grl_conceptos_requisitos_update(Request $data){
        $TypeEdit            = ($data->get("ConcepReqId")*1 > 0 ? 2 : 1);
        $ConcepReqId         = $data->get("ConcepReqId");
        $ConcepReqKey        = $data->get("ConcepReqKey");
        $ConcepId            = $data->get("ConcepId");
        $TipConcepReqId      = $data->get("TipConcepReqId");
        $ConcepReqParent     = $data->get("ConcepReqParent");
        $ConcepReqNombre     = $data->get("ConcepReqNombre");
        $ConcepReqCode       = $data->get("ConcepReqCode");
        $ConcepReqOrden      = $data->get("ConcepReqOrden");
        $ConcepReqOrdenc     = $data->get("ConcepReqOrdenc");
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

        $_id = \DB::select('exec grl.[conceptos_requisitos_sp_update] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',array($TypeEdit,$ConcepReqId,$ConcepReqKey,$ConcepId,$TipConcepReqId,$ConcepReqParent,$ConcepReqNombre,$ConcepReqCode,$ConcepReqOrden,$ConcepReqOrdenc,$ConcepReqObserv,$ConcepReqEstado,$ConcepReqNombrex,$ConcepReqNombrey,$DepenId,$EspeDetId,$ConcepReqPuit,$ConcepReqImpt,$TipAproxImptId,$ConcepReqDec,$ConcepReqOnlyEstud,$ConcepEnlacId,$ConcepReqOnlyEstudTram,$ConcepReqModPreUni,$ConcepReqPrintProg,$ConcepReqPrintDepen,$SessKey,$MenuId));
        return $_id;
    }
}